<?php
/**
 * Title: Massage Nexus Practitioner Service Structure Guide
 * Version: 1.10
 * Collection: practitioner_service
 * Description: Stores a practitioner's relationship to normalized services independently from any establishment menu.
 * Purpose: Represents public service capability, experience, evidence, and lifecycle without duplicating service_main.
 */
$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T09:49:12Z';
$practitioner_service_default = ['is_public' => true, 'status_capability' => 'UNK', 'status_record_lifecycle' => 'ACT'];
$practitioner_service = [
    '_id' => 'PsvK2pQ9xR4tV7zN', // Canonical relationship identifier.
    'practitioner_id' => 'P8rC3mL7xT1qV5nK', // Practitioner endpoint.
    'service_id' => 'Sv8K2pQ9xR4tV7zN', // Normalized service endpoint.
    'status_capability' => 'ACT', // Current capability state.
    'experience_year_count' => 5, // Supported whole-year experience estimate.
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
$practitioner_service_field_order = ['_id', 'practitioner_id', 'service_id', 'status_capability', 'experience_year_count', 'is_public', 'first_observed_at', 'last_confirmed_at', 'record_verification_id_list', 'research_source_id_list', 'public_note', 'internal_note', 'status_record_lifecycle', 'created_at', 'updated_at'];
$practitioner_service_embedded_structure = [];
$practitioner_service_field_property = [
    '_id' => ['field_label' => 'Practitioner Service ID', 'field_description' => 'Canonical identifier for this practitioner-to-service capability record.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_unique' => true, 'is_indexed' => true],
    'practitioner_id' => ['field_label' => 'Practitioner', 'field_description' => 'Practitioner who provides or is capable of the canonical service.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'service_id' => ['field_label' => 'Service', 'field_description' => 'Canonical service associated with the practitioner.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'status_capability' => ['field_label' => 'Capability Status', 'field_description' => 'Controlled current state of the practitioner-service capability.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'UNK'],
    'experience_year_count' => ['field_label' => 'Experience Year Count', 'field_description' => 'Supported non-negative whole-year estimate of experience providing this service.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'is_public' => ['field_label' => 'Public Service Capability', 'field_description' => 'Whether this practitioner-service capability is approved for public display.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => true],
    'first_observed_at' => ['field_label' => 'First Observed At', 'field_description' => 'UTC time when Massage Nexus first observed evidence of the capability.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'Latest UTC time when adequate evidence confirmed the capability.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'record_verification_id_list' => ['field_label' => 'Verification Records', 'field_description' => 'Record-verification references supporting or challenging the capability.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true],
    'research_source_id_list' => ['field_label' => 'Research Sources', 'field_description' => 'Research-source references providing provenance for the capability.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true],
    'public_note' => ['field_label' => 'Public Service Note', 'field_description' => 'Approved public clarification specific to the practitioner-service relationship.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'internal_note' => ['field_label' => 'Internal Service Note', 'field_description' => 'Restricted operational clarification that must not be exposed publicly.', 'type_data' => 'S', 'type_field' => 'TXA', 'visibility_scope' => 'PRV'],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state independent from capability status.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC time when the relationship record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC time when the relationship record was last changed.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
];
$practitioner_service_subfield_property = [];
$practitioner_service_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'pair', 'index_name' => 'uq_practitioner_service_pair', 'type_index' => 'CMP', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'practitioner_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'service_id', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20],
];
$practitioner_service_boundary = ['owns' => ['practitioner-to-normalized-service capability facts'], 'references' => ['practitioner_main, service_main, research source, and verification records'], 'does_not_own' => ['provider menu offerings, credentials, establishment affiliation, or booking availability']];
return ['practitioner_service_default' => $practitioner_service_default, 'practitioner_service' => $practitioner_service, 'practitioner_service_field_order' => $practitioner_service_field_order, 'practitioner_service_embedded_structure' => $practitioner_service_embedded_structure, 'practitioner_service_field_property' => $practitioner_service_field_property, 'practitioner_service_subfield_property' => $practitioner_service_subfield_property, 'practitioner_service_index_list' => $practitioner_service_index_list, 'practitioner_service_boundary' => $practitioner_service_boundary];
