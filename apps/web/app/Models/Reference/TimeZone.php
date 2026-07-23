<?php

namespace App\Models\Reference;

use MongoDB\Laravel\Eloquent\Model;

class TimeZone extends Model
{
    protected $connection = 'mongodb_reference';

    protected $table = 'time_zone_main';

    protected $primaryKey = 'time_zone_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];
}
