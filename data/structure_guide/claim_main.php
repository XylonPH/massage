<?php
/**
 * Title: Massage Nexus Claim Structure Guide
 * Version: 2.00
 * Collection: claim_main
 * Description: Stores one reviewed claim of relationship and requested authority over a supported target.
 * Purpose: Separates declared relationship, evidence, verification, decision, dispute, revocation, and resulting workspace access.
 */
$created_at = '2026-07-20T12:00:00Z';
$updated_at = '2026-07-21T08:49:01Z';
$claim_main_default = ['claimant_organization_id' => null, 'requested_permission_code_list' => [], 'document_id_list' => [], 'record_verification_id_list' => [], 'status_claim' => 'PND', 'resulting_access_assignment_id' => null, 'status_record_lifecycle' => 'ACT'];
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
    'resulting_access_assignment_id' => 'Aa7K2pQ9xR4tV8zN', // Separately authorized access assignment.
    'status_record_lifecycle' => 'ACT', // Database lifecycle.
    'created_at' => $created_at, // UTC creation time.
    'updated_at' => $updated_at, // UTC update time.
];
$claim_main_field_order = array_keys($claim_main);
$claim_main_embedded_structure = [];
$claim_main_field_property = [];
foreach ($claim_main as $field_name => $sample_value) {
    $claim_main_field_property[$field_name] = ['field_label' => ucwords(str_replace('_', ' ', $field_name)), 'field_description' => 'Claim field: ' . str_replace('_', ' ', $field_name) . '.', 'type_data' => is_array($sample_value) ? 'A' : 'S'];
}
$claim_main_field_property['_id']['is_mandatory'] = true;
$claim_main_field_property['target_collection']['is_mandatory'] = true;
$claim_main_field_property['target_record_id']['is_mandatory'] = true;
$claim_main_field_property['claimant_user_id']['is_mandatory'] = true;
$claim_main_subfield_property = [];
$claim_main_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'target_status', 'index_name' => 'ix_claim_main_target_status_submitted', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'target_collection', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'target_record_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_claim', 'type_index_mode' => 'ASC', 'sort_order' => 30], ['field_name' => 'submitted_at', 'type_index_mode' => 'DESC', 'sort_order' => 40]], 'sort_order' => 20],
    ['index_key' => 'claimant_status', 'index_name' => 'ix_claim_main_claimant_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'claimant_user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_claim', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 30],
];
$claim_main_boundary = ['owns' => ['declared relationship, requested authority, evidence references, decision, dispute, revocation, and access-assignment reference'], 'references' => ['claim target, user, organization, document_main, record_verification, and access_assignment'], 'does_not_own' => ['target facts, binary evidence, verification details, or workspace permissions; approval alone never grants access']];
return compact('claim_main_default', 'claim_main', 'claim_main_field_order', 'claim_main_embedded_structure', 'claim_main_field_property', 'claim_main_subfield_property', 'claim_main_index_list', 'claim_main_boundary');
