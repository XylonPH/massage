<?php
/**
 * Title: Massage Nexus Organization–Establishment Relationship Structure Guide
 * Version: 1.00
 * Collection: organization_establishment
 * Description: Stores effective-dated relationships between organizations and establishments.
 * Purpose: Separates brand, ownership, legal-operation, management, franchise, and affiliation facts from both endpoint records.
 */
$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T08:23:43Z';
$organization_establishment_default = ['is_primary' => false, 'is_public' => true, 'status_relationship' => 'ACT', 'status_record_lifecycle' => 'ACT'];
$organization_establishment = [
    '_id' => 'Oe8K2pQ9xR4tV7zN', // Canonical relationship identifier.
    'organization_id' => 'Or8K2pQ9xR4tV7zN', // Related organization.
    'establishment_id' => 'Es7K2pQ9xR4tV8zN', // Related establishment.
    'type_organization_establishment_relationship' => 'OPR', // Relationship meaning.
    'relationship_title' => 'Legal operator', // Human-readable context.
    'is_primary' => true, // Whether primary for this relationship type.
    'is_public' => true, // Whether safe for public display.
    'status_relationship' => 'ACT', // Current relationship state.
    'effective_from' => '2024-01-01', // Supported effective start.
    'effective_until' => null, // Supported effective end.
    'type_date_precision' => 'D', // Date precision.
    'type_date_qualifier' => 'EXA', // Date qualifier.
    'ownership_percentage' => null, // Permitted ownership percentage when applicable.
    'first_observed_at' => '2026-07-01T00:00:00Z', // First observation.
    'last_observed_at' => '2026-07-20T00:00:00Z', // Latest observation.
    'first_confirmed_at' => '2026-07-02T00:00:00Z', // First adequate confirmation.
    'last_confirmed_at' => '2026-07-20T00:00:00Z', // Latest adequate confirmation.
    'record_verification_id_list' => ['Vr8K2pQ9xR4tV7zN'], // Verification records.
    'document_id_list' => ['Do8K2pQ9xR4tV7zN'], // Supporting document records.
    'research_source_id_list' => ['Sr8K2pQ9xR4tV7zN'], // Supporting sources.
    'public_note' => 'Operates this branch.', // Public explanatory note.
    'internal_note' => null, // Restricted internal note.
    'status_record_lifecycle' => 'ACT', // Database lifecycle.
    'created_at' => $created_at, // UTC creation time.
    'updated_at' => $updated_at, // UTC update time.
];
$organization_establishment_field_order = ['_id', 'organization_id', 'establishment_id', 'type_organization_establishment_relationship', 'relationship_title', 'is_primary', 'is_public', 'status_relationship', 'effective_from', 'effective_until', 'type_date_precision', 'type_date_qualifier', 'ownership_percentage', 'first_observed_at', 'last_observed_at', 'first_confirmed_at', 'last_confirmed_at', 'record_verification_id_list', 'document_id_list', 'research_source_id_list', 'public_note', 'internal_note', 'status_record_lifecycle', 'created_at', 'updated_at'];
$organization_establishment_embedded_structure = [];
$organization_establishment_field_property = [];
foreach ($organization_establishment as $field_name => $sample_value) {
    $type_data = is_bool($sample_value) ? 'B' : (is_array($sample_value) ? 'A' : (is_float($sample_value) ? 'D' : 'S'));
    $organization_establishment_field_property[$field_name] = ['field_label' => ucwords(str_replace('_', ' ', $field_name)), 'field_description' => 'Organization-establishment relationship field: ' . str_replace('_', ' ', $field_name) . '.', 'type_data' => $type_data];
}
$organization_establishment_field_property['_id']['is_mandatory'] = true;
$organization_establishment_field_property['organization_id']['is_mandatory'] = true;
$organization_establishment_field_property['establishment_id']['is_mandatory'] = true;
$organization_establishment_field_property['type_organization_establishment_relationship']['is_mandatory'] = true;
$organization_establishment_subfield_property = [];
$organization_establishment_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'relationship_lookup', 'index_name' => 'ix_organization_establishment_pair_type_period', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'organization_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'type_organization_establishment_relationship', 'type_index_mode' => 'ASC', 'sort_order' => 30], ['field_name' => 'effective_from', 'type_index_mode' => 'DESC', 'sort_order' => 40]], 'sort_order' => 20],
];
$organization_establishment_boundary = ['owns' => ['effective-dated organization-to-establishment relationship facts'], 'references' => ['organization_main, establishment_main, record_verification, document_main, and research_source'], 'does_not_own' => ['workspace permissions or proof that branding alone establishes legal ownership']];
return compact('organization_establishment_default', 'organization_establishment', 'organization_establishment_field_order', 'organization_establishment_embedded_structure', 'organization_establishment_field_property', 'organization_establishment_subfield_property', 'organization_establishment_index_list', 'organization_establishment_boundary');
