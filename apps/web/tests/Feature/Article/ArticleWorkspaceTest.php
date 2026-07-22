<?php

namespace Tests\Feature\Article;

use App\Livewire\Workspace\Editorial\ArticleReview;
use App\Models\AccessAssignment;
use App\Models\Article\Article;
use App\Models\Article\ArticleBody;
use App\Models\Article\ArticleRevision;
use App\Models\Article\Tag;
use App\Models\User;
use App\Support\Article\PendingArticleRevisions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Livewire;
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
        $this->assertSame([(string) $user->getKey()], $article->article_owner_user_id_list);
        $this->assertSame($user->publicName(), $article->author_credit_list[0]['display_name']);
        $this->assertSame('Example Guide', $article->source_reference_list[0]['source_title']);
        $this->assertStringNotContainsString('script', $body->article_body);
        $this->assertStringNotContainsString('onclick', $body->article_body);
        $this->assertGreaterThan(0, $body->word_count);
        $this->assertSame(1, ArticleRevision::query()->where('article_id', (string) $article->getKey())->count());
        $this->assertSame(2, Tag::query()->count());

        $this->assertArrayNotHasKey('status_publication', $article->getAttributes());
        $this->assertArrayNotHasKey('status_review', $article->getAttributes());
        $this->assertArrayNotHasKey('visibility_scope', $article->getAttributes());
        $this->assertArrayNotHasKey('level_nsfw', $article->getAttributes());
        $this->assertArrayNotHasKey('is_commentable', $article->getAttributes());
        $this->assertArrayNotHasKey('is_shareable', $article->getAttributes());
        $this->assertArrayNotHasKey('status_record_lifecycle', $body->getAttributes());
        $this->assertSame(['text' => 'A Complete Article Draft'], $article->article_title['eng']);
        $tag = Tag::query()->firstOrFail();
        $this->assertSame(['text' => $tag->localized('tag_title')], $tag->tag_title['eng']);
        $this->assertArrayNotHasKey('status_record_lifecycle', $tag->getAttributes());
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
            ->assertSee('data-editor-character-count', false)
            ->assertSee('data-editor-spoken-reading-time', false)
            ->assertSee('data-add-author', false)
            ->assertSee('data-add-source', false)
            ->assertSee(__('article.language_spanish'))
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
            'article_body' => '<h2>Before you arrive</h2><p>This revised article body makes the comparison visible to its author.</p>',
            'revision_note' => 'Improved the introduction.',
        ]))->assertRedirect();
        $this->assertSame(2, ArticleRevision::query()->where('article_id', (string) $article->getKey())->count());

        $this->actingAs($user)->get('/workspace/article/'.$article->getKey().'/revision?revision=2&compare=1')
            ->assertOk()
            ->assertSee('data-revision-comparison', false)
            ->assertSee('This revised article body makes the comparison visible to its author.')
            ->assertSee('This article contains enough visible words to save safely.');

        $this->actingAs($user)->post('/workspace/article/'.$article->getKey().'/submit')
            ->assertRedirect(route('workspace.article.submitted'));
        $latest = ArticleRevision::query()->where('article_id', (string) $article->getKey())->orderByDesc('revision_number')->firstOrFail();
        $this->assertNotNull($latest->submitted_at);
        $this->assertArrayNotHasKey('status_review', $latest->getAttributes());
        $this->assertCount(1, PendingArticleRevisions::all());
        $this->assertSame((string) $article->getKey(), (string) PendingArticleRevisions::all()->first()->article_id);
        $this->actingAs($user)->get('/workspace/article/submitted')
            ->assertOk()
            ->assertSee('Updated Article Title');

        $this->actingAs($user)->put('/workspace/article/'.$article->getKey(), $this->validPayload([
            'article_title' => 'A New Unsubmitted Revision',
        ]))->assertRedirect();
        $this->actingAs($user)->get('/workspace/article/draft')->assertOk()->assertSee('A New Unsubmitted Revision');
        $this->actingAs($user)->get('/workspace/article/submitted')->assertOk()->assertDontSee('A New Unsubmitted Revision');
    }

    public function test_resubmitting_a_revision_after_requested_changes_reaches_editorial_queue(): void
    {
        $author = User::factory()->create();
        $editor = User::factory()->create();
        AccessAssignment::query()->create([
            'user_id' => (string) $editor->getKey(),
            'role_workspace' => 'EAD',
            'scope_access' => 'GBL',
            'status_access_assignment' => 'ACT',
            'effective_at' => now()->subMinute(),
        ]);

        $this->actingAs($author)->post('/workspace/article', $this->validPayload());
        $article = Article::query()->firstOrFail();
        $this->actingAs($author)->post('/workspace/article/'.$article->getKey().'/submit')
            ->assertRedirect(route('workspace.article.submitted'));

        Livewire::actingAs($editor)
            ->test(ArticleReview::class, ['article' => (string) $article->getKey()])
            ->set('reviewNote', 'Please clarify the second paragraph.')
            ->call('requestChanges')
            ->assertRedirect(route('workspace.editorial.article.index'));
        $this->assertSame('N', $article->refresh()->status_review);
        $this->assertSame(0, PendingArticleRevisions::all()->count());

        // Author resubmits the same revision as-is, without saving a new draft first.
        $this->actingAs($author)->post('/workspace/article/'.$article->getKey().'/submit')
            ->assertRedirect(route('workspace.article.submitted'));

        $this->assertCount(1, PendingArticleRevisions::all());
        $this->assertSame((string) $article->getKey(), (string) PendingArticleRevisions::all()->first()->article_id);
        $this->actingAs($editor)->get('/workspace/editorial/article')->assertOk()->assertSee('A Complete Article Draft');
        $this->actingAs($author)->get('/workspace/article/submitted')->assertOk()->assertSee('A Complete Article Draft');
        $this->actingAs($author)->get('/workspace/article/draft')->assertOk()->assertDontSee('A Complete Article Draft');
    }

    public function test_member_cannot_edit_another_authors_article(): void
    {
        $author = User::factory()->create();
        $other = User::factory()->create();
        $this->actingAs($author)->post('/workspace/article', $this->validPayload());
        $article = Article::query()->firstOrFail();

        $this->actingAs($other)->get('/workspace/article/'.$article->getKey().'/edit')->assertForbidden();
        $this->actingAs($other)->put('/workspace/article/'.$article->getKey(), $this->validPayload())->assertForbidden();
        $this->actingAs($other)->get('/workspace/article/'.$article->getKey().'/revision')->assertForbidden();
    }

    public function test_creator_can_share_ownership_and_use_a_custom_multiple_author_byline(): void
    {
        $creator = User::factory()->create();
        $coOwner = User::factory()->create();

        $this->actingAs($creator)->post('/workspace/article', $this->validPayload([
            'article_owner_user_id_list' => [(string) $creator->getKey(), (string) $coOwner->getKey()],
            'author_credit_list' => [
                ['user_id' => (string) $creator->getKey(), 'display_name' => 'Lead Writer'],
                ['user_id' => null, 'display_name' => 'Guest Researcher'],
            ],
        ]))->assertRedirect();

        $article = Article::query()->firstOrFail();
        $this->assertSame(['Lead Writer', 'Guest Researcher'], collect($article->author_credit_list)->pluck('display_name')->all());
        $this->assertSame([(string) $creator->getKey()], $article->author_user_id_list);
        $this->assertContains((string) $coOwner->getKey(), $article->article_owner_user_id_list);

        $this->actingAs($coOwner)->get('/workspace/article/'.$article->getKey().'/edit')->assertOk();
        $this->actingAs($coOwner)->put('/workspace/article/'.$article->getKey(), $this->validPayload([
            'article_title' => 'A Draft Revised by Its Co-owner',
            'author_credit_list' => $article->author_credit_list,
        ]))->assertRedirect();
        $this->assertSame('A Draft Revised by Its Co-owner', $article->refresh()->localized('article_title'));
        $this->assertContains((string) $creator->getKey(), $article->article_owner_user_id_list);
        $this->assertContains((string) $coOwner->getKey(), $article->article_owner_user_id_list);

        $article->forceFill(['status_publication' => 'P', 'visibility_scope' => 'PUB'])->save();
        $this->actingAs($coOwner)->post('/workspace/article/'.$article->getKey().'/unpublish')->assertRedirect();
        $this->assertSame('U', $article->refresh()->status_publication);
        $this->assertSame('PVT', $article->visibility_scope);
    }

    public function test_original_language_and_structured_sources_are_saved_without_delimiters(): void
    {
        $user = User::factory()->create();
        $serviceId = Str::random(16);
        DB::connection('mongodb')->table('service_main')->insert([
            '_id' => $serviceId,
            'service_name' => ['eng' => ['text' => 'Test Massage Service']],
            'status_record_lifecycle' => 'ACT',
        ]);

        try {
            $this->assertSame(1, DB::connection('mongodb')->table('service_main')->where('_id', $serviceId)->count());
            $this->assertSame(1, DB::connection('mongodb')->table('service_main')->where('_id', $serviceId)->where('status_record_lifecycle', 'ACT')->count());
            $this->actingAs($user)->get('/workspace/article/new')->assertOk()->assertSee('Test Massage Service');
            $this->actingAs($user)->post('/workspace/article', $this->validPayload([
                'article_title' => 'Gabay sa Unang Masahe',
                'article_slug' => 'gabay-sa-unang-masahe',
                'language_original_id' => 3600,
                'related_service_id_list' => [$serviceId],
                'source_reference_list' => [[
                    'source_title' => 'Opisyal na Gabay',
                    'source_organization' => 'Halimbawang Ahensya',
                    'source_url' => 'https://example.test/gabay',
                    'publication_identifier' => 'ISBN 123',
                ]],
            ]))->assertRedirect();

            $article = Article::query()->firstOrFail();
            $body = ArticleBody::query()->where('article_id', (string) $article->getKey())->firstOrFail();
            $this->assertSame(3600, $article->language_original_id);
            $this->assertSame('Gabay sa Unang Masahe', $article->article_title['fil']['text']);
            $this->assertSame(3600, $body->language_id);
            $this->assertSame([$serviceId], $article->related_service_id_list);
            $this->assertSame('Halimbawang Ahensya', $article->source_reference_list[0]['source_organization']);
        } finally {
            DB::connection('mongodb')->table('service_main')->where('_id', $serviceId)->delete();
        }
    }

    /** @param array<string, mixed> $overrides @return array<string, mixed> */
    private function validPayload(array $overrides = []): array
    {
        $user = auth()->user();

        return array_merge([
            'article_title' => 'A Complete Article Draft',
            'article_slug' => 'a-complete-article-draft',
            'short_description' => 'A concise description of this useful Massage Nexus article.',
            'language_original_id' => 3049,
            'type_article_category' => 'FTM',
            'target_audience' => 'C',
            'level_nsfw' => 'N',
            'tags' => 'first massage, spa etiquette',
            'article_body' => '<h2>Before you book</h2><p>This article contains enough visible words to save safely.</p>',
            'author_credit_list' => [[
                'user_id' => (string) $user->getKey(),
                'display_name' => $user->publicName(),
            ]],
            'source_reference_list' => [[
                'source_title' => 'Example Guide',
                'source_organization' => 'Example Health Agency',
                'source_url' => 'https://example.test/guide',
                'publication_identifier' => null,
            ]],
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
