<?php
/**
 * Title: Massage Nexus Practitioner Main Structure Guide
 * Version: 0.20
 * Collection: practitioner_main
 * Description: Stores one practitioner professional profile independently of a user account or employer.
 * Purpose: Documents the practitioner_main record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * This file is a PHP-readable visual structure guide.
 * It is not a seed file, not a runtime migration script, and not a generated
 * production schema. It exists so the database structure can be reviewed in a
 * familiar PHP array format before implementation.
 *
 * Layer rule:
 * - *_default contains omission defaults for actual stored record fields only.
 * - *_field_property describes schema/field metadata only.
 * - Do not mix field-definition metadata into record defaults.
 * Current scope:
 * - practitioner_main stores one practitioner's identity, therapist-level
 *   classification, practice status, cached public statistics, and record
 *   handling. Practitioner is the technical term; public UI may label the
 *   person as therapist, masseur, or masseuse depending on context
 *   (docs/04-architecture/database-structure.txt section 8).
 * - Establishment affiliations live in establishment_practitioner; per-context
 *   Work Arrangement, Provider Rank, Market Class, Availability Status,
 *   Booking Requirement, and Client Access belong to that relationship, not
 *   here (docs/05-directory/therapist-classification.txt).
 * - Services and capabilities live in practitioner_service; credentials live
 *   in practitioner_credential; reviews and ratings live in the shared
 *   review/rating domain. Verification is faceted and owned by
 *   verification_main; this collection stores no blanket verified flag
 *   (docs/05-directory/therapist-profile.txt sections 1 and 12).
 * - Location and service-area representation is deferred pending the shared
 *   location reference integration; only free-text summary display is used by
 *   the current demo layer.
 */

# Variable
$created_at = '2026-07-20T00:00:00Z';
$updated_at = '2026-07-21T04:24:17Z';
/**
 * Actual record-level defaults for practitioner_main.
 * Sparse-default storage may omit these values in actual database records.
 */
$practitioner_main_default = [
	'type_practice_setting' => [],
	'type_specialty_focus' => [],
	'target_client_focus' => [],
	'status_therapist_practice' => 'UN', // UN = Unknown
	'is_claimed' => false,
	'rating_official' => null, // no official score until the Rating System display threshold is met
	'rating_count' => 0,
	'review_count' => 0,
	'view_count' => 0,
	'save_count' => 0,
	'follow_count' => 0,
	'visibility_scope' => 'PUB', // PUB = Public directory record
	'level_nsfw' => 'N', // N = None
	'status_record_lifecycle' => 'ACT', // ACT = Active
	'record_note' => [],
];

$multilingual_text_sample = [
	'eng' => [
		'text' => 'Sample practitioner text',
		'method_translation' => 'HUM',
		'status_review' => 'A',
	],
];

/**
 * practitioner_main sample record.
 * This sample intentionally includes populated values so the intended shape can
 * be reviewed. Actual sparse records may omit default values. All people are
 * fictional demo identities.
 */
