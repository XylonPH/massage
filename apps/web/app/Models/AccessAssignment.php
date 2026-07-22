<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id', 'role_workspace', 'permission_code_list', 'scope_access',
    'scope_record_id', 'status_access_assignment', 'effective_at',
    'expires_at', 'assigned_by_user_id', 'assignment_reason', 'revoked_at',
])]
/**
 * @deprecated Runtime authorization now uses UserAccess and the user_access
 * collection. This compatibility adapter only keeps older extensions and
 * in-flight tests from writing to the retired collection.
 */
class AccessAssignment extends UserAccess
{
    public function setAttribute($key, $value): static
    {
        $key = match ($key) {
            'status_access_assignment' => 'status_user_access',
            'assigned_by_user_id' => 'granted_by_user_id',
            'assignment_reason' => 'grant_reason',
            default => $key,
        };

        return parent::setAttribute($key, $value);
    }
}
