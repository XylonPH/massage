<?php
/**
 * Title: Massage Nexus Rank Path Main Structure Guide
 * Version: 1.00
 * Collection: rank_path_main
 * Description: Defines one community progression path and its ordered thresholds.
 * Purpose: Keeps rank calculation governed and separate from access roles and commercial membership.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$multilingual_text_sample = ['eng' => ['text' => 'Community Contributor', 'method_translation' => 'HUM', 'status_review' => 'APR']];
$rank_path_main_default = ['status_rank_path' => 'DFT', 'revision_number' => 1];
$rank_path_main = ['_id' => 'Rp7K2pQ9xR4tV8zN', 'rank_path_code' => 'community-contributor', 'rank_path_name' => ['eng' => ['text' => 'Community Contributor', 'method_translation' => 'HUM', 'status_review' => 'APR']], 'rank_level_list' => [['rank_code' => 'NEW', 'rank_name' => ['eng' => ['text' => 'New Member', 'method_translation' => 'HUM', 'status_review' => 'APR']], 'minimum_points' => 0, 'sort_order' => 10], ['rank_code' => 'CON', 'rank_name' => ['eng' => ['text' => 'Contributor', 'method_translation' => 'HUM', 'status_review' => 'APR']], 'minimum_points' => 100, 'sort_order' => 20]], 'status_rank_path' => 'ACT', 'effective_at' => '2026-07-22T02:51:15Z', 'retired_at' => null, 'revision_number' => 1, 'created_at' => '2026-07-22T02:51:15Z', 'updated_at' => '2026-07-22T02:51:15Z'];
$rank_path_main_field_order = ['_id', 'rank_path_code', 'rank_path_name', 'rank_level_list', 'status_rank_path', 'effective_at', 'retired_at', 'revision_number', 'created_at', 'updated_at'];
$rank_path_main_embedded_structure = ['rank_level_list' => ['rank_code' => 'NEW', 'rank_name' => ['eng' => ['text' => 'New Member', 'method_translation' => 'HUM', 'status_review' => 'APR']], 'minimum_points' => 0, 'sort_order' => 10]];
$rank_path_main_field_property = [
    '_id' => ['field_label' => 'Rank Path ID', 'field_description' => 'Canonical application-generated rank-path identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'rank_path_code' => ['field_label' => 'Rank Path Code', 'field_description' => 'Stable machine-readable path code.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 3, 'max_character' => 80, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'rank_path_name' => ['field_label' => 'Rank Path Name', 'field_description' => 'Translatable public progression-path name.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true, 'is_mandatory' => true],
    'rank_level_list' => ['field_label' => 'Rank Level List', 'field_description' => 'Ordered non-overlapping rank thresholds.', 'type_data' => 'A', 'type_field' => 'JSE', 'is_mandatory' => true],
    'status_rank_path' => ['field_label' => 'Rank Path Status', 'field_description' => 'Draft, active, paused, or retired path lifecycle.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'DFT', 'is_mandatory' => true, 'is_indexed' => true],
    'effective_at' => ['field_label' => 'Effective At', 'field_description' => 'UTC calculation effective time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'retired_at' => ['field_label' => 'Retired At', 'field_description' => 'UTC path retirement time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest accepted update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$rank_path_main_subfield_property = [
    'rank_level_list.rank_code' => ['field_label' => 'Rank Code', 'field_description' => 'Stable code unique within the path.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_mandatory' => true],
    'rank_path_name.*.text' => ['field_label' => 'Rank Path Name Text', 'field_description' => 'Rank path name in one language.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 2, 'max_character' => 100, 'is_mandatory' => true],
    'rank_path_name.*.method_translation' => ['field_label' => 'Rank Path Name Translation Method', 'field_description' => 'Translation provenance for the rank path name.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'rank_path_name.*.status_review' => ['field_label' => 'Rank Path Name Review Status', 'field_description' => 'Review state for the localized rank path name.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'rank_level_list.rank_name' => ['field_label' => 'Rank Name', 'field_description' => 'Translatable public rank name.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_mandatory' => true],
    'rank_level_list.rank_name.*.text' => ['field_label' => 'Rank Name Text', 'field_description' => 'Rank name in one language.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_mandatory' => true],
    'rank_level_list.rank_name.*.method_translation' => ['field_label' => 'Rank Name Translation Method', 'field_description' => 'Translation provenance for the rank name.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'rank_level_list.rank_name.*.status_review' => ['field_label' => 'Rank Name Review Status', 'field_description' => 'Review state for the localized rank name.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'rank_level_list.minimum_points' => ['field_label' => 'Minimum Points', 'field_description' => 'Inclusive qualifying point threshold.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0, 'is_mandatory' => true],
    'rank_level_list.sort_order' => ['field_label' => 'Sort Order', 'field_description' => 'Stable ascending rank order.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 10, 'is_mandatory' => true],
];
$rank_path_main_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'code_unique', 'index_name' => 'uq_rank_path_main_code', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'rank_path_code', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 20], ['index_key' => 'status', 'index_name' => 'ix_rank_path_main_status', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'status_rank_path', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 30]];
$rank_path_main_boundary = ['owns' => ['rank path definition and thresholds'], 'reference_field_list' => [], 'does_not_own' => ['user points', 'user rank history', 'workspace role', 'premium membership']];
return ['multilingual_text_sample' => $multilingual_text_sample, 'rank_path_main_default' => $rank_path_main_default, 'rank_path_main' => $rank_path_main, 'rank_path_main_field_order' => $rank_path_main_field_order, 'rank_path_main_embedded_structure' => $rank_path_main_embedded_structure, 'rank_path_main_field_property' => $rank_path_main_field_property, 'rank_path_main_subfield_property' => $rank_path_main_subfield_property, 'rank_path_main_index_list' => $rank_path_main_index_list, 'rank_path_main_boundary' => $rank_path_main_boundary];
