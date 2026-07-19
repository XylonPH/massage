<?php
/**
 * Title: Massage Nexus Quote Main Structure Guide
 * Author: Xylon Reyes
 *
 * Collection: quote_main
 * Version: 0.10
 * Status: Proposed structure for review; not an accepted decision.
 *
 * This file is a PHP-readable visual structure guide.
 * It is not a seed file, not a runtime migration script, and not a generated
 * production schema. It exists so the database structure can be reviewed in a
 * familiar PHP array format before implementation.
 *
 * Purpose:
 * quote_main stores the approved quotes shown in the homepage Quote of the Day
 * section (docs/06-user-interface/home-page-ui.txt, section 20). The homepage
 * currently renders one temporary hard-coded quote from the sample-content
 * provider (apps/web/app/Support/Demo/SampleContent.php); this guide defines
 * the collection that replaces that temporary source. Per the homepage
 * documentation, quotes come from a database so they can support categories,
 * languages, authors, sources, active dates, seasonal use, and moderation.
 *
 * Layer rule:
 * - *_record_default contains defaults for actual stored record fields only.
 * - *_field_property describes schema/field metadata only.
 * - Do not mix field-definition metadata into record defaults.
 *
 * Scope decisions:
 * - A quote is short approved text with attribution; it is not article content
 *   and does not use content_main or content_body.
 * - Publication and scheduling behavior is represented by status_review,
 *   is_display_enabled, and the optional display_start_date/display_end_date
 *   window. A quote is eligible for homepage rotation only when review status
 *   is Approved, display is enabled, lifecycle is Active, and the current date
 *   falls inside the display window when one is set.
 * - Recurring seasonal scheduling (for example every December) is deliberately
 *   deferred; a seasonal or campaign relationship may be added later when the
 *   campaign system exists. Do not add speculative recurrence fields now.
 * - Relationships to author, contributor, content, media, theme, campaign, or
 *   occasion records are deliberately omitted because the current homepage
 *   feature only displays quote text and a plain attribution name.
 * - Rotation fairness (which quote shows on which day) is application logic,
 *   not stored state; no display counters are stored.
 *
 * Identifier note:
 * The sample below follows the existing Massage Nexus structure guides
 * (content_main, media_image), which show a simple numeric _id. The accepted
 * identifier direction in docs/04-architecture/database-structure.txt section 4
 * is an application-generated opaque 16-character alphanumeric _id for
 * MongoDB collections. Resolve the final identifier form at implementation
 * time consistently across collections; this guide does not decide it.
 */

# Variable
$created_at = '2026-07-19T00:00:00Z';
$updated_at = '2026-07-19T00:00:00Z';

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
 * Multilingual short-text sample.
 * Used by quote_text. English is shown as sample data only; the original
 * language of the quote is controlled by language_original_id.
 */
$multilingual_text_sample = [
	'eng' => [
		'text' => 'Sample text', // required when this language value exists
		'method_translation' => 'HUM', // optional; omit when default Human Translation applies
		'status_review' => 'A', // optional; A = Approved
	],
	'fil' => [
		'text' => 'Halimbawang teksto',
		'method_translation' => 'HUM',
		'status_review' => 'A',
	],
];

/**
 * Actual record-level defaults for quote_main.
 * These are defaults for stored quote records, not field-definition metadata.
 * Sparse-default storage may omit these values in actual database records.
 */
$quote_main_record_default = [
	'type_quote_category' => [], // taxonomy codes from type_quote_category
	'attribution_name' => null, // null when the quote is anonymous or proverb-like
	'source_title' => null,
	'source_url' => null,
	'display_start_date' => null, // null means no window start restriction
	'display_end_date' => null, // null means no window end restriction
	'is_display_enabled' => true,
	'status_review' => 'P', // P = Pending
	'level_nsfw' => 'N', // N = None
	'status_record_lifecycle' => 'ACT', // ACT = Active
	'record_note' => [],
];

/**
 * quote_main sample record.
 * This sample intentionally includes populated values so the intended shape can
 * be reviewed. Actual sparse records may omit default values.
 */
