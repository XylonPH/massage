<?php
/**
 * Title: Massage Nexus Article Body Structure Guide
 * Author: Xylon Reyes
 *
 * Collection: article_body
 * Version: 1.11
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
 * - article_body stores the long authored body for an article_main record.
 * - Article body content is stored as controlled/theme-aware HTML.
 * - No search_text field is defined in this structure.
 * - References to common_reference records retain that dataset's numeric identifier type.
 */

# Variable
$created_at = '2026-07-06T00:00:00Z';
$updated_at = '2026-07-06T00:00:00Z';

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
	'visibility_scope' => 'INH', // default: inherit visibility
	'level_nsfw' => 'N', // default: None
];

/**
 * Actual record-level defaults for article_body.
 */
$article_body_record_default = [
	'article_body' => '',
	'reading_duration' => null, // optional estimated reading duration in seconds
	'translator_user_id_list' => [],
	'source_article_body_id' => null,
	'method_translation' => null,
	'status_review' => 'P', // P = Pending
	'status_record_lifecycle' => 'ACT',
];

/**
 * article_body sample record.
 */
$article_body = [
	# Primary
	'_id' => 'B6qN1xT8mR3vK9cP', // canonical application-generated 16-character Base62 identifier

	# Parent
	'article_id' => 'A7mK2pQ9xR4tV8zN', // parent article_main._id
	'language_id' => 3049, // English in common_reference.language_main; common_reference IDs remain numeric

	# Body
	'article_body' => '<h2 class="mn-section-title">Before the massage begins</h2><p>Your therapist may ask about pressure, allergies, and areas of discomfort.</p><figure class="mn-image-center" data-media-image-id="M2wH7pL4xQ9nC5vR"><img src="/media/image/M2wH7pL4xQ9nC5vR" alt="Massage room with folded towels"><figcaption>A calm room helps the session feel less intimidating.</figcaption></figure>', // controlled/theme-aware HTML body
	'reading_duration' => 420, // optional estimated reading duration in seconds for this language body

	# Translation / Review
	'translator_user_id_list' => ['U3wD8kN1sR6pX4mQ'], // users who translated this body, empty for original language body
	'source_article_body_id' => null, // source body when this body is a translation
	'method_translation' => null, // null for original language body; HUM, AI, MT, IMP, MIG, UNK when translated/imported
	'status_review' => 'A', // P = Pending, A = Approved, N = Needs Changes, R = Rejected

	# Handling
	'status_record_lifecycle' => 'ACT',

	# Audit
	'created_at' => $created_at,
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'updated_at' => $updated_at,
	'updated_by_user_id' => 'U2pR7vX4kT9mC5qL',
	'reviewed_at' => '2026-07-06T10:00:00Z',
	'reviewed_by_user_id' => 'U6nH1sW8dK3yP9fR',
	'approved_at' => '2026-07-06T10:30:00Z',
	'approved_by_user_id' => 'U6nH1sW8dK3yP9fR',
];

$article_body_field_order = [
	'_id',
	'article_id',
	'language_id',
	'article_body',
	'reading_duration',
	'translator_user_id_list',
	'source_article_body_id',
	'method_translation',
	'status_review',
	'status_record_lifecycle',
	'created_at',
	'created_by_user_id',
	'updated_at',
	'updated_by_user_id',
	'reviewed_at',
	'reviewed_by_user_id',
	'approved_at',
	'approved_by_user_id',
];

$article_body_field_property = [
	# Primary
	'_id' => ['field_label' => 'Article Body ID', 'field_description' => 'Canonical application-generated 16-character Base62 identifier for the article_body record.', 'type_data' => 'S', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_system' => true, 'is_indexed' => true],

	# Parent
	'article_id' => ['field_label' => 'Article ID', 'field_description' => 'Reference to the owning article_main record.', 'type_data' => 'S', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
	'language_id' => ['field_label' => 'Language ID', 'field_description' => 'Numeric reference to common_reference.language_main for this body record.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],

	# Body
	'article_body' => ['field_label' => 'Article Body', 'field_description' => 'Long authored article body stored as controlled/theme-aware HTML. Search systems should not search raw class names or markup as content terms.', 'type_data' => 'S', 'type_field' => 'HTE', 'format_text' => 'HTML', 'is_mandatory' => true],
	'reading_duration' => ['field_label' => 'Reading Duration', 'field_description' => 'Optional estimated reading duration in seconds for this language body. This is an estimate, not a user-specific promise.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],

	# Translation / Review
	'translator_user_id_list' => ['field_label' => 'Translator User ID List', 'field_description' => 'List of user IDs that translated this body record. Empty when the body is original authored content.', 'type_data' => 'A', 'is_relational' => true],
	'source_article_body_id' => ['field_label' => 'Source Article Body ID', 'field_description' => 'Reference to the source article body used for translation, import, or migration.', 'type_data' => 'S', 'is_relational' => true],
	'method_translation' => ['field_label' => 'Translation Method', 'field_description' => 'How this translated body was initially produced. Null for original authored body records.', 'type_field' => 'DDL', 'type_sql' => 'ENUM'],
	'status_review' => [
		'field_label' => 'Review Status',
		'field_description' => 'Review state for this body language version.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			['option_code' => 'P', 'option_label' => 'Pending', 'option_description' => 'Waiting for review.', 'sort_order' => 10],
			['option_code' => 'A', 'option_label' => 'Approved', 'option_description' => 'Reviewed and approved.', 'sort_order' => 20],
			['option_code' => 'N', 'option_label' => 'Needs Changes', 'option_description' => 'Reviewed but requires revision.', 'sort_order' => 30],
			['option_code' => 'R', 'option_label' => 'Rejected', 'option_description' => 'Reviewed and rejected.', 'sort_order' => 40],
		],
		'is_indexed' => true,
	],

	# Handling
	'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state for this body record.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],

	# Audit
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when this body record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'User ID that created this body record.', 'type_data' => 'S', 'is_relational' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp when this body record was last updated.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'updated_by_user_id' => ['field_label' => 'Updated By User ID', 'field_description' => 'User ID that last updated this body record.', 'type_data' => 'S', 'is_relational' => true],
	'reviewed_at' => ['field_label' => 'Reviewed At', 'field_description' => 'UTC timestamp when this body record was reviewed.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'reviewed_by_user_id' => ['field_label' => 'Reviewed By User ID', 'field_description' => 'User ID that reviewed this body record.', 'type_data' => 'S', 'is_relational' => true],
	'approved_at' => ['field_label' => 'Approved At', 'field_description' => 'UTC timestamp when this body record was approved.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'approved_by_user_id' => ['field_label' => 'Approved By User ID', 'field_description' => 'User ID that approved this body record.', 'type_data' => 'S', 'is_relational' => true],
];
