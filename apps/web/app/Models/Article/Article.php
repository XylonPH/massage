<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable([
    'article_title', 'article_slug', 'short_description', 'language_original_id',
    'type_article_category', 'target_audience', 'tag_id_list', 'author_user_id_list',
    'editor_user_id_list', 'reviewer_user_id_list', 'photographer_user_id_list',
    'cover_media_image_id', 'related_article_id_list', 'related_organization_id_list',
    'related_establishment_id_list', 'related_practitioner_id_list', 'related_service_id_list',
    'related_product_id_list', 'view_count', 'comment_count', 'save_count', 'share_count',
    'reading_duration_visual', 'reading_duration_spoken', 'source_reference_list',
    'is_commentable', 'is_shareable', 'status_publication', 'status_review',
    'visibility_scope', 'level_nsfw', 'status_record_lifecycle', 'record_note',
    'created_by_user_id', 'updated_by_user_id', 'scheduled_publish_at', 'published_at',
    'published_by_user_id', 'archived_at', 'archived_by_user_id',
])]
class Article extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'article_main';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function (self $article): void {
            $article->{$article->getKeyName()} ??= Str::random(16);
        });
    }

    protected function casts(): array
    {
        return [
            'view_count' => 'integer',
            'comment_count' => 'integer',
            'save_count' => 'integer',
            'share_count' => 'integer',
            'reading_duration_visual' => 'integer',
            'reading_duration_spoken' => 'integer',
            'is_commentable' => 'boolean',
            'is_shareable' => 'boolean',
            'scheduled_publish_at' => 'datetime',
            'published_at' => 'datetime',
            'archived_at' => 'datetime',
        ];
    }

    public function scopePubliclyVisible(Builder $query): Builder
    {
        return $query
            ->where('status_publication', 'P')
            ->where('status_review', 'A')
            ->where('visibility_scope', 'PUB')
            ->where('status_record_lifecycle', 'ACT')
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function localized(string $field, string $locale = 'eng'): string
    {
        $values = $this->getAttribute($field);

        if (! is_array($values)) {
            return '';
        }

        $preferred = $values[$locale]['text'] ?? $values['eng']['text'] ?? null;

        if (is_string($preferred)) {
            return $preferred;
        }

        foreach ($values as $value) {
            if (is_array($value) && is_string($value['text'] ?? null)) {
                return $value['text'];
            }
        }

        return '';
    }
}
