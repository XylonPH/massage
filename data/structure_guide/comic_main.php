<?php
/**
 * Title: Massage Nexus Comic Main Structure Guide
 * Version: 1.00
 * Collection: comic_main
 * Description: Stores one language-specific comic strip record, its publication metadata, complete-page media relationship, accessibility text, and audit history.
 * Purpose: Documents the comic_main record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * - Governed by docs/09-content/the-resting-leaf.txt and docs/04-architecture/database-structure.txt.
 * - One record represents one language-specific published or draft edition of a strip.
 * - The standard strip owns six ordered comic_panel children.
 * - media_image owns technical image and rendition metadata; this record references the approved complete-page image.
 */

$created_at = '2026-07-23T17:08:39Z';
$updated_at = '2026-07-23T17:08:39Z';

$comic_main_default = [
	'short_description' => '',
	'cast_key_list' => [],
	'alt_text' => '',
	'caption_text' => '',
	'comic_transcript' => '',
	'tag_id_list' => [],
	'primary_media_image_id' => null,
	'level_nsfw' => 'N',
	'status_publication' => 'D',
	'status_record_lifecycle' => 'ACT',
	'record_note' => [],
	'revision_number' => 1,
	'scheduled_publish_at' => null,
	'published_at' => null,
	'published_by_user_id' => null,
	'archived_at' => null,
	'archived_by_user_id' => null,
];

$comic_main = [
	# Primary
	'_id' => 'C7mK2pQ9xR4tV8zN', // Canonical application-generated 16-character Base62 identifier.

	# Public Content
	'comic_title' => 'Grand Opening', // Individual strip title; The Resting Leaf remains the series title.
	'comic_slug' => 'grand-opening', // URL-safe value used by /comic/{comic-slug}.
	'short_description' => 'Opening-day nerves turn a simple client request into a six-panel chain reaction.',
	'language_id' => 3049, // Language of this comic edition in common_reference.language_main.
	'cast_key_list' => ['enzo', 'iris', 'gavin', 'lara'], // Stable keys from the controlled Nexus Cast project reference.
	'alt_text' => 'Six-panel sequence showing the Resting Leaf staff preparing for their first client and realizing the client is already inside.',
	'caption_text' => 'The Resting Leaf team learns that opening day started earlier than expected.',
	'comic_transcript' => "Panel 1: Gavin says, 'Ready na ba tayo?' Panel 2: Lara points behind him. Panel 3: The waiting client waves.",

	# Discovery / Media
	'tag_id_list' => ['T3gH7kM2pR9vX4cN'],
	'primary_media_image_id' => 'Im7K2pQ9xR4tV8zN', // Approved complete-page image in media_image.

	# Handling
	'level_nsfw' => 'N',
	'status_publication' => 'P',
	'status_record_lifecycle' => 'ACT',
	'record_note' => [
		[
			'type_record_note' => 'ED',
			'note_body' => 'Final lettering was checked against the approved Taglish script.',
			'created_at' => '2026-07-23T16:30:00Z',
			'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
		],
	],

	# Concurrency / Audit
	'revision_number' => 3,
	'created_at' => $created_at,
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'updated_at' => $updated_at,
	'updated_by_user_id' => 'U2pR7vX4kT9mC5qL',
	'scheduled_publish_at' => null,
	'published_at' => '2026-07-24T00:00:00Z',
	'published_by_user_id' => 'U2pR7vX4kT9mC5qL',
	'archived_at' => null,
	'archived_by_user_id' => null,
];

