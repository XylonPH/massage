<?php

namespace App\Models;

use App\Enums\RecordLifecycleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

class Establishment extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $table = 'establishment_main';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        '_id',
        'display_name',
        'short_description',
        'description',
        'email',
        'contact_number',
        'address_public',
        'coordinate_latitude',
        'coordinate_longitude',
        'direction_note',
        'parking_note',
        'landmark_list',
        'contact_channel_list',
        'treatment_area_list',
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
        'room_types',
        'shower_availability',
        'sauna_availability',
        'steam_room_availability',
        'jacuzzi_availability',
        'locker_availability',
        'couple_room_availability',
        'private_room_availability',
        'curtain_divider_information',
        'bed_mat_chair_setup',
        'air_conditioning_information',
        'operating_hours',
        'amenity_list',
        'accessibility_feature_list',
        'parking_availability_list',
        'date_opened',
        'date_opened_precision',
        'date_opened_qualifier',
        'date_closed',
        'date_closed_precision',
        'date_closed_qualifier',
    ];

    protected $casts = [
        'status_record_lifecycle' => RecordLifecycleStatus::class,
        'coordinate_latitude' => 'float',
        'coordinate_longitude' => 'float',
        'direction_note' => 'array',
        'parking_note' => 'array',
        'landmark_list' => 'array',
        'treatment_area_list' => 'array',
        'contact_channel_list' => 'array',
        'operating_hours' => 'array',
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
