<?php
/**
 * Title: Massage Nexus User Main Structure Guide
 * Version: 0.40
 * Collection: user_main
 * Description: Stores one Massage Nexus account, credential, membership, bounded public-profile, consent-cache, and current account-preference record.
 * Purpose: Documents the user_main record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * - Roles and permissions belong to access_assignment, never to user_main.
 * - Article, Review, contribution, badge, rank, Reputation, and reward history remain in their authoritative records and are derived for profile display.
 * - Detailed reusable booking and intake preferences belong to client_booking_default; account_preference contains only small current settings normally loaded with the user.
 * - Avatar and cover binaries and metadata belong to media_image; this record stores only references and the deterministic default-avatar key.
 * - Runtime support currently covers the account fields, display_name, profile_biography, and marketing opt-in. The additional accepted profile and preference fields require implementation before they appear in the website.
 */

$created_at = '2026-07-20T05:51:15Z';
$updated_at = '2026-07-21T13:06:44Z';

$user_main_default = [
    'user_slug' => null,
    'display_name' => null,
    'profile_biography' => null,
    'default_avatar_key' => null,
    'avatar_media_image_id' => null,
    'cover_media_image_id' => null,
    'visibility_scope' => 'PRV',
    'account_preference' => [],
    'email_verified_at' => null,
    'is_marketing_email_opt_in' => false,
    'status_account' => 'PND',
    'status_membership' => 'PEL',
    'remember_token' => null,
    'revision_number' => 1,
];

$user_main = [
    '_id' => 'U5rK8mP2xN7qL4vA', // Canonical application-generated 16-character Base62 identifier.
    'username' => 'wellnessfan7', // Unique lowercase login handle and public fallback name.
    'user_slug' => 'wellness-fan', // Stable public profile route value; null until public-profile routing is implemented.
    'display_name' => 'Wellness Fan', // Optional public display name with username fallback.
    'profile_biography' => 'Weekend spa explorer. Always chasing the perfect Swedish massage.', // Optional public account biography, distinct from a practitioner biography.
    'default_avatar_key' => 'leaf-ember-03', // Deterministically selected approved default-avatar asset key.
    'avatar_media_image_id' => 'M4vN8hQ7dP2kR9xC', // Optional uploaded avatar media_image identifier.
    'cover_media_image_id' => 'M7dP2kR9xC4vN8hQ', // Optional public-profile cover media_image identifier.
    'visibility_scope' => 'PUB', // Visibility of the public user-profile surface; private is the omission default.
    'account_preference' => [ // Small current settings normally loaded and changed with this user.
        'interface_language_id' => 3049, // common_reference.language_main identifier for interface presentation.
        'fallback_language_id' => 3049, // Optional fallback common-reference language identifier.
        'time_zone_id' => 255, // common_reference.time_zone_main identifier; 255 represents Asia/Manila in the current dataset.
    ],
    'email' => 'wellnessfan7@example.test', // Private normalized and unique login/contact email address.
    'email_verified_at' => '2026-07-20T01:00:00Z', // UTC time of successful email verification.
    'password' => 'argon2id-hash-placeholder', // Argon2id password hash placeholder; never plaintext or reversible.
    'remember_token' => null, // Rotating framework-managed remember-me token.
    'birth_date' => '1990-05-15', // Private date-only birth date used for eligibility; age is derived.
    'terms_accepted_at' => '2026-07-20T00:59:00Z', // UTC time of the cached current Terms acceptance.
    'terms_accepted_version' => '2026-07-18.1', // Cached accepted Terms version; authoritative history belongs to user_policy.
    'privacy_acknowledged_at' => '2026-07-20T00:59:00Z', // UTC time of the cached current Privacy Notice acknowledgment.
    'privacy_acknowledged_version' => '2026-07-18.1', // Cached acknowledged Privacy Notice version; authoritative history belongs to user_policy.
    'is_marketing_email_opt_in' => false, // Current convenience value; grant and withdrawal history belongs to user_policy.
    'status_account' => 'ACT', // Shared account lifecycle status.
    'status_membership' => 'ACT', // Massage Nexus membership status, separate from account lifecycle.
    'revision_number' => 1, // Optimistic-concurrency token incremented for every accepted record revision.
    'created_at' => $created_at, // UTC time when this user record was created.
    'updated_at' => $updated_at, // UTC time when this user record was last updated.
];

