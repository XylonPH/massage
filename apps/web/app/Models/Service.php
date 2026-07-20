<?php

namespace App\Models;

use App\Enums\RecordLifecycleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'service_main';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        '_id',
        'service_slug',
        'service_name',
        'short_description',
        'service_description_overview',
        'group_service_sector',
        'group_service_domain',
        'group_service_family',
        'status_record_lifecycle',
    ];

    protected $casts = [
        'status_record_lifecycle' => RecordLifecycleStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = \Illuminate\Support\Str::random(16);
            }
        });
    }

    /**
     * Get the preferred English name.
     */
    public function getEnglishNameAttribute()
    {
        return $this->service_name['eng'] ?? null;
    }

    /**
     * Get the English short description.
     */
    public function getEnglishShortDescriptionAttribute()
    {
        return $this->short_description['eng'] ?? null;
    }

    /**
     * Get the English overview.
     */
    public function getEnglishOverviewAttribute()
    {
        return $this->service_description_overview['eng'] ?? null;
    }
}
