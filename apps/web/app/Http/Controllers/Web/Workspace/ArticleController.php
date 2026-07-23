<?php

namespace App\Http\Controllers\Web\Workspace;

use App\Actions\Article\SaveArticleDraft;
use App\Actions\Media\StoreUploadedArticleImage;
use App\Enums\ArticleAudience;
use App\Enums\ArticleCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Article\SaveArticleRequest;
use App\Models\Article\Article;
use App\Models\Article\ArticleBody;
use App\Models\Article\ArticleRevision;
use App\Models\Article\Tag;
use App\Models\Media\MediaImage;
use App\Models\User;
use App\Support\Article\ArticleLanguage;
use App\Support\Article\PendingArticleRevisions;
use App\Support\Workspace\WorkspaceAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use MongoDB\BSON\Regex;

class ArticleController extends Controller
{
    private const MINIMUM_SUBMISSION_WORDS = 300;

    /** @var array<string, array{collection: string, label_fields: list<string>, search_fields: list<string>}> */
    private const LOOKUP_TYPES = [
        'article' => ['collection' => 'article_main', 'label_fields' => ['article_title'], 'search_fields' => ['article_title.eng.text', 'article_title.eng']],
        'organization' => ['collection' => 'organization_main', 'label_fields' => ['organization_name', 'display_name', 'name'], 'search_fields' => ['organization_name.eng.text', 'organization_name.eng', 'display_name.eng.text', 'display_name.eng', 'name.eng.text', 'name.eng']],
        'establishment' => ['collection' => 'establishment_main', 'label_fields' => ['display_name'], 'search_fields' => ['display_name.eng.text', 'display_name.eng']],
        'practitioner' => ['collection' => 'practitioner_main', 'label_fields' => ['practitioner_name'], 'search_fields' => ['practitioner_name.eng.text', 'practitioner_name.eng']],
        'service' => ['collection' => 'service_main', 'label_fields' => ['service_name'], 'search_fields' => ['service_name.eng.text', 'service_name.eng']],
        'product' => ['collection' => 'product_main', 'label_fields' => ['product_name', 'display_name', 'name'], 'search_fields' => ['product_name.eng.text', 'product_name.eng', 'display_name.eng.text', 'display_name.eng', 'name.eng.text', 'name.eng']],
    ];

    public function drafts(Request $request): View
    {
        return $this->index($request, 'draft');
    }

    public function submitted(Request $request): View
    {
        return $this->index($request, 'submitted');
    }

    public function published(Request $request): View
    {
        return $this->index($request, 'published');
    }

    public function index(Request $request, ?string $status = null): View
    {
        $userId = (string) $request->user()->getKey();
        $submittedIds = PendingArticleRevisions::all()
            ->pluck('article_id')
            ->map(static fn (mixed $id): string => (string) $id)
            ->values()
            ->all();
        $query = $this->ownedArticles($userId);

        if ($status === 'submitted') {
            $page = LengthAwarePaginator::resolveCurrentPage();
            $submittedArticles = collect($submittedIds)
                ->map(fn (string $articleId): ?Article => Article::query()->find($articleId))
                ->filter(fn (?Article $article): bool => $article !== null && $this->isOwner($article, $userId))
                ->sortByDesc(fn (Article $article) => $article->updated_at)
                ->values();
            $articles = new LengthAwarePaginator(
                $submittedArticles->forPage($page, 15)->values(),
                $submittedArticles->count(),
                15,
                $page,
                ['path' => $request->url(), 'query' => $request->query()],
            );

            return view('workspace.article.index', [
                'articles' => $articles,
                'status' => $status,
                'submittedArticleIds' => $submittedIds,
            ]);
        }

        match ($status) {
            'draft' => $submittedIds === []
                ? $query->whereSparseDefault('status_publication', 'D')
                : $query->whereSparseDefault('status_publication', 'D')->whereNotIn('_id', $submittedIds),
            'published' => $query->where('status_publication', 'P'),
            default => null,
        };

        return view('workspace.article.index', [
            'articles' => $query->orderByDesc('updated_at')->paginate(15),
            'status' => $status,
            'submittedArticleIds' => $submittedIds,
        ]);
    }

    public function create(Request $request, WorkspaceAccess $workspaceAccess): View
    {
        return view('workspace.article.editor', $this->editorData(
            viewer: $request->user(),
            canSchedule: $workspaceAccess->can($request->user(), 'article.schedule'),
        ));
    }

