<?php
/**
 * Title: Massage Nexus User Subscription Structure Guide
 * Version: 1.00
 * Collection: user_subscription
 * Description: Stores one user's commercial subscription lifecycle.
 * Purpose: Supports personal Premium and future user plans without treating payment as authority or trust.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_subscription_default = ['status_user_subscription' => 'PND', 'will_cancel_at_period_end' => false, 'revision_number' => 1];
$user_subscription = ['_id' => 'UsbK2pQ9xR4tV8zN', 'user_id' => 'U2pR7vX4kT9mC5qL', 'subscription_plan_id' => 'Sp7K2pQ9xR4tV8zN', 'provider_subscription_reference' => 'sub_external_token', 'status_user_subscription' => 'ACT', 'current_period_started_at' => '2026-07-22T02:51:15Z', 'current_period_ends_at' => '2026-08-22T02:51:15Z', 'will_cancel_at_period_end' => false, 'cancelled_at' => null, 'ended_at' => null, 'revision_number' => 1, 'created_at' => '2026-07-22T02:51:15Z', 'updated_at' => '2026-07-22T02:51:15Z'];
$user_subscription_field_order = ['_id', 'user_id', 'subscription_plan_id', 'provider_subscription_reference', 'status_user_subscription', 'current_period_started_at', 'current_period_ends_at', 'will_cancel_at_period_end', 'cancelled_at', 'ended_at', 'revision_number', 'created_at', 'updated_at'];
$user_subscription_embedded_structure = [];
$user_subscription_field_property = [
    '_id' => ['field_label' => 'User Subscription ID', 'field_description' => 'Canonical application-generated user-subscription identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'user_main subscriber.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'subscription_plan_id' => ['field_label' => 'Subscription Plan', 'field_description' => 'Purchased subscription_plan version.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true],
    'provider_subscription_reference' => ['field_label' => 'Provider Subscription Reference', 'field_description' => 'Opaque payment-provider subscription reference, never a credential.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_sensitive' => true],
    'status_user_subscription' => ['field_label' => 'User Subscription Status', 'field_description' => 'Pending, trial, active, past due, paused, cancelled, or ended lifecycle.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'PND', 'is_mandatory' => true, 'is_indexed' => true],
    'current_period_started_at' => ['field_label' => 'Current Period Started At', 'field_description' => 'UTC current billing or entitlement period start.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'current_period_ends_at' => ['field_label' => 'Current Period Ends At', 'field_description' => 'UTC current billing or entitlement period end.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'will_cancel_at_period_end' => ['field_label' => 'Cancel At Period End', 'field_description' => 'Whether non-renewal is scheduled at the current period end.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false],
    'cancelled_at' => ['field_label' => 'Cancelled At', 'field_description' => 'UTC cancellation request or immediate cancellation time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'ended_at' => ['field_label' => 'Ended At', 'field_description' => 'UTC entitlement-bearing subscription end.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest accepted lifecycle update.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$user_subscription_subfield_property = [];
$user_subscription_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'user_status', 'index_name' => 'ix_user_subscription_user_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_user_subscription', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20]];
$user_subscription_boundary = ['owns' => ['user commercial subscription lifecycle'], 'reference_field_list' => ['user_id', 'subscription_plan_id'], 'does_not_own' => ['payment credentials', 'transaction ledger', 'user authority', 'rank', 'reputation', 'derived entitlements']];
return ['user_subscription_default' => $user_subscription_default, 'user_subscription' => $user_subscription, 'user_subscription_field_order' => $user_subscription_field_order, 'user_subscription_embedded_structure' => $user_subscription_embedded_structure, 'user_subscription_field_property' => $user_subscription_field_property, 'user_subscription_subfield_property' => $user_subscription_subfield_property, 'user_subscription_index_list' => $user_subscription_index_list, 'user_subscription_boundary' => $user_subscription_boundary];