$quote_main = [
	# Primary
	'_id' => 1, // physical MongoDB identity; referenced elsewhere as quote_id; see Identifier note above

	# Core
	'quote_text' => [
		'eng' => [
			'text' => 'Your body hears everything your mind says.',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
		'fil' => [
			'text' => 'Naririnig ng iyong katawan ang lahat ng sinasabi ng iyong isip.',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
	], // required multilingual quote text; the displayed language follows the visitor's interface language with fallback rules

	# Parent / Classification
	'language_original_id' => 1, // original language of the quoted text; default English may be omitted in sparse records
	'type_quote_category' => ['WEL', 'MOT'], // multi-select codes from type_quote_category (see data/taxonomy/massage_nexus/content_classification.json)

	# Attribution / Source
	'attribution_name' => 'Naomi Judd', // public attribution exactly as displayed; null for anonymous or traditional sayings
	'source_title' => null, // optional title of the book, speech, interview, or work the quote comes from
	'source_url' => null, // optional reference URL used for editorial verification; not necessarily displayed publicly

	# Scheduling / Display Eligibility
	'display_start_date' => null, // optional first calendar date the quote may be shown; local platform date, no time component
	'display_end_date' => null, // optional last calendar date the quote may be shown; supports seasonal or campaign-window use
	'is_display_enabled' => true, // manual homepage-rotation switch independent of review and lifecycle

	# Handling
	'status_review' => 'A', // P = Pending, A = Approved, N = Needs Changes, R = Rejected; only Approved quotes are publicly displayed
	'level_nsfw' => 'N', // N = None; quotes shown on the homepage must remain None
	'status_record_lifecycle' => 'ACT', // ACT = Active; archived or retired quotes leave rotation without deleting history
	'record_note' => [
		[
			'type_record_note' => 'ED', // ED = Editorial, RV = Review, AD = Admin, CR = Correction
			'note_body' => 'Attribution confirmed against two published interviews.',
			'created_at' => '2026-07-19T00:00:00Z',
			'created_by_user_id' => 502,
		],
	], // embedded internal notes for this record; not public text

	# Audit
	'created_at' => $created_at,
	'created_by_user_id' => 501,
	'updated_at' => $updated_at,
	'updated_by_user_id' => 502,
	'archived_at' => null,
	'archived_by_user_id' => null,
];

/**
 * Current quote_main logical field order.
 */
$quote_main_field_order = [
	'_id',
	'quote_text',
	'language_original_id',
	'type_quote_category',
	'attribution_name',
	'source_title',
	'source_url',
	'display_start_date',
	'display_end_date',
	'is_display_enabled',
	'status_review',
	'level_nsfw',
	'status_record_lifecycle',
	'record_note',
	'created_at',
	'created_by_user_id',
	'updated_at',
	'updated_by_user_id',
	'archived_at',
	'archived_by_user_id',
];

/**
 * Embedded structures owned by quote_main.
 * record_note reuses the shared embedded note structure used by content_main.
 */
$quote_main_embedded_structure = [
	'record_note' => [
		'type_record_note' => 'ED', // ED = Editorial, RV = Review, AD = Admin, CR = Correction
		'note_body' => 'Internal note text for editors, reviewers, or administrators.',
		'created_at' => '2026-07-19T00:00:00Z',
		'created_by_user_id' => 502,
	],
];

/**
 * Field-property guide for quote_main.
 * These are field-definition properties, not stored record defaults.
 */
$quote_main_field_property = [
	# Primary
	'_id' => [
		'field_label' => 'Quote ID',
		'field_description' => 'Physical MongoDB identity for the quote_main record. Referenced by other structures as quote_id.',
		'type_data' => 'I',
		'type_sql' => 'INT',
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
	'language_original_id' => [
		'field_label' => 'Original Language ID',
		'field_description' => 'Reference to the language in which the quote was originally expressed. The original does not have to be English.',
		'type_data' => 'I',
		'type_sql' => 'INT',
		'is_relational' => true,
	],
	'type_quote_category' => [
		'field_label' => 'Quote Category',
		'field_description' => 'Multi-select editorial categories for rotation, filtering, and seasonal selection. Option codes are owned by the type_quote_category taxonomy record in data/taxonomy/massage_nexus/content_classification.json; this guide intentionally does not duplicate the option list.',
		'type_data' => 'A',
		'type_field' => 'CBL', // check-box list of taxonomy options
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

	# Scheduling / Display Eligibility
	'display_start_date' => [
		'field_label' => 'Display Start Date',
		'field_description' => 'Optional first calendar date on which the quote may appear in homepage rotation. Stored as a date-only value interpreted in the platform display time zone; do not store it as midnight UTC.',
		'type_field' => 'DTP', // date picker
		'type_sql' => 'DATE',
	],
	'display_end_date' => [
		'field_label' => 'Display End Date',
		'field_description' => 'Optional last calendar date on which the quote may appear in homepage rotation. Supports seasonal or campaign-window use without a recurrence system.',
		'type_field' => 'DTP',
		'type_sql' => 'DATE',
	],
	'is_display_enabled' => [
		'field_label' => 'Display Enabled',
		'field_description' => 'Manual switch controlling homepage-rotation eligibility. A quote is displayed only when this is true, status_review is Approved, status_record_lifecycle is Active, and the current date is inside the display window when one is set.',
		'type_data' => 'B',
		'type_field' => 'TGL',
		'type_sql' => 'TINYINT',
		'is_indexed' => true,
	],

	# Handling
	'status_review' => [
		'field_label' => 'Review Status',
		'field_description' => 'Editorial approval state of the quote. Only Approved quotes are eligible for public display. Uses the same option codes as content_main.status_review.',
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
	'level_nsfw' => ['field_label' => 'NSFW Level', 'field_description' => 'Content-sensitivity level. Homepage quotes must remain None; the field exists for consistency with shared record handling.', 'type_field' => 'DDL', 'type_sql' => 'ENUM'],
	'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state such as active, inactive, archived, or deleted. Archiving or retiring removes the quote from rotation while preserving history.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'record_note' => ['field_label' => 'Record Note', 'field_description' => 'Embedded internal notes attached to this quote record, including attribution-verification notes.', 'type_data' => 'A', 'type_field' => 'JSE'],

	# Audit
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when this quote record was created.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'User ID that created this quote record.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_relational' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp when this quote record was last updated.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'updated_by_user_id' => ['field_label' => 'Updated By User ID', 'field_description' => 'User ID that last updated this quote record.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_relational' => true],
	'archived_at' => ['field_label' => 'Archived At', 'field_description' => 'UTC timestamp when this quote record was archived.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'archived_by_user_id' => ['field_label' => 'Archived By User ID', 'field_description' => 'User ID that archived this quote record.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_relational' => true],
];

/**
 * Indexing suggestions.
 * Actual indexes belong to the implementing migration, not this guide.
 * - Rotation query index: is_display_enabled + status_review + status_record_lifecycle,
 *   optionally with display_start_date/display_end_date.
 * - Duplicate screening relies on editorial search plus an import-time check on
 *   same-language quote text with the same attribution_name; exact-match unique
 *   indexing of long multilingual text is intentionally not suggested.
 */
