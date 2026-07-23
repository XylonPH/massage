<?php

namespace Tests\Feature\Article;

use App\Models\Article\Article;
use App\Models\Media\MediaImage;
use App\Models\User;
use Tests\TestCase;

class ArticleHeroImageTest extends TestCase
{
    protected function tearDown(): void
    {
        MediaImage::query()->delete();
        Article::query()->delete();
        parent::tearDown();
    }

    private function createArticle(User $user): Article
    {
        $this->actingAs($user)->post('/workspace/article', [
            'article_title' => 'An Article With A Hero Image',
            'article_slug' => 'an-article-with-a-hero-image',
            'short_description' => 'A concise description of this useful Massage Nexus article.',
            'language_original_id' => 3049,
            'type_article_category' => 'FTM',
            'target_audience' => 'C',
            'level_nsfw' => 'N',
            'article_body' => '<p>'.str_repeat('Word ', 25).'</p>',
            'author_credit_list' => [['user_id' => (string) $user->getKey(), 'display_name' => $user->publicName()]],
        ]);

        return Article::query()->firstOrFail();
    }

    public function test_owner_can_set_a_belonging_image_as_the_hero_image(): void
    {
        $user = User::factory()->create();
        $article = $this->createArticle($user);
        $image = MediaImage::query()->create([
            'file_name' => 'hero.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/hero.webp',
            'alt_text' => ['eng' => ['text' => 'A hero image.']],
            'related_article_id_list' => [(string) $article->getKey()],
            'created_by_user_id' => (string) $user->getKey(),
        ]);

        $this->actingAs($user)
            ->post("/workspace/article/{$article->getKey()}/media/{$image->getKey()}/cover")
            ->assertRedirect();

        $this->assertSame((string) $image->getKey(), $article->refresh()->cover_media_image_id);
    }

    public function test_cannot_set_an_unrelated_image_as_the_hero_image(): void
    {
        $user = User::factory()->create();
        $article = $this->createArticle($user);
        $unrelatedImage = MediaImage::query()->create([
            'file_name' => 'unrelated.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/unrelated.webp',
            'alt_text' => ['eng' => ['text' => 'Unrelated image.']],
            'created_by_user_id' => (string) $user->getKey(),
        ]);

        $this->actingAs($user)
            ->post("/workspace/article/{$article->getKey()}/media/{$unrelatedImage->getKey()}/cover")
            ->assertStatus(422);

        $this->assertNull($article->refresh()->cover_media_image_id);
    }
}
