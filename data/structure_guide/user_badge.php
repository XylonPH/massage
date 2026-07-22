<?php
/**
 * Title: Massage Nexus User Badge Structure Guide
 * Version: 1.00
 * Collection: user_badge
 * Description: Stores one governed badge award to a user.
 * Purpose: Preserves award provenance, revocation, privacy, and featured-profile selection.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$user_badge_default = ['progress_value' => 0.0, 'is_public' => true, 'is_featured' => false, 'status_user_badge' => 'PRG', 'revision_number' => 1];
$user_badge = ['_id' => 'Ub7K2pQ9xR4tV8zN', 'user_id' => 'U2pR7vX4kT9mC5qL', 'badge_main_id' => 'Bd7K2pQ9xR4tV8zN', 'source_collection' => 'user_contribution', 'source_record_id' => 'Uc2pR7vX4kT9mC5q', 'award_reason' => 'First verified contribution.', 'progress_value' => 1.0, 'progress_target' => 1.0, 'eligibility_checked_at' => '2026-07-22T02:51:15Z', 'is_public' => true, 'is_featured' => true, 'featured_order' => 10, 'status_user_badge' => 'ACT', 'awarded_at' => '2026-07-22T02:51:15Z', 'expires_at' => null, 'revoked_at' => null, 'revocation_reason' => null, 'revision_number' => 1, 'created_at' => '2026-07-22T02:51:15Z', 'updated_at' => '2026-07-22T02:51:15Z'];
$user_badge_field_order = ['_id', 'user_id', 'badge_main_id', 'source_collection', 'source_record_id', 'award_reason', 'progress_value', 'progress_target', 'eligibility_checked_at', 'is_public', 'is_featured', 'featured_order', 'status_user_badge', 'awarded_at', 'expires_at', 'revoked_at', 'revocation_reason', 'revision_number', 'created_at', 'updated_at'];
$user_badge_embedded_structure = [];
$user_badge_field_property = [
    '_id' => ['field_label' => 'User Badge ID', 'field_description' => 'Canonical application-generated badge-award identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'user_id' => ['field_label' => 'User', 'field_description' => 'Award recipient user_main account.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'badge_main_id' => ['field_label' => 'Badge', 'field_description' => 'Governed badge_main definition.', 'type_data' => 'S', 'type_field' => 'REF', 'is_mandatory' => true, 'is_relational' => true, 'is_indexed' => true],
    'source_collection' => ['field_label' => 'Source Collection', 'field_description' => 'Allowlisted provenance collection for the award.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'source_record_id' => ['field_label' => 'Source Record', 'field_description' => 'Optional provenance record identifier.', 'type_data' => 'S', 'type_field' => 'REF', 'is_relational' => true],
    'award_reason' => ['field_label' => 'Award Reason', 'field_description' => 'Safe explanation for the award.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 500],
    'progress_value' => ['field_label' => 'Badge Progress Value', 'field_description' => 'Current governed progress value for a progressive badge.', 'type_data' => 'F', 'type_field' => 'NMB', 'min_number' => 0, 'default_value' => 0.0],
    'progress_target' => ['field_label' => 'Badge Progress Target', 'field_description' => 'Target value required for the current badge definition.', 'type_data' => 'F', 'type_field' => 'NMB', 'min_number' => 0],
    'eligibility_checked_at' => ['field_label' => 'Eligibility Checked At', 'field_description' => 'UTC time award or continued-public-eligibility conditions were last evaluated.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'is_public' => ['field_label' => 'Public Award', 'field_description' => 'Whether the user permits public display where the badge allows it.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => true],
    'is_featured' => ['field_label' => 'Featured Award', 'field_description' => 'Whether the user selected this award for the public featured set.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false],
    'featured_order' => ['field_label' => 'Featured Order', 'field_description' => 'Stable order within the maximum six featured badges.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 10, 'max_number' => 60],
    'status_user_badge' => ['field_label' => 'User Badge Status', 'field_description' => 'In-progress, active, hidden, disputed, expired, or revoked award state.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'PRG', 'is_mandatory' => true],
    'awarded_at' => ['field_label' => 'Awarded At', 'field_description' => 'UTC time progress became an award.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'expires_at' => ['field_label' => 'Expires At', 'field_description' => 'Optional UTC award expiry time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revoked_at' => ['field_label' => 'Revoked At', 'field_description' => 'UTC revocation time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revocation_reason' => ['field_label' => 'Revocation Reason', 'field_description' => 'Required governed explanation when an award is revoked.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 500],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest accepted update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$user_badge_subfield_property = [];
$user_badge_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'user_badge_unique', 'index_name' => 'uq_user_badge_user_badge', 'type_index' => 'CMP', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'user_id', 'type_index_mode' => 'ASC', 'sort_order' => 10], ['field_name' => 'badge_main_id', 'type_index_mode' => 'ASC', 'sort_order' => 20]], 'sort_order' => 20]];
$user_badge_boundary = ['owns' => ['badge award, visibility, feature choice, and revocation'], 'reference_field_list' => ['user_id', 'badge_main_id', 'source_record_id'], 'does_not_own' => ['badge definition', 'points', 'rank', 'workspace role']];
return ['user_badge_default' => $user_badge_default, 'user_badge' => $user_badge, 'user_badge_field_order' => $user_badge_field_order, 'user_badge_embedded_structure' => $user_badge_embedded_structure, 'user_badge_field_property' => $user_badge_field_property, 'user_badge_subfield_property' => $user_badge_subfield_property, 'user_badge_index_list' => $user_badge_index_list, 'user_badge_boundary' => $user_badge_boundary];
