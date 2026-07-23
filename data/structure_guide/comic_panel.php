<?php
/**
 * Title: Massage Nexus Comic Panel Structure Guide
 * Version: 1.00
 * Collection: comic_panel
 * Description: Stores one ordered panel belonging to a language-specific comic_main record, including production direction, final dialogue, accessibility text, and an optional panel image relationship.
 * Purpose: Documents the comic_panel record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * - Governed by docs/09-content/the-resting-leaf.txt and docs/04-architecture/database-structure.txt.
 * - A standard strip requires exactly six child records numbered 1 through 6 before publication.
 * - Full-page-first generation remains preferred; primary_media_image_id is optional for a panel-specific image or approved crop.
 */

$created_at = '2026-07-23T17:08:39Z';
$updated_at = '2026-07-23T17:08:39Z';

$comic_panel_default = [
	'cast_key_list' => [],
	'panel_description' => '',
	'dialogue_text' => '',
	'alt_text' => '',
	'primary_media_image_id' => null,
	'status_record_lifecycle' => 'ACT',
	'revision_number' => 1,
];

$comic_panel = [
	# Primary / Parent
	'_id' => 'P8nL3qR1yT6vW9zM', // Canonical application-generated 16-character Base62 identifier.
	'comic_id' => 'C7mK2pQ9xR4tV8zN', // Owning comic_main record.
	'panel_number' => 1, // Stable visible and reading-order number from 1 through 6.

	# Panel Content
	'cast_key_list' => ['gavin', 'lara'], // Recurring cast visibly present in this panel.
	'panel_description' => 'Reception desk at opening time. Gavin checks a clipboard with a confident smile while Lara notices someone behind him. Warm morning light, tidy counter, and no continuity change yet.',
	'dialogue_text' => "Gavin: 'Ready na ba tayo?'",
	'alt_text' => 'Gavin smiles over a clipboard at the reception desk while Lara looks past him toward the waiting area.',
	'primary_media_image_id' => null, // Optional panel-specific media image; the complete page may be sufficient.

	# Handling / Concurrency / Audit
	'status_record_lifecycle' => 'ACT',
	'revision_number' => 2,
	'created_at' => $created_at,
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'updated_at' => $updated_at,
	'updated_by_user_id' => 'U2pR7vX4kT9mC5qL',
];

$comic_panel_field_order = [
	'_id',
	'comic_id',
	'panel_number',
	'cast_key_list',
	'panel_description',
	'dialogue_text',
	'alt_text',
	'primary_media_image_id',
	'status_record_lifecycle',
	'revision_number',
	'created_at',
	'created_by_user_id',
	'updated_at',
	'updated_by_user_id',
];

$comic_panel_embedded_structure = [];

$comic_panel_field_property = [
	'_id' => ['field_label' => 'Comic Panel ID', 'field_description' => 'Canonical application-generated 16-character Base62 identifier for the comic_panel record.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_system' => true, 'is_indexed' => true],
	'comic_id' => ['field_label' => 'Comic ID', 'field_description' => 'Reference to the owning language-specific comic_main record.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
	'panel_number' => ['field_label' => 'Panel Number', 'field_description' => 'Stable visible number and reading-order position within the standard six-panel strip.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 1, 'max_number' => 6, 'is_mandatory' => true, 'is_indexed' => true],
	'cast_key_list' => ['field_label' => 'Cast Key List', 'field_description' => 'Stable Nexus Cast project-reference keys for recurring characters visibly present in this panel; unnamed background extras are excluded.', 'type_data' => 'A', 'type_field' => 'TAG', 'default_value' => []],
	'panel_description' => ['field_label' => 'Panel Description', 'field_description' => 'Approved production direction for the panel, including relevant setting, action, expressions, balloon placement, time and lighting, important props, and continuity change.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 5000, 'default_value' => ''],
	'dialogue_text' => ['field_label' => 'Dialogue Text', 'field_description' => 'Final dialogue, captions, and sound-effect text for this panel in reading order, including speaker labels where needed; empty for a silent panel.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 3000, 'default_value' => ''],
	'alt_text' => ['field_label' => 'Alt Text', 'field_description' => 'Accessible description of the meaningful visual content of this panel without merely repeating its dialogue.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 1000, 'default_value' => ''],
	'primary_media_image_id' => ['field_label' => 'Primary Media Image ID', 'field_description' => 'Optional reference to a media_image record for an individual panel image or approved crop.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'default_value' => null],
	'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state of the comic_panel record.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'default_value' => 'ACT'],
	'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Optimistic-concurrency token that starts at 1 and increments by exactly 1 after every accepted panel revision.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 1, 'is_mandatory' => true, 'is_system' => true, 'default_value' => 1],
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when the comic_panel record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'Reference to the user that created the comic_panel record.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp when the comic_panel record was last meaningfully updated.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'updated_by_user_id' => ['field_label' => 'Updated By User ID', 'field_description' => 'Reference to the user that last updated the comic_panel record.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true],
];

$comic_panel_subfield_property = [];

$comic_panel_index_list = [
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
	[
		'index_key' => 'comic_panel_number_unique',
		'index_name' => 'uq_comic_panel_comic_id_panel_number',
		'type_index' => 'CMP',
		'is_unique' => true,
		'is_sparse' => false,
		'index_field_list' => [
			['field_name' => 'comic_id', 'type_index_mode' => 'ASC', 'sort_order' => 10],
			['field_name' => 'panel_number', 'type_index_mode' => 'ASC', 'sort_order' => 20],
		],
		'sort_order' => 20,
	],
];

$comic_panel_boundary = [
	'owns' => [
		'the comic_panel record fields documented in this file',
		'the panel number, production direction, final dialogue, and panel-specific accessibility text',
	],
	'reference_field_list' => [
		'comic_id',
		'primary_media_image_id',
		'created_by_user_id',
		'updated_by_user_id',
	],
	'does_not_own' => [
		'the parent comic_main record or its publication state',
		'the complete-page comic image referenced by comic_main',
		'media_image technical files, storage details, thumbnails, or renditions',
		'Nexus Cast project-reference definitions named by cast_key_list',
		'user records stored in referenced collections',
		'runtime authorization, migration, seeding, publication validation, or deployment behavior',
	],
];

return [
	'comic_panel_default' => $comic_panel_default,
	'comic_panel' => $comic_panel,
	'comic_panel_field_order' => $comic_panel_field_order,
	'comic_panel_embedded_structure' => $comic_panel_embedded_structure,
	'comic_panel_field_property' => $comic_panel_field_property,
	'comic_panel_subfield_property' => $comic_panel_subfield_property,
	'comic_panel_index_list' => $comic_panel_index_list,
	'comic_panel_boundary' => $comic_panel_boundary,
];
