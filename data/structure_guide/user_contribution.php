<?php
/**
 * Title: Massage Nexus User Contribution Structure Guide
 * Version: 1.10
 * Collection: user_contribution
 * Description: Stores one user or staff proposal to add, correct, translate, enrich, or update a supported record.
 * Purpose: Documents the accepted shared user-contribution proposal, review, and decision boundary without acting as runtime workflow code or a migration.
 *
 * Notes:
 * - Runtime code currently uses the legacy contribution_main collection until a tested migration is implemented.
 * - proposed_data follows the target collection's field contracts.
 * - A relationship declaration or access request never grants authority without separate review.
 */

$created_at = '2026-07-20T10:31:38Z';
$updated_at = '2026-07-22T15:35:00Z';

$user_contribution_default = [
    'is_user_access_requested' => false,
    'status_user_contribution' => 'DFT',
    'revision_number' => 1,
    'duplicate_candidate_establishment_id_list' => [],
    'duplicate_acknowledged' => false,
    'is_visit_requested' => false,
];

$user_contribution = [
    '_id' => 'Uc7K2pQ9xR4tV8zN', // Canonical 16-character contribution identifier.
    'type_contribution' => 'COR', // Proposal action such as add, correct, translate, or enrich.
    'target_collection' => 'establishment_main', // Collection governing proposed_data.
    'target_record_id' => 'Es7K2pQ9xR4tV8zN', // Existing target; null for a new-record proposal.
    'submitted_by_user_id' => 'U2pR7vX4kT9mC5qL', // User creating the proposal.
    'proposed_data' => ['street_address' => '123 Corrected Street'], // Validated proposed target fields.
    'type_establishment_relationship' => 'MGR', // Optional submitter-declared establishment relationship.
    'type_practitioner_relationship' => null, // Optional submitter-declared practitioner relationship.
    'is_user_access_requested' => false, // Whether separate scoped user access is also requested.
    'relationship_note' => 'I manage the listed branch.', // Optional declaration explanation.
    'submission_note' => 'Photos available on request.', // Optional free-text note to the reviewer, separate from the relationship note.
    'duplicate_candidate_establishment_id_list' => ['Es7K2pQ9xR4tV8zN'], // Establishment IDs the duplicate check flagged at submission time.
    'duplicate_acknowledged' => true, // Whether the submitter confirmed this is not one of the flagged candidates.
    'is_visit_requested' => false, // Whether the submitter requested an in-person verification visit.
    'visit_preferred_time_note' => null, // Optional free-text preferred time when is_visit_requested is true.
    'status_user_contribution' => 'SUB', // Current proposal workflow state.
    'submitted_at' => '2026-07-22T02:51:15Z', // UTC review-submission time.
    'reviewer_user_id' => null, // Assigned reviewer.
    'reviewed_at' => null, // UTC decision time.
    'decision_note' => null, // Reviewer explanation or change request.
    'resulting_revision_id' => null, // Accepted record_revision or specialized revision.
    'revision_number' => 1, // Optimistic-concurrency token.
    'created_at' => '2026-07-22T02:51:15Z', // UTC record creation time.
    'updated_at' => '2026-07-22T02:51:15Z', // UTC latest accepted update time.
];

$user_contribution_field_order = [
    '_id', 'type_contribution', 'target_collection', 'target_record_id', 'submitted_by_user_id',
    'proposed_data', 'type_establishment_relationship', 'type_practitioner_relationship',
    'is_user_access_requested', 'relationship_note', 'submission_note', 'duplicate_candidate_establishment_id_list', 'duplicate_acknowledged', 'is_visit_requested', 'visit_preferred_time_note', 'status_user_contribution', 'submitted_at',
    'reviewer_user_id', 'reviewed_at', 'decision_note', 'resulting_revision_id', 'revision_number',
    'created_at', 'updated_at',
];

$user_contribution_embedded_structure = [
    'proposed_data' => ['field_name' => 'validated target value'],
];

