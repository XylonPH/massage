<?php
/**
 * Title: Massage Nexus Research Observation Structure Guide
 * Version: 1.30
 * Collection: research_observation
 * Description: Stores one sourced assertion or observation independently from the accepted value of a target record.
 * Purpose: Preserves exact wording, uncertainty, historical context, mapping confidence, contradictions, visibility, and review state for later verification or contribution workflows.
 */

$created_at = '2026-07-21T08:15:45Z';
$updated_at = '2026-07-22T02:51:15Z';
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
    'user_contribution_id' => null, // user_contribution created from an accepted candidate fact.
    'internal_note' => 'Do not publish as confirmed operating status without corroboration.', // Restricted research guidance.
    'created_at' => $created_at, // UTC record creation time.
    'created_by_user_id' => 'U2pR7vX4kT9mC5qL', // Researcher or extraction-system actor.
    'updated_at' => $updated_at, // UTC record update time.
    'status_record_lifecycle' => 'ACT', // Database lifecycle state.
];
$research_observation_field_order = ['_id', 'target_collection', 'target_record_id', 'target_field_path', 'related_record_list', 'type_observation', 'original_statement', 'normalized_summary', 'source_id', 'source_posted_at', 'observation_at', 'effective_at', 'type_date_precision', 'type_date_qualifier', 'is_firsthand', 'is_hearsay', 'level_confidence_extraction', 'level_confidence_mapping', 'corroboration_count', 'status_review', 'validation_required', 'type_verification_recommended', 'visibility_scope', 'record_verification_id', 'moderation_id', 'user_contribution_id', 'internal_note', 'created_at', 'created_by_user_id', 'updated_at', 'status_record_lifecycle'];
$research_observation_embedded_structure = ['related_record_list' => ['target_collection' => 'practitioner_main', 'target_record_id' => 'Pr7K2pQ9xR4tV8zN']];
$research_observation_field_property = [
    '_id' => ['field_label' => 'Observation ID', 'field_description' => 'Canonical sourced-observation identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_indexed' => true],
    'target_collection' => ['field_label' => 'Target Collection', 'field_description' => 'Collection whose fact the observation discusses.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_indexed' => true],
    'target_record_id' => ['field_label' => 'Target Record', 'field_description' => 'Specific record whose fact the observation discusses.', 'type_data' => 'S', 'type_field' => 'REF', 'is_indexed' => true],
    'target_field_path' => ['field_label' => 'Target Field Path', 'field_description' => 'Optional mapped field path for the candidate fact.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'related_record_list' => ['field_label' => 'Related Record List', 'field_description' => 'Other records mentioned by the assertion.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'type_observation' => ['field_label' => 'Observation Type', 'field_description' => 'Controlled category of sourced assertion.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'original_statement' => ['field_label' => 'Original Statement', 'field_description' => 'Exact limited excerpt or original research field note.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'normalized_summary' => ['field_label' => 'Normalized Summary', 'field_description' => 'Neutral candidate-fact summary.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'source_id' => ['field_label' => 'Research Source', 'field_description' => 'Authoritative research-source reference.', 'type_data' => 'S', 'type_field' => 'REF', 'is_indexed' => true],
    'source_posted_at' => ['field_label' => 'Source Posted At', 'field_description' => 'UTC time the source statement was posted.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'observation_at' => ['field_label' => 'Observation At', 'field_description' => 'Time the underlying observation reportedly occurred.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'effective_at' => ['field_label' => 'Effective At', 'field_description' => 'Claimed effective time when supported.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'type_date_precision' => ['field_label' => 'Date Precision', 'field_description' => 'Controlled precision of the claimed date.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'type_date_qualifier' => ['field_label' => 'Date Qualifier', 'field_description' => 'Controlled exactness qualifier for timing.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'is_firsthand' => ['field_label' => 'Firsthand', 'field_description' => 'Whether the source claims direct observation.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'is_hearsay' => ['field_label' => 'Hearsay', 'field_description' => 'Whether the source repeats another person\'s account.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'level_confidence_extraction' => ['field_label' => 'Extraction Confidence', 'field_description' => 'Confidence that the statement was extracted correctly.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'level_confidence_mapping' => ['field_label' => 'Mapping Confidence', 'field_description' => 'Confidence that the assertion maps to the target fact.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'corroboration_count' => ['field_label' => 'Corroboration Count', 'field_description' => 'Count of independently reviewed supporting observations.', 'type_data' => 'I', 'type_field' => 'NMB'],
    'status_review' => ['field_label' => 'Review Status', 'field_description' => 'Human review state of the observation.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_indexed' => true],
    'validation_required' => ['field_label' => 'Validation Required', 'field_description' => 'Whether authoritative confirmation remains required.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'type_verification_recommended' => ['field_label' => 'Recommended Verification Type', 'field_description' => 'Recommended verification procedure for the candidate fact.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'visibility_scope' => ['field_label' => 'Visibility Scope', 'field_description' => 'Maximum permitted audience for the observation.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'record_verification_id' => ['field_label' => 'Verification Record', 'field_description' => 'Verification result produced from review.', 'type_data' => 'S', 'type_field' => 'REF'],
    'moderation_id' => ['field_label' => 'Moderation Case', 'field_description' => 'Moderation case for safety or policy review.', 'type_data' => 'S', 'type_field' => 'REF'],
    'user_contribution_id' => ['field_label' => 'User Contribution', 'field_description' => 'user_contribution created from an accepted candidate fact.', 'type_data' => 'S', 'type_field' => 'REF'],
    'internal_note' => ['field_label' => 'Internal Observation Note', 'field_description' => 'Restricted research guidance.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC creation time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'created_by_user_id' => ['field_label' => 'Created By User', 'field_description' => 'Researcher or extraction-system actor reference.', 'type_data' => 'S', 'type_field' => 'REF'],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle independent from review status.', 'type_data' => 'S', 'type_field' => 'DDL'],
];
$research_observation_subfield_property = [
    'related_record_list.target_collection' => ['field_label' => 'Related Target Collection', 'field_description' => 'Collection of a related mentioned record.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'related_record_list.target_record_id' => ['field_label' => 'Related Target Record ID', 'field_description' => 'Identifier of a related mentioned record.', 'type_data' => 'S', 'type_field' => 'REF'],
];
$research_observation_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'target_review', 'index_name' => 'ix_research_observation_target_review', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'target_collection', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'target_record_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_review', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
    ['index_key' => 'source_lookup', 'index_name' => 'ix_research_observation_source', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'source_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 30],
];
$research_observation_boundary = ['owns' => ['source assertion, exact excerpt or field note, normalized candidate fact, uncertainty, mapping, review, and workflow references'], 'reference_field_list' => ['target_record_id', 'related_record_list.record_id', 'source_id', 'record_verification_id', 'moderation_id', 'user_contribution_id', 'created_by_user_id'], 'does_not_own' => ['accepted target value', 'source identity', 'verification result', 'moderation decision', 'restricted evidence binary']];
return ['research_observation_default' => $research_observation_default, 'research_observation' => $research_observation, 'research_observation_field_order' => $research_observation_field_order, 'research_observation_embedded_structure' => $research_observation_embedded_structure, 'research_observation_field_property' => $research_observation_field_property, 'research_observation_subfield_property' => $research_observation_subfield_property, 'research_observation_index_list' => $research_observation_index_list, 'research_observation_boundary' => $research_observation_boundary];
