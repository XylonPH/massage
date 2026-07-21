<?php
/**
 * Title: Massage Nexus Practitioner Credential Structure Guide
 * Version: 1.20
 * Collection: practitioner_credential
 * Description: Stores individual licenses, certificates, education, memberships, and competency credentials.
 * Purpose: Gives each credential independent evidence, verification, visibility, expiry, and lifecycle.
 */
$created_at = '2026-07-21T08:23:43Z';
$updated_at = '2026-07-21T10:48:10Z';
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
$practitioner_credential_field_order = ['_id', 'practitioner_id', 'type_credential', 'credential_name', 'credential_title_display', 'credential_number', 'credential_number_masked', 'issuing_organization_id', 'issuing_organization_name_snapshot', 'issuing_country_id', 'issuing_geographic_area_id', 'jurisdiction_note', 'related_service_id_list', 'related_capability_id_list', 'issued_at', 'issued_at_precision', 'issued_at_qualifier', 'valid_from', 'expires_at', 'status_credential_lifecycle', 'status_credential_verification', 'method_verification', 'verified_at', 'verified_by_user_id', 'last_checked_at', 'first_observed_at', 'last_confirmed_at', 'document_id_list', 'research_source_id_list', 'visibility_scope', 'public_display_note', 'internal_note', 'status_record_lifecycle', 'created_at', 'updated_at'];
$practitioner_credential_embedded_structure = [];
$practitioner_credential_field_property = [
    '_id' => ['field_label' => 'Credential ID', 'field_description' => 'Canonical credential record identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_indexed' => true],
    'practitioner_id' => ['field_label' => 'Practitioner', 'field_description' => 'Practitioner who holds the credential.', 'type_data' => 'S', 'type_field' => 'REF', 'is_indexed' => true],
    'type_credential' => ['field_label' => 'Credential Type', 'field_description' => 'Controlled credential category.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_indexed' => true],
    'credential_name' => ['field_label' => 'Credential Name', 'field_description' => 'Credential name as stated by the source.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'credential_title_display' => ['field_label' => 'Display Credential Title', 'field_description' => 'Approved public credential title.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'credential_number' => ['field_label' => 'Credential Number', 'field_description' => 'Restricted full credential identifier when collection is justified.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'credential_number_masked' => ['field_label' => 'Masked Credential Number', 'field_description' => 'Display-safe masked credential identifier.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'issuing_organization_id' => ['field_label' => 'Issuing Organization', 'field_description' => 'Canonical issuer organization reference.', 'type_data' => 'S', 'type_field' => 'REF', 'is_indexed' => true],
    'issuing_organization_name_snapshot' => ['field_label' => 'Issuer Name Snapshot', 'field_description' => 'Source-time issuer name when a reference is absent or insufficient.', 'type_data' => 'S', 'type_field' => 'TXT'],
    'issuing_country_id' => ['field_label' => 'Issuing Country', 'field_description' => 'Common-reference issuing-country identifier.', 'type_data' => 'I', 'type_field' => 'REF'],
    'issuing_geographic_area_id' => ['field_label' => 'Issuing Geographic Area', 'field_description' => 'Optional issuing-area reference.', 'type_data' => 'I', 'type_field' => 'REF'],
    'jurisdiction_note' => ['field_label' => 'Jurisdiction Note', 'field_description' => 'Jurisdiction qualification not represented by references.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'related_service_id_list' => ['field_label' => 'Related Services', 'field_description' => 'Canonical services related to the credential.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'related_capability_id_list' => ['field_label' => 'Related Capabilities', 'field_description' => 'Practitioner capabilities related to the credential.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'issued_at' => ['field_label' => 'Issued Date', 'field_description' => 'Best-supported credential issue date.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'issued_at_precision' => ['field_label' => 'Issue Date Precision', 'field_description' => 'Controlled precision of the issue date.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'issued_at_qualifier' => ['field_label' => 'Issue Date Qualifier', 'field_description' => 'Controlled qualifier of the issue date.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'valid_from' => ['field_label' => 'Valid From', 'field_description' => 'Credential validity start date.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'expires_at' => ['field_label' => 'Expires At', 'field_description' => 'Credential expiry date when applicable.', 'type_data' => 'S', 'type_field' => 'DTI', 'is_indexed' => true],
    'status_credential_lifecycle' => ['field_label' => 'Credential Lifecycle Status', 'field_description' => 'Controlled real-world credential lifecycle.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_indexed' => true],
    'status_credential_verification' => ['field_label' => 'Credential Verification Status', 'field_description' => 'Controlled verification result for the credential.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'method_verification' => ['field_label' => 'Verification Method', 'field_description' => 'Controlled method used to verify the credential.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'verified_at' => ['field_label' => 'Verified At', 'field_description' => 'UTC time formal verification was completed.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'verified_by_user_id' => ['field_label' => 'Verified By User', 'field_description' => 'Authorized verifier reference.', 'type_data' => 'S', 'type_field' => 'REF'],
    'last_checked_at' => ['field_label' => 'Last Checked At', 'field_description' => 'Latest UTC verification-check attempt.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'first_observed_at' => ['field_label' => 'First Observed At', 'field_description' => 'UTC time first observed.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'last_confirmed_at' => ['field_label' => 'Last Confirmed At', 'field_description' => 'Latest UTC adequate confirmation.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'document_id_list' => ['field_label' => 'Evidence Documents', 'field_description' => 'Protected evidence-document references.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'research_source_id_list' => ['field_label' => 'Research Sources', 'field_description' => 'Research provenance references.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'visibility_scope' => ['field_label' => 'Visibility Scope', 'field_description' => 'Maximum audience permitted to access the credential.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'public_display_note' => ['field_label' => 'Public Display Note', 'field_description' => 'Approved public credential qualification.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'internal_note' => ['field_label' => 'Internal Credential Note', 'field_description' => 'Restricted operational credential note.', 'type_data' => 'S', 'type_field' => 'TXA'],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle independent from credential lifecycle.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC creation time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$practitioner_credential_subfield_property = [];
$practitioner_credential_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'holder_status', 'index_name' => 'ix_practitioner_credential_holder_type_status_expiry', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'practitioner_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'type_credential', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'status_credential_lifecycle', 'type_index_mode' => 'ASC', 'sort_order' => 30], ['field_name' => 'expires_at', 'type_index_mode' => 'ASC', 'sort_order' => 40]], 'sort_order' => 20],
    ['index_key' => 'issuer', 'index_name' => 'ix_practitioner_credential_issuer', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => true, 'index_field_list' => [['field_name' => 'issuing_organization_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 30],
];
$practitioner_credential_boundary = ['owns' => ['individual credential identity, masked identifiers, evidence references, verification, visibility, and validity'], 'references' => ['practitioner, organization, service, document, and research records'], 'does_not_own' => ['binary evidence, practitioner identity, or a blanket practitioner certification flag']];
return ['practitioner_credential_default' => $practitioner_credential_default, 'practitioner_credential' => $practitioner_credential, 'practitioner_credential_field_order' => $practitioner_credential_field_order, 'practitioner_credential_embedded_structure' => $practitioner_credential_embedded_structure, 'practitioner_credential_field_property' => $practitioner_credential_field_property, 'practitioner_credential_subfield_property' => $practitioner_credential_subfield_property, 'practitioner_credential_index_list' => $practitioner_credential_index_list, 'practitioner_credential_boundary' => $practitioner_credential_boundary];