$user_contribution_field_property = [
    '_id' => ['field_label' => 'User Contribution ID', 'field_description' => 'Canonical application-generated 16-character identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'type_contribution' => ['field_label' => 'Contribution Type', 'field_description' => 'Controlled action proposed against the target.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true, 'is_indexed' => true],
    'target_collection' => ['field_label' => 'Target Collection', 'field_description' => 'Allowlisted collection that validates and owns the proposed values.', 'type_data' => 'S', 'type_field' => 'DDL', 'min_character' => 3, 'max_character' => 100, 'is_mandatory' => true, 'is_indexed' => true],
    'target_record_id' => ['field_label' => 'Target Record', 'field_description' => 'Existing target identifier, omitted for a new-record proposal.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_indexed' => true],
    'submitted_by_user_id' => ['field_label' => 'Submitted By User', 'field_description' => 'user_main._id that created the proposal.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'proposed_data' => ['field_label' => 'Proposed Data', 'field_description' => 'Proposed field values validated by the target collection contract.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_mandatory' => true],
    'type_establishment_relationship' => ['field_label' => 'Declared Establishment Relationship', 'field_description' => 'Optional submitter declaration that requires independent verification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'type_practitioner_relationship' => ['field_label' => 'Declared Practitioner Relationship', 'field_description' => 'Optional submitter declaration that requires independent verification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'is_user_access_requested' => ['field_label' => 'User Access Requested', 'field_description' => 'Whether the submitter requests a separately reviewed scoped access grant.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false],
    'relationship_note' => ['field_label' => 'Relationship Note', 'field_description' => 'Optional explanation supporting a declared relationship or access request.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 1000],
    'submission_note' => ['field_label' => 'Submission Note', 'field_description' => 'Optional free-text note to the reviewer, separate from the relationship note.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 2000],
    'duplicate_candidate_establishment_id_list' => ['field_label' => 'Duplicate Candidates', 'field_description' => 'Establishment references the duplicate check flagged as possible matches at submission time.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true],
    'duplicate_acknowledged' => ['field_label' => 'Duplicate Acknowledged', 'field_description' => 'Whether the submitter confirmed the proposal is not one of the flagged duplicate candidates.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false],
    'is_visit_requested' => ['field_label' => 'Visit Requested', 'field_description' => 'Whether the submitter requested an in-person verification visit.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false],
    'visit_preferred_time_note' => ['field_label' => 'Visit Preferred Time', 'field_description' => 'Optional free-text preferred time for the requested visit.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 500],
    'status_user_contribution' => ['field_label' => 'User Contribution Status', 'field_description' => 'Current proposal review and lifecycle state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'DFT', 'is_mandatory' => true, 'is_indexed' => true],
    'submitted_at' => ['field_label' => 'Submitted At', 'field_description' => 'UTC time the proposal entered review.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_indexed' => true],
    'reviewer_user_id' => ['field_label' => 'Reviewer User', 'field_description' => 'user_main._id assigned to review the proposal.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_indexed' => true],
    'reviewed_at' => ['field_label' => 'Reviewed At', 'field_description' => 'UTC time of the latest review decision.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'decision_note' => ['field_label' => 'Decision Note', 'field_description' => 'Reviewer explanation, rejection reason, or change request.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 2000],
    'resulting_revision_id' => ['field_label' => 'Resulting Revision', 'field_description' => 'Accepted record_revision or specialized revision produced from the proposal.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC time when the proposal record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC time of the latest accepted proposal update.', 'type_data' => 'S', 'type_field' => 'DTS'],
];

$user_contribution_subfield_property = [
    'proposed_data.*' => ['field_label' => 'Proposed Field', 'field_description' => 'Dynamic target field validated by target_collection before acceptance.', 'type_data' => 'S', 'type_field' => 'JSE'],
];

$user_contribution_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'submitter_status', 'index_name' => 'ix_user_contribution_submitter_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'submitted_by_user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_user_contribution', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'submitted_at', 'type_index_mode' => 'DESC', 'sort_order' => 30]], 'sort_order' => 20],
    ['index_key' => 'target_status', 'index_name' => 'ix_user_contribution_target_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => true, 'index_field_list' => [['field_name' => 'target_collection', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'target_record_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_user_contribution', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 30],
];

$user_contribution_boundary = [
    'owns' => ['proposal metadata, proposed-data snapshot, submitter declarations, review workflow, and decision references'],
    'reference_field_list' => ['target_record_id', 'submitted_by_user_id', 'reviewer_user_id', 'resulting_revision_id'],
    'does_not_own' => ['accepted target record', 'factual relationship verification', 'user access grant', 'record revision', 'reward or reputation event'],
];

return [
    'user_contribution_default' => $user_contribution_default,
    'user_contribution' => $user_contribution,
    'user_contribution_field_order' => $user_contribution_field_order,
    'user_contribution_embedded_structure' => $user_contribution_embedded_structure,
    'user_contribution_field_property' => $user_contribution_field_property,
    'user_contribution_subfield_property' => $user_contribution_subfield_property,
    'user_contribution_index_list' => $user_contribution_index_list,
    'user_contribution_boundary' => $user_contribution_boundary,
];
