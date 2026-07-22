<?php
/**
 * Title: Massage Nexus Quote Main Structure Guide
 * Version: 0.60
 * Collection: quote_main
 * Description: Stores one curated quotation, attribution, category, and lifecycle record.
 * Purpose: Documents the quote_main record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * This file is a PHP-readable visual structure guide.
 * It is not a seed file, not a runtime migration script, and not a generated
 * production schema. It exists so the database structure can be reviewed in a
 * familiar PHP array format before implementation.
 *
 * quote_main stores the approved quotes shown in the homepage Quote of the Day
 * section (docs/06-user-interface/home-page-ui.txt, section 20). The homepage
 * currently renders one temporary hard-coded quote from the sample-content
 * provider (apps/web/app/Support/Demo/SampleContent.php); this guide defines
 * the collection that replaces that temporary source.
 *
 * Layer rule:
 * - *_default contains omission defaults for actual stored record fields only.
 * - *_field_property describes schema/field metadata only.
 * - Do not mix field-definition metadata into record defaults.
 *
 * Scope decisions:
 * - A quote is short approved text with attribution; it is not article content
 *   and does not use article_main or article_body.
 * - Quotes are curated directly by administrators and editors; there is no community
 *   submission or approval review workflow.
 * - language_id is numeric per shared standard §9.3.1 (common_reference.language_main
 *   retains its numeric identifier contract). This field records the language in which
 *   the quote was originally expressed and drives locale-matching and fallback display.
 * - type_quote_category is single-select; each quote belongs to exactly one category.
 * - published_at is an ISO 8601 UTC timestamp supporting future-dated publication.
 *   A quote is eligible for rotation when published_at <= current UTC time,
 *   visibility_scope is Public (PUB), and status_record_lifecycle is Active (ACT).
 * - Rotation fairness (which quote shows on which day) is application logic,
 *   not stored state; no display counters are stored.
 * - record_note embedded repeaters are not used; the quote system is not an editorial
 *   workflow-heavy system requiring embedded audit notes on individual records.
 * - archived_at / archived_by_user_id are not stored; status_record_lifecycle is
 *   the authoritative lifecycle state and does not require a separate archive timestamp.
 * - References to common_reference records retain that dataset's numeric identifier type.
 *
 * Identifier note:
 * The sample follows the accepted application-generated opaque 16-character
 * Base62 _id direction in docs/04-architecture/database-structure.txt section 4.
 */

# Variable
$created_at = '2026-07-19T00:00:00Z';
$updated_at = '2026-07-22T04:52:00Z';
/**
 * Multilingual short-text sample.
 * Used by quote_text. English is shown as sample data only; the original
 * language of the quote is controlled by language_id.
 */
$multilingual_text_sample = [
	'eng' => [
		'text' => 'Sample text', // required when this language value exists
		'method_translation' => 'HUM', // optional; omit when default Human Translation applies
	],
];

/**
 * Actual record-level defaults for quote_main.
 * These are defaults for stored quote records, not field-definition metadata.
 * Sparse-default storage may omit these values in actual database records.
 */
$quote_main_default = [
	'type_quote_category' => null, // single taxonomy code from type_quote_category
	'attribution_name' => null,    // null when the quote is anonymous or proverb-like
	'source_title' => null,
	'source_url' => null,
	'visibility_scope' => 'PUB',   // PUB = Public
	'level_nsfw' => 'N',           // N = None
	'status_record_lifecycle' => 'ACT', // ACT = Active
	'published_at' => null,        // null means publish immediately upon creation
];

/**
 * quote_main sample record.
 * This sample intentionally includes populated values so the intended shape can
 * be reviewed. Actual sparse records may omit default values.
 */
