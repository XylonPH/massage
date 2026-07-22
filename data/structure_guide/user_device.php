<?php
/**
 * Title: Massage Nexus User Device Structure Guide
 * Version: 1.00
 * Collection: user_device
 * Description: Stores one user-recognized device summary without invasive fingerprinting.
 * Purpose: Supports device naming, recognition, distrust, security review, and session grouping.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_device_default = ['is_recognized' => false, 'status_user_device' => 'ACT', 'revision_number' => 1];
$user_device = ['_id' => 'Ud7K2pQ9xR4tV8zN', 'user_id' => 'U2pR7vX4kT9mC5qL', 'device_name' => 'Personal laptop', 'platform_summary' => 'Windows', 'browser_summary' => 'Chrome', 'is_recognized' => true, 'status_user_device' => 'ACT', 'first_seen_at' => '2026-07-20T02:51:15Z', 'last_seen_at' => '2026-07-22T02:51:15Z', 'distrusted_at' => null, 'revision_number' => 1, 'created_at' => '2026-07-20T02:51:15Z', 'updated_at' => '2026-07-22T02:51:15Z'];
$user_device_field_order = ['_id', 'user_id', 'device_name', 'platform_summary', 'browser_summary', 'is_recognized', 'status_user_device', 'first_seen_at', 'last_seen_at', 'distrusted_at', 'revision_number', 'created_at', 'updated_at'];
$user_device_embedded_structure = [];
$user_device_field_property = [
    '_id' => ['field_label' => 'User Device ID', 'field_description' => 'Canonical application-generated device-summary identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'user_main account that recognizes the device.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'device_name' => ['field_label' => 'Device Name', 'field_description' => 'User-editable private device label.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 1, 'max_character' => 80],
    'platform_summary' => ['field_label' => 'Platform Summary', 'field_description' => 'Safe coarse operating-system or platform presentation.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 80],
    'browser_summary' => ['field_label' => 'Browser Summary', 'field_description' => 'Safe coarse browser or application presentation.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 80],
    'is_recognized' => ['field_label' => 'Recognized', 'field_description' => 'Whether the user currently recognizes the device.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false],
    'status_user_device' => ['field_label' => 'User Device Status', 'field_description' => 'Active, distrusted, or retired device-summary state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT', 'is_mandatory' => true, 'is_indexed' => true],
    'first_seen_at' => ['field_label' => 'First Seen At', 'field_description' => 'UTC first accepted device observation.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_seen_at' => ['field_label' => 'Last Seen At', 'field_description' => 'UTC latest accepted device observation.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'distrusted_at' => ['field_label' => 'Distrusted At', 'field_description' => 'UTC time the user marked the device unrecognized or unsafe.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest accepted update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$user_device_subfield_property = [];
$user_device_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'user_status', 'index_name' => 'ix_user_device_user_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_user_device', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20]];
$user_device_boundary = ['owns' => ['user-recognized device presentation and trust lifecycle'], 'reference_field_list' => ['user_id'], 'does_not_own' => ['invasive fingerprint', 'session secret', 'passkey', 'security-event history']];
return ['user_device_default' => $user_device_default, 'user_device' => $user_device, 'user_device_field_order' => $user_device_field_order, 'user_device_embedded_structure' => $user_device_embedded_structure, 'user_device_field_property' => $user_device_field_property, 'user_device_subfield_property' => $user_device_subfield_property, 'user_device_index_list' => $user_device_index_list, 'user_device_boundary' => $user_device_boundary];
