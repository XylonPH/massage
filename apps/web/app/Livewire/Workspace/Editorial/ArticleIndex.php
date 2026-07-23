<?php

namespace App\Livewire\Workspace\Editorial;

use App\Models\Article\Article;
use App\Models\Article\ArticleBody;
use App\Models\Article\ArticleRevision;
use App\Support\Article\PendingArticleRevisions;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class ArticleIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = 'all';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function deleteArticle(string $id): void
    {
        $article = Article::query()->find($id);
        if (! $article) {
            return;
        }

        ArticleBody::query()->where('article_id', $id)->delete();
        ArticleRevision::query()->where('article_id', $id)->delete();
        $article->delete();

        session()->flash('editorial_status', 'Article and associated content deleted.');
    }

    public function render(): View
    {
        $revisions = PendingArticleRevisions::all();
        $pendingArticleIds = $revisions->pluck('article_id')->map(fn (mixed $id): string => (string) $id)->all();

        $query = Article::query()->orderBy('updated_at', 'desc');

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('article_title.eng', 'like', '%'.$this->search.'%')
                    ->orWhere('short_description.eng', 'like', '%'.$this->search.'%')
                    ->orWhere('article_slug', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter === 'pending') {
            $query->whereIn('_id', $pendingArticleIds);
        } elseif ($this->statusFilter === 'published') {
            $query->where('status_publication', 'P');
        } elseif ($this->statusFilter === 'draft') {
            $query->where('status_publication', 'D')->whereNotIn('_id', $pendingArticleIds);
        } elseif ($this->statusFilter === 'unpublished') {
            $query->where('status_publication', 'U');
        }

        $pendingArticlesMap = Article::query()
            ->whereIn('_id', $pendingArticleIds)
            ->get()
            ->keyBy(fn (Article $article): string => (string) $article->getKey());

        return view('livewire.workspace.editorial.article-index', [
            'articles' => $query->paginate(15),
            'revisions' => $revisions,
            'pendingArticles' => $pendingArticlesMap,
            'pendingCount' => $revisions->count(),
        ])->title(__('editorial.article_review_queue'));
    }
}
