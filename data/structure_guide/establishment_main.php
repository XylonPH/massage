<?php
/**
 * Title: Massage Nexus Establishment Main Structure Guide
 * Version: 1.00
 * Collection: establishment_main
 * Description: Stores one establishment or supported provider's public profile and directory classification record.
 * Purpose: Documents the establishment_main record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * - Public location and contact values must not expose private residential addresses or personal channels.
 * - The current public Spa page may use synthetic records until persistent establishment work is complete.
 */

$created_at = '2026-07-20T07:25:30Z';
$updated_at = '2026-07-21T08:49:01Z';
$establishment_main_default = [
    'mode_service_delivery' => [],
    'target_client_focus' => [],
    'landmark_list' => [],
    'treatment_area_list' => [],
    'amenity_list' => [],
    'accessibility_feature_list' => [],
    'payment_method_list' => [],
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
    'official_name' => 'Sample Wellness Spa Incorporated', // Supported official or registered place name when publishable.
    'short_description' => ['eng' => ['text' => 'A calm neighborhood wellness spa.']], // Concise multilingual public summary.
    'full_description' => ['eng' => ['text' => 'A neighborhood establishment offering massage and wellness services.']], // Longer multilingual profile description.
    'type_spa' => 'DY', // Primary spa or establishment classification.
    'level_spa_market' => 'B', // Market-position classification.
    'type_physical_setting' => 'CU', // Physical setting classification.
    'mode_service_delivery' => ['OS'], // Supported service-delivery modes.
    'mode_access' => 'AP', // Appointment or access mode.
    'type_establishment_operation' => 'IN', // Operating structure classification.
    'status_establishment' => 'OP', // Real-world operating status.
    'type_client_access' => 'OA', // Client eligibility or access classification.
    'target_client_focus' => ['GP'], // Intended client-focus classifications.
    'country_id' => 608, // Numeric common-reference country identifier.
    'geographic_area_id_list' => [], // Approved geographic-area references when available.
    'street_address' => '123 Sample Street', // Public street-level address when permitted.
    'building_name' => 'Sample Building', // Public building name.
    'floor_label' => '2nd Floor', // Public floor label.
    'unit_label' => 'Unit 201', // Public unit label.
    'postal_code' => '1200', // Postal code.
    'address_public' => 'Makati City, Metro Manila', // Approved public destination or privacy-reduced area.
    'level_address_visibility' => 'FUL', // Approved address-detail visibility.
    'coordinate_latitude' => 14.5547, // Public entrance or approved privacy-reduced latitude.
    'coordinate_longitude' => 121.0244, // Public entrance or approved privacy-reduced longitude.
    'type_coordinate' => 'ENT', // Meaning of the stored coordinate.
    'level_coordinate_confidence' => 'H', // Confidence in the mapped point.
    'direction_note' => ['eng' => ['text' => 'Use the entrance beside the pharmacy.']], // Multilingual arrival directions.
    'parking_note' => ['eng' => ['text' => 'Limited paid parking is available.']], // Multilingual parking information.
    'landmark_list' => [ // Bounded nearby public landmarks.
        ['landmark_name' => 'Sample Mall', 'walking_duration_minute' => 5, 'direction_note' => ['eng' => ['text' => 'Walk east from the main entrance.']]],
    ], // Bounded nearby public landmarks.
    'treatment_area_list' => [['type_treatment_area' => 'ER', 'level_treatment_privacy' => 'PV', 'type_treatment_capacity' => 'I', 'quantity' => 4]], // Bounded public treatment-area summary.
    'amenity_list' => ['PRK', 'WIFI', 'TOWL'], // Confirmed public amenity codes.
    'accessibility_feature_list' => ['SFE', 'ELV'], // Confirmed accessibility codes.
    'payment_method_list' => ['CSH', 'CC'], // Accepted payment-method codes.
    'primary_media_image_id' => 'Im7K2pQ9xR4tV8zN', // Primary public image reference.
    'status_record_lifecycle' => 'ACT', // Database record lifecycle state.
    'created_at' => '2026-07-21T04:08:58Z', // UTC record creation time.
    'updated_at' => '2026-07-21T04:08:58Z', // UTC record update time.
    'last_confirmed_at' => '2026-07-21T04:08:58Z', // UTC time public facts were last confirmed.
];