$comic_main_field_order = [
	'_id',
	'comic_title',
	'comic_slug',
	'short_description',
	'language_id',
	'cast_key_list',
	'alt_text',
	'caption_text',
	'comic_transcript',
	'tag_id_list',
	'primary_media_image_id',
	'level_nsfw',
	'status_publication',
	'status_record_lifecycle',
	'record_note',
	'revision_number',
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

$comic_main_embedded_structure = [
	'record_note' => [
		'type_record_note' => 'ED',
		'note_body' => 'Internal note text for editors, reviewers, or administrators.',
		'created_at' => '2026-07-23T16:30:00Z',
		'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	],
];

$comic_main_field_property = [
	'_id' => ['field_label' => 'Comic ID', 'field_description' => 'Canonical application-generated 16-character Base62 identifier for the comic_main record.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_system' => true, 'is_indexed' => true],
	'comic_title' => ['field_label' => 'Comic Title', 'field_description' => 'Individual public title of this comic strip; the recurring series title is not repeated here.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 255, 'is_mandatory' => true],
	'comic_slug' => ['field_label' => 'Comic Slug', 'field_description' => 'Unique URL-safe slug used by the public comic detail route.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 1, 'max_character' => 255, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
	'short_description' => ['field_label' => 'Short Description', 'field_description' => 'Concise preview description for comic cards, search results, and sharing.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 255, 'default_value' => ''],
	'language_id' => ['field_label' => 'Language ID', 'field_description' => 'Numeric reference to common_reference.language_main for this language-specific comic edition.', 'type_data' => 'I', 'type_field' => 'REF', 'type_sql' => 'INT', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
	'cast_key_list' => ['field_label' => 'Cast Key List', 'field_description' => 'Stable Nexus Cast project-reference keys for recurring characters visibly present in the strip; unnamed background extras are excluded.', 'type_data' => 'A', 'type_field' => 'TAG', 'default_value' => [], 'is_indexed' => true],
	'alt_text' => ['field_label' => 'Alt Text', 'field_description' => 'Accessible description of the meaningful visual sequence in the complete comic page without merely repeating the caption or transcript.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 2000, 'default_value' => ''],
	'caption_text' => ['field_label' => 'Caption Text', 'field_description' => 'Optional concise public caption or supporting text shown with the comic.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 1000, 'default_value' => ''],
	'comic_transcript' => ['field_label' => 'Comic Transcript', 'field_description' => 'Complete readable projection of final dialogue, captions, and relevant sound-effect text in panel order.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 10000, 'default_value' => ''],
	'tag_id_list' => ['field_label' => 'Tag ID List', 'field_description' => 'References to tag_main records used for discovery, filtering, and editorial organization.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true, 'is_indexed' => true, 'default_value' => []],
	'primary_media_image_id' => ['field_label' => 'Primary Media Image ID', 'field_description' => 'Reference to the media_image record containing the approved complete comic page and its technical renditions.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'default_value' => null],
	'level_nsfw' => ['field_label' => 'NSFW Level', 'field_description' => 'Content-sensitivity level of the complete comic.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'default_value' => 'N'],
	'status_publication' => ['field_label' => 'Publication Status', 'field_description' => 'Current publication state of the comic edition.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true, 'default_value' => 'D'],
	'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state of the comic_main record.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'default_value' => 'ACT'],
	'record_note' => ['field_label' => 'Record Note', 'field_description' => 'Embedded internal notes attached to this comic record.', 'type_data' => 'A', 'type_field' => 'JSE', 'default_value' => []],
	'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Optimistic-concurrency token that starts at 1 and increments by exactly 1 after every accepted record revision.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 1, 'is_mandatory' => true, 'is_system' => true, 'default_value' => 1],
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when the comic_main record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'Reference to the user that created the comic_main record.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp when the comic_main record was last meaningfully updated.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'updated_by_user_id' => ['field_label' => 'Updated By User ID', 'field_description' => 'Reference to the user that last updated the comic_main record.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true],
	'scheduled_publish_at' => ['field_label' => 'Scheduled Publish At', 'field_description' => 'UTC timestamp when a scheduled comic should be published; removed after successful publication.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'default_value' => null],
	'published_at' => ['field_label' => 'Published At', 'field_description' => 'UTC timestamp when this comic edition was actually published.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_indexed' => true, 'default_value' => null],
	'published_by_user_id' => ['field_label' => 'Published By User ID', 'field_description' => 'Reference to the user that published this comic edition.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'default_value' => null],
	'archived_at' => ['field_label' => 'Archived At', 'field_description' => 'UTC timestamp when this comic record was archived.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'default_value' => null],
	'archived_by_user_id' => ['field_label' => 'Archived By User ID', 'field_description' => 'Reference to the user that archived this comic record.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'default_value' => null],
];

$comic_main_subfield_property = [
	'record_note.type_record_note' => ['field_label' => 'Record Note Type', 'field_description' => 'Controlled purpose code for the embedded internal note.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
	'record_note.note_body' => ['field_label' => 'Note Body', 'field_description' => 'Internal note text for editors, reviewers, or administrators.', 'type_data' => 'S', 'type_field' => 'TXA', 'is_mandatory' => true],
	'record_note.created_at' => ['field_label' => 'Note Created At', 'field_description' => 'UTC timestamp when the embedded note was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
	'record_note.created_by_user_id' => ['field_label' => 'Note Created By User ID', 'field_description' => 'Reference to the user that created the embedded note.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true],
];

$comic_main_index_list = [
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
		'index_key' => 'comic_slug_unique',
		'index_name' => 'uq_comic_main_comic_slug',
		'type_index' => 'STD',
		'is_unique' => true,
		'is_sparse' => false,
		'index_field_list' => [
			['field_name' => 'comic_slug', 'type_index_mode' => 'ASC', 'sort_order' => 10],
		],
		'sort_order' => 20,
	],
	[
		'index_key' => 'publication_feed',
		'index_name' => 'ix_comic_main_publication_feed',
		'type_index' => 'CMP',
		'is_unique' => false,
		'is_sparse' => false,
		'index_field_list' => [
			['field_name' => 'status_publication', 'type_index_mode' => 'ASC', 'sort_order' => 10],
			['field_name' => 'language_id', 'type_index_mode' => 'ASC', 'sort_order' => 20],
			['field_name' => 'published_at', 'type_index_mode' => 'DES', 'sort_order' => 30],
		],
		'sort_order' => 30,
	],
	[
		'index_key' => 'cast_filter',
		'index_name' => 'ix_comic_main_cast_key_list',
		'type_index' => 'STD',
		'is_unique' => false,
		'is_sparse' => true,
		'index_field_list' => [
			['field_name' => 'cast_key_list', 'type_index_mode' => 'ASC', 'sort_order' => 10],
		],
		'sort_order' => 40,
	],
	[
		'index_key' => 'tag_filter',
		'index_name' => 'ix_comic_main_tag_id_list',
		'type_index' => 'STD',
		'is_unique' => false,
		'is_sparse' => true,
		'index_field_list' => [
			['field_name' => 'tag_id_list', 'type_index_mode' => 'ASC', 'sort_order' => 10],
		],
		'sort_order' => 50,
	],
];

$comic_main_boundary = [
	'owns' => [
		'the comic_main record fields and embedded record notes documented in this file',
		'the strip-level publication state, complete-page accessibility projection, and relationship to the approved complete-page image',
	],
	'reference_field_list' => [
		'language_id',
		'tag_id_list',
		'primary_media_image_id',
		'record_note.created_by_user_id',
		'created_by_user_id',
		'updated_by_user_id',
		'published_by_user_id',
		'archived_by_user_id',
	],
	'does_not_own' => [
		'comic_panel child records',
		'media_image technical files, storage details, thumbnails, or renditions',
		'Nexus Cast project-reference definitions named by cast_key_list',
		'tag, language, or user records stored in referenced collections or datasets',
		'runtime authorization, migration, seeding, publication jobs, or deployment behavior',
	],
];

return [
	'comic_main_default' => $comic_main_default,
	'comic_main' => $comic_main,
	'comic_main_field_order' => $comic_main_field_order,
	'comic_main_embedded_structure' => $comic_main_embedded_structure,
	'comic_main_field_property' => $comic_main_field_property,
	'comic_main_subfield_property' => $comic_main_subfield_property,
	'comic_main_index_list' => $comic_main_index_list,
	'comic_main_boundary' => $comic_main_boundary,
];
