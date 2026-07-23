<?php
/**
 * Title: Massage Nexus Establishment Main Structure Guide
 * Version: 1.50
 * Collection: establishment_main
 * Description: Stores one establishment or supported provider's public profile and directory classification record.
 * Purpose: Documents the establishment_main record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * - Public location and contact values must not expose private residential addresses or personal channels.
 * - Contact channels and operating schedules are loaded from their authoritative collections; they are not duplicated as unsourced snapshots here.
 * - The current public Spa page may use synthetic records until persistent establishment work is complete.
 */

$created_at = '2026-07-20T07:25:30Z';
$updated_at = '2026-07-22T05:16:05Z';
$establishment_main_default = [
    'mode_service_delivery' => [],
    'target_client_focus' => [],
    'previous_slug_list' => [],
    'landmark_list' => [],
    'treatment_area_list' => [],
    'amenity_list' => [],
    'accessibility_feature_list' => [],
    'payment_method_list' => [],
    'status_record_lifecycle' => 'ACT',
    'revision_number' => 1,
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
    'previous_slug_list' => [ // Retired public slugs preserved for redirect resolution after a rename or merge (docs/02-governance/edit-system.txt section 19).
        ['slug' => 'old-sample-wellness-spa', 'retired_at' => '2026-06-01T00:00:00Z'],
    ],
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
    'location_point' => ['type' => 'Point', 'coordinates' => [121.0244, 14.5547]], // GeoJSON public entrance or approved privacy-reduced point; longitude precedes latitude.
    'type_coordinate' => 'ENT', // Meaning of the stored coordinate.
    'level_coordinate_confidence' => 'H', // Confidence in the mapped point.
    'direction_note' => ['eng' => ['text' => 'Use the entrance beside the pharmacy.']], // Multilingual arrival directions.
    'parking_note' => ['eng' => ['text' => 'Limited paid parking is available.']], // Multilingual parking information.
    'landmark_list' => [ // Bounded nearby public landmarks.
        ['landmark_name' => 'Sample Mall', 'walking_duration_minute' => 5, 'direction_note' => ['eng' => ['text' => 'Walk east from the main entrance.']]],
    ],
    'treatment_area_list' => [['type_treatment_area' => 'ER', 'level_treatment_privacy' => 'PV', 'type_treatment_capacity' => 'I', 'quantity' => 4]], // Bounded public treatment-area summary.
    'shower_availability' => 'IR', // Shower access classification.
    'sauna_availability' => 'NA', // Sauna access classification.
    'steam_room_availability' => 'NA', // Steam room access classification.
    'jacuzzi_availability' => 'NA', // Jacuzzi access classification.
    'locker_availability' => 'NR', // Locker access classification.
    'couple_room_availability' => 'NA', // Couple-room access classification.
    'private_room_availability' => 'IR', // Private-room access classification.
    'curtain_divider_information' => 'NA', // Curtain/divider classification.
    'air_conditioning_information' => 'NA', // Air-conditioning classification.
    'room_types' => ['ER'], // Confirmed room-type codes.
    'bed_mat_chair_setup' => [], // Confirmed bed/mat/chair setup codes.
    'amenity_list' => ['PRK', 'WIFI', 'TOWL'], // Confirmed public amenity codes.
    'accessibility_feature_list' => ['SFE', 'ELV'], // Confirmed accessibility codes.
    'payment_method_list' => ['CSH', 'CC'], // Accepted payment-method codes.
    'parking_availability_list' => ['PRK_ONS_FREE'], // Confirmed parking availability codes.
    'date_opened' => '2024-03-01', // Best-supported opening date.
    'date_opened_precision' => 'M', // Precision of date_opened.
    'date_opened_qualifier' => 'EXA', // Qualifier of date_opened.
    'date_closed' => null, // Best-supported closure date, when applicable.
    'date_closed_precision' => null, // Precision of date_closed.
    'date_closed_qualifier' => null, // Qualifier of date_closed.
    'primary_media_image_id' => 'Im7K2pQ9xR4tV8zN', // Primary public image reference.
    'status_record_lifecycle' => 'ACT', // Database record lifecycle state.
    'revision_number' => 1, // Monotonic optimistic-concurrency token; the required concurrency token distinct from updated_at (docs/02-governance/edit-system.txt section 16).
    'created_at' => '2026-07-21T04:08:58Z', // UTC record creation time.
    'updated_at' => '2026-07-21T04:08:58Z', // UTC record update time.
    'last_confirmed_at' => '2026-07-21T04:08:58Z', // UTC time public facts were last confirmed.
];

