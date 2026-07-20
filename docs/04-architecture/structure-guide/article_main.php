<?php
/**
 * Title: Massage Nexus Article Main Structure Guide
 * Author: Xylon Reyes
 *
 * Collection: article_main
 * Version: 1.41
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
 * - article_main stores article identity, metadata, relationships, and publication state.
 * - News, Reviews, Comics, Legal documents, and Announcements use separate collections.
 * - The long HTML article body is not stored here; use article_body.
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
 * Multilingual short-text sample.
 * Used by article_title, short_description, caption_text, alt_text, and similar
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
 * Actual record-level defaults for article_main.
 * These are defaults for stored Article records, not field-definition metadata.
 * Sparse-default storage may omit these values in actual database records.
 */
$article_main_record_default = [
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
 * article_main sample record.
 * This sample intentionally includes populated values so the intended shape can
 * be reviewed. Actual sparse records may omit default values.
 */
$article_main = [
	# Primary
	'_id' => 'A7mK2pQ9xR4tV8zN', // canonical application-generated 16-character Base62 identifier

	# Core
	'article_title' => [
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
	'article_slug' => [
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
	'language_original_id' => 3049, // English in common_reference.language_main; common_reference IDs remain numeric
	'type_article_category' => 'FTM', // article-only category, e.g. First-Time Massage and Spa Etiquette
	'target_audience' => 'C', // C = Client
	'tag_id_list' => ['T3gH7kM2pR9vX4cN', 'T8qL1sF6wB3nJ5dP', 'T5xC9mK4rV2hN7zQ'], // tag records are stored in a separate tag collection

	# Credits
	'author_user_id_list' => ['U5rK8mP2xN7qL4vA', 'U9cF3hJ6sD1wB8nM'], // human users or Neural Agent user accounts credited as authors
	'editor_user_id_list' => ['U2pR7vX4kT9mC5qL'], // users who edited structure, clarity, grammar, or publication quality
	'reviewer_user_id_list' => ['U6nH1sW8dK3yP9fR'], // users who reviewed factual, safety, professional, or policy accuracy
	'photographer_user_id_list' => ['U4bM9xQ2jV7cL5tN'], // optional article-level photo credit when applicable

	# Media / Relationships
	'cover_media_image_id' => 'M7dP2kR9xC4vN8hQ', // main image used for article cards, listing previews, and header display
	'related_article_id_list' => ['B8nL3qR0yS5uW9aP', 'C9oM4rS1zT6vX0bQ'], // related article_main records
	'related_organization_id_list' => ['O3gK8pV1xR6mN4cT'], // organization-level relationship, e.g. Nuat Thai brand/company
	'related_establishment_id_list' => ['E6sQ2nW9kD4vH7pM'], // branch/location-level relationship
	'related_practitioner_id_list' => ['P8rC3mL7xT1qV5nK'], // practitioner-level relationship; public UI may call them therapist/masseur/masseuse
	'related_service_id_list' => ['S4vN9kR2pD7mX5cQ', 'S1hM6qT8wC3nL9yP'],
	'related_product_id_list' => ['D7xP2mK5vR9cN4qT'],

	# Cached Statistic
	'view_count' => 1280,
	'comment_count' => 16,
	'save_count' => 84,
	'share_count' => 21,
	'reading_duration' => 420, // optional fallback estimated reading duration in seconds

	# Handling
	'is_commentable' => true, // comments are allowed for this Article
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
			'created_by_user_id' => 'U2pR7vX4kT9mC5qL',
		],
	], // embedded internal notes for this record; not public article body

	# Audit
	'created_at' => $created_at,
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'updated_at' => $updated_at,
	'updated_by_user_id' => 'U2pR7vX4kT9mC5qL',
	'scheduled_publish_at' => null,
	'published_at' => '2026-07-12T00:00:00Z',
	'published_by_user_id' => 'U2pR7vX4kT9mC5qL',
	'archived_at' => null,
	'archived_by_user_id' => null,
];

/**
 * Current article_main logical field order.
 */
$article_main_field_order = [
	'_id',
	'article_title',
	'article_slug',
	'short_description',
	'language_original_id',
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
 * Embedded structures owned by article_main.
 */
$article_main_embedded_structure = [
	'record_note' => [
		'type_record_note' => 'ED', // ED = Editorial, RV = Review, AD = Admin, CR = Correction
		'note_body' => 'Internal note text for editors, reviewers, or administrators.',
		'created_at' => '2026-07-06T08:15:00Z',
		'created_by_user_id' => 'U2pR7vX4kT9mC5qL',
	],
];

/**
 * Field-property guide for article_main.
 * These are field-definition properties, not stored record defaults.
 */
$article_main_field_property = [
	# Primary
	'_id' => [
		'field_label' => 'Article ID',
		'field_description' => 'Canonical application-generated 16-character Base62 identifier for the article_main record. Referenced by other structures as article_id.',
		'type_data' => 'S',
		'min_character' => 16,
		'max_character' => 16,
		'is_mandatory' => true,
		'is_system' => true,
		'is_indexed' => true,
	],

	# Core
	'article_title' => [
		'field_label' => 'Article Title',
		'field_description' => 'Public multilingual title of the article.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'is_translatable' => true,
		'is_mandatory' => true,
	],
	'article_slug' => [
		'field_label' => 'Article Slug',
		'field_description' => 'Multilingual URL-safe slug text for public article routes. Values should use kebab-case.',
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
		'field_description' => 'Numeric reference to common_reference.language_main for the language in which the article was originally authored. The original does not have to be English.',
		'type_data' => 'I',
		'type_sql' => 'INT',
		'is_relational' => true,
		'is_indexed' => true,
	],
	'type_article_category' => [
		'field_label' => 'Article Category',
		'field_description' => 'Editorial category for the article. Category taxonomy is managed separately from tags.',
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
		'field_description' => 'Reference to the main image used for article cards, previews, and header display. Inline article images are referenced inside article_body HTML.',
		'type_data' => 'S',
		'is_relational' => true,
	],
	'related_article_id_list' => ['field_label' => 'Related Article ID List', 'field_description' => 'Related article_main IDs for article-to-article recommendations.', 'type_data' => 'A', 'is_relational' => true],
	'related_organization_id_list' => ['field_label' => 'Related Organization ID List', 'field_description' => 'Related organization IDs, such as spa brands or parent companies.', 'type_data' => 'A', 'is_relational' => true],
	'related_establishment_id_list' => ['field_label' => 'Related Establishment ID List', 'field_description' => 'Related establishment IDs, such as spa branches or physical service locations.', 'type_data' => 'A', 'is_relational' => true],
	'related_practitioner_id_list' => ['field_label' => 'Related Practitioner ID List', 'field_description' => 'Related practitioner IDs. Public UI may label practitioners as therapists, masseurs, or masseuses depending on context.', 'type_data' => 'A', 'is_relational' => true],
	'related_service_id_list' => ['field_label' => 'Related Service ID List', 'field_description' => 'Related massage, spa, or wellness service IDs.', 'type_data' => 'A', 'is_relational' => true],
	'related_product_id_list' => ['field_label' => 'Related Product ID List', 'field_description' => 'Related wellness product IDs.', 'type_data' => 'A', 'is_relational' => true],

	# Cached Statistic
	'view_count' => ['field_label' => 'View Count', 'field_description' => 'Cached total number of recorded views for this Article.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'comment_count' => ['field_label' => 'Comment Count', 'field_description' => 'Cached total number of comments for this Article.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'save_count' => ['field_label' => 'Save Count', 'field_description' => 'Cached total number of user saves or bookmarks for this Article.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'share_count' => ['field_label' => 'Share Count', 'field_description' => 'Cached total number of share actions for this Article.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'reading_duration' => ['field_label' => 'Reading Duration', 'field_description' => 'Optional fallback estimated reading duration in seconds. Article-body language-specific values override this when present.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],

	# Handling
	'is_commentable' => ['field_label' => 'Is Commentable', 'field_description' => 'Indicates whether users may comment on this content.', 'type_data' => 'B', 'type_field' => 'CHK', 'type_sql' => 'BOOLEAN'],
	'is_shareable' => ['field_label' => 'Is Shareable', 'field_description' => 'Indicates whether public share controls should be available for this content.', 'type_data' => 'B', 'type_field' => 'CHK', 'type_sql' => 'BOOLEAN'],
	'status_publication' => [
		'field_label' => 'Publication Status',
		'field_description' => 'Current publication state of the Article record.',
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
		'field_description' => 'Editorial or approval review state of the Article record.',
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
	'visibility_scope' => ['field_label' => 'Visibility Scope', 'field_description' => 'Audience visibility rule for the Article record.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'level_nsfw' => ['field_label' => 'NSFW Level', 'field_description' => 'Content-sensitivity level for moderation and display handling.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state such as active, archived, deleted, or retired.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'record_note' => ['field_label' => 'Record Note', 'field_description' => 'Embedded internal notes attached to this Article record.', 'type_data' => 'A', 'type_field' => 'JSE'],

	# Audit
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when this Article record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'User ID that created this article record.', 'type_data' => 'S', 'is_relational' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp when this Article record was last updated.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'updated_by_user_id' => ['field_label' => 'Updated By User ID', 'field_description' => 'User ID that last updated this article record.', 'type_data' => 'S', 'is_relational' => true],
	'scheduled_publish_at' => ['field_label' => 'Scheduled Publish At', 'field_description' => 'UTC timestamp when scheduled content should be published.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'published_at' => ['field_label' => 'Published At', 'field_description' => 'UTC timestamp when content was published.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'published_by_user_id' => ['field_label' => 'Published By User ID', 'field_description' => 'User ID that published the article.', 'type_data' => 'S', 'is_relational' => true],
	'archived_at' => ['field_label' => 'Archived At', 'field_description' => 'UTC timestamp when content was archived.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'archived_by_user_id' => ['field_label' => 'Archived By User ID', 'field_description' => 'User ID that archived the article.', 'type_data' => 'S', 'is_relational' => true],
];
