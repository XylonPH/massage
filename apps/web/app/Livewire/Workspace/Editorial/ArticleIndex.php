<?php

namespace App\Livewire\Workspace\Editorial;

use App\Models\Article\Article;
use App\Support\Article\PendingArticleRevisions;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class ArticleIndex extends Component
{
    public function render(): View
    {
        $revisions = PendingArticleRevisions::all();
        $articles = Article::query()
            ->whereIn('_id', $revisions->pluck('article_id')->all())
            ->get()
            ->keyBy(fn (Article $article): string => (string) $article->getKey());

        return view('livewire.workspace.editorial.article-index', [
            'revisions' => $revisions,
            'articles' => $articles,
        ])->title(__('editorial.article_review_queue'));
    }
}
