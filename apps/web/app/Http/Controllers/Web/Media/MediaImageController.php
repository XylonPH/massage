<?php

namespace App\Http\Controllers\Web\Media;

use App\Http\Controllers\Controller;
use App\Models\Media\MediaImage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class MediaImageController extends Controller
{
    public function show(MediaImage $media_image): Response
    {
        return $this->stream($media_image->storage_path, $media_image->mime_type);
    }

    public function thumbnail(MediaImage $media_image): Response
    {
        $variant = collect($media_image->image_variant_list)->firstWhere('type_image_variant', 'TH');
        abort_unless($variant, 404);

        return $this->stream($variant['storage_path'], $variant['mime_type']);
    }

    private function stream(string $path, string $mimeType): Response
    {
        abort_unless(Storage::disk('public')->exists($path), 404);

        return response(Storage::disk('public')->get($path), 200, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