$establishment_main_field_order = [
    '_id', 'establishment_slug', 'previous_slug_list', 'display_name', 'official_name', 'short_description', 'full_description', 'type_spa',
    'level_spa_market', 'type_physical_setting', 'mode_service_delivery', 'mode_access',
    'type_establishment_operation', 'status_establishment', 'type_client_access',
    'target_client_focus', 'country_id', 'geographic_area_id_list', 'street_address', 'building_name',
    'floor_label', 'unit_label', 'postal_code', 'address_public', 'level_address_visibility',
    'location_point', 'type_coordinate', 'level_coordinate_confidence',
    'direction_note', 'parking_note', 'landmark_list', 'treatment_area_list', 'shower_availability', 'sauna_availability', 'steam_room_availability', 'jacuzzi_availability', 'locker_availability', 'couple_room_availability', 'private_room_availability', 'curtain_divider_information', 'air_conditioning_information', 'room_types', 'bed_mat_chair_setup', 'amenity_list',
    'accessibility_feature_list', 'payment_method_list', 'parking_availability_list', 'date_opened', 'date_opened_precision', 'date_opened_qualifier', 'date_closed', 'date_closed_precision', 'date_closed_qualifier', 'primary_media_image_id',
    'status_record_lifecycle', 'revision_number', 'created_at', 'updated_at', 'last_confirmed_at',
];

$establishment_main_embedded_structure = [
    'previous_slug_list' => [
        'slug' => 'old-sample-wellness-spa',
        'retired_at' => '2026-06-01T00:00:00Z',
    ],
    'location_point' => [
        'type' => 'Point',
        'coordinates' => [121.0244, 14.5547],
    ],
    'landmark_list' => [
        'landmark_name' => 'Sample Mall',
        'walking_duration_minute' => 5,
        'direction_note' => ['eng' => ['text' => 'Walk east from the main entrance.']],
    ],
    'treatment_area_list' => ['type_treatment_area' => 'ER', 'level_treatment_privacy' => 'PV', 'type_treatment_capacity' => 'I', 'quantity' => 4],
];

