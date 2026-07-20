<?php

namespace App\Http\Controllers;

use App\Enums\ArticleCategory;
use App\Models\Article\Article;
use App\Support\Demo\SampleContent;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'featuredSpas' => SampleContent::featuredSpas(),
            'featuredTherapists' => SampleContent::featuredTherapists(),
            'areas' => SampleContent::areas(),
            'massageTypes' => SampleContent::massageTypes(),
            'articles' => $this->latestArticles(),
            'latestReviews' => SampleContent::latestReviews(),
            'promos' => SampleContent::promos(),
            'wellnessFinds' => SampleContent::wellnessFinds(),
            'trendingSpas' => SampleContent::trendingSpas(),
            'trendingTherapists' => SampleContent::trendingTherapists(),
            'newListings' => SampleContent::newListings(),
            'updatedProfiles' => SampleContent::updatedProfiles(),
            'stats' => SampleContent::stats(),
            'quote' => SampleContent::quote(),
        ]);
    }

    /** @return array<int, array<string, string|null>> */
    private function latestArticles(): array
    {
        $articles = Article::query()
            ->publiclyVisible()
            ->orderByDesc('published_at')
            ->limit(3)
            ->get()
            ->map(function (Article $article, int $index): array {
                $tones = ['from-ink-500 to-ink-800', 'from-leaf-500 to-leaf-800', 'from-ember-500 to-ember-800'];

                return [
                    'slug' => $article->localized('article_slug'),
                    'title' => $article->localized('article_title'),
                    'date' => $article->published_at?->format('M j, Y') ?? '',
                    'category' => ArticleCategory::tryFrom((string) $article->type_article_category)?->label() ?? '',
                    'tone' => $tones[$index % count($tones)],
                ];
            })
            ->all();

        return $articles !== [] ? $articles : SampleContent::latestArticles();
    }
}
