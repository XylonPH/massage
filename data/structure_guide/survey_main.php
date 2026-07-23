<?php
/**
 * Title: Massage Nexus Survey Main Structure Guide
 * Version: 1.00
 * Collection: survey_main
 * Description: Stores one stable platform survey or poll identity and its current revision pointer.
 * Purpose: Preserves the durable instrument identity separately from immutable revision content and conducted runs.
 */
$created_at = '2026-07-23T15:55:27Z';
$updated_at = '2026-07-23T16:03:52Z';
$survey_main_default = ['status_record_lifecycle' => 'ACT', 'revision_number' => 1];
$survey_main = [
    '_id' => 'SvyK2pQ9xR4tV8zN',
    'survey_key' => 'quarterly-client-needs',
    'type_survey' => 'SUR',
    'current_survey_revision_id' => 'SvrM7nQ2xP9kL4tV',
    'status_record_lifecycle' => 'ACT',
    'revision_number' => 1,
    'created_at' => $created_at,
    'created_by_user_id' => 'U5rK8mP2xN7qL4vA',
    'updated_at' => $updated_at,
    'updated_by_user_id' => 'U2pR7vX4kT9mC5qL',
];
$survey_main_field_order = ['_id', 'survey_key', 'type_survey', 'current_survey_revision_id', 'status_record_lifecycle', 'revision_number', 'created_at', 'created_by_user_id', 'updated_at', 'updated_by_user_id'];
$survey_main_embedded_structure = [];
$survey_main_field_property = [
    '_id' => ['field_label' => 'Survey ID', 'field_description' => 'Canonical application-generated 16-character identifier for the stable survey instrument.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'survey_key' => ['field_label' => 'Survey Key', 'field_description' => 'Stable import, export, and administrative identity that survives title changes and revision publication.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 3, 'max_character' => 100, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'type_survey' => ['field_label' => 'Survey Type', 'field_description' => 'Classifies the instrument as a multi-question survey or single-question poll.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
    'current_survey_revision_id' => ['field_label' => 'Current Survey Revision', 'field_description' => 'Current approved survey_revision record used when creating a new run; existing runs keep their own immutable revision reference.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_indexed' => true],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Active, inactive, archived, or soft-deleted state of the stable survey identity.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'default_value' => 'ACT', 'is_mandatory' => true, 'is_indexed' => true],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Optimistic-concurrency token incremented for every accepted change to this shared administrative record.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC timestamp when the survey identity was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'created_by_user_id' => ['field_label' => 'Created By User', 'field_description' => 'User who created the survey identity.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC timestamp of the latest accepted survey identity change.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'updated_by_user_id' => ['field_label' => 'Updated By User', 'field_description' => 'User who made the latest accepted survey identity change.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
];
$survey_main_subfield_property = [];
$survey_main_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'survey_key_unique', 'index_name' => 'uq_survey_main_survey_key', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'survey_key', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 20],
    ['index_key' => 'lifecycle_type', 'index_name' => 'ix_survey_main_lifecycle_type', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'status_record_lifecycle', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'type_survey', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 30],
];
$survey_main_boundary = [
    'owns' => ['stable survey identity, type, lifecycle, and current-revision pointer'],
    'reference_field_list' => ['current_survey_revision_id', 'created_by_user_id', 'updated_by_user_id'],
    'does_not_own' => ['revision questions and translations', 'conducted runs', 'responses and answers', 'runtime authorization, migration, seeding, or deployment behavior'],
];
return ['survey_main_default' => $survey_main_default, 'survey_main' => $survey_main, 'survey_main_field_order' => $survey_main_field_order, 'survey_main_embedded_structure' => $survey_main_embedded_structure, 'survey_main_field_property' => $survey_main_field_property, 'survey_main_subfield_property' => $survey_main_subfield_property, 'survey_main_index_list' => $survey_main_index_list, 'survey_main_boundary' => $survey_main_boundary];
