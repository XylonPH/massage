<?php

namespace App\Models;

use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

class UserSession extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'user_session';

    protected $primaryKey = '_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(fn (self $record) => $record->{$record->getKeyName()} ??= Str::random(16));
    }

    protected function casts(): array
    {
        return [
            'authenticated_at' => 'datetime', 'last_activity_at' => 'datetime',
            'expires_at' => 'datetime', 'revoked_at' => 'datetime', 'created_at' => 'datetime',
        ];
    }
}
