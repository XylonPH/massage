<?php
/**
 * Title: Massage Nexus Establishment Contact Structure Guide
 * Version: 1.30
 * Collection: establishment_contact
 * Description: Stores one current or historical official contact channel for an establishment.
 * Purpose: Separates independently verified and historied business contact channels from establishment_main and from research-source URLs.
 */
$created_at = '2026-07-21T08:15:45Z';
$updated_at = '2026-07-21T10:48:10Z';
$establishment_contact_default = ['is_primary' => false, 'visibility_scope' => 'PUB', 'status_contact_channel' => 'UNK', 'effective_from' => null, 'effective_until' => null, 'status_record_lifecycle' => 'ACT', 'revision_number' => 1];
$establishment_contact = [
    '_id' => 'Ec7K2pQ9xR4tV8zN', // Canonical 16-character contact identifier.
    'establishment_id' => 'Es7K2pQ9xR4tV8zN', // Owning establishment_main identifier.
    'type_contact_channel' => 'PHN', // Channel medium such as phone, email, website, or social media.
    'type_contact_number' => 'L', // Telephone technology subtype when applicable.
    'contact_label' => 'Reservations', // Public purpose label.
    'contact_value_display' => '+63 2 8123 4567', // Human-readable display value.
    'contact_value_normalized' => '+63281234567', // Normalized comparison and action value.
    'contact_url' => 'tel:+63281234567', // Validated safe action URL.
    'is_primary' => true, // Primary channel for its purpose or type.
    'visibility_scope' => 'PUB', // Public or restricted audience rule.
    'status_contact_channel' => 'ACT', // Observed channel activity state.
    'effective_from' => '2026-01-01', // Date the channel became effective when known.
    'effective_until' => null, // Date the channel ended; historical records remain retained.
    'type_date_precision' => 'D', // Precision of effective dates.
    'type_date_qualifier' => 'EXA', // Exactness qualifier for effective dates.
    'first_observed_at' => '2026-01-05T06:00:00Z', // First Massage Nexus observation.
    'last_observed_active_at' => '2026-07-20T06:00:00Z', // Latest positive active observation.
    'first_observed_inactive_at' => null, // First inactive observation when applicable.
    'last_checked_at' => '2026-07-21T07:00:00Z', // Latest check attempt regardless of result.
    'first_confirmed_at' => '2026-01-06T07:00:00Z', // First adequate confirmation.
    'last_confirmed_at' => '2026-07-21T07:00:00Z', // Latest adequate confirmation.
    'record_verification_id' => 'Vr7K2pQ9xR4tV8zN', // Latest relevant verification result.
    'source_id_list' => ['Sr7K2pQ9xR4tV8zN'], // Supporting sources.
    'internal_note' => null, // Restricted operational note.
    'status_record_lifecycle' => 'ACT', // Database lifecycle state.
    'revision_number' => 1, // Monotonic optimistic-concurrency token; the required concurrency token distinct from updated_at (docs/02-governance/edit-system.txt section 16).
    'created_at' => $created_at, // UTC record creation time.
    'updated_at' => $updated_at, // UTC record update time.
];
$establishment_contact_field_order = [
    '_id', 'establishment_id', 'type_contact_channel', 'type_contact_number', 'contact_label',
    'contact_value_display', 'contact_value_normalized', 'contact_url', 'is_primary', 'visibility_scope',
    'status_contact_channel', 'effective_from', 'effective_until', 'type_date_precision',
    'type_date_qualifier', 'first_observed_at', 'last_observed_active_at',
    'first_observed_inactive_at', 'last_checked_at', 'first_confirmed_at', 'last_confirmed_at',
    'record_verification_id', 'source_id_list', 'internal_note', 'status_record_lifecycle',
    'revision_number', 'created_at', 'updated_at',
];
$establishment_contact_embedded_structure = [];
$establishment_contact_field_property = [
    '_id' => ['field_label' => 'Contact ID', 'field_description' => 'Canonical identifier for the establishment contact record.', 'type_data' => 'S', 'type_field' => 'HDN', 'type_sql' => 'CHAR(16)', 'is_mandatory' => true, 'is_unique' => true, 'is_indexed' => true],
    'establishment_id' => ['field_label' => 'Establishment', 'field_description' => 'Establishment that owns the official contact channel.', 'type_data' => 'S', 'type_field' => 'REF', 'type_sql' => 'CHAR(16)', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'type_contact_channel' => ['field_label' => 'Contact Channel Type', 'field_description' => 'Controlled medium used for the channel, such as phone, email, website, or social media.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'is_mandatory' => true, 'is_indexed' => true],
    'type_contact_number' => ['field_label' => 'Contact Number Type', 'field_description' => 'Controlled telephone technology subtype when the channel is a telephone number.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)'],
    'contact_label' => ['field_label' => 'Contact Label', 'field_description' => 'Short public purpose label, such as Reservations or Customer Support.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(100)', 'max_character' => 100],
    'contact_value_display' => ['field_label' => 'Display Contact Value', 'field_description' => 'Human-readable channel value presented to approved audiences.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(500)', 'is_mandatory' => true, 'max_character' => 500],
    'contact_value_normalized' => ['field_label' => 'Normalized Contact Value', 'field_description' => 'Canonical comparison and action value after channel-specific normalization.', 'type_data' => 'S', 'type_field' => 'TXT', 'type_sql' => 'VARCHAR(500)', 'is_indexed' => true, 'max_character' => 500],
    'contact_url' => ['field_label' => 'Contact URL', 'field_description' => 'Validated action URL appropriate to the channel, including tel, mailto, https, or an approved platform URL.', 'type_data' => 'S', 'type_field' => 'URL', 'type_sql' => 'VARCHAR(2048)', 'max_character' => 2048, 'constraint_text_input' => ['URL']],
    'is_primary' => ['field_label' => 'Primary Contact', 'field_description' => 'Whether this is the primary channel for its contact type or purpose.', 'type_data' => 'B', 'type_field' => 'CHK', 'type_sql' => 'BOOLEAN', 'default_value' => false],
    'visibility_scope' => ['field_label' => 'Visibility Scope', 'field_description' => 'Maximum audience allowed to view or use the channel.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'default_value' => 'PUB'],
    'status_contact_channel' => ['field_label' => 'Contact Channel Status', 'field_description' => 'Observed activity state of the channel independent from record lifecycle.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'default_value' => 'UNK', 'is_indexed' => true],
    'effective_from' => ['field_label' => 'Effective From', 'field_description' => 'Date on which the contact channel became effective when known.', 'type_data' => 'S', 'type_field' => 'DTI', 'type_sql' => 'DATE'],
    'effective_until' => ['field_label' => 'Effective Until', 'field_description' => 'Date on which the channel ceased to apply; historical records remain retained.', 'type_data' => 'S', 'type_field' => 'DTI', 'type_sql' => 'DATE'],
    'type_date_precision' => ['field_label' => 'Date Precision', 'field_description' => 'Controlled precision of the effective dates.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)'],
    'type_date_qualifier' => ['field_label' => 'Date Qualifier', 'field_description' => 'Controlled exactness or uncertainty qualifier for the effective dates.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)'],
    'first_observed_at' => ['field_label' => 'First Observed At', 'field_description' => 'UTC time when Massage Nexus first observed the channel.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'last_observed_active_at' => ['field_label' => 'Last Observed Active At', 'field_description' => 'Latest UTC time when the channel was positively observed as active.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'first_observed_inactive_at' => ['field_label' => 'First Observed Inactive At', 'field_description' => 'First UTC time when the channel was observed as inactive.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'last_checked_at' => ['field_label' => 'Last Checked At', 'field_description' => 'Latest UTC time an activity check was attempted regardless of result.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'first_confirmed_at' => ['field_label' => 'First Confirmed At', 'field_description' => 'UTC time when adequate evidence first confirmed the channel.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'Latest UTC time when adequate evidence confirmed the channel.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
    'record_verification_id' => ['field_label' => 'Verification Record', 'field_description' => 'Latest relevant record-verification result for this channel.', 'type_data' => 'S', 'type_field' => 'REF', 'type_sql' => 'CHAR(16)', 'is_relational' => true],
    'source_id_list' => ['field_label' => 'Research Sources', 'field_description' => 'Research-source references supporting the channel and its observed state.', 'type_data' => 'A', 'type_field' => 'TAG', 'type_sql' => 'JSON', 'is_relational' => true],
    'internal_note' => ['field_label' => 'Internal Note', 'field_description' => 'Restricted operational note that must not be exposed as contact content.', 'type_data' => 'S', 'type_field' => 'TXA', 'type_sql' => 'TEXT', 'visibility_scope' => 'PRV'],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state independent from channel activity.', 'type_data' => 'S', 'type_field' => 'DDL', 'type_sql' => 'VARCHAR(8)', 'default_value' => 'ACT'],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token that increments by one on every accepted revision; the required concurrency token distinct from updated_at (docs/02-governance/edit-system.txt section 16).', 'type_data' => 'I', 'type_field' => 'NMB', 'type_sql' => 'INT', 'is_mandatory' => true, 'min_number' => 1],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC time when the contact record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC time when the contact record was last changed.', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true],
];
$establishment_contact_subfield_property = [];
$establishment_contact_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'establishment_active_channel', 'index_name' => 'ix_establishment_contact_owner_type_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'type_contact_channel', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_contact_channel', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
    ['index_key' => 'normalized_contact_lookup', 'index_name' => 'ix_establishment_contact_normalized', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => true, 'index_field_list' => [['field_name' => 'contact_value_normalized', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 30],
];
$establishment_contact_boundary = [
    'owns' => ['official establishment contact channel, effective history, observation, confirmation, and verification references'],
    'reference_field_list' => ['establishment_id', 'record_verification_id', 'source_id_list'],
    'does_not_own' => ['research-source URL merely citing the establishment', 'private practitioner contact', 'establishment identity', 'verification evidence', 'the lightweight contact_channel_list display snapshot embedded in establishment_main, which this collection supersedes as the historied authority'],
];
return [
    'establishment_contact_default' => $establishment_contact_default,
    'establishment_contact' => $establishment_contact,
    'establishment_contact_field_order' => $establishment_contact_field_order,
    'establishment_contact_embedded_structure' => $establishment_contact_embedded_structure,
    'establishment_contact_field_property' => $establishment_contact_field_property,
    'establishment_contact_subfield_property' => $establishment_contact_subfield_property,
    'establishment_contact_index_list' => $establishment_contact_index_list,
    'establishment_contact_boundary' => $establishment_contact_boundary,
];
