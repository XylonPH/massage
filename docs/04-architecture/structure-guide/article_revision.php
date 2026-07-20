<?php
/**
 * Title: Massage Nexus Article Revision Structure Guide
 * Author: Xylon Reyes
 *
 * Collection: article_revision
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
 * - article_revision stores body snapshots for review, rollback, and editorial
 *   revision history.
 * - It focuses on article_body revisions, not general audit logging.
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
 * Actual record-level defaults for article_revision.
 */
$article_revision_record_default = [
	'revision_number' => 1,
	'revision_note' => null,
	'review_note' => null,
	'status_review' => 'P',
	'status_record_lifecycle' => 'ACT',
];

/**
 * article_revision sample record.
 */
$article_revision = [
	# Primary
	'_id' => 'R9cM4xK1pT7vN2qH',

	# Parent
	'article_id' => 'A7mK2pQ9xR4tV8zN',
	'article_body_id' => 'B6qN1xT8mR3vK9cP',
	'language_id' => 3049, // English in common_reference.language_main; common_reference IDs remain numeric

	# Revision Snapshot
	'revision_number' => 2,
	'article_body' => '<h2 class="mn-section-title">Before the massage begins</h2><p>Your therapist may ask about pressure, allergies, areas of discomfort, and privacy preferences.</p>',
	'reading_duration' => 435,
	'revision_note' => 'Expanded the consultation paragraph and clarified privacy wording.',
	'review_note' => 'Approved after safety wording update.',
	'status_review' => 'A',

	# Handling
	'status_record_lifecycle' => 'ACT',

	# Audit
	'created_at' => $created_at,
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'submitted_at' => '2026-07-06T09:30:00Z',
	'submitted_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'reviewed_at' => '2026-07-06T10:00:00Z',
	'reviewed_by_user_id' => 'U6nH1sW8dK3yP9fR',
	'approved_at' => '2026-07-06T10:30:00Z',
	'approved_by_user_id' => 'U6nH1sW8dK3yP9fR',
];

$article_revision_field_order = [
	'_id',
	'article_id',
	'article_body_id',
	'language_id',
	'revision_number',
	'article_body',
	'reading_duration',
	'revision_note',
	'review_note',
	'status_review',
	'status_record_lifecycle',
	'created_at',
	'created_by_user_id',
	'submitted_at',
	'submitted_by_user_id',
	'reviewed_at',
	'reviewed_by_user_id',
	'approved_at',
	'approved_by_user_id',
];

$article_revision_field_property = [
	'_id' => ['field_label' => 'Article Revision ID', 'field_description' => 'Canonical application-generated 16-character Base62 identifier for the article_revision record.', 'type_data' => 'S', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_system' => true, 'is_indexed' => true],
	'article_id' => ['field_label' => 'Article ID', 'field_description' => 'Reference to the owning article_main record.', 'type_data' => 'S', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
	'article_body_id' => ['field_label' => 'Article Body ID', 'field_description' => 'Reference to the article body being revised.', 'type_data' => 'S', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
	'language_id' => ['field_label' => 'Language ID', 'field_description' => 'Numeric reference to common_reference.language_main for the revised body snapshot.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
	'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Sequential revision number for the article body language version.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'is_mandatory' => true, 'min_number' => 1],
	'article_body' => ['field_label' => 'Article Body', 'field_description' => 'Snapshot of the controlled/theme-aware HTML article body at this revision.', 'type_data' => 'S', 'type_field' => 'HTE', 'format_text' => 'HTML', 'is_mandatory' => true],
	'reading_duration' => ['field_label' => 'Reading Duration', 'field_description' => 'Estimated reading duration in seconds for this revision snapshot.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'revision_note' => ['field_label' => 'Revision Note', 'field_description' => 'Internal note explaining what changed in this revision.', 'type_data' => 'S', 'type_field' => 'TXA', 'format_text' => 'TXT'],
	'review_note' => ['field_label' => 'Review Note', 'field_description' => 'Reviewer note explaining approval, rejection, or requested changes.', 'type_data' => 'S', 'type_field' => 'TXA', 'format_text' => 'TXT'],
	'status_review' => [
		'field_label' => 'Review Status',
		'field_description' => 'Review state for this revision.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			['option_code' => 'P', 'option_label' => 'Pending', 'option_description' => 'Waiting for review.', 'sort_order' => 10],
			['option_code' => 'A', 'option_label' => 'Approved', 'option_description' => 'Reviewed and approved.', 'sort_order' => 20],
			['option_code' => 'N', 'option_label' => 'Needs Changes', 'option_description' => 'Reviewed but requires revision.', 'sort_order' => 30],
			['option_code' => 'R', 'option_label' => 'Rejected', 'option_description' => 'Reviewed and rejected.', 'sort_order' => 40],
		],
	],
	'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state for this revision record.', 'type_field' => 'DDL', 'type_sql' => 'ENUM'],
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when this revision record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'User ID that created this revision.', 'type_data' => 'S', 'is_relational' => true],
	'submitted_at' => ['field_label' => 'Submitted At', 'field_description' => 'UTC timestamp when this revision was submitted for review.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'submitted_by_user_id' => ['field_label' => 'Submitted By User ID', 'field_description' => 'User ID that submitted this revision for review.', 'type_data' => 'S', 'is_relational' => true],
	'reviewed_at' => ['field_label' => 'Reviewed At', 'field_description' => 'UTC timestamp when this revision was reviewed.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'reviewed_by_user_id' => ['field_label' => 'Reviewed By User ID', 'field_description' => 'User ID that reviewed this revision.', 'type_data' => 'S', 'is_relational' => true],
	'approved_at' => ['field_label' => 'Approved At', 'field_description' => 'UTC timestamp when this revision was approved.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'approved_by_user_id' => ['field_label' => 'Approved By User ID', 'field_description' => 'User ID that approved this revision.', 'type_data' => 'S', 'is_relational' => true],
];