    public function lookup(Request $request, string $type): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
            'exclude' => ['nullable', 'string', 'size:16'],
        ]);
        $term = trim($validated['q']);

        if ($type === 'user') {
            $users = User::query()
                ->where('status_account', 'ACT')
                ->where('status_membership', 'ACT')
                ->where(function (Builder $query) use ($term): void {
                    $query->where('username', 'like', "%{$term}%")
                        ->orWhere('display_name', 'like', "%{$term}%");
                })
                ->orderBy('username')
                ->limit(20)
                ->get();

            return response()->json(['results' => $users->map(fn (User $user): array => [
                'id' => (string) $user->getKey(),
                'label' => '@'.$user->username.' — '.$user->publicName(),
                'display_name' => $user->publicName(),
            ])->values()]);
        }

        abort_unless(array_key_exists($type, self::LOOKUP_TYPES), 404);
        $lookup = self::LOOKUP_TYPES[$type];
        $regex = new Regex(preg_quote($term), 'i');
        $query = DB::connection('mongodb')->table($lookup['collection'])
            ->where(function ($query): void {
                $query->where('status_record_lifecycle', 'ACT')->orWhereNull('status_record_lifecycle');
            })
            ->where(function ($query) use ($lookup, $regex): void {
                foreach ($lookup['search_fields'] as $index => $field) {
                    $index === 0
                        ? $query->where($field, 'regex', $regex)
                        : $query->orWhere($field, 'regex', $regex);
                }
            });

        if (filled($validated['exclude'] ?? null)) {
            $query->where('_id', '!=', $validated['exclude']);
        }

        $results = $query->limit(20)->get()->map(function (mixed $record) use ($lookup): array {
            $values = $record instanceof Model ? $record->getAttributes() : (array) $record;
            $id = (string) ($values['_id'] ?? $values['id'] ?? '');
            $label = '';
            foreach ($lookup['label_fields'] as $field) {
                $label = $this->localizedLabel($values[$field] ?? null);
                if ($label !== '') {
                    break;
                }
            }

            return ['id' => $id, 'label' => $label !== '' ? $label : $id];
        })->filter(fn (array $result): bool => $result['id'] !== '')->values();

        return response()->json(['results' => $results]);
    }

    public function store(SaveArticleRequest $request, SaveArticleDraft $save): RedirectResponse
    {
        $article = $save->execute($request->validated(), $request->user());

        return redirect()->route('workspace.article.edit', $article)
            ->with('status', __('article.draft_saved'));
    }

    public function storeMedia(Request $request, Article $article, StoreUploadedArticleImage $store): JsonResponse
    {
        $this->authorizeOwner($request, $article);

        $existingCount = MediaImage::query()->where('related_article_id_list', (string) $article->getKey())->count();
        abort_if($existingCount >= 10, 422, __('article.media_limit_reached'));

        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,webp', 'max:5120', 'dimensions:max_width=4000,max_height=4000'],
            'alt_text' => ['required', 'string', 'max:255'],
        ]);

        $image = $store->execute($validated['image'], $validated['alt_text'], $article, $request->user());

        return response()->json([
            'id' => (string) $image->getKey(),
            'url' => route('media.image.show', ['media_image' => $image->getKey()]),
            'thumbnail_url' => route('media.image.thumbnail', ['media_image' => $image->getKey()]),
        ]);
    }

    public function edit(Request $request, Article $article, WorkspaceAccess $workspaceAccess): View
    {
        $this->authorizeOwner($request, $article);
        $body = ArticleBody::query()
            ->where('article_id', (string) $article->getKey())
            ->where('language_id', (int) $article->language_original_id)
            ->firstOrFail();

        return view('workspace.article.editor', $this->editorData(
            viewer: $request->user(),
            article: $article,
            body: $body,
            canSchedule: $workspaceAccess->can($request->user(), 'article.schedule'),
        ));
    }

    public function update(SaveArticleRequest $request, Article $article, SaveArticleDraft $save): RedirectResponse
    {
        $this->authorizeOwner($request, $article);
        abort_if($article->status_publication === 'P', 409, __('article.published_read_only'));

        $save->execute($request->validated(), $request->user(), $article);

        return back()->with('status', __('article.draft_saved'));
    }

    public function submit(Request $request, Article $article): RedirectResponse
    {
        $this->authorizeOwner($request, $article);
        abort_if($article->status_publication === 'P', 409, __('article.published_read_only'));
        abort_if(PendingArticleRevisions::forArticle((string) $article->getKey()), 409, __('article.already_submitted'));

        $body = ArticleBody::query()
            ->where('article_id', (string) $article->getKey())
            ->where('language_id', (int) $article->language_original_id)
            ->firstOrFail();
        if ($body->word_count < self::MINIMUM_SUBMISSION_WORDS) {
            return back()->withErrors([
                'article_body' => __('article.submission_minimum_words', [
                    'minimum' => self::MINIMUM_SUBMISSION_WORDS,
                    'current' => $body->word_count,
                ]),
            ]);
        }
        $revision = ArticleRevision::query()
            ->where('article_body_id', (string) $body->getKey())
            ->orderByDesc('revision_number')
            ->firstOrFail();
        $revision->forceFill([
            'submitted_at' => now(),
            'submitted_by_user_id' => (string) $request->user()->getKey(),
            'status_review' => 'P',
        ]);
        $revision->unset(['reviewed_at', 'reviewed_by_user_id', 'review_note', 'approved_at', 'approved_by_user_id']);
        $revision->save();

        $body->forceFill(['status_review' => 'P']);
        $body->unset(['reviewed_at', 'reviewed_by_user_id', 'approved_at', 'approved_by_user_id']);
        $body->save();

        $article->forceFill(['status_review' => 'P'])->save();

        return redirect()->route('workspace.article.submitted')->with('status', __('article.submitted_for_review'));
    }

    public function unpublish(Request $request, Article $article): RedirectResponse
    {
        $this->authorizeOwner($request, $article);
        abort_unless($article->status_publication === 'P', 409);

        $article->forceFill([
            'status_publication' => 'U',
            'visibility_scope' => 'PVT',
            'updated_by_user_id' => (string) $request->user()->getKey(),
        ])->save();

        return redirect()->route('workspace.article.edit', $article)
            ->with('status', __('article.unpublished_successfully'));
    }

    public function revisions(Request $request, Article $article): View
    {
        $this->authorizeOwner($request, $article);

        $revisionQuery = ArticleRevision::query()
            ->where('article_id', (string) $article->getKey())
            ->orderByDesc('revision_number');
        $availableRevisions = (clone $revisionQuery)->get();
        $selectedRevision = $availableRevisions->first();

        if ($request->query->has('revision')) {
            $selectedRevision = $availableRevisions->first(
                fn (ArticleRevision $revision): bool => $revision->revision_number === $request->integer('revision')
            );
            abort_unless($selectedRevision, 404);
        }

        $comparisonRevision = $selectedRevision
            ? $availableRevisions->first(
                fn (ArticleRevision $revision): bool => $revision->revision_number < $selectedRevision->revision_number
            )
            : null;

        if ($request->query->has('compare')) {
            $comparisonRevision = $request->query('compare') === ''
                ? null
                : $availableRevisions->first(
                    fn (ArticleRevision $revision): bool => $revision->revision_number === $request->integer('compare')
                );
            abort_if($request->query('compare') !== '' && ! $comparisonRevision, 404);
        }

        if ($comparisonRevision?->revision_number === $selectedRevision?->revision_number) {
            $comparisonRevision = null;
        }

        return view('workspace.article.revisions', [
            'article' => $article,
            'revisions' => $revisionQuery->paginate(20),
            'availableRevisions' => $availableRevisions,
            'selectedRevision' => $selectedRevision,
            'comparisonRevision' => $comparisonRevision,
        ]);
    }

    /** @return array<string, mixed> */
    private function editorData(
        User $viewer,
        ?Article $article = null,
        ?ArticleBody $body = null,
        bool $canSchedule = false,
    ): array {
        $tags = $article
            ? Tag::query()->whereIn('_id', $article->tag_id_list ?? [])->get()->map(fn (Tag $tag) => $tag->localized('tag_title'))->implode(', ')
            : '';
        $legacyAuthorIds = $article?->author_user_id_list ?? [];
        $authorCredits = $article?->author_credit_list;
        if (! is_array($authorCredits) || $authorCredits === []) {
            $authorCredits = $article
                ? User::query()->whereIn('_id', $legacyAuthorIds)->get()->map(fn (User $user): array => [
                    'user_id' => (string) $user->getKey(),
                    'display_name' => $user->publicName(),
                ])->values()->all()
                : [[
                    'user_id' => (string) $viewer->getKey(),
                    'display_name' => $viewer->publicName(),
                ]];
        }
        $storedOwnerIds = $article?->article_owner_user_id_list;
        $ownerIds = is_array($storedOwnerIds) && $storedOwnerIds !== []
            ? $storedOwnerIds
            : ($legacyAuthorIds ?: [(string) $viewer->getKey()]);
        $selectedUserIds = array_values(array_unique([
            ...$ownerIds,
            ...collect($authorCredits)->pluck('user_id')->filter()->all(),
            ...collect(old('author_credit_list', []))->pluck('user_id')->filter()->all(),
            ...collect(old('article_owner_user_id_list', []))->filter()->all(),
        ]));
        $users = $selectedUserIds === []
            ? collect()
            : User::query()->whereIn('_id', $selectedUserIds)->get();

        return [
            'article' => $article,
            'body' => $body,
            'tags' => $tags,
            'sources' => $article?->source_reference_list ?? [],
            'authorCredits' => $authorCredits,
            'ownerIds' => $ownerIds,
            'userOptions' => $users->map(fn (User $user): array => [
                'id' => (string) $user->getKey(),
                'username' => (string) $user->username,
                'display_name' => $user->publicName(),
                'label' => '@'.$user->username.' — '.$user->publicName(),
            ])->sortBy('username')->values(),
            'languages' => ArticleLanguage::all(),
            'canManageOwnership' => ! $article || (string) $article->created_by_user_id === (string) $viewer->getKey(),
            'relatedOptions' => $this->relatedOptions($article),
            'categories' => ArticleCategory::cases(),
            'audiences' => ArticleAudience::cases(),
            'canSchedule' => $canSchedule,
            'isSubmitted' => $article !== null && PendingArticleRevisions::forArticle((string) $article->getKey()) !== null,
        ];
    }

    private function authorizeOwner(Request $request, Article $article): void
    {
        $userId = (string) $request->user()->getKey();
        abort_unless($this->isOwner($article, $userId), 403);
    }

    private function isOwner(Article $article, string $userId): bool
    {
        $owners = $article->article_owner_user_id_list;
        $owners = is_array($owners) && $owners !== [] ? $owners : ($article->author_user_id_list ?? []);

        return in_array($userId, $owners, true);
    }

    private function ownedArticles(string $userId): Builder
    {
        return Article::query()->where(function (Builder $query) use ($userId): void {
            $query->where('article_owner_user_id_list', $userId)
                ->orWhere(function (Builder $legacy) use ($userId): void {
                    $legacy->where(function (Builder $missingOwners): void {
                        $missingOwners->whereNull('article_owner_user_id_list')
                            ->orWhere('article_owner_user_id_list', []);
                    })
                        ->where('author_user_id_list', $userId);
                });
        });
    }

    /** @return array<string, array<int, array{id: string, label: string}>> */
    private function relatedOptions(?Article $article): array
    {
        return [
            'related_article_id_list' => $this->recordOptions('article_main', ['article_title'], old('related_article_id_list', $article?->related_article_id_list ?? [])),
            'related_organization_id_list' => $this->recordOptions('organization_main', ['organization_name', 'display_name', 'name'], old('related_organization_id_list', $article?->related_organization_id_list ?? [])),
            'related_establishment_id_list' => $this->recordOptions('establishment_main', ['display_name'], old('related_establishment_id_list', $article?->related_establishment_id_list ?? [])),
            'related_practitioner_id_list' => $this->recordOptions('practitioner_main', ['practitioner_name'], old('related_practitioner_id_list', $article?->related_practitioner_id_list ?? [])),
            'related_service_id_list' => $this->recordOptions('service_main', ['service_name'], old('related_service_id_list', $article?->related_service_id_list ?? [])),
            'related_product_id_list' => $this->recordOptions('product_main', ['product_name', 'display_name', 'name'], old('related_product_id_list', $article?->related_product_id_list ?? [])),
        ];
    }

    /** @param array<int, string> $labelFields
     * @param  array<int, string>  $selectedIds
     * @return array<int, array{id: string, label: string}>
     */
    private function recordOptions(string $collection, array $labelFields, array $selectedIds): array
    {
        if ($selectedIds === []) {
            return [];
        }

        $records = DB::connection('mongodb')->table($collection)->whereIn('_id', $selectedIds)->get();

        return $records->map(function (mixed $record) use ($labelFields): array {
            $values = $record instanceof Model
                ? $record->getAttributes()
                : (array) $record;
            $id = (string) ($values['_id'] ?? $values['id'] ?? '');
            $label = '';
            foreach ($labelFields as $field) {
                $label = $this->localizedLabel($values[$field] ?? null);
                if ($label !== '') {
                    break;
                }
            }

            return ['id' => $id, 'label' => $label !== '' ? $label : $id];
        })->filter(fn (array $option): bool => $option['id'] !== '')
            ->sortBy('label', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();
    }

    private function localizedLabel(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }
        if ($value instanceof \Traversable) {
            $value = iterator_to_array($value);
        } elseif (is_object($value)) {
            $value = (array) $value;
        }
        if (! is_array($value)) {
            return '';
        }

        $preferred = $value['eng']['text'] ?? $value['eng'] ?? $value['text'] ?? null;
        if (is_string($preferred)) {
            return $preferred;
        }
        foreach ($value as $candidate) {
            $label = $this->localizedLabel($candidate);
            if ($label !== '') {
                return $label;
            }
        }

        return '';
    }
}
