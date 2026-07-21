<?php
/**
 * Title: Massage Nexus Establishment Event Structure Guide
 * Version: 1.20
 * Collection: establishment_event
 * Description: Stores evidence-aware establishment lifecycle and history events without overwriting current identity.
 * Purpose: Represents uncertain, approximate, historical, and independently sourced business events.
 */
$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T10:38:00Z';
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
$establishment_event_field_order = ['_id', 'establishment_id', 'type_business_event', 'effective_date', 'type_date_precision', 'type_date_qualifier', 'effective_until', 'previous_name', 'resulting_name', 'previous_location_snapshot', 'resulting_location_snapshot', 'reason', 'public_note', 'internal_note', 'reported_at', 'first_observed_at', 'last_observed_at', 'first_confirmed_at', 'last_confirmed_at', 'level_confidence', 'status_verification', 'record_verification_id_list', 'research_source_id_list', 'related_establishment_id', 'status_record_lifecycle', 'created_at', 'updated_at'];
$establishment_event_embedded_structure = [];
$establishment_event_field_property = [
    '_id' => ['field_label' => 'Establishment Event ID', 'field_description' => 'Canonical identifier for the sourced establishment event.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_unique' => true, 'is_indexed' => true],
    'establishment_id' => ['field_label' => 'Establishment', 'field_description' => 'Establishment whose history contains the event.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'type_business_event' => ['field_label' => 'Business Event Type', 'field_description' => 'Controlled real-world event type, such as opening, closure, move, rename, sale, or merger.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true, 'is_indexed' => true],
    'effective_date' => ['field_label' => 'Effective Date', 'field_description' => 'Best-supported date on which the event took effect.', 'type_data' => 'S', 'type_field' => 'DTI', 'is_indexed' => true],
    'type_date_precision' => ['field_label' => 'Date Precision', 'field_description' => 'Controlled precision of the effective date.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'type_date_qualifier' => ['field_label' => 'Date Qualifier', 'field_description' => 'Controlled exactness or uncertainty qualifier for the effective date.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'effective_until' => ['field_label' => 'Effective Until', 'field_description' => 'End date when the event describes a bounded period rather than a point change.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'previous_name' => ['field_label' => 'Previous Name', 'field_description' => 'Source-supported establishment name before a rename or transition.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'resulting_name' => ['field_label' => 'Resulting Name', 'field_description' => 'Source-supported establishment name after a rename or transition.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'previous_location_snapshot' => ['field_label' => 'Previous Location Snapshot', 'field_description' => 'Source-time location wording before a move or transition.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'resulting_location_snapshot' => ['field_label' => 'Resulting Location Snapshot', 'field_description' => 'Source-time location wording after a move or transition.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'reason' => ['field_label' => 'Event Reason', 'field_description' => 'Supported explanation for why the event occurred.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'public_note' => ['field_label' => 'Public Event Note', 'field_description' => 'Approved public qualification of the event and its uncertainty.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'internal_note' => ['field_label' => 'Internal Event Note', 'field_description' => 'Restricted research or operational note that must not be exposed publicly.', 'type_data' => 'S', 'type_field' => 'TXA', 'visibility_scope' => 'PRV'],
    'reported_at' => ['field_label' => 'Reported At', 'field_description' => 'UTC time when a source reported the event.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'first_observed_at' => ['field_label' => 'First Observed At', 'field_description' => 'UTC time when Massage Nexus first observed evidence of the event.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_observed_at' => ['field_label' => 'Last Observed At', 'field_description' => 'Latest UTC time when Massage Nexus observed evidence of the event.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'first_confirmed_at' => ['field_label' => 'First Confirmed At', 'field_description' => 'UTC time when adequate evidence first confirmed the event.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'Latest UTC time when adequate evidence confirmed the event.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'level_confidence' => ['field_label' => 'Confidence Level', 'field_description' => 'Reviewed confidence in the event interpretation.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'U'],
    'status_verification' => ['field_label' => 'Verification Status', 'field_description' => 'Current verification result for the event.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'U'],
    'record_verification_id_list' => ['field_label' => 'Verification Records', 'field_description' => 'Record-verification references supporting or challenging the event.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true],
    'research_source_id_list' => ['field_label' => 'Research Sources', 'field_description' => 'Research-source references providing provenance for the event.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true],
    'related_establishment_id' => ['field_label' => 'Related Establishment', 'field_description' => 'Predecessor, successor, merged, split, or otherwise event-related establishment.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state independent from event verification.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC time when the event record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC time when the event record was last changed.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
];
$establishment_event_subfield_property = [];
$establishment_event_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'event_history', 'index_name' => 'ix_establishment_event_establishment_effective_type', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'effective_date', 'type_index_mode' => 'DESC', 'sort_order' => 20], ['field_name' => 'type_business_event', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
];
$establishment_event_boundary = [
    'owns' => ['independently sourced establishment lifecycle and history events'],
    'reference_field_list' => ['establishment_id', 'record_verification_id_list', 'research_source_id_list', 'related_establishment_id'],
    'does_not_own' => ['current establishment status or organization relationship history'],
];
return ['establishment_event_default' => $establishment_event_default, 'establishment_event' => $establishment_event, 'establishment_event_field_order' => $establishment_event_field_order, 'establishment_event_embedded_structure' => $establishment_event_embedded_structure, 'establishment_event_field_property' => $establishment_event_field_property, 'establishment_event_subfield_property' => $establishment_event_subfield_property, 'establishment_event_index_list' => $establishment_event_index_list, 'establishment_event_boundary' => $establishment_event_boundary];
