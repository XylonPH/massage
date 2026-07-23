<?php

namespace Tests\Feature\Article;

use App\Models\Article\Article;
use App\Models\Article\ArticleBody;
use App\Models\Media\MediaImage;
use App\Models\User;
use Tests\TestCase;

class ArticleImageEmbedTest extends TestCase
{
    protected function tearDown(): void
    {
        MediaImage::query()->delete();
        ArticleBody::query()->delete();
        Article::query()->delete();
        parent::tearDown();
    }

    public function test_a_saved_draft_containing_an_inline_image_figure_round_trips_through_the_sanitizer(): void
    {
        $user = User::factory()->create();
        $image = MediaImage::query()->create([
            'file_name' => 'inline.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/inline.webp',
            'alt_text' => ['eng' => ['text' => 'Inline article image.']],
            'created_by_user_id' => (string) $user->getKey(),
        ]);
        $imageId = (string) $image->getKey();

        $this->actingAs($user)->post('/workspace/article', [
            'article_title' => 'An Illustrated Article',
            'article_slug' => 'an-illustrated-article',
            'short_description' => 'A concise description of this useful Massage Nexus article.',
            'language_original_id' => 3049,
            'type_article_category' => 'FTM',
            'target_audience' => 'C',
            'level_nsfw' => 'N',
            'article_body' => '<p>Some intro text.</p><figure data-media-image-id="'.$imageId.'"><img src="/media/image/'.$imageId.'" alt="Inline article image."></figure><p>More text after the image.</p>',
            'author_credit_list' => [['user_id' => (string) $user->getKey(), 'display_name' => $user->publicName()]],
        ])->assertRedirect();

        $article = Article::query()->firstOrFail();
        $body = ArticleBody::query()->where('article_id', (string) $article->getKey())->firstOrFail();
        $this->assertStringContainsString('data-media-image-id="'.$imageId.'"', $body->article_body);
        $this->assertStringContainsString('src="/media/image/'.$imageId.'"', $body->article_body);
    }

    public function test_an_absolute_image_src_is_stripped_by_the_sanitizer_and_must_not_be_produced_by_the_editor(): void
    {
        $user = User::factory()->create();
        $image = MediaImage::query()->create([
            'file_name' => 'inline.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/inline.webp',
            'alt_text' => ['eng' => ['text' => 'Inline article image.']],
            'created_by_user_id' => (string) $user->getKey(),
        ]);
        $imageId = (string) $image->getKey();

        $this->actingAs($user)->post('/workspace/article', [
            'article_title' => 'An Article With A Broken Absolute Src',
            'article_slug' => 'an-article-with-a-broken-absolute-src',
            'short_description' => 'A concise description of this useful Massage Nexus article.',
            'language_original_id' => 3049,
            'type_article_category' => 'FTM',
            'target_audience' => 'C',
            'level_nsfw' => 'N',
            // Absolute URL, exactly what route('media.image.show', ...) produces by default —
            // this is the shape the editor JS must NEVER send, and this test locks that in.
            'article_body' => '<p>Some intro text.</p><figure data-media-image-id="'.$imageId.'"><img src="http://example.test/media/image/'.$imageId.'" alt="Inline article image."></figure>',
            'author_credit_list' => [['user_id' => (string) $user->getKey(), 'display_name' => $user->publicName()]],
        ])->assertRedirect();

        $article = Article::query()->firstOrFail();
        $body = ArticleBody::query()->where('article_id', (string) $article->getKey())->firstOrFail();
        // The figure/data-media-image-id survives, but the absolute src must be stripped —
        // this is exactly the bug: it documents the sanitizer's real behavior so the editor's
        // JS-side contract (always emit a RELATIVE src) can never silently regress.
        $this->assertStringContainsString('data-media-image-id="'.$imageId.'"', $body->article_body);
        $this->assertStringNotContainsString('src=', $body->article_body);
    }
}
