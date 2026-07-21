<?php
/**
 * Title: Massage Nexus Record Verification Structure Guide
 * Version: 1.01
 * Collection: record_verification
 * Description: Stores one cross-target, optionally field-specific verification process and result.
 * Purpose: Documents how Massage Nexus verifies a record, relationship, field, claim, credential, contact, document, or other supported fact without storing the authoritative target or unrestricted evidence.
 *
 * Privacy: Store only the minimum result appropriate to this access class. Restricted evidence belongs in document_main; uncertain source claims belong in research_observation.
 */

$created_at = '2026-07-21T08:15:45Z';
$updated_at = '2026-07-21T09:49:12Z';

$record_verification_default = [
    'target_field_path' => null,
    'source_id_list' => [],
    'document_id_list' => [],
    'status_review' => 'PND',
    'status_verification' => 'U',
    'level_confidence' => 'U',
    'is_conflicting' => false,
    'visibility_scope' => 'PRV',
    'status_record_lifecycle' => 'ACT',
];

$record_verification = [
    '_id' => 'Vr7K2pQ9xR4tV8zN', // Canonical 16-character verification identifier.
    'target_collection' => 'establishment_contact', // Collection owning the fact being verified.
    'target_record_id' => 'Ct7K2pQ9xR4tV8zN', // Stable identifier of the target record.
    'target_field_path' => 'contact_value_normalized', // Optional dotted path when verification is field-specific.
    'type_verification' => 'CON', // Verification category such as contact control or business identity.
    'requirement_basis' => 'Public booking contact must be controllable by the establishment.', // Reason verification is required.
    'observed_at' => '2026-07-20T06:00:00Z', // When the supporting fact was observed.
    'performed_at' => '2026-07-21T07:30:00Z', // When the verification procedure was performed.
    'valid_until' => '2027-07-21T07:30:00Z', // Optional validity end or renewal deadline.
    'method_verification' => 'OTP', // Method used to verify the fact.
    'submitted_by_user_id' => 'U2pR7vX4kT9mC5qL', // User who submitted or requested verification.
    'verified_by_user_id' => 'U9mC5qL2pR7vX4kT', // Authorized human verifier; null for qualified system checks.
    'source_id_list' => ['Sr7K2pQ9xR4tV8zN'], // Supporting research_source identifiers.
    'document_id_list' => [], // Restricted document_main evidence identifiers.
    'status_review' => 'APR', // Workflow review state, separate from the verification result.
    'status_verification' => 'V', // Result such as Verified, Supported, Unverified, or Disputed.
    'level_confidence' => 'H', // Evidence-based confidence classification.
    'is_conflicting' => false, // Whether unresolved contradictory evidence exists.
    'result_summary' => 'Control of the published telephone number was confirmed by OTP.', // Minimum safe result summary.
    'restricted_detail_reference' => null, // Protected storage or case reference for details not stored here.
    'next_review_at' => '2027-06-21T00:00:00Z', // Scheduled freshness or renewal review.
    'visibility_scope' => 'PRV', // Audience allowed to view the verification record.
    'internal_note' => null, // Restricted operational note.
    'status_record_lifecycle' => 'ACT', // Database lifecycle state.
    'created_at' => $created_at, // UTC record creation time.
    'updated_at' => $updated_at, // UTC record update time.
];

$record_verification_field_order = [
    '_id', 'target_collection', 'target_record_id', 'target_field_path', 'type_verification',
    'requirement_basis', 'observed_at', 'performed_at', 'valid_until', 'method_verification',
    'submitted_by_user_id', 'verified_by_user_id', 'source_id_list', 'document_id_list',
    'status_review', 'status_verification', 'level_confidence', 'is_conflicting',
    'result_summary', 'restricted_detail_reference', 'next_review_at', 'visibility_scope',
    'internal_note', 'status_record_lifecycle', 'created_at', 'updated_at',
];

$record_verification_embedded_structure = [];

