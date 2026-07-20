<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable([
    'user_id', 'role_workspace', 'permission_code_list', 'scope_access',
    'scope_record_id', 'status_access_assignment', 'effective_at',
    'expires_at', 'assigned_by_user_id', 'assignment_reason', 'revoked_at',
])]
class AccessAssignment extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'access_assignment';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function (self $assignment): void {
            $assignment->{$assignment->getKeyName()} ??= Str::random(16);
            $assignment->status_access_assignment ??= 'PND';
            $assignment->scope_access ??= 'GBL';
        });
    }

    protected function casts(): array
    {
        return [
            'permission_code_list' => 'array',
            'effective_at' => 'datetime',
            'expires_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public function scopeEffective(Builder $query): Builder
    {
        return $query
            ->where('status_access_assignment', 'ACT')
            ->where(function (Builder $query): void {
                $query->whereNull('effective_at')->orWhere('effective_at', '<=', now());
            })
            ->where(function (Builder $query): void {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }
}
