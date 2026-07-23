<?php

namespace Tests\Feature\Media;

use App\Models\Media\MediaImage;
use Tests\TestCase;

class MediaImageTest extends TestCase
{
    protected function tearDown(): void
    {
        MediaImage::query()->delete();
        parent::tearDown();
    }

    public function test_creating_a_media_image_applies_sparse_defaults(): void
    {
        $image = MediaImage::query()->create([
            'file_name' => 'sample.webp',
            'file_extension' => 'webp',
            'mime_type' => 'image/webp',
            'file_size_byte' => 1024,
            'width_pixel' => 800,
            'height_pixel' => 600,
            'storage_path' => 'media/image/2026/07/sample.webp',
            'alt_text' => ['eng' => ['text' => 'A sample image.']],
            'created_by_user_id' => 'U0000000000000001',
        ]);

        $this->assertSame(16, strlen((string) $image->getKey()));
        $this->assertSame('N', $image->level_nsfw);
        $this->assertSame('PND', $image->status_review);
        $this->assertSame('ACT', $image->status_record_lifecycle);
        $this->assertSame('INH', $image->visibility_scope);
        $this->assertSame([], $image->tag_id_list);
        $this->assertSame([], $image->related_article_id_list);
        $this->assertSame(800, $image->width_pixel);
        $this->assertSame('A sample image.', $image->localized('alt_text'));
    }

    public function test_detected_and_recognized_person_lists_are_always_empty_on_new_records(): void
    {
        $image = MediaImage::query()->create([
            'file_name' => 'sample.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/sample.webp',
            'alt_text' => ['eng' => ['text' => 'A sample image.']],
            'created_by_user_id' => 'U0000000000000001',
        ]);

        $this->assertSame([], $image->detected_person_list);
        $this->assertSame([], $image->recognized_person_list);
    }
}
