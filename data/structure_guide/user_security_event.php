<?php
/**
 * Title: Massage Nexus User Security Event Structure Guide
 * Version: 1.00
 * Collection: user_security_event
 * Description: Stores an append-only account-security event safe for user review and audit.
 * Purpose: Supports security history, alerts, anomaly review, and incident response.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_security_event_default = ['severity_security_event' => 'INF', 'result_security_event' => 'SUC'];
$user_security_event = ['_id' => 'Se7K2pQ9xR4tV8zN', 'user_id' => 'U2pR7vX4kT9mC5qL', 'user_session_id' => 'Us7K2pQ9xR4tV8zN', 'user_device_id' => 'Ud7K2pQ9xR4tV8zN', 'type_security_event' => 'LGN', 'severity_security_event' => 'INF', 'result_security_event' => 'SUC', 'ip_address_masked' => '203.0.113.xxx', 'location_summary' => 'Metro Manila, PH', 'user_agent_summary' => 'Chrome on Windows', 'event_metadata' => [], 'occurred_at' => '2026-07-22T02:51:15Z', 'created_at' => '2026-07-22T02:51:15Z'];
$user_security_event_field_order = ['_id', 'user_id', 'user_session_id', 'user_device_id', 'type_security_event', 'severity_security_event', 'result_security_event', 'ip_address_masked', 'location_summary', 'user_agent_summary', 'event_metadata', 'occurred_at', 'created_at'];
$user_security_event_embedded_structure = ['event_metadata' => ['example' => 'value']];
$user_security_event_field_property = [
    '_id' => ['field_label' => 'User Security Event ID', 'field_description' => 'Canonical application-generated security-event identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'Affected user_main account.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'user_session_id' => ['field_label' => 'User Session', 'field_description' => 'Related session when applicable.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'user_device_id' => ['field_label' => 'User Device', 'field_description' => 'Related recognized-device summary when applicable.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'type_security_event' => ['field_label' => 'Security Event Type', 'field_description' => 'Controlled category of account-security activity.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
    'severity_security_event' => ['field_label' => 'Security Event Severity', 'field_description' => 'Controlled informational, warning, or critical severity.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'INF', 'is_mandatory' => true],
    'result_security_event' => ['field_label' => 'Security Event Result', 'field_description' => 'Controlled success, failure, challenge, or blocked result.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'SUC', 'is_mandatory' => true],
    'ip_address_masked' => ['field_label' => 'Masked IP Address', 'field_description' => 'Privacy-reduced network address for user recognition.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_sensitive' => true],
    'location_summary' => ['field_label' => 'Location Summary', 'field_description' => 'Coarse inferred location for security recognition only.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 120, 'is_sensitive' => true],
    'user_agent_summary' => ['field_label' => 'User Agent Summary', 'field_description' => 'Safe coarse client presentation.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 160],
    'event_metadata' => ['field_label' => 'Event Metadata', 'field_description' => 'Allowlisted event-specific facts excluding secrets and raw sensitive payloads.', 'type_data' => 'O', 'type_field' => 'JSE'],
    'occurred_at' => ['field_label' => 'Occurred At', 'field_description' => 'UTC event occurrence time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true, 'is_indexed' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC immutable record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
];
$user_security_event_subfield_property = ['event_metadata.*' => ['field_label' => 'Event Metadata Value', 'field_description' => 'Allowlisted scalar security-event metadata value.', 'type_data' => 'S', 'type_field' => 'HDN']];
$user_security_event_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'user_time', 'index_name' => 'ix_user_security_event_user_time', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'occurred_at', 'type_index_mode' => 'DSC', 'sort_order' => 20]], 'sort_order' => 20]];
$user_security_event_boundary = ['owns' => ['append-only user-visible security-event facts'], 'reference_field_list' => ['user_id', 'user_session_id', 'user_device_id'], 'does_not_own' => ['raw secrets', 'authorization policy', 'session lifecycle']];
return ['user_security_event_default' => $user_security_event_default, 'user_security_event' => $user_security_event, 'user_security_event_field_order' => $user_security_event_field_order, 'user_security_event_embedded_structure' => $user_security_event_embedded_structure, 'user_security_event_field_property' => $user_security_event_field_property, 'user_security_event_subfield_property' => $user_security_event_subfield_property, 'user_security_event_index_list' => $user_security_event_index_list, 'user_security_event_boundary' => $user_security_event_boundary];
