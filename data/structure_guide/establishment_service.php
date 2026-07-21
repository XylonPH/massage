<?php
/**
 * Title: Massage Nexus Establishment Service Structure Guide
 * Version: 1.00
 * Collection: establishment_service
 * Description: Stores provider menu offerings, packages, combinations, add-ons, and facility-access products.
 * Purpose: Preserves provider-specific commercial presentation while mapping it to normalized service_main records.
 */
$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T08:23:43Z';
$establishment_service_default = ['normalized_service_mapping_list' => [], 'duration_option_list' => [], 'price_option_list' => [], 'component_list' => [], 'type_booking_method' => [], 'client_restriction_list' => [], 'included_facility_list' => [], 'included_product_list' => [], 'is_featured' => false, 'status_establishment_service' => 'ACT', 'status_record_lifecycle' => 'ACT'];
$multilingual_text_sample = ['eng' => ['text' => 'Sample service text', 'method_translation' => 'HUM', 'status_review' => 'APR']];
$establishment_service = [
    '_id' => 'EsrK2pQ9xR4tV7zN', // Canonical offering identifier.
    'establishment_id' => 'Es7K2pQ9xR4tV8zN', // Offering owner.
    'establishment_service_slug' => 'signature-recovery-package', // Scoped route value.
    'display_name' => ['eng' => ['text' => 'Signature Recovery Package']], // Public offering name.
    'original_menu_name' => 'Signature Recovery Package', // Exact source menu wording.
    'short_description' => ['eng' => ['text' => 'A combined massage and steam-room package.']], // Short public summary.
    'full_description' => ['eng' => ['text' => 'Includes a massage session and facility access.']], // Full public description.
    'menu_group_name' => 'Packages', // Source menu grouping.
    'type_establishment_service' => 'PKG', // Offering presentation type.
    'status_establishment_service' => 'ACT', // Offering availability status.
    'normalized_service_mapping_list' => [['service_id' => 'Sv8K2pQ9xR4tV7zN', 'type_service_mapping' => 'PRI', 'level_confidence' => 'H', 'status_review' => 'APR', 'mapped_by_user_id' => 'U2pR7vX4kT9mC5qL', 'mapped_at' => '2026-07-21T08:23:43Z', 'note' => null]], // Normalized service mappings.
    'duration_option_list' => [['duration_minute' => 90, 'preparation_minute' => 10, 'cleanup_minute' => 10, 'status_availability' => 'AVL', 'note' => null]], // Bookable duration options.
    'price_option_list' => [['amount' => 1800.00, 'currency_id' => 111, 'type_price' => 'REG', 'duration_minute' => 90, 'client_price_context' => null, 'mode_service_delivery' => 'OS', 'practitioner_id' => null, 'is_tax_included' => true, 'mandatory_fee_list' => [], 'effective_from' => '2026-07-01', 'effective_until' => null, 'status_price' => 'ACT', 'first_observed_at' => '2026-07-01T00:00:00Z', 'last_confirmed_at' => '2026-07-20T00:00:00Z']], // Effective-dated price options.
    'component_list' => [['establishment_service_id' => null, 'service_id' => 'Sv8K2pQ9xR4tV7zN', 'component_name_snapshot' => 'Swedish massage', 'quantity' => 1, 'duration_minute' => 60, 'is_selectable' => false, 'is_optional' => false, 'sort_order' => 10]], // Package or combination components.
    'mode_service_delivery' => ['OS'], // Delivery modes.
    'type_booking_method' => ['PHN', 'WEB'], // Supported booking methods.
    'client_restriction_list' => [], // Explicit restrictions.
    'included_facility_list' => ['STM'], // Included facility codes.
    'included_product_list' => [], // Included product references or snapshots.
    'is_featured' => true, // Featured display flag.
    'display_order' => 10, // Provider display order.
    'first_observed_at' => '2026-07-01T00:00:00Z', // First observation.
    'last_observed_active_at' => '2026-07-20T00:00:00Z', // Latest active observation.
    'first_observed_inactive_at' => null, // First inactive observation.
    'first_confirmed_at' => '2026-07-02T00:00:00Z', // First confirmation.
    'last_confirmed_at' => '2026-07-20T00:00:00Z', // Latest confirmation.
    'research_source_id_list' => ['Sr8K2pQ9xR4tV7zN'], // Source records.
    'record_verification_id_list' => [], // Verification records.
    'status_record_lifecycle' => 'ACT', // Database lifecycle.
    'created_at' => $created_at, // UTC creation time.
    'updated_at' => $updated_at, // UTC update time.
];
$establishment_service_field_order = array_keys($establishment_service);
$establishment_service_embedded_structure = ['normalized_service_mapping_list' => $establishment_service['normalized_service_mapping_list'][0], 'duration_option_list' => $establishment_service['duration_option_list'][0], 'price_option_list' => $establishment_service['price_option_list'][0], 'component_list' => $establishment_service['component_list'][0]];
$establishment_service_field_property = [];
foreach ($establishment_service as $field_name => $sample_value) {
    $type_data = is_bool($sample_value) ? 'B' : (is_array($sample_value) ? (in_array($field_name, ['display_name', 'short_description', 'full_description'], true) ? 'O' : 'A') : (is_int($sample_value) ? 'I' : 'S'));
    $establishment_service_field_property[$field_name] = ['field_label' => ucwords(str_replace('_', ' ', $field_name)), 'field_description' => 'Establishment service field: ' . str_replace('_', ' ', $field_name) . '.', 'type_data' => $type_data];
}
$establishment_service_field_property['_id']['is_mandatory'] = true;
$establishment_service_field_property['establishment_id']['is_mandatory'] = true;
$establishment_service_field_property['display_name']['is_translatable'] = true;
$establishment_service_subfield_property = [
    'normalized_service_mapping_list.service_id' => ['field_label' => 'Service ID', 'field_description' => 'Normalized service reference.', 'type_data' => 'S', 'is_mandatory' => true],
    'duration_option_list.duration_minute' => ['field_label' => 'Duration Minute', 'field_description' => 'Non-negative service duration.', 'type_data' => 'I', 'min_number' => 0],
    'price_option_list.amount' => ['field_label' => 'Amount', 'field_description' => 'Non-negative monetary amount.', 'type_data' => 'D', 'min_number' => 0],
    'price_option_list.currency_id' => ['field_label' => 'Currency ID', 'field_description' => 'Common-reference currency identifier.', 'type_data' => 'I', 'is_mandatory' => true],
    'component_list.sort_order' => ['field_label' => 'Component Sort Order', 'field_description' => 'Stable component display order.', 'type_data' => 'I', 'min_number' => 0],
];
$establishment_service_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'establishment_slug', 'index_name' => 'uq_establishment_service_establishment_slug', 'type_index' => 'CMP', 'is_unique' => true, 'is_sparse' => true, 'index_field_list' => [['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'establishment_service_slug', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20],
    ['index_key' => 'menu_filter', 'index_name' => 'ix_establishment_service_establishment_status_group_type', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_establishment_service', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'menu_group_name', 'type_index_mode' => 'ASC', 'sort_order' => 30], ['field_name' => 'type_establishment_service', 'type_index_mode' => 'ASC', 'sort_order' => 40]], 'sort_order' => 30],
];
$establishment_service_boundary = ['owns' => ['provider menu identity, price and duration options, package components, and normalized mappings'], 'references' => ['establishment_main, service_main, practitioner, research source, and verification records'], 'does_not_own' => ['universal service identity, temporary promotions, bookings, or overall establishment price range']];
return compact('establishment_service_default', 'multilingual_text_sample', 'establishment_service', 'establishment_service_field_order', 'establishment_service_embedded_structure', 'establishment_service_field_property', 'establishment_service_subfield_property', 'establishment_service_index_list', 'establishment_service_boundary');
