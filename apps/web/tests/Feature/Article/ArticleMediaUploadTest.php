<?php

namespace Tests\Feature\Article;

use App\Models\Article\Article;
use App\Models\Media\MediaImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleMediaUploadTest extends TestCase
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
            'article_title' => 'An Article With Images',
            'article_slug' => 'an-article-with-images',
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

    public function test_owner_can_upload_an_image_to_their_article(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $article = $this->createArticle($user);

        $response = $this->actingAs($user)->post("/workspace/article/{$article->getKey()}/media", [
            'image' => UploadedFile::fake()->image('photo.jpg', 800, 600),
            'alt_text' => 'A descriptive alt text.',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['id', 'url', 'thumbnail_url']);
        $image = MediaImage::query()->firstOrFail();
        $this->assertSame([(string) $article->getKey()], $image->related_article_id_list);
        $this->assertSame([(string) $user->getKey()], $image->creator_user_id_list);
        Storage::disk('public')->assertExists($image->storage_path);
        Storage::disk('public')->assertExists($image->image_variant_list[0]['storage_path']);
        // Source was 800x600 (landscape); thumbnail's long edge (width) must be scaled down to the 480px cap.
        $this->assertSame(480, $image->image_variant_list[0]['width_pixel']);
        $this->assertLessThanOrEqual(480, $image->image_variant_list[0]['height_pixel']);
    }

    public function test_non_owner_cannot_upload_an_image(): void
    {
        Storage::fake('public');
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $article = $this->createArticle($owner);

        $this->actingAs($other)->post("/workspace/article/{$article->getKey()}/media", [
            'image' => UploadedFile::fake()->image('photo.jpg', 800, 600),
            'alt_text' => 'A descriptive alt text.',
        ])->assertForbidden();
    }

    public function test_upload_rejects_a_file_over_the_size_limit(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $article = $this->createArticle($user);

        $this->actingAs($user)->post("/workspace/article/{$article->getKey()}/media", [
            'image' => UploadedFile::fake()->image('photo.jpg', 800, 600)->size(6000),
            'alt_text' => 'A descriptive alt text.',
        ])->assertSessionHasErrors('image');
    }

    public function test_upload_rejects_a_disallowed_mime_type(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $article = $this->createArticle($user);

        $this->actingAs($user)->post("/workspace/article/{$article->getKey()}/media", [
            'image' => UploadedFile::fake()->create('animation.gif', 100, 'image/gif'),
            'alt_text' => 'A descriptive alt text.',
        ])->assertSessionHasErrors('image');
    }

    public function test_upload_rejects_an_eleventh_image(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $article = $this->createArticle($user);
        for ($i = 0; $i < 10; $i++) {
            MediaImage::query()->create([
                'file_name' => "existing-{$i}.webp",
                'mime_type' => 'image/webp',
                'storage_path' => "media/image/existing-{$i}.webp",
                'alt_text' => ['eng' => ['text' => 'Existing image.']],
                'related_article_id_list' => [(string) $article->getKey()],
                'created_by_user_id' => (string) $user->getKey(),
            ]);
        }

        $this->actingAs($user)->post("/workspace/article/{$article->getKey()}/media", [
            'image' => UploadedFile::fake()->image('photo.jpg', 800, 600),
            'alt_text' => 'A descriptive alt text.',
        ])->assertStatus(422);
    }
}
