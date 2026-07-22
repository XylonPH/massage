<?php
/**
 * Title: Massage Nexus Claim Structure Guide
 * Version: 2.20
 * Collection: claim_main
 * Description: Stores one reviewed claim of relationship and requested authority over a supported target.
 * Purpose: Separates declared relationship, evidence, verification, decision, dispute, revocation, and resulting user access.
 */
$created_at = '2026-07-20T12:00:00Z';
$updated_at = '2026-07-22T02:51:15Z';
$claim_main_default = ['claimant_organization_id' => null, 'requested_permission_code_list' => [], 'document_id_list' => [], 'record_verification_id_list' => [], 'status_claim' => 'PND', 'resulting_user_access_id' => null, 'status_record_lifecycle' => 'ACT'];
$claim_main = [
    '_id' => 'Cl7K2pQ9xR4tV8zN', // Canonical claim identifier.
    'target_collection' => 'establishment_main', // Claimed target collection.
    'target_record_id' => 'Es7K2pQ9xR4tV8zN', // Claimed target identifier.
    'claimant_user_id' => 'U5rK8mP2xN7qL4vA', // Claimant account.
    'claimant_organization_id' => 'Or8K2pQ9xR4tV7zN', // Optional represented organization.
    'type_claim_relationship' => 'OWN', // Declared relationship; not proof by itself.
    'requested_role_workspace' => 'operator', // Requested role bundle.
    'requested_permission_code_list' => ['establishment.edit'], // Requested additive authority.
    'claim_statement' => 'I am authorized to administer this establishment.', // Claimant statement.
    'submitted_at' => '2026-07-20T12:00:00Z', // Submission time.
    'document_id_list' => ['Do8K2pQ9xR4tV7zN'], // Protected evidence documents.
    'record_verification_id_list' => ['Vr8K2pQ9xR4tV7zN'], // Verification processes and results.
    'status_claim' => 'APR', // Claim workflow and decision state.
    'reviewer_user_id' => 'U2pR7vX4kT9mC5qL', // Authorized reviewer.
    'decided_at' => '2026-07-20T14:45:00Z', // Decision time.
    'decision_reason' => 'Authority supported by reviewed evidence.', // Minimum safe decision reason.
    'dispute_opened_at' => null, // Dispute start when applicable.
    'dispute_resolved_at' => null, // Dispute resolution when applicable.
    'revoked_at' => null, // Authority revocation time.
    'revoked_by_user_id' => null, // Revoking actor.
    'revocation_reason' => null, // Revocation reason.
    'resulting_user_access_id' => 'Ua7K2pQ9xR4tV8zN', // Separately authorized user access grant.
    'status_record_lifecycle' => 'ACT', // Database lifecycle.
    'created_at' => $created_at, // UTC creation time.
    'updated_at' => $updated_at, // UTC update time.
];
$claim_main_field_order = ['_id', 'target_collection', 'target_record_id', 'claimant_user_id', 'claimant_organization_id', 'type_claim_relationship', 'requested_role_workspace', 'requested_permission_code_list', 'claim_statement', 'submitted_at', 'document_id_list', 'record_verification_id_list', 'status_claim', 'reviewer_user_id', 'decided_at', 'decision_reason', 'dispute_opened_at', 'dispute_resolved_at', 'revoked_at', 'revoked_by_user_id', 'revocation_reason', 'resulting_user_access_id', 'status_record_lifecycle', 'created_at', 'updated_at'];
$claim_main_embedded_structure = [];
$claim_main_field_property = [
    '_id' => ['field_label' => 'Claim ID', 'field_description' => 'Canonical identifier for the claim review record.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_unique' => true, 'is_indexed' => true],
    'target_collection' => ['field_label' => 'Target Collection', 'field_description' => 'Supported collection containing the record over which authority is claimed.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true, 'is_indexed' => true],
    'target_record_id' => ['field_label' => 'Target Record', 'field_description' => 'Identifier of the specific establishment, practitioner, or other supported claim target.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'claimant_user_id' => ['field_label' => 'Claimant User', 'field_description' => 'User account that submitted the claim.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'claimant_organization_id' => ['field_label' => 'Claimant Organization', 'field_description' => 'Optional organization the claimant states they represent.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'type_claim_relationship' => ['field_label' => 'Claimed Relationship Type', 'field_description' => 'Controlled relationship asserted by the claimant; this declaration is not proof by itself.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
    'requested_role_workspace' => ['field_label' => 'Requested Workspace Role', 'field_description' => 'Requested responsibility bundle; approval remains subject to independent authorization.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'requested_permission_code_list' => ['field_label' => 'Requested Permission Codes', 'field_description' => 'Specific additive permissions requested beyond or within the role bundle.', 'type_data' => 'A', 'type_field' => 'TAG', 'default_value' => []],
    'claim_statement' => ['field_label' => 'Claim Statement', 'field_description' => 'Claimant-authored explanation of the asserted authority and relationship.', 'type_data' => 'S', 'type_field' => 'TXA', 'is_mandatory' => true],
    'submitted_at' => ['field_label' => 'Submitted At', 'field_description' => 'UTC time when the claim entered review.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true, 'is_indexed' => true],
    'document_id_list' => ['field_label' => 'Evidence Documents', 'field_description' => 'Protected document references submitted or collected as claim evidence.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true, 'default_value' => [], 'visibility_scope' => 'PRV'],
    'record_verification_id_list' => ['field_label' => 'Verification Records', 'field_description' => 'Verification processes and results used when reviewing the claim.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true, 'default_value' => []],
    'status_claim' => ['field_label' => 'Claim Status', 'field_description' => 'Controlled workflow and decision state of the claim.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'PND', 'is_indexed' => true],
    'reviewer_user_id' => ['field_label' => 'Reviewer User', 'field_description' => 'Authorized user responsible for the current or final claim decision.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'decided_at' => ['field_label' => 'Decided At', 'field_description' => 'UTC time when the current claim decision was recorded.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'decision_reason' => ['field_label' => 'Decision Reason', 'field_description' => 'Minimum safe explanation supporting approval, rejection, return, or another decision.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'dispute_opened_at' => ['field_label' => 'Dispute Opened At', 'field_description' => 'UTC time when the claim decision or resulting authority was formally disputed.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'dispute_resolved_at' => ['field_label' => 'Dispute Resolved At', 'field_description' => 'UTC time when the related dispute was resolved.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revoked_at' => ['field_label' => 'Revoked At', 'field_description' => 'UTC time when previously accepted authority resulting from the claim was revoked.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revoked_by_user_id' => ['field_label' => 'Revoked By User', 'field_description' => 'Authorized user who recorded the revocation.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'revocation_reason' => ['field_label' => 'Revocation Reason', 'field_description' => 'Minimum safe explanation for the authority revocation.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'resulting_user_access_id' => ['field_label' => 'Resulting User Access', 'field_description' => 'Separately authorized user_access grant created after approval; the claim never grants access directly.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state independent from claim workflow status.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC time when the claim record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC time when the claim record was last changed.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
];
$claim_main_subfield_property = [];
$claim_main_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'target_status', 'index_name' => 'ix_claim_main_target_status_submitted', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'target_collection', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'target_record_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_claim', 'type_index_mode' => 'ASC', 'sort_order' => 30], ['field_name' => 'submitted_at', 'type_index_mode' => 'DESC', 'sort_order' => 40]], 'sort_order' => 20],
    ['index_key' => 'claimant_status', 'index_name' => 'ix_claim_main_claimant_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'claimant_user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_claim', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 30],
];
$claim_main_boundary = ['owns' => ['declared relationship, requested authority, evidence references, decision, dispute, revocation, and user-access reference'], 'reference_field_list' => ['target_record_id', 'claimant_user_id', 'claimant_organization_id', 'document_id_list', 'record_verification_id_list', 'reviewer_user_id', 'revoked_by_user_id', 'resulting_user_access_id'], 'does_not_own' => ['target facts', 'binary evidence', 'verification details', 'workspace permissions; approval alone never grants access']];
return ['claim_main_default' => $claim_main_default, 'claim_main' => $claim_main, 'claim_main_field_order' => $claim_main_field_order, 'claim_main_embedded_structure' => $claim_main_embedded_structure, 'claim_main_field_property' => $claim_main_field_property, 'claim_main_subfield_property' => $claim_main_subfield_property, 'claim_main_index_list' => $claim_main_index_list, 'claim_main_boundary' => $claim_main_boundary];
