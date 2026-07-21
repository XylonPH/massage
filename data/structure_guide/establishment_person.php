<?php
/**
 * Title: Massage Nexus Establishment–Person Relationship Structure Guide
 * Version: 1.40
 * Collection: establishment_person
 * Description: Stores effective-dated owner, investor, founder, operator, manager, representative, and non-practitioner staff relationships.
 * Purpose: Preserves human relationship facts independently from practitioner identity, user accounts, claims, and workspace access.
 *
 * Notes:
 * - At least one of user_id, practitioner_id, or person_name_snapshot must identify the person; multiple identifiers must resolve to the same human.
 * - A person may have both an establishment_person business relationship and an establishment_practitioner clinical affiliation; neither record replaces the other.
 */
$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T11:14:49Z';
$establishment_person_default = ['is_public' => false, 'visibility_scope' => 'PRV', 'status_relationship' => 'ACT', 'status_record_lifecycle' => 'ACT', 'revision_number' => 1];
$establishment_person = [
    '_id' => 'Ep8K2pQ9xR4tV7zN', // Canonical relationship identifier.
    'establishment_id' => 'Es7K2pQ9xR4tV8zN', // Related establishment.
    'user_id' => null, // Optional user-account reference when known.
    'practitioner_id' => 'P8rC3mL7xT1qV5nK', // Optional public practitioner reference.
    'person_name_snapshot' => 'Sample Person', // Minimal source-time identity snapshot.
    'type_establishment_person_relationship' => 'OWN', // Relationship meaning.
    'public_title' => 'Owner and practitioner', // Public title when permitted.
    'internal_role' => 'Authorized representative', // Internal operational context.
    'department' => null, // Optional department.
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
    'revision_number' => 1, // Monotonic optimistic-concurrency token; the required concurrency token distinct from updated_at (docs/02-governance/edit-system.txt section 16).
    'created_at' => $created_at, // UTC creation time.
    'updated_at' => $updated_at, // UTC update time.
];
$establishment_person_field_order = [
    '_id', 'establishment_id', 'user_id', 'practitioner_id', 'person_name_snapshot',
    'type_establishment_person_relationship', 'public_title', 'internal_role', 'department',
    'percentage_interest', 'is_public', 'visibility_scope', 'status_relationship', 'effective_from',
    'effective_until', 'type_date_precision', 'type_date_qualifier', 'first_observed_at',
    'last_observed_active_at', 'first_observed_inactive_at', 'first_confirmed_at', 'last_confirmed_at',
    'record_verification_id_list', 'research_source_id_list', 'document_id_list', 'claim_id',
    'internal_note', 'status_record_lifecycle', 'revision_number', 'created_at', 'updated_at',
];
$establishment_person_embedded_structure = [];
$establishment_person_field_property = [
    '_id' => ['field_label' => 'Relationship ID', 'field_description' => 'Canonical identifier for this establishment-person relationship record.', 'type_data' => 'S', 'type_field' => 'HDN', 'type_sql' => 'CHAR(16)', 'is_mandatory' => true, 'is_unique' => true, 'is_indexed' => true],
    'establishment_id' => ['field_label' => 'Establishment', 'field_description' => 'Establishment to which the person relationship applies; this reference already defines its scope.', 'type_data' => 'S', 'type_field' => 'REF', 'type_sql' => 'CHAR(16)', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'user_id' => ['field_label' => 'User Account', 'field_description' => 'Optional user account associated with the person; it does not grant workspace access.', 'type_data' => 'S', 'type_field' => 'REF', 'type_sql' => 'CHAR(16)', 'is_relational' => true],
    'practitioner_id' => ['field_label' => 'Practitioner', 'field_description' => 'Optional public practitioner profile associated with the person.', 'type_data' => 'S', 'type_field' => 'REF', 'type_sql' => 'CHAR(16)', 'is_relational' => true],
    'person_name_snapshot' => ['field_label' => 'Person Name Snapshot', 'field_description' => 'Minimal source-time name retained when no canonical person reference is available.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(200)', 'max_character' => 200],
    'type_establishment_person_relationship' => ['field_label' => 'Relationship Type', 'field_description' => 'Controlled factual role the person has or had with the establishment.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'is_mandatory' => true, 'is_indexed' => true],
    'public_title' => ['field_label' => 'Public Title', 'field_description' => 'Approved title that may be displayed publicly for this relationship.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(160)', 'max_character' => 160],
    'internal_role' => ['field_label' => 'Internal Role', 'field_description' => 'Restricted operational description that clarifies the person\'s role without granting permission.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(160)', 'max_character' => 160, 'visibility_scope' => 'PRV'],
    'department' => ['field_label' => 'Department', 'field_description' => 'Department or business unit associated with this relationship when known.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(160)', 'max_character' => 160],
    'percentage_interest' => ['field_label' => 'Ownership Interest Percentage', 'field_description' => 'Restricted supported ownership interest expressed from 0 through 100; absence means unknown, not zero.', 'type_data' => 'F', 'type_field' => 'NMB', 'type_sql' => 'DECIMAL(5,2)', 'min_number' => 0, 'max_number' => 100, 'visibility_scope' => 'PRV'],
    'is_public' => ['field_label' => 'Public Relationship', 'field_description' => 'Whether the relationship is approved for public presentation.', 'type_data' => 'B', 'type_field' => 'CHK', 'type_sql' => 'BOOLEAN', 'default_value' => false],
    'visibility_scope' => ['field_label' => 'Visibility Scope', 'field_description' => 'Maximum audience permitted to access the relationship record.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'default_value' => 'PRV', 'is_indexed' => true],
    'status_relationship' => ['field_label' => 'Relationship Status', 'field_description' => 'Controlled current lifecycle state of the factual relationship.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'default_value' => 'ACT', 'is_indexed' => true],
    'effective_from' => ['field_label' => 'Effective From', 'field_description' => 'Best-supported date on which the relationship began.', 'type_data' => 'S', 'type_field' => 'DTI', 'type_sql' => 'DATE'],
    'effective_until' => ['field_label' => 'Effective Until', 'field_description' => 'Best-supported date on which the relationship ended.', 'type_data' => 'S', 'type_field' => 'DTI', 'type_sql' => 'DATE'],
    'type_date_precision' => ['field_label' => 'Date Precision', 'field_description' => 'Controlled precision applying to the effective relationship dates.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)'],
    'type_date_qualifier' => ['field_label' => 'Date Qualifier', 'field_description' => 'Controlled qualifier indicating whether the effective dates are exact, approximate, or otherwise bounded.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)'],
    'first_observed_at' => ['field_label' => 'First Observed At', 'field_description' => 'UTC time when Massage Nexus first observed evidence of the relationship.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'last_observed_active_at' => ['field_label' => 'Last Observed Active At', 'field_description' => 'Latest UTC time when the relationship was positively observed as active.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'first_observed_inactive_at' => ['field_label' => 'First Observed Inactive At', 'field_description' => 'First UTC time when the relationship was observed as no longer active.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'first_confirmed_at' => ['field_label' => 'First Confirmed At', 'field_description' => 'UTC time when adequate evidence first confirmed the relationship.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'Latest UTC time when adequate evidence confirmed the relationship.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'record_verification_id_list' => ['field_label' => 'Verification Records', 'field_description' => 'Record-verification references supporting or challenging this relationship.', 'type_data' => 'A', 'type_field' => 'TAG', 'type_sql' => 'JSON', 'is_relational' => true],
    'research_source_id_list' => ['field_label' => 'Research Sources', 'field_description' => 'Research-source references that provide provenance for the relationship.', 'type_data' => 'A', 'type_field' => 'TAG', 'type_sql' => 'JSON', 'is_relational' => true],
    'document_id_list' => ['field_label' => 'Evidence Documents', 'field_description' => 'Protected document references supporting the relationship.', 'type_data' => 'A', 'type_field' => 'TAG', 'type_sql' => 'JSON', 'is_relational' => true, 'visibility_scope' => 'PRV'],
    'claim_id' => ['field_label' => 'Related Claim', 'field_description' => 'Claim record from which this relationship may have been reviewed; a claim is not itself proof.', 'type_data' => 'S', 'type_field' => 'REF', 'type_sql' => 'CHAR(16)', 'is_relational' => true],
    'internal_note' => ['field_label' => 'Internal Note', 'field_description' => 'Restricted operational note that must not be exposed publicly.', 'type_data' => 'S', 'type_field' => 'TXA', 'type_sql' => 'TEXT', 'visibility_scope' => 'PRV'],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state independent from the relationship state.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'default_value' => 'ACT', 'is_indexed' => true],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token that increments by one on every accepted revision; the required concurrency token distinct from updated_at (docs/02-governance/edit-system.txt section 16).', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'is_mandatory' => true, 'min_number' => 1],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC time when the relationship record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC time when the relationship record was last changed.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
];
$establishment_person_subfield_property = [];
$establishment_person_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'relationship_lookup', 'index_name' => 'ix_establishment_person_target_type_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'type_establishment_person_relationship', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_relationship', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
    ['index_key' => 'visibility', 'index_name' => 'ix_establishment_person_visibility_lifecycle', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'visibility_scope', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_record_lifecycle', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 30],
];
$establishment_person_boundary = [
    'owns' => ['effective-dated person relationships and minimal identity snapshots'],
    'reference_field_list' => ['establishment_id', 'user_id', 'practitioner_id', 'record_verification_id_list', 'research_source_id_list', 'document_id_list', 'claim_id'],
    'does_not_own' => ['private legal identity, practitioner affiliation facts, authentication, or workspace permissions'],
];
return [
    'establishment_person_default' => $establishment_person_default,
    'establishment_person' => $establishment_person,
    'establishment_person_field_order' => $establishment_person_field_order,
    'establishment_person_embedded_structure' => $establishment_person_embedded_structure,
    'establishment_person_field_property' => $establishment_person_field_property,
    'establishment_person_subfield_property' => $establishment_person_subfield_property,
    'establishment_person_index_list' => $establishment_person_index_list,
    'establishment_person_boundary' => $establishment_person_boundary,
];
