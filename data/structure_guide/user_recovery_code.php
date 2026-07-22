<?php
/**
 * Title: Massage Nexus User Recovery Code Structure Guide
 * Version: 1.00
 * Collection: user_recovery_code
 * Description: Stores one single-use hashed account-recovery code.
 * Purpose: Permits auditable recovery without storing plaintext codes.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_recovery_code_default = ['status_user_recovery_code' => 'ACT'];
$user_recovery_code = ['_id' => 'Rc7K2pQ9xR4tV8zN', 'user_id' => 'U2pR7vX4kT9mC5qL', 'code_hash' => '$argon2id$example', 'code_set_id' => 'Rcs4tV8zN7K2pQ9x', 'status_user_recovery_code' => 'ACT', 'used_at' => null, 'expires_at' => null, 'created_at' => '2026-07-22T02:51:15Z'];
$user_recovery_code_field_order = ['_id', 'user_id', 'code_hash', 'code_set_id', 'status_user_recovery_code', 'used_at', 'expires_at', 'created_at'];
$user_recovery_code_embedded_structure = [];
$user_recovery_code_field_property = [
    '_id' => ['field_label' => 'User Recovery Code ID', 'field_description' => 'Canonical application-generated recovery-code identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'user_main account that owns the recovery code.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'code_hash' => ['field_label' => 'Code Hash', 'field_description' => 'One-way hash of the recovery code; plaintext is never stored.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_sensitive' => true],
    'code_set_id' => ['field_label' => 'Code Set ID', 'field_description' => 'Identifier grouping codes issued in one replacement set.', 'type_data' => 'S', 'type_field' => 'HDN'],
    'status_user_recovery_code' => ['field_label' => 'User Recovery Code Status', 'field_description' => 'Active, used, expired, or revoked single-use state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT', 'is_mandatory' => true, 'is_indexed' => true],
    'used_at' => ['field_label' => 'Used At', 'field_description' => 'UTC successful one-time use.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'expires_at' => ['field_label' => 'Expires At', 'field_description' => 'Optional UTC invalidation time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC code creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
];
$user_recovery_code_subfield_property = [];
$user_recovery_code_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'user_status', 'index_name' => 'ix_user_recovery_code_user_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_user_recovery_code', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20]];
$user_recovery_code_boundary = ['owns' => ['hashed single-use recovery-code lifecycle'], 'reference_field_list' => ['user_id'], 'does_not_own' => ['plaintext recovery code', 'MFA method', 'passkey', 'session']];
return ['user_recovery_code_default' => $user_recovery_code_default, 'user_recovery_code' => $user_recovery_code, 'user_recovery_code_field_order' => $user_recovery_code_field_order, 'user_recovery_code_embedded_structure' => $user_recovery_code_embedded_structure, 'user_recovery_code_field_property' => $user_recovery_code_field_property, 'user_recovery_code_subfield_property' => $user_recovery_code_subfield_property, 'user_recovery_code_index_list' => $user_recovery_code_index_list, 'user_recovery_code_boundary' => $user_recovery_code_boundary];
