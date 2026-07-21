<?php
/**
 * Title: Massage Nexus Article Revision Structure Guide
 * Version: 1.31
 * Collection: article_revision
 * Description: Stores one immutable proposed or accepted revision of Article content or metadata.
 * Purpose: Documents the article_revision record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
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
 * - article_revision stores body snapshots for review, rollback, and editorial
 *   revision history.
 * - It focuses on article_body revisions, not general audit logging.
 * - review_note records the editorial decision about this exact immutable snapshot;
 *   it is distinct from article_main.record_note, which contains Article-wide notes.
 * - References to common_reference records retain that dataset's numeric identifier type.
 */

# Variable
$created_at = '2026-07-06T00:00:00Z';
$updated_at = '2026-07-21T10:36:14Z';
/**
 * Actual record-level defaults for article_revision.
 * Actual records omit these values, and writers unset them when a value returns to default.
 */
$article_revision_default = [
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
	'_id' => 'R9cM4xK1pT7vN2qH', // Canonical application-generated 16-character Base62 identifier for the article_revision record.

	# Parent
	'article_id' => 'A7mK2pQ9xR4tV8zN', // Reference to the owning article_main record.
	'article_body_id' => 'B6qN1xT8mR3vK9cP', // Reference to the article body being revised.
	'language_id' => 3049, // English in common_reference.language_main; common_reference IDs remain numeric

	# Revision Snapshot
	'revision_number' => 2, // Sequential revision number for the article body language version.
	'article_body' => '<h2 class="mn-section-title">Before the massage begins</h2><p>Your therapist may ask about pressure, allergies, areas of discomfort, and privacy preferences.</p>', // Snapshot of the controlled/theme-aware HTML article body at this revision.
	'word_count' => 1630, // Count of visible words in this revision snapshot after HTML and media placement tokens are removed.
	'reading_duration_visual' => 435, // Visual-reading estimate in seconds for this revision snapshot.
	'reading_duration_spoken' => 652, // Read-aloud, screen-reader, or text-to-speech estimate in seconds for this revision snapshot.
	'revision_note' => 'Expanded the consultation paragraph and clarified privacy wording.', // Internal note explaining what changed in this revision.
	'review_note' => 'Approved after safety wording update.', // Reviewer note explaining approval, rejection, or requested changes.
	'status_review' => 'A', // Review state for this revision.

	# Handling
	'status_record_lifecycle' => 'ACT', // Database lifecycle state for this revision record.

	# Audit
	'created_at' => $created_at, // UTC timestamp when this revision record was created.
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA', // User ID that created this revision.
	'submitted_at' => '2026-07-06T09:30:00Z', // UTC timestamp when this revision was submitted for review.
	'submitted_by_user_id' => 'U5rK8mP2xN7qL4vA', // User ID that submitted this revision for review.
	'reviewed_at' => '2026-07-06T10:00:00Z', // UTC timestamp when this revision was reviewed.
	'reviewed_by_user_id' => 'U6nH1sW8dK3yP9fR', // User ID that reviewed this revision.
	'approved_at' => '2026-07-06T10:30:00Z', // UTC timestamp when this revision was approved.
	'approved_by_user_id' => 'U6nH1sW8dK3yP9fR', // User ID that approved this revision.
];

$article_revision_field_order = [
	'_id',
	'article_id',
	'article_body_id',
	'language_id',
	'revision_number',
	'article_body',
	'word_count',
	'reading_duration_visual',
	'reading_duration_spoken',
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

$article_revision_embedded_structure = [];

$article_revision_field_property = [
	'_id' => ['field_label' => 'Article Revision ID', 'field_description' => 'Canonical application-generated 16-character Base62 identifier for the article_revision record.', 'type_data' => 'S', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_system' => true, 'is_indexed' => true],
	'article_id' => ['field_label' => 'Article ID', 'field_description' => 'Reference to the owning article_main record.', 'type_data' => 'S', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
	'article_body_id' => ['field_label' => 'Article Body ID', 'field_description' => 'Reference to the article body being revised.', 'type_data' => 'S', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
	'language_id' => ['field_label' => 'Language ID', 'field_description' => 'Numeric reference to common_reference.language_main for the revised body snapshot.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
	'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Sequential revision number for the article body language version.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'is_mandatory' => true, 'min_number' => 1],
	'article_body' => ['field_label' => 'Article Body', 'field_description' => 'Snapshot of the controlled/theme-aware HTML article body at this revision.', 'type_data' => 'S', 'type_field' => 'HTE', 'format_text' => 'HTML', 'is_mandatory' => true],
	'word_count' => ['field_label' => 'Word Count', 'field_description' => 'Count of visible words in this revision snapshot after HTML and media placement tokens are removed.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'reading_duration_visual' => ['field_label' => 'Visual Reading Duration', 'field_description' => 'Visual-reading estimate in seconds for this revision snapshot.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'reading_duration_spoken' => ['field_label' => 'Spoken Reading Duration', 'field_description' => 'Read-aloud, screen-reader, or text-to-speech estimate in seconds for this revision snapshot.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
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

$article_revision_subfield_property = [];

$article_revision_index_list = [
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

$article_revision_boundary = [
    'owns' => [
        'the article_revision record fields and embedded structures documented in this file',
    ],
    'reference_field_list' => [
        'article_id',
        'article_body_id',
        'language_id',
        'created_by_user_id',
        'submitted_by_user_id',
        'reviewed_by_user_id',
        'approved_by_user_id',
    ],
    'does_not_own' => [
        'records stored in referenced collections',
        'runtime authorization, migration, seeding, or deployment behavior',
    ],
];

return [
    'article_revision_default' => $article_revision_default,
    'article_revision' => $article_revision,
    'article_revision_field_order' => $article_revision_field_order,
    'article_revision_embedded_structure' => $article_revision_embedded_structure,
    'article_revision_field_property' => $article_revision_field_property,
    'article_revision_subfield_property' => $article_revision_subfield_property,
    'article_revision_index_list' => $article_revision_index_list,
    'article_revision_boundary' => $article_revision_boundary,
];
