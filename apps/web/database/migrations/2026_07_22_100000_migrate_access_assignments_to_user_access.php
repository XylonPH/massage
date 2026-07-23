<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $legacyRecords = DB::connection('mongodb')->table('access_assignment')->get();

        foreach ($legacyRecords as $legacyRecord) {
            $legacy = (array) $legacyRecord;
            $id = $legacy['_id'] ?? $legacy['id'] ?? null;

            if ($id === null || DB::connection('mongodb')->table('user_access')->where('_id', $id)->exists()) {
                continue;
            }

            $migrated = $legacy;
            $migrated['_id'] = $id;
            if (is_string($migrated['permission_code_list'] ?? null)) {
                $migrated['permission_code_list'] = json_decode($migrated['permission_code_list'], true) ?: [];
            }
            $migrated['status_user_access'] = $legacy['status_access_assignment'] ?? 'PND';
            $migrated['granted_by_user_id'] = $legacy['assigned_by_user_id'] ?? $legacy['user_id'] ?? null;
            $migrated['grant_reason'] = $legacy['assignment_reason'] ?? 'Migrated from the legacy access assignment record.';
            $migrated['revision_number'] = $legacy['revision_number'] ?? 1;
            unset(
                $migrated['status_access_assignment'],
                $migrated['assigned_by_user_id'],
                $migrated['assignment_reason'],
                $migrated['id'],
            );

            DB::connection('mongodb')->table('user_access')->insert($migrated);
        }

        Schema::connection('mongodb')->table('user_access', function (Blueprint $table): void {
            $table->index(['user_id', 'status_user_access', 'scope_access', 'scope_record_id']);
            $table->index(['effective_at', 'expires_at']);
            $table->index('role_workspace');
        });

        Schema::connection('mongodb')->table('user_main', function (Blueprint $table): void {
            $table->index('visibility_scope');
        });
    }

    public function down(): void
    {
        // The migration is intentionally additive. Retain migrated grants and
        // so rolling application code back cannot destroy access.
    }
};
