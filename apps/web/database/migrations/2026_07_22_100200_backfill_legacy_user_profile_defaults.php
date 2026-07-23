<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $users = DB::connection('mongodb')->table('user_main');

        foreach ($users->get() as $userRecord) {
            $user = (array) $userRecord;
            $userId = $user['_id'] ?? $user['id'] ?? null;

            if ($userId === null || ! isset($user['username'])) {
                continue;
            }

            $updates = [];
            $updates['profile_biography'] = $user['profile_biography'] ?? $user['bio'] ?? null;
            $updates['type_handedness'] = $user['type_handedness'] ?? 'UN';
            $updates['visibility_scope'] = $user['visibility_scope'] ?? 'PRV';
            $updates['account_preference'] = $user['account_preference'] ?? [];
            $updates['appearance_preference'] = $user['appearance_preference'] ?? [
                'color_mode' => 'SYS',
                'text_scale_percent' => 100,
                'is_high_contrast' => false,
                'is_reduced_motion' => false,
            ];
            $updates['notification_preference'] = $user['notification_preference'] ?? [
                'notification_channel_list' => ['WEB', 'EML'],
                'notification_category_list' => ['SEC'],
                'digest_frequency' => 'IMM',
                'is_marketing_email_opt_in' => (bool) ($user['is_marketing_email_opt_in'] ?? false),
            ];
            $updates['privacy_preference'] = $user['privacy_preference'] ?? [
                'visibility_activity' => 'PRV',
                'visibility_gender' => 'PRV',
                'visibility_handedness' => 'PRV',
                'type_birth_date_display' => 'HID',
                'is_social_connection_public' => false,
                'is_premium_label_public' => false,
                'is_analytics_cookie_allowed' => false,
                'is_personalization_cookie_allowed' => false,
                'is_marketing_cookie_allowed' => false,
            ];
            $updates['booking_preference'] = $user['booking_preference'] ?? [];
            $updates['revision_number'] = $user['revision_number'] ?? 1;

            $users->where(isset($user['_id']) ? '_id' : 'id', $userId)->update($updates);
        }
    }

    public function down(): void
    {
        // Additive compatibility backfill: retain migrated profile preferences.
    }
};
