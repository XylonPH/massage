<?php
/**
 * Title: Massage Nexus Organization Structure Guide
 * Version: 1.10
 * Collection: organization_main
 * Description: Stores a reusable legal, brand, management, hospitality, clinic, or training organization identity.
 * Purpose: Defines organization identity separately from establishments, contacts, relationships, workspace access, and evidence.
 */

$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T09:49:12Z';

$organization_main_default = ['alternate_name_list' => [], 'registration_identifier_list' => [], 'status_record_lifecycle' => 'ACT'];

$multilingual_text_sample = ['eng' => ['text' => 'Sample organization text', 'method_translation' => 'HUM', 'status_review' => 'APR']];

$organization_main = [
    '_id' => 'Or8K2pQ9xR4tV7zN', // Canonical organization identifier.
    'organization_slug' => 'sample-wellness-group', // Unique public route value.
    'display_name' => ['eng' => ['text' => 'Sample Wellness Group']], // Public organization name.
    'legal_name' => 'Sample Wellness Group Incorporated', // Registered legal name when publishable.
    'alternate_name_list' => [['type_name_variant' => 'ALI', 'name_variant_text' => 'Sample Wellness']], // Bounded alternate-name facts.
    'short_description' => ['eng' => ['text' => 'A sample wellness operator.']], // Public summary.
    'type_organization' => 'BUS', // Controlled organization type.
    'country_id' => 608, // Common-reference country identifier.
    'registration_identifier_list' => [['type_registration_identifier' => 'SEC', 'identifier_masked' => 'CS20••••••', 'country_id' => 608]], // Masked registration identifiers.
    'date_established' => '2019-01-01', // Best supported establishment date.
    'type_date_precision' => 'Y', // Precision of date_established.
    'type_date_qualifier' => 'EXA', // Qualifier of date_established.
    'status_organization' => 'ACT', // Real-world organization status.
    'primary_establishment_id' => 'Es7K2pQ9xR4tV8zN', // Optional primary public place.
    'primary_media_image_id' => 'Im7K2pQ9xR4tV8zN', // Optional primary image.
    'last_confirmed_at' => '2026-07-21T08:23:43Z', // Latest adequate confirmation.
    'status_record_lifecycle' => 'ACT', // Database lifecycle status.
    'created_at' => $created_at, // UTC creation time.
    'updated_at' => $updated_at, // UTC update time.
];

$organization_main_field_order = ['_id', 'organization_slug', 'display_name', 'legal_name', 'alternate_name_list', 'short_description', 'type_organization', 'country_id', 'registration_identifier_list', 'date_established', 'type_date_precision', 'type_date_qualifier', 'status_organization', 'primary_establishment_id', 'primary_media_image_id', 'last_confirmed_at', 'status_record_lifecycle', 'created_at', 'updated_at'];

$organization_main_embedded_structure = [
    'alternate_name_list' => ['type_name_variant' => 'ALI', 'name_variant_text' => 'Sample Wellness'],
    'registration_identifier_list' => ['type_registration_identifier' => 'SEC', 'identifier_masked' => 'CS20••••••', 'country_id' => 608],
];

$organization_main_field_property = [
    '_id' => ['field_label' => 'Organization ID', 'field_description' => 'Canonical 16-character organization identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true],
    'organization_slug' => ['field_label' => 'Organization Slug', 'field_description' => 'Unique public route value.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_indexed' => true],
    'display_name' => ['field_label' => 'Display Name', 'field_description' => 'Multilingual public organization name.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true, 'is_mandatory' => true],
    'legal_name' => ['field_label' => 'Legal Name', 'field_description' => 'Registered legal name when collection and display are permitted.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'alternate_name_list' => ['field_label' => 'Alternate Names', 'field_description' => 'Bounded organization name variants.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'short_description' => ['field_label' => 'Short Description', 'field_description' => 'Multilingual public summary.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true],
    'type_organization' => ['field_label' => 'Organization Type', 'field_description' => 'Controlled organization classification.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_indexed' => true],
    'country_id' => ['field_label' => 'Country ID', 'field_description' => 'Numeric common-reference country identifier.', 'type_data' => 'I', 'type_field' => 'REF', 'is_indexed' => true],
    'registration_identifier_list' => ['field_label' => 'Registration Identifiers', 'field_description' => 'Masked, typed registration identifiers.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'date_established' => ['field_label' => 'Date Established', 'field_description' => 'Best supported organization establishment date.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'type_date_precision' => ['field_label' => 'Date Precision', 'field_description' => 'Controlled precision of date_established.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'type_date_qualifier' => ['field_label' => 'Date Qualifier', 'field_description' => 'Controlled qualifier of date_established.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'status_organization' => ['field_label' => 'Organization Status', 'field_description' => 'Real-world organization status.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_indexed' => true],
    'primary_establishment_id' => ['field_label' => 'Primary Establishment ID', 'field_description' => 'Optional primary public establishment.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'primary_media_image_id' => ['field_label' => 'Primary Media Image ID', 'field_description' => 'Optional primary image reference.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'Latest adequate confirmation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_indexed' => true],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle independent of organization status.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true, 'is_indexed' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];

$organization_main_subfield_property = [
    'alternate_name_list.type_name_variant' => ['field_label' => 'Name Variant Type', 'field_description' => 'Controlled name-variant type.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
    'alternate_name_list.name_variant_text' => ['field_label' => 'Name Variant Text', 'field_description' => 'Organization name variant text for the specified variant type.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_mandatory' => true],
    'registration_identifier_list.type_registration_identifier' => ['field_label' => 'Registration Identifier Type', 'field_description' => 'Controlled registration identifier type.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
    'registration_identifier_list.identifier_masked' => ['field_label' => 'Masked Identifier', 'field_description' => 'Display-safe masked identifier.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_mandatory' => true],
    'registration_identifier_list.country_id' => ['field_label' => 'Issuing Country ID', 'field_description' => 'Numeric issuing-country reference.', 'type_data' => 'I', 'type_field' => 'REF'],
];

$organization_main_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'slug_unique', 'index_name' => 'uq_organization_main_slug', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => true, 'index_field_list' => [['field_name' => 'organization_slug', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 20],
    ['index_key' => 'classification', 'index_name' => 'ix_organization_main_type_country_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'type_organization', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'country_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_record_lifecycle', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 30],
];

$organization_main_boundary = ['owns' => ['reusable organization identity, classification, bounded names, and masked registration summaries'], 'references' => ['establishment and media records'], 'does_not_own' => ['establishment identity, contacts, person relationships, workspace access, or evidence files']];

return [
    'organization_main_default' => $organization_main_default,
    'multilingual_text_sample' => $multilingual_text_sample,
    'organization_main' => $organization_main,
    'organization_main_field_order' => $organization_main_field_order,
    'organization_main_embedded_structure' => $organization_main_embedded_structure,
    'organization_main_field_property' => $organization_main_field_property,
    'organization_main_subfield_property' => $organization_main_subfield_property,
    'organization_main_index_list' => $organization_main_index_list,
    'organization_main_boundary' => $organization_main_boundary,
];
