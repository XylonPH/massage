<?php
/**
 * Title: Massage Nexus Legal Main Structure Guide
 * Version: 1.10
 * Collection: legal_main
 * Description: Stores public legal documents, Terms of Service, Privacy Policies, disclosures, and legal notices with version tracking and localization.
 * Purpose: Documents the legal_main record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * This file is a PHP-readable visual structure guide.
 * It is not a seed file, not a runtime migration script, and not a generated production schema.
 * Governed by docs/09-content/legal-writing.txt and docs/18-policy/ standards.
 */

$created_at = '2026-07-22T08:50:00Z';
$updated_at = '2026-07-22T03:28:00Z';

$multilingual_text_sample = [
	'eng' => [
		'text' => 'Sample text',
		'method_translation' => 'HUM',
		'status_review' => 'A',
	],
];

$legal_main_default = [
	'type_legal_document' => 'TOS',
	'version_identifier' => '1.0.0',
	'effective_date' => '2026-07-22',
	'status_review' => 'P',
	'is_active_version' => false,
	'requires_user_action' => false,
	'revision_number' => 1,
	'level_nsfw' => 'N',
	'status_record_lifecycle' => 'ACT',
	'record_note' => [],
];

$legal_main = [
	'_id' => 'L1aB2cC3dD4eE5fF',

	'document_title' => [
		'eng' => [
			'text' => 'Terms of Service',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
	],
	'document_body' => [
		'eng' => [
			'text' => '<p>These Terms of Service govern your access to and use of Massage Nexus...</p>',
			'method_translation' => 'HUM',
			'status_review' => 'A',
		],
	],

	'type_legal_document' => 'TOS',
	'version_identifier' => '1.0.0',
	'effective_date' => '2026-07-22',

	'is_active_version' => true,
	'requires_user_action' => true,
	'action_due_at' => '2026-08-22T00:00:00Z',
	'supersedes_legal_main_id' => null,
	'published_at' => '2026-07-22T08:50:00Z',
	'status_review' => 'A',
	'level_nsfw' => 'N',
	'status_record_lifecycle' => 'ACT',
	'record_note' => [],

	'revision_number' => 1,
	'created_at' => $created_at,
	'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'updated_at' => $updated_at,
	'updated_by_user_id' => 'U5rK8mP2xN7qL4vA',
	'archived_at' => null,
	'archived_by_user_id' => null,
];

$legal_main_field_order = [
	'_id',
	'document_title',
	'document_body',
	'type_legal_document',
	'version_identifier',
	'effective_date',
	'is_active_version',
	'requires_user_action',
	'action_due_at',
	'supersedes_legal_main_id',
	'published_at',
	'status_review',
	'level_nsfw',
	'status_record_lifecycle',
	'record_note',
	'revision_number',
	'created_at',
	'created_by_user_id',
	'updated_at',
	'updated_by_user_id',
	'archived_at',
	'archived_by_user_id',
];

$legal_main_embedded_structure = [
	'record_note' => [
		'type_record_note' => 'ED',
		'note_body' => 'Approved by platform counsel prior to release.',
		'created_at' => '2026-07-22T08:50:00Z',
		'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
	],
];

$legal_main_field_property = [
	'_id' => [
		'field_label' => 'Legal Document ID',
		'field_description' => 'Canonical application-generated 16-character Base62 identifier for the legal_main record.',
		'type_data' => 'S',
		'type_field' => 'HDN',
		'min_character' => 16,
		'max_character' => 16,
		'is_mandatory' => true,
		'is_system' => true,
		'is_indexed' => true,
	],
	'document_title' => [
		'field_label' => 'Document Title',
		'field_description' => 'Multilingual document title displayed publicly.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'is_translatable' => true,
		'is_mandatory' => true,
		'max_character' => 255,
	],
	'document_body' => [
		'field_label' => 'Document Body',
		'field_description' => 'Multilingual HTML document body content.',
		'type_data' => 'O',
		'type_field' => 'JSE',
		'is_translatable' => true,
		'is_mandatory' => true,
	],
	'type_legal_document' => [
		'field_label' => 'Legal Document Type',
		'field_description' => 'Document type code (e.g. TOS, PRIV, DISC, ADV).',
		'type_data' => 'S',
		'type_field' => 'DDL',
		'is_mandatory' => true,
		'is_indexed' => true,
	],
	'version_identifier' => [
		'field_label' => 'Version Identifier',
		'field_description' => 'SemVer string representing the revision of this legal document (e.g. 1.0.0).',
		'type_data' => 'S',
		'type_field' => 'TXT',
		'is_mandatory' => true,
	],
	'effective_date' => [
		'field_label' => 'Effective Date',
		'field_description' => 'Calendar date when this version becomes legally binding.',
		'type_data' => 'S',
		'type_field' => 'DTP',
		'type_sql' => 'DATE',
		'is_mandatory' => true,
	],
	'is_active_version' => [
		'field_label' => 'Active Version Flag',
		'field_description' => 'True if this record represents the currently active, binding legal version.',
		'type_data' => 'B',
		'type_field' => 'CHK',
		'is_indexed' => true,
	],
	'requires_user_action' => [
		'field_label' => 'Requires User Action',
		'field_description' => 'Whether this version requires a fresh acceptance, acknowledgement, or consent before protected features may be used.',
		'type_data' => 'B',
		'type_field' => 'CHK',
		'default_value' => false,
		'is_indexed' => true,
	],
	'action_due_at' => [
		'field_label' => 'Action Due At',
		'field_description' => 'Optional UTC deadline after which protected authenticated features are gated until required action is recorded.',
		'type_data' => 'S',
		'type_field' => 'DTS',
	],
	'supersedes_legal_main_id' => [
		'field_label' => 'Supersedes Legal Document',
		'field_description' => 'Prior legal_main version replaced by this record.',
		'type_data' => 'S',
		'type_field' => 'REF',
		'is_relational' => true,
	],
	'published_at' => [
		'field_label' => 'Published At',
		'field_description' => 'UTC time this approved version became publicly available.',
		'type_data' => 'S',
		'type_field' => 'DTS',
	],
	'status_review' => [
		'field_label' => 'Review Status',
		'field_description' => 'Editorial/Legal review status code (P=Pending, A=Approved, N=Needs Changes, R=Rejected).',
		'type_data' => 'S',
		'type_field' => 'DDL',
		'type_sql' => 'ENUM',
		'is_indexed' => true,
	],
	'level_nsfw' => ['field_label' => 'NSFW Level', 'field_description' => 'Content sensitivity level.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM'],
	'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Lifecycle state (ACT, INA, ARC, DEL).', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	'record_note' => ['field_label' => 'Record Note', 'field_description' => 'Internal editorial/legal notes.', 'type_data' => 'A', 'type_field' => 'JSE'],
	'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
	'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
	'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'User ID that created record.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
	'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp when record was last updated.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'updated_by_user_id' => ['field_label' => 'Updated By User ID', 'field_description' => 'User ID that updated record.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
	'archived_at' => ['field_label' => 'Archived At', 'field_description' => 'UTC timestamp when record was archived.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'archived_by_user_id' => ['field_label' => 'Archived By User ID', 'field_description' => 'User ID that archived record.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
];

$legal_main_subfield_property = [
	'record_note.type_record_note' => ['field_label' => 'Record Note Type', 'field_description' => 'Controlled note-purpose code.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
	'record_note.note_body' => ['field_label' => 'Note Body', 'field_description' => 'Internal note text.', 'type_data' => 'S', 'type_field' => 'TXA', 'is_mandatory' => true],
	'record_note.created_at' => ['field_label' => 'Note Created At', 'field_description' => 'UTC note creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
	'record_note.created_by_user_id' => ['field_label' => 'Note Created By User ID', 'field_description' => 'User that created the note.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true],
];

$legal_main_index_list = [
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

$legal_main_boundary = [
	'owns' => ['the legal_main record fields and embedded structures documented in this file'],
	'reference_field_list' => ['supersedes_legal_main_id', 'created_by_user_id', 'updated_by_user_id', 'archived_by_user_id'],
	'does_not_own' => ['records stored in referenced collections', 'runtime legal enforceability'],
];

return [
	'multilingual_text_sample' => $multilingual_text_sample,
	'legal_main_default' => $legal_main_default,
	'legal_main' => $legal_main,
	'legal_main_field_order' => $legal_main_field_order,
	'legal_main_embedded_structure' => $legal_main_embedded_structure,
	'legal_main_field_property' => $legal_main_field_property,
	'legal_main_subfield_property' => $legal_main_subfield_property,
	'legal_main_index_list' => $legal_main_index_list,
	'legal_main_boundary' => $legal_main_boundary,
];
