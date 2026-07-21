<?php
/**
 * Title: Massage Nexus Tag Main Structure Guide
 * Author: Xylon Reyes
 *
 * Collection: tag_main
 * Version: 1.00
 * This PHP-readable guide documents the shared editorial tags referenced by
 * article_main.tag_id_list. It is documentation, not runtime code or seed data.
 *
 * Scope:
 * - Tags are lightweight discovery labels shared by editorial domains.
 * - Article categories remain a separate controlled taxonomy.
 * - Each tag has a stable 16-character Base62 ID; internal relationships use
 *   that ID even when a public route uses tag_slug.
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
	'status_record_lifecycle' => 'ACT',
	'visibility_scope' => 'INH',
	'level_nsfw' => 'N',
];

$tag_main_record_default = [
	'usage_count' => 0,
	'status_record_lifecycle' => 'ACT',
];

$tag_main = [
	'_id' => 'T3gH7kM2pR9vX4cN',
	'tag_title' => [
		'eng' => ['text' => 'first massage', 'method_translation' => 'HUM', 'status_review' => 'A'],
	],
	'tag_slug' => [
		'eng' => ['text' => 'first-massage', 'method_translation' => 'HUM', 'status_review' => 'A'],
	],
	'language_original_id' => 3049,
	'usage_count' => 18,
	'status_record_lifecycle' => 'ACT',
	'created_at' => $created_at,
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'updated_at' => $updated_at,
	'updated_by_user_id' => 'U2pR7vX4kT9mC5qL',
];

$tag_main_field_order = [
	'_id',
	'tag_title',
	'tag_slug',
	'language_original_id',
	'usage_count',
	'status_record_lifecycle',
	'created_at',
	'created_by_user_id',
	'updated_at',
	'updated_by_user_id',
];

$tag_main_field_property = [
	'_id' => ['field_label' => 'Tag ID', 'field_description' => 'Canonical application-generated 16-character Base62 identifier for the tag_main record.', 'type_data' => 'S', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_system' => true, 'is_indexed' => true],
	'tag_title' => ['field_label' => 'Tag Title', 'field_description' => 'Short multilingual public label for the editorial tag.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true, 'is_mandatory' => true, 'max_character' => 60],
	'tag_slug' => ['field_label' => 'Tag Slug', 'field_description' => 'Multilingual URL-safe slug used by public tag routes. Each language value must be unique among active tags.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true, 'is_mandatory' => true, 'is_indexed' => true, 'max_character' => 80],
	'language_original_id' => ['field_label' => 'Original Language ID', 'field_description' => 'Numeric common_reference.language_main identifier for the original tag label.', 'type_data' => 'I', 'type_sql' => 'INT', 'is_relational' => true],
	'usage_count' => ['field_label' => 'Usage Count', 'field_description' => 'Cached number of active records currently using this tag.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
	'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state for this tag record.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when the tag was created.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'User ID that created the tag.', 'type_data' => 'S', 'is_relational' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp when the tag was last updated.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'updated_by_user_id' => ['field_label' => 'Updated By User ID', 'field_description' => 'User ID that last updated the tag.', 'type_data' => 'S', 'is_relational' => true],
];

/**
 * Indexing suggestions:
 * - Unique per-language tag_slug.text values for active records.
 * - status_record_lifecycle + usage_count for public tag discovery.
 */
