<?php
/**
 * Title: Massage Nexus User Reputation Structure Guide
 * Version: 1.00
 * Collection: user_reputation
 * Description: Stores one immutable governed reputation adjustment or recalculation event.
 * Purpose: Preserves explainable reputation provenance while user_main carries a rebuildable summary.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_reputation_default = ['status_user_reputation' => 'ACT'];
$user_reputation = ['_id' => 'UrepK2pQ9xR4tV8zN', 'user_id' => 'U2pR7vX4kT9mC5qL', 'type_reputation_event' => 'VCF', 'reputation_delta' => 2.5, 'reputation_after' => 3652.5, 'source_collection' => 'user_contribution', 'source_record_id' => 'Uc2pR7vX4kT9mC5q', 'reason_code' => 'contribution.verified', 'idempotency_key' => 'contribution-Uc2pR7vX4kT9mC5q-reputation', 'calculation_version' => '1.0', 'status_user_reputation' => 'ACT', 'occurred_at' => '2026-07-22T02:51:15Z', 'created_at' => '2026-07-22T02:51:15Z'];
$user_reputation_field_order = ['_id', 'user_id', 'type_reputation_event', 'reputation_delta', 'reputation_after', 'source_collection', 'source_record_id', 'reason_code', 'idempotency_key', 'calculation_version', 'status_user_reputation', 'occurred_at', 'created_at'];
$user_reputation_embedded_structure = [];
$user_reputation_field_property = [
    '_id' => ['field_label' => 'User Reputation ID', 'field_description' => 'Canonical application-generated reputation-event identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'Affected user_main account.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'type_reputation_event' => ['field_label' => 'Reputation Event Type', 'field_description' => 'Controlled positive, negative, correction, or recalculation category.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
    'reputation_delta' => ['field_label' => 'Reputation Delta', 'field_description' => 'Signed change applied by the event.', 'type_data' => 'F', 'type_field' => 'NMB', 'is_mandatory' => true],
    'reputation_after' => ['field_label' => 'Reputation After', 'field_description' => 'Resulting bounded reputation score from 0 to 10,000.', 'type_data' => 'F', 'type_field' => 'NMB', 'min_number' => 0, 'max_number' => 10000, 'is_mandatory' => true],
    'source_collection' => ['field_label' => 'Source Collection', 'field_description' => 'Allowlisted provenance collection.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'source_record_id' => ['field_label' => 'Source Record', 'field_description' => 'Optional provenance record identifier.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'reason_code' => ['field_label' => 'Reason Code', 'field_description' => 'Stable explainable rule or moderation reason.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_mandatory' => true],
    'idempotency_key' => ['field_label' => 'Idempotency Key', 'field_description' => 'Unique business-event key preventing duplicate reputation effects.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'calculation_version' => ['field_label' => 'Calculation Version', 'field_description' => 'Version of the reputation calculation rules.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_mandatory' => true],
    'status_user_reputation' => ['field_label' => 'User Reputation Status', 'field_description' => 'Active, reversed, disputed, or void event state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT', 'is_mandatory' => true],
    'occurred_at' => ['field_label' => 'Occurred At', 'field_description' => 'UTC effective event time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true, 'is_indexed' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC immutable event-record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
];
$user_reputation_subfield_property = [];
$user_reputation_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'idempotency_unique', 'index_name' => 'uq_user_reputation_idempotency_key', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'idempotency_key', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 20], ['index_key' => 'user_time', 'index_name' => 'ix_user_reputation_user_time', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'occurred_at', 'type_index_mode' => 'DSC', 'sort_order' => 20]], 'sort_order' => 30]];
$user_reputation_boundary = ['owns' => ['immutable reputation adjustment and provenance'], 'reference_field_list' => ['user_id', 'source_record_id'], 'does_not_own' => ['rating average', 'reward points', 'rank', 'workspace role']];
return ['user_reputation_default' => $user_reputation_default, 'user_reputation' => $user_reputation, 'user_reputation_field_order' => $user_reputation_field_order, 'user_reputation_embedded_structure' => $user_reputation_embedded_structure, 'user_reputation_field_property' => $user_reputation_field_property, 'user_reputation_subfield_property' => $user_reputation_subfield_property, 'user_reputation_index_list' => $user_reputation_index_list, 'user_reputation_boundary' => $user_reputation_boundary];
