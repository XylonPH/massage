<?php
/**
 * Title: Massage Nexus User Entitlement Structure Guide
 * Version: 1.00
 * Collection: user_entitlement
 * Description: Stores one effective commercial feature entitlement for a user.
 * Purpose: Supports subscription-derived or promotional capabilities separately from authorization roles.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_entitlement_default = ['entitlement_usage' => 0, 'interval_entitlement_reset' => 'NON', 'status_user_entitlement' => 'ACT', 'revision_number' => 1];
$user_entitlement = ['_id' => 'Ue7K2pQ9xR4tV8zN', 'user_id' => 'U2pR7vX4kT9mC5qL', 'user_subscription_id' => 'UsbK2pQ9xR4tV8zN', 'entitlement_code' => 'profile.premium_label', 'source_entitlement' => 'SUB', 'entitlement_limit' => null, 'entitlement_usage' => 0, 'interval_entitlement_reset' => 'NON', 'usage_resets_at' => null, 'status_user_entitlement' => 'ACT', 'effective_at' => '2026-07-22T02:51:15Z', 'expires_at' => '2026-08-22T02:51:15Z', 'revoked_at' => null, 'revision_number' => 1, 'created_at' => '2026-07-22T02:51:15Z', 'updated_at' => '2026-07-22T02:51:15Z'];
$user_entitlement_field_order = ['_id', 'user_id', 'user_subscription_id', 'entitlement_code', 'source_entitlement', 'entitlement_limit', 'entitlement_usage', 'interval_entitlement_reset', 'usage_resets_at', 'status_user_entitlement', 'effective_at', 'expires_at', 'revoked_at', 'revision_number', 'created_at', 'updated_at'];
$user_entitlement_embedded_structure = [];
$user_entitlement_field_property = [
    '_id' => ['field_label' => 'User Entitlement ID', 'field_description' => 'Canonical application-generated entitlement identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'user_main account receiving the feature entitlement.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'user_subscription_id' => ['field_label' => 'User Subscription', 'field_description' => 'Source subscription when the entitlement is subscription-derived.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'entitlement_code' => ['field_label' => 'Entitlement Code', 'field_description' => 'Stable commercial feature capability code.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 3, 'max_character' => 120, 'is_mandatory' => true, 'is_indexed' => true],
    'source_entitlement' => ['field_label' => 'Entitlement Source', 'field_description' => 'Subscription, promotion, grant, or migration source.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
    'entitlement_limit' => ['field_label' => 'Entitlement Limit', 'field_description' => 'Optional maximum uses or units within each reset interval.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'entitlement_usage' => ['field_label' => 'Entitlement Usage', 'field_description' => 'Current consumed uses or units in the active reset interval.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0, 'default_value' => 0],
    'interval_entitlement_reset' => ['field_label' => 'Entitlement Reset Interval', 'field_description' => 'No reset, daily, monthly, or annual usage-reset interval.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'NON', 'is_mandatory' => true],
    'usage_resets_at' => ['field_label' => 'Usage Resets At', 'field_description' => 'Optional UTC next usage-counter reset time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'status_user_entitlement' => ['field_label' => 'User Entitlement Status', 'field_description' => 'Active, expired, or revoked effective state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT', 'is_mandatory' => true, 'is_indexed' => true],
    'effective_at' => ['field_label' => 'Effective At', 'field_description' => 'UTC entitlement start time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'expires_at' => ['field_label' => 'Expires At', 'field_description' => 'Optional UTC entitlement expiry.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revoked_at' => ['field_label' => 'Revoked At', 'field_description' => 'UTC manual or system revocation time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest accepted update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$user_entitlement_subfield_property = [];
$user_entitlement_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'user_code_status', 'index_name' => 'ix_user_entitlement_user_code_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'entitlement_code', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_user_entitlement', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20]];
$user_entitlement_boundary = ['owns' => ['effective commercial feature entitlement lifecycle'], 'reference_field_list' => ['user_id', 'user_subscription_id'], 'does_not_own' => ['workspace permission', 'role', 'rank', 'reputation', 'payment transaction']];
return ['user_entitlement_default' => $user_entitlement_default, 'user_entitlement' => $user_entitlement, 'user_entitlement_field_order' => $user_entitlement_field_order, 'user_entitlement_embedded_structure' => $user_entitlement_embedded_structure, 'user_entitlement_field_property' => $user_entitlement_field_property, 'user_entitlement_subfield_property' => $user_entitlement_subfield_property, 'user_entitlement_index_list' => $user_entitlement_index_list, 'user_entitlement_boundary' => $user_entitlement_boundary];
