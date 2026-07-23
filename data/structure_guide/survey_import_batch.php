<?php
/**
 * Title: Massage Nexus Survey Import Batch Structure Guide
 * Version: 1.00
 * Collection: survey_import_batch
 * Description: Stores one controlled Microsoft Forms, paper, or administrative survey-response import batch.
 * Purpose: Preserves source provenance, exact form version, mapping, validation, counts, and idempotent import lifecycle separately from imported responses.
 */
$created_at = '2026-07-23T15:55:27Z';
$updated_at = '2026-07-23T16:03:52Z';
$survey_import_batch_default = ['source_document_id' => null, 'external_form_id' => null, 'form_version_label' => null, 'imported_record_count' => 0, 'rejected_record_count' => 0, 'import_note' => null, 'imported_at' => null, 'status_survey_import' => 'DRA', 'revision_number' => 1];
$survey_import_batch = [
    '_id' => 'SibQ7mK2xR8vT4zN',
    'survey_run_id' => 'SrnQ4mV8xK2pT7zL',
    'source_survey_response' => 'MSF',
    'source_document_id' => 'DocM4pQ8xR2tV7zN',
    'external_form_id' => 'MSFORMS-CLIENT-2026-01',
    'form_version_label' => 'client-prelaunch-v1',
    'file_name' => 'client-prelaunch-responses-2026-07-23.xlsx',
    'checksum' => 'sha256:0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef',
    'import_mapping_list' => [[
        'source_question_key' => 'Q01',
        'question_key' => 'client_booking_priority',
        'option_mapping_list' => [['source_option_key' => 'Practitioner skill', 'option_key' => 'skill']],
    ]],
    'imported_record_count' => 120,
    'rejected_record_count' => 2,
    'import_note' => 'Two source rows were rejected after duplicate and completeness review.',
    'imported_at' => '2026-07-23T16:30:00Z',
    'status_survey_import' => 'CMP',
    'revision_number' => 1,
    'created_at' => $created_at,
    'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
    'updated_at' => $updated_at,
    'updated_by_user_id' => 'U2pR7vX4kT9mC5qL',
];
$survey_import_batch_field_order = ['_id', 'survey_run_id', 'source_survey_response', 'source_document_id', 'external_form_id', 'form_version_label', 'file_name', 'checksum', 'import_mapping_list', 'imported_record_count', 'rejected_record_count', 'import_note', 'imported_at', 'status_survey_import', 'revision_number', 'created_at', 'created_by_user_id', 'updated_at', 'updated_by_user_id'];
$survey_import_batch_embedded_structure = ['import_mapping_list' => ['source_question_key' => 'Q01', 'question_key' => 'client_booking_priority', 'option_mapping_list' => [['source_option_key' => 'Practitioner skill', 'option_key' => 'skill']]]];
$survey_import_batch_field_property = [
    '_id' => ['field_label' => 'Survey Import Batch ID', 'field_description' => 'Canonical application-generated identifier for one controlled external import batch.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'survey_run_id' => ['field_label' => 'Survey Run', 'field_description' => 'Survey run that owns all response and answer records created by this batch.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
    'source_survey_response' => ['field_label' => 'Survey Response Source', 'field_description' => 'Microsoft Forms, paper, or controlled administrative channel represented by the batch.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
    'source_document_id' => ['field_label' => 'Source Document', 'field_description' => 'Optional retained document_main record containing the protected original export, scan set, or source package.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'external_form_id' => ['field_label' => 'External Form ID', 'field_description' => 'Provider-issued or internally assigned stable identifier for the external questionnaire.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 200],
    'form_version_label' => ['field_label' => 'Form Version Label', 'field_description' => 'Human-readable exact external or printed questionnaire revision label used for collection.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 100],
    'file_name' => ['field_label' => 'File Name', 'field_description' => 'Original source export or controlled data-entry file name without a secret storage path.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 255, 'is_mandatory' => true],
    'checksum' => ['field_label' => 'Checksum', 'field_description' => 'Cryptographic checksum of the retained source file used for integrity and duplicate-import control.', 'type_data' => 'S', 'type_field' => 'HDN', 'max_character' => 160, 'is_mandatory' => true, 'is_indexed' => true],
    'import_mapping_list' => ['field_label' => 'Import Mapping List', 'field_description' => 'Explicit source-question and source-option mappings to immutable revision keys.', 'type_data' => 'A', 'type_field' => 'JSE', 'is_mandatory' => true],
    'imported_record_count' => ['field_label' => 'Imported Record Count', 'field_description' => 'Number of response rows successfully imported by this batch.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0, 'default_value' => 0],
    'rejected_record_count' => ['field_label' => 'Rejected Record Count', 'field_description' => 'Number of source rows rejected with recorded validation reasons.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0, 'default_value' => 0],
    'import_note' => ['field_label' => 'Import Note', 'field_description' => 'Bounded administrative explanation of validation findings, exceptions, or paper-entry quality checks.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 2000],
    'imported_at' => ['field_label' => 'Imported At', 'field_description' => 'UTC timestamp when the batch completed creation of its accepted response records.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'status_survey_import' => ['field_label' => 'Survey Import Status', 'field_description' => 'Draft, validating, ready, importing, completed, failed, or cancelled import lifecycle.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'default_value' => 'DRA', 'is_mandatory' => true, 'is_indexed' => true],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Optimistic-concurrency token incremented for every accepted batch-configuration change.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when the import batch was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'created_by_user_id' => ['field_label' => 'Created By User', 'field_description' => 'User who created the import batch.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp of the latest accepted batch change.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'updated_by_user_id' => ['field_label' => 'Updated By User', 'field_description' => 'User who made the latest accepted batch change.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
];
$survey_import_batch_subfield_property = [
    'import_mapping_list.source_question_key' => ['field_label' => 'Source Question Key', 'field_description' => 'Stable column, question, or printed-item key from the preserved source questionnaire.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 200, 'is_mandatory' => true],
    'import_mapping_list.question_key' => ['field_label' => 'Question Key', 'field_description' => 'Destination question key in the run revision.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 100, 'is_mandatory' => true],
    'import_mapping_list.option_mapping_list' => ['field_label' => 'Option Mapping List', 'field_description' => 'Source-to-destination option mapping for a controlled-choice question.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'import_mapping_list.option_mapping_list.source_option_key' => ['field_label' => 'Source Option Key', 'field_description' => 'Stable source option code or exact preserved source label.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 300, 'is_mandatory' => true],
    'import_mapping_list.option_mapping_list.option_key' => ['field_label' => 'Option Key', 'field_description' => 'Destination option key in the survey revision.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 80, 'is_mandatory' => true],
];
$survey_import_batch_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'run_status', 'index_name' => 'ix_survey_import_batch_run_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'survey_run_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_survey_import', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20],
    ['index_key' => 'checksum_unique', 'index_name' => 'uq_survey_import_batch_checksum', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'checksum', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 30],
    ['index_key' => 'source', 'index_name' => 'ix_survey_import_batch_source', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'source_survey_response', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 40],
];
$survey_import_batch_boundary = [
    'owns' => ['external import provenance, exact form version, protected-source reference, mapping, validation counts, notes, and import lifecycle'],
    'reference_field_list' => ['survey_run_id', 'source_document_id', 'created_by_user_id', 'updated_by_user_id'],
    'does_not_own' => ['the protected file bytes', 'imported responses and answers', 'respondent contact identity', 'reward eligibility', 'runtime authorization, migration, seeding, or deployment behavior'],
];
return ['survey_import_batch_default' => $survey_import_batch_default, 'survey_import_batch' => $survey_import_batch, 'survey_import_batch_field_order' => $survey_import_batch_field_order, 'survey_import_batch_embedded_structure' => $survey_import_batch_embedded_structure, 'survey_import_batch_field_property' => $survey_import_batch_field_property, 'survey_import_batch_subfield_property' => $survey_import_batch_subfield_property, 'survey_import_batch_index_list' => $survey_import_batch_index_list, 'survey_import_batch_boundary' => $survey_import_batch_boundary];
