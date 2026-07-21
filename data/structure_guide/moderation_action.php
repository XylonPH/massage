<?php
/**
 * Title: Massage Nexus Moderation Action Structure Guide
 * Version: 1.10
 * Collection: moderation_action
 * Description: Stores one immutable action taken during moderation of a governed record.
 * Purpose: Documents the moderation_action record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * - moderation_action stores individual human decisions, administrative
 *   actions, AI recommendations, escalations, or reversals inside a case.
 */

$created_at = '2026-07-20T12:00:00Z';
$updated_at = '2026-07-21T04:24:17Z';
$moderation_action_default = [
	'actor_type' => 'ADM', // ADM = Administrator
	'reason_note' => null,
	'is_system_generated' => false,
];

$moderation_action = [
	# Primary
	'_id' => 'K1sL9mP2xR4tV8zN', // Moderation Action ID.

	# Parent Relationship
	'moderation_id' => 'M4sK2pQ9xR7tV8zW', // Parent moderation case
	
	# Action
	'action_type' => 'RES', // RES = Restrict, RMV = Remove, APR = Approve, ESC = Escalate
	'reason_note' => 'Spam content confirmed. Temporarily restricted account.', // Reason Note.
	
	# Actor
	'actor_type' => 'ADM', // ADM = Admin, AI = AI Model, SYS = System
	'actor_user_id' => 'U2pR7vX4kT9mC5qL', // User ID of the administrator
	'is_system_generated' => false, // Is System Generated.
	
	# Metadata
	'created_at' => $created_at, // Created At.
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

$moderation_action_embedded_structure = [];

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

$moderation_action_subfield_property = [];

$moderation_action_index_list = [
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

$moderation_action_boundary = [
    'owns' => [
        'the moderation_action record fields and embedded structures documented in this file',
    ],
    'reference_field_list' => [
        'moderation_id',
        'actor_user_id',
    ],
    'does_not_own' => [
        'records stored in referenced collections',
        'runtime authorization, migration, seeding, or deployment behavior',
    ],
];

return [
    'moderation_action_default' => $moderation_action_default,
    'moderation_action' => $moderation_action,
    'moderation_action_field_order' => $moderation_action_field_order,
    'moderation_action_embedded_structure' => $moderation_action_embedded_structure,
    'moderation_action_field_property' => $moderation_action_field_property,
    'moderation_action_subfield_property' => $moderation_action_subfield_property,
    'moderation_action_index_list' => $moderation_action_index_list,
    'moderation_action_boundary' => $moderation_action_boundary,
];
