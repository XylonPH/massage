<?php
/**
 * Title: Massage Nexus Practitioner Capability Structure Guide
 * Version: 1.10
 * Collection: practitioner_capability
 * Description: Stores practitioner capabilities that are not equivalent to one normalized service or one credential.
 * Purpose: Represents supported methods, equipment use, client contexts, and other bounded professional capabilities with evidence and lifecycle.
 */
$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T09:49:12Z';
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
$practitioner_capability_field_order = ['_id', 'practitioner_id', 'type_capability', 'capability_name', 'related_service_id_list', 'status_capability', 'is_public', 'first_observed_at', 'last_confirmed_at', 'record_verification_id_list', 'research_source_id_list', 'public_note', 'internal_note', 'status_record_lifecycle', 'created_at', 'updated_at'];
$practitioner_capability_embedded_structure = [];
$practitioner_capability_field_property = [
    '_id' => ['field_label' => 'Capability ID', 'field_description' => 'Canonical identifier for this practitioner capability fact.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_unique' => true, 'is_indexed' => true],
    'practitioner_id' => ['field_label' => 'Practitioner', 'field_description' => 'Practitioner who holds the stated capability.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'type_capability' => ['field_label' => 'Capability Type', 'field_description' => 'Controlled category of professional capability.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true, 'is_indexed' => true],
    'capability_name' => ['field_label' => 'Capability Name', 'field_description' => 'Specific bounded capability statement within the selected category.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_mandatory' => true],
    'related_service_id_list' => ['field_label' => 'Related Services', 'field_description' => 'Canonical services for which the capability is relevant.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true, 'default_value' => []],
    'status_capability' => ['field_label' => 'Capability Status', 'field_description' => 'Controlled current state of the capability fact.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'UNK', 'is_indexed' => true],
    'is_public' => ['field_label' => 'Public Capability', 'field_description' => 'Whether the capability is approved for public display.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => true],
    'first_observed_at' => ['field_label' => 'First Observed At', 'field_description' => 'UTC time when Massage Nexus first observed evidence of the capability.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'Latest UTC time when adequate evidence confirmed the capability.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'record_verification_id_list' => ['field_label' => 'Verification Records', 'field_description' => 'Record-verification references supporting or challenging the capability.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true],
    'research_source_id_list' => ['field_label' => 'Research Sources', 'field_description' => 'Research-source references that provide provenance for the capability.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true],
    'public_note' => ['field_label' => 'Public Capability Note', 'field_description' => 'Approved public explanation specific to the capability.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'internal_note' => ['field_label' => 'Internal Capability Note', 'field_description' => 'Restricted operational note that must not be exposed publicly.', 'type_data' => 'S', 'type_field' => 'TXA', 'visibility_scope' => 'PRV'],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state independent from capability status.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC time when the capability record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC time when the capability record was last changed.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
];
$practitioner_capability_subfield_property = [];
$practitioner_capability_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'holder_type_status', 'index_name' => 'ix_practitioner_capability_holder_type_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'practitioner_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'type_capability', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_capability', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
];
$practitioner_capability_boundary = ['owns' => ['non-service practitioner capability facts with evidence and lifecycle'], 'references' => ['practitioner, normalized service, research source, and verification records'], 'does_not_own' => ['service identity, provider menu offerings, credentials, or sensitive health assertions']];
return ['practitioner_capability_default' => $practitioner_capability_default, 'practitioner_capability' => $practitioner_capability, 'practitioner_capability_field_order' => $practitioner_capability_field_order, 'practitioner_capability_embedded_structure' => $practitioner_capability_embedded_structure, 'practitioner_capability_field_property' => $practitioner_capability_field_property, 'practitioner_capability_subfield_property' => $practitioner_capability_subfield_property, 'practitioner_capability_index_list' => $practitioner_capability_index_list, 'practitioner_capability_boundary' => $practitioner_capability_boundary];
