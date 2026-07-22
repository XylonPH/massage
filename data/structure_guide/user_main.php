<?php
/**
 * Title: Massage Nexus User Main Structure Guide
 * Version: 1.10
 * Collection: user_main
 * Description: Stores one Massage Nexus account, bounded public profile, private identity facts, current preferences, primary references, and rebuildable user summaries.
 * Purpose: Documents the accepted user_main aggregate boundary for account loading, profile presentation, preferences, policy gating, and summary display without replacing authoritative growing user collections.
 *
 * Notes:
 * - Roles and permissions are authoritative in user_access.
 * - Contacts, addresses, policy decisions, social connections, security records, subscriptions, rewards, badges, ranks, reputation events, and contributions remain authoritative in their own user_* collections.
 * - Article, Review, Rating, booking, claim, message, and media histories remain in their owning domains.
 * - Runtime code currently implements only a subset and requires a tested migration before using this complete target shape.
 */

$created_at = '2026-07-18T12:36:56Z';
$updated_at = '2026-07-22T04:55:26Z';

$user_main_default = [
    'type_handedness' => 'UN',
    'visibility_scope' => 'PRV',
    'account_preference' => [],
    'appearance_preference' => ['theme_mode' => 'SYS', 'text_scale_percent' => 100, 'is_high_contrast' => false, 'is_reduced_motion' => false],
    'notification_preference' => ['notification_channel_list' => ['WEB', 'EML'], 'notification_category_list' => ['SEC'], 'digest_frequency' => 'IMM', 'is_marketing_email_opt_in' => false],
    'privacy_preference' => ['visibility_activity' => 'PRV', 'visibility_gender' => 'PRV', 'visibility_handedness' => 'PRV', 'type_birth_date_display' => 'HID', 'is_social_connection_public' => false, 'is_premium_label_public' => false, 'is_analytics_cookie_allowed' => false, 'is_personalization_cookie_allowed' => false, 'is_marketing_cookie_allowed' => false],
    'booking_preference' => [],
    'access_summary' => [],
    'activity_summary' => [],
    'community_summary' => [],
    'subscription_summary' => [],
    'policy_summary' => [],
    'security_summary' => [],
    'status_account' => 'PND',
    'status_membership' => 'PEL',
    'revision_number' => 1,
];

