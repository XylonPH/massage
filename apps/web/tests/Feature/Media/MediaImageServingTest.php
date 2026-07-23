<?php

namespace Tests\Feature\Media;

use App\Models\Media\MediaImage;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaImageServingTest extends TestCase
{
    protected function tearDown(): void
    {
        MediaImage::query()->delete();
        parent::tearDown();
    }

    private function makeImage(): MediaImage
    {
        Storage::fake('public');
        Storage::disk('public')->put('media/image/2026/07/sample.webp', 'full-size-bytes');
        Storage::disk('public')->put('media/image/2026/07/sample-thumb.webp', 'thumb-bytes');

        return MediaImage::query()->create([
            'file_name' => 'sample.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/sample.webp',
            'alt_text' => ['eng' => ['text' => 'A sample image.']],
            'created_by_user_id' => 'U0000000000000001',
            'image_variant_list' => [[
                'type_image_variant' => 'TH',
                'file_name' => 'sample-thumb.webp',
                'mime_type' => 'image/webp',
                'storage_path' => 'media/image/2026/07/sample-thumb.webp',
            ]],
        ]);
    }

    public function test_show_route_streams_the_original_file(): void
    {
        $image = $this->makeImage();

        $response = $this->get("/media/image/{$image->getKey()}");

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/webp');
        $this->assertSame('full-size-bytes', $response->getContent());
    }

    public function test_thumbnail_route_streams_the_thumbnail_variant(): void
    {
        $image = $this->makeImage();

        $response = $this->get("/media/image/{$image->getKey()}/thumbnail");

        $response->assertOk();
        $this->assertSame('thumb-bytes', $response->getContent());
    }

    public function test_show_route_404s_for_an_unknown_id(): void
    {
        $this->get('/media/image/Un0000000000wn16')->assertNotFound();
    }

    public function test_thumbnail_route_404s_when_no_thumbnail_variant_exists(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('media/image/2026/07/no-thumb.webp', 'bytes');
        $image = MediaImage::query()->create([
            'file_name' => 'no-thumb.webp',
            'mime_type' => 'image/webp',
            'storage_path' => 'media/image/2026/07/no-thumb.webp',
            'alt_text' => ['eng' => ['text' => 'No thumbnail.']],
            'created_by_user_id' => 'U0000000000000001',
        ]);

        $this->get("/media/image/{$image->getKey()}/thumbnail")->assertNotFound();
    }
}
