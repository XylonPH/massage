<?php
/**
 * Title: Massage Nexus Service Main Structure Guide
 * Version: 0.10
 * Collection: service_main
 * Description: Stores one normalized reusable service concept independently of a provider offering it.
 * Purpose: Documents the service_main record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * - The public service specification is docs/05-directory/service-profile.txt.
 */

$created_at = '2026-07-20T05:24:10Z';
$updated_at = '2026-07-21T04:24:17Z';
$service_main_default = [
    'status_record_lifecycle' => 'ACT',
];

$multilingual_text_sample = [
    'eng' => [
        'text' => 'Thai Massage',
        'method_translation' => 'HUM',
        'status_review' => 'APR',
    ],
];

$service_main = [
    '_id' => 'Sv7K2pQ9xR4tV8zN', // Canonical 16-character service identifier.
    'service_slug' => 'thai-massage', // Unique readable public route value.
    'service_name' => ['eng' => ['text' => 'Thai Massage']], // Preferred multilingual service name.
    'short_description' => ['eng' => ['text' => 'A traditional assisted stretching and pressure-based massage practice.']], // Concise multilingual summary.
    'service_description_overview' => ['eng' => ['text' => 'An overview of purpose, setting, and common session structure.']], // Extended multilingual overview.
    'group_service_sector' => 'health_wellness_and_care', // Top-level service hierarchy key.
    'group_service_domain' => 'spa_and_wellness', // Second-level service hierarchy key.
    'group_service_family' => 'massage', // Primary reusable service-family key.
    'status_record_lifecycle' => 'ACT', // Database lifecycle state.
    'created_at' => '2026-07-21T04:08:58Z', // UTC record creation time.
    'updated_at' => '2026-07-21T04:08:58Z', // UTC record update time.
];

$service_main_field_order = [
    '_id',
    'service_slug',
    'service_name',
    'short_description',
    'service_description_overview',
    'group_service_sector',
    'group_service_domain',
    'group_service_family',
    'status_record_lifecycle',
    'created_at',
    'updated_at',
];

$service_main_embedded_structure = [];

$service_main_field_property = [
    '_id' => ['field_label' => 'Service ID', 'field_description' => 'Canonical application-generated 16-character identifier.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'service_slug' => ['field_label' => 'Service Slug', 'field_description' => 'Unique readable public route value.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'service_name' => ['field_label' => 'Service Name', 'field_description' => 'Preferred multilingual service name.', 'type_data' => 'O', 'is_translatable' => true, 'is_mandatory' => true],
    'short_description' => ['field_label' => 'Short Description', 'field_description' => 'Concise multilingual service summary.', 'type_data' => 'O', 'is_translatable' => true],
    'service_description_overview' => ['field_label' => 'Service Description Overview', 'field_description' => 'Extended multilingual overview of purpose, setting, and session structure.', 'type_data' => 'O', 'is_translatable' => true],
    'group_service_sector' => ['field_label' => 'Service Sector Group', 'field_description' => 'Top-level service hierarchy key.', 'type_data' => 'S', 'is_indexed' => true],
    'group_service_domain' => ['field_label' => 'Service Domain Group', 'field_description' => 'Second-level service hierarchy key.', 'type_data' => 'S', 'is_indexed' => true],
    'group_service_family' => ['field_label' => 'Service Family Group', 'field_description' => 'Primary reusable service-family key.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC record update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];

$service_main_subfield_property = [
    'service_name.*.text' => ['field_label' => 'Service Name Text', 'field_description' => 'Service name for one language.', 'type_data' => 'S', 'is_mandatory' => true],
    'short_description.*.text' => ['field_label' => 'Short Description Text', 'field_description' => 'Short description for one language.', 'type_data' => 'S'],
    'service_description_overview.*.text' => ['field_label' => 'Overview Text', 'field_description' => 'Extended overview for one language.', 'type_data' => 'S'],
];

$service_main_index_list = [
    [
        'index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false,
        'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10,
    ],
    [
        'index_key' => 'service_slug_unique', 'index_name' => 'uq_service_main_service_slug', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false,
        'index_field_list' => [['field_name' => 'service_slug', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 20,
    ],
];

$service_main_boundary = [
    'owns' => ['canonical service identity, multilingual description, hierarchy placement, and lifecycle'],
    'references' => ['service classification authority in docs/05-directory/service-classification.txt and its taxonomy data'],
    'does_not_own' => ['establishment or practitioner offerings', 'prices', 'availability', 'bookings', 'reviews, ratings, or media'],
];

return [
    'service_main_default' => $service_main_default,
    'multilingual_text_sample' => $multilingual_text_sample,
    'service_main' => $service_main,
    'service_main_field_order' => $service_main_field_order,
    'service_main_embedded_structure' => $service_main_embedded_structure,
    'service_main_field_property' => $service_main_field_property,
    'service_main_subfield_property' => $service_main_subfield_property,
    'service_main_index_list' => $service_main_index_list,
    'service_main_boundary' => $service_main_boundary,
];
