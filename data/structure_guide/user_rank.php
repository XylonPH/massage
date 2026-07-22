<?php
/**
 * Title: Massage Nexus User Rank Structure Guide
 * Version: 1.00
 * Collection: user_rank
 * Description: Stores one calculated user-rank interval on a governed rank path.
 * Purpose: Preserves rank history while user_main carries only a rebuildable current summary.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_rank_default = ['status_user_rank' => 'ACT'];
$user_rank = ['_id' => 'Ur7K2pQ9xR4tV8zN', 'user_id' => 'U2pR7vX4kT9mC5qL', 'rank_path_main_id' => 'Rp7K2pQ9xR4tV8zN', 'rank_code' => 'NEW', 'qualifying_points' => 25, 'calculation_key' => 'U2pR7vX4kT9mC5qL-community-contributor-20260722T025115Z', 'calculation_version' => '1.0', 'status_user_rank' => 'ACT', 'effective_at' => '2026-07-22T02:51:15Z', 'ended_at' => null, 'created_at' => '2026-07-22T02:51:15Z'];
$user_rank_field_order = ['_id', 'user_id', 'rank_path_main_id', 'rank_code', 'qualifying_points', 'calculation_key', 'calculation_version', 'status_user_rank', 'effective_at', 'ended_at', 'created_at'];
$user_rank_embedded_structure = [];
$user_rank_field_property = [
    '_id' => ['field_label' => 'User Rank ID', 'field_description' => 'Canonical application-generated rank-history identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'Ranked user_main account.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'rank_path_main_id' => ['field_label' => 'Rank Path', 'field_description' => 'Governed rank_path_main definition used for calculation.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'rank_code' => ['field_label' => 'Rank Code', 'field_description' => 'Resulting level code from the referenced rank path.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_mandatory' => true],
    'qualifying_points' => ['field_label' => 'Qualifying Points', 'field_description' => 'Point balance used for this deterministic calculation.', 'type_data' => 'I', 'type_field' => 'NMB', 'is_mandatory' => true],
    'calculation_key' => ['field_label' => 'Calculation Key', 'field_description' => 'Unique deterministic key preventing duplicate rank-calculation intervals.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'calculation_version' => ['field_label' => 'Calculation Version', 'field_description' => 'Version of the rank calculation rules.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_mandatory' => true],
    'status_user_rank' => ['field_label' => 'User Rank Status', 'field_description' => 'Active, superseded, disputed, or void rank interval.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT', 'is_mandatory' => true, 'is_indexed' => true],
    'effective_at' => ['field_label' => 'Effective At', 'field_description' => 'UTC start of the rank interval.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'ended_at' => ['field_label' => 'Ended At', 'field_description' => 'UTC end of the rank interval when superseded.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC immutable calculation-record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
];
$user_rank_subfield_property = [];
$user_rank_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'calculation_key_unique', 'index_name' => 'uq_user_rank_calculation_key', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'calculation_key', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 20], ['index_key' => 'user_path_status', 'index_name' => 'ix_user_rank_user_path_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'rank_path_main_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_user_rank', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 30]];
$user_rank_boundary = ['owns' => ['immutable calculated rank interval and history'], 'reference_field_list' => ['user_id', 'rank_path_main_id'], 'does_not_own' => ['rank definition', 'reward events', 'workspace role', 'premium membership']];
return ['user_rank_default' => $user_rank_default, 'user_rank' => $user_rank, 'user_rank_field_order' => $user_rank_field_order, 'user_rank_embedded_structure' => $user_rank_embedded_structure, 'user_rank_field_property' => $user_rank_field_property, 'user_rank_subfield_property' => $user_rank_subfield_property, 'user_rank_index_list' => $user_rank_index_list, 'user_rank_boundary' => $user_rank_boundary];
