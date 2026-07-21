<?php
/**
 * Title: Massage Nexus Moderation Action Structure Guide
 * Author: Xylon Reyes
 *
 * Collection: moderation_action
 * Version: 1.00
 * This file is a PHP-readable visual structure guide.
 *
 * Layer rule:
 * - *_record_default contains defaults for actual stored record fields only.
 * - *_field_property describes schema/field metadata only.
 *
 * Current scope:
 * - moderation_action stores individual human decisions, administrative
 *   actions, AI recommendations, escalations, or reversals inside a case.
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

$moderation_action_record_default = [
	'actor_type' => 'ADM', // ADM = Administrator
	'reason_note' => null,
	'is_system_generated' => false,
];

$moderation_action = [
	# Primary
	'_id' => 'K1sL9mP2xR4tV8zN',

	# Parent Relationship
	'moderation_id' => 'M4sK2pQ9xR7tV8zW', // Parent moderation case
	
	# Action
	'action_type' => 'RES', // RES = Restrict, RMV = Remove, APR = Approve, ESC = Escalate
	'reason_note' => 'Spam content confirmed. Temporarily restricted account.',
	
	# Actor
	'actor_type' => 'ADM', // ADM = Admin, AI = AI Model, SYS = System
	'actor_user_id' => 'U2pR7vX4kT9mC5qL', // User ID of the administrator
	'is_system_generated' => false,
	
	# Metadata
	'created_at' => $created_at,
];

$moderation_action_field_order = [
	'_id',
	'moderation_id',
	'action_type',
	'reason_note',
	'actor_type',
	'actor_user_id',
	'is_system_generated',
	'created_at',
];

$moderation_action_field_property = [
	'_id' => ['field_label' => 'Moderation Action ID', 'type_data' => 'S', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_system' => true, 'is_indexed' => true],
	
	'moderation_id' => ['field_label' => 'Moderation Case ID', 'type_data' => 'S', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
	
	'action_type' => ['field_label' => 'Action Type', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'reason_note' => ['field_label' => 'Reason Note', 'type_data' => 'S'],
	
	'actor_type' => ['field_label' => 'Actor Type', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'actor_user_id' => ['field_label' => 'Actor User ID', 'type_data' => 'S', 'is_relational' => true, 'is_indexed' => true],
	'is_system_generated' => ['field_label' => 'Is System Generated', 'type_data' => 'B', 'type_field' => 'CHK', 'type_sql' => 'BOOLEAN'],
	
	'created_at' => ['field_label' => 'Created At', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true, 'is_indexed' => true],
];
