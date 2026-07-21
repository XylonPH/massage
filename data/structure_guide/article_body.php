<?php
/**
 * Title: Massage Nexus Article Body Structure Guide
 * Version: 1.32
 * Collection: article_body
 * Description: Stores one language-specific rendered body belonging to an Article.
 * Purpose: Documents the article_body record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
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
 * - article_body stores the long authored body for an article_main record.
 * - Article body content is stored as controlled/theme-aware HTML.
 * - article_plain_text is a system-generated, markup-free search projection;
 *   it is never an independently authored body.
 * - References to common_reference records retain that dataset's numeric identifier type.
 */

# Variable
$created_at = '2026-07-06T00:00:00Z';
$updated_at = '2026-07-21T10:36:14Z';
/**
 * Actual record-level defaults for article_body.
 * Actual records omit these values, and writers unset them when a value returns to default.
 */
$article_body_default = [
	'article_body' => '',
	'article_plain_text' => '',
	'word_count' => 0,
	'reading_duration_visual' => null, // optional visual-reading estimate in seconds
	'reading_duration_spoken' => null, // optional read-aloud or screen-reader estimate in seconds
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
	'article_plain_text' => 'Before the massage begins Your therapist may ask about pressure, allergies, and areas of discomfort. A calm room helps the session feel less intimidating.', // generated from visible body text
	'word_count' => 1575, // count of visible words after HTML and media tokens are removed
	'reading_duration_visual' => 420, // ceil((1575 / 225) * 60)
	'reading_duration_spoken' => 630, // ceil((1575 / 150) * 60)

	# Translation / Review
	'translator_user_id_list' => ['U3wD8kN1sR6pX4mQ'], // users who translated this body, empty for original language body
	'source_article_body_id' => null, // source body when this body is a translation
	'method_translation' => null, // null for original language body; HUM, AI, MT, IMP, MIG, UNK when translated/imported
	'status_review' => 'A', // P = Pending, A = Approved, N = Needs Changes, R = Rejected

	# Handling
	'status_record_lifecycle' => 'ACT', // Database lifecycle state for this body record.

	# Audit
	'created_at' => $created_at, // UTC timestamp when this body record was created.
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA', // User ID that created this body record.
	'updated_at' => $updated_at, // UTC timestamp when this body record was last updated.
	'updated_by_user_id' => 'U2pR7vX4kT9mC5qL', // User ID that last updated this body record.
	'reviewed_at' => '2026-07-06T10:00:00Z', // UTC timestamp when this body record was reviewed.
	'reviewed_by_user_id' => 'U6nH1sW8dK3yP9fR', // User ID that reviewed this body record.
	'approved_at' => '2026-07-06T10:30:00Z', // UTC timestamp when this body record was approved.
	'approved_by_user_id' => 'U6nH1sW8dK3yP9fR', // User ID that approved this body record.
];

$article_body_field_order = [
	'_id',
	'article_id',
	'language_id',
	'article_body',
	'article_plain_text',
	'word_count',
	'reading_duration_visual',
	'reading_duration_spoken',
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

$article_body_embedded_structure = [];

$article_body_field_property = [
	# Primary
	'_id' => ['field_label' => 'Article Body ID', 'field_description' => 'Canonical application-generated 16-character Base62 identifier for the article_body record.', 'type_data' => 'S', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_system' => true, 'is_indexed' => true],

	# Parent
	'article_id' => ['field_label' => 'Article ID', 'field_description' => 'Reference to the owning article_main record.', 'type_data' => 'S', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
	'language_id' => ['field_label' => 'Language ID', 'field_description' => 'Numeric reference to common_reference.language_main for this body record.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],

	# Body
	'article_body' => ['field_label' => 'Article Body', 'field_description' => 'Long authored article body stored as controlled/theme-aware HTML. Search systems should not search raw class names or markup as content terms.', 'type_data' => 'S', 'type_field' => 'HTE', 'format_text' => 'HTML', 'is_mandatory' => true],
	'article_plain_text' => ['field_label' => 'Article Plain Text', 'field_description' => 'System-generated visible-text projection of article_body for full-text search and indexing. Editors do not enter this value directly.', 'type_data' => 'S', 'type_field' => 'TXA', 'format_text' => 'TXT', 'is_system' => true, 'is_indexed' => true],
	'word_count' => ['field_label' => 'Word Count', 'field_description' => 'Count of visible words after HTML tags, hidden metadata, and media placement tokens are removed.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'reading_duration_visual' => ['field_label' => 'Visual Reading Duration', 'field_description' => 'Visual-reading estimate in seconds for this language body, normally calculated at 225 words per minute or 200 for dense material.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'reading_duration_spoken' => ['field_label' => 'Spoken Reading Duration', 'field_description' => 'Read-aloud, screen-reader, or text-to-speech estimate in seconds for this language body, normally calculated at 150 words per minute.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],

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

$article_body_subfield_property = [];

$article_body_index_list = [
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

$article_body_boundary = [
    'owns' => [
        'the article_body record fields and embedded structures documented in this file',
    ],
    'reference_field_list' => [
        'article_id',
        'language_id',
        'translator_user_id_list',
        'source_article_body_id',
        'created_by_user_id',
        'updated_by_user_id',
        'reviewed_by_user_id',
        'approved_by_user_id',
    ],
    'does_not_own' => [
        'records stored in referenced collections',
        'runtime authorization, migration, seeding, or deployment behavior',
    ],
];

return [
    'article_body_default' => $article_body_default,
    'article_body' => $article_body,
    'article_body_field_order' => $article_body_field_order,
    'article_body_embedded_structure' => $article_body_embedded_structure,
    'article_body_field_property' => $article_body_field_property,
    'article_body_subfield_property' => $article_body_subfield_property,
    'article_body_index_list' => $article_body_index_list,
    'article_body_boundary' => $article_body_boundary,
];
