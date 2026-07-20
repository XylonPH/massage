<?php

namespace Tests\Feature\Article;

use App\Models\AccessAssignment;
use App\Models\Article\Article;
use App\Models\Article\ArticleBody;
use App\Models\Article\ArticleRevision;
use App\Models\Article\Tag;
use App\Models\User;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class ArticleWorkspaceTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpInteractsWithMongoUsers();
        $this->clearArticles();
        AccessAssignment::query()->delete();
    }

    protected function tearDown(): void
    {
        $this->clearArticles();
        AccessAssignment::query()->delete();
        $this->tearDownInteractsWithMongoUsers();
        parent::tearDown();
    }

    public function test_guest_cannot_open_article_workspace(): void
    {
        $this->get('/workspace/article')->assertRedirect('/login');
    }

    public function test_active_verified_member_can_create_sanitized_draft_and_revision(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/workspace/article', $this->validPayload([
            'article_body' => '<h2 onclick="bad()">A useful heading</h2><p>Visible article copy for readers.</p><script>alert(1)</script>',
        ]));

        $article = Article::query()->firstOrFail();
        $body = ArticleBody::query()->where('article_id', (string) $article->getKey())->firstOrFail();
        $response->assertRedirect(route('workspace.article.edit', $article));
        $this->assertSame('D', $article->status_publication);
        $this->assertSame('PVT', $article->visibility_scope);
        $this->assertSame([(string) $user->getKey()], $article->author_user_id_list);
        $this->assertStringNotContainsString('script', $body->article_body);
        $this->assertStringNotContainsString('onclick', $body->article_body);
        $this->assertGreaterThan(0, $body->word_count);
        $this->assertSame(1, ArticleRevision::query()->where('article_id', (string) $article->getKey())->count());
        $this->assertSame(2, Tag::query()->count());
    }

    public function test_editor_is_integrated_with_workspace_and_supports_rich_and_html_modes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/workspace/article/new')
            ->assertOk()
            ->assertSee(__('workspace.nav_articles'))
            ->assertSee('data-editor-mode="visual"', false)
            ->assertSee('data-editor-mode="html"', false)
            ->assertSee('maxlength="255"', false)
            ->assertDontSee('name="scheduled_publish_at"', false);
    }

    public function test_member_can_save_an_anonymous_article_with_an_automatic_slug(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/workspace/article', $this->validPayload([
            'article_title' => 'Automatically Generated Slug',
            'article_slug' => '',
            'is_anonymous' => '1',
        ]))->assertRedirect();

        $article = Article::query()->firstOrFail();
        $this->assertTrue($article->is_anonymous);
        $this->assertSame('automatically-generated-slug', $article->localized('article_slug'));
    }

    public function test_article_scheduling_requires_permission(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/workspace/article', $this->validPayload([
            'scheduled_publish_at' => now()->addDay()->toDateTimeString(),
        ]))->assertSessionHasErrors('scheduled_publish_at');

        $this->assertSame(0, Article::query()->count());
    }

    public function test_editorial_user_can_record_a_future_publication_time(): void
    {
        $user = User::factory()->create();
        AccessAssignment::query()->create([
            'user_id' => (string) $user->getKey(),
            'role_workspace' => 'EAD',
            'permission_code_list' => [],
            'scope_access' => 'GBL',
            'status_access_assignment' => 'ACT',
            'assigned_by_user_id' => (string) $user->getKey(),
            'assignment_reason' => 'Article scheduling test.',
        ]);
        $scheduledAt = now()->addDay()->startOfMinute();

        $this->actingAs($user)->get('/workspace/article/new')
            ->assertOk()
            ->assertSee('name="scheduled_publish_at"', false);
        $this->actingAs($user)->post('/workspace/article', $this->validPayload([
            'scheduled_publish_at' => $scheduledAt->toDateTimeString(),
        ]))->assertRedirect();

        $this->assertTrue(Article::query()->firstOrFail()->scheduled_publish_at->equalTo($scheduledAt));
    }

    public function test_author_can_update_and_submit_latest_revision(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/workspace/article', $this->validPayload());
        $article = Article::query()->firstOrFail();

        $this->actingAs($user)->put('/workspace/article/'.$article->getKey(), $this->validPayload([
            'article_title' => 'Updated Article Title',
            'revision_note' => 'Improved the introduction.',
        ]))->assertRedirect();
        $this->assertSame(2, ArticleRevision::query()->where('article_id', (string) $article->getKey())->count());

        $this->actingAs($user)->post('/workspace/article/'.$article->getKey().'/submit')
            ->assertRedirect(route('workspace.article.submitted'));
        $latest = ArticleRevision::query()->where('article_id', (string) $article->getKey())->orderByDesc('revision_number')->firstOrFail();
        $this->assertNotNull($latest->submitted_at);

        $this->actingAs($user)->put('/workspace/article/'.$article->getKey(), $this->validPayload([
            'article_title' => 'A New Unsubmitted Revision',
        ]))->assertRedirect();
        $this->actingAs($user)->get('/workspace/article/draft')->assertOk()->assertSee('A New Unsubmitted Revision');
        $this->actingAs($user)->get('/workspace/article/submitted')->assertOk()->assertDontSee('A New Unsubmitted Revision');
    }

    public function test_member_cannot_edit_another_authors_article(): void
    {
        $author = User::factory()->create();
        $other = User::factory()->create();
        $this->actingAs($author)->post('/workspace/article', $this->validPayload());
        $article = Article::query()->firstOrFail();

        $this->actingAs($other)->get('/workspace/article/'.$article->getKey().'/edit')->assertForbidden();
        $this->actingAs($other)->put('/workspace/article/'.$article->getKey(), $this->validPayload())->assertForbidden();
    }

    /** @param array<string, mixed> $overrides @return array<string, mixed> */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'article_title' => 'A Complete Article Draft',
            'article_slug' => 'a-complete-article-draft',
            'short_description' => 'A concise description of this useful Massage Nexus article.',
            'type_article_category' => 'FTM',
            'target_audience' => 'C',
            'level_nsfw' => 'N',
            'tags' => 'first massage, spa etiquette',
            'article_body' => '<h2>Before you book</h2><p>This article contains enough visible words to save safely.</p>',
            'source_references' => 'Example Guide | Example Health Agency | https://example.test/guide |',
            'revision_note' => 'Initial test draft.',
            'is_commentable' => '1',
            'is_shareable' => '1',
            'is_anonymous' => '0',
        ], $overrides);
    }

    private function clearArticles(): void
    {
        ArticleRevision::query()->delete();
        ArticleBody::query()->delete();
        Article::query()->delete();
        Tag::query()->delete();
    }
}
