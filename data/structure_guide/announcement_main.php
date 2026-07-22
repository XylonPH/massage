<?php
/**
 * Title: Massage Nexus Announcement Main Structure Guide
 * Version: 1.00
 * Collection: announcement_main
 * Description: Stores first-party platform announcements, service updates, and official release notes.
 * Purpose: Documents the announcement_main record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * This file is a PHP-readable visual structure guide.
 * Governed by docs/09-content/news-writing.txt Section 27 standards.
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

$announcement_main_default = [
	'type_announcement_category' => 'SYS',
	'display_start_date' => null,
	'display_end_date' => null,
	'is_pinned' => false,
	'status_review' => 'P',
	'level_nsfw' => 'N',
	'status_record_lifecycle' => 'ACT',
	'record_note' => [],
];

$announcement_main = [
	'_id' => 'A1aB2cC3dD4eE5fF',

	'announcement_title' => [
		'eng' => [
			'text' => 'Scheduled System Maintenance on July 25, 2026',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
	],
	'announcement_body' => [
		'eng' => [
			'text' => '<p>Massage Nexus will undergo scheduled database optimization on July 25...</p>',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
	],

	'type_announcement_category' => 'SYS',
	'display_start_date' => '2026-07-22',
	'display_end_date' => '2026-07-26',
	'is_pinned' => true,

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

$announcement_main_field_order = [
	'_id',
	'announcement_title',
	'announcement_body',
	'type_announcement_category',
	'display_start_date',
	'display_end_date',
	'is_pinned',
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

$announcement_main_embedded_structure = [
	'record_note' => [
		'type_record_note' => 'AD',
		'note_body' => 'Approved for homepage banner placement.',
		'created_at' => '2026-07-22T08:50:00Z',
		'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	],
];

$announcement_main_field_property = [
	'_id' => [
		'field_label' => 'Announcement ID',
		'field_description' => 'Canonical application-generated 16-character Base62 identifier for announcement_main.',
		'type_data' => 'S',
		'min_character' => 16,
		'max_character' => 16,
		'is_mandatory' => true,
		'is_system' => true,
		'is_indexed' => true,
	],
	'announcement_title' => [
		'field_label' => 'Announcement Title',
		'field_description' => 'Multilingual announcement title text.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'is_translatable' => true,
		'is_mandatory' => true,
		'max_character' => 255,
	],
	'announcement_body' => [
		'field_label' => 'Announcement Body',
		'field_description' => 'Multilingual announcement body text/HTML.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'is_translatable' => true,
		'is_mandatory' => true,
	],
	'type_announcement_category' => [
		'field_label' => 'Announcement Category',
		'field_description' => 'Category code (e.g. SYS, FEAT, SEC, MAINT).',
		'type_data' => 'S',
		'type_field' => 'DDL',
		'is_mandatory' => true,
		'is_indexed' => true,
	],
	'display_start_date' => [
		'field_label' => 'Display Start Date',
		'field_description' => 'First calendar date announcement is displayed.',
		'type_field' => 'DTP',
		'type_sql' => 'DATE',
	],
	'display_end_date' => [
		'field_label' => 'Display End Date',
		'field_description' => 'Last calendar date announcement is displayed.',
		'type_field' => 'DTP',
		'type_sql' => 'DATE',
	],
	'is_pinned' => [
		'field_label' => 'Pinned Flag',
		'field_description' => 'True if announcement is pinned to top of feed/banner.',
		'type_data' => 'B',
		'type_field' => 'TGL',
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

$announcement_main_subfield_property = [
	'record_note.type_record_note' => ['field_label' => 'Record Note Type', 'field_description' => 'Controlled note-purpose code.', 'type_data' => 'S', 'is_mandatory' => true],
	'record_note.note_body' => ['field_label' => 'Note Body', 'field_description' => 'Internal note text.', 'type_data' => 'S', 'is_mandatory' => true],
	'record_note.created_at' => ['field_label' => 'Note Created At', 'field_description' => 'UTC note creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
	'record_note.created_by_user_id' => ['field_label' => 'Note Created By User ID', 'field_description' => 'User that created the note.', 'type_data' => 'S', 'is_relational' => true, 'is_mandatory' => true],
];

$announcement_main_index_list = [
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

$announcement_main_boundary = [
	'owns' => ['the announcement_main record fields and embedded structures documented in this file'],
	'reference_field_list' => ['created_by_user_id', 'updated_by_user_id', 'archived_by_user_id'],
	'does_not_own' => ['records stored in referenced collections'],
];

return [
	'multilingual_text_sample' => $multilingual_text_sample,
	'announcement_main_default' => $announcement_main_default,
	'announcement_main' => $announcement_main,
	'announcement_main_field_order' => $announcement_main_field_order,
	'announcement_main_embedded_structure' => $announcement_main_embedded_structure,
	'announcement_main_field_property' => $announcement_main_field_property,
	'announcement_main_subfield_property' => $announcement_main_subfield_property,
	'announcement_main_index_list' => $announcement_main_index_list,
	'announcement_main_boundary' => $announcement_main_boundary,
];
