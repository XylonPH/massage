<?php

namespace App\Livewire\Workspace\Editorial;

use App\Models\Article\Article;
use App\Models\Article\ArticleBody;
use App\Models\Article\ArticleRevision;
use App\Support\Article\PendingArticleRevisions;
use App\Support\Workspace\WorkspaceAccess;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class ArticleReview extends Component
{
    public string $article;

    public string $reviewNote = '';

    public function mount(string $article): void
    {
        $this->authorizeEditorialAccess();
        abort_unless(PendingArticleRevisions::forArticle($article), 404);
        $this->article = $article;
    }

    public function approve(): void
    {
        $this->decide('A');
    }

    public function requestChanges(): void
    {
        $this->validate(['reviewNote' => ['required', 'string', 'max:2000']]);
        $this->decide('N');
    }

    public function reject(): void
    {
        $this->validate(['reviewNote' => ['required', 'string', 'max:2000']]);
        $this->decide('R');
    }

    private function decide(string $decision): void
    {
        $this->authorizeEditorialAccess();
        $revision = PendingArticleRevisions::forArticle($this->article);
        abort_unless($revision, 409, __('editorial.article_already_reviewed'));

        $article = Article::query()->findOrFail($this->article);
        $body = ArticleBody::query()->findOrFail((string) $revision->article_body_id);
        $reviewerId = (string) Auth::id();
        $reviewedAt = now();

        $revision->forceFill([
            'status_review' => $decision,
            'review_note' => trim($this->reviewNote) ?: null,
            'reviewed_at' => $reviewedAt,
            'reviewed_by_user_id' => $reviewerId,
        ]);
        $body->forceFill([
            'status_review' => $decision,
            'reviewed_at' => $reviewedAt,
            'reviewed_by_user_id' => $reviewerId,
        ]);
        $article->forceFill([
            'status_review' => $decision,
            'reviewer_user_id_list' => array_values(array_unique([
                ...($article->reviewer_user_id_list ?? []),
                $reviewerId,
            ])),
            'updated_by_user_id' => $reviewerId,
        ]);

        if ($decision === 'A') {
            $revision->forceFill(['approved_at' => $reviewedAt, 'approved_by_user_id' => $reviewerId]);
            $body->forceFill(['approved_at' => $reviewedAt, 'approved_by_user_id' => $reviewerId]);
            $article->forceFill([
                'status_publication' => 'P',
                'visibility_scope' => 'PUB',
                'published_at' => $reviewedAt,
                'published_by_user_id' => $reviewerId,
            ]);
        }

        $revision->save();
        $body->save();
        $article->save();

        session()->flash('editorial_status', __('editorial.article_decision_saved'));
        $this->redirectRoute('workspace.editorial.article.index', navigate: true);
    }

    private function authorizeEditorialAccess(): void
    {
        $user = Auth::user();
        abort_unless($user && app(WorkspaceAccess::class)->can($user, 'workspace.editorial.access'), 403);
    }

    public function render(): View
    {
        $article = Article::query()->findOrFail($this->article);
        $revision = PendingArticleRevisions::forArticle($this->article);
        abort_unless($revision instanceof ArticleRevision, 404);

        return view('livewire.workspace.editorial.article-review', [
            'record' => $article,
            'revision' => $revision,
        ])->title(__('editorial.review_article'));
    }
}
