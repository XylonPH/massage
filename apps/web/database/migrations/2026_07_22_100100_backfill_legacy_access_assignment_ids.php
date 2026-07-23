<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $mongodb = DB::connection('mongodb');

        foreach ($mongodb->table('access_assignment')->get() as $legacyRecord) {
            $legacy = (array) $legacyRecord;
            $id = $legacy['_id'] ?? $legacy['id'] ?? null;

            if ($id === null || $mongodb->table('user_access')->where('_id', $id)->exists()) {
                continue;
            }

            $permissions = $legacy['permission_code_list'] ?? [];
            if (is_string($permissions)) {
                $permissions = json_decode($permissions, true) ?: [];
            }

            $mongodb->table('user_access')->insert([
                '_id' => $id,
                'user_id' => $legacy['user_id'],
                'role_workspace' => $legacy['role_workspace'] ?? null,
                'permission_code_list' => $permissions,
                'scope_access' => $legacy['scope_access'] ?? 'GBL',
                'scope_record_id' => $legacy['scope_record_id'] ?? null,
                'status_user_access' => $legacy['status_access_assignment'] ?? 'PND',
                'effective_at' => $legacy['effective_at'] ?? null,
                'expires_at' => $legacy['expires_at'] ?? null,
                'granted_by_user_id' => $legacy['assigned_by_user_id'] ?? $legacy['user_id'],
                'grant_reason' => $legacy['assignment_reason'] ?? 'Migrated from the legacy access assignment record.',
                'is_role_public' => false,
                'public_role_order' => 0,
                'revoked_at' => $legacy['revoked_at'] ?? null,
                'revision_number' => $legacy['revision_number'] ?? 1,
                'created_at' => $legacy['created_at'] ?? now(),
                'updated_at' => $legacy['updated_at'] ?? now(),
            ]);
        }
    }

    public function down(): void
    {
        // Additive safety backfill: do not delete access grants on rollback.
    }
};
