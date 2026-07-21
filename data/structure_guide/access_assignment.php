<?php
/**
 * Title: Massage Nexus Access Assignment Structure Guide
 * Version: 0.30
 * Collection: access_assignment
 * Description: Stores one additive role or direct-permission assignment for a user within an optional scope.
 * Purpose: Documents the access_assignment record shape for review, validation, comparison, and implementation without acting as runtime authorization code, a migration, or a seed.
 *
 * Notes:
 * - Personal user capabilities do not require an assignment.
 * - Only active assignments inside their effective and expiry window grant access.
 * - Establishment ownership or management belongs to establishment_person or organization_establishment; this collection owns only permissions.
 */

$created_at = '2026-07-20T10:31:38Z';
$updated_at = '2026-07-21T08:49:01Z';
$access_assignment_default = [
    'permission_code_list' => [],
    'scope_access' => 'GBL',
    'status_access_assignment' => 'PND',
];

$access_assignment = [
    '_id' => 'Aa7K2pQ9xR4tV8zN', // Canonical 16-character assignment identifier.
    'user_id' => 'U2pR7vX4kT9mC5qL', // User receiving the assignment.
    'role_workspace' => 'editor', // Optional workspace role bundle.
    'permission_code_list' => ['article.publish'], // Direct permission codes added by this assignment.
    'scope_access' => 'GBL', // Scope code; GBL means global.
    'scope_record_id' => null, // Scoped record identifier; omitted or null for GBL.
    'status_access_assignment' => 'ACT', // Current assignment lifecycle or approval status.
    'effective_at' => '2026-07-21T04:08:58Z', // UTC time when access begins.
    'expires_at' => null, // Optional UTC expiry time.
    'assigned_by_user_id' => 'U9mC5qL2pR7vX4kT', // Authorized user that created the assignment.
    'assignment_reason' => 'Approved editorial publishing responsibilities.', // Human-readable assignment reason.
    'revoked_at' => null, // UTC revocation time when revoked.
    'created_at' => '2026-07-21T04:08:58Z', // UTC record creation time.
    'updated_at' => '2026-07-21T04:08:58Z', // UTC record update time.
];

$access_assignment_field_order = [
    '_id',
    'user_id',
    'role_workspace',
    'permission_code_list',
    'scope_access',
    'scope_record_id',
    'status_access_assignment',
    'effective_at',
    'expires_at',
    'assigned_by_user_id',
    'assignment_reason',
    'revoked_at',
    'created_at',
    'updated_at',
];

$access_assignment_embedded_structure = [];

$access_assignment_field_property = [
    '_id' => ['field_label' => 'Access Assignment ID', 'field_description' => 'Canonical application-generated 16-character identifier.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'user_id' => ['field_label' => 'User ID', 'field_description' => 'user_main._id receiving the assignment.', 'type_data' => 'S', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'role_workspace' => ['field_label' => 'Workspace Role', 'field_description' => 'Optional role bundle from workspace access taxonomy.', 'type_data' => 'S'],
    'permission_code_list' => ['field_label' => 'Permission Code List', 'field_description' => 'Stable application permission codes granted directly.', 'type_data' => 'A'],
    'scope_access' => ['field_label' => 'Access Scope', 'field_description' => 'Global or record-bound assignment scope.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'scope_record_id' => ['field_label' => 'Scope Record ID', 'field_description' => 'Target identifier required for a non-global scope.', 'type_data' => 'S', 'is_relational' => true, 'is_indexed' => true],
    'status_access_assignment' => ['field_label' => 'Assignment Status', 'field_description' => 'Current assignment approval and lifecycle state.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'effective_at' => ['field_label' => 'Effective At', 'field_description' => 'UTC time when the assignment begins granting access.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'expires_at' => ['field_label' => 'Expires At', 'field_description' => 'Optional UTC time when the assignment stops granting access.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_indexed' => true],
    'assigned_by_user_id' => ['field_label' => 'Assigned By User ID', 'field_description' => 'user_main._id of the assigning actor.', 'type_data' => 'S', 'is_mandatory' => true, 'is_relational' => true],
    'assignment_reason' => ['field_label' => 'Assignment Reason', 'field_description' => 'Required explanation for granting access.', 'type_data' => 'S', 'max_character' => 1000, 'is_mandatory' => true],
    'revoked_at' => ['field_label' => 'Revoked At', 'field_description' => 'UTC time when the assignment was revoked.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC record update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];

$access_assignment_subfield_property = [];

$access_assignment_index_list = [
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

$access_assignment_boundary = [
    'owns' => ['assignment scope, role, direct permissions, effective window, status, and audit fields'],
    'references' => ['user_main through user_id and assigned_by_user_id', 'a scoped target through scope_record_id'],
    'does_not_own' => ['personal user capabilities', 'establishment ownership or management facts', 'application authorization implementation'],
];

return [
    'access_assignment_default' => $access_assignment_default,
    'access_assignment' => $access_assignment,
    'access_assignment_field_order' => $access_assignment_field_order,
    'access_assignment_embedded_structure' => $access_assignment_embedded_structure,
    'access_assignment_field_property' => $access_assignment_field_property,
    'access_assignment_subfield_property' => $access_assignment_subfield_property,
    'access_assignment_index_list' => $access_assignment_index_list,
    'access_assignment_boundary' => $access_assignment_boundary,
];
