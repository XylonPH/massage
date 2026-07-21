<?php

namespace Tests\Feature\Article;

use App\Models\Article\Article;
use App\Models\Article\ArticleBody;
use App\Models\Article\ArticleRevision;
use App\Models\Article\Tag;
use App\Models\User;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class ArticlePagesTest extends TestCase
{
    use InteractsWithMongoUsers;

    private User $author;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpInteractsWithMongoUsers();
        $this->clearArticles();
        $this->author = User::factory()->create(['username' => 'articleauthor']);
    }

    protected function tearDown(): void
    {
        $this->clearArticles();
        $this->tearDownInteractsWithMongoUsers();
        parent::tearDown();
    }

    public function test_public_article_index_and_detail_render_only_published_approved_articles(): void
    {
        [$published] = $this->createArticle('first-massage-guide', true);
        $this->createArticle('private-draft', false);

        $this->get('/article')
            ->assertOk()
            ->assertSee('First Massage Guide')
            ->assertDontSee('Private Draft');

        $this->get('/article/first-massage-guide')
            ->assertOk()
            ->assertSee('First Massage Guide')
            ->assertSee('Safe article body')
            ->assertSee('Example Health Agency');

        $this->get('/article/private-draft')->assertNotFound();
        $this->assertSame(1, Article::query()->find($published->getKey())->view_count);
    }

    public function test_search_matches_the_generated_plain_text_body(): void
    {
        $this->createArticle('first-massage-guide', true, 'A uniquely searchable contraindication phrase.');

        $this->get('/article/search?q=contraindication')
            ->assertOk()
            ->assertSee('First Massage Guide');
    }

    public function test_category_and_audience_routes_filter_articles(): void
    {
        $this->createArticle('first-massage-guide', true);

        $this->get('/article/category/first-time-massage-and-spa-etiquette')
            ->assertOk()
            ->assertSee('First Massage Guide');
        $this->get('/article/audience/client')
            ->assertOk()
            ->assertSee('First Massage Guide');
        $this->get('/article/category/not-a-category')->assertNotFound();
    }

    public function test_anonymous_article_hides_its_public_author_association(): void
    {
        [$article] = $this->createArticle('first-massage-guide', true);
        $article->forceFill(['is_anonymous' => true])->save();

        $this->get('/article/first-massage-guide')
            ->assertOk()
            ->assertSee(__('article.anonymous_byline'))
            ->assertDontSee($this->author->username);
        $this->get('/article/author/'.$this->author->username)
            ->assertOk()
            ->assertDontSee('First Massage Guide');
    }

    public function test_public_byline_preserves_linked_and_custom_author_credits_in_order(): void
    {
        [$article] = $this->createArticle('first-massage-guide', true);
        $article->forceFill([
            'author_credit_list' => [
                ['user_id' => (string) $this->author->getKey(), 'display_name' => 'Lead Writer'],
                ['user_id' => null, 'display_name' => 'Guest Researcher'],
            ],
        ])->save();

        $this->get('/article/first-massage-guide')
            ->assertOk()
            ->assertSeeInOrder(['Lead Writer', 'Guest Researcher']);
    }

    public function test_public_detail_resolves_the_original_filipino_slug_and_body(): void
    {
        [$article, $body] = $this->createArticle('first-massage-guide', true);
        $article->forceFill([
            'article_title' => ['fil' => ['text' => 'Gabay sa Unang Masahe']],
            'article_slug' => ['fil' => ['text' => 'gabay-sa-unang-masahe']],
            'short_description' => ['fil' => ['text' => 'Isang praktikal na gabay.']],
            'language_original_id' => 3600,
        ])->save();
        $body->forceFill(['language_id' => 3600])->save();

        $this->get('/article/gabay-sa-unang-masahe')
            ->assertOk()
            ->assertSee('Gabay sa Unang Masahe')
            ->assertSee('Safe article body');
    }

    public function test_future_scheduled_article_is_not_public_before_its_time(): void
    {
        [$article] = $this->createArticle('first-massage-guide', true);
        $article->forceFill(['scheduled_publish_at' => now()->addDay()])->save();

        $this->get('/article')->assertOk()->assertDontSee('First Massage Guide');
        $this->get('/article/first-massage-guide')->assertNotFound();
    }

    /** @return array{Article, ArticleBody} */
    private function createArticle(string $slug, bool $published, string $plainText = 'Safe article body for readers.'): array
    {
        $title = $slug === 'private-draft' ? 'Private Draft' : 'First Massage Guide';
        $article = Article::query()->create([
            'article_title' => ['eng' => ['text' => $title]],
            'article_slug' => ['eng' => ['text' => $slug]],
            'short_description' => ['eng' => ['text' => 'A practical test article description.']],
            'language_original_id' => 3049,
            'type_article_category' => 'FTM',
            'target_audience' => 'C',
            'tag_id_list' => [],
            'author_user_id_list' => [(string) $this->author->getKey()],
            'is_anonymous' => false,
            'related_article_id_list' => [],
            'source_reference_list' => [[
                'source_title' => 'Example Source',
                'source_organization' => 'Example Health Agency',
                'source_url' => 'https://example.test/source',
                'publication_identifier' => null,
            ]],
            'reading_duration_visual' => 45,
            'reading_duration_spoken' => 60,
            'view_count' => 0,
            'status_publication' => $published ? 'P' : 'D',
            'status_review' => $published ? 'A' : 'P',
            'visibility_scope' => $published ? 'PUB' : 'PVT',
            'status_record_lifecycle' => 'ACT',
            'published_at' => $published ? now()->subMinute() : null,
        ]);
        $body = ArticleBody::query()->create([
            'article_id' => (string) $article->getKey(),
            'language_id' => 3049,
            'article_body' => '<h2>Safe article body</h2><p>'.$plainText.'</p>',
            'article_plain_text' => $plainText,
            'word_count' => 8,
            'reading_duration_visual' => 45,
            'reading_duration_spoken' => 60,
            'status_review' => $published ? 'A' : 'P',
            'status_record_lifecycle' => 'ACT',
        ]);

        return [$article, $body];
    }

    private function clearArticles(): void
    {
        ArticleRevision::query()->delete();
        ArticleBody::query()->delete();
        Article::query()->delete();
        Tag::query()->delete();
    }
}
