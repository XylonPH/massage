<?php
/**
 * Title: Massage Nexus Practitioner Capability Structure Guide
 * Version: 1.00
 * Collection: practitioner_capability
 * Description: Stores practitioner capabilities that are not equivalent to one normalized service or one credential.
 * Purpose: Represents supported methods, equipment use, client contexts, and other bounded professional capabilities with evidence and lifecycle.
 */
$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T08:23:43Z';
$practitioner_capability_default = ['related_service_id_list' => [], 'is_public' => true, 'status_capability' => 'UNK', 'status_record_lifecycle' => 'ACT'];
$practitioner_capability = [
    '_id' => 'PcaK2pQ9xR4tV7zN', // Canonical capability identifier.
    'practitioner_id' => 'P8rC3mL7xT1qV5nK', // Capability holder.
    'type_capability' => 'MET', // Capability type.
    'capability_name' => 'Hot-stone equipment use', // Specific capability name.
    'related_service_id_list' => ['Sv8K2pQ9xR4tV7zN'], // Related normalized services.
    'status_capability' => 'ACT', // Current capability state.
    'is_public' => true, // Whether public display is allowed.
    'first_observed_at' => '2026-07-01T00:00:00Z', // First observation.
    'last_confirmed_at' => '2026-07-20T00:00:00Z', // Latest adequate confirmation.
    'record_verification_id_list' => [], // Verification records.
    'research_source_id_list' => ['Sr8K2pQ9xR4tV7zN'], // Supporting sources.
    'public_note' => null, // Public explanation.
    'internal_note' => null, // Restricted note.
    'status_record_lifecycle' => 'ACT', // Database lifecycle.
    'created_at' => $created_at, // UTC creation time.
    'updated_at' => $updated_at, // UTC update time.
];
$practitioner_capability_field_order = array_keys($practitioner_capability);
$practitioner_capability_embedded_structure = [];
$practitioner_capability_field_property = [];
foreach ($practitioner_capability as $field_name => $sample_value) {
    $type_data = is_bool($sample_value) ? 'B' : (is_array($sample_value) ? 'A' : 'S');
    $practitioner_capability_field_property[$field_name] = ['field_label' => ucwords(str_replace('_', ' ', $field_name)), 'field_description' => 'Practitioner capability field: ' . str_replace('_', ' ', $field_name) . '.', 'type_data' => $type_data];
}
$practitioner_capability_field_property['_id']['is_mandatory'] = true;
$practitioner_capability_field_property['practitioner_id']['is_mandatory'] = true;
$practitioner_capability_field_property['type_capability']['is_mandatory'] = true;
$practitioner_capability_subfield_property = [];
$practitioner_capability_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'holder_type_status', 'index_name' => 'ix_practitioner_capability_holder_type_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'practitioner_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'type_capability', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_capability', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
];
$practitioner_capability_boundary = ['owns' => ['non-service practitioner capability facts with evidence and lifecycle'], 'references' => ['practitioner, normalized service, research source, and verification records'], 'does_not_own' => ['service identity, provider menu offerings, credentials, or sensitive health assertions']];
return compact('practitioner_capability_default', 'practitioner_capability', 'practitioner_capability_field_order', 'practitioner_capability_embedded_structure', 'practitioner_capability_field_property', 'practitioner_capability_subfield_property', 'practitioner_capability_index_list', 'practitioner_capability_boundary');
