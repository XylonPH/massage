<?php
/**
 * Title: Massage Nexus Tag Main Structure Guide
 * Version: 1.10
 * Collection: tag_main
 * Description: Stores one reusable multilingual editorial tag.
 * Purpose: Documents the tag_main record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * - Tags are lightweight discovery labels shared by editorial domains.
 * - Article categories remain a separate controlled taxonomy.
 */

$created_at = '2026-07-20T00:00:00Z';
$updated_at = '2026-07-21T04:24:17Z';
$tag_main_default = [
	'usage_count' => 0,
	'status_record_lifecycle' => 'ACT',
];

$multilingual_text_sample = [
	'eng' => [
		'text' => 'first massage',
		'method_translation' => 'HUM',
		'status_review' => 'A',
	],
];

$tag_main = [
	'_id' => 'T3gH7kM2pR9vX4cN', // Canonical application-generated 16-character Base62 identifier for the tag_main record.
	'tag_title' => [ // Short multilingual public label for the editorial tag.
		'eng' => ['text' => 'first massage', 'method_translation' => 'HUM', 'status_review' => 'A'],
	],
	'tag_slug' => [ // Multilingual URL-safe slug used by public tag routes. Each language value must be unique among active tags.
		'eng' => ['text' => 'first-massage', 'method_translation' => 'HUM', 'status_review' => 'A'],
	],
	'language_original_id' => 3049, // Numeric common_reference.language_main identifier for the original tag label.
	'usage_count' => 18, // Cached number of active records currently using this tag.
	'status_record_lifecycle' => 'ACT', // Database lifecycle state for this tag record.
	'created_at' => $created_at, // UTC timestamp when the tag was created.
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA', // User ID that created the tag.
	'updated_at' => $updated_at, // UTC timestamp when the tag was last updated.
	'updated_by_user_id' => 'U2pR7vX4kT9mC5qL', // User ID that last updated the tag.
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

$tag_main_embedded_structure = [];

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

$tag_main_subfield_property = [];

$tag_main_index_list = [
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

$tag_main_boundary = [
    'owns' => [
        'the tag_main record fields and embedded structures documented in this file',
    ],
    'reference_field_list' => [
        'language_original_id',
        'created_by_user_id',
        'updated_by_user_id',
    ],
    'does_not_own' => [
        'records stored in referenced collections',
        'runtime authorization, migration, seeding, or deployment behavior',
    ],
];

return [
    'tag_main_default' => $tag_main_default,
    'multilingual_text_sample' => $multilingual_text_sample,
    'tag_main' => $tag_main,
    'tag_main_field_order' => $tag_main_field_order,
    'tag_main_embedded_structure' => $tag_main_embedded_structure,
    'tag_main_field_property' => $tag_main_field_property,
    'tag_main_subfield_property' => $tag_main_subfield_property,
    'tag_main_index_list' => $tag_main_index_list,
    'tag_main_boundary' => $tag_main_boundary,
];
