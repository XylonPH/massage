<?php
/**
 * Title: Massage Nexus Establishment Service Structure Guide
 * Version: 1.20
 * Collection: establishment_service
 * Description: Stores provider menu offerings, packages, combinations, add-ons, and facility-access products.
 * Purpose: Preserves provider-specific commercial presentation while mapping it to normalized service_main records.
 */
$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T10:38:00Z';
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
    'normalized_service_mapping_list' => [['service_id' => 'Sv8K2pQ9xR4tV7zN', 'type_service_mapping' => 'PRI', 'level_confidence' => 'H', 'status_review' => 'APR', 'mapped_by_user_id' => 'U2pR7vX4kT9mC5qL', 'mapped_at' => '2026-07-21T08:23:43Z', 'service_mapping_note' => null]], // Normalized service mappings.
    'duration_option_list' => [['duration_minute' => 90, 'preparation_minute' => 10, 'cleanup_minute' => 10, 'status_availability' => 'AVL', 'duration_option_note' => null]], // Bookable duration options.
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
$establishment_service_field_order = [
    '_id', 'establishment_id', 'establishment_service_slug', 'display_name', 'original_menu_name',
    'short_description', 'full_description', 'menu_group_name', 'type_establishment_service',
    'status_establishment_service', 'normalized_service_mapping_list', 'duration_option_list',
    'price_option_list', 'component_list', 'mode_service_delivery', 'type_booking_method',
    'client_restriction_list', 'included_facility_list', 'included_product_list', 'is_featured',
    'display_order', 'first_observed_at', 'last_observed_active_at', 'first_observed_inactive_at',
    'first_confirmed_at', 'last_confirmed_at', 'research_source_id_list',
    'record_verification_id_list', 'status_record_lifecycle', 'created_at', 'updated_at',
];
$establishment_service_embedded_structure = [
    'normalized_service_mapping_list' => ['service_id' => 'Sv8K2pQ9xR4tV7zN', 'type_service_mapping' => 'PRI', 'level_confidence' => 'H', 'status_review' => 'APR', 'mapped_by_user_id' => 'U2pR7vX4kT9mC5qL', 'mapped_at' => '2026-07-21T08:23:43Z', 'service_mapping_note' => null],
    'duration_option_list' => ['duration_minute' => 90, 'preparation_minute' => 10, 'cleanup_minute' => 10, 'status_availability' => 'AVL', 'duration_option_note' => null],
    'price_option_list' => ['amount' => 1800.00, 'currency_id' => 111, 'type_price' => 'REG', 'duration_minute' => 90, 'client_price_context' => null, 'mode_service_delivery' => 'OS', 'practitioner_id' => null, 'is_tax_included' => true, 'mandatory_fee_list' => [], 'effective_from' => '2026-07-01', 'effective_until' => null, 'status_price' => 'ACT', 'first_observed_at' => '2026-07-01T00:00:00Z', 'last_confirmed_at' => '2026-07-20T00:00:00Z'],
    'component_list' => ['establishment_service_id' => null, 'service_id' => 'Sv8K2pQ9xR4tV7zN', 'component_name_snapshot' => 'Swedish massage', 'quantity' => 1, 'duration_minute' => 60, 'is_selectable' => false, 'is_optional' => false, 'sort_order' => 10],
];
$establishment_service_field_property = [
    '_id' => ['field_label' => 'Establishment Service ID', 'field_description' => 'Canonical identifier for this provider-specific offering.', 'type_data' => 'S', 'type_field' => 'HDN', 'type_sql' => 'CHAR(16)', 'is_mandatory' => true, 'is_unique' => true, 'is_indexed' => true],
    'establishment_id' => ['field_label' => 'Establishment', 'field_description' => 'Establishment that owns and presents the offering.', 'type_data' => 'S', 'type_field' => 'REF', 'type_sql' => 'CHAR(16)', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'establishment_service_slug' => ['field_label' => 'Establishment Service Slug', 'field_description' => 'Route-safe slug unique within the owning establishment.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(200)', 'is_unique' => true, 'is_indexed' => true],
    'display_name' => ['field_label' => 'Display Name', 'field_description' => 'Approved multilingual public name for the provider-specific offering.', 'type_data' => 'O', 'type_field' => 'JSE', 'type_sql' => 'JSON', 'is_mandatory' => true, 'is_translatable' => true],
    'original_menu_name' => ['field_label' => 'Original Menu Name', 'field_description' => 'Exact source wording retained for provenance and mapping review.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(300)'],
    'short_description' => ['field_label' => 'Short Description', 'field_description' => 'Multilingual concise public summary of the offering.', 'type_data' => 'O', 'type_field' => 'JSE', 'type_sql' => 'JSON', 'is_translatable' => true],
    'full_description' => ['field_label' => 'Full Description', 'field_description' => 'Multilingual detailed public description of included service.', 'type_data' => 'O', 'type_field' => 'JSE', 'type_sql' => 'JSON', 'is_translatable' => true],
    'menu_group_name' => ['field_label' => 'Menu Group Name', 'field_description' => 'Provider-authored menu section in which the offering is presented.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(160)', 'is_indexed' => true],
    'type_establishment_service' => ['field_label' => 'Establishment Service Type', 'field_description' => 'Controlled commercial presentation type, such as single service, package, combination, add-on, or facility access.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'is_indexed' => true],
    'status_establishment_service' => ['field_label' => 'Establishment Service Status', 'field_description' => 'Controlled current availability state of the provider offering.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'default_value' => 'ACT', 'is_indexed' => true],
    'normalized_service_mapping_list' => ['field_label' => 'Normalized Service Mapping List', 'field_description' => 'Reviewed mappings from the provider offering to canonical service records.', 'type_data' => 'A', 'type_field' => 'JSE', 'type_sql' => 'JSON', 'default_value' => []],
    'duration_option_list' => ['field_label' => 'Duration Option List', 'field_description' => 'Bookable duration variants and their preparation or cleanup allowances.', 'type_data' => 'A', 'type_field' => 'JSE', 'type_sql' => 'JSON', 'default_value' => []],
    'price_option_list' => ['field_label' => 'Price Option List', 'field_description' => 'Effective-dated price variants tied to currency, duration, delivery context, and applicable practitioner.', 'type_data' => 'A', 'type_field' => 'JSE', 'type_sql' => 'JSON', 'default_value' => []],
    'component_list' => ['field_label' => 'Component List', 'field_description' => 'Ordered component offerings or normalized services included in a package or combination.', 'type_data' => 'A', 'type_field' => 'JSE', 'type_sql' => 'JSON', 'default_value' => []],
    'mode_service_delivery' => ['field_label' => 'Service Delivery Modes', 'field_description' => 'Controlled delivery modes in which the offering is available.', 'type_data' => 'A', 'type_field' => 'TAG', 'type_sql' => 'JSON'],
    'type_booking_method' => ['field_label' => 'Booking Methods', 'field_description' => 'Controlled methods through which a client may request or complete a booking.', 'type_data' => 'A', 'type_field' => 'TAG', 'type_sql' => 'JSON', 'default_value' => []],
    'client_restriction_list' => ['field_label' => 'Client Restriction List', 'field_description' => 'Explicit structured restrictions applying to client eligibility for the offering.', 'type_data' => 'A', 'type_field' => 'JSE', 'type_sql' => 'JSON', 'default_value' => []],
    'included_facility_list' => ['field_label' => 'Included Facility List', 'field_description' => 'Controlled facility features included with the offering.', 'type_data' => 'A', 'type_field' => 'TAG', 'type_sql' => 'JSON', 'default_value' => []],
    'included_product_list' => ['field_label' => 'Included Product List', 'field_description' => 'Product references or bounded source snapshots included with the offering.', 'type_data' => 'A', 'type_field' => 'JSE', 'type_sql' => 'JSON', 'default_value' => []],
    'is_featured' => ['field_label' => 'Featured Offering', 'field_description' => 'Whether the provider has marked the offering for featured presentation.', 'type_data' => 'B', 'type_field' => 'CHK', 'type_sql' => 'BOOLEAN', 'default_value' => false],
    'display_order' => ['field_label' => 'Display Order', 'field_description' => 'Provider-controlled stable ordering value within its menu context.', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'min_number' => 0],
    'first_observed_at' => ['field_label' => 'First Observed At', 'field_description' => 'UTC time when Massage Nexus first observed the offering.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'last_observed_active_at' => ['field_label' => 'Last Observed Active At', 'field_description' => 'Latest UTC time when the offering was observed as active.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'first_observed_inactive_at' => ['field_label' => 'First Observed Inactive At', 'field_description' => 'First UTC time when the offering was observed as inactive.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'first_confirmed_at' => ['field_label' => 'First Confirmed At', 'field_description' => 'UTC time when adequate evidence first confirmed the offering.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'Latest UTC time when adequate evidence confirmed the offering.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'research_source_id_list' => ['field_label' => 'Research Sources', 'field_description' => 'Research-source references supporting the provider offering facts.', 'type_data' => 'A', 'type_field' => 'TAG', 'type_sql' => 'JSON', 'is_relational' => true],
    'record_verification_id_list' => ['field_label' => 'Verification Records', 'field_description' => 'Record-verification references supporting or challenging offering facts.', 'type_data' => 'A', 'type_field' => 'TAG', 'type_sql' => 'JSON', 'is_relational' => true],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state independent from offering availability.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'default_value' => 'ACT'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC time when the offering record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC time when the offering record was last changed.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
];
$establishment_service_subfield_property = [
    'display_name.*.text' => ['field_label' => 'Display Name Text', 'field_description' => 'Localized display-name text keyed by three-letter language code.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'short_description.*.text' => ['field_label' => 'Short Description Text', 'field_description' => 'Localized short-description text keyed by three-letter language code.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'full_description.*.text' => ['field_label' => 'Full Description Text', 'field_description' => 'Localized full-description text keyed by three-letter language code.', 'type_data' => 'S', 'type_field' => 'RTE'],
    'normalized_service_mapping_list.service_id' => ['field_label' => 'Mapped Service', 'field_description' => 'Canonical service to which the provider offering is mapped.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true],
    'normalized_service_mapping_list.type_service_mapping' => ['field_label' => 'Service Mapping Type', 'field_description' => 'Controlled role of the canonical service in the offering mapping.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'normalized_service_mapping_list.level_confidence' => ['field_label' => 'Mapping Confidence', 'field_description' => 'Reviewed confidence that the provider wording maps to the canonical service.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'normalized_service_mapping_list.status_review' => ['field_label' => 'Mapping Review Status', 'field_description' => 'Review state of the individual service mapping.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'normalized_service_mapping_list.mapped_by_user_id' => ['field_label' => 'Mapped By User', 'field_description' => 'User responsible for the mapping decision.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'normalized_service_mapping_list.mapped_at' => ['field_label' => 'Mapped At', 'field_description' => 'UTC time when the mapping decision was recorded.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'normalized_service_mapping_list.service_mapping_note' => ['field_label' => 'Service Mapping Note', 'field_description' => 'Mapping-specific explanation that cannot be represented by controlled mapping fields.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'duration_option_list.duration_minute' => ['field_label' => 'Service Duration Minutes', 'field_description' => 'Non-negative client service duration in minutes.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'duration_option_list.preparation_minute' => ['field_label' => 'Preparation Minutes', 'field_description' => 'Non-negative preparation time reserved before client service.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'duration_option_list.cleanup_minute' => ['field_label' => 'Cleanup Minutes', 'field_description' => 'Non-negative cleanup or turnover time reserved after client service.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'duration_option_list.status_availability' => ['field_label' => 'Duration Availability Status', 'field_description' => 'Controlled availability state of the duration option.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'duration_option_list.duration_option_note' => ['field_label' => 'Duration Option Note', 'field_description' => 'Duration-specific qualification that cannot be represented by the structured timing fields.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'price_option_list.amount' => ['field_label' => 'Price Amount', 'field_description' => 'Non-negative monetary amount in the referenced currency.', 'type_data' => 'F', 'type_field' => 'NMB', 'min_number' => 0],
    'price_option_list.currency_id' => ['field_label' => 'Currency', 'field_description' => 'Numeric common-reference currency identifier.', 'type_data' => 'I', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true],
    'price_option_list.type_price' => ['field_label' => 'Price Type', 'field_description' => 'Controlled commercial meaning of the amount, such as regular or starting price.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'price_option_list.duration_minute' => ['field_label' => 'Price Duration Minutes', 'field_description' => 'Duration variant to which the amount applies.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'price_option_list.client_price_context' => ['field_label' => 'Client Price Context', 'field_description' => 'Bounded client category or condition to which the amount applies.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'price_option_list.mode_service_delivery' => ['field_label' => 'Price Delivery Mode', 'field_description' => 'Controlled delivery mode to which the amount applies.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'price_option_list.practitioner_id' => ['field_label' => 'Price Practitioner', 'field_description' => 'Optional practitioner for whom this specific amount applies.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'price_option_list.is_tax_included' => ['field_label' => 'Tax Included', 'field_description' => 'Whether mandatory tax is already included in the displayed amount.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'price_option_list.mandatory_fee_list' => ['field_label' => 'Mandatory Fee List', 'field_description' => 'Structured mandatory fees not already included in the displayed amount.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'price_option_list.effective_from' => ['field_label' => 'Price Effective From', 'field_description' => 'Date on which the price option begins to apply.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'price_option_list.effective_until' => ['field_label' => 'Price Effective Until', 'field_description' => 'Date after which the price option no longer applies.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'price_option_list.status_price' => ['field_label' => 'Price Status', 'field_description' => 'Controlled lifecycle or availability state of the price option.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'price_option_list.first_observed_at' => ['field_label' => 'Price First Observed At', 'field_description' => 'UTC time when Massage Nexus first observed the price option.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'price_option_list.last_confirmed_at' => ['field_label' => 'Price Last Confirmed At', 'field_description' => 'Latest UTC time when adequate evidence confirmed the price option.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'component_list.establishment_service_id' => ['field_label' => 'Component Establishment Service', 'field_description' => 'Optional provider-offering component reference.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'component_list.service_id' => ['field_label' => 'Component Service', 'field_description' => 'Optional canonical service component reference.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'component_list.component_name_snapshot' => ['field_label' => 'Component Name Snapshot', 'field_description' => 'Source-time component wording retained when a canonical reference is unavailable or insufficient.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'component_list.quantity' => ['field_label' => 'Component Quantity', 'field_description' => 'Non-negative quantity of this component in the offering.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'component_list.duration_minute' => ['field_label' => 'Component Duration Minutes', 'field_description' => 'Service time allocated to this component in minutes.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'component_list.is_selectable' => ['field_label' => 'Component Is Selectable', 'field_description' => 'Whether the client may choose this component from supported alternatives.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'component_list.is_optional' => ['field_label' => 'Component Is Optional', 'field_description' => 'Whether the component may be omitted from the offering.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'component_list.sort_order' => ['field_label' => 'Component Sort Order', 'field_description' => 'Stable display order of the component within the offering.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
];
$establishment_service_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'establishment_slug', 'index_name' => 'uq_establishment_service_establishment_slug', 'type_index' => 'CMP', 'is_unique' => true, 'is_sparse' => true, 'index_field_list' => [['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'establishment_service_slug', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20],
    ['index_key' => 'menu_filter', 'index_name' => 'ix_establishment_service_establishment_status_group_type', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_establishment_service', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'menu_group_name', 'type_index_mode' => 'ASC', 'sort_order' => 30], ['field_name' => 'type_establishment_service', 'type_index_mode' => 'ASC', 'sort_order' => 40]], 'sort_order' => 30],
];
$establishment_service_boundary = [
    'owns' => ['provider menu identity, price and duration options, package components, and normalized mappings'],
    'reference_field_list' => ['establishment_id', 'research_source_id_list', 'record_verification_id_list'],
    'does_not_own' => ['universal service identity, temporary promotions, bookings, or overall establishment price range'],
];
return [
    'establishment_service_default' => $establishment_service_default,
    'multilingual_text_sample' => $multilingual_text_sample,
    'establishment_service' => $establishment_service,
    'establishment_service_field_order' => $establishment_service_field_order,
    'establishment_service_embedded_structure' => $establishment_service_embedded_structure,
    'establishment_service_field_property' => $establishment_service_field_property,
    'establishment_service_subfield_property' => $establishment_service_subfield_property,
    'establishment_service_index_list' => $establishment_service_index_list,
    'establishment_service_boundary' => $establishment_service_boundary,
];
