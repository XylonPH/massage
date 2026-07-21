<?php
/**
 * Title: Massage Nexus Establishment Event Structure Guide
 * Version: 1.00
 * Collection: establishment_event
 * Description: Stores evidence-aware establishment lifecycle and history events without overwriting current identity.
 * Purpose: Represents uncertain, approximate, historical, and independently sourced business events.
 */
$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T08:23:43Z';
$establishment_event_default = ['status_verification' => 'U', 'level_confidence' => 'U', 'status_record_lifecycle' => 'ACT'];
$establishment_event = [
    '_id' => 'Ee8K2pQ9xR4tV7zN', // Canonical event identifier.
    'establishment_id' => 'Es7K2pQ9xR4tV8zN', // Event subject.
    'type_business_event' => 'SO', // Reused business-event code.
    'effective_date' => '2024-03-01', // Best supported effective date.
    'type_date_precision' => 'M', // Date precision.
    'type_date_qualifier' => 'APP', // Date qualifier.
    'effective_until' => null, // Optional event-period end.
    'previous_name' => null, // Name before the event.
    'resulting_name' => 'Sample Wellness Spa', // Name after the event.
    'previous_location_snapshot' => null, // Source-time prior location.
    'resulting_location_snapshot' => 'Makati City', // Source-time resulting location.
    'reason' => null, // Supported reason.
    'public_note' => 'Soft opening reported for March 2024.', // Public note.
    'internal_note' => null, // Restricted note.
    'reported_at' => '2024-03-15T00:00:00Z', // Time event was reported.
    'first_observed_at' => '2026-07-01T00:00:00Z', // First observation.
    'last_observed_at' => '2026-07-20T00:00:00Z', // Latest observation.
    'first_confirmed_at' => null, // First confirmation.
    'last_confirmed_at' => null, // Latest confirmation.
    'level_confidence' => 'M', // Shared confidence-level code.
    'status_verification' => 'U', // Verification result.
    'record_verification_id_list' => [], // Verification records.
    'research_source_id_list' => ['Sr8K2pQ9xR4tV7zN'], // Supporting sources.
    'related_establishment_id' => null, // Related predecessor, successor, or merged place.
    'status_record_lifecycle' => 'ACT', // Database lifecycle.
    'created_at' => $created_at, // UTC creation time.
    'updated_at' => $updated_at, // UTC update time.
];
$establishment_event_field_order = array_keys($establishment_event);
$establishment_event_embedded_structure = [];
$establishment_event_field_property = [];
foreach ($establishment_event as $field_name => $sample_value) {
    $type_data = is_array($sample_value) ? 'A' : 'S';
    $establishment_event_field_property[$field_name] = ['field_label' => ucwords(str_replace('_', ' ', $field_name)), 'field_description' => 'Establishment event field: ' . str_replace('_', ' ', $field_name) . '.', 'type_data' => $type_data];
}
$establishment_event_field_property['_id']['is_mandatory'] = true;
$establishment_event_field_property['establishment_id']['is_mandatory'] = true;
$establishment_event_field_property['type_business_event']['is_mandatory'] = true;
$establishment_event_subfield_property = [];
$establishment_event_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'event_history', 'index_name' => 'ix_establishment_event_establishment_effective_type', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'effective_date', 'type_index_mode' => 'DESC', 'sort_order' => 20], ['field_name' => 'type_business_event', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
];
$establishment_event_boundary = ['owns' => ['independently sourced establishment lifecycle and history events'], 'references' => ['establishment, research source, and verification records'], 'does_not_own' => ['current establishment status or organization relationship history']];
return compact('establishment_event_default', 'establishment_event', 'establishment_event_field_order', 'establishment_event_embedded_structure', 'establishment_event_field_property', 'establishment_event_subfield_property', 'establishment_event_index_list', 'establishment_event_boundary');
