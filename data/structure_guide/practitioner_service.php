<?php
/**
 * Title: Massage Nexus Practitioner Service Structure Guide
 * Version: 1.00
 * Collection: practitioner_service
 * Description: Stores a practitioner's relationship to normalized services independently from any establishment menu.
 * Purpose: Represents public service capability, experience, evidence, and lifecycle without duplicating service_main.
 */
$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T08:23:43Z';
$practitioner_service_default = ['is_public' => true, 'status_capability' => 'UNK', 'status_record_lifecycle' => 'ACT'];
$practitioner_service = [
    '_id' => 'PsvK2pQ9xR4tV7zN', // Canonical relationship identifier.
    'practitioner_id' => 'P8rC3mL7xT1qV5nK', // Practitioner endpoint.
    'service_id' => 'Sv8K2pQ9xR4tV7zN', // Normalized service endpoint.
    'status_capability' => 'ACT', // Current capability state.
    'year_experience' => 5, // Supported experience estimate.
    'is_public' => true, // Public display permission.
    'first_observed_at' => '2026-07-01T00:00:00Z', // First observation.
    'last_confirmed_at' => '2026-07-20T00:00:00Z', // Latest adequate confirmation.
    'record_verification_id_list' => [], // Verification records.
    'research_source_id_list' => ['Sr8K2pQ9xR4tV7zN'], // Supporting sources.
    'public_note' => null, // Public note.
    'internal_note' => null, // Restricted note.
    'status_record_lifecycle' => 'ACT', // Database lifecycle.
    'created_at' => $created_at, // UTC creation time.
    'updated_at' => $updated_at, // UTC update time.
];
$practitioner_service_field_order = array_keys($practitioner_service);
$practitioner_service_embedded_structure = [];
$practitioner_service_field_property = [];
foreach ($practitioner_service as $field_name => $sample_value) {
    $type_data = is_bool($sample_value) ? 'B' : (is_array($sample_value) ? 'A' : (is_int($sample_value) ? 'I' : 'S'));
    $practitioner_service_field_property[$field_name] = ['field_label' => ucwords(str_replace('_', ' ', $field_name)), 'field_description' => 'Practitioner-service field: ' . str_replace('_', ' ', $field_name) . '.', 'type_data' => $type_data];
}
$practitioner_service_field_property['_id']['is_mandatory'] = true;
$practitioner_service_field_property['practitioner_id']['is_mandatory'] = true;
$practitioner_service_field_property['service_id']['is_mandatory'] = true;
$practitioner_service_subfield_property = [];
$practitioner_service_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'pair', 'index_name' => 'uq_practitioner_service_pair', 'type_index' => 'CMP', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'practitioner_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'service_id', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20],
];
$practitioner_service_boundary = ['owns' => ['practitioner-to-normalized-service capability facts'], 'references' => ['practitioner_main, service_main, research source, and verification records'], 'does_not_own' => ['provider menu offerings, credentials, establishment affiliation, or booking availability']];
return compact('practitioner_service_default', 'practitioner_service', 'practitioner_service_field_order', 'practitioner_service_embedded_structure', 'practitioner_service_field_property', 'practitioner_service_subfield_property', 'practitioner_service_index_list', 'practitioner_service_boundary');