$establishment_main_field_order = [
    '_id', 'establishment_slug', 'display_name', 'official_name', 'short_description', 'full_description', 'type_spa',
    'level_spa_market', 'type_physical_setting', 'mode_service_delivery', 'mode_access',
    'type_establishment_operation', 'status_establishment', 'type_client_access',
    'target_client_focus', 'country_id', 'geographic_area_id_list', 'street_address', 'building_name',
    'floor_label', 'unit_label', 'postal_code', 'address_public', 'level_address_visibility',
    'coordinate_latitude', 'coordinate_longitude', 'type_coordinate', 'level_coordinate_confidence',
    'direction_note', 'parking_note', 'landmark_list', 'treatment_area_list', 'amenity_list',
    'accessibility_feature_list', 'payment_method_list', 'primary_media_image_id',
    'status_record_lifecycle', 'created_at', 'updated_at', 'last_confirmed_at',
];

$establishment_main_embedded_structure = [
    'landmark_list' => [
        'landmark_name' => 'Sample Mall',
        'walking_duration_minute' => 5,
        'direction_note' => ['eng' => ['text' => 'Walk east from the main entrance.']],
    ],
    'treatment_area_list' => ['type_treatment_area' => 'ER', 'level_treatment_privacy' => 'PV', 'type_treatment_capacity' => 'I', 'quantity' => 4],
];