$user_main = [
    '_id' => 'U2pR7vX4kT9mC5qL', // Canonical 16-character account identifier.
    'username' => 'wellnessfan7', // Unique normalized account handle.
    'user_slug' => 'wellness-fan-7', // Stable public profile route value.
    'display_name' => 'Wellness Fan', // Optional moderated public display name.
    'profile_biography' => 'Weekend spa explorer who enjoys thoughtful reviews and directory research.', // Optional public biography.
    'pronoun_text' => 'they/them', // Optional self-provided pronouns.
    'gender_identity' => 'NB', // Optional privacy-controlled gender identity.
    'type_handedness' => 'RH', // Optional privacy-controlled dominant-hand classification.
    'birth_date' => '1990-05-15', // Private date-only eligibility fact.
    'default_avatar_key' => 'resting-leaf-03', // Deterministic branded fallback avatar.
    'avatar_media_image_id' => 'Mi7K2pQ9xR4tV8zN', // Optional uploaded avatar media reference.
    'cover_media_image_id' => 'Mi9mC5qL2pR7vX4k', // Optional uploaded cover media reference.
    'visibility_scope' => 'PUB', // Overall public-profile audience.
    'account_preference' => [
        'interface_language_id' => 3049,
        'fallback_language_id' => 3049,
        'content_language_id_list' => [3049],
        'time_zone_id' => 583,
        'regional_format_code' => 'en-PH',
    ],
    'appearance_preference' => [
        'theme_mode' => 'SYS',
        'text_scale_percent' => 100,
        'is_high_contrast' => false,
        'is_reduced_motion' => false,
    ],
    'notification_preference' => [
        'notification_channel_list' => ['WEB', 'EML'],
        'notification_category_list' => ['SEC', 'BKG', 'CON'],
        'digest_frequency' => 'IMM',
        'is_marketing_email_opt_in' => false,
    ],
    'privacy_preference' => [
        'visibility_activity' => 'PUB',
        'visibility_gender' => 'PUB',
        'visibility_handedness' => 'PUB',
        'type_birth_date_display' => 'AGE',
        'is_social_connection_public' => true,
        'is_premium_label_public' => false,
        'is_analytics_cookie_allowed' => false,
        'is_personalization_cookie_allowed' => false,
        'is_marketing_cookie_allowed' => false,
        'cookie_preference_decided_at' => '2026-07-22T02:51:15Z',
    ],
    'booking_preference' => [
        'preferred_language_id' => 3049,
        'level_booking_pressure_preference' => 'MED',
        'pressure_adjustment_preference_list' => ['CHK'],
        'body_area_preference_list' => [],
        'product_preference_list' => [],
        'attire_preference' => 'NOP',
        'undressing_preference' => 'NOP',
        'treatment_contact_preference' => 'ASK',
        'draping_preference' => 'STD',
        'treatment_support_preference' => 'NOP',
        'communication_preference' => 'QET',
        'therapist_selection_preference' => 'NPR',
        'last_confirmed_at' => '2026-07-22T02:51:15Z',
    ],
    'primary_user_contact_id' => 'Ct7K2pQ9xR4tV8zN', // Primary personal contact reference.
    'primary_booking_contact_id' => 'Ct9mC5qL2pR7vX4k', // Default verified booking contact reference.
    'default_user_address_id' => 'Ad7K2pQ9xR4tV8zN', // Default saved booking address reference.
    'own_practitioner_id' => null, // Cached verified Self practitioner reference.
    'access_summary' => [
        'primary_public_role_workspace' => 'FND',
        'active_user_access_count' => 1,
        'managed_establishment_count' => 0,
        'managed_practitioner_count' => 0,
        'calculated_at' => '2026-07-22T02:51:15Z',
    ],
    'activity_summary' => [
        'article_published_count' => 3,
        'review_published_count' => 5,
        'contribution_submitted_count' => 8,
        'contribution_accepted_count' => 6,
        'rating_count' => 12,
        'media_approved_count' => 4,
        'calculated_at' => '2026-07-22T02:51:15Z',
    ],
    'community_summary' => [
        'nexus_point_total' => 2450,
        'ember_balance' => 860,
        'inkling_balance' => 320,
        'reputation_score' => 3650,
        'reputation_level' => 4,
        'status_reputation' => 'NRM',
        'selected_rank_path_id' => 'Rp7K2pQ9xR4tV8zN',
        'selected_rank_level' => 3,
        'badge_count' => 6,
        'featured_user_badge_id_list' => ['Ub7K2pQ9xR4tV8zN'],
        'calculated_at' => '2026-07-22T02:51:15Z',
    ],
    'subscription_summary' => [
        'subscription_plan_code' => 'FREE',
        'status_subscription' => 'ACT',
        'subscription_ends_at' => null,
        'active_entitlement_count' => 0,
        'calculated_at' => '2026-07-22T02:51:15Z',
    ],
    'policy_summary' => [
        'status_policy_compliance' => 'CMP',
        'required_policy_action_count' => 0,
        'checked_at' => '2026-07-22T02:51:15Z',
    ],
    'security_summary' => [
        'active_session_count' => 2,
        'recognized_device_count' => 2,
        'passkey_count' => 1,
        'is_mfa_enabled' => true,
        'last_security_event_at' => '2026-07-22T02:40:00Z',
        'calculated_at' => '2026-07-22T02:51:15Z',
    ],
    'email' => 'wellnessfan7@example.test', // Synchronized normalized primary login-email cache.
    'email_verified_at' => '2026-07-18T12:48:21Z', // Verification time for the cached primary login email.
    'password' => '$argon2id$v=19$m=65536,t=4,p=1$example-only', // Password hash only.
    'remember_token' => 'hashed-or-random-remember-token', // Revocable remember-me token.
    'status_account' => 'ACT', // Network-account lifecycle state.
    'status_membership' => 'ACT', // Massage Nexus membership state.
    'revision_number' => 1, // Optimistic-concurrency token.
    'created_at' => '2026-07-18T12:36:56Z', // UTC account creation time.
    'updated_at' => '2026-07-22T02:51:15Z', // UTC latest accepted update time.
];

