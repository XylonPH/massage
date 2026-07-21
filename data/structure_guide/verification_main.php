<?php
/**
 * Title: Massage Nexus Verification Main Structure Guide
 * Version: 1.10
 * Collection: verification_main
 * Description: Stores one reviewed verification request and its evidence decision state.
 * Purpose: Documents the verification_main record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * - verification_main stores the shared verification process and result history
 *   for establishments, practitioners, contacts, and other supported targets.
 */

$created_at = '2026-07-20T12:00:00Z';
$updated_at = '2026-07-21T04:24:17Z';
$verification_main_default = [
	'status_verification' => 'PEN', // PEN = Pending
	'type_evidence_list' => [],
	'reviewer_user_id' => null,
	'reviewed_at' => null,
	'expires_at' => null,
	'is_revoked' => false,
];

$verification_main = [
	# Primary
	'_id' => 'V5mK2pQ9xR4tY8zB', // Verification ID.

	# Target
	'target_collection' => 'establishment_main', // Target Collection.
	'target_record_id' => 'E6sQ2nW9kD4vH7pM', // Target Record ID.
	'type_verification' => 'LIC', // LIC = License, GOV = Government ID, CON = Contact Info, OPN = Open Status
	
	# Submission
	'submitter_user_id' => 'U5rK8mP2xN7qL4vA', // User who requested verification
	'type_evidence_list' => ['DOC', 'URL'], // E.g., Document upload, external link
	'evidence_note' => 'Attached business permit for the current year.', // Evidence Note.
	
	# Status & Review
	'status_verification' => 'APR', // PEN = Pending, APR = Approved, REJ = Rejected, REV = Revoked
	'reviewer_user_id' => 'U2pR7vX4kT9mC5qL', // Administrator who reviewed it
	'reviewed_at' => '2026-07-20T14:30:00Z', // Reviewed At.
	'decision_note' => 'Valid business permit checked.', // Decision Note.
	
	# Lifecycle
	'expires_at' => '2027-12-31T23:59:59Z', // Verification might be time-bound
	'is_revoked' => false, // Is Revoked.
	
	# Audit
	'created_at' => $created_at, // Created At.
	'updated_at' => $updated_at, // Updated At.
];

$verification_main_field_order = [
	'_id',
	'target_collection',
	'target_record_id',
	'type_verification',
	'submitter_user_id',
	'type_evidence_list',
	'evidence_note',
	'status_verification',
	'reviewer_user_id',
	'reviewed_at',
	'decision_note',
	'expires_at',
	'is_revoked',
	'created_at',
	'updated_at',
];

$verification_main_embedded_structure = [];

$verification_main_field_property = [
	'_id' => ['field_label' => 'Verification ID', 'type_data' => 'S', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_system' => true, 'is_indexed' => true],
	
	'target_collection' => ['field_label' => 'Target Collection', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
	'target_record_id' => ['field_label' => 'Target Record ID', 'type_data' => 'S', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
	'type_verification' => ['field_label' => 'Verification Type', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	
	'submitter_user_id' => ['field_label' => 'Submitter User ID', 'type_data' => 'S', 'is_relational' => true, 'is_indexed' => true],
	'type_evidence_list' => ['field_label' => 'Evidence Types', 'type_data' => 'A'],
	'evidence_note' => ['field_label' => 'Evidence Note', 'type_data' => 'S'],
	
	'status_verification' => ['field_label' => 'Verification Status', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'reviewer_user_id' => ['field_label' => 'Reviewer User ID', 'type_data' => 'S', 'is_relational' => true, 'is_indexed' => true],
	'reviewed_at' => ['field_label' => 'Reviewed At', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'decision_note' => ['field_label' => 'Decision Note', 'type_data' => 'S'],
	
	'expires_at' => ['field_label' => 'Expires At', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_indexed' => true],
	'is_revoked' => ['field_label' => 'Is Revoked', 'type_data' => 'B', 'type_field' => 'CHK', 'type_sql' => 'BOOLEAN'],
	
	'created_at' => ['field_label' => 'Created At', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true, 'is_indexed' => true],
	'updated_at' => ['field_label' => 'Updated At', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
];

$verification_main_subfield_property = [];

$verification_main_index_list = [
    [
        'index_key' => 'primary',
        'index_name' => '_id_',
        'type_index' => 'STD',
        'is_unique' => true,
        'is_sparse' => false,
        'index_field_list' => [
            ['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10],
        ],
        'sort_order' => 10,
    ],
];

$verification_main_boundary = [
    'owns' => [
        'the verification_main record fields and embedded structures documented in this file',
    ],
    'reference_field_list' => [
        'target_record_id',
        'submitter_user_id',
        'reviewer_user_id',
    ],
    'does_not_own' => [
        'records stored in referenced collections',
        'runtime authorization, migration, seeding, or deployment behavior',
    ],
];

return [
    'verification_main_default' => $verification_main_default,
    'verification_main' => $verification_main,
    'verification_main_field_order' => $verification_main_field_order,
    'verification_main_embedded_structure' => $verification_main_embedded_structure,
    'verification_main_field_property' => $verification_main_field_property,
    'verification_main_subfield_property' => $verification_main_subfield_property,
    'verification_main_index_list' => $verification_main_index_list,
    'verification_main_boundary' => $verification_main_boundary,
];