$establishment_main_field_property = [
    '_id' => ['field_label' => 'Establishment ID', 'field_description' => 'Canonical application-generated 16-character identifier.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'establishment_slug' => ['field_label' => 'Establishment Slug', 'field_description' => 'Unique readable public route value.', 'type_data' => 'S', 'is_indexed' => true],
    'display_name' => ['field_label' => 'Display Name', 'field_description' => 'Approved multilingual public operating name.', 'type_data' => 'O', 'is_translatable' => true, 'is_mandatory' => true],
    'official_name' => ['field_label' => 'Official Name', 'field_description' => 'Supported official or registered place name when publishable.', 'type_data' => 'S'],
    'short_description' => ['field_label' => 'Short Description', 'field_description' => 'Concise multilingual public summary.', 'type_data' => 'O', 'is_translatable' => true],
    'full_description' => ['field_label' => 'Full Description', 'field_description' => 'Longer multilingual public profile description.', 'type_data' => 'O', 'is_translatable' => true],
    'type_spa' => ['field_label' => 'Spa Type', 'field_description' => 'Primary spa or establishment classification.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'level_spa_market' => ['field_label' => 'Spa Market Level', 'field_description' => 'Market-position classification.', 'type_data' => 'S', 'is_indexed' => true],
    'type_physical_setting' => ['field_label' => 'Physical Setting Type', 'field_description' => 'Physical setting classification.', 'type_data' => 'S', 'is_indexed' => true],
    'mode_service_delivery' => ['field_label' => 'Service Delivery Modes', 'field_description' => 'Supported service-delivery mode codes.', 'type_data' => 'A', 'is_indexed' => true],
    'mode_access' => ['field_label' => 'Access Mode', 'field_description' => 'Appointment, walk-in, or other access mode.', 'type_data' => 'S', 'is_indexed' => true],
    'type_establishment_operation' => ['field_label' => 'Establishment Operation Type', 'field_description' => 'Operating structure classification.', 'type_data' => 'S', 'is_indexed' => true],
    'status_establishment' => ['field_label' => 'Establishment Status', 'field_description' => 'Real-world operating status.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'type_client_access' => ['field_label' => 'Client Access Type', 'field_description' => 'Client eligibility or access classification.', 'type_data' => 'S', 'is_indexed' => true],
    'target_client_focus' => ['field_label' => 'Target Client Focus', 'field_description' => 'Intended client-focus classification codes.', 'type_data' => 'A', 'is_indexed' => true],
    'country_id' => ['field_label' => 'Country ID', 'field_description' => 'Numeric common-reference country identifier.', 'type_data' => 'I', 'is_indexed' => true],
    'geographic_area_id_list' => ['field_label' => 'Geographic Area IDs', 'field_description' => 'Approved geographic-area references when datasets are available.', 'type_data' => 'A', 'is_indexed' => true],
    'street_address' => ['field_label' => 'Street Address', 'field_description' => 'Public street-level address when permitted.', 'type_data' => 'S'],
    'building_name' => ['field_label' => 'Building Name', 'field_description' => 'Public building name.', 'type_data' => 'S'],
    'floor_label' => ['field_label' => 'Floor Label', 'field_description' => 'Public floor label.', 'type_data' => 'S'],
    'unit_label' => ['field_label' => 'Unit Label', 'field_description' => 'Public unit label.', 'type_data' => 'S'],
    'postal_code' => ['field_label' => 'Postal Code', 'field_description' => 'Postal code.', 'type_data' => 'S'],
    'address_public' => ['field_label' => 'Public Address', 'field_description' => 'Approved public destination or privacy-reduced area.', 'type_data' => 'S'],
    'level_address_visibility' => ['field_label' => 'Address Visibility Level', 'field_description' => 'Controlled amount of address detail permitted for display.', 'type_data' => 'S', 'is_indexed' => true],
    'coordinate_latitude' => ['field_label' => 'Latitude', 'field_description' => 'Public entrance or approved privacy-reduced latitude.', 'type_data' => 'D', 'min_number' => -90, 'max_number' => 90],
    'coordinate_longitude' => ['field_label' => 'Longitude', 'field_description' => 'Public entrance or approved privacy-reduced longitude.', 'type_data' => 'D', 'min_number' => -180, 'max_number' => 180],
    'type_coordinate' => ['field_label' => 'Coordinate Type', 'field_description' => 'Controlled meaning of the stored coordinate.', 'type_data' => 'S'],
    'level_coordinate_confidence' => ['field_label' => 'Coordinate Confidence', 'field_description' => 'Controlled confidence that the point represents its stated coordinate type.', 'type_data' => 'S'],
    'direction_note' => ['field_label' => 'Direction Note', 'field_description' => 'Multilingual arrival directions.', 'type_data' => 'O', 'is_translatable' => true],
    'parking_note' => ['field_label' => 'Parking Note', 'field_description' => 'Multilingual parking information.', 'type_data' => 'O', 'is_translatable' => true],
    'landmark_list' => ['field_label' => 'Landmark List', 'field_description' => 'Bounded nearby public landmarks.', 'type_data' => 'A'],
    'treatment_area_list' => ['field_label' => 'Treatment Area List', 'field_description' => 'Bounded public treatment-area summary; individual bookable resources are separate.', 'type_data' => 'A'],
    'amenity_list' => ['field_label' => 'Amenity List', 'field_description' => 'Confirmed public amenity codes.', 'type_data' => 'A'],
    'accessibility_feature_list' => ['field_label' => 'Accessibility Feature List', 'field_description' => 'Confirmed public accessibility codes.', 'type_data' => 'A'],
    'payment_method_list' => ['field_label' => 'Payment Method List', 'field_description' => 'Accepted payment-method codes.', 'type_data' => 'A'],
    'primary_media_image_id' => ['field_label' => 'Primary Media Image ID', 'field_description' => 'Primary public image reference.', 'type_data' => 'S'],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle independent of operating status.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC record update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'UTC time public facts were last confirmed.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_indexed' => true],
];

$establishment_main_subfield_property = [
    'landmark_list.landmark_name' => ['field_label' => 'Landmark Name', 'field_description' => 'Public landmark name.', 'type_data' => 'S', 'is_mandatory' => true],
    'landmark_list.walking_duration_minute' => ['field_label' => 'Walking Duration Minute', 'field_description' => 'Approximate non-negative walk duration.', 'type_data' => 'I', 'min_number' => 0],
    'landmark_list.direction_note' => ['field_label' => 'Landmark Direction Note', 'field_description' => 'Multilingual directions from the landmark.', 'type_data' => 'O', 'is_translatable' => true],
    'treatment_area_list.type_treatment_area' => ['field_label' => 'Treatment Area Type', 'field_description' => 'Controlled treatment-area type.', 'type_data' => 'S', 'is_mandatory' => true],
    'treatment_area_list.level_treatment_privacy' => ['field_label' => 'Treatment Privacy Level', 'field_description' => 'Controlled privacy level.', 'type_data' => 'S'],
    'treatment_area_list.type_treatment_capacity' => ['field_label' => 'Treatment Capacity Type', 'field_description' => 'Controlled capacity class.', 'type_data' => 'S'],
    'treatment_area_list.quantity' => ['field_label' => 'Quantity', 'field_description' => 'Non-negative count when confirmed.', 'type_data' => 'I', 'min_number' => 0],
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
    [
        'index_key' => 'directory_filter', 'index_name' => 'ix_establishment_main_geo_classification_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false,
        'index_field_list' => [['field_name' => 'country_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'type_spa', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_establishment', 'type_index_mode' => 'ASC', 'sort_order' => 30], ['field_name' => 'status_record_lifecycle', 'type_index_mode' => 'ASC', 'sort_order' => 40]], 'sort_order' => 30,
    ],
    [
        'index_key' => 'coordinate', 'index_name' => 'ix_establishment_main_coordinate', 'type_index' => 'GEO', 'is_unique' => false, 'is_sparse' => true,
        'index_field_list' => [['field_name' => 'coordinate_longitude', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'coordinate_latitude', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 40,
    ],
];

$establishment_main_boundary = [
    'owns' => ['public establishment identity, directory classification, current public location, bounded facility summaries, and lifecycle'],
    'references' => ['controlled classifications, geographic references, and primary media'],
    'does_not_own' => ['contact channels, operating schedules, private addresses, organization or person relationships, services, bookings, evidence, reviews, or media records'],
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
