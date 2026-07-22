<?php

namespace App\Models\Reference;

use MongoDB\Laravel\Eloquent\Model;

/**
 * Read-only reference model. city_main does not exist yet (see the separate
 * "PH Geographic Reference Data" project) — queries against it safely return
 * no rows until that project seeds it.
 */
class City extends Model
{
    protected $connection = 'mongodb_reference';

    protected $table = 'city_main';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;
}
