<?php
/**
 * Title: Massage Nexus Rating Main Structure Guide
 * Author: Xylon Reyes
 *
 * Collection: rating_main
 * Version: 1.00
 * This file is a PHP-readable visual structure guide. It is not a seed file,
 * runtime migration, or generated production schema.
 *
 * Current scope:
 * - One shared rating collection serves approved target families.
 * - This first implementation writes review-linked spa and therapist events.
 * - Criterion entries remain embedded because there are at most five.
 * - Official XCES summaries and user-period selection are not cached here yet.
 */

$created_at = '2026-07-20T00:00:00Z';
$updated_at = '2026-07-20T00:00:00Z';

$field_property_default = [
	'type_data' => 'S',
	'type_field' => 'TXT',
	'is_translatable' => false,
	'is_mandatory' => false,
	'is_relational' => false,
	'is_indexed' => false,
];

$rating_main_record_default = [
	'mode_rating' => 'SMP',
	'rating_criterion_list' => [],
	'type_rating_source' => 'RVW',
	'status_rating' => 'DRA',
];

$rating_main = [
	'_id' => 'G8nV3xQ6mK2pR9tL',
	'target_collection' => 'practitioner_main',
	'target_record_id' => 'P4rK8mN2xV7qL5dA',
	'review_id' => 'R7mQ2vK9xP4nH8sD',
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'mode_rating' => 'CRT',
	'rating_score' => 8.8,
	'rating_criterion_list' => [
		['type_rating_criterion' => 'TEC', 'status_rating_observation' => 'SCR', 'rating_score' => 9],
		['type_rating_criterion' => 'PRS', 'status_rating_observation' => 'SCR', 'rating_score' => 9],
		['type_rating_criterion' => 'PRO', 'status_rating_observation' => 'SCR', 'rating_score' => 9],
		['type_rating_criterion' => 'COM', 'status_rating_observation' => 'SCR', 'rating_score' => 9],
		['type_rating_criterion' => 'REL', 'status_rating_observation' => 'SCR', 'rating_score' => 8],
	],
	'type_rating_source' => 'RVW',
	'date_experience' => '2026-07-18',
	'status_rating' => 'ACT',
	'created_at' => $created_at,
	'updated_at' => $updated_at,
];

$rating_main_field_order = [
	'_id', 'target_collection', 'target_record_id', 'review_id',
	'created_by_user_id', 'mode_rating', 'rating_score',
	'rating_criterion_list', 'type_rating_source', 'date_experience',
	'status_rating', 'created_at', 'updated_at',
];

$rating_main_field_property = [
	'_id' => ['field_label' => 'Rating ID', 'field_description' => 'Opaque application-generated 16-character rating-event identifier.', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true],
	'target_collection' => ['field_label' => 'Target Collection', 'field_description' => 'Authoritative collection containing the rated target.', 'is_mandatory' => true, 'is_indexed' => true],
	'target_record_id' => ['field_label' => 'Target Record ID', 'field_description' => 'Stable target _id from target_collection.', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
	'review_id' => ['field_label' => 'Review ID', 'field_description' => 'Review associated with a review-linked rating event; unique when present.', 'is_relational' => true, 'is_indexed' => true],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'Registered user whose experience score this event records.', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
	'mode_rating' => ['field_label' => 'Rating Mode', 'field_description' => 'Simple overall or criteria rating mode from review_and_rating taxonomy.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'rating_score' => ['field_label' => 'Rating Score', 'field_description' => 'Overall 1-to-10 event score; for criteria mode this is the arithmetic mean of answered criteria.', 'type_data' => 'D', 'type_field' => 'NUM', 'type_sql' => 'DECIMAL', 'min_number' => 1, 'max_number' => 10, 'is_mandatory' => true],
	'rating_criterion_list' => ['field_label' => 'Rating Criterion List', 'field_description' => 'Bounded list of five unique target-appropriate criterion codes with an observation status and optional 1-to-10 score.', 'type_data' => 'A'],
	'type_rating_criterion' => ['field_label' => 'Rating Criterion Type', 'field_description' => 'Criterion code inside rating_criterion_list from review_and_rating taxonomy.', 'type_field' => 'DDL', 'type_sql' => 'ENUM'],
	'status_rating_observation' => ['field_label' => 'Rating Observation Status', 'field_description' => 'States whether the criterion was scored, not observed, or not applicable.', 'type_field' => 'DDL', 'type_sql' => 'ENUM'],
	'type_rating_source' => ['field_label' => 'Rating Source Type', 'field_description' => 'Workflow or evidence source from review_and_rating taxonomy.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'date_experience' => ['field_label' => 'Experience Date', 'field_description' => 'Optional experience date preserved independently from submission and edit timestamps.', 'type_field' => 'DTE', 'type_sql' => 'DATE', 'is_indexed' => true],
	'status_rating' => ['field_label' => 'Rating Status', 'field_description' => 'Eligibility and moderation state from review_and_rating taxonomy; only Active events may enter official scoring.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC creation timestamp.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true, 'is_indexed' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC last-update timestamp.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
];
