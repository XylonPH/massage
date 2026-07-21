<?php
/**
 * Title: Massage Nexus Establishment User Relationship Structure Guide
 * Version: 0.20
 * Collection: establishment_user
 * Description: Stores one evidenced factual relationship between a user and an establishment.
 * Purpose: Documents the establishment_user record shape for review, validation, comparison, and implementation without granting workspace access or acting as runtime code.
 *
 * Notes:
 * - Multiple separately evidenced relationship records may exist for the same user and establishment.
 * - Operational permissions belong to access_assignment.
 */

$created_at = '2026-07-20T10:31:38Z';
$updated_at = '2026-07-21T04:24:17Z';
$establishment_user_default = [
    'status_establishment_relationship' => 'PND',
];

$establishment_user = [
    '_id' => 'Eu7K2pQ9xR4tV8zN', // Canonical 16-character relationship identifier.
    'establishment_id' => 'Es7K2pQ9xR4tV8zN', // Related establishment_main identifier.
    'user_id' => 'U2pR7vX4kT9mC5qL', // Related user_main identifier.
    'type_establishment_relationship' => 'MGR', // Factual relationship classification.
    'status_establishment_relationship' => 'VER', // Verification or lifecycle state of the relationship.
    'verified_by_user_id' => 'U9mC5qL2pR7vX4kT', // User that verified the evidence.
    'verified_at' => '2026-07-21T04:08:58Z', // UTC verification time.
    'ended_at' => null, // UTC time the factual relationship ended.
    'created_at' => '2026-07-21T04:08:58Z', // UTC record creation time.
    'updated_at' => '2026-07-21T04:08:58Z', // UTC record update time.
];

$establishment_user_field_order = [
    '_id',
    'establishment_id',
    'user_id',
    'type_establishment_relationship',
    'status_establishment_relationship',
    'verified_by_user_id',
    'verified_at',
    'ended_at',
    'created_at',
    'updated_at',
];

$establishment_user_embedded_structure = [];

$establishment_user_field_property = [
    '_id' => ['field_label' => 'Establishment User ID', 'field_description' => 'Canonical application-generated 16-character identifier.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'establishment_id' => ['field_label' => 'Establishment ID', 'field_description' => 'Related establishment_main._id.', 'type_data' => 'S', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
    'user_id' => ['field_label' => 'User ID', 'field_description' => 'Related user_main._id.', 'type_data' => 'S', 'is_relational' => true, 'is_mandatory' => true, 'is_indexed' => true],
    'type_establishment_relationship' => ['field_label' => 'Establishment Relationship Type', 'field_description' => 'Factual relationship classification.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'status_establishment_relationship' => ['field_label' => 'Establishment Relationship Status', 'field_description' => 'Verification and lifecycle state of the relationship.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'verified_by_user_id' => ['field_label' => 'Verified By User ID', 'field_description' => 'user_main._id of the verifier.', 'type_data' => 'S', 'is_relational' => true],
    'verified_at' => ['field_label' => 'Verified At', 'field_description' => 'UTC verification time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'ended_at' => ['field_label' => 'Ended At', 'field_description' => 'UTC time the factual relationship ended.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC record update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];

$establishment_user_subfield_property = [];

$establishment_user_index_list = [
    [
        'index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false,
        'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10,
    ],
    [
        'index_key' => 'establishment_user_lookup', 'index_name' => 'ix_establishment_user_establishment_user', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false,
        'index_field_list' => [
            ['field_name' => 'establishment_id', 'type_index_mode' => 'ASC', 'sort_order' => 10],
            ['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 20],
        ],
        'sort_order' => 20,
    ],
];

$establishment_user_boundary = [
    'owns' => ['factual user-to-establishment relationship type, evidence status, verification, and end time'],
    'references' => ['establishment_main through establishment_id', 'user_main through user_id and verified_by_user_id'],
    'does_not_own' => ['establishment profile', 'user account', 'workspace roles and permissions'],
];

return [
    'establishment_user_default' => $establishment_user_default,
    'establishment_user' => $establishment_user,
    'establishment_user_field_order' => $establishment_user_field_order,
    'establishment_user_embedded_structure' => $establishment_user_embedded_structure,
    'establishment_user_field_property' => $establishment_user_field_property,
    'establishment_user_subfield_property' => $establishment_user_subfield_property,
    'establishment_user_index_list' => $establishment_user_index_list,
    'establishment_user_boundary' => $establishment_user_boundary,
];
