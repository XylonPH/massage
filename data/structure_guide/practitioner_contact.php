<?php
/**
 * Title: Massage Nexus Practitioner Contact Structure Guide
 * Version: 1.00
 * Collection: practitioner_contact
 * Description: Stores one professional contact channel for a practitioner with explicit context, visibility, history, and verification.
 * Purpose: Separates professional practitioner contacts from public profile identity, private personal contacts, and establishment-owned channels.
 *
 * Privacy: Never publish a private phone, email, residence, or personal social account merely because research encountered it.
 */
$created_at = '2026-07-21T08:15:45Z';
$updated_at = '2026-07-21T08:15:45Z';
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
$practitioner_contact_field_order = array_keys($practitioner_contact);
$practitioner_contact_embedded_structure = [];
$practitioner_contact_field_property = [];
foreach ($practitioner_contact as $field_name => $sample_value) { $practitioner_contact_field_property[$field_name] = ['field_label' => ucwords(str_replace('_', ' ', $field_name)), 'field_description' => 'Practitioner professional contact property: ' . $field_name . '.', 'type_data' => is_array($sample_value) ? 'A' : (is_bool($sample_value) ? 'B' : 'S')]; }
$practitioner_contact_subfield_property = [];
$practitioner_contact_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'practitioner_context_status', 'index_name' => 'ix_practitioner_contact_owner_context_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'practitioner_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_contact_channel', 'type_index_mode' => 'ASC', 'sort_order' => 30]], 'sort_order' => 20],
];
$practitioner_contact_boundary = ['owns' => ['approved professional practitioner contact channel, visibility, history, and verification reference'], 'references' => ['practitioner_main', 'optional establishment context', 'record_verification', 'research_source'], 'does_not_own' => ['private personal contact', 'establishment-owned contact', 'practitioner identity', 'booking contact snapshot']];
return compact('practitioner_contact_default', 'practitioner_contact', 'practitioner_contact_field_order', 'practitioner_contact_embedded_structure', 'practitioner_contact_field_property', 'practitioner_contact_subfield_property', 'practitioner_contact_index_list', 'practitioner_contact_boundary');
