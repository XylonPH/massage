<?php
/**
 * Title: Massage Nexus Moderation Main Structure Guide
 * Version: 1.10
 * Collection: moderation_main
 * Description: Stores one moderation case and its current workflow state.
 * Purpose: Documents the moderation_main record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * - moderation_main stores the state, assignment, and resolution of a case.
 * - Individual actions and decisions are stored in moderation_action.
 */

$created_at = '2026-07-20T12:00:00Z';
$updated_at = '2026-07-21T04:24:17Z';
$moderation_main_default = [
	'status_moderation' => 'OPN', // OPN = Open
	'priority_level' => 'M', // M = Medium
	'category_report_list' => [],
	'assigned_user_id' => null,
	'assigned_at' => null,
	'resolved_at' => null,
	'resolution_type' => null,
	'is_escalated' => false,
	'is_appealed' => false,
	'related_moderation_id_list' => [],
];

$moderation_main = [
	# Primary
	'_id' => 'M4sK2pQ9xR7tV8zW', // Moderation Case ID.

	# Target
	'target_collection' => 'review_main', // e.g. review_main, media_image, user_main
	'target_record_id' => 'R9mP2xR4tV8zN7qL', // The identifier of the reported or moderated record.
	
	# Case Details
	'status_moderation' => 'URV', // OPN = Open, URV = Under Review, RES = Resolved, CLS = Closed
	'priority_level' => 'H', // H = High, M = Medium, L = Low
	'category_report_list' => ['SPAM', 'HARASSMENT'], // The policy violations reported
	'is_escalated' => true, // Is Escalated.
	'is_appealed' => false, // Is Appealed.
	
	# Assignment
	'assigned_user_id' => 'U2pR7vX4kT9mC5qL', // Administrator handling the case
	'assigned_at' => '2026-07-20T12:15:00Z', // Assigned At.
	
	# Resolution
	'resolved_at' => null, // Timestamp of resolution
	'resolution_type' => null, // REM = Removed, REJ = Rejected Report, WRN = Warned, RES = Restricted
	
	# Relationships
	'related_moderation_id_list' => ['M8nP3yT1qR9wV2kM'], // Related cases, e.g. multiple reports on the same user
	
	# Audit
	'created_at' => $created_at, // Created At.
	'updated_at' => $updated_at, // Updated At.
];

$moderation_main_field_order = [
	'_id',
	'target_collection',
	'target_record_id',
	'status_moderation',
	'priority_level',
	'category_report_list',
	'is_escalated',
	'is_appealed',
	'assigned_user_id',
	'assigned_at',
	'resolved_at',
	'resolution_type',
	'related_moderation_id_list',
	'created_at',
	'updated_at',
];

$moderation_main_embedded_structure = [];

$moderation_main_field_property = [
	'_id' => ['field_label' => 'Moderation Case ID', 'type_data' => 'S', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_system' => true, 'is_indexed' => true],
	
	'target_collection' => ['field_label' => 'Target Collection', 'field_description' => 'The collection of the reported or moderated record.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
	'target_record_id' => ['field_label' => 'Target Record ID', 'field_description' => 'The identifier of the reported or moderated record.', 'type_data' => 'S', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
	
	'status_moderation' => ['field_label' => 'Moderation Status', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'priority_level' => ['field_label' => 'Priority Level', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'category_report_list' => ['field_label' => 'Report Categories', 'field_description' => 'List of policy violation categories reported for this case.', 'type_data' => 'A', 'is_indexed' => true],
	'is_escalated' => ['field_label' => 'Is Escalated', 'type_data' => 'B', 'type_field' => 'CHK', 'type_sql' => 'BOOLEAN', 'is_indexed' => true],
	'is_appealed' => ['field_label' => 'Is Appealed', 'type_data' => 'B', 'type_field' => 'CHK', 'type_sql' => 'BOOLEAN', 'is_indexed' => true],
	
	'assigned_user_id' => ['field_label' => 'Assigned User ID', 'field_description' => 'Administrator currently assigned to the case.', 'type_data' => 'S', 'is_relational' => true, 'is_indexed' => true],
	'assigned_at' => ['field_label' => 'Assigned At', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	
	'resolved_at' => ['field_label' => 'Resolved At', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'resolution_type' => ['field_label' => 'Resolution Type', 'field_description' => 'The final outcome of the moderation case.', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_indexed' => true],
	
	'related_moderation_id_list' => ['field_label' => 'Related Moderation ID List', 'type_data' => 'A', 'is_relational' => true],
	
	'created_at' => ['field_label' => 'Created At', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true, 'is_indexed' => true],
	'updated_at' => ['field_label' => 'Updated At', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
];

$moderation_main_subfield_property = [];

$moderation_main_index_list = [
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

$moderation_main_boundary = [
    'owns' => [
        'the moderation_main record fields and embedded structures documented in this file',
    ],
    'reference_field_list' => [
        'target_record_id',
        'assigned_user_id',
        'related_moderation_id_list',
    ],
    'does_not_own' => [
        'records stored in referenced collections',
        'runtime authorization, migration, seeding, or deployment behavior',
    ],
];

return [
    'moderation_main_default' => $moderation_main_default,
    'moderation_main' => $moderation_main,
    'moderation_main_field_order' => $moderation_main_field_order,
    'moderation_main_embedded_structure' => $moderation_main_embedded_structure,
    'moderation_main_field_property' => $moderation_main_field_property,
    'moderation_main_subfield_property' => $moderation_main_subfield_property,
    'moderation_main_index_list' => $moderation_main_index_list,
    'moderation_main_boundary' => $moderation_main_boundary,
];
