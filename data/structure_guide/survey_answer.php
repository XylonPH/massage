<?php
/**
 * Title: Massage Nexus Survey Answer Structure Guide
 * Version: 1.00
 * Collection: survey_answer
 * Description: Stores one response's current answer to one exact survey-revision question.
 * Purpose: Supports indexed completion checks, incremental Community Pulse completion, controlled edits, exports, and aggregation without scanning embedded answer histories.
 */
$created_at = '2026-07-23T15:55:27Z';
$updated_at = '2026-07-23T16:03:52Z';
$survey_answer_default = ['revision_number' => 1];
$survey_answer = [
    '_id' => 'SanR4mK8xQ2vT7zL',
    'survey_response_id' => 'SrpT8mK2xQ7vN4zL',
    'survey_run_id' => 'SrnQ4mV8xK2pT7zL',
    'survey_revision_id' => 'SvrM7nQ2xP9kL4tV',
    'question_key' => 'client_booking_priority',
    'source_survey_response' => 'CPL',
    'answer_value' => [
        'selected_option_key_list' => ['skill'],
        'ranking_option_key_list' => [],
        'numeric_value' => null,
        'text_value' => null,
        'matrix_answer_list' => [],
    ],
    'answered_at' => '2026-07-23T16:06:00Z',
    'revision_number' => 1,
    'created_at' => $created_at,
    'updated_at' => $updated_at,
];
$survey_answer_field_order = ['_id', 'survey_response_id', 'survey_run_id', 'survey_revision_id', 'question_key', 'source_survey_response', 'answer_value', 'answered_at', 'revision_number', 'created_at', 'updated_at'];
$survey_answer_embedded_structure = [
    'answer_value' => ['selected_option_key_list' => ['skill'], 'ranking_option_key_list' => ['skill', 'price'], 'numeric_value' => 8, 'text_value' => 'Sample answer', 'matrix_answer_list' => [['matrix_row_key' => 'booking', 'selected_option_key' => 'satisfied']]],
];
$survey_answer_field_property = [
    '_id' => ['field_label' => 'Survey Answer ID', 'field_description' => 'Canonical application-generated identifier for one current question answer.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'survey_response_id' => ['field_label' => 'Survey Response', 'field_description' => 'Respondent participation envelope that owns this current answer.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
    'survey_run_id' => ['field_label' => 'Survey Run', 'field_description' => 'Denormalized run reference used for efficient question and aggregate queries; it must match the response envelope.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
    'survey_revision_id' => ['field_label' => 'Survey Revision', 'field_description' => 'Exact survey revision containing the answered question key.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
    'question_key' => ['field_label' => 'Question Key', 'field_description' => 'Stable question key from the referenced survey revision.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 3, 'max_character' => 100, 'is_mandatory' => true, 'is_indexed' => true],
    'source_survey_response' => ['field_label' => 'Survey Response Source', 'field_description' => 'Channel through which this answer was most recently captured, allowing Community Pulse and full-form completion to share one current answer.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
    'answer_value' => ['field_label' => 'Answer Value', 'field_description' => 'Typed answer object; only members applicable to the referenced question type may be stored.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_mandatory' => true],
    'answered_at' => ['field_label' => 'Answered At', 'field_description' => 'UTC timestamp of the latest accepted current answer.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true, 'is_indexed' => true],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Optimistic-concurrency token; an edit increments this record without creating another current answer or reward.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when the first current answer record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp of the latest accepted answer edit.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$survey_answer_subfield_property = [
    'answer_value.selected_option_key_list' => ['field_label' => 'Selected Option Keys', 'field_description' => 'Unique selected option keys for single-choice, multiple-choice, yes-or-no, or image-choice questions.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'answer_value.ranking_option_key_list' => ['field_label' => 'Ranking Option Keys', 'field_description' => 'Ordered unique option keys for a ranking question.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'answer_value.numeric_value' => ['field_label' => 'Numeric Value', 'field_description' => 'Integer selected for a numeric-scale question within the revision-defined limits.', 'type_data' => 'I', 'type_field' => 'NMB'],
    'answer_value.text_value' => ['field_label' => 'Text Value', 'field_description' => 'Respondent-entered short or long text within revision-defined character limits.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 10000],
    'answer_value.matrix_answer_list' => ['field_label' => 'Matrix Answer List', 'field_description' => 'Bounded row-key and option-key answers for an accessible matrix question.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'answer_value.matrix_answer_list.matrix_row_key' => ['field_label' => 'Matrix Row Key', 'field_description' => 'Stable row identity from the matrix question definition.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 1, 'max_character' => 80, 'is_mandatory' => true],
    'answer_value.matrix_answer_list.selected_option_key' => ['field_label' => 'Selected Option Key', 'field_description' => 'Option key selected for the corresponding matrix row.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 1, 'max_character' => 80, 'is_mandatory' => true],
];
$survey_answer_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'response_question_unique', 'index_name' => 'uq_survey_answer_response_question', 'type_index' => 'CMP', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'survey_response_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'question_key', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20],
    ['index_key' => 'run_question', 'index_name' => 'ix_survey_answer_run_question', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'survey_run_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'question_key', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 30],
    ['index_key' => 'revision', 'index_name' => 'ix_survey_answer_revision', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'survey_revision_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 40],
    ['index_key' => 'source_time', 'index_name' => 'ix_survey_answer_source_time', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'source_survey_response', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'answered_at', 'type_index_mode' => 'DSC', 'sort_order' => 20]], 'sort_order' => 50],
];
$survey_answer_boundary = [
    'owns' => ['one current typed answer value and its edit concurrency state'],
    'reference_field_list' => ['survey_response_id', 'survey_run_id', 'survey_revision_id'],
    'does_not_own' => ['response identity and lifecycle', 'question definition', 'answer revision history outside the shared audit or record-revision systems', 'rewards', 'public aggregates', 'runtime authorization, migration, seeding, or deployment behavior'],
];
return ['survey_answer_default' => $survey_answer_default, 'survey_answer' => $survey_answer, 'survey_answer_field_order' => $survey_answer_field_order, 'survey_answer_embedded_structure' => $survey_answer_embedded_structure, 'survey_answer_field_property' => $survey_answer_field_property, 'survey_answer_subfield_property' => $survey_answer_subfield_property, 'survey_answer_index_list' => $survey_answer_index_list, 'survey_answer_boundary' => $survey_answer_boundary];
