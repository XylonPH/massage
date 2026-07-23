<?php

namespace Tests\Feature\Article;

use App\Models\Article\Article;
use App\Models\Article\ArticleBody;
use App\Models\Media\MediaImage;
use App\Models\User;
use Tests\TestCase;

class ArticleImageDisplayTest extends TestCase
{
    protected function tearDown(): void
    {
        MediaImage::query()->delete();
        Article::query()->delete();
        parent::tearDown();
    }

    public function test_workspace_index_shows_featured_thumbnail_when_set(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/workspace/article', [
            'article_title' => 'Featured Thumbnail Article',
            'article_slug' => 'featured-thumbnail-article',
            'short_description' => 'A concise description of this useful Massage Nexus article.',
            'language_original_id' => 3049,
            'type_article_category' => 'FTM',
            'target_audience' => 'C',
            'level_nsfw' => 'N',
            'article_body' => '<p>'.str_repeat('Word ', 25).'</p>',
            'author_credit_list' => [['user_id' => (string) $user->getKey(), 'display_name' => $user->publicName()]],
        ]);
        $article = Article::query()->firstOrFail();
        $image = MediaImage::query()->create([
            'file_name' => 'thumb.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/thumb.webp',
            'alt_text' => ['eng' => ['text' => 'Featured.']],
            'related_article_id_list' => [(string) $article->getKey()],
            'created_by_user_id' => (string) $user->getKey(),
        ]);
        $article->forceFill(['featured_media_image_id' => (string) $image->getKey()])->save();

        $this->actingAs($user)->get('/workspace/article')
            ->assertOk()
            ->assertSee(route('media.image.thumbnail', $image), false);
    }

    public function test_public_show_page_renders_hero_image_when_set(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/workspace/article', [
            'article_title' => 'Hero Banner Article',
            'article_slug' => 'hero-banner-article',
            'short_description' => 'A concise description of this useful Massage Nexus article.',
            'language_original_id' => 3049,
            'type_article_category' => 'FTM',
            'target_audience' => 'C',
            'level_nsfw' => 'N',
            'article_body' => '<p>'.str_repeat('Word ', 25).'</p>',
            'author_credit_list' => [['user_id' => (string) $user->getKey(), 'display_name' => $user->publicName()]],
        ]);
        $article = Article::query()->firstOrFail();
        $image = MediaImage::query()->create([
            'file_name' => 'hero.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/hero.webp',
            'alt_text' => ['eng' => ['text' => 'Hero.']],
            'related_article_id_list' => [(string) $article->getKey()],
            'created_by_user_id' => (string) $user->getKey(),
        ]);
        $article->forceFill([
            'cover_media_image_id' => (string) $image->getKey(),
            'status_publication' => 'P',
            'status_review' => 'A',
            'visibility_scope' => 'PUB',
            'published_at' => now()->subMinute(),
        ])->save();
        ArticleBody::query()
            ->where('article_id', (string) $article->getKey())
            ->where('language_id', (int) $article->language_original_id)
            ->update(['status_review' => 'A']);

        $this->get(route('article.show', $article->localized('article_slug')))
            ->assertOk()
            ->assertSee(route('media.image.show', $image), false);
    }
}
