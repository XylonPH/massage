<?php
/**
 * Title: Massage Nexus Practitioner Contact Structure Guide
 * Version: 1.10
 * Collection: practitioner_contact
 * Description: Stores one professional contact channel for a practitioner with explicit context, visibility, history, and verification.
 * Purpose: Separates professional practitioner contacts from public profile identity, private personal contacts, and establishment-owned channels.
 *
 * Privacy: Never publish a private phone, email, residence, or personal social account merely because research encountered it.
 */
$created_at = '2026-07-21T08:15:45Z';
$updated_at = '2026-07-21T09:49:12Z';
$practitioner_contact_default = ['establishment_id' => null, 'is_primary' => false, 'visibility_scope' => 'PRV', 'status_contact_channel' => 'UNK', 'effective_until' => null, 'status_record_lifecycle' => 'ACT'];
$practitioner_contact = [
    '_id' => 'Pc7K2pQ9xR4tV8zN', // Canonical 16-character contact identifier.
    'practitioner_id' => 'Pr7K2pQ9xR4tV8zN', // Owning practitioner_main identifier.
    'establishment_id' => null, // Optional professional context; establishment-owned contacts remain establishment_contact.
    'type_contact_channel' => 'EML', // Professional channel medium.
    'type_contact_number' => null, // Telephone subtype when applicable.
    'contact_label' => 'Independent practice inquiries', // Safe professional purpose label.
    'contact_value_display' => 'hello@example.invalid', // Approved display form.
    'contact_value_normalized' => 'hello@example.invalid', // Normalized comparison value.
    'contact_url' => 'mailto:hello@example.invalid', // Validated safe action URL.
    'is_primary' => true, // Primary professional channel for its context.
    'visibility_scope' => 'PUB', // Public only with authority and appropriate consent or basis.
    'status_contact_channel' => 'AC', // Observed channel activity state.
    'effective_from' => '2026-06-01', // Effective start when known.
    'effective_until' => null, // Effective end when known.
    'last_checked_at' => '2026-07-21T07:00:00Z', // Latest check attempt.
    'last_confirmed_at' => '2026-07-21T07:00:00Z', // Latest adequate confirmation.
    'record_verification_id' => 'Vr8K2pQ9xR4tV8zN', // Latest relevant verification result.
    'source_id_list' => ['Sr8K2pQ9xR4tV8zN'], // Supporting sources.
    'internal_note' => null, // Restricted operational note.
    'status_record_lifecycle' => 'ACT', // Database lifecycle state.
    'created_at' => $created_at, // UTC record creation time.
    'updated_at' => $updated_at, // UTC record update time.
];
$practitioner_contact_field_order = ['_id', 'practitioner_id', 'establishment_id', 'type_contact_channel', 'type_contact_number', 'contact_label', 'contact_value_display', 'contact_value_normalized', 'contact_url', 'is_primary', 'visibility_scope', 'status_contact_channel', 'effective_from', 'effective_until', 'last_checked_at', 'last_confirmed_at', 'record_verification_id', 'source_id_list', 'internal_note', 'status_record_lifecycle', 'created_at', 'updated_at'];
$practitioner_contact_embedded_structure = [];
$practitioner_contact_field_property = [
    '_id' => ['field_label' => 'Practitioner Contact ID', 'field_description' => 'Canonical identifier for the approved professional contact channel.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_unique' => true, 'is_indexed' => true],
    'practitioner_id' => ['field_label' => 'Practitioner', 'field_description' => 'Practitioner whose professional contact channel is represented.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'establishment_id' => ['field_label' => 'Establishment Context', 'field_description' => 'Optional establishment context within which the professional channel applies.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true, 'is_indexed' => true],
    'type_contact_channel' => ['field_label' => 'Contact Channel Type', 'field_description' => 'Controlled medium used for the professional contact channel.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
    'type_contact_number' => ['field_label' => 'Contact Number Type', 'field_description' => 'Controlled telephone technology subtype when applicable.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'contact_label' => ['field_label' => 'Contact Label', 'field_description' => 'Short approved purpose label for the professional channel.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'contact_value_display' => ['field_label' => 'Display Contact Value', 'field_description' => 'Human-readable professional contact value shown to approved audiences.', 'type_data' => 'S', 'type_field' => 'TXT', 'is_mandatory' => true],
    'contact_value_normalized' => ['field_label' => 'Normalized Contact Value', 'field_description' => 'Canonical comparison and action value after channel-specific normalization.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'contact_url' => ['field_label' => 'Contact URL', 'field_description' => 'Validated safe action URL appropriate to the channel.', 'type_data' => 'S', 'type_field' => 'URL'],
    'is_primary' => ['field_label' => 'Primary Contact', 'field_description' => 'Whether this is the primary professional channel for its context.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false],
    'visibility_scope' => ['field_label' => 'Visibility Scope', 'field_description' => 'Maximum audience permitted to access the professional channel.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'PRV'],
    'status_contact_channel' => ['field_label' => 'Contact Channel Status', 'field_description' => 'Observed activity state of the channel.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'UNK', 'is_indexed' => true],
    'effective_from' => ['field_label' => 'Effective From', 'field_description' => 'Date on which the channel began to apply in this context.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'effective_until' => ['field_label' => 'Effective Until', 'field_description' => 'Date after which the channel no longer applies in this context.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'last_checked_at' => ['field_label' => 'Last Checked At', 'field_description' => 'Latest UTC time an activity check was attempted.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'Latest UTC time adequate evidence confirmed the channel.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'record_verification_id' => ['field_label' => 'Verification Record', 'field_description' => 'Latest relevant record-verification result for the channel.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'source_id_list' => ['field_label' => 'Research Sources', 'field_description' => 'Research-source references supporting the channel.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true],
    'internal_note' => ['field_label' => 'Internal Contact Note', 'field_description' => 'Restricted operational note that must not expose private personal contact.', 'type_data' => 'S', 'type_field' => 'TXA', 'visibility_scope' => 'PRV'],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state independent from channel activity.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'ACT'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC time when the contact record was created.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC time when the contact record was last changed.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
];
$practitioner_contact_subfield_property = [];
$practitioner_contact_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'practitioner_context_status', 'index_name' => 'ix_practitioner_contact_owner_context_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'practitioner_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_contact_channel', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
];
$practitioner_contact_boundary = ['owns' => ['approved professional practitioner contact channel, visibility, history, and verification reference'], 'references' => ['practitioner_main', 'optional establishment context', 'record_verification', 'research_source'], 'does_not_own' => ['private personal contact', 'establishment-owned contact', 'practitioner identity', 'booking contact snapshot']];
return ['practitioner_contact_default' => $practitioner_contact_default, 'practitioner_contact' => $practitioner_contact, 'practitioner_contact_field_order' => $practitioner_contact_field_order, 'practitioner_contact_embedded_structure' => $practitioner_contact_embedded_structure, 'practitioner_contact_field_property' => $practitioner_contact_field_property, 'practitioner_contact_subfield_property' => $practitioner_contact_subfield_property, 'practitioner_contact_index_list' => $practitioner_contact_index_list, 'practitioner_contact_boundary' => $practitioner_contact_boundary];
