<?php
/**
 * Title: Massage Nexus User Reward Structure Guide
 * Version: 1.00
 * Collection: user_reward
 * Description: Stores one append-only points or reward-value event for a user.
 * Purpose: Preserves auditable earning, spending, expiry, correction, and reversal history.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_reward_default = ['status_user_reward' => 'ACT'];
$user_reward = ['_id' => 'UrwK2pQ9xR4tV8zN', 'user_id' => 'U2pR7vX4kT9mC5qL', 'type_reward_event' => 'ERN', 'reward_unit' => 'NXP', 'reward_delta' => 25, 'balance_after' => 125, 'source_collection' => 'user_contribution', 'source_record_id' => 'Uc2pR7vX4kT9mC5q', 'reason_code' => 'contribution.approved', 'idempotency_key' => 'contribution-Uc2pR7vX4kT9mC5q-approved', 'reversal_of_user_reward_id' => null, 'status_user_reward' => 'ACT', 'occurred_at' => '2026-07-22T02:51:15Z', 'expires_at' => null, 'created_at' => '2026-07-22T02:51:15Z'];
$user_reward_field_order = ['_id', 'user_id', 'type_reward_event', 'reward_unit', 'reward_delta', 'balance_after', 'source_collection', 'source_record_id', 'reason_code', 'idempotency_key', 'reversal_of_user_reward_id', 'status_user_reward', 'occurred_at', 'expires_at', 'created_at'];
$user_reward_embedded_structure = [];
$user_reward_field_property = [
    '_id' => ['field_label' => 'User Reward ID', 'field_description' => 'Canonical application-generated reward-event identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'Affected user_main account.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'type_reward_event' => ['field_label' => 'Reward Event Type', 'field_description' => 'Earn, spend, expire, correction, reversal, or migration category.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
    'reward_unit' => ['field_label' => 'Reward Unit', 'field_description' => 'Controlled unit such as non-cash community points.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
    'reward_delta' => ['field_label' => 'Reward Delta', 'field_description' => 'Signed amount applied by this immutable event.', 'type_data' => 'I', 'type_field' => 'NMB', 'is_mandatory' => true],
    'balance_after' => ['field_label' => 'Balance After', 'field_description' => 'Resulting unit balance used for reconciliation.', 'type_data' => 'I', 'type_field' => 'NMB', 'is_mandatory' => true],
    'source_collection' => ['field_label' => 'Source Collection', 'field_description' => 'Allowlisted provenance collection.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'source_record_id' => ['field_label' => 'Source Record', 'field_description' => 'Optional provenance record identifier.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'reason_code' => ['field_label' => 'Reason Code', 'field_description' => 'Stable explainable reward rule or adjustment reason.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_mandatory' => true],
    'idempotency_key' => ['field_label' => 'Idempotency Key', 'field_description' => 'Unique business-event key preventing duplicate rewards.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'reversal_of_user_reward_id' => ['field_label' => 'Reversal Of User Reward', 'field_description' => 'Original user_reward event reversed by this event.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'status_user_reward' => ['field_label' => 'User Reward Status', 'field_description' => 'Active, reversed, disputed, or void event state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT', 'is_mandatory' => true],
    'occurred_at' => ['field_label' => 'Occurred At', 'field_description' => 'UTC effective event time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true, 'is_indexed' => true],
    'expires_at' => ['field_label' => 'Expires At', 'field_description' => 'Optional UTC time at which an expiring grant requires a corresponding expiry event.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC immutable event-record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
];
$user_reward_subfield_property = [];
$user_reward_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'idempotency_unique', 'index_name' => 'uq_user_reward_idempotency_key', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'idempotency_key', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 20], ['index_key' => 'user_time', 'index_name' => 'ix_user_reward_user_time', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'occurred_at', 'type_index_mode' => 'DSC', 'sort_order' => 20]], 'sort_order' => 30]];
$user_reward_boundary = ['owns' => ['append-only points and reward-value event history'], 'reference_field_list' => ['user_id', 'source_record_id', 'reversal_of_user_reward_id'], 'does_not_own' => ['cash balance', 'payment transaction', 'rank definition', 'reputation', 'workspace role']];
return ['user_reward_default' => $user_reward_default, 'user_reward' => $user_reward, 'user_reward_field_order' => $user_reward_field_order, 'user_reward_embedded_structure' => $user_reward_embedded_structure, 'user_reward_field_property' => $user_reward_field_property, 'user_reward_subfield_property' => $user_reward_subfield_property, 'user_reward_index_list' => $user_reward_index_list, 'user_reward_boundary' => $user_reward_boundary];
