<?php

namespace App\Models;

use App\Enums\RecordLifecycleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $table = 'service_main';

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
                $model->{$model->getKeyName()} = Str::random(16);
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

    public function setEnglishNameAttribute($value)
    {
        $names = $this->service_name ?? [];
        $names['eng'] = $value;
        $this->attributes['service_name'] = $names;
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

    public function getEnglishOverviewAttribute()
    {
        return $this->service_description_overview['eng'] ?? null;
    }

    public function setEnglishOverviewAttribute($value)
    {
        $overview = $this->service_description_overview ?? [];
        $overview['eng'] = $value;
        $this->attributes['service_description_overview'] = $overview;
    }

    public function getChineseNameAttribute()
    {
        return $this->service_name['zho'] ?? null;
    }

    public function setChineseNameAttribute($value)
    {
        $names = $this->service_name ?? [];
        $names['zho'] = $value;
        $this->attributes['service_name'] = $names;
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

    public function getChineseOverviewAttribute()
    {
        return $this->service_description_overview['zho'] ?? null;
    }

    public function setChineseOverviewAttribute($value)
    {
        $overview = $this->service_description_overview ?? [];
        $overview['zho'] = $value;
        $this->attributes['service_description_overview'] = $overview;
    }
}
