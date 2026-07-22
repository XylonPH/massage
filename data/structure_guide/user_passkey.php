<?php
/**
 * Title: Massage Nexus User Passkey Structure Guide
 * Version: 1.00
 * Collection: user_passkey
 * Description: Stores WebAuthn public credential metadata for one user passkey.
 * Purpose: Supports phishing-resistant authentication and credential revocation.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_passkey_default = ['status_user_passkey' => 'ACT', 'signature_counter' => 0, 'revision_number' => 1];
$user_passkey = ['_id' => 'Pk7K2pQ9xR4tV8zN', 'user_id' => 'U2pR7vX4kT9mC5qL', 'credential_id' => 'base64url-credential', 'public_key' => 'base64url-public-key', 'passkey_label' => 'Phone passkey', 'aaguid' => null, 'transport_list' => ['internal'], 'signature_counter' => 0, 'status_user_passkey' => 'ACT', 'last_used_at' => null, 'revoked_at' => null, 'revision_number' => 1, 'created_at' => '2026-07-22T02:51:15Z', 'updated_at' => '2026-07-22T02:51:15Z'];
$user_passkey_field_order = ['_id', 'user_id', 'credential_id', 'public_key', 'passkey_label', 'aaguid', 'transport_list', 'signature_counter', 'status_user_passkey', 'last_used_at', 'revoked_at', 'revision_number', 'created_at', 'updated_at'];
$user_passkey_embedded_structure = [];
$user_passkey_field_property = [
    '_id' => ['field_label' => 'User Passkey ID', 'field_description' => 'Canonical application-generated passkey identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'user_main account that owns the credential.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'credential_id' => ['field_label' => 'Credential ID', 'field_description' => 'WebAuthn credential identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true, 'is_sensitive' => true],
    'public_key' => ['field_label' => 'Public Key', 'field_description' => 'WebAuthn public credential key; no private key is stored.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true],
    'passkey_label' => ['field_label' => 'Passkey Label', 'field_description' => 'Private user-facing credential label.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 1, 'max_character' => 80],
    'aaguid' => ['field_label' => 'Authenticator AAGUID', 'field_description' => 'Optional authenticator model identifier.', 'type_data' => 'S', 'type_field' => 'HDN'],
    'transport_list' => ['field_label' => 'Transport List', 'field_description' => 'WebAuthn transports reported for credential use.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'signature_counter' => ['field_label' => 'Signature Counter', 'field_description' => 'Latest accepted authenticator signature counter.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0, 'default_value' => 0],
    'status_user_passkey' => ['field_label' => 'User Passkey Status', 'field_description' => 'Active, suspended, or revoked credential state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT', 'is_mandatory' => true, 'is_indexed' => true],
    'last_used_at' => ['field_label' => 'Last Used At', 'field_description' => 'UTC latest successful authentication.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revoked_at' => ['field_label' => 'Revoked At', 'field_description' => 'UTC credential revocation time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest accepted update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$user_passkey_subfield_property = [];
$user_passkey_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'credential_unique', 'index_name' => 'uq_user_passkey_credential_id', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'credential_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 20], ['index_key' => 'user_status', 'index_name' => 'ix_user_passkey_user_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_user_passkey', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 30]];
$user_passkey_boundary = ['owns' => ['WebAuthn public credential metadata and lifecycle'], 'reference_field_list' => ['user_id'], 'does_not_own' => ['private authenticator key', 'MFA recovery code', 'session']];
return ['user_passkey_default' => $user_passkey_default, 'user_passkey' => $user_passkey, 'user_passkey_field_order' => $user_passkey_field_order, 'user_passkey_embedded_structure' => $user_passkey_embedded_structure, 'user_passkey_field_property' => $user_passkey_field_property, 'user_passkey_subfield_property' => $user_passkey_subfield_property, 'user_passkey_index_list' => $user_passkey_index_list, 'user_passkey_boundary' => $user_passkey_boundary];
