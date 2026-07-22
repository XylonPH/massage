<?php
/**
 * Title: Massage Nexus User Notification Structure Guide
 * Version: 1.00
 * Collection: user_notification
 * Description: Stores one in-product notification for a user.
 * Purpose: Supports durable inbox state independently from email, push, or SMS delivery logs.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_notification_default = ['status_user_notification' => 'UNR', 'revision_number' => 1];
$user_notification = ['_id' => 'Un7K2pQ9xR4tV8zN', 'user_id' => 'U2pR7vX4kT9mC5qL', 'type_user_notification' => 'CON', 'title_key' => 'notification.contribution_reviewed', 'message_parameter' => ['user_contribution_id' => 'Uc2pR7vX4kT9mC5q'], 'target_url' => '/workspace/contributions/Uc2pR7vX4kT9mC5q', 'status_user_notification' => 'UNR', 'read_at' => null, 'expires_at' => null, 'revision_number' => 1, 'created_at' => '2026-07-22T02:51:15Z', 'updated_at' => '2026-07-22T02:51:15Z'];
$user_notification_field_order = ['_id', 'user_id', 'type_user_notification', 'title_key', 'message_parameter', 'target_url', 'status_user_notification', 'read_at', 'expires_at', 'revision_number', 'created_at', 'updated_at'];
$user_notification_embedded_structure = ['message_parameter' => ['user_contribution_id' => 'Uc2pR7vX4kT9mC5q']];
$user_notification_field_property = [
    '_id' => ['field_label' => 'User Notification ID', 'field_description' => 'Canonical application-generated notification identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'Recipient user_main account.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'type_user_notification' => ['field_label' => 'User Notification Type', 'field_description' => 'Controlled notification category used for preference filtering.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
    'title_key' => ['field_label' => 'Title Translation Key', 'field_description' => 'Translation key rendered in the user language.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true],
    'message_parameter' => ['field_label' => 'Message Parameters', 'field_description' => 'Allowlisted scalar interpolation parameters, excluding sensitive data.', 'type_data' => 'O', 'type_field' => 'JSE'],
    'target_url' => ['field_label' => 'Target URL', 'field_description' => 'Validated internal destination for notification action.', 'type_data' => 'S', 'type_field' => 'URL', 'max_character' => 500],
    'status_user_notification' => ['field_label' => 'User Notification Status', 'field_description' => 'Unread, read, archived, or expired inbox state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'UNR', 'is_mandatory' => true, 'is_indexed' => true],
    'read_at' => ['field_label' => 'Read At', 'field_description' => 'UTC first-read time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'expires_at' => ['field_label' => 'Expires At', 'field_description' => 'Optional UTC inbox expiry time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC notification creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true, 'is_indexed' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest inbox-state update.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$user_notification_subfield_property = ['message_parameter.*' => ['field_label' => 'Message Parameter', 'field_description' => 'Allowlisted scalar translation parameter.', 'type_data' => 'S', 'type_field' => 'HDN']];
$user_notification_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'user_status_time', 'index_name' => 'ix_user_notification_user_status_time', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_user_notification', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'created_at', 'type_index_mode' => 'DSC', 'sort_order' => 30]], 'sort_order' => 20]];
$user_notification_boundary = ['owns' => ['in-product inbox notification and read state'], 'reference_field_list' => ['user_id'], 'does_not_own' => ['email, push, or SMS delivery log', 'target record', 'translation text']];
return ['user_notification_default' => $user_notification_default, 'user_notification' => $user_notification, 'user_notification_field_order' => $user_notification_field_order, 'user_notification_embedded_structure' => $user_notification_embedded_structure, 'user_notification_field_property' => $user_notification_field_property, 'user_notification_subfield_property' => $user_notification_subfield_property, 'user_notification_index_list' => $user_notification_index_list, 'user_notification_boundary' => $user_notification_boundary];
