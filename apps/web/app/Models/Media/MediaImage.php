<?php

namespace App\Models\Media;

use App\Models\Concerns\HasSparseDefaults;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable([
    'image_title', 'file_name', 'file_extension', 'mime_type', 'file_size_byte',
    'width_pixel', 'height_pixel', 'storage_path', 'storage_url',
    'alt_text', 'caption_text',
    'tag_id_list', 'related_organization_id_list', 'related_establishment_id_list',
    'related_practitioner_id_list', 'related_service_id_list', 'related_product_id_list',
    'related_article_id_list', 'level_nsfw',
    'method_media_creation', 'creator_user_id_list', 'photographer_user_id_list',
    'editor_user_id_list', 'ai_tool_name', 'source_media_image_id', 'source_url',
    'image_variant_list', 'detected_person_list', 'recognized_person_list',
    'visibility_scope', 'status_review', 'status_record_lifecycle',
    'created_by_user_id', 'updated_by_user_id',
])]
class MediaImage extends Model
{
    use HasSparseDefaults;

    protected $connection = 'mongodb';

    protected $table = 'media_image';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function (self $image): void {
            $image->{$image->getKeyName()} ??= Str::random(16);
        });
    }

    protected function casts(): array
    {
        return [
            'file_size_byte' => 'integer',
            'width_pixel' => 'integer',
            'height_pixel' => 'integer',
        ];
    }

    public function localized(string $field, string $locale = 'eng'): string
    {
        $values = $this->getAttribute($field);

        return is_array($values) ? (string) ($values[$locale]['text'] ?? $values['eng']['text'] ?? '') : '';
    }

    /** @return array<string, mixed> */
    protected function sparseDefaults(): array
    {
        return [
            'tag_id_list' => [],
            'related_organization_id_list' => [],
            'related_establishment_id_list' => [],
            'related_practitioner_id_list' => [],
            'related_service_id_list' => [],
            'related_product_id_list' => [],
            'related_article_id_list' => [],
            'creator_user_id_list' => [],
            'photographer_user_id_list' => [],
            'editor_user_id_list' => [],
            'source_media_image_id' => null,
            'level_nsfw' => 'N',
            'image_variant_list' => [],
            'detected_person_list' => [],
            'recognized_person_list' => [],
            'visibility_scope' => 'INH',
            'status_review' => 'PND',
            'status_record_lifecycle' => 'ACT',
        ];
    }
}