$quote_main = [
	# Primary
	'_id' => 'Q8mR3xN6pK1vT9cH', // canonical application-generated 16-character Base62 identifier

	# Core
	'quote_text' => [ // Multilingual quote text displayed in the homepage Quote of the Day section. Combined with attribution_name, this is the practical duplicate-prevention key: editors must search existing quote text before adding a record, and imports should reject an exact same-language text match with the same attribution.
		'eng' => [
			'text' => 'Your body hears everything your mind says.',
			'method_translation' => 'HUM',
		],
		'fil' => [
			'text' => 'Naririnig ng iyong katawan ang lahat ng sinasabi ng iyong isip.',
			'method_translation' => 'HUM',
		],
	], // required multilingual quote text; the displayed language follows the visitor's interface language with fallback rules

	# Parent / Classification
	'language_id' => 3049, // English in common_reference.language_main; the language in which the quote was originally expressed. common_reference IDs remain numeric per shared standard §9.3.1.
	'type_quote_category' => 'WEL', // single taxonomy code from type_quote_category (see data/taxonomy/massage_nexus/content_classification.json)

	# Attribution / Source
	'attribution_name' => 'Naomi Judd', // public attribution exactly as displayed; null for anonymous or traditional sayings
	'source_title' => null, // optional title of the book, speech, interview, or work the quote comes from
	'source_url' => null, // optional reference URL used for editorial verification; not necessarily displayed publicly

	# Handling
	'visibility_scope' => 'PUB', // PUB = Public; suppression can remove a quote from rotation without deleting it
	'level_nsfw' => 'N', // N = None; quotes shown on the homepage must remain None
	'status_record_lifecycle' => 'ACT', // ACT = Active; retired or soft-deleted quotes leave rotation without deleting history

	# Audit
	'published_at' => $created_at, // UTC timestamp when the quote becomes eligible for rotation; supports future-dated scheduling
	'created_at' => $created_at,
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'updated_at' => $updated_at,
	'updated_by_user_id' => 'U2pR7vX4kT9mC5qL',
];

/**
 * Current quote_main logical field order.
 */
$quote_main_field_order = [
	'_id',
	'quote_text',
	'language_id',
	'type_quote_category',
	'attribution_name',
	'source_title',
	'source_url',
	'visibility_scope',
	'level_nsfw',
	'status_record_lifecycle',
	'published_at',
	'created_at',
	'created_by_user_id',
	'updated_at',
	'updated_by_user_id',
];

/**
 * quote_main has no embedded repeater structures.
 * The record_note pattern used in article_main and the earlier quote_main draft
 * was removed; the quote system does not require embedded editorial audit notes.
 */
$quote_main_embedded_structure = [];

/**
 * Field-property guide for quote_main.
 * These are field-definition properties, not stored record defaults.
 */
