<?php
/**
 * Title: Massage Nexus User Follow Structure Guide
 * Version: 1.00
 * Collection: user_follow
 * Description: Stores one user follow relationship to an allowlisted target.
 * Purpose: Supports opt-in feeds and notifications without storing follower arrays on profiles.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_follow_default = ['is_notification_enabled' => true, 'status_user_follow' => 'ACT', 'revision_number' => 1];
$user_follow = ['_id' => 'Uf7K2pQ9xR4tV8zN', 'follower_user_id' => 'U2pR7vX4kT9mC5qL', 'target_collection' => 'user_main', 'target_record_id' => 'U8zN7K2pQ9xR4tV', 'is_notification_enabled' => true, 'status_user_follow' => 'ACT', 'revision_number' => 1, 'created_at' => '2026-07-22T02:51:15Z', 'updated_at' => '2026-07-22T02:51:15Z'];
$user_follow_field_order = ['_id', 'follower_user_id', 'target_collection', 'target_record_id', 'is_notification_enabled', 'status_user_follow', 'revision_number', 'created_at', 'updated_at'];
$user_follow_embedded_structure = [];
$user_follow_field_property = [
    '_id' => ['field_label' => 'User Follow ID', 'field_description' => 'Canonical application-generated follow identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'follower_user_id' => ['field_label' => 'Follower User', 'field_description' => 'user_main account that created the follow.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'target_collection' => ['field_label' => 'Target Collection', 'field_description' => 'Allowlisted collection containing the followed target.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true, 'is_indexed' => true],
    'target_record_id' => ['field_label' => 'Target Record', 'field_description' => 'Identifier of the followed target.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'is_notification_enabled' => ['field_label' => 'Notifications Enabled', 'field_description' => 'Whether eligible target activity may create notifications.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => true],
    'status_user_follow' => ['field_label' => 'User Follow Status', 'field_description' => 'Active, muted, blocked, or ended follow lifecycle.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT', 'is_mandatory' => true],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC follow creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest accepted update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$user_follow_subfield_property = [];
$user_follow_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'follower_target_unique', 'index_name' => 'uq_user_follow_follower_target', 'type_index' => 'CMP', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'follower_user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'target_collection', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'target_record_id', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20]];
$user_follow_boundary = ['owns' => ['follow lifecycle and per-follow notification choice'], 'reference_field_list' => ['follower_user_id', 'target_record_id'], 'does_not_own' => ['target record', 'notification delivery', 'blocked-user policy']];
return ['user_follow_default' => $user_follow_default, 'user_follow' => $user_follow, 'user_follow_field_order' => $user_follow_field_order, 'user_follow_embedded_structure' => $user_follow_embedded_structure, 'user_follow_field_property' => $user_follow_field_property, 'user_follow_subfield_property' => $user_follow_subfield_property, 'user_follow_index_list' => $user_follow_index_list, 'user_follow_boundary' => $user_follow_boundary];
