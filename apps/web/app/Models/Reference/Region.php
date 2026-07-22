<?php

namespace App\Models\Reference;

use MongoDB\Laravel\Eloquent\Model;

/**
 * Read-only reference model. region_main is owned and populated outside this
 * application; this model never writes to it.
 */
class Region extends Model
{
    protected $connection = 'mongodb_reference';

    protected $table = 'region_main';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = ['_id', 'country_id', 'region_name', 'region_key'];
}
