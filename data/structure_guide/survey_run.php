<?php
/**
 * Title: Massage Nexus Survey Run Structure Guide
 * Version: 1.00
 * Collection: survey_run
 * Description: Stores one scheduled or conducted period of an exact survey revision.
 * Purpose: Owns audience, placement, access, result, reward, timing, and lifecycle rules that may differ between repeated runs.
 */
$created_at = '2026-07-23T15:55:27Z';
$updated_at = '2026-07-23T16:25:16Z';
$survey_run_default = ['mode_survey_identity' => 'CNF', 'placement_survey_list' => ['DED'], 'is_answer_editable' => true, 'is_result_public' => false, 'min_response_count' => 20, 'result_embargo_at' => null, 'is_reward_eligible' => false, 'reward_rule_key' => null, 'type_survey_context' => null, 'context_record_id' => null, 'status_survey_run' => 'DRA', 'revision_number' => 1];
$survey_run = [
    '_id' => 'SrnQ4mV8xK2pT7zL',
    'survey_revision_id' => 'SvrM7nQ2xP9kL4tV',
    'survey_run_key' => 'quarterly-client-needs-2026-q3',
    'target_survey_audience_list' => ['CLI'],
    'placement_survey_list' => ['WSP', 'DED', 'HOM', 'CNT', 'DIR'],
    'mode_survey_identity' => 'CNF',
    'is_answer_editable' => true,
    'is_result_public' => true,
    'min_response_count' => 20,
    'result_embargo_at' => null,
    'is_reward_eligible' => true,
    'reward_rule_key' => 'survey.full.quarterly.v1',
    'type_survey_context' => null,
    'context_record_id' => null,
    'opens_at' => '2026-07-23T16:00:00Z',
    'closes_at' => '2026-09-30T15:59:59Z',
    'status_survey_run' => 'OPN',
    'revision_number' => 1,
    'created_at' => $created_at,
    'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
    'updated_at' => $updated_at,
    'updated_by_user_id' => 'U2pR7vX4kT9mC5qL',
];
$survey_run_field_order = ['_id', 'survey_revision_id', 'survey_run_key', 'target_survey_audience_list', 'placement_survey_list', 'mode_survey_identity', 'is_answer_editable', 'is_result_public', 'min_response_count', 'result_embargo_at', 'is_reward_eligible', 'reward_rule_key', 'type_survey_context', 'context_record_id', 'opens_at', 'closes_at', 'status_survey_run', 'revision_number', 'created_at', 'created_by_user_id', 'updated_at', 'updated_by_user_id'];
$survey_run_embedded_structure = [];
$survey_run_field_property = [
    '_id' => ['field_label' => 'Survey Run ID', 'field_description' => 'Canonical application-generated identifier for one conducted period.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'survey_revision_id' => ['field_label' => 'Survey Revision', 'field_description' => 'Exact immutable survey_revision administered in this run.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
    'survey_run_key' => ['field_label' => 'Survey Run Key', 'field_description' => 'Stable unique key for this conducted period across import, export, and analytics.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 3, 'max_character' => 120, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'target_survey_audience_list' => ['field_label' => 'Target Survey Audiences', 'field_description' => 'Audience codes eligible for the run; actual eligibility is derived from authoritative account relationships.', 'type_data' => 'A', 'type_field' => 'TAG', 'taxonomy_field_name' => 'target_survey_audience', 'is_mandatory' => true, 'is_indexed' => true],
    'placement_survey_list' => ['field_label' => 'Survey Placements', 'field_description' => 'Approved interface placements in which this run may be offered.', 'type_data' => 'A', 'type_field' => 'TAG', 'taxonomy_field_name' => 'placement_survey', 'default_value' => ['DED']],
    'mode_survey_identity' => ['field_label' => 'Survey Identity Mode', 'field_description' => 'Identified, confidential, or anonymous respondent-linkage rule for this run.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'default_value' => 'CNF', 'is_mandatory' => true],
    'is_answer_editable' => ['field_label' => 'Answer Editable', 'field_description' => 'Whether a respondent may update the same saved answers while the run remains open.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => true, 'is_mandatory' => true],
    'is_result_public' => ['field_label' => 'Result Public', 'field_description' => 'Whether de-identified aggregate results may be publicly displayed after threshold and embargo checks.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false, 'is_mandatory' => true],
    'min_response_count' => ['field_label' => 'Minimum Response Count', 'field_description' => 'Minimum eligible registered responses required before any public aggregate is displayed; Community Pulse defaults to 20.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 20, 'is_mandatory' => true],
    'result_embargo_at' => ['field_label' => 'Result Embargo At', 'field_description' => 'Optional UTC time before which aggregate results must remain hidden even if the response threshold is met.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'is_reward_eligible' => ['field_label' => 'Reward Eligible', 'field_description' => 'Whether an approved Reward System rule may award the first eligible completion or Community Pulse answer.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false, 'is_mandatory' => true],
    'reward_rule_key' => ['field_label' => 'Reward Rule Key', 'field_description' => 'Stable versioned Reward System rule applied idempotently when this run is reward eligible.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 120],
    'type_survey_context' => ['field_label' => 'Survey Context Type', 'field_description' => 'Optional booking, Campus course, Support, or campaign context classification for an event-triggered run.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM'],
    'context_record_id' => ['field_label' => 'Context Record', 'field_description' => 'Optional referenced domain record that caused or scopes this run.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_indexed' => true],
    'opens_at' => ['field_label' => 'Opens At', 'field_description' => 'UTC time when eligible participation may begin.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true, 'is_indexed' => true],
    'closes_at' => ['field_label' => 'Closes At', 'field_description' => 'UTC time when normal submission and editing end.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true, 'is_indexed' => true],
    'status_survey_run' => ['field_label' => 'Survey Run Status', 'field_description' => 'Draft, scheduled, open, closed, withdrawn, or archived lifecycle state.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'default_value' => 'DRA', 'is_mandatory' => true, 'is_indexed' => true],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Optimistic-concurrency token incremented for every accepted run-setting change.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when the run was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'created_by_user_id' => ['field_label' => 'Created By User', 'field_description' => 'User who created the run.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp of the latest accepted run-setting change.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'updated_by_user_id' => ['field_label' => 'Updated By User', 'field_description' => 'User who made the latest accepted run-setting change.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
];
$survey_run_subfield_property = [];
$survey_run_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'run_key_unique', 'index_name' => 'uq_survey_run_run_key', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'survey_run_key', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 20],
    ['index_key' => 'eligible_run', 'index_name' => 'ix_survey_run_status_audience_window', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'status_survey_run', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'target_survey_audience_list', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'opens_at', 'type_index_mode' => 'ASC', 'sort_order' => 30], ['field_name' => 'closes_at', 'type_index_mode' => 'ASC', 'sort_order' => 40]], 'sort_order' => 30],
    ['index_key' => 'revision', 'index_name' => 'ix_survey_run_revision', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'survey_revision_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 40],
    ['index_key' => 'context', 'index_name' => 'ix_survey_run_context', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => true, 'index_field_list' => [['field_name' => 'context_record_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 50],
];
$survey_run_boundary = [
    'owns' => ['one conducted period, audience, placement, identity, result, reward, availability, and lifecycle rules'],
    'reference_field_list' => ['survey_revision_id', 'context_record_id', 'created_by_user_id', 'updated_by_user_id'],
    'does_not_own' => ['survey question wording', 'audience-source relationships', 'response answers', 'reward events', 'notifications and reminders', 'runtime authorization, migration, seeding, or deployment behavior'],
];
return ['survey_run_default' => $survey_run_default, 'survey_run' => $survey_run, 'survey_run_field_order' => $survey_run_field_order, 'survey_run_embedded_structure' => $survey_run_embedded_structure, 'survey_run_field_property' => $survey_run_field_property, 'survey_run_subfield_property' => $survey_run_subfield_property, 'survey_run_index_list' => $survey_run_index_list, 'survey_run_boundary' => $survey_run_boundary];
