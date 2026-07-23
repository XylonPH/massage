<?php
/**
 * Title: Massage Nexus Survey Response Structure Guide
 * Version: 1.00
 * Collection: survey_response
 * Description: Stores one respondent's run-specific participation envelope and completion state.
 * Purpose: Provides efficient completion lookup, duplicate prevention, progress, consent, source, and lifecycle tracking without embedding a growing answer list.
 */
$created_at = '2026-07-23T15:55:27Z';
$updated_at = '2026-07-23T16:03:52Z';
$survey_response_default = ['user_id' => null, 'survey_import_batch_id' => null, 'answer_count' => 0, 'required_answer_count' => 0, 'progress_percent' => 0, 'consent_at' => null, 'submitted_at' => null, 'withdrawn_at' => null, 'status_survey_response' => 'PRG', 'revision_number' => 1];
$survey_response = [
    '_id' => 'SrpT8mK2xQ7vN4zL',
    'survey_run_id' => 'SrnQ4mV8xK2pT7zL',
    'user_id' => 'U2pR7vX4kT9mC5qL',
    'respondent_key' => 'USR:U2pR7vX4kT9mC5qL',
    'source_survey_response' => 'NAT',
    'survey_import_batch_id' => null,
    'answer_count' => 5,
    'required_answer_count' => 5,
    'progress_percent' => 100,
    'consent_at' => '2026-07-23T16:05:00Z',
    'started_at' => '2026-07-23T16:04:00Z',
    'last_answered_at' => '2026-07-23T16:08:00Z',
    'submitted_at' => '2026-07-23T16:08:05Z',
    'withdrawn_at' => null,
    'status_survey_response' => 'CMP',
    'revision_number' => 1,
    'created_at' => $created_at,
    'updated_at' => $updated_at,
];
$survey_response_field_order = ['_id', 'survey_run_id', 'user_id', 'respondent_key', 'source_survey_response', 'survey_import_batch_id', 'answer_count', 'required_answer_count', 'progress_percent', 'consent_at', 'started_at', 'last_answered_at', 'submitted_at', 'withdrawn_at', 'status_survey_response', 'revision_number', 'created_at', 'updated_at'];
$survey_response_embedded_structure = [];
$survey_response_field_property = [
    '_id' => ['field_label' => 'Survey Response ID', 'field_description' => 'Canonical application-generated identifier for one respondent participation envelope.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'survey_run_id' => ['field_label' => 'Survey Run', 'field_description' => 'Conducted survey run to which this response belongs.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'Registered respondent for identified or confidential native participation; omitted for approved anonymous external responses.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_indexed' => true],
    'respondent_key' => ['field_label' => 'Respondent Key', 'field_description' => 'Run-local non-public deduplication key, such as a user identifier key or external batch-row key; it must not contain contact details.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 3, 'max_character' => 180, 'is_mandatory' => true, 'is_indexed' => true],
    'source_survey_response' => ['field_label' => 'Survey Response Source', 'field_description' => 'Native survey, Community Pulse, Microsoft Forms, paper, or controlled administrative capture channel.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
    'survey_import_batch_id' => ['field_label' => 'Survey Import Batch', 'field_description' => 'External import batch that created this response, omitted for direct native participation.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_indexed' => true],
    'answer_count' => ['field_label' => 'Answer Count', 'field_description' => 'Rebuildable count of current saved answers for efficient progress display.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0, 'default_value' => 0],
    'required_answer_count' => ['field_label' => 'Required Answer Count', 'field_description' => 'Snapshot of required questions for the exact revision used by this response.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0, 'default_value' => 0],
    'progress_percent' => ['field_label' => 'Progress Percent', 'field_description' => 'Rebuildable whole-percent completion projection from zero through one hundred.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0, 'max_number' => 100, 'default_value' => 0],
    'consent_at' => ['field_label' => 'Consent At', 'field_description' => 'UTC timestamp of explicit survey-specific consent when the run requires it; ordinary platform participation need not fabricate this value.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'started_at' => ['field_label' => 'Started At', 'field_description' => 'UTC timestamp when this response first saved an answer or entered the form.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true, 'is_indexed' => true],
    'last_answered_at' => ['field_label' => 'Last Answered At', 'field_description' => 'UTC timestamp of the most recent current-answer change.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'submitted_at' => ['field_label' => 'Submitted At', 'field_description' => 'UTC timestamp when the response first met and submitted the completion rule.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_indexed' => true],
    'withdrawn_at' => ['field_label' => 'Withdrawn At', 'field_description' => 'UTC timestamp when the respondent withdrew the response where withdrawal is supported.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'status_survey_response' => ['field_label' => 'Survey Response Status', 'field_description' => 'In-progress, completed, withdrawn, or invalidated response lifecycle.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'default_value' => 'PRG', 'is_mandatory' => true, 'is_indexed' => true],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Optimistic-concurrency token incremented for every accepted response-envelope change.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when the response envelope was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp of the latest response-envelope change.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$survey_response_subfield_property = [];
$survey_response_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'run_respondent_unique', 'index_name' => 'uq_survey_response_run_respondent', 'type_index' => 'CMP', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'survey_run_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'respondent_key', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20],
    ['index_key' => 'user_status', 'index_name' => 'ix_survey_response_user_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => true, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_survey_response', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 30],
    ['index_key' => 'run_source_status', 'index_name' => 'ix_survey_response_run_source_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'survey_run_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'source_survey_response', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_survey_response', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 40],
    ['index_key' => 'import_batch', 'index_name' => 'ix_survey_response_import_batch', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => true, 'index_field_list' => [['field_name' => 'survey_import_batch_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 50],
];
$survey_response_boundary = [
    'owns' => ['one respondent run envelope, deduplication key, source, progress, consent timestamp, completion state, and lifecycle'],
    'reference_field_list' => ['survey_run_id', 'user_id', 'survey_import_batch_id'],
    'does_not_own' => ['question definitions', 'individual answer values', 'raw contact identity', 'reward events', 'aggregate calculations', 'runtime authorization, migration, seeding, or deployment behavior'],
];
return ['survey_response_default' => $survey_response_default, 'survey_response' => $survey_response, 'survey_response_field_order' => $survey_response_field_order, 'survey_response_embedded_structure' => $survey_response_embedded_structure, 'survey_response_field_property' => $survey_response_field_property, 'survey_response_subfield_property' => $survey_response_subfield_property, 'survey_response_index_list' => $survey_response_index_list, 'survey_response_boundary' => $survey_response_boundary];
