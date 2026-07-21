<?php
/**
 * Title: Massage Nexus Establishment Main Structure Guide
 * Version: 0.20
 * Collection: establishment_main
 * Description: Stores one establishment or supported provider's public profile and directory classification record.
 * Purpose: Documents the establishment_main record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * - Public location and contact values must not expose private residential addresses or personal channels.
 * - The current public Spa page may use synthetic records until persistent establishment work is complete.
 */

$created_at = '2026-07-20T07:25:30Z';
$updated_at = '2026-07-21T04:24:17Z';
$establishment_main_default = [
    'mode_service_delivery' => [],
    'target_client_focus' => [],
    'landmark_list' => [],
    'contact_channel_list' => [],
    'status_record_lifecycle' => 'ACT',
];

$multilingual_text_sample = [
    'eng' => [
        'text' => 'Sample establishment text',
        'method_translation' => 'HUM',
        'status_review' => 'APR',
    ],
];

$establishment_main = [
    '_id' => 'Es7K2pQ9xR4tV8zN', // Canonical 16-character establishment identifier.
    'establishment_slug' => 'sample-wellness-spa', // Unique readable public route value.
    'display_name' => ['eng' => ['text' => 'Sample Wellness Spa']], // Approved multilingual operating name.
    'short_description' => ['eng' => ['text' => 'A calm neighborhood wellness spa.']], // Concise multilingual public summary.
    'type_spa' => 'DAY', // Primary spa or establishment classification.
    'level_spa_market' => 'MID', // Market-position classification.
    'type_physical_setting' => 'COM', // Physical setting classification.
    'mode_service_delivery' => ['ONS'], // Supported service-delivery modes.
    'mode_access' => 'APO', // Appointment or access mode.
    'type_establishment_operation' => 'IND', // Operating structure classification.
    'status_establishment' => 'OPN', // Real-world operating status.
    'type_client_access' => 'PUB', // Client eligibility or access classification.
    'target_client_focus' => ['GEN'], // Intended client-focus classifications.
    'address_public' => 'Makati City, Metro Manila', // Approved public destination or privacy-reduced area.
    'coordinate_latitude' => 14.5547, // Public entrance or approved privacy-reduced latitude.
    'coordinate_longitude' => 121.0244, // Public entrance or approved privacy-reduced longitude.
    'direction_note' => ['eng' => ['text' => 'Use the entrance beside the pharmacy.']], // Multilingual arrival directions.
    'parking_note' => ['eng' => ['text' => 'Limited paid parking is available.']], // Multilingual parking information.
    'landmark_list' => [ // Bounded nearby public landmarks.
        ['landmark_name' => 'Sample Mall', 'walking_duration_minute' => 5, 'direction_note' => ['eng' => ['text' => 'Walk east from the main entrance.']]],
    ], // Bounded nearby public landmarks.
    'contact_channel_list' => [ // Approved public business contact channels.
        ['type_contact_channel' => 'PHN', 'type_contact_number' => 'BUS', 'contact_label' => 'Reservations', 'contact_value' => '+63 2 8123 4567', 'contact_url' => 'tel:+63281234567', 'status_contact_channel' => 'ACT', 'last_confirmed_at' => '2026-07-21T04:08:58Z'],
    ], // Approved public business contact channels.
    'status_record_lifecycle' => 'ACT', // Database record lifecycle state.
    'created_at' => '2026-07-21T04:08:58Z', // UTC record creation time.
    'updated_at' => '2026-07-21T04:08:58Z', // UTC record update time.
    'last_confirmed_at' => '2026-07-21T04:08:58Z', // UTC time public facts were last confirmed.
];

$establishment_main_field_order = [
    '_id', 'establishment_slug', 'display_name', 'short_description', 'type_spa',
    'level_spa_market', 'type_physical_setting', 'mode_service_delivery', 'mode_access',
    'type_establishment_operation', 'status_establishment', 'type_client_access',
    'target_client_focus', 'address_public', 'coordinate_latitude', 'coordinate_longitude',
    'direction_note', 'parking_note', 'landmark_list', 'contact_channel_list',
    'status_record_lifecycle', 'created_at', 'updated_at', 'last_confirmed_at',
];

$establishment_main_embedded_structure = [
    'landmark_list' => [
        'landmark_name' => 'Sample Mall',
        'walking_duration_minute' => 5,
        'direction_note' => ['eng' => ['text' => 'Walk east from the main entrance.']],
    ],
    'contact_channel_list' => [
        'type_contact_channel' => 'PHN',
        'type_contact_number' => 'BUS',
        'contact_label' => 'Reservations',
        'contact_value' => '+63 2 8123 4567',
        'contact_url' => 'tel:+63281234567',
        'status_contact_channel' => 'ACT',
        'last_confirmed_at' => '2026-07-21T04:08:58Z',
    ],
];

