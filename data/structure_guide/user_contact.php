<?php
/**
 * Title: Massage Nexus User Contact Structure Guide
 * Version: 1.00
 * Collection: user_contact
 * Description: Stores one current or historical private personal contact channel for a user.
 * Purpose: Documents verified personal contact, primary-use, replacement, and booking-reference behavior without exposing professional or public contact information.
 */

$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_contact_default = ['is_primary_login' => false, 'is_primary_booking' => false, 'status_contact_verification' => 'UNV', 'status_record_lifecycle' => 'ACT', 'revision_number' => 1];
$user_contact = [
    '_id' => 'Ct7K2pQ9xR4tV8zN', // Canonical contact identifier.
    'user_id' => 'U2pR7vX4kT9mC5qL', // Owning user.
    'type_contact_channel' => 'EML', // Email, phone, SMS, or approved messaging channel.
    'type_contact_number' => null, // Telephone subtype when applicable.
    'contact_label' => 'Personal', // User-facing private label.
    'contact_value_display' => 'wellnessfan7@example.test', // Encrypted display value.
    'contact_value_normalized' => 'wellnessfan7@example.test', // Protected normalized comparison value.
    'is_primary_login' => true, // Primary authentication contact.
    'is_primary_booking' => true, // Default booking contact.
    'status_contact_verification' => 'VER', // Control-verification state.
    'verified_at' => '2026-07-22T02:51:15Z', // UTC verification time.
    'effective_from' => '2026-07-22T02:51:15Z', // UTC activation time.
    'effective_until' => null, // UTC retirement time.
    'replaced_by_user_contact_id' => null, // Replacement contact reference.
    'status_record_lifecycle' => 'ACT', // Record lifecycle.
    'revision_number' => 1, // Optimistic-concurrency token.
    'created_at' => '2026-07-22T02:51:15Z', // UTC record creation time.
    'updated_at' => '2026-07-22T02:51:15Z', // UTC latest update time.
];
$user_contact_field_order = ['_id', 'user_id', 'type_contact_channel', 'type_contact_number', 'contact_label', 'contact_value_display', 'contact_value_normalized', 'is_primary_login', 'is_primary_booking', 'status_contact_verification', 'verified_at', 'effective_from', 'effective_until', 'replaced_by_user_contact_id', 'status_record_lifecycle', 'revision_number', 'created_at', 'updated_at'];
$user_contact_embedded_structure = [];
$user_contact_field_property = [
    '_id' => ['field_label' => 'User Contact ID', 'field_description' => 'Canonical application-generated 16-character identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'user_main._id that owns the private contact.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'type_contact_channel' => ['field_label' => 'Contact Channel Type', 'field_description' => 'Controlled communication medium.', 'type_data' => 'S', 'type_field' => 'DDL', 'taxonomy_field_name' => 'type_contact_channel', 'is_mandatory' => true, 'is_indexed' => true],
    'type_contact_number' => ['field_label' => 'Contact Number Type', 'field_description' => 'Telephone technology subtype when applicable.', 'type_data' => 'S', 'type_field' => 'DDL', 'taxonomy_field_name' => 'type_contact_number'],
    'contact_label' => ['field_label' => 'Contact Label', 'field_description' => 'Short private purpose label.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 40],
    'contact_value_display' => ['field_label' => 'Display Contact Value', 'field_description' => 'Encrypted human-readable personal contact value.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 255, 'is_mandatory' => true, 'visibility_scope' => 'PRV'],
    'contact_value_normalized' => ['field_label' => 'Normalized Contact Value', 'field_description' => 'Protected normalized value used for comparison and controlled authentication lookup.', 'type_data' => 'S', 'type_field' => 'HDN', 'max_character' => 255, 'is_mandatory' => true, 'is_indexed' => true, 'visibility_scope' => 'PRV'],
    'is_primary_login' => ['field_label' => 'Primary Login Contact', 'field_description' => 'Whether this is the current primary authentication contact.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false, 'is_indexed' => true],
    'is_primary_booking' => ['field_label' => 'Primary Booking Contact', 'field_description' => 'Whether this is the default verified booking contact.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false],
    'status_contact_verification' => ['field_label' => 'Contact Verification Status', 'field_description' => 'Current proof-of-control state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'UNV', 'is_mandatory' => true, 'is_indexed' => true],
    'verified_at' => ['field_label' => 'Verified At', 'field_description' => 'UTC time proof of control completed.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'effective_from' => ['field_label' => 'Effective From', 'field_description' => 'UTC time the contact became current.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'effective_until' => ['field_label' => 'Effective Until', 'field_description' => 'UTC time the contact ceased being current.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'replaced_by_user_contact_id' => ['field_label' => 'Replaced By Contact', 'field_description' => 'Replacement user_contact identifier.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Current record lifecycle state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT', 'is_mandatory' => true],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest accepted update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$user_contact_subfield_property = [];
$user_contact_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'user_channel_status', 'index_name' => 'ix_user_contact_user_channel_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'type_contact_channel', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_contact_verification', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
    ['index_key' => 'normalized_lookup', 'index_name' => 'ix_user_contact_normalized', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'contact_value_normalized', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 30],
    ['index_key' => 'primary_login', 'index_name' => 'ix_user_contact_primary_login', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'is_primary_login', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 40],
];
$user_contact_boundary = ['owns' => ['private personal contact value, verification, primary use, replacement, and history'], 'reference_field_list' => ['user_id', 'replaced_by_user_contact_id'], 'does_not_own' => ['public professional contact', 'booking contact snapshot', 'verification challenge secret', 'account identity']];
return ['user_contact_default' => $user_contact_default, 'user_contact' => $user_contact, 'user_contact_field_order' => $user_contact_field_order, 'user_contact_embedded_structure' => $user_contact_embedded_structure, 'user_contact_field_property' => $user_contact_field_property, 'user_contact_subfield_property' => $user_contact_subfield_property, 'user_contact_index_list' => $user_contact_index_list, 'user_contact_boundary' => $user_contact_boundary];
