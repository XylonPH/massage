<?php
/**
 * Title: Massage Nexus User Session Structure Guide
 * Version: 1.00
 * Collection: user_session
 * Description: Stores one active or historical authenticated user session without readable reusable session secrets.
 * Purpose: Supports session review, expiry, revocation, remote logout, and safe device context.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_session_default = ['status_user_session' => 'ACT'];
$user_session = ['_id' => 'Us7K2pQ9xR4tV8zN', 'user_id' => 'U2pR7vX4kT9mC5qL', 'user_device_id' => 'Ud7K2pQ9xR4tV8zN', 'session_secret_hash' => 'sha256:example-only', 'type_authentication_method' => 'PWD', 'ip_address' => '203.0.113.10', 'user_agent_summary' => 'Chrome on Windows', 'approximate_location' => 'Metro Manila, Philippines', 'status_user_session' => 'ACT', 'authenticated_at' => '2026-07-22T02:40:00Z', 'last_activity_at' => '2026-07-22T02:51:15Z', 'expires_at' => '2026-08-21T02:40:00Z', 'revoked_at' => null, 'revoked_by_user_id' => null, 'created_at' => '2026-07-22T02:40:00Z'];
$user_session_field_order = ['_id', 'user_id', 'user_device_id', 'session_secret_hash', 'type_authentication_method', 'ip_address', 'user_agent_summary', 'approximate_location', 'status_user_session', 'authenticated_at', 'last_activity_at', 'expires_at', 'revoked_at', 'revoked_by_user_id', 'created_at'];
$user_session_embedded_structure = [];
$user_session_field_property = [
    '_id' => ['field_label' => 'User Session ID', 'field_description' => 'Canonical application-generated session identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'Authenticated user_main account.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'user_device_id' => ['field_label' => 'User Device', 'field_description' => 'Optional recognized user_device reference.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'session_secret_hash' => ['field_label' => 'Session Secret Hash', 'field_description' => 'One-way verifier for the session secret; never the reusable secret.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'visibility_scope' => 'PRV'],
    'type_authentication_method' => ['field_label' => 'Authentication Method', 'field_description' => 'Primary method used to establish the session.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'ip_address' => ['field_label' => 'IP Address', 'field_description' => 'Protected source IP retained according to security policy.', 'type_data' => 'S', 'type_field' => 'HDN', 'visibility_scope' => 'PRV'],
    'user_agent_summary' => ['field_label' => 'User Agent Summary', 'field_description' => 'Safe browser and platform summary for user review.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 255],
    'approximate_location' => ['field_label' => 'Approximate Location', 'field_description' => 'Coarse security-review location that is not treated as precise tracking.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 120, 'visibility_scope' => 'PRV'],
    'status_user_session' => ['field_label' => 'User Session Status', 'field_description' => 'Active, expired, revoked, or invalidated session state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT', 'is_mandatory' => true, 'is_indexed' => true],
    'authenticated_at' => ['field_label' => 'Authenticated At', 'field_description' => 'UTC session authentication time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_activity_at' => ['field_label' => 'Last Activity At', 'field_description' => 'UTC latest accepted activity time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'expires_at' => ['field_label' => 'Expires At', 'field_description' => 'UTC absolute session expiry time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_indexed' => true],
    'revoked_at' => ['field_label' => 'Revoked At', 'field_description' => 'UTC explicit revocation time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revoked_by_user_id' => ['field_label' => 'Revoked By User', 'field_description' => 'User or authorized support actor that revoked the session.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
];
$user_session_subfield_property = [];
$user_session_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'user_status', 'index_name' => 'ix_user_session_user_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_user_session', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20], ['index_key' => 'expiry', 'index_name' => 'ix_user_session_expiry', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'expires_at', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 30]];
$user_session_boundary = ['owns' => ['session verifier, authentication context, activity, expiry, and revocation'], 'reference_field_list' => ['user_id', 'user_device_id', 'revoked_by_user_id'], 'does_not_own' => ['raw reusable session secret', 'device identity', 'security event history']];
return ['user_session_default' => $user_session_default, 'user_session' => $user_session, 'user_session_field_order' => $user_session_field_order, 'user_session_embedded_structure' => $user_session_embedded_structure, 'user_session_field_property' => $user_session_field_property, 'user_session_subfield_property' => $user_session_subfield_property, 'user_session_index_list' => $user_session_index_list, 'user_session_boundary' => $user_session_boundary];
