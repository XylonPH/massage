<?php

namespace App\Models;

use App\Enums\NsfwLevel;
use App\Enums\QuoteCategory;
use App\Enums\RecordLifecycleStatus;
use App\Enums\ReviewStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'quote_main';
    
    // Per documentation: application-generated 16-character Base62 identifier
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        '_id',
        'quote_text',
        'language_original_id',
        'type_quote_category',
        'attribution_name',
        'source_title',
        'source_url',
        'display_start_date',
        'display_end_date',
        'is_display_enabled',
        'status_review',
        'level_nsfw',
        'status_record_lifecycle',
        'record_note',
        'created_by_user_id',
        'updated_by_user_id',
        'archived_at',
        'archived_by_user_id',
    ];

    protected $casts = [
        'display_start_date' => 'date',
        'display_end_date' => 'date',
        'is_display_enabled' => 'boolean',
        'status_review' => ReviewStatus::class,
        'level_nsfw' => NsfwLevel::class,
        'status_record_lifecycle' => RecordLifecycleStatus::class,
        'archived_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                // Generate a 16-character Base62 ID using Str::random or similar
                $model->{$model->getKeyName()} = \Illuminate\Support\Str::random(16);
            }
        });
    }

    /**
     * Helper to easily get/set the English quote text since it is stored as an array
     * under the 'eng' key: ['eng' => ['text' => '...', 'method_translation' => 'HUM', 'status_review' => 'A']]
     */
    public function getEnglishTextAttribute()
    {
        return $this->quote_text['eng']['text'] ?? null;
    }

    public function setEnglishTextAttribute($value)
    {
        $current = $this->quote_text ?? [];
        $current['eng'] = [
            'text' => $value,
            'method_translation' => 'HUM',
            'status_review' => 'A', // Assuming standard entry is approved for the translation array itself
        ];
        $this->quote_text = $current;
    }
}
