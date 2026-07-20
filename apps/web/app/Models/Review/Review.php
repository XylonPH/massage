<?php

namespace App\Models\Review;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable([
    'review_title', 'review_slug', 'short_description', 'review_body',
    'language_original_id', 'type_review', 'target_collection', 'target_record_id',
    'author_user_id_list', 'is_anonymous', 'date_experience', 'service_received',
    'amount_paid', 'currency_id', 'type_review_disclosure', 'rating_id',
    'reading_duration_visual', 'reading_duration_spoken', 'level_nsfw',
    'status_publication', 'status_review', 'visibility_scope',
    'status_record_lifecycle', 'record_note', 'created_by_user_id',
    'updated_by_user_id', 'submitted_at', 'submitted_by_user_id', 'published_at',
    'published_by_user_id',
])]
class Review extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'review_main';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function (self $review): void {
            $review->{$review->getKeyName()} ??= Str::random(16);
        });
    }

    protected function casts(): array
    {
        return [
            'language_original_id' => 'integer',
            'is_anonymous' => 'boolean',
            'date_experience' => 'date',
            'amount_paid' => 'decimal:2',
            'currency_id' => 'integer',
            'reading_duration_visual' => 'integer',
            'reading_duration_spoken' => 'integer',
            'submitted_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function scopePubliclyVisible(Builder $query): Builder
    {
        return $query
            ->where('status_publication', 'P')
            ->where('status_review', 'APR')
            ->where('visibility_scope', 'PUB')
            ->where('status_record_lifecycle', 'ACT')
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function isOwnedBy(string $userId): bool
    {
        return in_array($userId, $this->author_user_id_list ?? [], true);
    }

    public function isEditableDraft(): bool
    {
        return $this->status_publication === 'D' && $this->status_review === 'NR';
    }
}
