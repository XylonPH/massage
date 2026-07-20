<?php

namespace App\Models;

use App\Enums\RecordLifecycleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Str;

class Establishment extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'establishment_main';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        '_id',
        'display_name',
        'short_description',
        'email',
        'contact_number',
        'type_spa',
        'level_spa_market',
        'type_physical_setting',
        'mode_service_delivery',
        'mode_access',
        'type_establishment_operation',
        'status_establishment',
        'type_client_access',
        'target_client_focus',
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
                $model->{$model->getKeyName()} = Str::random(16);
            }
        });
    }

    /**
     * Get the preferred English name.
     */
    public function getEnglishNameAttribute()
    {
        return $this->display_name['eng'] ?? null;
    }

    public function setEnglishNameAttribute($value)
    {
        $names = $this->display_name ?? [];
        $names['eng'] = $value;
        $this->attributes['display_name'] = $names;
    }

    public function getEnglishShortDescriptionAttribute()
    {
        return $this->short_description['eng'] ?? null;
    }

    public function setEnglishShortDescriptionAttribute($value)
    {
        $desc = $this->short_description ?? [];
        $desc['eng'] = $value;
        $this->attributes['short_description'] = $desc;
    }

    public function getChineseNameAttribute()
    {
        return $this->display_name['zho'] ?? null;
    }

    public function setChineseNameAttribute($value)
    {
        $names = $this->display_name ?? [];
        $names['zho'] = $value;
        $this->attributes['display_name'] = $names;
    }

    public function getChineseShortDescriptionAttribute()
    {
        return $this->short_description['zho'] ?? null;
    }

    public function setChineseShortDescriptionAttribute($value)
    {
        $desc = $this->short_description ?? [];
        $desc['zho'] = $value;
        $this->attributes['short_description'] = $desc;
    }
}
