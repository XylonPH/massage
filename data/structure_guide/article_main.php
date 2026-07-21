<?php
/**
 * Title: Massage Nexus Article Main Structure Guide
 * Version: 1.80
 * Collection: article_main
 * Description: Stores one Article identity, publication, ownership, classification, and relationship record.
 * Purpose: Documents the article_main record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
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
 * - article_main stores article identity, metadata, relationships, and publication state.
 * - News, Reviews, Comics, Legal documents, and Announcements use separate collections.
 * - The long HTML article body is not stored here; use article_body.
 * - References to common_reference records retain that dataset's numeric identifier type.
 */

# Variable
$created_at = '2026-07-06T00:00:00Z';
$updated_at = '2026-07-21T04:24:17Z';
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
	]
];

/**
 * Actual record-level defaults for article_main.
 * These are defaults for stored Article records, not field-definition metadata.
 * Sparse-default storage may omit these values in actual database records.
 */
$article_main_default = [
	'target_audience' => 'G', // G = General
	'tag_id_list' => [],
	'author_user_id_list' => [],
	'author_credit_list' => [],
	'article_owner_user_id_list' => [],
	'is_anonymous' => false,
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
	'reading_duration_visual' => null, // optional fallback visual-reading estimate in seconds
	'reading_duration_spoken' => null, // optional fallback spoken/screen-reader estimate in seconds
	'source_reference_list' => [],
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
	'article_title' => [ // Public multilingual title of the article.
		'eng' => [
			'text' => 'What Actually Happens During Your First Massage',
			'method_translation' => 'HUM',
			'status_review' => 'A', // Editorial or approval review state of the Article record.
		],
		'fil' => [
			'text' => 'Ano ang Totoong Nangyayari sa Una Mong Masahe',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
	], // required public title; multilingual bounded text
	'article_slug' => [ // Multilingual URL-safe slug text for public article routes. Values should use kebab-case.
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
	'short_description' => [ // Optional multilingual preview description for listing cards, search snippets, and social previews. Each language text value should not exceed 255 characters.
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
	'author_user_id_list' => ['U5rK8mP2xN7qL4vA'], // derived list of linked users in author_credit_list for discovery, reputation, and compatibility
	'author_credit_list' => [ // Ordered public byline entries. Each embedded entry contains display_name and an optional user_id. A null user_id permits a custom credited person without a Massage Nexus account. Credit is not ownership or authorization.
		[
			'user_id' => 'U5rK8mP2xN7qL4vA', // optional link to a registered user or Neural Agent account
			'display_name' => 'Xylon Reyes', // public byline name retained as an intentional credit snapshot
		],
		[
			'user_id' => null, // null permits a credited person who does not have a Massage Nexus account
			'display_name' => 'Jordan Santos',
		],
	], // ordered public byline credits; order is the displayed author order
	'article_owner_user_id_list' => ['U5rK8mP2xN7qL4vA', 'U8dQ4nV2kM7pR1xC'], // users authorized to revise, submit, and unpublish this Article
	'is_anonymous' => false, // when true, public surfaces hide author_credit_list while internal ownership, review, audit, safety, and abuse handling remain available
	'editor_user_id_list' => ['U2pR7vX4kT9mC5qL'], // users who edited structure, clarity, grammar, or publication quality
	'reviewer_user_id_list' => ['U6nH1sW8dK3yP9fR'], // users who reviewed factual, safety, professional, or policy accuracy
	'photographer_user_id_list' => ['U4bM9xQ2jV7cL5tN'], // optional article-level photo credit when applicable

	# Media / Relationships
	'cover_media_image_id' => 'M7dP2kR9xC4vN8hQ', // main image used for article cards, listing previews, and header display
	'related_article_id_list' => ['B8nL3qR0yS5uW9aP', 'C9oM4rS1zT6vX0bQ'], // related article_main records
	'related_organization_id_list' => ['O3gK8pV1xR6mN4cT'], // organization-level relationship, e.g. Nuat Thai brand/company
	'related_establishment_id_list' => ['E6sQ2nW9kD4vH7pM'], // branch/location-level relationship
	'related_practitioner_id_list' => ['P8rC3mL7xT1qV5nK'], // practitioner-level relationship; public UI may call them therapist/masseur/masseuse
	'related_service_id_list' => ['S4vN9kR2pD7mX5cQ', 'S1hM6qT8wC3nL9yP'], // Related massage, spa, or wellness service IDs.
	'related_product_id_list' => ['D7xP2mK5vR9cN4qT'], // Related wellness product IDs.

	# Cached Statistic
	'view_count' => 1280, // Cached total number of recorded views for this Article.
	'comment_count' => 16, // Cached total number of comments for this Article.
	'save_count' => 84, // Cached total number of user saves or bookmarks for this Article.
	'share_count' => 21, // Cached total number of share actions for this Article.
	'reading_duration_visual' => 420, // optional fallback visual-reading estimate in seconds
	'reading_duration_spoken' => 630, // optional fallback read-aloud or screen-reader estimate in seconds
	'source_reference_list' => [ // Ordered, bounded list of public references used to research or substantiate the article. Empty means no external source was used.
		[
			'source_title' => 'Massage Therapy: What You Need To Know',
			'source_organization' => 'National Center for Complementary and Integrative Health',
			'source_url' => 'https://www.nccih.nih.gov/health/massage-therapy-what-you-need-to-know',
			'publication_identifier' => null,
		],
	], // ordered public source references used by the article

	# Handling
	'is_commentable' => true, // comments are allowed for this Article
	'is_shareable' => true, // sharing controls may display public share actions
	'status_publication' => 'P', // D = Draft, S = Scheduled, P = Published, U = Unpublished
	'status_review' => 'A', // P = Pending, A = Approved, N = Needs Changes, R = Rejected
	'visibility_scope' => 'PUB', // PUB = Public
	'level_nsfw' => 'N', // N = None
	'status_record_lifecycle' => 'ACT', // ACT = Active
	'record_note' => [ // Embedded internal notes attached to this Article record.
		[
			'type_record_note' => 'ED', // ED = Editorial Note
			'note_body' => 'Check if this guide needs an updated beginner checklist before launch.',
			'created_at' => '2026-07-06T08:15:00Z', // UTC timestamp when this Article record was created.
			'created_by_user_id' => 'U2pR7vX4kT9mC5qL', // User ID that created this article record.
		],
	], // embedded internal notes for this record; not public article body

	# Audit
	'created_at' => $created_at,
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'updated_at' => $updated_at, // UTC timestamp when this Article record was last updated.
	'updated_by_user_id' => 'U2pR7vX4kT9mC5qL', // User ID that last updated this article record.
	'scheduled_publish_at' => null, // UTC timestamp when scheduled content should be published.
	'published_at' => '2026-07-12T00:00:00Z', // UTC timestamp when content was published.
	'published_by_user_id' => 'U2pR7vX4kT9mC5qL', // User ID that published the article.
	'archived_at' => null, // UTC timestamp when content was archived.
	'archived_by_user_id' => null, // User ID that archived the article.
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
	'author_credit_list',
	'article_owner_user_id_list',
	'is_anonymous',
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
	'reading_duration_visual',
	'reading_duration_spoken',
	'source_reference_list',
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
	'source_reference_list' => [
		'source_title' => 'Human-readable title of the cited work or resource.',
		'source_organization' => 'Optional publisher, agency, institution, or organization name.',
		'source_url' => 'Optional canonical HTTPS URL for the source.',
		'publication_identifier' => 'Optional DOI, ISBN, report number, or comparable publication identifier.',
	],
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
		'field_description' => 'Derived ordered list of registered user IDs represented in author_credit_list. Supports author discovery, reputation attribution, Neural Agent author pages, and compatibility; it does not grant Article control.',
		'type_data' => 'A',
		'is_relational' => true,
	],
	'author_credit_list' => [
		'field_label' => 'Author Credit List',
		'field_description' => 'Ordered public byline entries. Each embedded entry contains display_name and an optional user_id. A null user_id permits a custom credited person without a Massage Nexus account. Credit is not ownership or authorization.',
		'type_data' => 'A',
		'type_field' => 'JSE',
		'is_mandatory' => true,
	],
	'article_owner_user_id_list' => [
		'field_label' => 'Article Owner User ID List',
		'field_description' => 'List of active registered users authorized to revise, submit, and unpublish the Article. The creator remains an owner. Public credit and anonymity do not change ownership.',
		'type_data' => 'A',
		'is_relational' => true,
		'is_mandatory' => true,
		'is_indexed' => true,
	],
	'is_anonymous' => [
		'field_label' => 'Anonymous Public Authorship',
		'field_description' => 'When true, public Article surfaces suppress author_credit_list and display an anonymous byline. Internal ownership, editorial review, audit, safety, and abuse handling continue to use article_owner_user_id_list and audit fields.',
		'type_data' => 'B',
		'type_field' => 'CHK',
		'type_sql' => 'BOOLEAN',
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
	'reading_duration_visual' => ['field_label' => 'Visual Reading Duration', 'field_description' => 'Optional fallback visual-reading estimate in seconds. The language-specific article_body value overrides this when present.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'reading_duration_spoken' => ['field_label' => 'Spoken Reading Duration', 'field_description' => 'Optional fallback read-aloud, screen-reader, or text-to-speech estimate in seconds. The language-specific article_body value overrides this when present.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'source_reference_list' => ['field_label' => 'Source Reference List', 'field_description' => 'Ordered, bounded list of public references used to research or substantiate the article. Empty means no external source was used.', 'type_data' => 'A', 'type_field' => 'JSE'],

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

$article_main_subfield_property = [
	'source_reference_list.source_title' => ['field_label' => 'Source Title', 'field_description' => 'Human-readable title of the cited work or resource.', 'type_data' => 'S', 'is_mandatory' => true],
	'source_reference_list.source_organization' => ['field_label' => 'Source Organization', 'field_description' => 'Optional publisher, agency, institution, or organization.', 'type_data' => 'S'],
	'source_reference_list.source_url' => ['field_label' => 'Source URL', 'field_description' => 'Optional canonical HTTPS URL.', 'type_data' => 'S'],
	'source_reference_list.publication_identifier' => ['field_label' => 'Publication Identifier', 'field_description' => 'Optional DOI, ISBN, report number, or comparable identifier.', 'type_data' => 'S'],
	'record_note.type_record_note' => ['field_label' => 'Record Note Type', 'field_description' => 'Controlled note-purpose code.', 'type_data' => 'S', 'is_mandatory' => true],
	'record_note.note_body' => ['field_label' => 'Note Body', 'field_description' => 'Internal note text.', 'type_data' => 'S', 'is_mandatory' => true],
	'record_note.created_at' => ['field_label' => 'Note Created At', 'field_description' => 'UTC note creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
	'record_note.created_by_user_id' => ['field_label' => 'Note Created By User ID', 'field_description' => 'User that created the note.', 'type_data' => 'S', 'is_relational' => true, 'is_mandatory' => true],
];

$article_main_index_list = [
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

$article_main_boundary = [
    'owns' => [
        'the article_main record fields and embedded structures documented in this file',
    ],
    'reference_field_list' => [
        'language_original_id',
        'tag_id_list',
        'author_user_id_list',
        'article_owner_user_id_list',
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
        'created_by_user_id',
        'updated_by_user_id',
        'published_by_user_id',
        'archived_by_user_id',
    ],
    'does_not_own' => [
        'records stored in referenced collections',
        'runtime authorization, migration, seeding, or deployment behavior',
    ],
];

return [
    'multilingual_text_sample' => $multilingual_text_sample,
    'article_main_default' => $article_main_default,
    'article_main' => $article_main,
    'article_main_field_order' => $article_main_field_order,
    'article_main_embedded_structure' => $article_main_embedded_structure,
    'article_main_field_property' => $article_main_field_property,
    'article_main_subfield_property' => $article_main_subfield_property,
    'article_main_index_list' => $article_main_index_list,
    'article_main_boundary' => $article_main_boundary,
];
