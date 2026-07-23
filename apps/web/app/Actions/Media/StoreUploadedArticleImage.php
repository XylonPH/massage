<?php

namespace App\Actions\Media;

use App\Models\Article\Article;
use App\Models\Media\MediaImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class StoreUploadedArticleImage
{
    private const THUMBNAIL_MAX_DIMENSION = 480;

    public function execute(UploadedFile $file, string $altText, Article $article, User $user): MediaImage
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $datePath = now()->format('Y/m');
        $baseName = Str::random(16);
        $storedPath = "media/image/{$datePath}/{$baseName}.{$extension}";

        Storage::disk('public')->put($storedPath, file_get_contents($file->getRealPath()));

        [$width, $height] = getimagesize($file->getRealPath());

        $manager = ImageManager::gd();
        $thumbnail = $manager->read($file->getRealPath())->scaleDown(width: self::THUMBNAIL_MAX_DIMENSION);
        $thumbnailBinary = (string) $thumbnail->encodeByExtension($extension);
        $thumbnailPath = "media/image/{$datePath}/{$baseName}-thumb.{$extension}";
        Storage::disk('public')->put($thumbnailPath, $thumbnailBinary);

        return MediaImage::query()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_extension' => $extension,
            'mime_type' => $file->getMimeType(),
            'file_size_byte' => $file->getSize(),
            'width_pixel' => $width,
            'height_pixel' => $height,
            'storage_path' => $storedPath,
            'alt_text' => ['eng' => ['text' => $altText]],
            'related_article_id_list' => [(string) $article->getKey()],
            'creator_user_id_list' => [(string) $user->getKey()],
            'method_media_creation' => 'IMP',
            'image_variant_list' => [[
                'type_image_variant' => 'TH',
                'file_name' => basename($thumbnailPath),
                'file_extension' => $extension,
                'mime_type' => $file->getMimeType(),
                'file_size_byte' => strlen($thumbnailBinary),
                'width_pixel' => $thumbnail->width(),
                'height_pixel' => $thumbnail->height(),
                'storage_path' => $thumbnailPath,
                'storage_url' => null,
            ]],
            'created_by_user_id' => (string) $user->getKey(),
        ]);
    }
}