$user_main_field_order = [
    '_id',
    'username',
    'user_slug',
    'display_name',
    'profile_biography',
    'default_avatar_key',
    'avatar_media_image_id',
    'cover_media_image_id',
    'visibility_scope',
    'account_preference',
    'email',
    'email_verified_at',
    'password',
    'remember_token',
    'birth_date',
    'terms_accepted_at',
    'terms_accepted_version',
    'privacy_acknowledged_at',
    'privacy_acknowledged_version',
    'is_marketing_email_opt_in',
    'status_account',
    'status_membership',
    'revision_number',
    'created_at',
    'updated_at',
];

$user_main_embedded_structure = [
    'account_preference' => [
        'interface_language_id' => 3049,
        'fallback_language_id' => 3049,
        'time_zone_id' => 255,
    ],
];

$user_main_field_property = [
    '_id' => ['field_label' => 'User ID', 'field_description' => 'Canonical application-generated 16-character Base62 identifier referenced by related Massage Nexus records.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'CHAR(16)', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_system' => true, 'is_indexed' => true, 'is_unique' => true],
    'username' => ['field_label' => 'Username', 'field_description' => 'Unique login handle and public-name fallback using lowercase letters and digits, beginning with a letter.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(30)', 'min_character' => 4, 'max_character' => 30, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_slug' => ['field_label' => 'User Slug', 'field_description' => 'Stable URL-safe route value for the future public user profile, separate from the mutable display name.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(80)', 'max_character' => 80, 'constraint_text_input' => ['SLG'], 'is_indexed' => true, 'is_unique' => true],
    'display_name' => ['field_label' => 'Display Name', 'field_description' => 'Optional public account name; public presentation falls back to username when absent.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(60)', 'max_character' => 60, 'visibility_scope' => 'PUB'],
    'profile_biography' => ['field_label' => 'Profile Biography', 'field_description' => 'Optional public account biography distinct from a practitioner professional biography.', 'type_data' => 'S', 'type_field' => 'TXA', 'type_sql' => 'TEXT', 'max_character' => 1000, 'visibility_scope' => 'PUB'],
    'default_avatar_key' => ['field_label' => 'Default Avatar Key', 'field_description' => 'Approved deterministic default-avatar asset key used when no uploaded avatar is active.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(80)', 'max_character' => 80, 'is_system' => true],
    'avatar_media_image_id' => ['field_label' => 'Avatar Media Image', 'field_description' => 'Optional media_image identifier for the active uploaded public avatar; storage and moderation metadata remain in the media record.', 'type_data' => 'S', 'type_field' => 'REF', 'type_sql' => 'CHAR(16)', 'is_relational' => true],
    'cover_media_image_id' => ['field_label' => 'Cover Media Image', 'field_description' => 'Optional media_image identifier for the public profile cover; storage and moderation metadata remain in the media record.', 'type_data' => 'S', 'type_field' => 'REF', 'type_sql' => 'CHAR(16)', 'is_relational' => true],
    'visibility_scope' => ['field_label' => 'Profile Visibility', 'field_description' => 'Maximum audience allowed to access the public user-profile surface; absence means Private.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'default_value' => 'PRV', 'is_mandatory' => true, 'is_indexed' => true],
    'account_preference' => ['field_label' => 'Account Preference', 'field_description' => 'Bounded embedded current interface and regional settings normally loaded and changed with the user account.', 'type_data' => 'O', 'type_field' => 'JSE', 'type_sql' => 'JSON', 'default_value' => []],
    'email' => ['field_label' => 'Email Address', 'field_description' => 'Private normalized verified-contact and login identifier; stored lowercase and unique.', 'type_data' => 'S', 'type_field' => 'EML', 'type_sql' => 'VARCHAR(255)', 'max_character' => 255, 'constraint_text_input' => ['EML'], 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true, 'visibility_scope' => 'PRV'],
    'email_verified_at' => ['field_label' => 'Email Verified At', 'field_description' => 'UTC time when the current email address completed verification; null while pending.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'password' => ['field_label' => 'Password Hash', 'field_description' => 'Argon2id password hash managed by the authentication framework; never plaintext, reversible, or exposed.', 'type_data' => 'S', 'type_field' => 'PWD', 'type_sql' => 'VARCHAR(255)', 'is_mandatory' => true, 'is_system' => true, 'visibility_scope' => 'PRV'],
    'remember_token' => ['field_label' => 'Remember Token', 'field_description' => 'Rotating framework-managed remember-me token that is never exposed as profile data.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(255)', 'is_system' => true, 'visibility_scope' => 'PRV'],
    'birth_date' => ['field_label' => 'Birth Date', 'field_description' => 'Private date-only birth date used for 18+ eligibility; age is derived and ordinary self-service editing is prohibited.', 'type_data' => 'S', 'type_field' => 'DTI', 'type_sql' => 'DATE', 'is_mandatory' => true, 'visibility_scope' => 'PRV'],
    'terms_accepted_at' => ['field_label' => 'Terms Accepted At', 'field_description' => 'UTC time of the cached acceptance for the current Terms version; complete evidence history belongs to user_policy.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true, 'visibility_scope' => 'PRV'],
    'terms_accepted_version' => ['field_label' => 'Terms Accepted Version', 'field_description' => 'Cached current Terms version accepted by the user; authoritative history belongs to user_policy.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(80)', 'max_character' => 80, 'is_mandatory' => true, 'visibility_scope' => 'PRV'],
    'privacy_acknowledged_at' => ['field_label' => 'Privacy Acknowledged At', 'field_description' => 'UTC time of the cached acknowledgment for the current Privacy Notice; complete evidence history belongs to user_policy.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true, 'visibility_scope' => 'PRV'],
    'privacy_acknowledged_version' => ['field_label' => 'Privacy Acknowledged Version', 'field_description' => 'Cached current Privacy Notice version acknowledged by the user; authoritative history belongs to user_policy.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(80)', 'max_character' => 80, 'is_mandatory' => true, 'visibility_scope' => 'PRV'],
    'is_marketing_email_opt_in' => ['field_label' => 'Marketing Email Opt-In', 'field_description' => 'Current optional marketing-email convenience value; grant and withdrawal history belongs to user_policy.', 'type_data' => 'B', 'type_field' => 'CHK', 'type_sql' => 'BOOLEAN', 'default_value' => false, 'visibility_scope' => 'PRV'],
    'status_account' => ['field_label' => 'Account Status', 'field_description' => 'Current shared account lifecycle code, independent from Massage Nexus membership and workspace assignments.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'default_value' => 'PND', 'is_mandatory' => true, 'is_indexed' => true, 'visibility_scope' => 'PRV'],
    'status_membership' => ['field_label' => 'Membership Status', 'field_description' => 'Current Massage Nexus eligibility and membership code, separate from account lifecycle and workspace assignments.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'default_value' => 'PEL', 'is_mandatory' => true, 'is_indexed' => true, 'visibility_scope' => 'PRV'],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Optimistic-concurrency token that starts at 1 and increments for every accepted user record revision.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'default_value' => 1, 'min_number' => 1, 'is_mandatory' => true, 'is_system' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC time when the user record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true, 'is_system' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC time when the user record was last changed.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true, 'is_system' => true],
];

$user_main_subfield_property = [
    'account_preference.interface_language_id' => ['field_label' => 'Interface Language', 'field_description' => 'Numeric common_reference.language_main identifier used for the user interface when supported.', 'type_data' => 'I', 'type_field' => 'REF', 'type_sql' => 'INT', 'is_relational' => true],
    'account_preference.fallback_language_id' => ['field_label' => 'Fallback Language', 'field_description' => 'Optional numeric common_reference.language_main identifier used when preferred interface or content text is unavailable.', 'type_data' => 'I', 'type_field' => 'REF', 'type_sql' => 'INT', 'is_relational' => true],
    'account_preference.time_zone_id' => ['field_label' => 'Time Zone', 'field_description' => 'Numeric common_reference.time_zone_main identifier used to present and interpret user-local date and time values.', 'type_data' => 'I', 'type_field' => 'REF', 'type_sql' => 'INT', 'is_relational' => true],
];

$user_main_index_list = [
    [
        'index_key' => 'primary',
        'index_name' => '_id_',
        'type_index' => 'STD',
        'is_unique' => true,
        'is_sparse' => false,
        'index_field_list' => [
            ['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10],
        ],
        'sort_order' => 10,
    ],
    [
        'index_key' => 'username_unique',
        'index_name' => 'uq_user_main_username',
        'type_index' => 'STD',
        'is_unique' => true,
        'is_sparse' => false,
        'index_field_list' => [
            ['field_name' => 'username', 'type_index_mode' => 'ASC', 'sort_order' => 10],
        ],
        'sort_order' => 20,
    ],
    [
        'index_key' => 'email_unique',
        'index_name' => 'uq_user_main_email',
        'type_index' => 'STD',
        'is_unique' => true,
        'is_sparse' => false,
        'index_field_list' => [
            ['field_name' => 'email', 'type_index_mode' => 'ASC', 'sort_order' => 10],
        ],
        'sort_order' => 30,
    ],
    [
        'index_key' => 'user_slug_unique',
        'index_name' => 'uq_user_main_user_slug',
        'type_index' => 'STD',
        'is_unique' => true,
        'is_sparse' => true,
        'index_field_list' => [
            ['field_name' => 'user_slug', 'type_index_mode' => 'ASC', 'sort_order' => 10],
        ],
        'sort_order' => 40,
    ],
    [
        'index_key' => 'account_membership_status',
        'index_name' => 'ix_user_main_status_account_status_membership',
        'type_index' => 'CMP',
        'is_unique' => false,
        'is_sparse' => false,
        'index_field_list' => [
            ['field_name' => 'status_account', 'type_index_mode' => 'ASC', 'sort_order' => 10],
            ['field_name' => 'status_membership', 'type_index_mode' => 'ASC', 'sort_order' => 20],
        ],
        'sort_order' => 50,
    ],
    [
        'index_key' => 'public_profile_visibility',
        'index_name' => 'ix_user_main_visibility_scope',
        'type_index' => 'STD',
        'is_unique' => false,
        'is_sparse' => false,
        'index_field_list' => [
            ['field_name' => 'visibility_scope', 'type_index_mode' => 'ASC', 'sort_order' => 10],
        ],
        'sort_order' => 60,
    ],
];

$user_main_boundary = [
    'owns' => ['account identity, credential, membership, bounded public-profile fields, consent convenience values, and the embedded current account_preference object'],
    'reference_field_list' => ['avatar_media_image_id', 'cover_media_image_id', 'account_preference.interface_language_id', 'account_preference.fallback_language_id', 'account_preference.time_zone_id'],
    'does_not_own' => ['workspace roles or permissions', 'detailed booking or intake defaults', 'media records or binaries', 'policy decision history', 'activity records', 'badges', 'rank progress', 'reputation events', 'reward-ledger history', 'runtime authorization, migration, seeding, or deployment behavior'],
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
