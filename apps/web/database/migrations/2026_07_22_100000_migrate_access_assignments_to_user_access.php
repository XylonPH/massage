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
            $id = $legacy['_id'] ?? null;

            if ($id === null || DB::connection('mongodb')->table('user_access')->where('_id', $id)->exists()) {
                continue;
            }

            $migrated = $legacy;
            $migrated['status_user_access'] = $legacy['status_access_assignment'] ?? 'PND';
            $migrated['granted_by_user_id'] = $legacy['assigned_by_user_id'] ?? $legacy['user_id'] ?? null;
            $migrated['grant_reason'] = $legacy['assignment_reason'] ?? 'Migrated from the legacy access assignment record.';
            $migrated['revision_number'] = $legacy['revision_number'] ?? 1;
            unset(
                $migrated['status_access_assignment'],
                $migrated['assigned_by_user_id'],
                $migrated['assignment_reason'],
            );

            DB::connection('mongodb')->table('user_access')->insert($migrated);
        }

        Schema::connection('mongodb')->table('user_access', function (Blueprint $table): void {
            $table->index(['user_id', 'status_user_access', 'scope_access', 'scope_record_id']);
            $table->index(['effective_at', 'expires_at']);
            $table->index('role_workspace');
        });

        Schema::connection('mongodb')->table('user_main', function (Blueprint $table): void {
            $table->unique('user_slug');
            $table->index('visibility_scope');
        });

        DB::connection('mongodb')->table('user_main')
            ->whereNull('user_slug')
            ->get()
            ->each(function (object $user): void {
                $values = (array) $user;
                if (! isset($values['_id'], $values['username'])) {
                    return;
                }

                DB::connection('mongodb')->table('user_main')
                    ->where('_id', $values['_id'])
                    ->update(['user_slug' => (string) $values['username']]);
            });
    }

    public function down(): void
    {
        // The migration is intentionally additive. Retain migrated grants and
        // user slugs so rolling application code back cannot destroy access.
    }
};
