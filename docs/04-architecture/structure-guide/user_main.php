<?php
/**
 * Title: Massage Nexus User Main Structure Guide
 * Author: Xylon Reyes
 *
 * Collection: user_main
 * Version: 0.10
 * This file is a PHP-readable visual structure guide.
 * It is not a seed file, not a runtime migration script, and not a generated
 * production schema. It exists so the database structure can be reviewed in a
 * familiar PHP array format before implementation.
 *
 * Layer rule:
 * - *_record_default contains defaults for actual stored record fields only.
 * - *_field_property describes schema/field metadata only.
 * - Do not mix field-definition metadata into record defaults.
 * Current scope:
 * - user_main stores the account, credential, contact, membership, and basic
 *   personal-profile record for one registered user. Account, credential,
 *   contact, and membership responsibilities stay embedded in this one
 *   collection for the initial implementation, per
 *   docs/07-accounts/account-and-authentication-system.txt
 *   section 18, which also records the later split candidates (sessions,
 *   passkeys, security events, external identities).
 * - This guide documents the fields the application already reads and writes
 *   (App\Models\User) plus the personal-profile fields introduced by the User
 *   Workspace (display_name, bio). Password hashes are Argon2id; plaintext or
 *   reversible storage is prohibited.
 * - Policy-acceptance grant/withdrawal history beyond the cached versions here
 *   belongs to user_policy. Roles and permissions remain an unresolved later
 *   system; no role fields are defined in this version.
 */

# Variable
$created_at = '2026-07-20T00:00:00Z';
$updated_at = '2026-07-20T00:00:00Z';

/**
 * Default field-property values for this structure guide.
 * These describe field-definition metadata, not stored record defaults.
 */
$field_property_default = [
	'type_data' => 'S', // default runtime value shape is String
	'type_field' => 'TXT', // default suggested UI control is Text Box
	'format_text' => 'TXT', // default text format is Plain Text
	'is_translatable' => false, // default: stored field value is not multilingual
	'is_mandatory' => false, // default: not required
	'is_relational' => false, // default: not normally a reference field
	'is_indexed' => false, // default: no common indexing suggestion
	'status_record_lifecycle' => 'ACT', // default: Active
	'visibility_scope' => 'PRV', // default: private account data
	'level_nsfw' => 'N', // default: None
];

/**
 * Actual record-level defaults for user_main.
 * Sparse-default storage may omit these values in actual database records.
 */
$user_main_record_default = [
	'display_name' => null, // public profile falls back to username until set
	'bio' => null,
	'email_verified_at' => null,
	'is_marketing_email_opt_in' => false,
	'status_account' => 'PND',
	'status_membership' => 'PEL',
	'remember_token' => null,
];

/**
 * user_main sample record.
 * This sample intentionally includes populated values so the intended shape can
 * be reviewed. Actual sparse records may omit default values. The person is a
 * fictional demo identity; the password value is a placeholder, never a real hash.
 */
$user_main = [
	# Primary
	'_id' => 'U5rK8mP2xN7qL4vA', // canonical application-generated 16-character Base62 identifier

	# Identity
	'username' => 'wellnessfan7', // lowercase letters and digits, starts with a letter, 4-30 chars, unique
	'display_name' => 'Wellness Fan', // optional public display name; public profile falls back to username
	'bio' => 'Weekend spa explorer. Always chasing the perfect Swedish massage.', // optional public biography text

	# Contact / Credential
	'email' => 'wellnessfan7@example.test', // stored lowercase; unique
	'email_verified_at' => '2026-07-20T01:00:00Z',
	'password' => 'argon2id-hash-placeholder', // Argon2id hash via Laravel hashed cast
	'remember_token' => null,

	# Membership / Eligibility
	'birth_date' => '1990-05-15', // date-only Y-m-d string per Shared Project Standards 8.6 (never midnight UTC); age always derived; 18+ eligibility
	'terms_accepted_at' => '2026-07-20T00:59:00Z',
	'terms_accepted_version' => '2026-07-18.1',
	'privacy_acknowledged_at' => '2026-07-20T00:59:00Z',
	'privacy_acknowledged_version' => '2026-07-18.1',
	'is_marketing_email_opt_in' => false,
	'status_account' => 'ACT', // PND, ACT, LCK, SUS, DEL
	'status_membership' => 'ACT', // PEL, ACT, RST, END

	# Audit
	'created_at' => $created_at,
	'updated_at' => $updated_at,
];

