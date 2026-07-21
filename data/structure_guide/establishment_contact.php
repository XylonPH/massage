<?php
/**
 * Title: Massage Nexus Establishment Contact Structure Guide
 * Version: 1.00
 * Collection: establishment_contact
 * Description: Stores one current or historical official contact channel for an establishment.
 * Purpose: Separates independently verified and historied business contact channels from establishment_main and from research-source URLs.
 */
$created_at = '2026-07-21T08:15:45Z';
$updated_at = '2026-07-21T08:15:45Z';
$establishment_contact_default = ['platform_code' => null, 'username_or_handle' => null, 'is_primary' => false, 'visibility_scope' => 'PUB', 'status_contact_channel' => 'UNK', 'effective_from' => null, 'effective_until' => null, 'status_record_lifecycle' => 'ACT'];
$establishment_contact = [
    '_id' => 'Ec7K2pQ9xR4tV8zN', // Canonical 16-character contact identifier.
    'establishment_id' => 'Es7K2pQ9xR4tV8zN', // Owning establishment_main identifier.
    'type_contact_channel' => 'PHN', // Channel medium such as phone, email, website, or social media.
    'type_contact_number' => 'L', // Telephone technology subtype when applicable.
    'contact_label' => 'Reservations', // Public purpose label.
    'contact_value_display' => '+63 2 8123 4567', // Human-readable display value.
    'contact_value_normalized' => '+63281234567', // Normalized comparison and action value.
    'contact_url' => 'tel:+63281234567', // Validated safe action URL.
    'platform_code' => null, // Platform code for social or messaging channels.
    'username_or_handle' => null, // Platform account handle when applicable.
    'is_primary' => true, // Primary channel for its purpose or type.
    'visibility_scope' => 'PUB', // Public or restricted audience rule.
    'status_contact_channel' => 'AC', // Observed channel activity state.
    'effective_from' => '2026-01-01', // Date the channel became effective when known.
    'effective_until' => null, // Date the channel ended; historical records remain retained.
    'type_date_precision' => 'D', // Precision of effective dates.
    'type_date_qualifier' => 'EXA', // Exactness qualifier for effective dates.
    'first_observed_at' => '2026-01-05T06:00:00Z', // First Massage Nexus observation.
    'last_observed_active_at' => '2026-07-20T06:00:00Z', // Latest positive active observation.
    'first_observed_inactive_at' => null, // First inactive observation when applicable.
    'last_checked_at' => '2026-07-21T07:00:00Z', // Latest check attempt regardless of result.
    'first_confirmed_at' => '2026-01-06T07:00:00Z', // First adequate confirmation.
    'last_confirmed_at' => '2026-07-21T07:00:00Z', // Latest adequate confirmation.
    'record_verification_id' => 'Vr7K2pQ9xR4tV8zN', // Latest relevant verification result.
    'source_id_list' => ['Sr7K2pQ9xR4tV8zN'], // Supporting sources.
    'internal_note' => null, // Restricted operational note.
    'status_record_lifecycle' => 'ACT', // Database lifecycle state.
    'created_at' => $created_at, // UTC record creation time.
    'updated_at' => $updated_at, // UTC record update time.
];
$establishment_contact_field_order = array_keys($establishment_contact);
$establishment_contact_embedded_structure = [];
$establishment_contact_field_property = [];
foreach ($establishment_contact as $field_name => $sample_value) { $establishment_contact_field_property[$field_name] = ['field_label' => ucwords(str_replace('_', ' ', $field_name)), 'field_description' => 'Establishment contact property: ' . $field_name . '.', 'type_data' => is_array($sample_value) ? 'A' : (is_bool($sample_value) ? 'B' : 'S')]; }
$establishment_contact_subfield_property = [];
$establishment_contact_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'establishment_active_channel', 'index_name' => 'ix_establishment_contact_owner_type_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'type_contact_channel', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_contact_channel', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
    ['index_key' => 'normalized_contact_lookup', 'index_name' => 'ix_establishment_contact_normalized', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => true, 'index_field_list' => [['field_name' => 'contact_value_normalized', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 30],
];
$establishment_contact_boundary = ['owns' => ['official establishment contact channel, effective history, observation, confirmation, and verification references'], 'references' => ['establishment_main', 'record_verification', 'research_source'], 'does_not_own' => ['research-source URL merely citing the establishment', 'private practitioner contact', 'establishment identity', 'verification evidence']];
return compact('establishment_contact_default', 'establishment_contact', 'establishment_contact_field_order', 'establishment_contact_embedded_structure', 'establishment_contact_field_property', 'establishment_contact_subfield_property', 'establishment_contact_index_list', 'establishment_contact_boundary');
