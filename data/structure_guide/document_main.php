<?php
/**
 * Title: Massage Nexus Secure Document Structure Guide
 * Version: 1.00
 * Collection: document_main
 * Description: Stores secure metadata and storage references for one document used by a supported target, claim, credential, or verification.
 * Purpose: Keeps document identity, access, redaction, verification, and retention separate from target records and from the binary file itself.
 *
 * Privacy: Full identifiers, signatures, health evidence, identity evidence, and unredacted files require restricted access. Public display at a physical location does not authorize online publication.
 */
$created_at = '2026-07-21T08:15:45Z';
$updated_at = '2026-07-21T08:15:45Z';
$document_main_default = ['target_reference_list' => [], 'document_number' => null, 'document_number_masked' => null, 'expiry_date' => null, 'visibility_scope' => 'PRV', 'status_verification' => 'U', 'status_redaction' => 'NR', 'retention_until' => null, 'status_record_lifecycle' => 'ACT'];
$document_main = [
    '_id' => 'Do7K2pQ9xR4tV8zN', // Canonical 16-character document identifier.
    'target_reference_list' => [['target_collection' => 'claim_main', 'target_record_id' => 'Cl7K2pQ9xR4tV8zN']], // Bounded records this document supports.
    'type_document' => 'BPR', // Controlled document category such as business permit.
    'document_title' => 'Sample City Business Permit 2026', // Safe human-readable title.
    'issuer_name' => 'Sample City Business Permits Office', // Issuer name snapshot.
    'issuer_organization_id' => null, // organization_main identifier when the issuer is catalogued.
    'document_number' => null, // Restricted complete number only when necessary.
    'document_number_masked' => 'BP-2026-••••42', // Safe masked number for authorized display.
    'issue_date' => '2026-01-15', // Document issue date.
    'expiry_date' => '2026-12-31', // Document expiry date.
    'jurisdiction_text' => 'Sample City, Philippines', // Issuing jurisdiction snapshot or description.
    'file_storage_reference' => 'private:document/Do7K2pQ9xR4tV8zN', // Protected object-storage reference, never a public path.
    'checksum' => 'sha256:0123456789abcdef', // Integrity checksum and algorithm label.
    'mime_type' => 'application/pdf', // Validated media type.
    'page_count' => 2, // Non-negative page count when known.
    'visibility_scope' => 'PRV', // Audience visibility rule.
    'level_access' => 'ADM', // Internal access classification.
    'is_publicly_displayed_at_source' => true, // Evidence context, not publication permission.
    'status_redaction' => 'REQ', // Public-redaction workflow state.
    'status_verification' => 'SUP', // Evidence support result.
    'category_retention' => 'VRF', // Retention category controlling disposal review.
    'retention_until' => '2032-12-31T23:59:59Z', // Retention deadline unless legal hold applies.
    'source_id' => 'Sr7K2pQ9xR4tV8zN', // research_source describing origin.
    'uploaded_by_user_id' => 'U2pR7vX4kT9mC5qL', // Authorized uploader.
    'created_at' => $created_at, // UTC record creation time.
    'updated_at' => $updated_at, // UTC record update time.
    'status_record_lifecycle' => 'ACT', // Database lifecycle state.
];
$document_main_field_order = ['_id', 'target_reference_list', 'type_document', 'document_title', 'issuer_name', 'issuer_organization_id', 'document_number', 'document_number_masked', 'issue_date', 'expiry_date', 'jurisdiction_text', 'file_storage_reference', 'checksum', 'mime_type', 'page_count', 'visibility_scope', 'level_access', 'is_publicly_displayed_at_source', 'status_redaction', 'status_verification', 'category_retention', 'retention_until', 'source_id', 'uploaded_by_user_id', 'created_at', 'updated_at', 'status_record_lifecycle'];
$document_main_embedded_structure = ['target_reference_list' => ['target_collection' => 'claim_main', 'target_record_id' => 'Cl7K2pQ9xR4tV8zN']];
$document_main_field_property = [
    '_id' => ['field_label' => 'Document ID', 'field_description' => 'Canonical secure-document identifier.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'target_reference_list' => ['field_label' => 'Target Reference List', 'field_description' => 'Bounded records supported by this document.', 'type_data' => 'A'],
    'type_document' => ['field_label' => 'Document Type', 'field_description' => 'Controlled document category.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'document_title' => ['field_label' => 'Document Title', 'field_description' => 'Safe human-readable title.', 'type_data' => 'S', 'is_mandatory' => true],
    'issuer_name' => ['field_label' => 'Issuer Name', 'field_description' => 'Issuer name snapshot.', 'type_data' => 'S'],
    'issuer_organization_id' => ['field_label' => 'Issuer Organization ID', 'field_description' => 'Optional organization_main issuer.', 'type_data' => 'S', 'is_relational' => true],
    'document_number' => ['field_label' => 'Document Number', 'field_description' => 'Restricted full number when necessary.', 'type_data' => 'S'],
    'document_number_masked' => ['field_label' => 'Masked Document Number', 'field_description' => 'Masked display-safe number.', 'type_data' => 'S'],
    'issue_date' => ['field_label' => 'Issue Date', 'field_description' => 'Document issue date.', 'type_data' => 'S', 'type_field' => 'DTE'],
    'expiry_date' => ['field_label' => 'Expiry Date', 'field_description' => 'Document expiry date.', 'type_data' => 'S', 'type_field' => 'DTE', 'is_indexed' => true],
    'jurisdiction_text' => ['field_label' => 'Jurisdiction', 'field_description' => 'Issuing jurisdiction snapshot.', 'type_data' => 'S'],
    'file_storage_reference' => ['field_label' => 'File Storage Reference', 'field_description' => 'Protected object-storage reference.', 'type_data' => 'S', 'is_mandatory' => true],
    'checksum' => ['field_label' => 'Checksum', 'field_description' => 'Integrity checksum.', 'type_data' => 'S', 'is_indexed' => true],
    'mime_type' => ['field_label' => 'MIME Type', 'field_description' => 'Validated media type.', 'type_data' => 'S'],
    'page_count' => ['field_label' => 'Page Count', 'field_description' => 'Non-negative page count.', 'type_data' => 'I', 'min_number' => 0],
    'visibility_scope' => ['field_label' => 'Visibility Scope', 'field_description' => 'Audience visibility rule.', 'type_data' => 'S', 'is_indexed' => true],
    'level_access' => ['field_label' => 'Access Level', 'field_description' => 'Internal access classification.', 'type_data' => 'S', 'is_indexed' => true],
    'is_publicly_displayed_at_source' => ['field_label' => 'Publicly Displayed at Source', 'field_description' => 'Whether source context displayed it publicly.', 'type_data' => 'B'],
    'status_redaction' => ['field_label' => 'Redaction Status', 'field_description' => 'Public-redaction workflow state.', 'type_data' => 'S', 'is_indexed' => true],
    'status_verification' => ['field_label' => 'Verification Status', 'field_description' => 'Evidence support result.', 'type_data' => 'S', 'is_indexed' => true],
    'category_retention' => ['field_label' => 'Retention Category', 'field_description' => 'Retention rule category.', 'type_data' => 'S', 'is_indexed' => true],
    'retention_until' => ['field_label' => 'Retention Until', 'field_description' => 'UTC disposal-review deadline.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_indexed' => true],
    'source_id' => ['field_label' => 'Source ID', 'field_description' => 'Originating research_source.', 'type_data' => 'S', 'is_relational' => true],
    'uploaded_by_user_id' => ['field_label' => 'Uploaded By User ID', 'field_description' => 'Authorized uploader.', 'type_data' => 'S', 'is_relational' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC creation time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state.', 'type_data' => 'S', 'is_indexed' => true],
];
$document_main_subfield_property = ['target_reference_list.target_collection' => ['field_label' => 'Target Collection', 'field_description' => 'Supported target collection.', 'type_data' => 'S'], 'target_reference_list.target_record_id' => ['field_label' => 'Target Record ID', 'field_description' => 'Supported target identifier.', 'type_data' => 'S']];
$document_main_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'target_lookup', 'index_name' => 'ix_document_main_target', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'target_reference_list.target_collection', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'target_reference_list.target_record_id', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20],
    ['index_key' => 'checksum_lookup', 'index_name' => 'ix_document_main_checksum', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => true, 'index_field_list' => [['field_name' => 'checksum', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 30],
];
$document_main_boundary = ['owns' => ['document metadata, secure storage reference, access, redaction, verification, and retention'], 'references' => ['supported targets', 'organization_main', 'research_source', 'user_main'], 'does_not_own' => ['binary file', 'claim decision', 'credential record', 'verification process', 'public publication permission inferred from source display']];
return compact('document_main_default', 'document_main', 'document_main_field_order', 'document_main_embedded_structure', 'document_main_field_property', 'document_main_subfield_property', 'document_main_index_list', 'document_main_boundary');
