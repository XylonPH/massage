<?php

namespace App\Models\Review;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable([
    'target_collection', 'target_record_id', 'review_id', 'created_by_user_id',
    'mode_rating', 'rating_score', 'rating_criterion_list', 'type_rating_source',
    'date_experience', 'status_rating',
])]
class Rating extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'rating_main';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function (self $rating): void {
            $rating->{$rating->getKeyName()} ??= Str::random(16);
        });
    }

    protected function casts(): array
    {
        return [
            'rating_score' => 'decimal:2',
            'date_experience' => 'date',
        ];
    }
}
