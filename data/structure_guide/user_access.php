<?php
/**
 * Title: Massage Nexus User Access Structure Guide
 * Version: 1.20
 * Collection: user_access
 * Description: Stores one additive global or scoped role or direct-permission grant for a user.
 * Purpose: Documents the accepted user_access record shape for authorization, public-role presentation, review, and migration planning without acting as runtime code or a migration.
 *
 * Notes:
 * - Runtime authorization uses user_access exclusively. The retired access_assignment collection was removed after every legacy grant was verified in user_access.
 * - Fields holding empty lists or inapplicable optional values are omitted from stored documents and resolved through sparse runtime defaults where needed.
 * - Factual ownership, employment, management, investment, and representation do not belong here.
 * - Authorization evaluates effective user_access records; user_main access_summary is presentation-only.
 */

$created_at = '2026-07-20T10:31:38Z';
$updated_at = '2026-07-22T07:55:39Z';

$user_access_default = [
    'scope_access' => 'GBL',
    'status_user_access' => 'PND',
    'is_role_public' => false,
    'public_role_order' => 0,
    'revision_number' => 1,
];

$user_access = [
    '_id' => 'Ua7K2pQ9xR4tV8zN', // Canonical 16-character access-grant identifier.
    'user_id' => 'U2pR7vX4kT9mC5qL', // User receiving the access grant.
    'role_workspace' => 'FND', // Optional workspace role bundle.
    'permission_code_list' => ['article.schedule'], // Direct permission codes added by the grant.
    'scope_access' => 'GBL', // Global or supported record scope.
    'status_user_access' => 'ACT', // Current grant lifecycle state.
    'effective_at' => '2026-07-22T02:51:15Z', // UTC time when access begins.
    'granted_by_user_id' => 'U9mC5qL2pR7vX4kT', // Authorized grantor.
    'grant_reason' => 'Approved project decision responsibility.', // Required grant explanation.
    'is_role_public' => true, // Whether this role is eligible for public presentation.
    'public_role_order' => 10, // Order among eligible public role labels.
    'revision_number' => 1, // Optimistic-concurrency token.
    'created_at' => '2026-07-22T02:51:15Z', // UTC record creation time.
    'updated_at' => '2026-07-22T02:51:15Z', // UTC latest accepted update time.
];

$user_access_field_order = [
    '_id', 'user_id', 'role_workspace', 'permission_code_list', 'scope_access', 'scope_record_id',
    'status_user_access', 'effective_at', 'expires_at', 'granted_by_user_id', 'grant_reason',
    'is_role_public', 'public_role_order', 'revoked_at', 'revoked_by_user_id', 'revocation_reason',
    'revision_number', 'created_at', 'updated_at',
];

$user_access_embedded_structure = [];

$user_access_field_property = [
    '_id' => ['field_label' => 'User Access ID', 'field_description' => 'Canonical application-generated 16-character identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'user_main._id receiving the access grant.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'role_workspace' => ['field_label' => 'Workspace Role', 'field_description' => 'Optional role bundle from the workspace-access taxonomy.', 'type_data' => 'S', 'type_field' => 'DDL', 'taxonomy_field_name' => 'role_workspace', 'is_indexed' => true],
    'permission_code_list' => ['field_label' => 'Permission Codes', 'field_description' => 'Optional stable namespaced direct permissions added by this grant; omitted when no direct permissions apply.', 'type_data' => 'A', 'type_field' => 'TAG', 'taxonomy_field_name' => 'permission_code_list'],
    'scope_access' => ['field_label' => 'Access Scope', 'field_description' => 'Global or record-bound grant scope.', 'type_data' => 'S', 'type_field' => 'DDL', 'taxonomy_field_name' => 'scope_access', 'default_value' => 'GBL', 'is_mandatory' => true, 'is_indexed' => true],
    'scope_record_id' => ['field_label' => 'Scope Record', 'field_description' => 'Target record identifier required for a non-global scope.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_indexed' => true],
    'status_user_access' => ['field_label' => 'User Access Status', 'field_description' => 'Current grant lifecycle and approval state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'PND', 'is_mandatory' => true, 'is_indexed' => true],
    'effective_at' => ['field_label' => 'Effective At', 'field_description' => 'UTC time when the grant begins authorizing actions.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_indexed' => true],
    'expires_at' => ['field_label' => 'Expires At', 'field_description' => 'Optional UTC time when the grant stops authorizing actions.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_indexed' => true],
    'granted_by_user_id' => ['field_label' => 'Granted By User', 'field_description' => 'Authorized user_main._id that approved the grant.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true],
    'grant_reason' => ['field_label' => 'Grant Reason', 'field_description' => 'Required human-readable reason for granting access.', 'type_data' => 'S', 'type_field' => 'TXA', 'min_character' => 10, 'max_character' => 1000, 'is_mandatory' => true],
    'is_role_public' => ['field_label' => 'Public Role', 'field_description' => 'Whether an eligible role may appear on the public user profile.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false],
    'public_role_order' => ['field_label' => 'Public Role Order', 'field_description' => 'Presentation order among this user’s eligible public roles.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0, 'max_number' => 1000, 'default_value' => 0],
    'revoked_at' => ['field_label' => 'Revoked At', 'field_description' => 'UTC time when the grant was revoked.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revoked_by_user_id' => ['field_label' => 'Revoked By User', 'field_description' => 'Authorized user_main._id that revoked the grant.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'revocation_reason' => ['field_label' => 'Revocation Reason', 'field_description' => 'Required reason when the grant is revoked.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 1000],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC time when the grant record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC time of the latest accepted record update.', 'type_data' => 'S', 'type_field' => 'DTS'],
];

$user_access_subfield_property = [];

$user_access_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'user_status_scope', 'index_name' => 'ix_user_access_user_status_scope', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_user_access', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'scope_access', 'type_index_mode' => 'ASC', 'sort_order' => 30], ['field_name' => 'scope_record_id', 'type_index_mode' => 'ASC', 'sort_order' => 40]], 'sort_order' => 20],
    ['index_key' => 'effective_expiry', 'index_name' => 'ix_user_access_effective_expiry', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => true, 'index_field_list' => [['field_name' => 'effective_at', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'expires_at', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 30],
];

$user_access_boundary = [
    'owns' => ['global or scoped role and direct-permission grant, effective window, lifecycle, public-role eligibility, and grant audit metadata'],
    'reference_field_list' => ['user_id', 'scope_record_id', 'granted_by_user_id', 'revoked_by_user_id'],
    'does_not_own' => ['account identity', 'factual personal or business relationship', 'claim workflow', 'public profile summary', 'runtime permission implementation'],
];

return [
    'user_access_default' => $user_access_default,
    'user_access' => $user_access,
    'user_access_field_order' => $user_access_field_order,
    'user_access_embedded_structure' => $user_access_embedded_structure,
    'user_access_field_property' => $user_access_field_property,
    'user_access_subfield_property' => $user_access_subfield_property,
    'user_access_index_list' => $user_access_index_list,
    'user_access_boundary' => $user_access_boundary,
];
