<?php
/**
 * Title: Massage Nexus Establishment–Practitioner Affiliation Structure Guide
 * Version: 1.00
 * Collection: establishment_practitioner
 * Description: Stores effective-dated practitioner affiliation facts specific to one establishment.
 * Purpose: Separates roster, employment, booking, price, confirmation, and dispute context from practitioner identity.
 */
$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T08:23:43Z';
$establishment_practitioner_default = ['is_public_roster' => false, 'is_public_booking' => false, 'eligible_establishment_service_id_list' => [], 'status_affiliation' => 'UNK', 'status_record_lifecycle' => 'ACT'];
$establishment_practitioner = [
    '_id' => 'EprK2pQ9xR4tV7zN', // Canonical affiliation identifier.
    'establishment_id' => 'Es7K2pQ9xR4tV8zN', // Establishment endpoint.
    'practitioner_id' => 'P8rC3mL7xT1qV5nK', // Practitioner endpoint.
    'status_affiliation' => 'ACT', // Current affiliation state.
    'type_work_arrangement' => 'EMP', // Employment or contract arrangement.
    'public_title' => 'Senior Massage Practitioner', // Public establishment-specific title.
    'position_affiliation' => 'SEN', // Provider rank/position code.
    'department' => 'Massage', // Establishment area.
    'is_public_roster' => true, // May appear on public roster.
    'is_public_booking' => true, // May be selected for public booking.
    'status_user_confirmation' => 'CFM', // Practitioner confirmation state using the shared user-confirmation taxonomy.
    'status_dispute' => 'NON', // Dispute state.
    'eligible_establishment_service_id_list' => ['EsrK2pQ9xR4tV7zN'], // Eligible menu offerings.
    'price_surcharge_list' => [['establishment_service_id' => 'EsrK2pQ9xR4tV7zN', 'amount' => 200.00, 'currency_id' => 111, 'effective_from' => '2026-07-01', 'effective_until' => null]], // Practitioner-specific adjustments.
    'availability_reference_id' => null, // Future schedule/availability reference.
    'started_at' => null, // Supported actual start, not first sighting.
    'started_at_precision' => 'U', // Start-date precision.
    'started_at_qualifier' => null, // Qualifier is omitted because no start date is known.
    'ended_at' => null, // Supported actual end.
    'ended_at_precision' => 'U', // End-date precision.
    'ended_at_qualifier' => null, // Qualifier is omitted because no end date is known.
    'first_observed_active_at' => '2026-07-11T00:00:00Z', // First positive observation.
    'last_observed_active_at' => '2026-07-20T00:00:00Z', // Latest positive observation.
    'first_observed_inactive_at' => null, // First inactive observation.
    'last_checked_at' => '2026-07-20T00:00:00Z', // Latest check attempt.
    'first_confirmed_at' => null, // First adequate confirmation.
    'last_confirmed_at' => null, // Latest adequate confirmation.
    'record_verification_id_list' => [], // Verification records.
    'research_source_id_list' => ['Sr8K2pQ9xR4tV7zN'], // Supporting sources.
    'public_note' => null, // Public note.
    'internal_note' => 'Start date remains unknown.', // Restricted note.
    'status_record_lifecycle' => 'ACT', // Database lifecycle.
    'created_at' => $created_at, // UTC creation time.
    'updated_at' => $updated_at, // UTC update time.
];
$establishment_practitioner_field_order = array_keys($establishment_practitioner);
$establishment_practitioner_embedded_structure = ['price_surcharge_list' => $establishment_practitioner['price_surcharge_list'][0]];
$establishment_practitioner_field_property = [];
foreach ($establishment_practitioner as $field_name => $sample_value) {
    $type_data = is_bool($sample_value) ? 'B' : (is_array($sample_value) ? 'A' : 'S');
    $establishment_practitioner_field_property[$field_name] = ['field_label' => ucwords(str_replace('_', ' ', $field_name)), 'field_description' => 'Establishment-practitioner affiliation field: ' . str_replace('_', ' ', $field_name) . '.', 'type_data' => $type_data];
}
$establishment_practitioner_field_property['_id']['is_mandatory'] = true;
$establishment_practitioner_field_property['establishment_id']['is_mandatory'] = true;
$establishment_practitioner_field_property['practitioner_id']['is_mandatory'] = true;
$establishment_practitioner_subfield_property = [
    'price_surcharge_list.establishment_service_id' => ['field_label' => 'Establishment Service ID', 'field_description' => 'Offering to which the adjustment applies.', 'type_data' => 'S', 'is_mandatory' => true],
    'price_surcharge_list.amount' => ['field_label' => 'Amount', 'field_description' => 'Non-negative price adjustment.', 'type_data' => 'D', 'min_number' => 0],
    'price_surcharge_list.currency_id' => ['field_label' => 'Currency ID', 'field_description' => 'Common-reference currency identifier.', 'type_data' => 'I'],
];
$establishment_practitioner_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'pair', 'index_name' => 'ix_establishment_practitioner_pair_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'practitioner_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_affiliation', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
    ['index_key' => 'public_roster', 'index_name' => 'ix_establishment_practitioner_public_booking', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'is_public_roster', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'is_public_booking', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 30],
];
$establishment_practitioner_boundary = ['owns' => ['establishment-specific affiliation, roster, booking, confirmation, dispute, and price context'], 'references' => ['establishment, practitioner, menu offering, research source, and verification records'], 'does_not_own' => ['practitioner identity, credentials, workspace access, or authoritative booking availability']];
return compact('establishment_practitioner_default', 'establishment_practitioner', 'establishment_practitioner_field_order', 'establishment_practitioner_embedded_structure', 'establishment_practitioner_field_property', 'establishment_practitioner_subfield_property', 'establishment_practitioner_index_list', 'establishment_practitioner_boundary');
