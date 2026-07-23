<?php

namespace App\Http\Controllers\Web\Public;

use App\Enums\ArticleAudience;
use App\Enums\ArticleCategory;
use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\Article\ArticleBody;
use App\Models\Article\Tag;
use App\Models\User;
use App\Support\Article\ArticleContent;
use App\Support\Article\ArticleLanguage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use MongoDB\BSON\Regex;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        return $this->listing($request, Article::query(), __('article.all_articles'));
    }

    public function categoryIndex(Request $request): View
    {
        return $this->listing($request, Article::query(), __('article.browse_categories'));
    }

    public function category(Request $request, string $article_category_slug): View
    {
        $category = ArticleCategory::fromSlug($article_category_slug);
        abort_unless($category, 404);

        return $this->listing(
            $request,
            Article::query()->where('type_article_category', $category->value),
            $category->label(),
            $category->description(),
        );
    }

    public function tagIndex(Request $request): View
    {
        return $this->listing($request, Article::query(), __('article.browse_tags'));
    }

    public function tag(Request $request, string $tag_slug): View
    {
        $tag = Tag::query()
            ->where('tag_slug.eng.text', $tag_slug)
            ->whereSparseDefault('status_record_lifecycle', 'ACT')
            ->firstOrFail();

        return $this->listing(
            $request,
            Article::query()->where('tag_id_list', (string) $tag->getKey()),
            '#'.$tag->localized('tag_title'),
        );
    }

    public function authorIndex(Request $request): View
    {
        return $this->listing($request, Article::query(), __('article.browse_authors'));
    }

    public function author(Request $request, string $author_slug): View
    {
        $author = User::query()->where('username', $author_slug)->firstOrFail();

        return $this->listing(
            $request,
            Article::query()
                ->where('author_user_id_list', (string) $author->getKey())
                ->where(function (Builder $query): void {
                    $query->whereNull('is_anonymous')->orWhere('is_anonymous', false);
                }),
            __('article.articles_by', ['name' => $author->username]),
        );
    }

    public function neuralAgent(Request $request, string $neural_agent_slug): View
    {
        return $this->author($request, $neural_agent_slug);
    }

    public function neuralAgentIndex(Request $request): View
    {
        return $this->listing($request, Article::query(), __('article.browse_neural_agents'));
    }

    public function audienceIndex(Request $request): View
    {
        return $this->listing($request, Article::query(), __('article.browse_audiences'));
    }

    public function audience(Request $request, string $audience_slug): View
    {
        $audience = ArticleAudience::fromSlug($audience_slug);
        abort_unless($audience, 404);

        return $this->listing(
            $request,
            Article::query()->where('target_audience', $audience->value),
            __('article.for_audience', ['audience' => $audience->label()]),
        );
    }

    public function archive(Request $request): View
    {
        return $this->listing($request, Article::query(), __('article.archive'));
    }

    public function search(Request $request): View
    {
        return $this->listing($request, Article::query(), __('article.search_results'));
    }

    public function show(string $article_slug, ArticleContent $content): View
    {
        $article = Article::query()
            ->publiclyVisible()
            ->where(function (Builder $query) use ($article_slug): void {
                foreach (ArticleLanguage::keys() as $index => $languageKey) {
                    $index === 0
                        ? $query->where("article_slug.{$languageKey}.text", $article_slug)
                        : $query->orWhere("article_slug.{$languageKey}.text", $article_slug);
                }
            })
            ->firstOrFail();
        $body = ArticleBody::query()
            ->where('article_id', (string) $article->getKey())
            ->where('language_id', (int) $article->language_original_id)
            ->where('status_review', 'A')
            ->whereSparseDefault('status_record_lifecycle', 'ACT')
            ->firstOrFail();

        Article::query()->where('_id', $article->getKey())->increment('view_count');

        $tags = $this->tagsFor($article);
        $authors = $this->authorsFor($article);
        $relatedIds = array_slice($article->related_article_id_list ?? [], 0, 3);
        $related = $relatedIds === []
            ? collect()
            : Article::query()
                ->publiclyVisible()
                ->whereIn('_id', $relatedIds)
                ->get()
                ->map(fn (Article $item): array => $this->card($item));

        return view('article.show', [
            'article' => $article,
            'body' => $body,
            'renderedBody' => $content->sanitize((string) $body->article_body),
            'category' => ArticleCategory::tryFrom((string) $article->type_article_category),
            'audience' => ArticleAudience::tryFrom((string) $article->target_audience),
            'tags' => $tags,
            'authors' => $authors,
            'relatedArticles' => $related,
        ]);
    }

    private function listing(Request $request, Builder $query, string $heading, ?string $categoryDescription = null): View
    {
        $query->publiclyVisible();
        $search = trim((string) $request->query('q', ''));

        if ($search !== '') {
            $regex = new Regex(preg_quote(mb_substr($search, 0, 100)), 'i');
            $bodyArticleIds = ArticleBody::query()
                ->where('article_plain_text', 'regex', $regex)
                ->limit(250)
                ->pluck('article_id')
                ->all();
            $query->where(function (Builder $query) use ($regex, $bodyArticleIds): void {
                foreach (ArticleLanguage::keys() as $index => $languageKey) {
                    $method = $index === 0 ? 'where' : 'orWhere';
                    $query->{$method}("article_title.{$languageKey}.text", 'regex', $regex)
                        ->orWhere("short_description.{$languageKey}.text", 'regex', $regex);
                }
                if ($bodyArticleIds !== []) {
                    $query->orWhereIn('_id', $bodyArticleIds);
                }
            });
        }

        $articles = $query
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();
        $this->presentPaginator($articles);

        return view('article.index', [
            'articles' => $articles,
            'heading' => $heading,
            'categoryDescription' => $categoryDescription,
            'search' => $search,
            'categories' => ArticleCategory::cases(),
            'audiences' => ArticleAudience::cases(),
            'popularTags' => Tag::query()->whereSparseDefault('status_record_lifecycle', 'ACT')->orderByDesc('usage_count')->limit(18)->get(),
        ]);
    }

    private function presentPaginator(LengthAwarePaginator $articles): void
    {
        $articles->through(fn (Article $article): array => $this->card($article));
    }

    /** @return array<string, mixed> */
    private function card(Article $article): array
    {
        return [
            'id' => (string) $article->getKey(),
            'title' => $article->localized('article_title'),
            'slug' => $article->localized('article_slug'),
            'description' => $article->localized('short_description'),
            'category' => ArticleCategory::tryFrom((string) $article->type_article_category),
            'audience' => ArticleAudience::tryFrom((string) $article->target_audience),
            'published_at' => $article->published_at,
            'reading_duration_visual' => (int) $article->reading_duration_visual,
            'level_nsfw' => $article->level_nsfw,
            'featured_thumbnail_url' => $article->featured_media_image_id
                ? route('media.image.thumbnail', ['media_image' => $article->featured_media_image_id])
                : null,
        ];
    }

    private function tagsFor(Article $article)
    {
        return Tag::query()->whereIn('_id', $article->tag_id_list ?? [])->get();
    }

    private function authorsFor(Article $article)
    {
        if ($article->is_anonymous) {
            return collect();
        }

        $credits = $article->author_credit_list;
        if (is_array($credits) && $credits !== []) {
            $linkedUsers = User::query()
                ->whereIn('_id', collect($credits)->pluck('user_id')->filter()->all())
                ->get()
                ->keyBy(fn (User $user): string => (string) $user->getKey());

            return collect($credits)->map(function (array $credit) use ($linkedUsers): array {
                $user = filled($credit['user_id'] ?? null) ? $linkedUsers->get((string) $credit['user_id']) : null;

                return [
                    'name' => (string) ($credit['display_name'] ?? $user?->publicName() ?? ''),
                    'username' => $user?->username,
                ];
            })->filter(fn (array $credit): bool => $credit['name'] !== '')->values();
        }

        return User::query()->whereIn('_id', $article->author_user_id_list ?? [])->get()
            ->map(fn (User $user): array => ['name' => $user->publicName(), 'username' => $user->username]);
    }
}
