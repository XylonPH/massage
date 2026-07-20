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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $articleIds = Article::query()
            ->where('author_user_id_list', $userId)
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
        $query = Article::query()->where('author_user_id_list', $userId);

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

    public function create(): View
    {
        return view('workspace.article.editor', $this->editorData());
    }

    public function store(SaveArticleRequest $request, SaveArticleDraft $save): RedirectResponse
    {
        $article = $save->execute($request->validated(), $request->user());

        return redirect()->route('workspace.article.edit', $article)
            ->with('status', __('article.draft_saved'));
    }

    public function edit(Request $request, Article $article): View
    {
        $this->authorizeOwner($request, $article);
        $body = ArticleBody::query()
            ->where('article_id', (string) $article->getKey())
            ->where('language_id', 3049)
            ->firstOrFail();

        return view('workspace.article.editor', $this->editorData($article, $body));
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
            ->where('language_id', 3049)
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
    private function editorData(?Article $article = null, ?ArticleBody $body = null): array
    {
        $tags = $article
            ? Tag::query()->whereIn('_id', $article->tag_id_list ?? [])->get()->map(fn (Tag $tag) => $tag->localized('tag_title'))->implode(', ')
            : '';
        $sources = collect($article?->source_reference_list ?? [])->map(fn (array $source): string => implode(' | ', [
            $source['source_title'] ?? '',
            $source['source_organization'] ?? '',
            $source['source_url'] ?? '',
            $source['publication_identifier'] ?? '',
        ]))->implode(PHP_EOL);

        return [
            'article' => $article,
            'body' => $body,
            'tags' => $tags,
            'sources' => $sources,
            'categories' => ArticleCategory::cases(),
            'audiences' => ArticleAudience::cases(),
        ];
    }

    private function authorizeOwner(Request $request, Article $article): void
    {
        abort_unless(in_array((string) $request->user()->getKey(), $article->author_user_id_list ?? [], true), 403);
    }
}
