<?php
/**
 * Title: Massage Nexus User External Identity Structure Guide
 * Version: 1.00
 * Collection: user_external_identity
 * Description: Stores one stable external authentication-provider identity linked to a user account.
 * Purpose: Documents secure account linking and unlinking without treating provider email text or public social presentation as identity authority.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_external_identity_default = ['status_external_identity' => 'PND', 'revision_number' => 1];
$user_external_identity = ['_id' => 'Ei7K2pQ9xR4tV8zN', 'user_id' => 'U2pR7vX4kT9mC5qL', 'provider_external_identity' => 'GOO', 'provider_subject' => 'provider-stable-subject', 'provider_email_snapshot' => 'wellnessfan7@example.test', 'credential_secret_reference' => 'vault://external-identity/example', 'scope_list' => ['openid', 'email'], 'status_external_identity' => 'ACT', 'linked_at' => '2026-07-22T02:51:15Z', 'last_used_at' => '2026-07-22T02:51:15Z', 'unlinked_at' => null, 'revision_number' => 1, 'created_at' => '2026-07-22T02:51:15Z', 'updated_at' => '2026-07-22T02:51:15Z'];
$user_external_identity_field_order = ['_id', 'user_id', 'provider_external_identity', 'provider_subject', 'provider_email_snapshot', 'credential_secret_reference', 'scope_list', 'status_external_identity', 'linked_at', 'last_used_at', 'unlinked_at', 'revision_number', 'created_at', 'updated_at'];
$user_external_identity_embedded_structure = [];
$user_external_identity_field_property = [
    '_id' => ['field_label' => 'User External Identity ID', 'field_description' => 'Canonical application-generated 16-character identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'Linked user_main account.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'provider_external_identity' => ['field_label' => 'External Identity Provider', 'field_description' => 'Approved external authentication provider.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true, 'is_indexed' => true],
    'provider_subject' => ['field_label' => 'Provider Subject', 'field_description' => 'Stable provider-issued subject identifier; email is not a substitute.', 'type_data' => 'S', 'type_field' => 'HDN', 'max_character' => 255, 'is_mandatory' => true, 'is_indexed' => true],
    'provider_email_snapshot' => ['field_label' => 'Provider Email Snapshot', 'field_description' => 'Private informational email returned at the latest approved provider interaction.', 'type_data' => 'S', 'type_field' => 'EML', 'max_character' => 255, 'visibility_scope' => 'PRV'],
    'credential_secret_reference' => ['field_label' => 'Credential Secret Reference', 'field_description' => 'Restricted approved secret-store reference; never a profile or log value.', 'type_data' => 'S', 'type_field' => 'HDN', 'max_character' => 500, 'visibility_scope' => 'PRV'],
    'scope_list' => ['field_label' => 'Authorized Scopes', 'field_description' => 'Provider scopes granted to the connection.', 'type_data' => 'A', 'type_field' => 'TAG', 'visibility_scope' => 'PRV'],
    'status_external_identity' => ['field_label' => 'External Identity Status', 'field_description' => 'Current linking and lifecycle state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'PND', 'is_mandatory' => true, 'is_indexed' => true],
    'linked_at' => ['field_label' => 'Linked At', 'field_description' => 'UTC time the identity link became active.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_used_at' => ['field_label' => 'Last Used At', 'field_description' => 'UTC time the identity last authenticated or refreshed.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'unlinked_at' => ['field_label' => 'Unlinked At', 'field_description' => 'UTC time the user or authorized recovery flow disconnected the identity.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest accepted update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$user_external_identity_subfield_property = [];
$user_external_identity_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'provider_subject_unique', 'index_name' => 'uq_user_external_identity_provider_subject', 'type_index' => 'CMP', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'provider_external_identity', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'provider_subject', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20], ['index_key' => 'user_status', 'index_name' => 'ix_user_external_identity_user_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_external_identity', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 30]];
$user_external_identity_boundary = ['owns' => ['stable provider identity link, approved scopes, secret reference, and lifecycle'], 'reference_field_list' => ['user_id'], 'does_not_own' => ['public social-profile presentation', 'provider account', 'secret material', 'Massage Nexus membership']];
return ['user_external_identity_default' => $user_external_identity_default, 'user_external_identity' => $user_external_identity, 'user_external_identity_field_order' => $user_external_identity_field_order, 'user_external_identity_embedded_structure' => $user_external_identity_embedded_structure, 'user_external_identity_field_property' => $user_external_identity_field_property, 'user_external_identity_subfield_property' => $user_external_identity_subfield_property, 'user_external_identity_index_list' => $user_external_identity_index_list, 'user_external_identity_boundary' => $user_external_identity_boundary];
