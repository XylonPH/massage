<?php
/**
 * Title: Massage Nexus Claim Main Structure Guide
 * Version: 1.10
 * Collection: claim_main
 * Description: Stores one reviewed claim of authority or relationship to a governed record.
 * Purpose: Documents the claim_main record shape for review, validation, comparison, and implementation without acting as runtime code, a migration, or a seed.
 *
 * Notes:
 * - claim_main stores ownership or management claims for claimable targets
 *   such as establishments or practitioner profiles.
 */

$created_at = '2026-07-20T12:00:00Z';
$updated_at = '2026-07-21T04:24:17Z';
$claim_main_default = [
	'status_claim' => 'PEN', // PEN = Pending
	'type_relationship' => 'MGR', // MGR = Manager
	'reviewer_user_id' => null,
	'reviewed_at' => null,
	'is_revoked' => false,
];

$claim_main = [
	# Primary
	'_id' => 'C7xK2pQ9xR4tY8zA', // Claim ID.

	# Target
	'target_collection' => 'establishment_main', // Target Collection.
	'target_record_id' => 'E6sQ2nW9kD4vH7pM', // Target Record ID.
	
	# Submission
	'claimant_user_id' => 'U5rK8mP2xN7qL4vA', // User claiming the profile
	'type_relationship' => 'OWN', // OWN = Owner, MGR = Manager, PR = Public Relations, EMP = Employee
	'claim_evidence_note' => 'I am the registered owner, here is the DTI registration.', // Claim Evidence Note.
	
	# Status & Review
	'status_claim' => 'APR', // PEN = Pending, APR = Approved, REJ = Rejected, REV = Revoked
	'reviewer_user_id' => 'U2pR7vX4kT9mC5qL', // Administrator who reviewed it
	'reviewed_at' => '2026-07-20T14:45:00Z', // Reviewed At.
	'decision_note' => 'Ownership verified via provided documents.', // Decision Note.
	
	# Lifecycle
	'is_revoked' => false, // Is Revoked.
	
	# Audit
	'created_at' => $created_at, // Created At.
	'updated_at' => $updated_at, // Updated At.
];

$claim_main_field_order = [
	'_id',
	'target_collection',
	'target_record_id',
	'claimant_user_id',
	'type_relationship',
	'claim_evidence_note',
	'status_claim',
	'reviewer_user_id',
	'reviewed_at',
	'decision_note',
	'is_revoked',
	'created_at',
	'updated_at',
];

$claim_main_embedded_structure = [];

$claim_main_field_property = [
	'_id' => ['field_label' => 'Claim ID', 'type_data' => 'S', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_system' => true, 'is_indexed' => true],
	
	'target_collection' => ['field_label' => 'Target Collection', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
	'target_record_id' => ['field_label' => 'Target Record ID', 'type_data' => 'S', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
	
	'claimant_user_id' => ['field_label' => 'Claimant User ID', 'type_data' => 'S', 'is_relational' => true, 'is_indexed' => true],
	'type_relationship' => ['field_label' => 'Relationship Type', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true],
	'claim_evidence_note' => ['field_label' => 'Claim Evidence Note', 'type_data' => 'S'],
	
	'status_claim' => ['field_label' => 'Claim Status', 'type_field' => 'DDL', 'type_sql' => 'ENUM', 'is_mandatory' => true, 'is_indexed' => true],
	'reviewer_user_id' => ['field_label' => 'Reviewer User ID', 'type_data' => 'S', 'is_relational' => true, 'is_indexed' => true],
	'reviewed_at' => ['field_label' => 'Reviewed At', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
	'decision_note' => ['field_label' => 'Decision Note', 'type_data' => 'S'],
	
	'is_revoked' => ['field_label' => 'Is Revoked', 'type_data' => 'B', 'type_field' => 'CHK', 'type_sql' => 'BOOLEAN'],
	
	'created_at' => ['field_label' => 'Created At', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME', 'is_mandatory' => true, 'is_indexed' => true],
	'updated_at' => ['field_label' => 'Updated At', 'type_data' => 'S', 'type_field' => 'DTS', 'type_sql' => 'DATETIME'],
];

$claim_main_subfield_property = [];

$claim_main_index_list = [
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

$claim_main_boundary = [
    'owns' => [
        'the claim_main record fields and embedded structures documented in this file',
    ],
    'reference_field_list' => [
        'target_record_id',
        'claimant_user_id',
        'reviewer_user_id',
    ],
    'does_not_own' => [
        'records stored in referenced collections',
        'runtime authorization, migration, seeding, or deployment behavior',
    ],
];

return [
    'claim_main_default' => $claim_main_default,
    'claim_main' => $claim_main,
    'claim_main_field_order' => $claim_main_field_order,
    'claim_main_embedded_structure' => $claim_main_embedded_structure,
    'claim_main_field_property' => $claim_main_field_property,
    'claim_main_subfield_property' => $claim_main_subfield_property,
    'claim_main_index_list' => $claim_main_index_list,
    'claim_main_boundary' => $claim_main_boundary,
];
