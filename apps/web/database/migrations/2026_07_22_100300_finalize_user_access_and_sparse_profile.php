<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $mongodb = DB::connection('mongodb');
        $legacyAccess = $mongodb->table('access_assignment');
        $userAccess = $mongodb->table('user_access');

        foreach ($legacyAccess->get() as $legacyRecord) {
            $legacy = (array) $legacyRecord;
            $legacyId = $legacy['_id'] ?? $legacy['id'] ?? null;
            $migrated = $legacyId !== null && $userAccess->where('_id', $legacyId)->exists();

            if (! $migrated) {
                $migrated = $userAccess
                    ->where('user_id', $legacy['user_id'] ?? null)
                    ->where('role_workspace', $legacy['role_workspace'] ?? null)
                    ->get()
                    ->contains(function (object|array $candidate) use ($legacy): bool {
                        $candidate = (array) $candidate;

                        return ($candidate['scope_access'] ?? 'GBL') === ($legacy['scope_access'] ?? 'GBL')
                            && ($candidate['scope_record_id'] ?? null) === ($legacy['scope_record_id'] ?? null);
                    });
            }

            if (! $migrated) {
                throw new RuntimeException('Legacy access remains without a matching user_access record.');
            }
        }

        foreach ($userAccess->get() as $accessRecord) {
            $access = (array) $accessRecord;
            $id = $access['_id'] ?? $access['id'] ?? null;
            if ($id === null) {
                continue;
            }

            $unset = [];
            foreach (['permission_code_list', 'scope_record_id', 'expires_at', 'revoked_at', 'revoked_by_user_id', 'revocation_reason'] as $field) {
                $value = $access[$field] ?? null;
                if ($value === null || $value === [] || $value === '[]' || $value === '') {
                    $unset[$field] = true;
                }
            }

            if ($unset !== []) {
                $userAccess->where(isset($access['_id']) ? '_id' : 'id', $id)->update(['$unset' => $unset]);
            }
        }

        foreach ($mongodb->table('user_main')->get() as $userRecord) {
            $user = (array) $userRecord;
            $id = $user['_id'] ?? $user['id'] ?? null;
            if ($id === null) {
                continue;
            }

            $set = [];
            $appearance = $user['appearance_preference'] ?? null;
            if ($appearance instanceof Traversable) {
                $appearance = iterator_to_array($appearance);
            } elseif (is_object($appearance)) {
                $appearance = get_object_vars($appearance);
            }
            if (is_array($appearance) && array_key_exists('theme_mode', $appearance)) {
                $set['appearance_preference.color_mode'] = $appearance['theme_mode'];
            }

            $unset = ['user_slug' => true, 'bio' => true];
            if (is_array($appearance) && array_key_exists('theme_mode', $appearance)) {
                $unset['appearance_preference.theme_mode'] = true;
            }
            foreach (['display_name', 'profile_biography', 'pronoun_text', 'gender_identity', 'account_preference', 'booking_preference', 'access_summary', 'activity_summary', 'community_summary', 'subscription_summary', 'policy_summary', 'security_summary'] as $field) {
                $value = $user[$field] ?? null;
                if ($value === null || $value === [] || $value === '') {
                    $unset[$field] = true;
                }
            }

            $update = ['$unset' => $unset];
            if ($set !== []) {
                $update['$set'] = $set;
            }

            $mongodb->table('user_main')->where(isset($user['_id']) ? '_id' : 'id', $id)->update($update);
        }

        foreach ($mongodb->getCollection('user_main')->listIndexes() as $index) {
            $keys = (array) $index->getKey();
            if (array_key_exists('user_slug', $keys)) {
                $mongodb->getCollection('user_main')->dropIndex($index->getName());
            }
        }

        $mongodb->getSchemaBuilder()->dropIfExists('access_assignment');
    }

    public function down(): void
    {
        // The removed compatibility collection and duplicate fields are not reconstructed.
    }
};
