<?php
/**
 * Title: Massage Nexus Establishment Operating Schedule Structure Guide
 * Version: 1.10
 * Collection: establishment_operating_schedule
 * Description: Stores one effective version of an establishment's bounded weekly operating intervals and dated exceptions.
 * Purpose: Separates historied operating hours from establishment_main and provides the source for open-now calculations without storing an authoritative is_open_now flag.
 */
$created_at = '2026-07-21T08:15:45Z';
$updated_at = '2026-07-21T09:49:12Z';
$establishment_operating_schedule_default = ['effective_until' => null, 'weekly_day_list' => [], 'exception_list' => [], 'status_schedule' => 'ACT', 'status_record_lifecycle' => 'ACT'];
$establishment_operating_schedule = [
    '_id' => 'Oh7K2pQ9xR4tV8zN', // Canonical 16-character schedule identifier.
    'establishment_id' => 'Es7K2pQ9xR4tV8zN', // Owning establishment_main identifier.
    'time_zone_id' => 255, // Numeric common_reference.time_zone_main identifier for Asia/Manila.
    'type_business_hours' => 'REG', // Regular, seasonal, 24/7, appointment-only, irregular, or temporary.
    'effective_from' => '2026-01-01', // Schedule version effective start.
    'effective_until' => null, // Schedule version effective end.
    'type_date_precision' => 'D', // Effective-date precision.
    'type_date_qualifier' => 'EXA', // Effective-date qualifier.
    'weekly_day_list' => [['day_of_week' => 'MON', 'is_closed' => false, 'is_appointment_only' => false, 'is_walk_in_available' => true, 'interval_list' => [['opens_at_local' => '10:00', 'closes_at_local' => '22:00', 'crosses_midnight' => false, 'type_access_period' => 'ALL', 'last_booking_at_local' => '21:00', 'last_walk_in_at_local' => '20:30']], 'schedule_day_note' => null]], // Bounded weekly operating pattern.
    'exception_list' => [['exception_date_start' => '2026-12-25', 'exception_date_end' => '2026-12-25', 'type_hour_exception' => 'CLO', 'is_closed' => true, 'interval_list' => [], 'schedule_exception_note' => 'Closed for the holiday.', 'first_observed_at' => '2026-12-01T00:00:00Z', 'last_confirmed_at' => '2026-12-20T00:00:00Z']], // Dated deviations from the weekly pattern.
    'status_schedule' => 'ACT', // Schedule workflow or activation state.
    'first_observed_at' => '2026-01-02T00:00:00Z', // First observation of this schedule version.
    'last_observed_at' => '2026-07-20T00:00:00Z', // Latest observation of the schedule.
    'first_confirmed_at' => '2026-01-03T00:00:00Z', // First adequate confirmation.
    'last_confirmed_at' => '2026-07-20T00:00:00Z', // Latest adequate confirmation.
    'source_id_list' => ['Sr7K2pQ9xR4tV8zN'], // Supporting sources.
    'status_record_lifecycle' => 'ACT', // Database lifecycle state.
    'created_at' => $created_at, // UTC record creation time.
    'updated_at' => $updated_at, // UTC record update time.
];
$establishment_operating_schedule_field_order = [
    '_id', 'establishment_id', 'time_zone_id', 'type_business_hours', 'effective_from', 'effective_until',
    'type_date_precision', 'type_date_qualifier', 'weekly_day_list', 'exception_list', 'status_schedule',
    'first_observed_at', 'last_observed_at', 'first_confirmed_at', 'last_confirmed_at', 'source_id_list',
    'status_record_lifecycle', 'created_at', 'updated_at',
];
$establishment_operating_schedule_embedded_structure = [
    'weekly_day_list' => ['day_of_week' => 'MON', 'is_closed' => false, 'is_appointment_only' => false, 'is_walk_in_available' => true, 'interval_list' => [], 'schedule_day_note' => null],
    'weekly_day_list.interval_list' => ['opens_at_local' => '10:00', 'closes_at_local' => '22:00', 'crosses_midnight' => false, 'type_access_period' => 'ALL', 'last_booking_at_local' => '21:00', 'last_walk_in_at_local' => '20:30'],
    'exception_list' => ['exception_date_start' => '2026-12-25', 'exception_date_end' => '2026-12-25', 'type_hour_exception' => 'CLO', 'is_closed' => true, 'interval_list' => [], 'schedule_exception_note' => 'Closed for the holiday.', 'first_observed_at' => '2026-12-01T00:00:00Z', 'last_confirmed_at' => '2026-12-20T00:00:00Z'],
];
$establishment_operating_schedule_field_property = [
    '_id' => ['field_label' => 'Operating Schedule ID', 'field_description' => 'Canonical identifier for one effective schedule version.', 'type_data' => 'S', 'type_field' => 'HDN', 'type_sql' => 'CHAR(16)', 'is_mandatory' => true, 'is_unique' => true, 'is_indexed' => true],
    'establishment_id' => ['field_label' => 'Establishment', 'field_description' => 'Establishment whose operating schedule is represented.', 'type_data' => 'S', 'type_field' => 'REF', 'type_sql' => 'CHAR(16)', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'time_zone_id' => ['field_label' => 'Time Zone', 'field_description' => 'Numeric common-reference time-zone identifier used to interpret every local time in the schedule.', 'type_data' => 'I', 'type_field' => 'REF', 'type_sql' => 'INT', 'is_mandatory' => true, 'is_relational' => true],
    'type_business_hours' => ['field_label' => 'Business Hours Type', 'field_description' => 'Controlled pattern describing whether the schedule is regular, seasonal, continuous, appointment-only, irregular, or temporary.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'is_mandatory' => true],
    'effective_from' => ['field_label' => 'Effective From', 'field_description' => 'Date on which this schedule version begins to apply.', 'type_data' => 'S', 'type_field' => 'DTI', 'type_sql' => 'DATE', 'is_mandatory' => true, 'is_indexed' => true],
    'effective_until' => ['field_label' => 'Effective Until', 'field_description' => 'Date after which this schedule version no longer applies.', 'type_data' => 'S', 'type_field' => 'DTI', 'type_sql' => 'DATE'],
    'type_date_precision' => ['field_label' => 'Date Precision', 'field_description' => 'Controlled precision of the schedule effective dates.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)'],
    'type_date_qualifier' => ['field_label' => 'Date Qualifier', 'field_description' => 'Controlled exactness or uncertainty qualifier for the effective dates.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)'],
    'weekly_day_list' => ['field_label' => 'Weekly Day List', 'field_description' => 'Seven-day operating pattern containing bounded local-time intervals and day-specific access rules.', 'type_data' => 'A', 'type_field' => 'JSE', 'type_sql' => 'JSON', 'default_value' => []],
    'exception_list' => ['field_label' => 'Schedule Exception List', 'field_description' => 'Dated closures or replacement intervals that override the weekly pattern.', 'type_data' => 'A', 'type_field' => 'JSE', 'type_sql' => 'JSON', 'default_value' => []],
    'status_schedule' => ['field_label' => 'Schedule Status', 'field_description' => 'Controlled activation or workflow state of this schedule version.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'default_value' => 'ACT', 'is_indexed' => true],
    'first_observed_at' => ['field_label' => 'First Observed At', 'field_description' => 'UTC time when Massage Nexus first observed this schedule version.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'last_observed_at' => ['field_label' => 'Last Observed At', 'field_description' => 'Latest UTC time when Massage Nexus observed this schedule version.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'first_confirmed_at' => ['field_label' => 'First Confirmed At', 'field_description' => 'UTC time when adequate evidence first confirmed this schedule version.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'Latest UTC time when adequate evidence confirmed this schedule version.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'source_id_list' => ['field_label' => 'Research Sources', 'field_description' => 'Research-source references supporting the schedule and exceptions.', 'type_data' => 'A', 'type_field' => 'TAG', 'type_sql' => 'JSON', 'is_relational' => true],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state independent from schedule activation.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'default_value' => 'ACT'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC time when the schedule record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC time when the schedule record was last changed.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
];
$establishment_operating_schedule_subfield_property = [
    'weekly_day_list.day_of_week' => ['field_label' => 'Day of Week', 'field_description' => 'Controlled weekday represented by this day item.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'weekly_day_list.is_closed' => ['field_label' => 'Day Is Closed', 'field_description' => 'Whether no operating interval applies for the day.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'weekly_day_list.is_appointment_only' => ['field_label' => 'Day Is Appointment Only', 'field_description' => 'Whether client service during the day requires an appointment.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'weekly_day_list.is_walk_in_available' => ['field_label' => 'Day Allows Walk-Ins', 'field_description' => 'Whether walk-in service is normally accepted during the day.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'weekly_day_list.interval_list' => ['field_label' => 'Day Interval List', 'field_description' => 'Bounded local-time intervals operating during the day.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'weekly_day_list.schedule_day_note' => ['field_label' => 'Schedule Day Note', 'field_description' => 'Day-specific public qualification that cannot be represented by the controlled fields.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'weekly_day_list.interval_list.opens_at_local' => ['field_label' => 'Opens At Local', 'field_description' => 'Local wall-clock time at which the interval begins.', 'type_data' => 'S', 'type_field' => 'TMI'],
    'weekly_day_list.interval_list.closes_at_local' => ['field_label' => 'Closes At Local', 'field_description' => 'Local wall-clock time at which the interval ends.', 'type_data' => 'S', 'type_field' => 'TMI'],
    'weekly_day_list.interval_list.crosses_midnight' => ['field_label' => 'Crosses Midnight', 'field_description' => 'Whether the closing time falls on the following local calendar day.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'weekly_day_list.interval_list.type_access_period' => ['field_label' => 'Access Period Type', 'field_description' => 'Controlled client-access purpose supported during the interval.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'weekly_day_list.interval_list.last_booking_at_local' => ['field_label' => 'Last Booking At Local', 'field_description' => 'Latest local booking start or cutoff time for the interval.', 'type_data' => 'S', 'type_field' => 'TMI'],
    'weekly_day_list.interval_list.last_walk_in_at_local' => ['field_label' => 'Last Walk-In At Local', 'field_description' => 'Latest local walk-in cutoff time for the interval.', 'type_data' => 'S', 'type_field' => 'TMI'],
    'exception_list.exception_date_start' => ['field_label' => 'Exception Start Date', 'field_description' => 'First local calendar date affected by the exception.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'exception_list.exception_date_end' => ['field_label' => 'Exception End Date', 'field_description' => 'Last local calendar date affected by the exception.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'exception_list.type_hour_exception' => ['field_label' => 'Hour Exception Type', 'field_description' => 'Controlled way in which the exception changes the weekly schedule.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'exception_list.is_closed' => ['field_label' => 'Exception Is Closed', 'field_description' => 'Whether the establishment is closed throughout the exception period.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'exception_list.interval_list' => ['field_label' => 'Exception Interval List', 'field_description' => 'Replacement operating intervals for a non-closed exception.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'exception_list.schedule_exception_note' => ['field_label' => 'Schedule Exception Note', 'field_description' => 'Exception-specific public qualification that cannot be represented by the controlled fields.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'exception_list.first_observed_at' => ['field_label' => 'Exception First Observed At', 'field_description' => 'UTC time when Massage Nexus first observed the exception.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'exception_list.last_confirmed_at' => ['field_label' => 'Exception Last Confirmed At', 'field_description' => 'Latest UTC time when adequate evidence confirmed the exception.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$establishment_operating_schedule_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'owner_effective_status', 'index_name' => 'ix_establishment_operating_schedule_owner_effective', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'effective_from', 'type_index_mode' => 'DESC', 'sort_order' => 20], ['field_name' => 'status_schedule', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
];
$establishment_operating_schedule_boundary = ['owns' => ['versioned weekly operating intervals, dated exceptions, time zone, effective period, and freshness'], 'references' => ['establishment_main', 'common_reference.time_zone_main', 'research_source'], 'does_not_own' => ['bookable availability', 'staff or resource schedules', 'authoritative is_open_now boolean', 'establishment operating status']];
return [
    'establishment_operating_schedule_default' => $establishment_operating_schedule_default,
    'establishment_operating_schedule' => $establishment_operating_schedule,
    'establishment_operating_schedule_field_order' => $establishment_operating_schedule_field_order,
    'establishment_operating_schedule_embedded_structure' => $establishment_operating_schedule_embedded_structure,
    'establishment_operating_schedule_field_property' => $establishment_operating_schedule_field_property,
    'establishment_operating_schedule_subfield_property' => $establishment_operating_schedule_subfield_property,
    'establishment_operating_schedule_index_list' => $establishment_operating_schedule_index_list,
    'establishment_operating_schedule_boundary' => $establishment_operating_schedule_boundary,
];