/**
 * Current user_main logical field order.
 */
$user_main_field_order = [
	'_id',
	'username',
	'display_name',
	'bio',
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
	'created_at',
	'updated_at',
];

/**
 * Field-property guide for user_main.
 * These are field-definition properties, not stored record defaults.
 */
$user_main_field_property = [
	# Primary
	'_id' => [
		'field_label' => 'User ID',
		'field_description' => 'Canonical application-generated 16-character Base62 identifier for the user_main record. Referenced by other structures as user_id fields.',
		'type_data' => 'S',
		'min_character' => 16,
		'max_character' => 16,
		'is_mandatory' => true,
		'is_system' => true,
		'is_indexed' => true,
	],

	# Identity
	'username' => [
		'field_label' => 'Username',
		'field_description' => 'Unique login and public handle. Lowercase letters and digits only, starts with a letter, 4-30 characters, protected-name families rejected per the account specification.',
		'min_character' => 4,
		'max_character' => 30,
		'is_mandatory' => true,
		'is_indexed' => true,
	],
	'display_name' => [
		'field_label' => 'Display Name',
		'field_description' => 'Optional public display name shown on the public user profile and community activity. Public presentation falls back to the username when unset.',
		'max_character' => 60,
		'visibility_scope' => 'PUB',
	],
	'bio' => [
		'field_label' => 'Bio',
		'field_description' => 'Optional public biography text for the user profile.',
		'type_field' => 'TXA',
		'max_character' => 1000,
		'visibility_scope' => 'PUB',
	],

	# Contact / Credential
	'email' => [
		'field_label' => 'Email Address',
		'field_description' => 'Verified contact and login identifier. Stored lowercase; unique. Private.',
		'max_character' => 255,
		'is_mandatory' => true,
		'is_indexed' => true,
	],
	'email_verified_at' => ['field_label' => 'Email Verified At', 'field_description' => 'UTC timestamp of successful email verification. Null while pending.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'password' => ['field_label' => 'Password Hash', 'field_description' => 'Argon2id password hash via the framework hashed cast. Never plaintext, never reversible, never exposed.', 'is_mandatory' => true, 'is_system' => true],
	'remember_token' => ['field_label' => 'Remember Token', 'field_description' => 'Rotating remember-me token managed by the framework.', 'is_system' => true],

	# Membership / Eligibility
	'birth_date' => ['field_label' => 'Birth Date', 'field_description' => 'Date of birth for 18+ eligibility. Age is always derived, never stored. Private; self-service editing is not permitted after registration.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'terms_accepted_at' => ['field_label' => 'Terms Accepted At', 'field_description' => 'UTC timestamp of the recorded Terms acceptance for the cached current version.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'terms_accepted_version' => ['field_label' => 'Terms Accepted Version', 'field_description' => 'Cached accepted Terms version string. Full acceptance history belongs to user_policy.', 'is_mandatory' => true],
	'privacy_acknowledged_at' => ['field_label' => 'Privacy Acknowledged At', 'field_description' => 'UTC timestamp of the recorded Privacy Notice acknowledgment for the cached current version.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'privacy_acknowledged_version' => ['field_label' => 'Privacy Acknowledged Version', 'field_description' => 'Cached acknowledged Privacy Notice version string. Full history belongs to user_policy.', 'is_mandatory' => true],
	'is_marketing_email_opt_in' => ['field_label' => 'Marketing Email Opt-In', 'field_description' => 'Separate, optional, unchecked-by-default marketing email consent. Grant and withdrawal history belongs to user_policy.', 'type_data' => 'B', 'type_field' => 'CHK', 'type_sql' => 'BOOLEAN'],
	'status_account' => ['field_label' => 'Account Status', 'field_description' => 'Account lifecycle state taxonomy code (e.g. PND, ACT, LCK, SUS, DEL).', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'status_membership' => ['field_label' => 'Membership Status', 'field_description' => 'Massage Nexus membership state taxonomy code (e.g. PEL, ACT, RST, END). Separate from the shared network account state.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],

	# Audit
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when this user record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp when this user record was last updated.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
];