$practitioner_main = [
	# Primary
	'_id' => 'P8rC3mL7xT1qV5nK', // canonical application-generated 16-character Base62 identifier

	# Core
	'practitioner_name' => [ // Approved public professional name. Multilingual bounded text so transliterations remain connected to the original name. Private legal names are never stored here.
		'eng' => [
			'text' => 'Maya Santos',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
	], // approved public professional name; multilingual bounded text for transliteration support
	'practitioner_slug' => [ // Multilingual URL-safe slug text for public therapist routes. Values should use kebab-case. The stable _id, not the slug, prevents duplicate records.
		'eng' => [
			'text' => 'maya-santos',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
	], // multilingual public URL slug text; values must be kebab-case
	'short_description' => [ // Optional multilingual professional summary for the identity header, listing cards, and search snippets. Each language text value should not exceed 255 characters.
		'eng' => [
			'text' => 'Calm, detail-focused therapist specializing in Swedish and deep tissue massage for stress recovery.',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
	], // optional; maximum 255 characters per language text value
	'biography' => [ // Optional longer public professional biography. Multilingual text; machine translation must remain identified per the Translation System.
		'eng' => [
			'text' => 'Maya has practiced professional massage for over nine years across spa and independent settings. She focuses on pressure communication, careful draping, and helping first-time clients feel at ease.',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
	], // optional longer public biography; multilingual text

	# Parent / Classification
	'language_original_id' => 'L8pD3sW6nC1yH5qR', // original authored language of name/biography source content
	'type_practice_setting' => ['SP', 'PS', 'CR'], // multi-select; Spa, Private Studio, Client Residence
	'type_specialty_focus' => ['RLX', 'DP', 'STR'], // multi-select; Relaxation, Deep Pressure, Stress Relief
	'target_client_focus' => ['GP', 'OW', 'AT'], // multi-select; General Public, Office Workers, Athletes
	'status_therapist_practice' => 'AC', // AC = Active, IN = Inactive, RT = Retired, UN = Unknown

	# Claim
	'is_claimed' => true, // cached claim state; the authoritative claim workflow record lives in claim_main

	# Cached Statistic
	'rating_official' => 4.9, // cached official Rating System score; null until the display threshold is met
	'rating_count' => 214, // cached count of eligible rating events; distinct from review_count
	'review_count' => 128, // cached count of published reviews
	'view_count' => 5420, // Cached total number of recorded profile views.
	'save_count' => 310, // Cached total number of user saves/bookmarks for this profile.
	'follow_count' => 96, // Cached total number of users following this profile. Follower identities remain private workspace data.

	# Handling
	'visibility_scope' => 'PUB', // PUB = Public
	'level_nsfw' => 'N', // N = None
	'status_record_lifecycle' => 'ACT', // ACT = Active
	'record_note' => [ // Embedded internal notes attached to this profile record. Never public.
		[
			'type_record_note' => 'AD', // AD = Admin Note
			'note_body' => 'Profile created from verified field research; awaiting practitioner claim contact.',
			'created_at' => '2026-07-20T08:00:00Z', // UTC timestamp when this profile record was created.
			'created_by_user_id' => 'U2pR7vX4kT9mC5qL', // User ID that created this profile record.
		],
	], // embedded internal notes for this record; never public

	# Audit
	'created_at' => $created_at,
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'updated_at' => $updated_at, // UTC timestamp when this profile record was last updated.
	'updated_by_user_id' => 'U2pR7vX4kT9mC5qL', // User ID that last updated this profile record.
	'last_confirmed_at' => '2026-07-18T00:00:00Z', // freshness timestamp per docs/05-directory/therapist-profile.txt section 12
	'archived_at' => null, // UTC timestamp when the profile record was archived.
	'archived_by_user_id' => null, // User ID that archived the profile record.
];

/**
 * Current practitioner_main logical field order.
 */
$practitioner_main_field_order = [
	'_id',
	'practitioner_name',
	'practitioner_slug',
	'short_description',
	'biography',
	'language_original_id',
	'type_practice_setting',
	'type_specialty_focus',
	'target_client_focus',
	'status_therapist_practice',
	'is_claimed',
	'rating_official',
	'rating_count',
	'review_count',
	'view_count',
	'save_count',
	'follow_count',
	'visibility_scope',
	'level_nsfw',
	'status_record_lifecycle',
	'record_note',
	'created_at',
	'created_by_user_id',
	'updated_at',
	'updated_by_user_id',
	'last_confirmed_at',
	'archived_at',
	'archived_by_user_id',
];

/**
 * Field-property guide for practitioner_main.
 * These are field-definition properties, not stored record defaults.
 * type_practice_setting, type_specialty_focus, target_client_focus, and
 * status_therapist_practice option lists are owned by
 * data/taxonomy/massage_nexus/practitioner_classification.json and are not
 * duplicated here.
 */

$practitioner_main_embedded_structure = [];

$practitioner_main_field_property = [
	# Primary
	'_id' => [
		'field_label' => 'Practitioner ID',
		'field_description' => 'Canonical application-generated 16-character Base62 identifier for the practitioner_main record. Referenced by other structures as practitioner_id.',
		'type_data' => 'S',
		'min_character' => 16,
		'max_character' => 16,
		'is_mandatory' => true,
		'is_system' => true,
		'is_indexed' => true,
	],

	# Core
	'practitioner_name' => [
		'field_label' => 'Practitioner Name',
		'field_description' => 'Approved public professional name. Multilingual bounded text so transliterations remain connected to the original name. Private legal names are never stored here.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'is_translatable' => true,
		'is_mandatory' => true,
	],
	'practitioner_slug' => [
		'field_label' => 'Practitioner Slug',
		'field_description' => 'Multilingual URL-safe slug text for public therapist routes. Values should use kebab-case. The stable _id, not the slug, prevents duplicate records.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'is_translatable' => true,
		'is_mandatory' => true,
		'is_indexed' => true,
	],
	'short_description' => [
		'field_label' => 'Short Description',
		'field_description' => 'Optional multilingual professional summary for the identity header, listing cards, and search snippets. Each language text value should not exceed 255 characters.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'is_translatable' => true,
		'max_character' => 255,
	],
	'biography' => [
		'field_label' => 'Biography',
		'field_description' => 'Optional longer public professional biography. Multilingual text; machine translation must remain identified per the Translation System.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'is_translatable' => true,
	],

	# Parent / Classification
	'language_original_id' => [
		'field_label' => 'Original Language ID',
		'field_description' => 'Reference to the language in which the name and biography source content was originally authored.',
		'type_data' => 'S',
		'is_relational' => true,
		'is_indexed' => true,
	],
	'type_practice_setting' => [
		'field_label' => 'Practice Setting',
		'field_description' => 'Multi-select recurring professional environments. Options owned by practitioner_classification.json.',
		'type_data' => 'A',
		'type_field' => 'TGL',
		'is_indexed' => true,
	],
	'type_specialty_focus' => [
		'field_label' => 'Specialty Focus',
		'field_description' => 'Multi-select specialty focus areas. Named services and massage systems are service capabilities in practitioner_service, not Specialty Focus values. Options owned by practitioner_classification.json.',
		'type_data' => 'A',
		'type_field' => 'TGL',
		'is_indexed' => true,
	],
	'target_client_focus' => [
		'field_label' => 'Client Focus',
		'field_description' => 'Multi-select client groups the practitioner has services or experience for. Client Focus does not restrict access; Client Access rules are per professional context. Options owned by practitioner_classification.json.',
		'type_data' => 'A',
		'type_field' => 'TGL',
	],
	'status_therapist_practice' => [
		'field_label' => 'Professional Practice Status',
		'field_description' => 'Overall current practice status for the therapist. Separate from per-context Availability Status and from person life status. Options owned by practitioner_classification.json.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'is_indexed' => true,
	],

	# Claim
	'is_claimed' => [
		'field_label' => 'Is Claimed',
		'field_description' => 'Cached indicator that the profile has been claimed through the individual therapist identity-verification workflow. The authoritative claim record lives in claim_main. An unclaimed profile cannot receive confirmed reservations or payment.',
		'type_data' => 'B',
		'type_field' => 'CHK',
		'type_sql' => 'BOOLEAN',
		'is_indexed' => true,
	],

	# Cached Statistic
	'rating_official' => ['field_label' => 'Official Rating', 'field_description' => 'Cached official Rating System score for this therapist target. Null until the public display threshold is met. Internal precision rules follow the Rating System.', 'type_data' => 'F', 'type_field' => 'NMB', 'type_sql' => 'DECIMAL', 'min_number' => 1, 'max_number' => 10],
	'rating_count' => ['field_label' => 'Rating Count', 'field_description' => 'Cached count of eligible rating events. Displayed separately from review_count per the Rating System.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'review_count' => ['field_label' => 'Review Count', 'field_description' => 'Cached count of published reviews for this therapist target.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'view_count' => ['field_label' => 'View Count', 'field_description' => 'Cached total number of recorded profile views.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'save_count' => ['field_label' => 'Save Count', 'field_description' => 'Cached total number of user saves/bookmarks for this profile.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'follow_count' => ['field_label' => 'Follow Count', 'field_description' => 'Cached total number of users following this profile. Follower identities remain private workspace data.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],

	# Handling
	'visibility_scope' => ['field_label' => 'Visibility Scope', 'field_description' => 'Audience visibility rule for the profile record. Suppression can remove public display without deleting lawfully necessary restricted records.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'level_nsfw' => ['field_label' => 'NSFW Level', 'field_description' => 'Content-sensitivity level. Adult or sexual services must never be inferred from identity, client access, or massage system per docs/05-directory/therapist-profile.txt section 15.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state such as active, archived, deleted, or retired. Separate from Professional Practice Status.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'record_note' => ['field_label' => 'Record Note', 'field_description' => 'Embedded internal notes attached to this profile record. Never public.', 'type_data' => 'A', 'type_field' => 'JSE'],

	# Audit
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when this profile record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'User ID that created this profile record.', 'type_data' => 'S', 'is_relational' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp when this profile record was last updated.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'updated_by_user_id' => ['field_label' => 'Updated By User ID', 'field_description' => 'User ID that last updated this profile record.', 'type_data' => 'S', 'is_relational' => true],
	'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'UTC timestamp when profile facts were last confirmed through research, claim activity, or verification. Freshness expectations vary by field per the provenance system.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'archived_at' => ['field_label' => 'Archived At', 'field_description' => 'UTC timestamp when the profile record was archived.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'archived_by_user_id' => ['field_label' => 'Archived By User ID', 'field_description' => 'User ID that archived the profile record.', 'type_data' => 'S', 'is_relational' => true],
];

$practitioner_main_subfield_property = [];

$practitioner_main_index_list = [
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
];

$practitioner_main_boundary = [
    'owns' => [
        'the practitioner_main record fields and embedded structures documented in this file',
    ],
    'reference_field_list' => [
        'language_original_id',
        'created_by_user_id',
        'updated_by_user_id',
        'archived_by_user_id',
    ],
    'does_not_own' => [
        'records stored in referenced collections',
        'runtime authorization, migration, seeding, or deployment behavior',
    ],
];

return [
    'practitioner_main_default' => $practitioner_main_default,
    'multilingual_text_sample' => $multilingual_text_sample,
    'practitioner_main' => $practitioner_main,
    'practitioner_main_field_order' => $practitioner_main_field_order,
    'practitioner_main_embedded_structure' => $practitioner_main_embedded_structure,
    'practitioner_main_field_property' => $practitioner_main_field_property,
    'practitioner_main_subfield_property' => $practitioner_main_subfield_property,
    'practitioner_main_index_list' => $practitioner_main_index_list,
    'practitioner_main_boundary' => $practitioner_main_boundary,
];
