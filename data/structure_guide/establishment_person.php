<?php
/**
 * Title: Massage Nexus Establishment–Person Relationship Structure Guide
 * Version: 1.00
 * Collection: establishment_person
 * Description: Stores effective-dated owner, investor, founder, operator, manager, representative, and non-practitioner staff relationships.
 * Purpose: Preserves human relationship facts independently from practitioner identity, user accounts, claims, and workspace access.
 */
$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T08:23:43Z';
$establishment_person_default = ['is_public' => false, 'visibility_scope' => 'PRV', 'status_relationship' => 'ACT', 'status_record_lifecycle' => 'ACT'];
$establishment_person = [
    '_id' => 'Ep8K2pQ9xR4tV7zN', // Canonical relationship identifier.
    'establishment_id' => 'Es7K2pQ9xR4tV8zN', // Related establishment.
    'person_id' => null, // Optional future canonical private-person reference.
    'user_id' => null, // Optional user-account reference when known.
    'practitioner_id' => 'P8rC3mL7xT1qV5nK', // Optional public practitioner reference.
    'person_name_snapshot' => 'Sample Person', // Minimal source-time identity snapshot.
    'type_establishment_person_relationship' => 'OWN', // Relationship meaning.
    'public_title' => 'Owner and practitioner', // Public title when permitted.
    'internal_role' => 'Authorized representative', // Internal operational context.
    'department' => null, // Optional department.
    'relationship_scope' => 'This establishment only', // Bounded scope explanation.
    'percentage_interest' => null, // Restricted ownership interest when justified.
    'is_public' => false, // Whether relationship may be public.
    'visibility_scope' => 'PRV', // Access classification.
    'status_relationship' => 'ACT', // Current relationship status.
    'effective_from' => null, // Supported relationship start.
    'effective_until' => null, // Supported relationship end.
    'type_date_precision' => 'U', // Date precision.
    'type_date_qualifier' => null, // Date qualifier is omitted when no date is known.
    'first_observed_at' => '2026-07-01T00:00:00Z', // First observation.
    'last_observed_active_at' => '2026-07-20T00:00:00Z', // Latest positive observation.
    'first_observed_inactive_at' => null, // First inactive observation.
    'first_confirmed_at' => null, // First adequate confirmation.
    'last_confirmed_at' => null, // Latest adequate confirmation.
    'record_verification_id_list' => [], // Verification records.
    'research_source_id_list' => ['Sr8K2pQ9xR4tV7zN'], // Supporting sources.
    'document_id_list' => [], // Supporting documents.
    'claim_id' => null, // Related claim when applicable.
    'internal_note' => 'Do not infer workspace access.', // Restricted note.
    'status_record_lifecycle' => 'ACT', // Database lifecycle.
    'created_at' => $created_at, // UTC creation time.
    'updated_at' => $updated_at, // UTC update time.
];
$establishment_person_field_order = array_keys($establishment_person);
$establishment_person_embedded_structure = [];
$establishment_person_field_property = [];
foreach ($establishment_person as $field_name => $sample_value) {
    $type_data = is_bool($sample_value) ? 'B' : (is_array($sample_value) ? 'A' : (is_float($sample_value) ? 'D' : 'S'));
    $establishment_person_field_property[$field_name] = ['field_label' => ucwords(str_replace('_', ' ', $field_name)), 'field_description' => 'Establishment-person relationship field: ' . str_replace('_', ' ', $field_name) . '.', 'type_data' => $type_data];
}
$establishment_person_field_property['_id']['is_mandatory'] = true;
$establishment_person_field_property['establishment_id']['is_mandatory'] = true;
$establishment_person_field_property['type_establishment_person_relationship']['is_mandatory'] = true;
$establishment_person_subfield_property = [];
$establishment_person_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'relationship_lookup', 'index_name' => 'ix_establishment_person_target_type_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'person_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'type_establishment_person_relationship', 'type_index_mode' => 'ASC', 'sort_order' => 30], ['field_name' => 'status_relationship', 'type_index_mode' => 'ASC', 'sort_order' => 40]], 'sort_order' => 20],
    ['index_key' => 'visibility', 'index_name' => 'ix_establishment_person_visibility_lifecycle', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'visibility_scope', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_record_lifecycle', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 30],
];
$establishment_person_boundary = ['owns' => ['effective-dated person relationships and minimal identity snapshots'], 'references' => ['future private person identity, user, practitioner, claim, evidence, and verification records'], 'does_not_own' => ['private legal identity, practitioner affiliation facts, authentication, or workspace permissions']];
return compact('establishment_person_default', 'establishment_person', 'establishment_person_field_order', 'establishment_person_embedded_structure', 'establishment_person_field_property', 'establishment_person_subfield_property', 'establishment_person_index_list', 'establishment_person_boundary');
