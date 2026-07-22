<?php
/**
 * Title: Massage Nexus User Policy Structure Guide
 * Version: 1.00
 * Collection: user_policy
 * Description: Stores one immutable versioned user decision for a published legal or policy document.
 * Purpose: Preserves acceptance, acknowledgment, optional consent, refusal, and withdrawal evidence independently from current policy-gate summaries.
 *
 * Notes:
 * - Records are append-only; correction or withdrawal creates another user_policy record.
 * - Published legal text belongs to legal_main.
 */

$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_policy_default = ['is_required_action' => false, 'status_user_policy' => 'REC'];
$user_policy = [
    '_id' => 'Up7K2pQ9xR4tV8zN', // Canonical policy-decision identifier.
    'user_id' => 'U2pR7vX4kT9mC5qL', // Deciding user.
    'legal_main_id' => 'Lg7K2pQ9xR4tV8zN', // Exact legal_main version presented.
    'version_identifier' => '2.1.0', // Immutable version snapshot.
    'language_id' => 3049, // Presented language.
    'type_policy_action' => 'ACP', // Accept, acknowledge, grant, refuse, or withdraw.
    'is_required_action' => true, // Whether access required the decision.
    'presented_at' => '2026-07-22T02:45:00Z', // UTC presentation time.
    'decided_at' => '2026-07-22T02:51:15Z', // UTC decision time.
    'evidence_hash' => 'sha256:example-only', // Integrity hash for approved evidence metadata.
    'supersedes_user_policy_id' => null, // Earlier decision superseded by this event.
    'status_user_policy' => 'REC', // Recorded decision lifecycle state.
    'created_at' => '2026-07-22T02:51:15Z', // Immutable record creation time.
];
$user_policy_field_order = ['_id', 'user_id', 'legal_main_id', 'version_identifier', 'language_id', 'type_policy_action', 'is_required_action', 'presented_at', 'decided_at', 'evidence_hash', 'supersedes_user_policy_id', 'status_user_policy', 'created_at'];
$user_policy_embedded_structure = [];
$user_policy_field_property = [
    '_id' => ['field_label' => 'User Policy ID', 'field_description' => 'Canonical application-generated 16-character identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'user_main._id that made the decision.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'legal_main_id' => ['field_label' => 'Legal Document', 'field_description' => 'Exact legal_main record presented for the decision.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'version_identifier' => ['field_label' => 'Version Identifier', 'field_description' => 'Immutable presented legal-document version snapshot.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 80, 'is_mandatory' => true],
    'language_id' => ['field_label' => 'Presented Language', 'field_description' => 'common_reference language identifier used for presentation.', 'type_data' => 'I', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true],
    'type_policy_action' => ['field_label' => 'Policy Action Type', 'field_description' => 'Acceptance, acknowledgment, grant, refusal, or withdrawal action.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true, 'is_indexed' => true],
    'is_required_action' => ['field_label' => 'Required Action', 'field_description' => 'Whether the requested feature required this decision.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false],
    'presented_at' => ['field_label' => 'Presented At', 'field_description' => 'UTC time the exact document was presented.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'decided_at' => ['field_label' => 'Decided At', 'field_description' => 'UTC time the user made the decision.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true, 'is_indexed' => true],
    'evidence_hash' => ['field_label' => 'Evidence Hash', 'field_description' => 'Integrity hash for approved minimal decision evidence metadata.', 'type_data' => 'S', 'type_field' => 'HDN', 'max_character' => 160, 'visibility_scope' => 'PRV'],
    'supersedes_user_policy_id' => ['field_label' => 'Supersedes User Policy', 'field_description' => 'Earlier user_policy decision superseded by this event.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'status_user_policy' => ['field_label' => 'User Policy Status', 'field_description' => 'Recorded, invalidated, or legally held decision state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'REC', 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC immutable record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
];
$user_policy_subfield_property = [];
$user_policy_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'user_document_action', 'index_name' => 'ix_user_policy_user_document_action', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'legal_main_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'type_policy_action', 'type_index_mode' => 'ASC', 'sort_order' => 30], ['field_name' => 'decided_at', 'type_index_mode' => 'DESC', 'sort_order' => 40]], 'sort_order' => 20],
];
$user_policy_boundary = ['owns' => ['immutable user policy or consent decision and minimal evidence metadata'], 'reference_field_list' => ['user_id', 'legal_main_id', 'language_id', 'supersedes_user_policy_id'], 'does_not_own' => ['published legal text', 'current user preference', 'policy-gate cache', 'cookie binary or browser storage']];
return ['user_policy_default' => $user_policy_default, 'user_policy' => $user_policy, 'user_policy_field_order' => $user_policy_field_order, 'user_policy_embedded_structure' => $user_policy_embedded_structure, 'user_policy_field_property' => $user_policy_field_property, 'user_policy_subfield_property' => $user_policy_subfield_property, 'user_policy_index_list' => $user_policy_index_list, 'user_policy_boundary' => $user_policy_boundary];
