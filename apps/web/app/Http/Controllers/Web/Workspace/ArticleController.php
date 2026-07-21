<?php

namespace App\Http\Controllers\Web\Workspace;

use App\Actions\Article\SaveArticleDraft;
use App\Enums\ArticleAudience;
use App\Enums\ArticleCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Article\SaveArticleRequest;
use App\Models\Article\Article;
use App\Models\Article\ArticleBody;
use App\Models\Article\ArticleRevision;
use App\Models\Article\Tag;
use App\Models\User;
use App\Support\Article\ArticleLanguage;
use App\Support\Workspace\WorkspaceAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ArticleController extends Controller
{
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
        $articleIds = $this->ownedArticles($userId)
            ->pluck('_id')
            ->all();
        $submittedIds = ArticleRevision::query()
            ->whereIn('article_id', $articleIds)
            ->orderByDesc('revision_number')
            ->get()
            ->unique('article_id')
            ->filter(fn (ArticleRevision $revision): bool => $revision->submitted_at !== null && $revision->status_review === 'P')
            ->pluck('article_id')
            ->values()
            ->all();
        $query = $this->ownedArticles($userId);

        match ($status) {
            'draft' => $submittedIds === []
                ? $query->where('status_publication', 'D')
                : $query->where('status_publication', 'D')->whereNotIn('_id', $submittedIds),
            'submitted' => $query->whereIn('_id', $submittedIds)->where('status_review', 'P'),
            'published' => $query->where('status_publication', 'P'),
            default => null,
        };

        return view('workspace.article.index', [
            'articles' => $query->orderByDesc('updated_at')->paginate(15),
            'status' => $status,
        ]);
    }

    public function create(Request $request, WorkspaceAccess $workspaceAccess): View
    {
        return view('workspace.article.editor', $this->editorData(
            viewer: $request->user(),
            canSchedule: $workspaceAccess->can($request->user(), 'article.schedule'),
        ));
    }

    public function store(SaveArticleRequest $request, SaveArticleDraft $save): RedirectResponse
    {
        $article = $save->execute($request->validated(), $request->user());

        return redirect()->route('workspace.article.edit', $article)
            ->with('status', __('article.draft_saved'));
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

        $body = ArticleBody::query()
            ->where('article_id', (string) $article->getKey())
            ->where('language_id', (int) $article->language_original_id)
            ->firstOrFail();
        $revision = ArticleRevision::query()
            ->where('article_body_id', (string) $body->getKey())
            ->orderByDesc('revision_number')
            ->firstOrFail();
        $revision->forceFill([
            'submitted_at' => now(),
            'submitted_by_user_id' => (string) $request->user()->getKey(),
            'status_review' => 'P',
        ])->save();
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

        return view('workspace.article.revisions', [
            'article' => $article,
            'revisions' => ArticleRevision::query()
                ->where('article_id', (string) $article->getKey())
                ->orderByDesc('revision_number')
                ->paginate(20),
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
        ]));
        $users = User::query()
            ->where('status_account', 'ACT')
            ->where('status_membership', 'ACT')
            ->orderBy('username')
            ->get();
        if ($selectedUserIds !== []) {
            $users = $users->merge(User::query()->whereIn('_id', $selectedUserIds)->get())->unique('_id');
        }

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
            ])->sortBy('username')->values(),
            'languages' => ArticleLanguage::all(),
            'canManageOwnership' => ! $article || (string) $article->created_by_user_id === (string) $viewer->getKey(),
            'relatedOptions' => $this->relatedOptions($article),
            'categories' => ArticleCategory::cases(),
            'audiences' => ArticleAudience::cases(),
            'canSchedule' => $canSchedule,
        ];
    }

    private function authorizeOwner(Request $request, Article $article): void
    {
        $userId = (string) $request->user()->getKey();
        $owners = $article->article_owner_user_id_list;
        $owners = is_array($owners) && $owners !== [] ? $owners : ($article->author_user_id_list ?? []);

        abort_unless(in_array($userId, $owners, true), 403);
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
            'related_article_id_list' => $this->recordOptions('article_main', ['article_title'], $article?->related_article_id_list ?? [], $article ? (string) $article->getKey() : null),
            'related_organization_id_list' => $this->recordOptions('organization_main', ['organization_name', 'display_name', 'name'], $article?->related_organization_id_list ?? []),
            'related_establishment_id_list' => $this->recordOptions('establishment_main', ['display_name'], $article?->related_establishment_id_list ?? []),
            'related_practitioner_id_list' => $this->recordOptions('practitioner_main', ['practitioner_name'], $article?->related_practitioner_id_list ?? []),
            'related_service_id_list' => $this->recordOptions('service_main', ['service_name'], $article?->related_service_id_list ?? []),
            'related_product_id_list' => $this->recordOptions('product_main', ['product_name', 'display_name', 'name'], $article?->related_product_id_list ?? []),
        ];
    }

    /** @param array<int, string> $labelFields
     * @param  array<int, string>  $selectedIds
     * @return array<int, array{id: string, label: string}>
     */
    private function recordOptions(string $collection, array $labelFields, array $selectedIds, ?string $excludeId = null): array
    {
        $query = DB::connection('mongodb')->table($collection)->where('status_record_lifecycle', 'ACT');
        if ($excludeId) {
            $query->where('_id', '!=', $excludeId);
        }
        $records = $query->get();
        if ($selectedIds !== []) {
            $selected = DB::connection('mongodb')->table($collection)->whereIn('_id', $selectedIds)->get();
            $records = $records->merge($selected)->unique(
                fn (mixed $record): string => (string) data_get($record, '_id', data_get($record, 'id', ''))
            );
        }

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
