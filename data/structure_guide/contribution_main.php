<?php
/**
 * Title: Massage Nexus Contribution Main Structure Guide
 * Version: 0.30
 * Collection: contribution_main
 * Description: Stores one proposal to add, correct, translate, enrich, or update a supported record.
 * Purpose: Documents the contribution_main record shape for review, validation, comparison, and implementation without acting as workflow code, a migration, or a seed.
 *
 * Notes:
 * - A declared relationship and a request for workspace access remain claims for review.
 * - Proposed data follows the field contracts of the target collection.
 */

$created_at = '2026-07-20T10:31:38Z';
$updated_at = '2026-07-21T04:24:17Z';
$contribution_main_default = [
    'is_workspace_access_requested' => false,
    'status_contribution' => 'DFT',
];

$contribution_main = [
    '_id' => 'Co7K2pQ9xR4tV8zN', // Canonical 16-character contribution identifier.
    'type_contribution' => 'ADD', // Contribution action such as add or correct.
    'target_collection' => 'establishment_main', // Collection that owns the proposed record fields.
    'target_record_id' => null, // Existing target identifier; null for a new record proposal.
    'submitted_by_user_id' => 'U2pR7vX4kT9mC5qL', // User that created the proposal.
    'proposed_data' => ['display_name' => ['eng' => ['text' => 'Sample Wellness Spa']]], // Validated target fields proposed by the contributor.
    'type_establishment_relationship' => 'MGR', // Submitter-declared establishment relationship.
    'type_practitioner_relationship' => null, // Submitter-declared practitioner relationship when applicable.
    'is_workspace_access_requested' => false, // Whether the submitter also requests scoped workspace access.
    'relationship_note' => 'I manage the establishment profile.', // Optional explanation of the declared relationship.
    'status_contribution' => 'DFT', // Current contribution workflow state.
    'submitted_at' => null, // UTC time when the draft entered review.
    'reviewer_user_id' => null, // User assigned to review the contribution.
    'reviewed_at' => null, // UTC time of the review decision.
    'decision_note' => null, // Reviewer explanation or requested changes.
    'created_at' => '2026-07-21T04:08:58Z', // UTC record creation time.
    'updated_at' => '2026-07-21T04:08:58Z', // UTC record update time.
];

$contribution_main_field_order = [
    '_id',
    'type_contribution',
    'target_collection',
    'target_record_id',
    'submitted_by_user_id',
    'proposed_data',
    'type_establishment_relationship',
    'type_practitioner_relationship',
    'is_workspace_access_requested',
    'relationship_note',
    'status_contribution',
    'submitted_at',
    'reviewer_user_id',
    'reviewed_at',
    'decision_note',
    'created_at',
    'updated_at',
];

$contribution_main_embedded_structure = [
    'proposed_data' => [
        'field_name' => 'validated target value', // Dynamic field governed by target_collection.
    ],
];

$contribution_main_field_property = [
    '_id' => ['field_label' => 'Contribution ID', 'field_description' => 'Canonical application-generated 16-character identifier.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'type_contribution' => ['field_label' => 'Contribution Type', 'field_description' => 'Action proposed against the target collection.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'target_collection' => ['field_label' => 'Target Collection', 'field_description' => 'Collection that validates and owns proposed_data.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'target_record_id' => ['field_label' => 'Target Record ID', 'field_description' => 'Existing target identifier for target-bound proposals.', 'type_data' => 'S', 'is_relational' => true, 'is_indexed' => true],
    'submitted_by_user_id' => ['field_label' => 'Submitted By User ID', 'field_description' => 'user_main._id of the contributor.', 'type_data' => 'S', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'proposed_data' => ['field_label' => 'Proposed Data', 'field_description' => 'Validated proposed fields using the target collection contracts.', 'type_data' => 'O', 'is_mandatory' => true],
    'type_establishment_relationship' => ['field_label' => 'Establishment Relationship Type', 'field_description' => 'Submitter declaration only; verification remains separate.', 'type_data' => 'S'],
    'type_practitioner_relationship' => ['field_label' => 'Practitioner Relationship Type', 'field_description' => 'Submitter declaration only; verification remains separate.', 'type_data' => 'S'],
    'is_workspace_access_requested' => ['field_label' => 'Workspace Access Requested', 'field_description' => 'Whether separately reviewed scoped access is requested.', 'type_data' => 'B'],
    'relationship_note' => ['field_label' => 'Relationship Note', 'field_description' => 'Optional explanation supporting the declared relationship.', 'type_data' => 'S', 'max_character' => 1000],
    'status_contribution' => ['field_label' => 'Contribution Status', 'field_description' => 'Current proposal workflow state.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'submitted_at' => ['field_label' => 'Submitted At', 'field_description' => 'UTC time the proposal entered review.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_indexed' => true],
    'reviewer_user_id' => ['field_label' => 'Reviewer User ID', 'field_description' => 'user_main._id assigned to review.', 'type_data' => 'S', 'is_relational' => true, 'is_indexed' => true],
    'reviewed_at' => ['field_label' => 'Reviewed At', 'field_description' => 'UTC time of the review decision.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'decision_note' => ['field_label' => 'Decision Note', 'field_description' => 'Reviewer explanation or requested changes.', 'type_data' => 'S', 'max_character' => 2000],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC record update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];

$contribution_main_subfield_property = [
    'proposed_data.*' => ['field_label' => 'Proposed Field', 'field_description' => 'Dynamic field validated by target_collection before acceptance.'],
];

$contribution_main_index_list = [
    [
        'index_key' => 'primary',
        'index_name' => '_id_',
        'type_index' => 'STD',
        'is_unique' => true,
        'is_sparse' => false,
        'index_field_list' => [
            ['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10],
        ],
        'sort_order' => 10,
    ],
];

$contribution_main_boundary = [
    'owns' => ['proposal metadata, proposed_data snapshot, declarations, workflow state, and decision fields'],
    'references' => ['user_main through submitter and reviewer IDs', 'the collection and record identified by target_collection and target_record_id'],
    'does_not_own' => ['accepted target record', 'relationship verification', 'workspace access grant'],
];

return [
    'contribution_main_default' => $contribution_main_default,
    'contribution_main' => $contribution_main,
    'contribution_main_field_order' => $contribution_main_field_order,
    'contribution_main_embedded_structure' => $contribution_main_embedded_structure,
    'contribution_main_field_property' => $contribution_main_field_property,
    'contribution_main_subfield_property' => $contribution_main_subfield_property,
    'contribution_main_index_list' => $contribution_main_index_list,
    'contribution_main_boundary' => $contribution_main_boundary,
];
