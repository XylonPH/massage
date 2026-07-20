<?php
/**
 * Title: Massage Nexus Verification Main Structure Guide
 * Author: Xylon Reyes
 *
 * Collection: verification_main
 * Version: 1.00
 * This file is a PHP-readable visual structure guide.
 *
 * Current scope:
 * - verification_main stores the shared verification process and result
 *   history (e.g. verifying an establishment, practitioner, or contact).
 */

# Variable
$created_at = '2026-07-20T12:00:00Z';
$updated_at = '2026-07-20T12:00:00Z';

$field_property_default = [
	'type_data' => 'S',
	'type_field' => 'TXT',
	'is_translatable' => false,
	'is_mandatory' => false,
	'is_relational' => false,
	'is_indexed' => false,
];

$verification_main_record_default = [
	'status_verification' => 'PEN', // PEN = Pending
	'type_evidence_list' => [],
	'reviewer_user_id' => null,
	'reviewed_at' => null,
	'expires_at' => null,
	'is_revoked' => false,
];

$verification_main = [
	# Primary
	'_id' => 'V5mK2pQ9xR4tY8zB',

	# Target
	'target_collection' => 'establishment_main',
	'target_record_id' => 'E6sQ2nW9kD4vH7pM',
	'type_verification' => 'LIC', // LIC = License, GOV = Government ID, CON = Contact Info, OPN = Open Status
	
	# Submission
	'submitter_user_id' => 'U5rK8mP2xN7qL4vA', // User who requested verification
	'type_evidence_list' => ['DOC', 'URL'], // E.g., Document upload, external link
	'evidence_note' => 'Attached business permit for the current year.',
	
	# Status & Review
	'status_verification' => 'APR', // PEN = Pending, APR = Approved, REJ = Rejected, REV = Revoked
	'reviewer_user_id' => 'U2pR7vX4kT9mC5qL', // Administrator who reviewed it
	'reviewed_at' => '2026-07-20T14:30:00Z',
	'decision_note' => 'Valid business permit checked.',
	
	# Lifecycle
	'expires_at' => '2027-12-31T23:59:59Z', // Verification might be time-bound
	'is_revoked' => false,
	
	# Audit
	'created_at' => $created_at,
	'updated_at' => $updated_at,
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
