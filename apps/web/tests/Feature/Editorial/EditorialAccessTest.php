<?php

namespace Tests\Feature\Editorial;

use App\Livewire\Workspace\Editorial\ArticleReview;
use App\Models\AccessAssignment;
use App\Models\Article\Article;
use App\Models\Article\ArticleBody;
use App\Models\Article\ArticleRevision;
use App\Models\User;
use App\Support\Article\PendingArticleRevisions;
use Livewire\Livewire;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class EditorialAccessTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function setUp(): void
    {
        parent::setUp();
        AccessAssignment::query()->delete();
        ArticleRevision::query()->delete();
        ArticleBody::query()->delete();
        Article::query()->delete();
    }

    protected function tearDown(): void
    {
        AccessAssignment::query()->delete();
        ArticleRevision::query()->delete();
        ArticleBody::query()->delete();
        Article::query()->delete();
        parent::tearDown();
    }

    private function grantEditorial(User $user): void
    {
        AccessAssignment::query()->create([
            'user_id' => (string) $user->getKey(),
            'role_workspace' => 'EAD',
            'scope_access' => 'GBL',
            'status_access_assignment' => 'ACT',
            'effective_at' => now()->subMinute(),
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/workspace/editorial')->assertRedirect('/login');
    }

    public function test_member_without_permission_is_forbidden(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/workspace/editorial')->assertForbidden();
    }

    public function test_editor_sees_landing_page_with_collection_cards(): void
    {
        $user = User::factory()->create();
        $this->grantEditorial($user);

        $this->actingAs($user)->get('/workspace/editorial')
            ->assertOk()
            ->assertSee(__('editorial.title'))
            ->assertSee(route('workspace.editorial.establishment.index', [], false), false)
            ->assertSee(route('workspace.editorial.service.index', [], false), false)
            ->assertSee(route('workspace.editorial.quote.index', [], false), false)
            ->assertSee(route('workspace.editorial.article.index', [], false), false);
    }

    public function test_editor_can_review_and_publish_a_submitted_article(): void
    {
        $editor = User::factory()->create();
        $author = User::factory()->create();
        $this->grantEditorial($editor);
        $article = Article::query()->create([
            'article_title' => ['eng' => ['text' => 'Ready for Editorial Review']],
            'article_slug' => ['eng' => ['text' => 'ready-for-editorial-review']],
            'short_description' => ['eng' => ['text' => 'A submitted Article test.']],
            'language_original_id' => 3049,
            'type_article_category' => 'FTM',
            'author_user_id_list' => [(string) $author->getKey()],
            'article_owner_user_id_list' => [(string) $author->getKey()],
            'created_by_user_id' => (string) $author->getKey(),
        ]);
        $body = ArticleBody::query()->create([
            'article_id' => (string) $article->getKey(),
            'language_id' => 3049,
            'article_body' => '<h2>Reviewed body</h2><p>Safe editorial content.</p>',
            'article_plain_text' => 'Reviewed body Safe editorial content.',
            'word_count' => 5,
            'created_by_user_id' => (string) $author->getKey(),
        ]);
        $revision = ArticleRevision::query()->create([
            'article_id' => (string) $article->getKey(),
            'article_body_id' => (string) $body->getKey(),
            'language_id' => 3049,
            'revision_number' => 1,
            'article_body' => $body->article_body,
            'word_count' => 5,
            'created_at' => now()->subMinute(),
            'created_by_user_id' => (string) $author->getKey(),
            'submitted_at' => now(),
            'submitted_by_user_id' => (string) $author->getKey(),
        ]);

        $this->actingAs($editor)->get('/workspace/editorial/article')
            ->assertOk()
            ->assertSee('Ready for Editorial Review')
            ->assertSee(route('workspace.editorial.article.review', $article, false), false);

        Livewire::actingAs($editor)
            ->test(ArticleReview::class, ['article' => (string) $article->getKey()])
            ->assertSee('Ready for Editorial Review')
            ->call('approve')
            ->assertRedirect(route('workspace.editorial.article.index'));

        $this->assertSame('P', $article->refresh()->status_publication);
        $this->assertSame('A', $article->status_review);
        $this->assertSame('PUB', $article->visibility_scope);
        $this->assertSame('A', $body->refresh()->status_review);
        $this->assertSame('A', $revision->refresh()->status_review);
        $this->assertNotNull($revision->approved_at);
        $this->assertSame(0, PendingArticleRevisions::all()->count());
    }

    public function test_moderation_and_system_placeholders_are_permission_gated(): void
    {
        $member = User::factory()->create();
        $founder = User::factory()->create();
        AccessAssignment::query()->create([
            'user_id' => (string) $founder->getKey(),
            'role_workspace' => 'FND',
            'scope_access' => 'GBL',
            'status_access_assignment' => 'ACT',
            'effective_at' => now()->subMinute(),
        ]);

        foreach (['moderation', 'system'] as $area) {
            $this->actingAs($member)->get("/workspace/{$area}")->assertForbidden();
            $this->actingAs($founder)->get("/workspace/{$area}")
                ->assertOk()
                ->assertSee(__('workspace.admin_placeholder_text'));
        }
    }
}
