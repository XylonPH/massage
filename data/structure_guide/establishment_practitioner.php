<?php
/**
 * Title: Massage Nexus Establishment–Practitioner Affiliation Structure Guide
 * Version: 1.10
 * Collection: establishment_practitioner
 * Description: Stores effective-dated practitioner affiliation facts specific to one establishment.
 * Purpose: Separates roster, employment, booking, price, confirmation, and dispute context from practitioner identity.
 */
$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T09:49:12Z';
$establishment_practitioner_default = ['is_public_roster' => false, 'is_public_booking' => false, 'eligible_establishment_service_id_list' => [], 'status_affiliation' => 'UNK', 'status_record_lifecycle' => 'ACT'];
$establishment_practitioner = [
    '_id' => 'EprK2pQ9xR4tV7zN', // Canonical affiliation identifier.
    'establishment_id' => 'Es7K2pQ9xR4tV8zN', // Establishment endpoint.
    'practitioner_id' => 'P8rC3mL7xT1qV5nK', // Practitioner endpoint.
    'status_affiliation' => 'ACT', // Current affiliation state.
    'type_work_arrangement' => 'EMP', // Employment or contract arrangement.
    'public_title' => 'Senior Massage Practitioner', // Public establishment-specific title.
    'position_affiliation' => 'SEN', // Provider rank/position code.
    'department' => 'Massage', // Establishment area.
    'is_public_roster' => true, // May appear on public roster.
    'is_public_booking' => true, // May be selected for public booking.
    'status_user_confirmation' => 'CFM', // Practitioner confirmation state using the shared user-confirmation taxonomy.
    'status_dispute' => 'NON', // Dispute state.
    'eligible_establishment_service_id_list' => ['EsrK2pQ9xR4tV7zN'], // Eligible menu offerings.
    'price_surcharge_list' => [['establishment_service_id' => 'EsrK2pQ9xR4tV7zN', 'amount' => 200.00, 'currency_id' => 111, 'effective_from' => '2026-07-01', 'effective_until' => null]], // Practitioner-specific adjustments.
    'availability_reference_id' => null, // Future schedule/availability reference.
    'started_at' => null, // Supported actual start, not first sighting.
    'started_at_precision' => 'U', // Start-date precision.
    'started_at_qualifier' => null, // Qualifier is omitted because no start date is known.
    'ended_at' => null, // Supported actual end.
    'ended_at_precision' => 'U', // End-date precision.
    'ended_at_qualifier' => null, // Qualifier is omitted because no end date is known.
    'first_observed_active_at' => '2026-07-11T00:00:00Z', // First positive observation.
    'last_observed_active_at' => '2026-07-20T00:00:00Z', // Latest positive observation.
    'first_observed_inactive_at' => null, // First inactive observation.
    'last_checked_at' => '2026-07-20T00:00:00Z', // Latest check attempt.
    'first_confirmed_at' => null, // First adequate confirmation.
    'last_confirmed_at' => null, // Latest adequate confirmation.
    'record_verification_id_list' => [], // Verification records.
    'research_source_id_list' => ['Sr8K2pQ9xR4tV7zN'], // Supporting sources.
    'public_note' => null, // Public note.
    'internal_note' => 'Start date remains unknown.', // Restricted note.
    'status_record_lifecycle' => 'ACT', // Database lifecycle.
    'created_at' => $created_at, // UTC creation time.
    'updated_at' => $updated_at, // UTC update time.
];
$establishment_practitioner_field_order = ['_id', 'establishment_id', 'practitioner_id', 'status_affiliation', 'type_work_arrangement', 'public_title', 'position_affiliation', 'department', 'is_public_roster', 'is_public_booking', 'status_user_confirmation', 'status_dispute', 'eligible_establishment_service_id_list', 'price_surcharge_list', 'availability_reference_id', 'started_at', 'started_at_precision', 'started_at_qualifier', 'ended_at', 'ended_at_precision', 'ended_at_qualifier', 'first_observed_active_at', 'last_observed_active_at', 'first_observed_inactive_at', 'last_checked_at', 'first_confirmed_at', 'last_confirmed_at', 'record_verification_id_list', 'research_source_id_list', 'public_note', 'internal_note', 'status_record_lifecycle', 'created_at', 'updated_at'];
$establishment_practitioner_embedded_structure = ['price_surcharge_list' => ['establishment_service_id' => 'EsrK2pQ9xR4tV7zN', 'amount' => 200.00, 'currency_id' => 111, 'effective_from' => '2026-07-01', 'effective_until' => null, 'status_price' => 'ACT']];
$establishment_practitioner_field_property = [
    '_id' => ['field_label' => 'Affiliation ID', 'field_description' => 'Canonical identifier for the establishment-practitioner affiliation.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true],
    'establishment_id' => ['field_label' => 'Establishment', 'field_description' => 'Establishment participating in the affiliation.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true],
    'practitioner_id' => ['field_label' => 'Practitioner', 'field_description' => 'Practitioner participating in the affiliation.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true],
    'status_affiliation' => ['field_label' => 'Affiliation Status', 'field_description' => 'Controlled current state of the affiliation.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'type_work_arrangement' => ['field_label' => 'Work Arrangement', 'field_description' => 'Controlled work arrangement in this establishment context.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'public_title' => ['field_label' => 'Public Title', 'field_description' => 'Approved establishment-specific practitioner title.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'position_affiliation' => ['field_label' => 'Affiliation Position', 'field_description' => 'Controlled practitioner position within the establishment.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'department' => ['field_label' => 'Department', 'field_description' => 'Department or unit associated with the affiliation.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'is_public_roster' => ['field_label' => 'Public Roster', 'field_description' => 'Whether the practitioner may appear on the public establishment roster.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'is_public_booking' => ['field_label' => 'Public Booking', 'field_description' => 'Whether public booking may present this affiliation.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'status_user_confirmation' => ['field_label' => 'User Confirmation Status', 'field_description' => 'Current user-confirmation state for the affiliation.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'status_dispute' => ['field_label' => 'Dispute Status', 'field_description' => 'Current dispute state for the affiliation facts.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'eligible_establishment_service_id_list' => ['field_label' => 'Eligible Offerings', 'field_description' => 'Provider offerings the practitioner may deliver in this context.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'price_surcharge_list' => ['field_label' => 'Price Surcharge List', 'field_description' => 'Effective practitioner-specific price adjustments.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'availability_reference_id' => ['field_label' => 'Availability Reference', 'field_description' => 'Reference to separate context-specific availability.', 'type_data' => 'S', 'type_field' => 'REF'],
    'started_at' => ['field_label' => 'Start Date', 'field_description' => 'Best-supported affiliation start date.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'started_at_precision' => ['field_label' => 'Start Date Precision', 'field_description' => 'Controlled precision of the start date.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'started_at_qualifier' => ['field_label' => 'Start Date Qualifier', 'field_description' => 'Controlled exactness qualifier for the start date.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'ended_at' => ['field_label' => 'End Date', 'field_description' => 'Best-supported affiliation end date.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'ended_at_precision' => ['field_label' => 'End Date Precision', 'field_description' => 'Controlled precision of the end date.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'ended_at_qualifier' => ['field_label' => 'End Date Qualifier', 'field_description' => 'Controlled exactness qualifier for the end date.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'first_observed_active_at' => ['field_label' => 'First Observed Active At', 'field_description' => 'UTC time first observed active.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_observed_active_at' => ['field_label' => 'Last Observed Active At', 'field_description' => 'Latest UTC time observed active.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'first_observed_inactive_at' => ['field_label' => 'First Observed Inactive At', 'field_description' => 'First UTC time observed inactive.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_checked_at' => ['field_label' => 'Last Checked At', 'field_description' => 'Latest UTC time the affiliation was checked.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'first_confirmed_at' => ['field_label' => 'First Confirmed At', 'field_description' => 'UTC time adequate evidence first confirmed the affiliation.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'Latest UTC time adequate evidence confirmed the affiliation.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'record_verification_id_list' => ['field_label' => 'Verification Records', 'field_description' => 'Verification references for the affiliation.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'research_source_id_list' => ['field_label' => 'Research Sources', 'field_description' => 'Provenance references for the affiliation.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'public_note' => ['field_label' => 'Public Affiliation Note', 'field_description' => 'Approved public clarification of the affiliation.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'internal_note' => ['field_label' => 'Internal Affiliation Note', 'field_description' => 'Restricted operational clarification.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle independent from affiliation status.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC creation time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$establishment_practitioner_subfield_property = [
    'price_surcharge_list.establishment_service_id' => ['field_label' => 'Establishment Service ID', 'field_description' => 'Offering to which the adjustment applies.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true],
    'price_surcharge_list.amount' => ['field_label' => 'Amount', 'field_description' => 'Non-negative monetary adjustment.', 'type_data' => 'F', 'type_field' => 'NMB', 'min_number' => 0],
    'price_surcharge_list.currency_id' => ['field_label' => 'Currency ID', 'field_description' => 'Common-reference currency identifier.', 'type_data' => 'I', 'type_field' => 'REF'],
];
$establishment_practitioner_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'pair', 'index_name' => 'ix_establishment_practitioner_pair_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'practitioner_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_affiliation', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
    ['index_key' => 'public_roster', 'index_name' => 'ix_establishment_practitioner_public_booking', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'is_public_roster', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'is_public_booking', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 30],
];
$establishment_practitioner_boundary = ['owns' => ['establishment-specific affiliation, roster, booking, confirmation, dispute, and price context'], 'references' => ['establishment, practitioner, menu offering, research source, and verification records'], 'does_not_own' => ['practitioner identity, credentials, workspace access, or authoritative booking availability']];
return ['establishment_practitioner_default' => $establishment_practitioner_default, 'establishment_practitioner' => $establishment_practitioner, 'establishment_practitioner_field_order' => $establishment_practitioner_field_order, 'establishment_practitioner_embedded_structure' => $establishment_practitioner_embedded_structure, 'establishment_practitioner_field_property' => $establishment_practitioner_field_property, 'establishment_practitioner_subfield_property' => $establishment_practitioner_subfield_property, 'establishment_practitioner_index_list' => $establishment_practitioner_index_list, 'establishment_practitioner_boundary' => $establishment_practitioner_boundary];
