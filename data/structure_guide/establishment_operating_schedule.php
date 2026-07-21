<?php
/**
 * Title: Massage Nexus Establishment Operating Schedule Structure Guide
 * Version: 1.00
 * Collection: establishment_operating_schedule
 * Description: Stores one effective version of an establishment's bounded weekly operating intervals and dated exceptions.
 * Purpose: Separates historied operating hours from establishment_main and provides the source for open-now calculations without storing an authoritative is_open_now flag.
 */
$created_at = '2026-07-21T08:15:45Z';
$updated_at = '2026-07-21T08:15:45Z';
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
    'weekly_day_list' => [['day_of_week' => 'MON', 'is_closed' => false, 'is_appointment_only' => false, 'is_walk_in_available' => true, 'interval_list' => [['opens_at_local' => '10:00', 'closes_at_local' => '22:00', 'crosses_midnight' => false, 'type_access_period' => 'ALL', 'last_booking_at_local' => '21:00', 'last_walk_in_at_local' => '20:30']], 'note' => null]], // Bounded weekly operating pattern.
    'exception_list' => [['exception_date_start' => '2026-12-25', 'exception_date_end' => '2026-12-25', 'type_hour_exception' => 'CLO', 'is_closed' => true, 'interval_list' => [], 'note' => 'Closed for the holiday.', 'first_observed_at' => '2026-12-01T00:00:00Z', 'last_confirmed_at' => '2026-12-20T00:00:00Z']], // Dated deviations from the weekly pattern.
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
$establishment_operating_schedule_field_order = array_keys($establishment_operating_schedule);
$establishment_operating_schedule_embedded_structure = ['weekly_day_list' => $establishment_operating_schedule['weekly_day_list'][0], 'weekly_day_list.interval_list' => $establishment_operating_schedule['weekly_day_list'][0]['interval_list'][0], 'exception_list' => $establishment_operating_schedule['exception_list'][0]];
$establishment_operating_schedule_field_property = [];
foreach ($establishment_operating_schedule as $field_name => $sample_value) { $establishment_operating_schedule_field_property[$field_name] = ['field_label' => ucwords(str_replace('_', ' ', $field_name)), 'field_description' => 'Operating schedule property: ' . $field_name . '.', 'type_data' => is_array($sample_value) ? 'A' : (is_bool($sample_value) ? 'B' : (is_int($sample_value) ? 'I' : 'S'))]; }
$establishment_operating_schedule_subfield_property = [
    'weekly_day_list.day_of_week' => ['field_label' => 'Day of Week', 'field_description' => 'Controlled weekday.', 'type_data' => 'S'], 'weekly_day_list.is_closed' => ['field_label' => 'Is Closed', 'field_description' => 'Whether no interval operates that day.', 'type_data' => 'B'], 'weekly_day_list.is_appointment_only' => ['field_label' => 'Appointment Only', 'field_description' => 'Whether service requires appointment.', 'type_data' => 'B'], 'weekly_day_list.is_walk_in_available' => ['field_label' => 'Walk-In Available', 'field_description' => 'Whether walk-ins are normally accepted.', 'type_data' => 'B'], 'weekly_day_list.interval_list' => ['field_label' => 'Interval List', 'field_description' => 'Bounded local-time intervals.', 'type_data' => 'A'], 'weekly_day_list.note' => ['field_label' => 'Day Note', 'field_description' => 'Qualified schedule note.', 'type_data' => 'S'],
    'weekly_day_list.interval_list.opens_at_local' => ['field_label' => 'Opens At Local', 'field_description' => 'Local opening time.', 'type_data' => 'S'], 'weekly_day_list.interval_list.closes_at_local' => ['field_label' => 'Closes At Local', 'field_description' => 'Local closing time.', 'type_data' => 'S'], 'weekly_day_list.interval_list.crosses_midnight' => ['field_label' => 'Crosses Midnight', 'field_description' => 'Whether interval ends the next day.', 'type_data' => 'B'], 'weekly_day_list.interval_list.type_access_period' => ['field_label' => 'Access Period Type', 'field_description' => 'Purpose or availability of the interval.', 'type_data' => 'S'], 'weekly_day_list.interval_list.last_booking_at_local' => ['field_label' => 'Last Booking At Local', 'field_description' => 'Latest booking start or cutoff.', 'type_data' => 'S'], 'weekly_day_list.interval_list.last_walk_in_at_local' => ['field_label' => 'Last Walk-In At Local', 'field_description' => 'Latest walk-in cutoff.', 'type_data' => 'S'],
    'exception_list.exception_date_start' => ['field_label' => 'Exception Start Date', 'field_description' => 'First exception date.', 'type_data' => 'S'], 'exception_list.exception_date_end' => ['field_label' => 'Exception End Date', 'field_description' => 'Last exception date.', 'type_data' => 'S'], 'exception_list.type_hour_exception' => ['field_label' => 'Hour Exception Type', 'field_description' => 'Reason or behavior of the exception.', 'type_data' => 'S'], 'exception_list.is_closed' => ['field_label' => 'Exception Is Closed', 'field_description' => 'Whether the establishment is closed.', 'type_data' => 'B'], 'exception_list.interval_list' => ['field_label' => 'Exception Interval List', 'field_description' => 'Intervals replacing the weekly pattern.', 'type_data' => 'A'], 'exception_list.note' => ['field_label' => 'Exception Note', 'field_description' => 'Qualified exception wording.', 'type_data' => 'S'], 'exception_list.first_observed_at' => ['field_label' => 'Exception First Observed At', 'field_description' => 'First observation time.', 'type_data' => 'S'], 'exception_list.last_confirmed_at' => ['field_label' => 'Exception Last Confirmed At', 'field_description' => 'Latest confirmation time.', 'type_data' => 'S'],
];
$establishment_operating_schedule_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'owner_effective_status', 'index_name' => 'ix_establishment_operating_schedule_owner_effective', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'effective_from', 'type_index_mode' => 'DESC', 'sort_order' => 20], ['field_name' => 'status_schedule', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
];
$establishment_operating_schedule_boundary = ['owns' => ['versioned weekly operating intervals, dated exceptions, time zone, effective period, and freshness'], 'references' => ['establishment_main', 'common_reference.time_zone_main', 'research_source'], 'does_not_own' => ['bookable availability', 'staff or resource schedules', 'authoritative is_open_now boolean', 'establishment operating status']];
return compact('establishment_operating_schedule_default', 'establishment_operating_schedule', 'establishment_operating_schedule_field_order', 'establishment_operating_schedule_embedded_structure', 'establishment_operating_schedule_field_property', 'establishment_operating_schedule_subfield_property', 'establishment_operating_schedule_index_list', 'establishment_operating_schedule_boundary');