$quote_main_field_property = [
	# Primary
	'_id' => [
		'field_label' => 'Quote ID',
		'field_description' => 'Canonical application-generated 16-character Base62 identifier for the quote_main record. Referenced by other structures as quote_id.',
		'type_data' => 'S',
		'min_character' => 16,
		'max_character' => 16,
		'is_mandatory' => true,
		'is_system' => true,
		'is_indexed' => true,
	],

	# Core
	'quote_text' => [
		'field_label' => 'Quote Text',
		'field_description' => 'Multilingual quote text displayed in the homepage Quote of the Day section. Combined with attribution_name, this is the practical duplicate-prevention key: editors must search existing quote text before adding a record, and imports should reject an exact same-language text match with the same attribution.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'is_translatable' => true,
		'is_mandatory' => true,
		'max_character' => 500, // per language text value; quotes are short editorial text, not article bodies
	],

	# Parent / Classification
	'language_id' => [
		'field_label' => 'Original Language ID',
		'field_description' => 'Numeric reference to common_reference.language_main for the language in which the quote was originally expressed. The original does not have to be English. Drives locale-matching and fallback display per the Translation System.',
		'type_data' => 'I',
		'type_sql' => 'INT',
		'is_relational' => true,
		'is_indexed' => true,
	],
	'type_quote_category' => [
		'field_label' => 'Quote Category',
		'field_description' => 'Single editorial category for rotation and filtering. Option codes are owned by the type_quote_category taxonomy record in data/taxonomy/massage_nexus/content_classification.json; each quote belongs to exactly one category.',
		'type_data' => 'S',
		'type_field' => 'DDL', // single-select dropdown
		'type_sql' => 'VARCHAR',
		'is_indexed' => true,
	],

	# Attribution / Source
	'attribution_name' => [
		'field_label' => 'Attribution Name',
		'field_description' => 'Public attribution shown with the quote, exactly as displayed. Null represents an anonymous, traditional, or unattributed quote. This is display text, not a reference to a person record; a person or author relationship may be added later only if the platform starts profiling quoted people.',
		'max_character' => 150,
	],
	'source_title' => [
		'field_label' => 'Source Title',
		'field_description' => 'Optional title of the creative or published work the quote comes from, such as a book, speech, interview, or article.',
		'max_character' => 200,
	],
	'source_url' => [
		'field_label' => 'Source URL',
		'field_description' => 'Optional reference URL supporting editorial verification of the quote and attribution. Not automatically displayed publicly.',
		'constraint_text_input' => ['URL'],
		'max_character' => 500,
	],

	# Handling
	'visibility_scope' => ['field_label' => 'Visibility Scope', 'field_description' => 'Audience visibility rule for the quote record. Suppression can remove a quote from public rotation without deleting it or its history.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'level_nsfw' => ['field_label' => 'NSFW Level', 'field_description' => 'Content-sensitivity level. Homepage quotes must remain None; the field exists for consistency with shared record handling.', 'type_field' => 'DDL', 'type_sql' => 'ENUM'],
	'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state such as active, inactive, or soft-deleted. Retiring removes the quote from rotation while preserving history. Archiving is expressed through this field, not a separate archived_at timestamp.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],

	# Audit
	'published_at' => ['field_label' => 'Published At', 'field_description' => 'UTC timestamp when the quote becomes eligible for rotation. Supports future-dated scheduling; a quote with a future published_at date will not appear until that timestamp is reached.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_indexed' => true],
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when this quote record was created.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'User ID that created this quote record.', 'type_data' => 'S', 'is_relational' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp when this quote record was last updated.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'updated_by_user_id' => ['field_label' => 'Updated By User ID', 'field_description' => 'User ID that last updated this quote record.', 'type_data' => 'S', 'is_relational' => true],
];

/**
/**
 * quote_main has no embedded subfield property map.
 * Present as an empty array to satisfy structure guide validation requirements.
 */
$quote_main_subfield_property = [];

/**
 * Indexing suggestions.
 * Actual indexes belong to the implementing migration, not this guide.
 * - Rotation query index: status_record_lifecycle + language_id.
 * - Category filtering index: type_quote_category.
 * - Duplicate screening relies on editorial search plus an import-time check on
 *   same-language quote text with the same attribution_name; exact-match unique
 *   indexing of long multilingual text is intentionally not suggested.
 */

$quote_main_index_list = [
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

$quote_main_boundary = [
    'owns' => [
        'the quote_main record fields and embedded structures documented in this file',
    ],
    'reference_field_list' => [
        'language_id',
        'created_by_user_id',
        'updated_by_user_id',
    ],
    'does_not_own' => [
        'records stored in referenced collections',
        'runtime authorization, migration, seeding, or deployment behavior',
    ],
];

return [
    'multilingual_text_sample' => $multilingual_text_sample,
    'quote_main_default' => $quote_main_default,
    'quote_main' => $quote_main,
    'quote_main_field_order' => $quote_main_field_order,
    'quote_main_embedded_structure' => $quote_main_embedded_structure,
    'quote_main_field_property' => $quote_main_field_property,
    'quote_main_subfield_property' => $quote_main_subfield_property,
    'quote_main_index_list' => $quote_main_index_list,
    'quote_main_boundary' => $quote_main_boundary,
];
