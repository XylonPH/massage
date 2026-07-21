<?php
/**
 * Title: Massage Nexus Practitioner Credential Structure Guide
 * Version: 1.00
 * Collection: practitioner_credential
 * Description: Stores individual licenses, certificates, education, memberships, and competency credentials.
 * Purpose: Gives each credential independent evidence, verification, visibility, expiry, and lifecycle.
 */
$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T08:23:43Z';
$practitioner_credential_default = ['related_service_id_list' => [], 'related_capability_id_list' => [], 'status_credential_lifecycle' => 'UN', 'status_credential_verification' => 'SD', 'visibility_scope' => 'PRV', 'status_record_lifecycle' => 'ACT'];
$practitioner_credential = [
    '_id' => 'PcrK2pQ9xR4tV7zN', // Canonical credential identifier.
    'practitioner_id' => 'P8rC3mL7xT1qV5nK', // Credential holder.
    'type_credential' => 'TRC', // Credential type.
    'credential_name' => 'Sample Massage Training Certificate', // Source credential name.
    'credential_title_display' => 'Massage Training Certificate', // Public display title.
    'credential_number' => null, // Restricted full identifier when needed.
    'credential_number_masked' => 'MTC-•••-123', // Public-safe masked identifier.
    'issuing_organization_id' => 'Or8K2pQ9xR4tV7zN', // Canonical issuer when known.
    'issuing_organization_name_snapshot' => 'Sample Training Institute', // Source-time issuer snapshot.
    'issuing_country_id' => 608, // Common-reference country ID.
    'issuing_geographic_area_id' => null, // Optional geographic-area reference.
    'jurisdiction_note' => 'Philippines', // Jurisdiction summary.
    'related_service_id_list' => ['Sv8K2pQ9xR4tV7zN'], // Related normalized services.
    'related_capability_id_list' => [], // Related capability records.
    'issued_at' => '2022-06-01', // Issue date.
    'issued_at_precision' => 'M', // Issue-date precision.
    'issued_at_qualifier' => 'EXA', // Issue-date qualifier.
    'valid_from' => '2022-06-01', // Validity start.
    'expires_at' => null, // Expiry date.
    'status_credential_lifecycle' => 'AC', // Credential lifecycle.
    'status_credential_verification' => 'VF', // Credential verification result.
    'method_verification' => 'DOC', // Verification method.
    'verified_at' => '2026-07-18T00:00:00Z', // Formal verification time.
    'verified_by_user_id' => 'U2pR7vX4kT9mC5qL', // Verifier.
    'last_checked_at' => '2026-07-18T00:00:00Z', // Latest check attempt.
    'first_observed_at' => '2026-07-01T00:00:00Z', // First observation.
    'last_confirmed_at' => '2026-07-18T00:00:00Z', // Latest adequate confirmation.
    'document_id_list' => ['Do8K2pQ9xR4tV7zN'], // Evidence documents.
    'research_source_id_list' => [], // Supporting sources.
    'visibility_scope' => 'PUB', // Display/access classification.
    'public_display_note' => 'Credential verified from a redacted copy.', // Public note.
    'internal_note' => null, // Restricted note.
    'status_record_lifecycle' => 'ACT', // Database lifecycle.
    'created_at' => $created_at, // UTC creation time.
    'updated_at' => $updated_at, // UTC update time.
];
$practitioner_credential_field_order = array_keys($practitioner_credential);
$practitioner_credential_embedded_structure = [];
$practitioner_credential_field_property = [];
foreach ($practitioner_credential as $field_name => $sample_value) {
    $type_data = is_array($sample_value) ? 'A' : (is_int($sample_value) ? 'I' : 'S');
    $practitioner_credential_field_property[$field_name] = ['field_label' => ucwords(str_replace('_', ' ', $field_name)), 'field_description' => 'Practitioner credential field: ' . str_replace('_', ' ', $field_name) . '.', 'type_data' => $type_data];
}
$practitioner_credential_field_property['_id']['is_mandatory'] = true;
$practitioner_credential_field_property['practitioner_id']['is_mandatory'] = true;
$practitioner_credential_field_property['type_credential']['is_mandatory'] = true;
$practitioner_credential_subfield_property = [];
$practitioner_credential_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'holder_status', 'index_name' => 'ix_practitioner_credential_holder_type_status_expiry', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'practitioner_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'type_credential', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_credential_lifecycle', 'type_index_mode' => 'ASC', 'sort_order' => 30], ['field_name' => 'expires_at', 'type_index_mode' => 'ASC', 'sort_order' => 40]], 'sort_order' => 20],
    ['index_key' => 'issuer', 'index_name' => 'ix_practitioner_credential_issuer', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => true, 'index_field_list' => [['field_name' => 'issuing_organization_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 30],
];
$practitioner_credential_boundary = ['owns' => ['individual credential identity, masked identifiers, evidence references, verification, visibility, and validity'], 'references' => ['practitioner, organization, service, document, and research records'], 'does_not_own' => ['binary evidence, practitioner identity, or a blanket practitioner certification flag']];
return compact('practitioner_credential_default', 'practitioner_credential', 'practitioner_credential_field_order', 'practitioner_credential_embedded_structure', 'practitioner_credential_field_property', 'practitioner_credential_subfield_property', 'practitioner_credential_index_list', 'practitioner_credential_boundary');
