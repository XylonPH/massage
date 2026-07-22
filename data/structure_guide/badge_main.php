<?php
/**
 * Title: Massage Nexus Badge Main Structure Guide
 * Version: 1.00
 * Collection: badge_main
 * Description: Defines one governed community badge and its display metadata.
 * Purpose: Provides stable badge definitions independently from user awards.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$multilingual_text_sample = ['eng' => ['text' => 'Founding Contributor', 'method_translation' => 'HUM', 'status_review' => 'APR']];
$badge_main_default = ['is_public' => true, 'is_featureable' => true, 'status_badge' => 'DFT', 'revision_number' => 1];
$badge_main = ['_id' => 'Bd7K2pQ9xR4tV8zN', 'badge_code' => 'founding-contributor', 'badge_name' => ['eng' => ['text' => 'Founding Contributor', 'method_translation' => 'HUM', 'status_review' => 'APR']], 'badge_description' => ['eng' => ['text' => 'Recognizes an early verified contribution to Massage Nexus.', 'method_translation' => 'HUM', 'status_review' => 'APR']], 'badge_icon_key' => 'badge.founding_contributor', 'type_badge' => 'ACH', 'is_public' => true, 'is_featureable' => true, 'status_badge' => 'ACT', 'effective_at' => '2026-07-22T02:51:15Z', 'retired_at' => null, 'revision_number' => 1, 'created_at' => '2026-07-22T02:51:15Z', 'updated_at' => '2026-07-22T02:51:15Z'];
$badge_main_field_order = ['_id', 'badge_code', 'badge_name', 'badge_description', 'badge_icon_key', 'type_badge', 'is_public', 'is_featureable', 'status_badge', 'effective_at', 'retired_at', 'revision_number', 'created_at', 'updated_at'];
$badge_main_embedded_structure = [];
$badge_main_field_property = [
    '_id' => ['field_label' => 'Badge ID', 'field_description' => 'Canonical application-generated badge-definition identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'badge_code' => ['field_label' => 'Badge Code', 'field_description' => 'Stable machine-readable badge code.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 3, 'max_character' => 80, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'badge_name' => ['field_label' => 'Badge Name', 'field_description' => 'Translatable public badge name.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true, 'is_mandatory' => true],
    'badge_description' => ['field_label' => 'Badge Description', 'field_description' => 'Translatable explanation of the governed badge meaning.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true, 'is_mandatory' => true],
    'badge_icon_key' => ['field_label' => 'Badge Icon Key', 'field_description' => 'Approved theme or asset key for visual presentation.', 'type_data' => 'S', 'type_field' => 'TXT', 'max_character' => 120],
    'type_badge' => ['field_label' => 'Badge Type', 'field_description' => 'Achievement, verification, participation, service, or legacy category.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
    'is_public' => ['field_label' => 'Public Badge', 'field_description' => 'Whether eligible awards may be shown publicly.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => true],
    'is_featureable' => ['field_label' => 'Featureable Badge', 'field_description' => 'Whether a user may select the badge among featured badges.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => true],
    'status_badge' => ['field_label' => 'Badge Status', 'field_description' => 'Draft, active, paused, or retired definition lifecycle.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'DFT', 'is_mandatory' => true, 'is_indexed' => true],
    'effective_at' => ['field_label' => 'Effective At', 'field_description' => 'UTC earliest award time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'retired_at' => ['field_label' => 'Retired At', 'field_description' => 'UTC definition retirement time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest accepted update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$badge_main_subfield_property = [
    'badge_name.*.text' => ['field_label' => 'Badge Name Text', 'field_description' => 'Public badge name in one language.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 2, 'max_character' => 100, 'is_mandatory' => true],
    'badge_name.*.method_translation' => ['field_label' => 'Badge Name Translation Method', 'field_description' => 'Translation provenance for the badge name.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'badge_name.*.status_review' => ['field_label' => 'Badge Name Review Status', 'field_description' => 'Review state for the localized badge name.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'badge_description.*.text' => ['field_label' => 'Badge Description Text', 'field_description' => 'Badge explanation in one language.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 500, 'is_mandatory' => true],
    'badge_description.*.method_translation' => ['field_label' => 'Badge Description Translation Method', 'field_description' => 'Translation provenance for the badge description.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'badge_description.*.status_review' => ['field_label' => 'Badge Description Review Status', 'field_description' => 'Review state for the localized badge description.', 'type_data' => 'S', 'type_field' => 'DDL'],
];
$badge_main_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'badge_code_unique', 'index_name' => 'uq_badge_main_badge_code', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'badge_code', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 20], ['index_key' => 'status', 'index_name' => 'ix_badge_main_status', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'status_badge', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 30]];
$badge_main_boundary = ['owns' => ['badge definition and display contract'], 'reference_field_list' => [], 'does_not_own' => ['user award', 'reward event', 'workspace role']];
return ['multilingual_text_sample' => $multilingual_text_sample, 'badge_main_default' => $badge_main_default, 'badge_main' => $badge_main, 'badge_main_field_order' => $badge_main_field_order, 'badge_main_embedded_structure' => $badge_main_embedded_structure, 'badge_main_field_property' => $badge_main_field_property, 'badge_main_subfield_property' => $badge_main_subfield_property, 'badge_main_index_list' => $badge_main_index_list, 'badge_main_boundary' => $badge_main_boundary];
