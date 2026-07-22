<?php

namespace App\Models\Reference;

use MongoDB\Laravel\Eloquent\Model;

/**
 * Read-only reference model. country_main is owned and populated outside this
 * application (docs/04-architecture/database-structure.txt's common_reference
 * database); this model never writes to it.
 */
class Country extends Model
{
    protected $connection = 'mongodb_reference';

    protected $table = 'country_main';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;
}
