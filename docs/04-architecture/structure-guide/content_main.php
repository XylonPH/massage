<?php
/**
 * Title: Massage Nexus Content Main Structure Guide
 * Author: Xylon Reyes
 *
 * Collection: content_main
 * Version: 1.31
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
 * - content_main is currently treated as article-first content.
 * - type_content exists so future content types can be added without renaming
 *   the collection, but this version only defines Article as active scope.
 * - The long HTML article body is not stored here; use content_body.
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
 * Multilingual short-text sample.
 * Used by content_title, short_description, caption_text, alt_text, and similar
 * bounded user-facing text values. English is shown as sample data only; the
 * original language of authored content is controlled by language_original_id.
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
 * Actual record-level defaults for content_main.
 * These are defaults for stored content records, not field-definition metadata.
 * Sparse-default storage may omit these values in actual database records.
 */
$content_main_record_default = [
	'type_content' => 'A', // A = Article; current default while content is article-first
	'target_audience' => 'G', // G = General
	'tag_id_list' => [],
	'author_user_id_list' => [],
	'editor_user_id_list' => [],
	'reviewer_user_id_list' => [],
	'photographer_user_id_list' => [],
	'related_article_id_list' => [],
	'related_organization_id_list' => [],
	'related_establishment_id_list' => [],
	'related_practitioner_id_list' => [],
	'related_service_id_list' => [],
	'related_product_id_list' => [],
	'view_count' => 0,
	'comment_count' => 0,
	'save_count' => 0,
	'share_count' => 0,
	'reading_duration' => null, // optional fallback estimated reading duration in seconds
	'is_commentable' => true,
	'is_shareable' => true,
	'status_publication' => 'D', // D = Draft
	'status_review' => 'P', // P = Pending
	'visibility_scope' => 'PVT', // PVT = Private until published
	'level_nsfw' => 'N', // N = None
	'status_record_lifecycle' => 'ACT', // ACT = Active
	'record_note' => [],
];

/**
 * content_main sample record.
 * This sample intentionally includes populated values so the intended shape can
 * be reviewed. Actual sparse records may omit default values.
 */
