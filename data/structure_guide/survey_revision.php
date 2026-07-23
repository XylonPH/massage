<?php
/**
 * Title: Massage Nexus Survey Revision Structure Guide
 * Version: 1.00
 * Collection: survey_revision
 * Description: Stores one versioned, multilingual survey definition with ordered questions and options.
 * Purpose: Freezes the exact wording and answer contract used by a run while allowing later revisions without rewriting history.
 */
$created_at = '2026-07-23T15:55:27Z';
$updated_at = '2026-07-23T16:03:52Z';
$multilingual_text_sample = ['eng' => ['text' => 'Sample text', 'method_translation' => 'HUM', 'status_review' => 'APR']];
$survey_revision_default = ['survey_description' => [], 'estimated_duration_minute' => 1, 'status_review' => 'DRA', 'published_at' => null, 'revision_number' => 1];
$survey_revision = [
    '_id' => 'SvrM7nQ2xP9kL4tV',
    'survey_id' => 'SvyK2pQ9xR4tV8zN',
    'survey_revision_number' => 1,
    'language_original_id' => 3049,
    'survey_title' => ['eng' => ['text' => 'Quarterly Client Needs Survey', 'method_translation' => 'HUM', 'status_review' => 'APR']],
    'survey_description' => ['eng' => ['text' => 'Tell us what would make massage discovery and booking more useful.', 'method_translation' => 'HUM', 'status_review' => 'APR']],
    'question_list' => [[
        'question_key' => 'client_booking_priority',
        'question_text' => ['eng' => ['text' => 'What matters most when choosing a massage provider?', 'method_translation' => 'HUM', 'status_review' => 'APR']],
        'type_survey_question' => 'SGL',
        'option_list' => [
            ['option_key' => 'skill', 'option_text' => ['eng' => ['text' => 'Practitioner skill', 'method_translation' => 'HUM', 'status_review' => 'APR']], 'sort_order' => 10],
            ['option_key' => 'price', 'option_text' => ['eng' => ['text' => 'Price', 'method_translation' => 'HUM', 'status_review' => 'APR']], 'sort_order' => 20],
        ],
        'is_answer_required' => true,
        'is_community_pulse_eligible' => true,
        'min_selection_count' => 1,
        'max_selection_count' => 1,
        'min_scale_value' => null,
        'max_scale_value' => null,
        'scale_start_label' => [],
        'scale_end_label' => [],
        'min_character' => null,
        'max_character' => null,
        'sort_order' => 10,
    ]],
    'estimated_duration_minute' => 3,
    'status_review' => 'APR',
    'published_at' => '2026-07-23T15:55:27Z',
    'revision_number' => 1,
    'created_at' => $created_at,
    'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
    'updated_at' => $updated_at,
    'updated_by_user_id' => 'U2pR7vX4kT9mC5qL',
];
$survey_revision_field_order = ['_id', 'survey_id', 'survey_revision_number', 'language_original_id', 'survey_title', 'survey_description', 'question_list', 'estimated_duration_minute', 'status_review', 'published_at', 'revision_number', 'created_at', 'created_by_user_id', 'updated_at', 'updated_by_user_id'];
$survey_revision_embedded_structure = [
    'question_list' => ['question_key' => 'client_booking_priority', 'question_text' => $multilingual_text_sample, 'type_survey_question' => 'SGL', 'option_list' => [['option_key' => 'skill', 'option_text' => $multilingual_text_sample, 'option_media_image_id' => null, 'sort_order' => 10]], 'is_answer_required' => true, 'is_community_pulse_eligible' => true, 'min_selection_count' => 1, 'max_selection_count' => 1, 'min_scale_value' => null, 'max_scale_value' => null, 'scale_start_label' => $multilingual_text_sample, 'scale_end_label' => $multilingual_text_sample, 'min_character' => null, 'max_character' => null, 'sort_order' => 10],
];
$survey_revision_field_property = [
    '_id' => ['field_label' => 'Survey Revision ID', 'field_description' => 'Canonical application-generated identifier for one exact instrument revision.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'survey_id' => ['field_label' => 'Survey', 'field_description' => 'Stable survey_main identity to which this revision belongs.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
    'survey_revision_number' => ['field_label' => 'Survey Revision Number', 'field_description' => 'Monotonic human-readable revision number unique within one survey identity.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'is_mandatory' => true, 'is_indexed' => true],
    'language_original_id' => ['field_label' => 'Original Language', 'field_description' => 'Numeric common_reference.language_main identifier for the original authored survey wording.', 'type_data' => 'I', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true],
    'survey_title' => ['field_label' => 'Survey Title', 'field_description' => 'Bounded multilingual title shown in survey invitations, forms, and administration.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true, 'is_mandatory' => true, 'max_character' => 150],
    'survey_description' => ['field_label' => 'Survey Description', 'field_description' => 'Optional multilingual purpose, burden, and respondent-facing explanation.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true, 'max_character' => 1000],
    'question_list' => ['field_label' => 'Question List', 'field_description' => 'Ordered immutable question definitions and controlled answer contracts for this revision.', 'type_data' => 'A', 'type_field' => 'JSE', 'is_mandatory' => true],
    'estimated_duration_minute' => ['field_label' => 'Estimated Duration Minutes', 'field_description' => 'Expected whole minutes required to complete this revision.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'max_number' => 120, 'default_value' => 1],
    'status_review' => ['field_label' => 'Review Status', 'field_description' => 'Editorial and translation-readiness review state; only an approved revision may open a run.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'default_value' => 'DRA', 'is_mandatory' => true, 'is_indexed' => true],
    'published_at' => ['field_label' => 'Published At', 'field_description' => 'UTC timestamp when the approved revision became available for use by new runs.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Optimistic-concurrency token for draft administration; publication freezes the definition.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when this revision record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'created_by_user_id' => ['field_label' => 'Created By User', 'field_description' => 'User who created this survey revision.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp of the latest accepted draft change.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'updated_by_user_id' => ['field_label' => 'Updated By User', 'field_description' => 'User who made the latest accepted draft change.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
];
$survey_revision_subfield_property = [
    'question_list.question_key' => ['field_label' => 'Question Key', 'field_description' => 'Stable key for mapping a logical question across import, export, display, and later revisions.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 3, 'max_character' => 100, 'is_mandatory' => true],
    'question_list.question_text' => ['field_label' => 'Question Text', 'field_description' => 'Multilingual respondent-facing wording for this exact revision.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true, 'is_mandatory' => true, 'max_character' => 1000],
    'question_list.type_survey_question' => ['field_label' => 'Survey Question Type', 'field_description' => 'Controlled answer control and stored answer shape.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true],
    'question_list.option_list' => ['field_label' => 'Option List', 'field_description' => 'Ordered controlled choices for choice, ranking, image, or matrix questions.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'question_list.option_list.option_key' => ['field_label' => 'Option Key', 'field_description' => 'Stable choice identity used in answers, imports, exports, and aggregates.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 1, 'max_character' => 80, 'is_mandatory' => true],
    'question_list.option_list.option_text' => ['field_label' => 'Option Text', 'field_description' => 'Multilingual respondent-facing choice label.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true, 'is_mandatory' => true, 'max_character' => 300],
    'question_list.option_list.option_media_image_id' => ['field_label' => 'Option Media Image', 'field_description' => 'Optional media_image record used by an image-choice option; accessible text remains mandatory.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'question_list.option_list.sort_order' => ['field_label' => 'Option Sort Order', 'field_description' => 'Stable display order of an option within its question.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0, 'is_mandatory' => true],
    'question_list.is_answer_required' => ['field_label' => 'Answer Required', 'field_description' => 'Whether this question must be answered for run completion.', 'type_data' => 'B', 'type_field' => 'CHK', 'is_mandatory' => true],
    'question_list.is_community_pulse_eligible' => ['field_label' => 'Community Pulse Eligible', 'field_description' => 'Whether the question may appear individually in authenticated Community Pulse placements.', 'type_data' => 'B', 'type_field' => 'CHK', 'is_mandatory' => true],
    'question_list.min_selection_count' => ['field_label' => 'Minimum Selection Count', 'field_description' => 'Minimum number of selected options required when the question uses selections.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'question_list.max_selection_count' => ['field_label' => 'Maximum Selection Count', 'field_description' => 'Maximum number of selected options accepted when the question uses selections.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1],
    'question_list.min_scale_value' => ['field_label' => 'Minimum Scale Value', 'field_description' => 'Lowest integer accepted by a numeric-scale question.', 'type_data' => 'I', 'type_field' => 'NMB'],
    'question_list.max_scale_value' => ['field_label' => 'Maximum Scale Value', 'field_description' => 'Highest integer accepted by a numeric-scale question.', 'type_data' => 'I', 'type_field' => 'NMB'],
    'question_list.scale_start_label' => ['field_label' => 'Scale Start Label', 'field_description' => 'Optional multilingual explanation of the minimum scale value.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true, 'max_character' => 150],
    'question_list.scale_end_label' => ['field_label' => 'Scale End Label', 'field_description' => 'Optional multilingual explanation of the maximum scale value.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true, 'max_character' => 150],
    'question_list.min_character' => ['field_label' => 'Minimum Characters', 'field_description' => 'Minimum accepted length of a text answer when applicable.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'question_list.max_character' => ['field_label' => 'Maximum Characters', 'field_description' => 'Maximum accepted length of a text answer when applicable.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'max_number' => 10000],
    'question_list.sort_order' => ['field_label' => 'Question Sort Order', 'field_description' => 'Stable display order of a question within the revision.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0, 'is_mandatory' => true],
];
$survey_revision_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'survey_revision_unique', 'index_name' => 'uq_survey_revision_survey_number', 'type_index' => 'CMP', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'survey_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'survey_revision_number', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20],
    ['index_key' => 'review_status', 'index_name' => 'ix_survey_revision_review_status', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'status_review', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 30],
];
$survey_revision_boundary = [
    'owns' => ['exact multilingual title, description, ordered questions, options, constraints, and revision review state'],
    'reference_field_list' => ['survey_id', 'language_original_id', 'question_list.option_list.option_media_image_id', 'created_by_user_id', 'updated_by_user_id'],
    'does_not_own' => ['survey identity lifecycle', 'run scheduling and audience', 'responses and answers', 'translation audit history outside the embedded current values', 'runtime authorization, migration, seeding, or deployment behavior'],
];
return ['multilingual_text_sample' => $multilingual_text_sample, 'survey_revision_default' => $survey_revision_default, 'survey_revision' => $survey_revision, 'survey_revision_field_order' => $survey_revision_field_order, 'survey_revision_embedded_structure' => $survey_revision_embedded_structure, 'survey_revision_field_property' => $survey_revision_field_property, 'survey_revision_subfield_property' => $survey_revision_subfield_property, 'survey_revision_index_list' => $survey_revision_index_list, 'survey_revision_boundary' => $survey_revision_boundary];
