<?php
/**
 * Title: Massage Nexus Audit Log Structure Guide
 * Version: 1.10
 * Collection: audit_log
 * Description: Stores one immutable audit event describing an action against a governed record.
 * Purpose: Documents the audit_log record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * - audit_log stores an immutable operational trail for important record,
 *   administrative, security, approval, restoration, and system actions.
 */

$created_at = '2026-07-20T12:00:00Z';
$updated_at = '2026-07-21T04:24:17Z';
$audit_log_default = [
	'action_category' => 'DAT', // DAT = Data Change
	'actor_type' => 'ADM', // ADM = Administrator
	'reason_note' => null,
	'snapshot_previous' => null,
	'snapshot_new' => null,
	'changed_fields' => [],
];

$audit_log = [
	# Primary
	'_id' => 'K9mP2xR4tV8zN7qL', // Audit Event ID.

	# Target
	'target_collection' => 'article_main', // Target Collection.
	'target_record_id' => 'A7mK2pQ9xR4tV8zN', // Target Record ID.
	'target_record_label' => 'What Actually Happens During Your First Massage', // A human-readable label for the target record at the time of the event.

	# Action
	'action_category' => 'DAT', // DAT = Data, SEC = Security, SYS = System, MOD = Moderation
	'action_type' => 'UPD', // CRE = Create, UPD = Update, DEL = Delete, PUB = Publish, APR = Approve
	'reason_note' => 'Corrected a typo in the title.', // Reason Note.

	# Actor
	'actor_type' => 'ADM', // ADM = Admin, USR = User, SYS = System, API = API Client
	'actor_user_id' => 'U2pR7vX4kT9mC5qL', // Null if system
	'actor_ip_address' => '192.168.1.100', // Actor IP Address.
	'actor_user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)...', // Actor User Agent.

	# Data Payload
	'snapshot_previous' => [ // Partial or full snapshot of the record before the change.
		'article_title' => [
			'eng' => ['text' => 'What Actually Happnes During Your First Massage'],
		],
	],
	'snapshot_new' => [ // Partial or full snapshot of the record after the change.
		'article_title' => [
			'eng' => ['text' => 'What Actually Happens During Your First Massage'],
		],
	],
	'changed_fields' => ['article_title'], // Array of field paths changed

	# Metadata
	'created_at' => $created_at, // Created At.
];

$audit_log_field_order = [
	'_id',
	'target_collection',
	'target_record_id',
	'target_record_label',
	'action_category',
	'action_type',
	'reason_note',
	'actor_type',
	'actor_user_id',
	'actor_ip_address',
	'actor_user_agent',
	'snapshot_previous',
	'snapshot_new',
	'changed_fields',
	'created_at',
];

$audit_log_embedded_structure = [];

$audit_log_field_property = [
	'_id' => ['field_label' => 'Audit Event ID', 'type_data' => 'S', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_system' => true, 'is_indexed' => true],
	
	'target_collection' => ['field_label' => 'Target Collection', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
	'target_record_id' => ['field_label' => 'Target Record ID', 'type_data' => 'S', 'is_relational' => true, 'is_indexed' => true],
	'target_record_label' => ['field_label' => 'Target Record Label', 'field_description' => 'A human-readable label for the target record at the time of the event.', 'type_data' => 'S'],
	
	'action_category' => ['field_label' => 'Action Category', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'action_type' => ['field_label' => 'Action Type', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'reason_note' => ['field_label' => 'Reason Note', 'type_data' => 'S'],
	
	'actor_type' => ['field_label' => 'Actor Type', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'actor_user_id' => ['field_label' => 'Actor User ID', 'type_data' => 'S', 'is_relational' => true, 'is_indexed' => true],
	'actor_ip_address' => ['field_label' => 'Actor IP Address', 'type_data' => 'S'],
	'actor_user_agent' => ['field_label' => 'Actor User Agent', 'type_data' => 'S'],
	
	'snapshot_previous' => ['field_label' => 'Previous Snapshot', 'field_description' => 'Partial or full snapshot of the record before the change.', 'type_data' => 'A', 'type_field' => 'JSE'],
	'snapshot_new' => ['field_label' => 'New Snapshot', 'field_description' => 'Partial or full snapshot of the record after the change.', 'type_data' => 'A', 'type_field' => 'JSE'],
	'changed_fields' => ['field_label' => 'Changed Fields', 'field_description' => 'List of field names that were changed in this action.', 'type_data' => 'A'],
	
	'created_at' => ['field_label' => 'Created At', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true, 'is_indexed' => true],
];

$audit_log_subfield_property = [];

$audit_log_index_list = [
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

$audit_log_boundary = [
    'owns' => [
        'the audit_log record fields and embedded structures documented in this file',
    ],
    'reference_field_list' => [
        'target_record_id',
        'actor_user_id',
    ],
    'does_not_own' => [
        'records stored in referenced collections',
        'runtime authorization, migration, seeding, or deployment behavior',
    ],
];

return [
    'audit_log_default' => $audit_log_default,
    'audit_log' => $audit_log,
    'audit_log_field_order' => $audit_log_field_order,
    'audit_log_embedded_structure' => $audit_log_embedded_structure,
    'audit_log_field_property' => $audit_log_field_property,
    'audit_log_subfield_property' => $audit_log_subfield_property,
    'audit_log_index_list' => $audit_log_index_list,
    'audit_log_boundary' => $audit_log_boundary,
];
