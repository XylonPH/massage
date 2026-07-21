<?php
/**
 * Title: Massage Nexus Research Observation Structure Guide
 * Version: 1.00
 * Collection: research_observation
 * Description: Stores one sourced assertion or observation independently from the accepted value of a target record.
 * Purpose: Preserves exact wording, uncertainty, historical context, mapping confidence, contradictions, visibility, and review state for later verification or contribution workflows.
 */

$created_at = '2026-07-21T08:15:45Z';
$updated_at = '2026-07-21T08:15:45Z';
$research_observation_default = ['target_field_path' => null, 'related_record_list' => [], 'is_firsthand' => false, 'is_hearsay' => false, 'corroboration_count' => 0, 'validation_required' => true, 'visibility_scope' => 'PRV', 'status_review' => 'PND', 'status_record_lifecycle' => 'ACT'];
$research_observation = [
    '_id' => 'Ro7K2pQ9xR4tV8zN', // Canonical 16-character observation identifier.
    'target_collection' => 'establishment_main', // Collection whose fact is discussed.
    'target_record_id' => 'Es7K2pQ9xR4tV8zN', // Target record identifier.
    'target_field_path' => 'status_establishment', // Optional mapped field path.
    'related_record_list' => [['target_collection' => 'practitioner_main', 'target_record_id' => 'Pr7K2pQ9xR4tV8zN']], // Other records mentioned by the assertion.
    'type_observation' => 'STA', // Controlled observation category.
    'original_statement' => 'I visited last Friday and the spa appeared open.', // Exact limited excerpt or original field note.
    'normalized_summary' => 'A source reported seeing the establishment operating recently.', // Neutral candidate-fact summary.
    'source_id' => 'Sr7K2pQ9xR4tV8zN', // Authoritative research_source identifier.
    'source_posted_at' => '2026-07-18T09:00:00Z', // When the source statement was posted.
    'observation_at' => '2026-07-17T11:00:00Z', // When the underlying observation reportedly occurred.
    'effective_at' => null, // Claimed effective time; omitted when the source does not establish it.
    'type_date_precision' => 'D', // Precision of the claimed or observed date.
    'type_date_qualifier' => 'APP', // Exactness qualifier for uncertain timing.
    'is_firsthand' => true, // Whether the source claims direct observation.
    'is_hearsay' => false, // Whether the statement repeats another person's account.
    'level_confidence_extraction' => 'H', // Confidence that the statement was extracted correctly.
    'level_confidence_mapping' => 'M', // Confidence that it maps to the target and field.
    'corroboration_count' => 0, // Count of independently reviewed supporting observations.
    'status_review' => 'PND', // Human review state.
    'validation_required' => true, // Whether authoritative confirmation is still required.
    'type_verification_recommended' => 'OPS', // Recommended verification procedure or category.
    'visibility_scope' => 'PRV', // Audience rule; sensitive allegations default private.
    'record_verification_id' => null, // Resulting record_verification identifier when reviewed.
    'moderation_id' => null, // Moderation case when safety or policy review is required.
    'contribution_id' => null, // Contribution created from an accepted candidate fact.
    'internal_note' => 'Do not publish as confirmed operating status without corroboration.', // Restricted research guidance.
    'created_at' => $created_at, // UTC record creation time.
    'created_by_user_id' => 'U2pR7vX4kT9mC5qL', // Researcher or extraction-system actor.
    'updated_at' => $updated_at, // UTC record update time.
    'status_record_lifecycle' => 'ACT', // Database lifecycle state.
];
$research_observation_field_order = ['_id', 'target_collection', 'target_record_id', 'target_field_path', 'related_record_list', 'type_observation', 'original_statement', 'normalized_summary', 'source_id', 'source_posted_at', 'observation_at', 'effective_at', 'type_date_precision', 'type_date_qualifier', 'is_firsthand', 'is_hearsay', 'level_confidence_extraction', 'level_confidence_mapping', 'corroboration_count', 'status_review', 'validation_required', 'type_verification_recommended', 'visibility_scope', 'record_verification_id', 'moderation_id', 'contribution_id', 'internal_note', 'created_at', 'created_by_user_id', 'updated_at', 'status_record_lifecycle'];
$research_observation_embedded_structure = ['related_record_list' => ['target_collection' => 'practitioner_main', 'target_record_id' => 'Pr7K2pQ9xR4tV8zN']];
$research_observation_field_property = [];
foreach ($research_observation as $field_name => $sample_value) {
    $research_observation_field_property[$field_name] = ['field_label' => ucwords(str_replace('_', ' ', $field_name)), 'field_description' => 'Stored research observation property: ' . $field_name . '.', 'type_data' => is_array($sample_value) ? 'A' : (is_bool($sample_value) ? 'B' : (is_int($sample_value) ? 'I' : 'S'))];
}
$research_observation_field_property['_id']['is_mandatory'] = true;
$research_observation_field_property['target_collection']['is_mandatory'] = true;
$research_observation_field_property['target_record_id']['is_mandatory'] = true;
$research_observation_field_property['source_id']['is_mandatory'] = true;
$research_observation_subfield_property = [
    'related_record_list.target_collection' => ['field_label' => 'Related Target Collection', 'field_description' => 'Collection of a related mentioned record.', 'type_data' => 'S'],
    'related_record_list.target_record_id' => ['field_label' => 'Related Target Record ID', 'field_description' => 'Identifier of a related mentioned record.', 'type_data' => 'S'],
];
$research_observation_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'target_review', 'index_name' => 'ix_research_observation_target_review', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'target_collection', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'target_record_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_review', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
    ['index_key' => 'source_lookup', 'index_name' => 'ix_research_observation_source', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'source_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 30],
];
$research_observation_boundary = ['owns' => ['source assertion, exact excerpt or field note, normalized candidate fact, uncertainty, mapping, review, and workflow references'], 'references' => ['supported target records', 'research_source', 'record_verification', 'moderation_main', 'contribution_main', 'user_main'], 'does_not_own' => ['accepted target value', 'source identity', 'verification result', 'moderation decision', 'restricted evidence binary']];
return compact('research_observation_default', 'research_observation', 'research_observation_field_order', 'research_observation_embedded_structure', 'research_observation_field_property', 'research_observation_subfield_property', 'research_observation_index_list', 'research_observation_boundary');
