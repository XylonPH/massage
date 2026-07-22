<?php
/**
 * Title: Massage Nexus User Address Structure Guide
 * Version: 1.00
 * Collection: user_address
 * Description: Stores one labeled private personal address that a user may select for an approved booking or home-service workflow.
 * Purpose: Documents multiple-address ownership, protected exact details, confirmation, default use, and lifecycle without creating a public practitioner or establishment address.
 */

$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_address_default = ['geographic_area_id_list' => [], 'is_default_booking' => false, 'status_address_verification' => 'UNV', 'status_record_lifecycle' => 'ACT', 'revision_number' => 1];
$user_address = [
    '_id' => 'Ad7K2pQ9xR4tV8zN', // Canonical address identifier.
    'user_id' => 'U2pR7vX4kT9mC5qL', // Owning user.
    'address_label' => 'Home', // Private label.
    'recipient_name' => 'Wellness Fan', // Booking recipient name.
    'country_id' => 608, // Country reference.
    'geographic_area_id_list' => [1376], // Broad-to-specific area references.
    'street_address' => '123 Sample Street', // Encrypted exact street address.
    'building_name' => 'Sample Residences', // Encrypted building name.
    'floor_label' => '5th Floor', // Encrypted floor label.
    'unit_label' => 'Unit 5A', // Encrypted unit label.
    'postal_code' => '1550', // Protected postal code.
    'address_access_instruction' => 'Call from the lobby before entering.', // Encrypted private access instruction.
    'is_default_booking' => true, // Default selectable booking address.
    'status_address_verification' => 'SLF', // Address verification level.
    'last_confirmed_at' => '2026-07-22T02:51:15Z', // User confirmation time.
    'effective_until' => null, // Optional retirement time.
    'status_record_lifecycle' => 'ACT', // Record lifecycle.
    'revision_number' => 1, // Optimistic-concurrency token.
    'created_at' => '2026-07-22T02:51:15Z', // UTC record creation time.
    'updated_at' => '2026-07-22T02:51:15Z', // UTC latest update time.
];
$user_address_field_order = ['_id', 'user_id', 'address_label', 'recipient_name', 'country_id', 'geographic_area_id_list', 'street_address', 'building_name', 'floor_label', 'unit_label', 'postal_code', 'address_access_instruction', 'is_default_booking', 'status_address_verification', 'last_confirmed_at', 'effective_until', 'status_record_lifecycle', 'revision_number', 'created_at', 'updated_at'];
$user_address_embedded_structure = [];
$user_address_field_property = [
    '_id' => ['field_label' => 'User Address ID', 'field_description' => 'Canonical application-generated 16-character identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'user_main._id that owns the private address.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'address_label' => ['field_label' => 'Address Label', 'field_description' => 'Short private user label such as Home, Work, or Hotel.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 1, 'max_character' => 40, 'is_mandatory' => true],
    'recipient_name' => ['field_label' => 'Recipient Name', 'field_description' => 'Private recipient name copied only into an approved booking snapshot.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 100, 'visibility_scope' => 'PRV'],
    'country_id' => ['field_label' => 'Country', 'field_description' => 'common_reference country identifier.', 'type_data' => 'I', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true],
    'geographic_area_id_list' => ['field_label' => 'Geographic Areas', 'field_description' => 'Ordered broad-to-specific geographic-area references.', 'type_data' => 'A', 'type_field' => 'TAG', 'default_value' => [], 'is_relational' => true],
    'street_address' => ['field_label' => 'Street Address', 'field_description' => 'Encrypted private street-level address.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 255, 'visibility_scope' => 'PRV'],
    'building_name' => ['field_label' => 'Building Name', 'field_description' => 'Encrypted private building or property name.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 120, 'visibility_scope' => 'PRV'],
    'floor_label' => ['field_label' => 'Floor', 'field_description' => 'Encrypted private floor label.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 40, 'visibility_scope' => 'PRV'],
    'unit_label' => ['field_label' => 'Unit', 'field_description' => 'Encrypted private unit label.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 40, 'visibility_scope' => 'PRV'],
    'postal_code' => ['field_label' => 'Postal Code', 'field_description' => 'Protected postal or ZIP code.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 20, 'visibility_scope' => 'PRV'],
    'address_access_instruction' => ['field_label' => 'Address Access Instruction', 'field_description' => 'Encrypted private arrival, entry, landmark, or access instruction.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 500, 'visibility_scope' => 'PRV'],
    'is_default_booking' => ['field_label' => 'Default Booking Address', 'field_description' => 'Whether this is the default address offered for explicit booking selection.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false, 'is_indexed' => true],
    'status_address_verification' => ['field_label' => 'Address Verification Status', 'field_description' => 'Current confirmation or verification level for the address.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'UNV', 'is_mandatory' => true, 'is_indexed' => true],
    'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'UTC time the user last confirmed the current address.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'effective_until' => ['field_label' => 'Effective Until', 'field_description' => 'Optional UTC time the address ceased being current.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Current record lifecycle state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT', 'is_mandatory' => true],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest accepted update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$user_address_subfield_property = [];
$user_address_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'user_status', 'index_name' => 'ix_user_address_user_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_address_verification', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20],
    ['index_key' => 'user_default', 'index_name' => 'ix_user_address_user_default', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'is_default_booking', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 30],
];
$user_address_boundary = ['owns' => ['private reusable address, confirmation, default selection, protected access instructions, and lifecycle'], 'reference_field_list' => ['user_id', 'country_id', 'geographic_area_id_list'], 'does_not_own' => ['public practice location', 'professional service area', 'booking address snapshot', 'provider disclosure decision']];
return ['user_address_default' => $user_address_default, 'user_address' => $user_address, 'user_address_field_order' => $user_address_field_order, 'user_address_embedded_structure' => $user_address_embedded_structure, 'user_address_field_property' => $user_address_field_property, 'user_address_subfield_property' => $user_address_subfield_property, 'user_address_index_list' => $user_address_index_list, 'user_address_boundary' => $user_address_boundary];
