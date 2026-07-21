<?php
/**
 * Title: Massage Nexus Record Revision Structure Guide
 * Version: 1.00
 * Collection: record_revision
 * Description: Stores one immutable, restorable revision snapshot for a supported non-specialized record.
 * Purpose: Documents generic record revision history without replacing specialized revision collections such as article_revision or the operational audit trail.
 */

$created_at = '2026-07-21T08:15:45Z';
$updated_at = '2026-07-21T08:15:45Z';
$record_revision_default = ['revision_note' => null, 'status_review' => 'NR', 'status_record_lifecycle' => 'ACT'];

$record_revision = [
    '_id' => 'Rv7K2pQ9xR4tV8zN', // Canonical 16-character revision identifier.
    'target_collection' => 'establishment_main', // Collection containing the revised record.
    'target_record_id' => 'Es7K2pQ9xR4tV8zN', // Stable target record identifier.
    'revision_number' => 7, // Monotonic target-local revision sequence.
    'snapshot_record' => ['status_establishment' => 'OP'], // Restorable record snapshot under the target contract.
    'changed_field_path_list' => ['status_establishment'], // Dotted paths changed from the preceding revision.
    'revision_note' => 'Confirmed current operating status from an official source.', // Human explanation for the revision.
    'source_contribution_id' => null, // Optional contribution_main that produced the revision.
    'source_revision_id' => null, // Earlier revision restored to create this new current revision.
    'status_review' => 'APR', // Review state when revision approval is required.
    'created_at' => $created_at, // UTC revision creation time.
    'created_by_user_id' => 'U2pR7vX4kT9mC5qL', // User or qualified system actor creating the revision.
    'status_record_lifecycle' => 'ACT', // Revision lifecycle state.
];

$record_revision_field_order = ['_id', 'target_collection', 'target_record_id', 'revision_number', 'snapshot_record', 'changed_field_path_list', 'revision_note', 'source_contribution_id', 'source_revision_id', 'status_review', 'created_at', 'created_by_user_id', 'status_record_lifecycle'];
$record_revision_embedded_structure = ['snapshot_record' => ['field_name' => 'value']];
$record_revision_field_property = [
    '_id' => ['field_label' => 'Record Revision ID', 'field_description' => 'Canonical revision identifier.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'target_collection' => ['field_label' => 'Target Collection', 'field_description' => 'Collection owning the revised record.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'target_record_id' => ['field_label' => 'Target Record ID', 'field_description' => 'Stable revised-record identifier.', 'type_data' => 'S', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic sequence within one target.', 'type_data' => 'I', 'min_number' => 1, 'is_mandatory' => true, 'is_indexed' => true],
    'snapshot_record' => ['field_label' => 'Record Snapshot', 'field_description' => 'Restorable snapshot governed by target_collection.', 'type_data' => 'O', 'is_mandatory' => true],
    'changed_field_path_list' => ['field_label' => 'Changed Field Path List', 'field_description' => 'Dotted paths changed from the preceding revision.', 'type_data' => 'A'],
    'revision_note' => ['field_label' => 'Revision Note', 'field_description' => 'Reason or summary for this revision.', 'type_data' => 'S'],
    'source_contribution_id' => ['field_label' => 'Source Contribution ID', 'field_description' => 'Optional originating contribution_main identifier.', 'type_data' => 'S', 'is_relational' => true],
    'source_revision_id' => ['field_label' => 'Source Revision ID', 'field_description' => 'Earlier revision used as a restoration source.', 'type_data' => 'S', 'is_relational' => true],
    'status_review' => ['field_label' => 'Review Status', 'field_description' => 'Revision review state.', 'type_data' => 'S', 'is_indexed' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC revision creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'Creating user_main identifier.', 'type_data' => 'S', 'is_relational' => true],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Revision lifecycle state.', 'type_data' => 'S', 'is_indexed' => true],
];
$record_revision_subfield_property = ['snapshot_record.*' => ['field_label' => 'Snapshot Field', 'field_description' => 'Dynamic field validated by target_collection.']];
$record_revision_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'target_revision_unique', 'index_name' => 'uq_record_revision_target_number', 'type_index' => 'CMP', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'target_collection', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'target_record_id', 'type_index_mode' => 'ASC', 'sort_order' => 20], ['field_name' => 'revision_number', 'type_index_mode' => 'DESC', 'sort_order' => 30]], 'sort_order' => 20],
];
$record_revision_boundary = ['owns' => ['immutable restorable snapshot and revision metadata'], 'references' => ['supported target record', 'contribution_main', 'earlier record_revision', 'user_main'], 'does_not_own' => ['current target record', 'operational audit event', 'source evidence', 'specialized article revision workflow']];

return compact('record_revision_default', 'record_revision', 'record_revision_field_order', 'record_revision_embedded_structure', 'record_revision_field_property', 'record_revision_subfield_property', 'record_revision_index_list', 'record_revision_boundary');
