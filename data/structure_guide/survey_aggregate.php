<?php
/**
 * Title: Massage Nexus Survey Aggregate Structure Guide
 * Version: 1.00
 * Collection: survey_aggregate
 * Description: Stores one rebuildable question-level aggregate for a run and approved cohort.
 * Purpose: Serves privacy-checked results and charts without exposing or repeatedly scanning raw respondent answers.
 */
$created_at = '2026-07-23T15:55:27Z';
$updated_at = '2026-07-23T16:03:52Z';
$survey_aggregate_default = ['cohort_key' => 'all-eligible', 'response_count' => 0, 'answer_count' => 0, 'average_value' => null, 'is_result_suppressed' => true, 'suppression_reason' => 'Below minimum response threshold', 'revision_number' => 1];
$survey_aggregate = [
    '_id' => 'SagK8mQ2xR7vT4zN',
    'survey_run_id' => 'SrnQ4mV8xK2pT7zL',
    'survey_revision_id' => 'SvrM7nQ2xP9kL4tV',
    'question_key' => 'client_booking_priority',
    'cohort_key' => 'all-eligible',
    'response_count' => 120,
    'answer_count' => 118,
    'distribution_list' => [
        ['aggregate_value_key' => 'skill', 'count_value' => 70, 'percent_value' => 59.32],
        ['aggregate_value_key' => 'price', 'count_value' => 48, 'percent_value' => 40.68],
    ],
    'average_value' => null,
    'is_result_suppressed' => false,
    'suppression_reason' => null,
    'calculation_version' => 'survey-aggregate-v1',
    'calculated_at' => '2026-07-23T16:15:00Z',
    'revision_number' => 1,
    'created_at' => $created_at,
    'updated_at' => $updated_at,
];
$survey_aggregate_field_order = ['_id', 'survey_run_id', 'survey_revision_id', 'question_key', 'cohort_key', 'response_count', 'answer_count', 'distribution_list', 'average_value', 'is_result_suppressed', 'suppression_reason', 'calculation_version', 'calculated_at', 'revision_number', 'created_at', 'updated_at'];
$survey_aggregate_embedded_structure = ['distribution_list' => ['aggregate_value_key' => 'skill', 'count_value' => 70, 'percent_value' => 59.32]];
$survey_aggregate_field_property = [
    '_id' => ['field_label' => 'Survey Aggregate ID', 'field_description' => 'Canonical application-generated identifier for one run-question-cohort aggregate.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'survey_run_id' => ['field_label' => 'Survey Run', 'field_description' => 'Survey run summarized by this aggregate.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
    'survey_revision_id' => ['field_label' => 'Survey Revision', 'field_description' => 'Exact question definition used by this aggregate.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true],
    'question_key' => ['field_label' => 'Question Key', 'field_description' => 'Question summarized within the referenced run revision.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 3, 'max_character' => 100, 'is_mandatory' => true, 'is_indexed' => true],
    'cohort_key' => ['field_label' => 'Cohort Key', 'field_description' => 'Stable approved grouping key; all-eligible is the default and custom cohorts require methodology and privacy review.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 3, 'max_character' => 120, 'default_value' => 'all-eligible', 'is_mandatory' => true, 'is_indexed' => true],
    'response_count' => ['field_label' => 'Response Count', 'field_description' => 'Eligible distinct responses in the aggregate cohort, including permitted skips.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0, 'default_value' => 0, 'is_mandatory' => true],
    'answer_count' => ['field_label' => 'Answer Count', 'field_description' => 'Eligible non-skipped answers represented by the distribution or numeric summary.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0, 'default_value' => 0, 'is_mandatory' => true],
    'distribution_list' => ['field_label' => 'Distribution List', 'field_description' => 'Counts and percentages by approved option, numeric bucket, or other non-identifying value key.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'average_value' => ['field_label' => 'Average Value', 'field_description' => 'Optional arithmetic mean for an eligible numeric-scale question; distribution remains available for interpretation.', 'type_data' => 'F', 'type_field' => 'NMB'],
    'is_result_suppressed' => ['field_label' => 'Result Suppressed', 'field_description' => 'Whether the aggregate is withheld because of threshold, embargo, sensitivity, invalid methodology, or withdrawal.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => true, 'is_mandatory' => true],
    'suppression_reason' => ['field_label' => 'Suppression Reason', 'field_description' => 'Administrative explanation for why this aggregate must not be displayed.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 500, 'default_value' => 'Below minimum response threshold'],
    'calculation_version' => ['field_label' => 'Calculation Version', 'field_description' => 'Versioned aggregation and eligibility formula used to build the record.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 80, 'is_mandatory' => true],
    'calculated_at' => ['field_label' => 'Calculated At', 'field_description' => 'UTC timestamp when this aggregate was last rebuilt.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Optimistic-concurrency token for safe aggregate replacement and administration.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when this aggregate key was first created.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp of the latest aggregate rebuild.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$survey_aggregate_subfield_property = [
    'distribution_list.aggregate_value_key' => ['field_label' => 'Aggregate Value Key', 'field_description' => 'Option key, numeric bucket key, or other stable non-identifying value represented by one distribution entry.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 100, 'is_mandatory' => true],
    'distribution_list.count_value' => ['field_label' => 'Count Value', 'field_description' => 'Eligible answer count represented by this distribution entry.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0, 'is_mandatory' => true],
    'distribution_list.percent_value' => ['field_label' => 'Percent Value', 'field_description' => 'Percentage from zero through one hundred represented by this distribution entry.', 'type_data' => 'F', 'type_field' => 'NMB', 'min_number' => 0, 'max_number' => 100, 'is_mandatory' => true],
];
$survey_aggregate_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'run_question_cohort_unique', 'index_name' => 'uq_survey_aggregate_run_question_cohort', 'type_index' => 'CMP', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'survey_run_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'question_key', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'cohort_key', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
];
$survey_aggregate_boundary = [
    'owns' => ['rebuildable question-level counts, percentages, numeric summary, suppression decision, and calculation metadata'],
    'reference_field_list' => ['survey_run_id', 'survey_revision_id'],
    'does_not_own' => ['raw answers', 'respondent identity', 'survey methodology approval', 'analytics-event history', 'runtime authorization, migration, seeding, or deployment behavior'],
];
return ['survey_aggregate_default' => $survey_aggregate_default, 'survey_aggregate' => $survey_aggregate, 'survey_aggregate_field_order' => $survey_aggregate_field_order, 'survey_aggregate_embedded_structure' => $survey_aggregate_embedded_structure, 'survey_aggregate_field_property' => $survey_aggregate_field_property, 'survey_aggregate_subfield_property' => $survey_aggregate_subfield_property, 'survey_aggregate_index_list' => $survey_aggregate_index_list, 'survey_aggregate_boundary' => $survey_aggregate_boundary];