$establishment_main_field_property = [
    '_id' => ['field_label' => 'Establishment ID', 'field_description' => 'Canonical application-generated 16-character identifier.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'establishment_slug' => ['field_label' => 'Establishment Slug', 'field_description' => 'Unique readable public route value.', 'type_data' => 'S', 'is_indexed' => true],
    'display_name' => ['field_label' => 'Display Name', 'field_description' => 'Approved multilingual public operating name.', 'type_data' => 'O', 'is_translatable' => true, 'is_mandatory' => true],
    'short_description' => ['field_label' => 'Short Description', 'field_description' => 'Concise multilingual public summary.', 'type_data' => 'O', 'is_translatable' => true],
    'type_spa' => ['field_label' => 'Spa Type', 'field_description' => 'Primary spa or establishment classification.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'level_spa_market' => ['field_label' => 'Spa Market Level', 'field_description' => 'Market-position classification.', 'type_data' => 'S', 'is_indexed' => true],
    'type_physical_setting' => ['field_label' => 'Physical Setting Type', 'field_description' => 'Physical setting classification.', 'type_data' => 'S', 'is_indexed' => true],
    'mode_service_delivery' => ['field_label' => 'Service Delivery Modes', 'field_description' => 'Supported service-delivery mode codes.', 'type_data' => 'A', 'is_indexed' => true],
    'mode_access' => ['field_label' => 'Access Mode', 'field_description' => 'Appointment, walk-in, or other access mode.', 'type_data' => 'S', 'is_indexed' => true],
    'type_establishment_operation' => ['field_label' => 'Establishment Operation Type', 'field_description' => 'Operating structure classification.', 'type_data' => 'S', 'is_indexed' => true],
    'status_establishment' => ['field_label' => 'Establishment Status', 'field_description' => 'Real-world operating status.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'type_client_access' => ['field_label' => 'Client Access Type', 'field_description' => 'Client eligibility or access classification.', 'type_data' => 'S', 'is_indexed' => true],
    'target_client_focus' => ['field_label' => 'Target Client Focus', 'field_description' => 'Intended client-focus classification codes.', 'type_data' => 'A', 'is_indexed' => true],
    'address_public' => ['field_label' => 'Public Address', 'field_description' => 'Approved public destination or privacy-reduced area.', 'type_data' => 'S'],
    'coordinate_latitude' => ['field_label' => 'Latitude', 'field_description' => 'Public entrance or approved privacy-reduced latitude.', 'type_data' => 'D', 'min_number' => -90, 'max_number' => 90],
    'coordinate_longitude' => ['field_label' => 'Longitude', 'field_description' => 'Public entrance or approved privacy-reduced longitude.', 'type_data' => 'D', 'min_number' => -180, 'max_number' => 180],
    'direction_note' => ['field_label' => 'Direction Note', 'field_description' => 'Multilingual arrival directions.', 'type_data' => 'O', 'is_translatable' => true],
    'parking_note' => ['field_label' => 'Parking Note', 'field_description' => 'Multilingual parking information.', 'type_data' => 'O', 'is_translatable' => true],
    'landmark_list' => ['field_label' => 'Landmark List', 'field_description' => 'Bounded nearby public landmarks.', 'type_data' => 'A'],
    'contact_channel_list' => ['field_label' => 'Contact Channel List', 'field_description' => 'Approved public business contact channels.', 'type_data' => 'A'],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle independent of operating status.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC record update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'UTC time public facts were last confirmed.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_indexed' => true],
];

$establishment_main_subfield_property = [
    'landmark_list.landmark_name' => ['field_label' => 'Landmark Name', 'field_description' => 'Public landmark name.', 'type_data' => 'S', 'is_mandatory' => true],
    'landmark_list.walking_duration_minute' => ['field_label' => 'Walking Duration Minute', 'field_description' => 'Approximate non-negative walk duration.', 'type_data' => 'I', 'min_number' => 0],
    'landmark_list.direction_note' => ['field_label' => 'Landmark Direction Note', 'field_description' => 'Multilingual directions from the landmark.', 'type_data' => 'O', 'is_translatable' => true],
    'contact_channel_list.type_contact_channel' => ['field_label' => 'Contact Channel Type', 'field_description' => 'Controlled public channel type.', 'type_data' => 'S', 'is_mandatory' => true],
    'contact_channel_list.type_contact_number' => ['field_label' => 'Contact Number Type', 'field_description' => 'Optional telephone subtype.', 'type_data' => 'S'],
    'contact_channel_list.contact_label' => ['field_label' => 'Contact Label', 'field_description' => 'Public purpose label for the channel.', 'type_data' => 'S', 'is_mandatory' => true],
    'contact_channel_list.contact_value' => ['field_label' => 'Contact Value', 'field_description' => 'Human-readable public contact value.', 'type_data' => 'S', 'is_mandatory' => true],
    'contact_channel_list.contact_url' => ['field_label' => 'Contact URL', 'field_description' => 'Normalized safe action URL.', 'type_data' => 'S', 'is_mandatory' => true],
    'contact_channel_list.status_contact_channel' => ['field_label' => 'Contact Channel Status', 'field_description' => 'Controlled channel lifecycle state.', 'type_data' => 'S'],
    'contact_channel_list.last_confirmed_at' => ['field_label' => 'Channel Last Confirmed At', 'field_description' => 'UTC time the channel was last confirmed.', 'type_data' => 'S', 'type_field' => 'DTS'],
];

$establishment_main_index_list = [
    [
        'index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false,
        'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10,
    ],
    [
        'index_key' => 'establishment_slug_unique', 'index_name' => 'uq_establishment_main_establishment_slug', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => true,
        'index_field_list' => [['field_name' => 'establishment_slug', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 20,
    ],
];

$establishment_main_boundary = [
    'owns' => ['public establishment identity, directory classification, public location, public contact channels, and lifecycle'],
    'references' => ['controlled classifications in Massage Nexus and shared taxonomy datasets'],
    'does_not_own' => ['private residential address', 'private personal contact channels', 'user relationships and permissions', 'reviews, ratings, services, bookings, and media records'],
];

return [
    'establishment_main_default' => $establishment_main_default,
    'multilingual_text_sample' => $multilingual_text_sample,
    'establishment_main' => $establishment_main,
    'establishment_main_field_order' => $establishment_main_field_order,
    'establishment_main_embedded_structure' => $establishment_main_embedded_structure,
    'establishment_main_field_property' => $establishment_main_field_property,
    'establishment_main_subfield_property' => $establishment_main_subfield_property,
    'establishment_main_index_list' => $establishment_main_index_list,
    'establishment_main_boundary' => $establishment_main_boundary,
];
