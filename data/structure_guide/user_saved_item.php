<?php
/**
 * Title: Massage Nexus User Saved Item Structure Guide
 * Version: 1.00
 * Collection: user_saved_item
 * Description: Stores one private user bookmark for a supported public record.
 * Purpose: Supports saved articles, practitioners, establishments, reviews, and other approved targets.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_saved_item_default = ['status_user_saved_item' => 'ACT', 'revision_number' => 1];
$user_saved_item = ['_id' => 'Si7K2pQ9xR4tV8zN', 'user_id' => 'U2pR7vX4kT9mC5qL', 'target_collection' => 'article_main', 'target_record_id' => 'Ar2pR7vX4kT9mC5q', 'folder_label' => 'Read later', 'private_note' => null, 'status_user_saved_item' => 'ACT', 'revision_number' => 1, 'created_at' => '2026-07-22T02:51:15Z', 'updated_at' => '2026-07-22T02:51:15Z'];
$user_saved_item_field_order = ['_id', 'user_id', 'target_collection', 'target_record_id', 'folder_label', 'private_note', 'status_user_saved_item', 'revision_number', 'created_at', 'updated_at'];
$user_saved_item_embedded_structure = [];
$user_saved_item_field_property = [
    '_id' => ['field_label' => 'User Saved Item ID', 'field_description' => 'Canonical application-generated bookmark identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'user_main account that privately saved the target.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'target_collection' => ['field_label' => 'Target Collection', 'field_description' => 'Allowlisted collection containing the saved record.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true, 'is_indexed' => true],
    'target_record_id' => ['field_label' => 'Target Record', 'field_description' => 'Identifier of the saved record in target_collection.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'folder_label' => ['field_label' => 'Folder Label', 'field_description' => 'Optional private grouping label.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 60],
    'private_note' => ['field_label' => 'Private Note', 'field_description' => 'Optional private note never shown publicly.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 500, 'is_sensitive' => true],
    'status_user_saved_item' => ['field_label' => 'User Saved Item Status', 'field_description' => 'Active or removed bookmark lifecycle.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT', 'is_mandatory' => true],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC bookmark creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest accepted update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$user_saved_item_subfield_property = [];
$user_saved_item_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'user_target_unique', 'index_name' => 'uq_user_saved_item_user_target', 'type_index' => 'CMP', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'target_collection', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'target_record_id', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20]];
$user_saved_item_boundary = ['owns' => ['private bookmark metadata'], 'reference_field_list' => ['user_id', 'target_record_id'], 'does_not_own' => ['target record', 'public follow relationship', 'notification']];
return ['user_saved_item_default' => $user_saved_item_default, 'user_saved_item' => $user_saved_item, 'user_saved_item_field_order' => $user_saved_item_field_order, 'user_saved_item_embedded_structure' => $user_saved_item_embedded_structure, 'user_saved_item_field_property' => $user_saved_item_field_property, 'user_saved_item_subfield_property' => $user_saved_item_subfield_property, 'user_saved_item_index_list' => $user_saved_item_index_list, 'user_saved_item_boundary' => $user_saved_item_boundary];
