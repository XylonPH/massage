<?php
/**
 * Title: Massage Nexus User Practitioner Structure Guide
 * Version: 1.00
 * Collection: user_practitioner
 * Description: Stores one verified current or historical Self identity link between a user account and practitioner profile.
 * Purpose: Distinguishes personal identity linkage and opt-in presentation reuse from scoped Steward access to another practitioner.
 */

$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_practitioner_default = ['type_user_practitioner_relationship' => 'SLF', 'profile_sync_choice' => [], 'status_user_practitioner' => 'PND', 'revision_number' => 1];
$user_practitioner = [
    '_id' => 'Ut7K2pQ9xR4tV8zN', // Canonical link identifier.
    'user_id' => 'U2pR7vX4kT9mC5qL', // Linked account.
    'practitioner_id' => 'P8rC3mL7xT1qV5nK', // Linked professional profile.
    'type_user_practitioner_relationship' => 'SLF', // Verified Self relationship only.
    'claim_id' => 'Cl7K2pQ9xR4tV8zN', // Originating approved claim.
    'record_verification_id_list' => ['Vf7K2pQ9xR4tV8zN'], // Identity verification references.
    'profile_sync_choice' => ['avatar' => true, 'cover' => false, 'display_name' => false, 'biography' => false, 'gender_presentation' => true, 'birthday_presentation' => false], // One-way presentation choices.
    'status_user_practitioner' => 'ACT', // Link lifecycle state.
    'linked_at' => '2026-07-22T02:51:15Z', // UTC activation time.
    'revoked_at' => null, // UTC revocation time.
    'revoked_by_user_id' => null, // Authorized revoking user.
    'revocation_reason' => null, // Revocation explanation.
    'revision_number' => 1, // Optimistic-concurrency token.
    'created_at' => '2026-07-22T02:51:15Z', // UTC record creation time.
    'updated_at' => '2026-07-22T02:51:15Z', // UTC latest update time.
];
$user_practitioner_field_order = ['_id', 'user_id', 'practitioner_id', 'type_user_practitioner_relationship', 'claim_id', 'record_verification_id_list', 'profile_sync_choice', 'status_user_practitioner', 'linked_at', 'revoked_at', 'revoked_by_user_id', 'revocation_reason', 'revision_number', 'created_at', 'updated_at'];
$user_practitioner_embedded_structure = ['profile_sync_choice' => ['avatar' => true, 'cover' => false, 'display_name' => false, 'biography' => false, 'gender_presentation' => true, 'birthday_presentation' => false]];
$user_practitioner_field_property = [
    '_id' => ['field_label' => 'User Practitioner ID', 'field_description' => 'Canonical application-generated 16-character identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'min_character' => 16, 'max_character' => 16, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'Verified user_main account in the Self relationship.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'practitioner_id' => ['field_label' => 'Practitioner', 'field_description' => 'Verified practitioner_main profile in the Self relationship.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'type_user_practitioner_relationship' => ['field_label' => 'User Practitioner Relationship', 'field_description' => 'Verified relationship type; current design permits Self only.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'SLF', 'is_mandatory' => true],
    'claim_id' => ['field_label' => 'Claim', 'field_description' => 'Approved claim_main that originated the link.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'record_verification_id_list' => ['field_label' => 'Verification Records', 'field_description' => 'Identity and relationship verification references.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true],
    'profile_sync_choice' => ['field_label' => 'Profile Reuse Choices', 'field_description' => 'Bounded one-way public presentation reuse choices.', 'type_data' => 'O', 'type_field' => 'JSE', 'default_value' => []],
    'status_user_practitioner' => ['field_label' => 'User Practitioner Status', 'field_description' => 'Current verified link lifecycle state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'PND', 'is_mandatory' => true, 'is_indexed' => true],
    'linked_at' => ['field_label' => 'Linked At', 'field_description' => 'UTC time the verified link became active.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revoked_at' => ['field_label' => 'Revoked At', 'field_description' => 'UTC time the link was revoked.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revoked_by_user_id' => ['field_label' => 'Revoked By User', 'field_description' => 'Authorized user_main account that revoked the link.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'revocation_reason' => ['field_label' => 'Revocation Reason', 'field_description' => 'Required explanation when the link is revoked.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 1000],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest accepted update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$user_practitioner_subfield_property = [
    'profile_sync_choice.avatar' => ['field_label' => 'Reuse Avatar', 'field_description' => 'Whether the professional profile may reuse the current account avatar reference.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'profile_sync_choice.cover' => ['field_label' => 'Reuse Cover', 'field_description' => 'Whether the professional profile may reuse the current account cover reference.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'profile_sync_choice.display_name' => ['field_label' => 'Reuse Display Name', 'field_description' => 'Whether approved account display-name changes may feed professional review.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'profile_sync_choice.biography' => ['field_label' => 'Reuse Biography', 'field_description' => 'Whether approved account biography changes may feed professional review.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'profile_sync_choice.gender_presentation' => ['field_label' => 'Reuse Gender Presentation', 'field_description' => 'Whether eligible public gender presentation may resolve from user_main.', 'type_data' => 'B', 'type_field' => 'CHK'],
    'profile_sync_choice.birthday_presentation' => ['field_label' => 'Reuse Birthday Presentation', 'field_description' => 'Whether eligible derived birthday presentation may resolve from user_main.', 'type_data' => 'B', 'type_field' => 'CHK'],
];
$user_practitioner_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'user_status', 'index_name' => 'ix_user_practitioner_user_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_user_practitioner', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20],
    ['index_key' => 'practitioner_status', 'index_name' => 'ix_user_practitioner_practitioner_status', 'type_index' => 'CMP', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'practitioner_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'status_user_practitioner', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 30],
];
$user_practitioner_boundary = ['owns' => ['verified Self link, origin references, lifecycle, and bounded presentation-reuse choices'], 'reference_field_list' => ['user_id', 'practitioner_id', 'claim_id', 'record_verification_id_list', 'revoked_by_user_id'], 'does_not_own' => ['professional profile facts', 'private user identity', 'Steward or manager access', 'claim or verification workflow']];
return ['user_practitioner_default' => $user_practitioner_default, 'user_practitioner' => $user_practitioner, 'user_practitioner_field_order' => $user_practitioner_field_order, 'user_practitioner_embedded_structure' => $user_practitioner_embedded_structure, 'user_practitioner_field_property' => $user_practitioner_field_property, 'user_practitioner_subfield_property' => $user_practitioner_subfield_property, 'user_practitioner_index_list' => $user_practitioner_index_list, 'user_practitioner_boundary' => $user_practitioner_boundary];