$record_verification_field_property = [
    '_id' => ['field_label' => 'Record Verification ID', 'field_description' => 'Canonical 16-character verification identifier.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'target_collection' => ['field_label' => 'Target Collection', 'field_description' => 'Collection owning the verified target.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'target_record_id' => ['field_label' => 'Target Record ID', 'field_description' => 'Identifier of the verified record.', 'type_data' => 'S', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'target_field_path' => ['field_label' => 'Target Field Path', 'field_description' => 'Optional dotted field path for field-level verification.', 'type_data' => 'S', 'is_indexed' => true],
    'type_verification' => ['field_label' => 'Verification Type', 'field_description' => 'Controlled verification purpose.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'requirement_basis' => ['field_label' => 'Requirement Basis', 'field_description' => 'Reason or rule requiring verification.', 'type_data' => 'S'],
    'observed_at' => ['field_label' => 'Observed At', 'field_description' => 'UTC time the supporting fact was observed.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'performed_at' => ['field_label' => 'Performed At', 'field_description' => 'UTC time the verification procedure occurred.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_indexed' => true],
    'valid_until' => ['field_label' => 'Valid Until', 'field_description' => 'Optional UTC validity end.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_indexed' => true],
    'method_verification' => ['field_label' => 'Verification Method', 'field_description' => 'Controlled method used to verify the fact.', 'type_data' => 'S'],
    'submitted_by_user_id' => ['field_label' => 'Submitted By User ID', 'field_description' => 'Submitting user_main identifier.', 'type_data' => 'S', 'is_relational' => true],
    'verified_by_user_id' => ['field_label' => 'Verified By User ID', 'field_description' => 'Authorized verifier user_main identifier.', 'type_data' => 'S', 'is_relational' => true],
    'source_id_list' => ['field_label' => 'Source ID List', 'field_description' => 'Supporting research_source identifiers.', 'type_data' => 'A', 'is_relational' => true],
    'document_id_list' => ['field_label' => 'Document ID List', 'field_description' => 'Restricted supporting document_main identifiers.', 'type_data' => 'A', 'is_relational' => true],
    'status_review' => ['field_label' => 'Review Status', 'field_description' => 'Workflow review state.', 'type_data' => 'S', 'is_indexed' => true],
    'status_verification' => ['field_label' => 'Verification Status', 'field_description' => 'Evidence result independent from workflow review.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'level_confidence' => ['field_label' => 'Confidence Level', 'field_description' => 'Evidence-based confidence.', 'type_data' => 'S'],
    'is_conflicting' => ['field_label' => 'Is Conflicting', 'field_description' => 'Whether unresolved contradictory evidence exists.', 'type_data' => 'B', 'is_indexed' => true],
    'result_summary' => ['field_label' => 'Result Summary', 'field_description' => 'Minimum safe explanation of the result.', 'type_data' => 'S'],
    'restricted_detail_reference' => ['field_label' => 'Restricted Detail Reference', 'field_description' => 'Protected reference for high-sensitivity details.', 'type_data' => 'S'],
    'next_review_at' => ['field_label' => 'Next Review At', 'field_description' => 'UTC time the fact should be rechecked.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_indexed' => true],
    'visibility_scope' => ['field_label' => 'Visibility Scope', 'field_description' => 'Audience visibility rule.', 'type_data' => 'S', 'is_indexed' => true],
    'internal_note' => ['field_label' => 'Internal Note', 'field_description' => 'Restricted operational note.', 'type_data' => 'S'],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state.', 'type_data' => 'S', 'is_indexed' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC record update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];

$record_verification_subfield_property = [];

$record_verification_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'target_field_status', 'index_name' => 'ix_record_verification_target_field_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'target_collection', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'target_record_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'target_field_path', 'type_index_mode' => 'ASC', 'sort_order' => 30], ['field_name' => 'status_verification', 'type_index_mode' => 'ASC', 'sort_order' => 40]], 'sort_order' => 20],
    ['index_key' => 'review_due', 'index_name' => 'ix_record_verification_next_review', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => true, 'index_field_list' => [['field_name' => 'next_review_at', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 30],
];

$record_verification_boundary = [
    'owns' => ['verification request, method, workflow state, evidence result, confidence, safe summary, and renewal timing'],
    'references' => ['any supported target record', 'research_source', 'document_main', 'user_main'],
    'does_not_own' => ['authoritative target fact', 'source assertion', 'unrestricted evidence binary', 'claim workflow', 'workspace permission'],
];

return ['record_verification_default' => $record_verification_default, 'record_verification' => $record_verification, 'record_verification_field_order' => $record_verification_field_order, 'record_verification_embedded_structure' => $record_verification_embedded_structure, 'record_verification_field_property' => $record_verification_field_property, 'record_verification_subfield_property' => $record_verification_subfield_property, 'record_verification_index_list' => $record_verification_index_list, 'record_verification_boundary' => $record_verification_boundary];
