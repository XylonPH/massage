<?php
/**
 * Title: Massage Nexus News Main Structure Guide
 * Version: 1.10
 * Collection: news_main
 * Description: Stores timely news reports, industry updates, regulatory announcements, and journalistic stories with multi-language support.
 * Purpose: Documents the news_main record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * This file is a PHP-readable visual structure guide.
 * Governed by docs/09-content/news-writing.txt standards.
 */

$created_at = '2026-07-22T08:50:00Z';
$updated_at = '2026-07-22T08:50:00Z';

$multilingual_text_sample = [
	'eng' => [
		'text' => 'Sample text',
		'method_translation' => 'HUM',
		'status_review' => 'A',
	],
];

$news_main_default = [
	'type_news_category' => [],
	'published_at' => null,
	'status_review' => 'P',
	'level_nsfw' => 'N',
	'status_record_lifecycle' => 'ACT',
	'record_note' => [],
];

$news_main = [
	'_id' => 'N1aB2cC3dD4eE5fF',

	'news_headline' => [
		'eng' => [
			'text' => 'DOH Issues Updated Sanitation Guidelines for Massage Establishments',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
	],
	'news_body' => [
		'eng' => [
			'text' => '<p>The Department of Health released revised sanitary inspection standards today...</p>',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
	],

	'type_news_category' => ['REG', 'IND'],
	'published_at' => '2026-07-22T08:50:00Z',

	'status_review' => 'A',
	'level_nsfw' => 'N',
	'status_record_lifecycle' => 'ACT',
	'record_note' => [],

	'created_at' => $created_at,
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'updated_at' => $updated_at,
	'updated_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'archived_at' => null,
	'archived_by_user_id' => null,
];

$news_main_field_order = [
	'_id',
	'news_headline',
	'news_body',
	'type_news_category',
	'published_at',
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

$news_main_embedded_structure = [
	'record_note' => [
		'type_record_note' => 'ED',
		'note_body' => 'Fact-checked against official DOH press release.',
		'created_at' => '2026-07-22T08:50:00Z',
		'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	],
];

$news_main_field_property = [
	'_id' => [
		'field_label' => 'News Story ID',
		'field_description' => 'Canonical application-generated 16-character Base62 identifier for news_main.',
		'type_data' => 'S',
		'min_character' => 16,
		'max_character' => 16,
		'is_mandatory' => true,
		'is_system' => true,
		'is_indexed' => true,
	],
	'news_headline' => [
		'field_label' => 'News Headline',
		'field_description' => 'Multilingual news headline text.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'is_translatable' => true,
		'is_mandatory' => true,
		'max_character' => 255,
	],
	'news_body' => [
		'field_label' => 'News Body',
		'field_description' => 'Multilingual news article body content.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'is_translatable' => true,
		'is_mandatory' => true,
	],
	'type_news_category' => [
		'field_label' => 'News Category',
		'field_description' => 'Multi-select news category codes from content_classification taxonomy.',
		'type_data' => 'A',
		'type_field' => 'CBL',
		'is_indexed' => true,
	],
	'published_at' => [
		'field_label' => 'Published At',
		'field_description' => 'UTC timestamp when news story was or will be published. Supports future-dated scheduling.',
		'type_field' => 'DTS',
		'type_sql' => 'DATETIME',
		'is_mandatory' => true,
		'is_indexed' => true,
	],
	'status_review' => [
		'field_label' => 'Review Status',
		'field_description' => 'Editorial review status code.',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'is_indexed' => true,
	],
	'level_nsfw' => ['field_label' => 'NSFW Level', 'field_description' => 'Content sensitivity level.', 'type_field' => 'DDL', 'type_sql' => 'ENUM'],
	'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Lifecycle state (ACT, INA, ARC, DEL).', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'record_note' => ['field_label' => 'Record Note', 'field_description' => 'Internal notes attached to record.', 'type_data' => 'A', 'type_field' => 'JSE'],
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when record was created.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'User ID that created record.', 'type_data' => 'S', 'is_relational' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp when record was last updated.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'updated_by_user_id' => ['field_label' => 'Updated By User ID', 'field_description' => 'User ID that updated record.', 'type_data' => 'S', 'is_relational' => true],
	'archived_at' => ['field_label' => 'Archived At', 'field_description' => 'UTC timestamp when record was archived.', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'archived_by_user_id' => ['field_label' => 'Archived By User ID', 'field_description' => 'User ID that archived record.', 'type_data' => 'S', 'is_relational' => true],
];

$news_main_subfield_property = [
	'record_note.type_record_note' => ['field_label' => 'Record Note Type', 'field_description' => 'Controlled note-purpose code.', 'type_data' => 'S', 'is_mandatory' => true],
	'record_note.note_body' => ['field_label' => 'Note Body', 'field_description' => 'Internal note text.', 'type_data' => 'S', 'is_mandatory' => true],
	'record_note.created_at' => ['field_label' => 'Note Created At', 'field_description' => 'UTC note creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
	'record_note.created_by_user_id' => ['field_label' => 'Note Created By User ID', 'field_description' => 'User that created the note.', 'type_data' => 'S', 'is_relational' => true, 'is_mandatory' => true],
];

$news_main_index_list = [
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

$news_main_boundary = [
	'owns' => ['the news_main record fields and embedded structures documented in this file'],
	'reference_field_list' => ['created_by_user_id', 'updated_by_user_id', 'archived_by_user_id'],
	'does_not_own' => ['records stored in referenced collections', 'third-party news feeds'],
];

return [
	'multilingual_text_sample' => $multilingual_text_sample,
	'news_main_default' => $news_main_default,
	'news_main' => $news_main,
	'news_main_field_order' => $news_main_field_order,
	'news_main_embedded_structure' => $news_main_embedded_structure,
	'news_main_field_property' => $news_main_field_property,
	'news_main_subfield_property' => $news_main_subfield_property,
	'news_main_index_list' => $news_main_index_list,
	'news_main_boundary' => $news_main_boundary,
];
