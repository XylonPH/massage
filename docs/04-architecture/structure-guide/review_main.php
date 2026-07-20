<?php
/**
 * Title: Massage Nexus Review Main Structure Guide
 * Author: Xylon Reyes
 *
 * Collection: review_main
 * Version: 1.00
 * This file is a PHP-readable visual structure guide. It is not a seed file,
 * runtime migration, or generated production schema.
 *
 * Current scope:
 * - One shared review collection serves establishment and practitioner targets.
 * - Product reviews may reuse this structure when that public feature is built.
 * - The written review and its moderation/publication state live here.
 * - The target score and bounded criterion entries live in rating_main.
 * - Public anonymity hides the byline only; author identity remains internal.
 */

$created_at = '2026-07-20T00:00:00Z';
$updated_at = '2026-07-20T00:00:00Z';

$field_property_default = [
	'type_data' => 'S',
	'type_field' => 'TXT',
	'format_text' => 'TXT',
	'is_translatable' => false,
	'is_mandatory' => false,
	'is_relational' => false,
	'is_indexed' => false,
];

$review_main_record_default = [
	'type_review' => 'USR',
	'author_user_id_list' => [],
	'is_anonymous' => false,
	'amount_paid' => null,
	'currency_id' => 111, // PHP, retained from common_reference.currency_main
	'type_review_disclosure' => 'SFP',
	'rating_id' => null,
	'reading_duration_visual' => null,
	'reading_duration_spoken' => null,
	'level_nsfw' => 'N',
	'status_publication' => 'D',
	'status_review' => 'NR',
	'visibility_scope' => 'PRV',
	'status_record_lifecycle' => 'DRA',
	'record_note' => [],
];

$review_main = [
	# Primary and public identity
	'_id' => 'R7mQ2vK9xP4nH8sD',
	'review_title' => 'Quiet, careful service with excellent pressure control',
	'review_slug' => 'quiet-careful-service-with-excellent-pressure-control',
	'short_description' => 'A detailed first-hand review of a calm home-service massage experience.',

	# Authored content
	'review_body' => "The session began with a clear conversation about pressure and focus areas.\n\nThe therapist checked in without interrupting the flow and adjusted carefully when asked.",
	'language_original_id' => 3049, // English in common_reference.language_main
	'type_review' => 'USR',

	# Target and authorship
	'target_collection' => 'practitioner_main',
	'target_record_id' => 'P4rK8mN2xV7qL5dA',
	'author_user_id_list' => ['U5rK8mP2xN7qL4vA'],
	'is_anonymous' => false,

	# Experience context
	'date_experience' => '2026-07-18',
	'service_received' => '90-minute deep tissue massage',
	'amount_paid' => 1400.00,
	'currency_id' => 111,
	'type_review_disclosure' => 'SFP',

	# Rating relationship and computed reading metadata
	'rating_id' => 'G8nV3xQ6mK2pR9tL',
	'reading_duration_visual' => 48,
	'reading_duration_spoken' => 72,

	# Safety, moderation, and publication
	'level_nsfw' => 'N',
	'status_publication' => 'P',
	'status_review' => 'APR',
	'visibility_scope' => 'PUB',
	'status_record_lifecycle' => 'ACT',
	'record_note' => [],

	# Workflow and audit
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'updated_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'submitted_at' => '2026-07-19T08:00:00Z',
	'submitted_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'published_at' => '2026-07-20T08:00:00Z',
	'published_by_user_id' => 'U2pR7vX4kT9mC5qL',
	'created_at' => $created_at,
	'updated_at' => $updated_at,
];

$review_main_field_order = [
	'_id', 'review_title', 'review_slug', 'short_description', 'review_body',
	'language_original_id', 'type_review', 'target_collection', 'target_record_id',
	'author_user_id_list', 'is_anonymous', 'date_experience', 'service_received',
	'amount_paid', 'currency_id', 'type_review_disclosure', 'rating_id',
	'reading_duration_visual', 'reading_duration_spoken', 'level_nsfw',
	'status_publication', 'status_review', 'visibility_scope',
	'status_record_lifecycle', 'record_note', 'created_by_user_id',
	'updated_by_user_id', 'submitted_at', 'submitted_by_user_id', 'published_at',
	'published_by_user_id', 'created_at', 'updated_at',
];

