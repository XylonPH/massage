<?php
/**
 * Title: Massage Nexus User MFA Method Structure Guide
 * Version: 1.00
 * Collection: user_mfa_method
 * Description: Stores one enrolled multi-factor authentication method without recoverable secrets.
 * Purpose: Supports challenge selection, verification, disabling, and security review.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_mfa_method_default = ['is_primary' => false, 'status_user_mfa_method' => 'PND', 'revision_number' => 1];
$user_mfa_method = ['_id' => 'Mf7K2pQ9xR4tV8zN', 'user_id' => 'U2pR7vX4kT9mC5qL', 'type_mfa_method' => 'TOTP', 'method_label' => 'Authenticator app', 'secret_reference' => 'vault://mfa/example', 'is_primary' => true, 'status_user_mfa_method' => 'ACT', 'verified_at' => '2026-07-22T02:51:15Z', 'last_used_at' => null, 'disabled_at' => null, 'revision_number' => 1, 'created_at' => '2026-07-22T02:51:15Z', 'updated_at' => '2026-07-22T02:51:15Z'];
$user_mfa_method_field_order = ['_id', 'user_id', 'type_mfa_method', 'method_label', 'secret_reference', 'is_primary', 'status_user_mfa_method', 'verified_at', 'last_used_at', 'disabled_at', 'revision_number', 'created_at', 'updated_at'];
$user_mfa_method_embedded_structure = [];
$user_mfa_method_field_property = [
    '_id' => ['field_label' => 'User MFA Method ID', 'field_description' => 'Canonical application-generated MFA-method identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'user_main account that owns the enrollment.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'type_mfa_method' => ['field_label' => 'MFA Method Type', 'field_description' => 'Controlled enrolled factor type.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
    'method_label' => ['field_label' => 'Method Label', 'field_description' => 'Private user-facing method label.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 1, 'max_character' => 80],
    'secret_reference' => ['field_label' => 'Secret Reference', 'field_description' => 'Opaque reference to encrypted secret material outside the collection.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_sensitive' => true],
    'is_primary' => ['field_label' => 'Primary Method', 'field_description' => 'Whether this is the preferred MFA method.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false],
    'status_user_mfa_method' => ['field_label' => 'User MFA Method Status', 'field_description' => 'Pending, active, or disabled enrollment lifecycle.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'PND', 'is_mandatory' => true, 'is_indexed' => true],
    'verified_at' => ['field_label' => 'Verified At', 'field_description' => 'UTC successful enrollment verification time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_used_at' => ['field_label' => 'Last Used At', 'field_description' => 'UTC latest accepted challenge time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'disabled_at' => ['field_label' => 'Disabled At', 'field_description' => 'UTC enrollment disablement time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest accepted update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$user_mfa_method_subfield_property = [];
$user_mfa_method_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'user_status', 'index_name' => 'ix_user_mfa_method_user_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_user_mfa_method', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20]];
$user_mfa_method_boundary = ['owns' => ['MFA enrollment metadata and lifecycle'], 'reference_field_list' => ['user_id'], 'does_not_own' => ['recoverable authentication secret', 'recovery code', 'passkey', 'session']];
return ['user_mfa_method_default' => $user_mfa_method_default, 'user_mfa_method' => $user_mfa_method, 'user_mfa_method_field_order' => $user_mfa_method_field_order, 'user_mfa_method_embedded_structure' => $user_mfa_method_embedded_structure, 'user_mfa_method_field_property' => $user_mfa_method_field_property, 'user_mfa_method_subfield_property' => $user_mfa_method_subfield_property, 'user_mfa_method_index_list' => $user_mfa_method_index_list, 'user_mfa_method_boundary' => $user_mfa_method_boundary];
