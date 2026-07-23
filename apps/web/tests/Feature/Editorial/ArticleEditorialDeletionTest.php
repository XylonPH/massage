<?php

namespace Tests\Feature\Editorial;

use App\Livewire\Workspace\Editorial\ArticleIndex;
use App\Models\Article\Article;
use App\Models\Article\ArticleBody;
use App\Models\Article\ArticleRevision;
use App\Models\User;
use App\Models\UserAccess;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class ArticleEditorialDeletionTest extends TestCase
{
    public function test_editor_can_view_article_management_index_and_delete_article(): void
    {
        /** @var User $editor */
        $editor = User::factory()->create();
        UserAccess::query()->create([
            'user_id' => (string) $editor->getKey(),
            'role_workspace' => 'EAD',
            'scope_access' => 'GBL',
            'status_user_access' => 'ACT',
        ]);

        /** @var User $author */
        $author = User::factory()->create();
        $slug = 'test-del-'.Str::random(8);

        $article = Article::query()->create([
            'article_slug' => ['eng' => ['text' => $slug]],
            'article_title' => ['eng' => ['text' => 'Unique Deletion Test Article']],
            'short_description' => ['eng' => ['text' => 'Test short description.']],
            'status_publication' => 'P',
            'status_review' => 'A',
            'created_by_user_id' => (string) $author->getKey(),
            'author_user_id_list' => [(string) $author->getKey()],
            'article_owner_user_id_list' => [(string) $author->getKey()],
            'language_original_id' => 3049,
        ]);

        $body = ArticleBody::query()->create([
            'article_id' => (string) $article->getKey(),
            'language_id' => 3049,
            'body_text' => 'Article body paragraph for test.',
            'status_review' => 'A',
        ]);

        ArticleRevision::query()->create([
            'article_id' => (string) $article->getKey(),
            'article_body_id' => (string) $body->getKey(),
            'revision_number' => 1,
            'status_review' => 'A',
            'submitted_by_user_id' => (string) $author->getKey(),
            'submitted_at' => now(),
            'word_count' => 120,
        ]);

        $this->actingAs($editor);

        Livewire::test(ArticleIndex::class)
            ->assertSee('Unique Deletion Test Article')
            ->call('deleteArticle', (string) $article->getKey());

        $this->assertNull(Article::query()->find((string) $article->getKey()));
        $this->assertNull(ArticleBody::query()->where('article_id', (string) $article->getKey())->first());
        $this->assertNull(ArticleRevision::query()->where('article_id', (string) $article->getKey())->first());
    }
}