$content_main = [
	# Primary
	'_id' => 1, // physical MongoDB identity; referenced elsewhere as content_id

	# Core
	'content_title' => [
		'eng' => [
			'text' => 'What Actually Happens During Your First Massage',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
		'fil' => [
			'text' => 'Ano ang Totoong Nangyayari sa Una Mong Masahe',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
	], // required public title; multilingual bounded text
	'content_slug' => [
		'eng' => [
			'text' => 'what-actually-happens-during-your-first-massage',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
		'fil' => [
			'text' => 'ano-ang-totoong-nangyayari-sa-una-mong-masahe',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
	], // multilingual public URL slug text; values must be kebab-case
	'short_description' => [
		'eng' => [
			'text' => 'A beginner-friendly guide to what usually happens before, during, and after a first massage appointment.',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
	], // optional; maximum 255 characters per language text value

	# Parent / Classification
	'language_original_id' => 1, // original authored language; default English may be omitted in sparse records
	'type_content' => 'A', // A = Article; future values require an approved version update
	'type_article_category' => 'FTM', // article-only category, e.g. First-Time Massage and Spa Etiquette
	'target_audience' => 'C', // C = Client
	'tag_id_list' => [301, 302, 315], // tag records are stored in a separate tag collection

	# Credits
	'author_user_id_list' => [501, 9001], // human users or Neural Agent user accounts credited as authors
	'editor_user_id_list' => [502], // users who edited structure, clarity, grammar, or publication quality
	'reviewer_user_id_list' => [503], // users who reviewed factual, safety, professional, or policy accuracy
	'photographer_user_id_list' => [504], // optional article-level photo credit when applicable

	# Media / Relationships
	'cover_media_image_id' => 7001, // main image used for article cards, listing previews, and header display
	'related_article_id_list' => [1002, 1005], // related content_main records
	'related_organization_id_list' => [201], // organization-level relationship, e.g. Nuat Thai brand/company
	'related_establishment_id_list' => [301], // branch/location-level relationship
	'related_practitioner_id_list' => [401], // practitioner-level relationship; public UI may call them therapist/masseur/masseuse
	'related_service_id_list' => [601, 602],
	'related_product_id_list' => [801],

	# Cached Statistic
	'view_count' => 1280,
	'comment_count' => 16,
	'save_count' => 84,
	'share_count' => 21,
	'reading_duration' => 420, // optional fallback estimated reading duration in seconds

	# Handling
	'is_commentable' => true, // comments are allowed for this content record
	'is_shareable' => true, // sharing controls may display public share actions
	'status_publication' => 'P', // D = Draft, S = Scheduled, P = Published, U = Unpublished
	'status_review' => 'A', // P = Pending, A = Approved, N = Needs Changes, R = Rejected
	'visibility_scope' => 'PUB', // PUB = Public
	'level_nsfw' => 'N', // N = None
	'status_record_lifecycle' => 'ACT', // ACT = Active
	'record_note' => [
		[
			'type_record_note' => 'ED', // ED = Editorial Note
			'note_body' => 'Check if this guide needs an updated beginner checklist before launch.',
			'created_at' => '2026-07-06T08:15:00Z',
			'created_by_user_id' => 502,
		],
	], // embedded internal notes for this record; not public article body

	# Audit
	'created_at' => $created_at,
	'created_by_user_id' => 501,
	'updated_at' => $updated_at,
	'updated_by_user_id' => 502,
	'scheduled_publish_at' => null,
	'published_at' => '2026-07-12T00:00:00Z',
	'published_by_user_id' => 502,
	'archived_at' => null,
	'archived_by_user_id' => null,
];

/**
 * Current content_main logical field order.
 */
$content_main_field_order = [
	'_id',
	'content_title',
	'content_slug',
	'short_description',
	'language_original_id',
	'type_content',
	'type_article_category',
	'target_audience',
	'tag_id_list',
	'author_user_id_list',
	'editor_user_id_list',
	'reviewer_user_id_list',
	'photographer_user_id_list',
	'cover_media_image_id',
	'related_article_id_list',
	'related_organization_id_list',
	'related_establishment_id_list',
	'related_practitioner_id_list',
	'related_service_id_list',
	'related_product_id_list',
	'view_count',
	'comment_count',
	'save_count',
	'share_count',
	'reading_duration',
	'is_commentable',
	'is_shareable',
	'status_publication',
	'status_review',
	'visibility_scope',
	'level_nsfw',
	'status_record_lifecycle',
	'record_note',
	'created_at',
	'created_by_user_id',
	'updated_at',
	'updated_by_user_id',
	'scheduled_publish_at',
	'published_at',
	'published_by_user_id',
	'archived_at',
	'archived_by_user_id',
];

/**
 * Embedded structures owned by content_main.
 */
$content_main_embedded_structure = [
	'record_note' => [
		'type_record_note' => 'ED', // ED = Editorial, RV = Review, AD = Admin, CR = Correction
		'note_body' => 'Internal note text for editors, reviewers, or administrators.',
		'created_at' => '2026-07-06T08:15:00Z',
		'created_by_user_id' => 502,
	],
];

/**
 * Field-property guide for content_main.
 * These are field-definition properties, not stored record defaults.
 */
$content_main_field_property = [
	# Primary
	'_id' => [
		'field_label' => 'Content ID',
		'field_description' => 'Physical MongoDB identity for the content_main record. Referenced by other structures as content_id.',
		'type_data' => 'I',
		'type_sql' => 'INT',
		'is_mandatory' => true,
		'is_system' => true,
		'is_indexed' => true,
	],

	# Core
	'content_title' => [
		'field_label' => 'Content Title',
		'field_description' => 'Public multilingual title of the content record. For the current article-first scope, this is the article title.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'is_translatable' => true,
		'is_mandatory' => true,
	],
	'content_slug' => [
		'field_label' => 'Content Slug',
		'field_description' => 'Multilingual URL-safe slug text for public content routes. Values should use kebab-case.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'is_translatable' => true,
		'is_mandatory' => true,
		'is_indexed' => true,
	],
	'short_description' => [
		'field_label' => 'Short Description',
		'field_description' => 'Optional multilingual preview description for listing cards, search snippets, and social previews. Each language text value should not exceed 255 characters.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'is_translatable' => true,
		'max_character' => 255,
	],

	# Parent / Classification
	'language_original_id' => [
		'field_label' => 'Original Language ID',
		'field_description' => 'Reference to the language in which the content was originally authored. The original does not have to be English.',
		'type_data' => 'I',
		'type_sql' => 'INT',
		'is_relational' => true,
		'is_indexed' => true,
	],
	'type_content' => [
		'field_label' => 'Content Type',
		'field_description' => 'Classifies the content record. Current active scope is Article only; future content types require a structure version update.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			[
				'option_code' => 'A',
				'option_label' => 'Article',
				'option_description' => 'Editorial or educational article content.',
				'sort_order' => 10,
			],
		],
		'is_mandatory' => true,
		'is_indexed' => true,
	],
	'type_article_category' => [
		'field_label' => 'Article Category',
		'field_description' => 'Article-only editorial category used while type_content is Article. Category taxonomy is managed separately from tags.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'is_indexed' => true,
	],
	'target_audience' => [
		'field_label' => 'Target Audience',
		'field_description' => 'Broad reader role for the content, such as General, Client, Practitioner, Owner, Caregiver, or Traveler.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			['option_code' => 'G', 'option_label' => 'General', 'option_description' => 'Broad public audience.', 'sort_order' => 10],
			['option_code' => 'C', 'option_label' => 'Client', 'option_description' => 'People considering or using spa and massage services.', 'sort_order' => 20],
			['option_code' => 'P', 'option_label' => 'Practitioner', 'option_description' => 'Massage practitioners, bodyworkers, or wellness workers.', 'sort_order' => 30],
			['option_code' => 'O', 'option_label' => 'Owner', 'option_description' => 'Spa owners, managers, or operators.', 'sort_order' => 40],
			['option_code' => 'V', 'option_label' => 'Caregiver', 'option_description' => 'Family or support caregivers.', 'sort_order' => 50],
			['option_code' => 'T', 'option_label' => 'Traveler', 'option_description' => 'Readers visiting unfamiliar spa or wellness cultures.', 'sort_order' => 60],
		],
		'is_indexed' => true,
	],
	'tag_id_list' => [
		'field_label' => 'Tag ID List',
		'field_description' => 'List of tag record identifiers attached to the content for filtering, discovery, and editorial organization.',
		'type_data' => 'A',
		'type_field' => 'TGL',
		'is_relational' => true,
		'is_indexed' => true,
	],

	# Credits
	'author_user_id_list' => [
		'field_label' => 'Author User ID List',
		'field_description' => 'Ordered list of user IDs credited as authors. Neural Agents are represented as ordinary user records when credited.',
		'type_data' => 'A',
		'is_relational' => true,
		'is_mandatory' => true,
	],
	'editor_user_id_list' => [
		'field_label' => 'Editor User ID List',
		'field_description' => 'List of user IDs credited with editing the content for clarity, structure, grammar, tone, or publication quality.',
		'type_data' => 'A',
		'is_relational' => true,
	],
	'reviewer_user_id_list' => [
		'field_label' => 'Reviewer User ID List',
		'field_description' => 'List of user IDs credited with factual, safety, professional, legal, or policy review.',
		'type_data' => 'A',
		'is_relational' => true,
	],
	'photographer_user_id_list' => [
		'field_label' => 'Photographer User ID List',
		'field_description' => 'Optional article-level list of user IDs credited as photographers when the article itself carries photography credit.',
		'type_data' => 'A',
		'is_relational' => true,
	],

	# Media / Relationships
	'cover_media_image_id' => [
		'field_label' => 'Cover Media Image ID',
		'field_description' => 'Reference to the main image used for content cards, previews, and header display. Inline article images are referenced inside content_body HTML.',
		'type_data' => 'I',
		'type_sql' => 'INT',
		'is_relational' => true,
	],
	'related_article_id_list' => ['field_label' => 'Related Article ID List', 'field_description' => 'Related content_main IDs for article-to-article recommendations.', 'type_data' => 'A', 'is_relational' => true],
	'related_organization_id_list' => ['field_label' => 'Related Organization ID List', 'field_description' => 'Related organization IDs, such as spa brands or parent companies.', 'type_data' => 'A', 'is_relational' => true],
	'related_establishment_id_list' => ['field_label' => 'Related Establishment ID List', 'field_description' => 'Related establishment IDs, such as spa branches or physical service locations.', 'type_data' => 'A', 'is_relational' => true],
	'related_practitioner_id_list' => ['field_label' => 'Related Practitioner ID List', 'field_description' => 'Related practitioner IDs. Public UI may label practitioners as therapists, masseurs, or masseuses depending on context.', 'type_data' => 'A', 'is_relational' => true],
	'related_service_id_list' => ['field_label' => 'Related Service ID List', 'field_description' => 'Related massage, spa, or wellness service IDs.', 'type_data' => 'A', 'is_relational' => true],
	'related_product_id_list' => ['field_label' => 'Related Product ID List', 'field_description' => 'Related wellness product IDs.', 'type_data' => 'A', 'is_relational' => true],

	# Cached Statistic
	'view_count' => ['field_label' => 'View Count', 'field_description' => 'Cached total number of recorded views for this content.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'comment_count' => ['field_label' => 'Comment Count', 'field_description' => 'Cached total number of comments for this content.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'save_count' => ['field_label' => 'Save Count', 'field_description' => 'Cached total number of user saves/bookmarks for this content.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'share_count' => ['field_label' => 'Share Count', 'field_description' => 'Cached total number of share actions for this content.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'reading_duration' => ['field_label' => 'Reading Duration', 'field_description' => 'Optional fallback estimated reading duration in seconds. Content-body language-specific values override this when present.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],

	# Handling
	'is_commentable' => ['field_label' => 'Is Commentable', 'field_description' => 'Indicates whether users may comment on this content.', 'type_data' => 'B', 'type_field' => 'CHK', 'type_sql' => 'BOOLEAN'],
	'is_shareable' => ['field_label' => 'Is Shareable', 'field_description' => 'Indicates whether public share controls should be available for this content.', 'type_data' => 'B', 'type_field' => 'CHK', 'type_sql' => 'BOOLEAN'],
	'status_publication' => [
		'field_label' => 'Publication Status',
		'field_description' => 'Current publication state of the content record.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'field_option' => [
			['option_code' => 'D', 'option_label' => 'Draft', 'option_description' => 'Not yet published.', 'sort_order' => 10],
			['option_code' => 'S', 'option_label' => 'Scheduled', 'option_description' => 'Queued for publication at a scheduled time.', 'sort_order' => 20],
			['option_code' => 'P', 'option_label' => 'Published', 'option_description' => 'Published and available according to visibility rules.', 'sort_order' => 30],
			['option_code' => 'U', 'option_label' => 'Unpublished', 'option_description' => 'Previously or deliberately removed from publication without deleting the record.', 'sort_order' => 40],
		],
		'is_indexed' => true,
	],
	'status_review' => [
		'field_label' => 'Review Status',
		'field_description' => 'Editorial or approval review state of the content record.',
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
	'visibility_scope' => ['field_label' => 'Visibility Scope', 'field_description' => 'Audience visibility rule for the content record.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'level_nsfw' => ['field_label' => 'NSFW Level', 'field_description' => 'Content-sensitivity level for moderation and display handling.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state such as active, archived, deleted, or retired.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'record_note' => ['field_label' => 'Record Note', 'field_description' => 'Embedded internal notes attached to this content record.', 'type_data' => 'A', 'type_field' => 'JSE'],

	# Audit
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when this content record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'User ID that created this content record.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_relational' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp when this content record was last updated.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'updated_by_user_id' => ['field_label' => 'Updated By User ID', 'field_description' => 'User ID that last updated this content record.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_relational' => true],
	'scheduled_publish_at' => ['field_label' => 'Scheduled Publish At', 'field_description' => 'UTC timestamp when scheduled content should be published.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'published_at' => ['field_label' => 'Published At', 'field_description' => 'UTC timestamp when content was published.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'published_by_user_id' => ['field_label' => 'Published By User ID', 'field_description' => 'User ID that published the content.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_relational' => true],
	'archived_at' => ['field_label' => 'Archived At', 'field_description' => 'UTC timestamp when content was archived.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'archived_by_user_id' => ['field_label' => 'Archived By User ID', 'field_description' => 'User ID that archived the content.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_relational' => true],
];
