<?php

namespace App\Support\Article;

use App\Models\Article\ArticleRevision;
use Illuminate\Support\Collection;

final class PendingArticleRevisions
{
    /** @return Collection<int, ArticleRevision> */
    public static function all(?array $articleIds = null): Collection
    {
        if ($articleIds === []) {
            return collect();
        }

        return ArticleRevision::query()
            ->when($articleIds !== null, fn ($query) => $query->whereIn('article_id', $articleIds))
            ->orderByDesc('revision_number')
            ->get()
            ->unique('article_id')
            ->filter(fn (ArticleRevision $revision): bool => $revision->submitted_at !== null
                && $revision->reviewed_at === null
                && $revision->status_review === 'P')
            ->values();
    }

    public static function forArticle(string $articleId): ?ArticleRevision
    {
        return self::all([$articleId])->first();
    }
}