$establishment_main_field_property = [
    '_id' => ['field_label' => 'Establishment ID', 'field_description' => 'Canonical application-generated 16-character identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true],
    'establishment_slug' => ['field_label' => 'Establishment Slug', 'field_description' => 'Unique readable public route value.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_indexed' => true],
    'previous_slug_list' => ['field_label' => 'Previous Slug List', 'field_description' => 'Bounded list of retired public slugs preserved so old URLs can resolve to a durable redirect after a rename or merge (docs/02-governance/edit-system.txt section 19).', 'type_data' => 'A', 'type_field' => 'JSE'],
    'display_name' => ['field_label' => 'Display Name', 'field_description' => 'Approved multilingual public operating name.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true, 'is_mandatory' => true],
    'official_name' => ['field_label' => 'Official Name', 'field_description' => 'Supported official or registered place name when publishable.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'short_description' => ['field_label' => 'Short Description', 'field_description' => 'Concise multilingual public summary.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true],
    'full_description' => ['field_label' => 'Full Description', 'field_description' => 'Longer multilingual public profile description.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true],
    'type_spa' => ['field_label' => 'Spa Type', 'field_description' => 'Primary spa or establishment classification.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true, 'is_indexed' => true],
    'level_spa_market' => ['field_label' => 'Spa Market Level', 'field_description' => 'Market-position classification.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_indexed' => true],
    'type_physical_setting' => ['field_label' => 'Physical Setting Type', 'field_description' => 'Physical setting classification.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_indexed' => true],
    'mode_service_delivery' => ['field_label' => 'Service Delivery Modes', 'field_description' => 'Supported service-delivery mode codes.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_indexed' => true],
    'mode_access' => ['field_label' => 'Access Mode', 'field_description' => 'Appointment, walk-in, or other access mode.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_indexed' => true],
    'type_establishment_operation' => ['field_label' => 'Establishment Operation Type', 'field_description' => 'Operating structure classification.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_indexed' => true],
    'status_establishment' => ['field_label' => 'Establishment Status', 'field_description' => 'Real-world operating status.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true, 'is_indexed' => true],
    'type_client_access' => ['field_label' => 'Client Access Type', 'field_description' => 'Client eligibility or access classification.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_indexed' => true],
    'target_client_focus' => ['field_label' => 'Target Client Focus', 'field_description' => 'Intended client-focus classification codes.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_indexed' => true],
    'country_id' => ['field_label' => 'Country ID', 'field_description' => 'Numeric common-reference country identifier.', 'type_data' => 'I', 'type_field' => 'REF', 'is_relational' => true, 'is_indexed' => true],
    'geographic_area_id_list' => ['field_label' => 'Geographic Area IDs', 'field_description' => 'Approved geographic-area references when datasets are available.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true, 'is_indexed' => true],
    'street_address' => ['field_label' => 'Street Address', 'field_description' => 'Public street-level address when permitted.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'building_name' => ['field_label' => 'Building Name', 'field_description' => 'Public building name.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'floor_label' => ['field_label' => 'Floor Label', 'field_description' => 'Public floor label.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'unit_label' => ['field_label' => 'Unit Label', 'field_description' => 'Public unit label.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'postal_code' => ['field_label' => 'Postal Code', 'field_description' => 'Postal code.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'address_public' => ['field_label' => 'Public Address', 'field_description' => 'Approved public destination or privacy-reduced area.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'level_address_visibility' => ['field_label' => 'Address Visibility Level', 'field_description' => 'Controlled amount of address detail permitted for display.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_indexed' => true],
    'location_point' => ['field_label' => 'Location Point', 'field_description' => 'GeoJSON Point for the public entrance or approved privacy-reduced location, storing longitude before latitude.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_indexed' => true],
    'type_coordinate' => ['field_label' => 'Coordinate Type', 'field_description' => 'Controlled meaning of the stored coordinate.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'level_coordinate_confidence' => ['field_label' => 'Coordinate Confidence', 'field_description' => 'Controlled confidence that the point represents its stated coordinate type.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'direction_note' => ['field_label' => 'Direction Note', 'field_description' => 'Multilingual arrival directions.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true],
    'parking_note' => ['field_label' => 'Parking Note', 'field_description' => 'Multilingual parking information.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true],
    'landmark_list' => ['field_label' => 'Landmark List', 'field_description' => 'Bounded nearby public landmarks.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'treatment_area_list' => ['field_label' => 'Treatment Area List', 'field_description' => 'Bounded public treatment-area summary; individual bookable resources are separate.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'shower_availability' => ['field_label' => 'Shower Availability', 'field_description' => 'Controlled shower-access classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'sauna_availability' => ['field_label' => 'Sauna Availability', 'field_description' => 'Controlled sauna-access classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'steam_room_availability' => ['field_label' => 'Steam Room Availability', 'field_description' => 'Controlled steam-room-access classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'jacuzzi_availability' => ['field_label' => 'Jacuzzi Availability', 'field_description' => 'Controlled jacuzzi-access classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'locker_availability' => ['field_label' => 'Locker Availability', 'field_description' => 'Controlled locker-access classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'couple_room_availability' => ['field_label' => 'Couple Room Availability', 'field_description' => 'Controlled couple-room-access classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'private_room_availability' => ['field_label' => 'Private Room Availability', 'field_description' => 'Controlled private-room-access classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'curtain_divider_information' => ['field_label' => 'Room Divider Information', 'field_description' => 'Controlled curtain-or-divider classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'air_conditioning_information' => ['field_label' => 'Air-Conditioning Information', 'field_description' => 'Controlled air-conditioning classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'room_types' => ['field_label' => 'Room Types', 'field_description' => 'Confirmed treatment-room-type codes.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'bed_mat_chair_setup' => ['field_label' => 'Seating and Sleeping Setup', 'field_description' => 'Confirmed bed, mat, or chair setup codes.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'amenity_list' => ['field_label' => 'Amenity List', 'field_description' => 'Confirmed public amenity codes.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'accessibility_feature_list' => ['field_label' => 'Accessibility Feature List', 'field_description' => 'Confirmed public accessibility codes.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'payment_method_list' => ['field_label' => 'Payment Method List', 'field_description' => 'Accepted payment-method codes.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'parking_availability_list' => ['field_label' => 'Parking Availability', 'field_description' => 'Confirmed parking-availability codes.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'date_opened' => ['field_label' => 'Date Opened', 'field_description' => 'Best-supported opening date, denormalized from the authoritative establishment_event opening record for display and query.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'date_opened_precision' => ['field_label' => 'Date Opened Precision', 'field_description' => 'Controlled precision of date_opened, matching establishment_event.type_date_precision.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'date_opened_qualifier' => ['field_label' => 'Date Opened Qualifier', 'field_description' => 'Controlled qualifier of date_opened, matching establishment_event.type_date_qualifier.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'date_closed' => ['field_label' => 'Date Closed', 'field_description' => 'Best-supported closure date when the establishment has ceased, relocated, or is temporarily closed; denormalized from the authoritative establishment_event closure record.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'date_closed_precision' => ['field_label' => 'Date Closed Precision', 'field_description' => 'Controlled precision of date_closed.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'date_closed_qualifier' => ['field_label' => 'Date Closed Qualifier', 'field_description' => 'Controlled qualifier of date_closed.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'primary_media_image_id' => ['field_label' => 'Primary Media Image ID', 'field_description' => 'Primary public image reference.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle independent of operating status.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true, 'is_indexed' => true],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token that increments by one on every accepted revision; the required concurrency token distinct from updated_at (docs/02-governance/edit-system.txt section 16).', 'type_data' => 'I', 'type_field' => 'NMB', 'is_mandatory' => true, 'min_number' => 1],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC record update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'UTC time public facts were last confirmed.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_indexed' => true],
];

$establishment_main_subfield_property = [
    'previous_slug_list.slug' => ['field_label' => 'Previous Slug', 'field_description' => 'A formerly active establishment_slug value.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_mandatory' => true],
    'previous_slug_list.retired_at' => ['field_label' => 'Slug Retired At', 'field_description' => 'UTC time this slug stopped being the current public slug.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'location_point.type' => ['field_label' => 'GeoJSON Geometry Type', 'field_description' => 'GeoJSON geometry discriminator, fixed to Point for an establishment location.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_mandatory' => true],
    'location_point.coordinates' => ['field_label' => 'GeoJSON Coordinates', 'field_description' => 'Two-number GeoJSON coordinate array ordered as longitude then latitude.', 'type_data' => 'A', 'type_field' => 'JSE', 'is_mandatory' => true],
    'location_point.coordinates.*' => ['field_label' => 'GeoJSON Coordinate Number', 'field_description' => 'One floating-point longitude or latitude member of the ordered coordinate pair.', 'type_data' => 'F', 'type_field' => 'NMB', 'is_mandatory' => true],
    'landmark_list.landmark_name' => ['field_label' => 'Landmark Name', 'field_description' => 'Public landmark name.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_mandatory' => true],
    'landmark_list.walking_duration_minute' => ['field_label' => 'Walking Duration Minute', 'field_description' => 'Approximate non-negative walk duration.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'landmark_list.direction_note' => ['field_label' => 'Landmark Direction Note', 'field_description' => 'Multilingual directions from the landmark.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true],
    'treatment_area_list.type_treatment_area' => ['field_label' => 'Treatment Area Type', 'field_description' => 'Controlled treatment-area type.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
    'treatment_area_list.level_treatment_privacy' => ['field_label' => 'Treatment Privacy Level', 'field_description' => 'Controlled privacy level.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'treatment_area_list.type_treatment_capacity' => ['field_label' => 'Treatment Capacity Type', 'field_description' => 'Controlled capacity class.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'treatment_area_list.quantity' => ['field_label' => 'Quantity', 'field_description' => 'Non-negative count when confirmed.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
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
        'index_key' => 'location_point', 'index_name' => 'ix_establishment_main_location_point', 'type_index' => 'GEO', 'is_unique' => false, 'is_sparse' => true,
        'index_field_list' => [['field_name' => 'location_point', 'type_index_mode' => '2DS', 'sort_order' => 10]], 'sort_order' => 40,
    ],
];

$establishment_main_boundary = [
    'owns' => ['public establishment identity, directory classification, current public location, bounded facility summaries, retired-slug history, and lifecycle'],
    'reference_field_list' => ['country_id', 'geographic_area_id_list', 'primary_media_image_id'],
    'does_not_own' => [
        'contact channels, owned by establishment_contact',
        'weekly operating intervals and dated exceptions, owned by establishment_schedule',
        'establishment-person and establishment-practitioner relationships, owned by establishment_person and establishment_practitioner',
        'provider menu offerings and pricing, owned by establishment_service',
        'sourced lifecycle and history events, owned by establishment_event',
        'private addresses, organization relationships, bookings, evidence, reviews, or media records',
    ],
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