$user_main_field_order = [
    '_id', 'username', 'user_slug', 'display_name', 'profile_biography', 'pronoun_text',
    'gender_identity', 'type_handedness', 'birth_date', 'default_avatar_key', 'avatar_media_image_id',
    'cover_media_image_id', 'visibility_scope', 'account_preference', 'appearance_preference',
    'notification_preference', 'privacy_preference', 'booking_preference',
    'primary_user_contact_id', 'primary_booking_contact_id', 'default_user_address_id',
    'own_practitioner_id', 'access_summary', 'activity_summary', 'community_summary',
    'subscription_summary', 'policy_summary', 'security_summary', 'email', 'email_verified_at',
    'password', 'remember_token', 'status_account', 'status_membership', 'revision_number',
    'created_at', 'updated_at',
];

$user_main_embedded_structure = [
    'account_preference' => ['interface_language_id' => 3049, 'fallback_language_id' => 3049, 'content_language_id_list' => [3049], 'time_zone_id' => 583, 'regional_format_code' => 'en-PH'],
    'appearance_preference' => ['theme_mode' => 'SYS', 'text_scale_percent' => 100, 'is_high_contrast' => false, 'is_reduced_motion' => false],
    'notification_preference' => ['notification_channel_list' => ['WEB'], 'notification_category_list' => ['SEC'], 'digest_frequency' => 'IMM', 'is_marketing_email_opt_in' => false],
    'privacy_preference' => ['visibility_activity' => 'PUB', 'visibility_gender' => 'PUB', 'visibility_handedness' => 'PUB', 'type_birth_date_display' => 'AGE', 'is_social_connection_public' => true, 'is_premium_label_public' => false, 'is_analytics_cookie_allowed' => false, 'is_personalization_cookie_allowed' => false, 'is_marketing_cookie_allowed' => false, 'cookie_preference_decided_at' => '2026-07-22T02:51:15Z'],
    'booking_preference' => ['preferred_language_id' => 3049, 'level_booking_pressure_preference' => 'MED', 'pressure_adjustment_preference_list' => ['CHK'], 'body_area_preference_list' => [], 'product_preference_list' => [], 'attire_preference' => 'NOP', 'undressing_preference' => 'NOP', 'treatment_contact_preference' => 'ASK', 'draping_preference' => 'STD', 'treatment_support_preference' => 'NOP', 'communication_preference' => 'QET', 'therapist_selection_preference' => 'NPR', 'last_confirmed_at' => '2026-07-22T02:51:15Z'],
    'access_summary' => ['primary_public_role_workspace' => 'FND', 'active_user_access_count' => 1, 'managed_establishment_count' => 0, 'managed_practitioner_count' => 0, 'calculated_at' => '2026-07-22T02:51:15Z'],
    'activity_summary' => ['article_published_count' => 3, 'review_published_count' => 5, 'contribution_submitted_count' => 8, 'contribution_accepted_count' => 6, 'rating_count' => 12, 'media_approved_count' => 4, 'calculated_at' => '2026-07-22T02:51:15Z'],
    'community_summary' => ['nexus_point_total' => 2450, 'ember_balance' => 860, 'inkling_balance' => 320, 'reputation_score' => 3650, 'reputation_level' => 4, 'status_reputation' => 'NRM', 'selected_rank_path_id' => 'Rp7K2pQ9xR4tV8zN', 'selected_rank_level' => 3, 'badge_count' => 6, 'featured_user_badge_id_list' => ['Ub7K2pQ9xR4tV8zN'], 'calculated_at' => '2026-07-22T02:51:15Z'],
    'subscription_summary' => ['subscription_plan_code' => 'FREE', 'status_subscription' => 'ACT', 'subscription_ends_at' => null, 'active_entitlement_count' => 0, 'calculated_at' => '2026-07-22T02:51:15Z'],
    'policy_summary' => ['status_policy_compliance' => 'CMP', 'required_policy_action_count' => 0, 'checked_at' => '2026-07-22T02:51:15Z'],
    'security_summary' => ['active_session_count' => 2, 'recognized_device_count' => 2, 'passkey_count' => 1, 'is_mfa_enabled' => true, 'last_security_event_at' => '2026-07-22T02:40:00Z', 'calculated_at' => '2026-07-22T02:51:15Z'],
];

