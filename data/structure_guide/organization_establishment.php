<?php
/**
 * Title: Massage Nexus Organization–Establishment Relationship Structure Guide
 * Version: 1.10
 * Collection: organization_establishment
 * Description: Stores effective-dated relationships between organizations and establishments.
 * Purpose: Separates brand, ownership, legal-operation, management, franchise, and affiliation facts from both endpoint records.
 */
$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T09:49:12Z';
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
$organization_establishment_field_property = [
    '_id' => ['field_label' => 'Organization-Establishment Relationship ID', 'field_description' => 'Canonical identifier for the relationship record.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_unique' => true, 'is_indexed' => true],
    'organization_id' => ['field_label' => 'Organization', 'field_description' => 'Organization participating in the relationship.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'establishment_id' => ['field_label' => 'Establishment', 'field_description' => 'Establishment participating in the relationship.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'type_organization_establishment_relationship' => ['field_label' => 'Relationship Type', 'field_description' => 'Controlled legal, ownership, brand, management, franchise, or affiliation relationship.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true, 'is_indexed' => true],
    'relationship_title' => ['field_label' => 'Relationship Title', 'field_description' => 'Concise human-readable context for this specific relationship.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'is_primary' => ['field_label' => 'Primary Relationship', 'field_description' => 'Whether this is the primary relationship of its type for the endpoint context.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false],
    'is_public' => ['field_label' => 'Public Relationship', 'field_description' => 'Whether the relationship is approved for public presentation.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => true],
    'status_relationship' => ['field_label' => 'Relationship Status', 'field_description' => 'Controlled current lifecycle state of the factual relationship.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT'],
    'effective_from' => ['field_label' => 'Effective From', 'field_description' => 'Best-supported date on which the relationship began.', 'type_data' => 'S', 'type_field' => 'DTI', 'is_indexed' => true],
    'effective_until' => ['field_label' => 'Effective Until', 'field_description' => 'Best-supported date on which the relationship ended.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'type_date_precision' => ['field_label' => 'Date Precision', 'field_description' => 'Controlled precision applying to relationship dates.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'type_date_qualifier' => ['field_label' => 'Date Qualifier', 'field_description' => 'Controlled exactness or uncertainty qualifier for relationship dates.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'ownership_percentage' => ['field_label' => 'Ownership Percentage', 'field_description' => 'Supported ownership interest from 0 through 100 when the relationship type is ownership; absence means unknown.', 'type_data' => 'F', 'type_field' => 'NMB', 'min_number' => 0, 'max_number' => 100, 'visibility_scope' => 'PRV'],
    'first_observed_at' => ['field_label' => 'First Observed At', 'field_description' => 'UTC time when Massage Nexus first observed evidence of the relationship.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_observed_at' => ['field_label' => 'Last Observed At', 'field_description' => 'Latest UTC time when Massage Nexus observed evidence of the relationship.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'first_confirmed_at' => ['field_label' => 'First Confirmed At', 'field_description' => 'UTC time when adequate evidence first confirmed the relationship.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'Latest UTC time when adequate evidence confirmed the relationship.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'record_verification_id_list' => ['field_label' => 'Verification Records', 'field_description' => 'Record-verification references supporting or challenging the relationship.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true],
    'document_id_list' => ['field_label' => 'Evidence Documents', 'field_description' => 'Protected document references supporting the relationship.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true, 'visibility_scope' => 'PRV'],
    'research_source_id_list' => ['field_label' => 'Research Sources', 'field_description' => 'Research-source references providing provenance for the relationship.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true],
    'public_note' => ['field_label' => 'Public Relationship Note', 'field_description' => 'Approved public explanation specific to the relationship.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'internal_note' => ['field_label' => 'Internal Relationship Note', 'field_description' => 'Restricted operational note that must not be exposed publicly.', 'type_data' => 'S', 'type_field' => 'TXA', 'visibility_scope' => 'PRV'],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state independent from relationship status.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC time when the relationship record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC time when the relationship record was last changed.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
];
$organization_establishment_subfield_property = [];
$organization_establishment_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'relationship_lookup', 'index_name' => 'ix_organization_establishment_pair_type_period', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'organization_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'type_organization_establishment_relationship', 'type_index_mode' => 'ASC', 'sort_order' => 30], ['field_name' => 'effective_from', 'type_index_mode' => 'DESC', 'sort_order' => 40]], 'sort_order' => 20],
];
$organization_establishment_boundary = ['owns' => ['effective-dated organization-to-establishment relationship facts'], 'references' => ['organization_main, establishment_main, record_verification, document_main, and research_source'], 'does_not_own' => ['workspace permissions or proof that branding alone establishes legal ownership']];
return ['organization_establishment_default' => $organization_establishment_default, 'organization_establishment' => $organization_establishment, 'organization_establishment_field_order' => $organization_establishment_field_order, 'organization_establishment_embedded_structure' => $organization_establishment_embedded_structure, 'organization_establishment_field_property' => $organization_establishment_field_property, 'organization_establishment_subfield_property' => $organization_establishment_subfield_property, 'organization_establishment_index_list' => $organization_establishment_index_list, 'organization_establishment_boundary' => $organization_establishment_boundary];
