<?php
/**
 * Title: Massage Nexus Audit Log Structure Guide
 * Author: Xylon Reyes
 *
 * Collection: audit_log
 * Version: 1.00
 * This file is a PHP-readable visual structure guide.
 *
 * Layer rule:
 * - *_record_default contains defaults for actual stored record fields only.
 * - *_field_property describes schema/field metadata only.
 *
 * Current scope:
 * - audit_log stores an immutable operational trail for important record,
 *   administrative, security, approval, restoration, and system actions.
 */

# Variable
$created_at = '2026-07-20T12:00:00Z';

$field_property_default = [
	'type_data' => 'S',
	'type_field' => 'TXT',
	'is_translatable' => false,
	'is_mandatory' => false,
	'is_relational' => false,
	'is_indexed' => false,
];

$audit_log_record_default = [
	'action_category' => 'DAT', // DAT = Data Change
	'actor_type' => 'ADM', // ADM = Administrator
	'reason_note' => null,
	'snapshot_previous' => null,
	'snapshot_new' => null,
	'changed_fields' => [],
];

$audit_log = [
	# Primary
	'_id' => 'K9mP2xR4tV8zN7qL',

	# Target
	'target_collection' => 'article_main',
	'target_record_id' => 'A7mK2pQ9xR4tV8zN',
	'target_record_label' => 'What Actually Happens During Your First Massage',

	# Action
	'action_category' => 'DAT', // DAT = Data, SEC = Security, SYS = System, MOD = Moderation
	'action_type' => 'UPD', // CRE = Create, UPD = Update, DEL = Delete, PUB = Publish, APR = Approve
	'reason_note' => 'Corrected a typo in the title.',

	# Actor
	'actor_type' => 'ADM', // ADM = Admin, USR = User, SYS = System, API = API Client
	'actor_user_id' => 'U2pR7vX4kT9mC5qL', // Null if system
	'actor_ip_address' => '192.168.1.100',
	'actor_user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)...',

	# Data Payload
	'snapshot_previous' => [
		'article_title' => [
			'eng' => ['text' => 'What Actually Happnes During Your First Massage'],
		],
	],
	'snapshot_new' => [
		'article_title' => [
			'eng' => ['text' => 'What Actually Happens During Your First Massage'],
		],
	],
	'changed_fields' => ['article_title'], // Array of field paths changed

	# Metadata
	'created_at' => $created_at,
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
