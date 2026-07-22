<?php

namespace App\Models;

use App\Models\Concerns\HasSparseDefaults;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable([
    'user_id', 'role_workspace', 'permission_code_list', 'scope_access',
    'scope_record_id', 'status_user_access', 'effective_at', 'expires_at',
    'granted_by_user_id', 'grant_reason', 'is_role_public', 'public_role_order',
    'revoked_at', 'revoked_by_user_id', 'revocation_reason', 'revision_number',
])]
class UserAccess extends Model
{
    use HasSparseDefaults;

    protected $connection = 'mongodb';

    protected $table = 'user_access';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function (self $access): void {
            $access->{$access->getKeyName()} ??= Str::random(16);
        });
    }

    protected function casts(): array
    {
        return [
            'permission_code_list' => 'array',
            'effective_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_role_public' => 'boolean',
            'public_role_order' => 'integer',
            'revoked_at' => 'datetime',
            'revision_number' => 'integer',
        ];
    }

    protected function sparseDefaults(): array
    {
        return [
            'permission_code_list' => [],
            'scope_access' => 'GBL',
            'status_user_access' => 'PND',
            'is_role_public' => false,
            'public_role_order' => 0,
            'revision_number' => 1,
        ];
    }

    public function scopeEffective(Builder $query): Builder
    {
        return $query
            ->where('status_user_access', 'ACT')
            ->where(function (Builder $query): void {
                $query->whereNull('effective_at')->orWhere('effective_at', '<=', now());
            })
            ->where(function (Builder $query): void {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->whereNull('revoked_at');
    }
}