$user_main_field_property = [
    '_id' => ['field_label' => 'User ID', 'field_description' => 'Canonical application-generated 16-character account identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'username' => ['field_label' => 'Username', 'field_description' => 'Unique normalized lowercase alphanumeric account handle.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 3, 'max_character' => 30, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_slug' => ['field_label' => 'User Slug', 'field_description' => 'Stable URL-safe public profile route value.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 3, 'max_character' => 80, 'is_indexed' => true, 'is_unique' => true],
    'display_name' => ['field_label' => 'Display Name', 'field_description' => 'Optional moderated public name with username fallback.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 2, 'max_character' => 60],
    'profile_biography' => ['field_label' => 'Profile Biography', 'field_description' => 'Optional user-provided public biography.', 'type_data' => 'S', 'type_field' => 'TXA', 'min_character' => 20, 'max_character' => 1000],
    'pronoun_text' => ['field_label' => 'Pronouns', 'field_description' => 'Optional self-provided pronoun presentation.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 1, 'max_character' => 40],
    'gender_identity' => ['field_label' => 'Gender Identity', 'field_description' => 'Optional privacy-controlled self-declared gender identity.', 'type_data' => 'S', 'type_field' => 'DDL', 'taxonomy_field_name' => 'gender_identity'],
    'type_handedness' => ['field_label' => 'Handedness', 'field_description' => 'Optional privacy-controlled self-declared dominant hand using the shared practitioner handedness taxonomy; omission means Unknown and the value is never inferred.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'default_value' => 'UN', 'taxonomy_field_name' => 'type_handedness'],
    'birth_date' => ['field_label' => 'Birth Date', 'field_description' => 'Private date-only eligibility fact; public presentation is derived separately.', 'type_data' => 'S', 'type_field' => 'DTI', 'is_mandatory' => true, 'visibility_scope' => 'PRV'],
    'default_avatar_key' => ['field_label' => 'Default Avatar Key', 'field_description' => 'Approved deterministic non-gendered branded avatar key.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 80],
    'avatar_media_image_id' => ['field_label' => 'Avatar Media Image', 'field_description' => 'Optional uploaded avatar media_image identifier.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'cover_media_image_id' => ['field_label' => 'Cover Media Image', 'field_description' => 'Optional uploaded cover media_image identifier.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'visibility_scope' => ['field_label' => 'Profile Visibility', 'field_description' => 'Audience permitted to view the complete user profile.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'PRV', 'is_mandatory' => true, 'is_indexed' => true],
    'account_preference' => ['field_label' => 'Account Preferences', 'field_description' => 'Bounded current language, region, and time-zone choices.', 'type_data' => 'O', 'type_field' => 'JSE', 'default_value' => []],
    'appearance_preference' => ['field_label' => 'Appearance Preferences', 'field_description' => 'Bounded current theme and accessible presentation choices.', 'type_data' => 'O', 'type_field' => 'JSE', 'default_value' => ['theme_mode' => 'SYS', 'text_scale_percent' => 100, 'is_high_contrast' => false, 'is_reduced_motion' => false]],
    'notification_preference' => ['field_label' => 'Notification Preferences', 'field_description' => 'Bounded current channel, category, digest, and marketing choices.', 'type_data' => 'O', 'type_field' => 'JSE', 'default_value' => ['notification_channel_list' => ['WEB', 'EML'], 'notification_category_list' => ['SEC'], 'digest_frequency' => 'IMM', 'is_marketing_email_opt_in' => false]],
    'privacy_preference' => ['field_label' => 'Privacy Preferences', 'field_description' => 'Bounded current public-presentation, cookie, and personalization choices.', 'type_data' => 'O', 'type_field' => 'JSE', 'default_value' => ['visibility_activity' => 'PRV', 'visibility_gender' => 'PRV', 'visibility_handedness' => 'PRV', 'type_birth_date_display' => 'HID', 'is_social_connection_public' => false, 'is_premium_label_public' => false, 'is_analytics_cookie_allowed' => false, 'is_personalization_cookie_allowed' => false, 'is_marketing_cookie_allowed' => false]],
    'booking_preference' => ['field_label' => 'Booking Preferences', 'field_description' => 'One bounded current reusable booking-preference set; excludes time-sensitive intake.', 'type_data' => 'O', 'type_field' => 'JSE', 'default_value' => [], 'visibility_scope' => 'PRV'],
    'primary_user_contact_id' => ['field_label' => 'Primary User Contact', 'field_description' => 'Default personal user_contact reference.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'primary_booking_contact_id' => ['field_label' => 'Primary Booking Contact', 'field_description' => 'Default verified user_contact used for booking.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'default_user_address_id' => ['field_label' => 'Default User Address', 'field_description' => 'Default private user_address used when explicitly selected for booking.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'own_practitioner_id' => ['field_label' => 'Own Practitioner', 'field_description' => 'Cached practitioner_main identifier from the active verified Self link.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'access_summary' => ['field_label' => 'Access Summary', 'field_description' => 'Rebuildable presentation-only summary of effective user_access.', 'type_data' => 'O', 'type_field' => 'JSE', 'default_value' => []],
    'activity_summary' => ['field_label' => 'Activity Summary', 'field_description' => 'Rebuildable bounded activity counts from authoritative domain records.', 'type_data' => 'O', 'type_field' => 'JSE', 'default_value' => []],
    'community_summary' => ['field_label' => 'Community Summary', 'field_description' => 'Rebuildable reward, badge, Rank, and Reputation presentation values.', 'type_data' => 'O', 'type_field' => 'JSE', 'default_value' => []],
    'subscription_summary' => ['field_label' => 'Subscription Summary', 'field_description' => 'Rebuildable current personal plan and entitlement summary.', 'type_data' => 'O', 'type_field' => 'JSE', 'default_value' => []],
    'policy_summary' => ['field_label' => 'Policy Summary', 'field_description' => 'Rebuildable current policy-gate status without acceptance evidence.', 'type_data' => 'O', 'type_field' => 'JSE', 'default_value' => []],
    'security_summary' => ['field_label' => 'Security Summary', 'field_description' => 'Rebuildable counts and state from authoritative security records.', 'type_data' => 'O', 'type_field' => 'JSE', 'default_value' => [], 'visibility_scope' => 'PRV'],
    'email' => ['field_label' => 'Login Email Cache', 'field_description' => 'Synchronized normalized primary login email used by current authentication.', 'type_data' => 'S', 'type_field' => 'EML', 'max_character' => 255, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true, 'visibility_scope' => 'PRV'],
    'email_verified_at' => ['field_label' => 'Email Verified At', 'field_description' => 'UTC verification time for the cached primary login email.', 'type_data' => 'S', 'type_field' => 'DTS', 'visibility_scope' => 'PRV'],
    'password' => ['field_label' => 'Password Hash', 'field_description' => 'One-way password hash; plaintext is never stored.', 'type_data' => 'S', 'type_field' => 'PWD', 'is_mandatory' => true, 'visibility_scope' => 'PRV'],
    'remember_token' => ['field_label' => 'Remember Token', 'field_description' => 'Revocable random or hashed remember-me token.', 'type_data' => 'S', 'type_field' => 'HDN', 'visibility_scope' => 'PRV'],
    'status_account' => ['field_label' => 'Account Status', 'field_description' => 'Current network-account lifecycle state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'PND', 'is_mandatory' => true, 'is_indexed' => true],
    'status_membership' => ['field_label' => 'Membership Status', 'field_description' => 'Current Massage Nexus membership state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'PEL', 'is_mandatory' => true, 'is_indexed' => true],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC account creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest accepted account update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];

$user_main_subfield_property = [
    'account_preference.interface_language_id' => ['field_label' => 'Interface Language', 'field_description' => 'Preferred interface language reference.', 'type_data' => 'I', 'type_field' => 'REF', 'is_relational' => true],
    'account_preference.fallback_language_id' => ['field_label' => 'Fallback Language', 'field_description' => 'Fallback language reference used when preferred text is unavailable.', 'type_data' => 'I', 'type_field' => 'REF', 'is_relational' => true],
    'account_preference.content_language_id_list' => ['field_label' => 'Content Languages', 'field_description' => 'Ordered preferred content-language references.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true],
    'account_preference.time_zone_id' => ['field_label' => 'Time Zone', 'field_description' => 'Preferred time-zone reference for display and interpretation.', 'type_data' => 'I', 'type_field' => 'REF', 'is_relational' => true],
    'account_preference.regional_format_code' => ['field_label' => 'Regional Format', 'field_description' => 'Locale-style presentation code for dates, numbers, currency, and measurements.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 20],
    'appearance_preference.theme_mode' => ['field_label' => 'Theme Mode', 'field_description' => 'System, light, or dark appearance choice.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'appearance_preference.text_scale_percent' => ['field_label' => 'Text Scale', 'field_description' => 'Preferred interface text scale percentage.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 80, 'max_number' => 200],
    'appearance_preference.is_high_contrast' => ['field_label' => 'High Contrast', 'field_description' => 'Whether increased contrast is requested.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'appearance_preference.is_reduced_motion' => ['field_label' => 'Reduced Motion', 'field_description' => 'Whether optional interface motion should be reduced.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'notification_preference.notification_channel_list' => ['field_label' => 'Notification Channels', 'field_description' => 'Enabled ordinary notification delivery channels.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'notification_preference.notification_category_list' => ['field_label' => 'Notification Categories', 'field_description' => 'Enabled ordinary notification categories.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'notification_preference.digest_frequency' => ['field_label' => 'Digest Frequency', 'field_description' => 'Preferred delivery frequency for eligible notification digests.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'notification_preference.is_marketing_email_opt_in' => ['field_label' => 'Marketing Email Opt-In', 'field_description' => 'Current optional marketing-email choice; decision history belongs to user_policy.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'privacy_preference.visibility_activity' => ['field_label' => 'Activity Visibility', 'field_description' => 'Audience permitted to view eligible public user activity.', 'type_data' => 'S', 'type_field' => 'DDL', 'taxonomy_field_name' => 'visibility_scope'],
    'privacy_preference.visibility_gender' => ['field_label' => 'Gender Visibility', 'field_description' => 'Audience permitted to view the optional gender identity.', 'type_data' => 'S', 'type_field' => 'DDL', 'taxonomy_field_name' => 'visibility_scope'],
    'privacy_preference.visibility_handedness' => ['field_label' => 'Handedness Visibility', 'field_description' => 'Audience permitted to view the optional handedness value.', 'type_data' => 'S', 'type_field' => 'DDL', 'taxonomy_field_name' => 'visibility_scope'],
    'privacy_preference.type_birth_date_display' => ['field_label' => 'Birthday Display', 'field_description' => 'Hidden, age, age-range, or month-and-day presentation.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'privacy_preference.is_social_connection_public' => ['field_label' => 'Social Connections Public', 'field_description' => 'Default display choice for eligible connected social profiles.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'privacy_preference.is_premium_label_public' => ['field_label' => 'Premium Label Public', 'field_description' => 'Whether an eligible Premium Member label may appear publicly.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'privacy_preference.is_analytics_cookie_allowed' => ['field_label' => 'Analytics Cookies Allowed', 'field_description' => 'Current authenticated preference for optional analytics cookies.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'privacy_preference.is_personalization_cookie_allowed' => ['field_label' => 'Personalization Cookies Allowed', 'field_description' => 'Current authenticated preference for optional personalization cookies.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'privacy_preference.is_marketing_cookie_allowed' => ['field_label' => 'Marketing Cookies Allowed', 'field_description' => 'Current authenticated preference for optional marketing cookies.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'privacy_preference.cookie_preference_decided_at' => ['field_label' => 'Cookie Preference Decided At', 'field_description' => 'UTC time the current authenticated cookie preference was chosen.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'booking_preference.preferred_language_id' => ['field_label' => 'Preferred Booking Language', 'field_description' => 'Preferred communication language for booking.', 'type_data' => 'I', 'type_field' => 'REF', 'is_relational' => true],
    'booking_preference.level_booking_pressure_preference' => ['field_label' => 'Preferred Massage Pressure', 'field_description' => 'Default requested massage-pressure level.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'booking_preference.pressure_adjustment_preference_list' => ['field_label' => 'Pressure Adjustment Preferences', 'field_description' => 'Default communication choices for pressure changes.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'booking_preference.body_area_preference_list' => ['field_label' => 'Body-Area Preferences', 'field_description' => 'Bounded reusable focus and avoid-area preferences.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'booking_preference.product_preference_list' => ['field_label' => 'Product Preferences', 'field_description' => 'Bounded reusable product, medium, scent, oil, or lotion preferences.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'booking_preference.attire_preference' => ['field_label' => 'Attire Preference', 'field_description' => 'Default permitted attire preference.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'booking_preference.undressing_preference' => ['field_label' => 'Undressing Preference', 'field_description' => 'Default permitted undressing preference.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'booking_preference.treatment_contact_preference' => ['field_label' => 'Treatment Contact Preference', 'field_description' => 'Default permitted treatment-contact preference.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'booking_preference.draping_preference' => ['field_label' => 'Draping Preference', 'field_description' => 'Default permitted draping preference.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'booking_preference.treatment_support_preference' => ['field_label' => 'Treatment Support Preference', 'field_description' => 'Default permitted support or positioning preference.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'booking_preference.communication_preference' => ['field_label' => 'Communication Preference', 'field_description' => 'Default in-session conversation or communication preference.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'booking_preference.therapist_selection_preference' => ['field_label' => 'Therapist Selection Preference', 'field_description' => 'Default therapist-selection choice.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'booking_preference.last_confirmed_at' => ['field_label' => 'Booking Preferences Confirmed At', 'field_description' => 'UTC time the user last confirmed the saved defaults.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'access_summary.primary_public_role_workspace' => ['field_label' => 'Primary Public Role', 'field_description' => 'Presentation-only primary role code derived from public effective user_access.', 'type_data' => 'S', 'type_field' => 'DDL', 'taxonomy_field_name' => 'role_workspace'],
    'access_summary.active_user_access_count' => ['field_label' => 'Active User Access Count', 'field_description' => 'Rebuildable count of effective user_access grants.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'access_summary.managed_establishment_count' => ['field_label' => 'Managed Establishment Count', 'field_description' => 'Rebuildable count of establishments with effective management access.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'access_summary.managed_practitioner_count' => ['field_label' => 'Managed Practitioner Count', 'field_description' => 'Rebuildable count of practitioners with effective management access.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'access_summary.calculated_at' => ['field_label' => 'Access Summary Calculated At', 'field_description' => 'UTC time the access summary was rebuilt.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'activity_summary.article_published_count' => ['field_label' => 'Published Article Count', 'field_description' => 'Rebuildable count of eligible published Article bylines.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'activity_summary.review_published_count' => ['field_label' => 'Published Review Count', 'field_description' => 'Rebuildable count of eligible published Reviews.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'activity_summary.contribution_submitted_count' => ['field_label' => 'Submitted Contribution Count', 'field_description' => 'Rebuildable count of submitted user_contribution records.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'activity_summary.contribution_accepted_count' => ['field_label' => 'Accepted Contribution Count', 'field_description' => 'Rebuildable count of accepted user_contribution records.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'activity_summary.rating_count' => ['field_label' => 'Rating Count', 'field_description' => 'Rebuildable count of eligible Ratings submitted by the user.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'activity_summary.media_approved_count' => ['field_label' => 'Approved Media Count', 'field_description' => 'Rebuildable count of approved media contributions.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'activity_summary.calculated_at' => ['field_label' => 'Activity Summary Calculated At', 'field_description' => 'UTC time the activity summary was rebuilt.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'community_summary.nexus_point_total' => ['field_label' => 'Nexus Points', 'field_description' => 'Rebuildable lifetime useful-activity point total.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'community_summary.ember_balance' => ['field_label' => 'Ember Balance', 'field_description' => 'Rebuildable current spendable Ember balance.', 'type_data' => 'I', 'type_field' => 'NMB'],
    'community_summary.inkling_balance' => ['field_label' => 'Inkling Balance', 'field_description' => 'Rebuildable current Inkling balance.', 'type_data' => 'I', 'type_field' => 'NMB'],
    'community_summary.reputation_score' => ['field_label' => 'Reputation Score', 'field_description' => 'Rebuildable current Reputation score from 0 to 10,000.', 'type_data' => 'F', 'type_field' => 'NMB', 'min_number' => 0, 'max_number' => 10000],
    'community_summary.reputation_level' => ['field_label' => 'Reputation Level', 'field_description' => 'Rebuildable current Reputation level from 1 to 8.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'max_number' => 8],
    'community_summary.status_reputation' => ['field_label' => 'Reputation Status', 'field_description' => 'Current trust-effect status derived from reputation and moderation state.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'community_summary.selected_rank_path_id' => ['field_label' => 'Selected Rank Path', 'field_description' => 'rank_path_main selected for primary public presentation.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'community_summary.selected_rank_level' => ['field_label' => 'Selected Rank Level', 'field_description' => 'Current eligible level within the selected path.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1],
    'community_summary.badge_count' => ['field_label' => 'Badge Count', 'field_description' => 'Rebuildable count of current eligible public user badges.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'community_summary.featured_user_badge_id_list' => ['field_label' => 'Featured User Badges', 'field_description' => 'Rebuildable ordered projection of up to six active public user_badge identifiers.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true],
    'community_summary.calculated_at' => ['field_label' => 'Community Summary Calculated At', 'field_description' => 'UTC time the community summary was rebuilt.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'subscription_summary.subscription_plan_code' => ['field_label' => 'Subscription Plan Code', 'field_description' => 'Current personal plan presentation code.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 40],
    'subscription_summary.status_subscription' => ['field_label' => 'Subscription Status', 'field_description' => 'Current personal subscription lifecycle summary.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'subscription_summary.subscription_ends_at' => ['field_label' => 'Subscription Ends At', 'field_description' => 'UTC expected end of the current paid period.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'subscription_summary.active_entitlement_count' => ['field_label' => 'Active Entitlement Count', 'field_description' => 'Rebuildable count of currently effective user entitlements.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'subscription_summary.calculated_at' => ['field_label' => 'Subscription Summary Calculated At', 'field_description' => 'UTC time the subscription summary was rebuilt.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'policy_summary.status_policy_compliance' => ['field_label' => 'Policy Compliance Status', 'field_description' => 'Current derived required-policy gate state.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'policy_summary.required_policy_action_count' => ['field_label' => 'Required Policy Action Count', 'field_description' => 'Number of required current policy decisions still outstanding.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'policy_summary.checked_at' => ['field_label' => 'Policy Checked At', 'field_description' => 'UTC time policy compliance was last evaluated.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'security_summary.active_session_count' => ['field_label' => 'Active Session Count', 'field_description' => 'Rebuildable count of active user sessions.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'security_summary.recognized_device_count' => ['field_label' => 'Recognized Device Count', 'field_description' => 'Rebuildable count of recognized user devices.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'security_summary.passkey_count' => ['field_label' => 'Passkey Count', 'field_description' => 'Rebuildable count of active WebAuthn passkeys.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'security_summary.is_mfa_enabled' => ['field_label' => 'MFA Enabled', 'field_description' => 'Whether at least one confirmed MFA method is active.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'security_summary.last_security_event_at' => ['field_label' => 'Last Security Event At', 'field_description' => 'UTC time of the latest user-visible security event.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'security_summary.calculated_at' => ['field_label' => 'Security Summary Calculated At', 'field_description' => 'UTC time the security summary was rebuilt.', 'type_data' => 'S', 'type_field' => 'DTS'],
];

$user_main_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'username_unique', 'index_name' => 'uq_user_main_username', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'username', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 20],
    ['index_key' => 'email_unique', 'index_name' => 'uq_user_main_email', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'email', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 30],
    ['index_key' => 'slug_unique', 'index_name' => 'uq_user_main_user_slug', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => true, 'index_field_list' => [['field_name' => 'user_slug', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 40],
    ['index_key' => 'account_membership', 'index_name' => 'ix_user_main_account_membership', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'status_account', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_membership', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 50],
    ['index_key' => 'profile_visibility', 'index_name' => 'ix_user_main_profile_visibility', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'visibility_scope', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 60],
];

$user_main_boundary = [
    'owns' => ['account identity and credential cache', 'bounded public profile', 'private birth date plus optional gender identity and handedness', 'bounded current preferences', 'primary references', 'rebuildable presentation summaries'],
    'reference_field_list' => ['avatar_media_image_id', 'cover_media_image_id', 'account_preference.interface_language_id', 'account_preference.fallback_language_id', 'account_preference.content_language_id_list', 'account_preference.time_zone_id', 'booking_preference.preferred_language_id', 'primary_user_contact_id', 'primary_booking_contact_id', 'default_user_address_id', 'own_practitioner_id', 'community_summary.selected_rank_path_id', 'community_summary.featured_user_badge_id_list'],
    'does_not_own' => ['growing contact, address, policy, access, social, security, subscription, reward, badge, Rank, Reputation, saved-item, follow, notification, or contribution records', 'professional practitioner identity', 'media binaries', 'domain activity history'],
];

return [
    'user_main_default' => $user_main_default,
    'user_main' => $user_main,
    'user_main_field_order' => $user_main_field_order,
    'user_main_embedded_structure' => $user_main_embedded_structure,
    'user_main_field_property' => $user_main_field_property,
    'user_main_subfield_property' => $user_main_subfield_property,
    'user_main_index_list' => $user_main_index_list,
    'user_main_boundary' => $user_main_boundary,
];
