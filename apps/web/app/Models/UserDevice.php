<?php

namespace App\Models;

use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

class UserDevice extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'user_device';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (self $record): void {
            $record->{$record->getKeyName()} ??= Str::random(16);
            $record->status_user_device ??= 'ACT';
            $record->is_recognized ??= false;
            $record->revision_number ??= 1;
        });
    }

    protected function casts(): array
    {
        return [
            'is_recognized' => 'boolean', 'first_seen_at' => 'datetime',
            'last_seen_at' => 'datetime', 'distrusted_at' => 'datetime',
        ];
    }
}