$review_main_field_property = [
	'_id' => ['field_label' => 'Review ID', 'field_description' => 'Opaque application-generated 16-character review identifier.', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true],
	'review_title' => ['field_label' => 'Review Title', 'field_description' => 'User-facing title summarizing the reviewed experience.', 'max_character' => 75, 'is_mandatory' => true],
	'review_slug' => ['field_label' => 'Review Slug', 'field_description' => 'Unique lowercase public route slug generated from the review title.', 'max_character' => 100, 'is_mandatory' => true, 'is_indexed' => true],
	'short_description' => ['field_label' => 'Short Description', 'field_description' => 'Plain-text preview derived from the review body when the author does not supply one.', 'max_character' => 255, 'is_mandatory' => true],
	'review_body' => ['field_label' => 'Review Body', 'field_description' => 'The Review Author\'s original substantive first-hand review text.', 'type_field' => 'TXA', 'format_text' => 'TXT', 'max_character' => 30000, 'is_mandatory' => true],
	'language_original_id' => ['field_label' => 'Original Language ID', 'field_description' => 'Stable numeric language identifier from common_reference.language_main for the submitted review text.', 'type_data' => 'I', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
	'type_review' => ['field_label' => 'Review Type', 'field_description' => 'User or editorial review classification from review_and_rating taxonomy.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'target_collection' => ['field_label' => 'Target Collection', 'field_description' => 'Authoritative collection containing the reviewed target, initially establishment_main or practitioner_main.', 'is_mandatory' => true, 'is_indexed' => true],
	'target_record_id' => ['field_label' => 'Target Record ID', 'field_description' => 'Stable target _id from target_collection.', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
	'author_user_id_list' => ['field_label' => 'Author User ID List', 'field_description' => 'Ordered registered-user IDs credited internally as Review Authors.', 'type_data' => 'A', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
	'is_anonymous' => ['field_label' => 'Is Anonymous', 'field_description' => 'Hides the public byline while retaining internal author identity.', 'type_data' => 'B', 'type_field' => 'CHK', 'type_sql' => 'BOOLEAN'],
	'date_experience' => ['field_label' => 'Experience Date', 'field_description' => 'Optional calendar date on which the reviewed experience occurred; it cannot be in the future.', 'type_field' => 'DTE', 'type_sql' => 'DATE'],
	'service_received' => ['field_label' => 'Service Received', 'field_description' => 'Optional concise user-entered description of the service experienced.', 'max_character' => 160],
	'amount_paid' => ['field_label' => 'Amount Paid', 'field_description' => 'Optional non-negative amount paid for the reviewed experience, interpreted with currency_id.', 'type_data' => 'D', 'type_field' => 'NUM', 'type_sql' => 'DECIMAL'],
	'currency_id' => ['field_label' => 'Currency ID', 'field_description' => 'Stable numeric identifier from common_reference.currency_main for amount_paid.', 'type_data' => 'I', 'is_relational' => true],
	'type_review_disclosure' => ['field_label' => 'Review Disclosure Type', 'field_description' => 'Circumstance disclosure from review_and_rating taxonomy.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'rating_id' => ['field_label' => 'Rating ID', 'field_description' => 'One-to-one rating_main event submitted with this review.', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
	'reading_duration_visual' => ['field_label' => 'Visual Reading Duration', 'field_description' => 'Computed silent-reading estimate in whole seconds.', 'type_data' => 'I'],
	'reading_duration_spoken' => ['field_label' => 'Spoken Reading Duration', 'field_description' => 'Computed spoken or screen-reader estimate in whole seconds.', 'type_data' => 'I'],
	'level_nsfw' => ['field_label' => 'NSFW Level', 'field_description' => 'Shared sensitive-content level controlling warnings, filters, and moderation routing.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'status_publication' => ['field_label' => 'Publication Status', 'field_description' => 'Draft, published, scheduled, or unpublished state independent from moderation approval.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'status_review' => ['field_label' => 'Review Status', 'field_description' => 'Shared moderation or editorial approval state.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'visibility_scope' => ['field_label' => 'Visibility Scope', 'field_description' => 'Shared record visibility boundary.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Shared lifecycle state independent from publication and moderation.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'record_note' => ['field_label' => 'Record Note', 'field_description' => 'Bounded internal notes using the standard record-note structure.', 'type_data' => 'A'],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'Registered user that created the review record.', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
	'updated_by_user_id' => ['field_label' => 'Updated By User ID', 'field_description' => 'Last registered user that changed the review record.', 'is_relational' => true],
	'submitted_at' => ['field_label' => 'Submitted At', 'field_description' => 'UTC timestamp when the review entered moderation.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_indexed' => true],
	'submitted_by_user_id' => ['field_label' => 'Submitted By User ID', 'field_description' => 'Registered user that submitted the review for moderation.', 'is_relational' => true],
	'published_at' => ['field_label' => 'Published At', 'field_description' => 'UTC timestamp when the approved review became public.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_indexed' => true],
	'published_by_user_id' => ['field_label' => 'Published By User ID', 'field_description' => 'Authorized user or system actor that published the approved review.', 'is_relational' => true],
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC creation timestamp.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true, 'is_indexed' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC last-update timestamp.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
];
