<?php
/**
 * Title: Massage Nexus Subscription Plan Structure Guide
 * Version: 1.00
 * Collection: subscription_plan
 * Description: Defines a purchasable personal or organization subscription plan.
 * Purpose: Separates commercial plan configuration from user authority, rank, and reputation.
 */
$created_at = '2026-07-22T02:51:15Z';
$updated_at = '2026-07-22T02:51:15Z';
$multilingual_text_sample = ['eng' => ['text' => 'Personal Premium', 'method_translation' => 'HUM', 'status_review' => 'APR']];
$subscription_plan_default = ['is_public' => false, 'status_subscription_plan' => 'DFT', 'revision_number' => 1];
$subscription_plan = ['_id' => 'Sp7K2pQ9xR4tV8zN', 'plan_code' => 'personal-premium-monthly', 'plan_name' => ['eng' => ['text' => 'Personal Premium', 'method_translation' => 'HUM', 'status_review' => 'APR']], 'type_subscription_audience' => 'USR', 'billing_interval' => 'MON', 'currency_code' => 'PHP', 'price_minor_unit' => 19900, 'entitlement_definition_list' => [['entitlement_code' => 'profile.premium_label', 'entitlement_limit' => null, 'interval_entitlement_reset' => 'NON']], 'is_public' => true, 'status_subscription_plan' => 'ACT', 'effective_at' => '2026-07-22T02:51:15Z', 'retired_at' => null, 'revision_number' => 1, 'created_at' => '2026-07-22T02:51:15Z', 'updated_at' => '2026-07-22T02:51:15Z'];
$subscription_plan_field_order = ['_id', 'plan_code', 'plan_name', 'type_subscription_audience', 'billing_interval', 'currency_code', 'price_minor_unit', 'entitlement_definition_list', 'is_public', 'status_subscription_plan', 'effective_at', 'retired_at', 'revision_number', 'created_at', 'updated_at'];
$subscription_plan_embedded_structure = ['entitlement_definition_list' => ['entitlement_code' => 'profile.premium_label', 'entitlement_limit' => null, 'interval_entitlement_reset' => 'NON']];
$subscription_plan_field_property = [
    '_id' => ['field_label' => 'Subscription Plan ID', 'field_description' => 'Canonical application-generated plan identifier.', 'type_data' => 'S', 'type_field' => 'HDN', 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'plan_code' => ['field_label' => 'Plan Code', 'field_description' => 'Stable machine-readable commercial plan code.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 3, 'max_character' => 80, 'is_mandatory' => true, 'is_indexed' => true, 'is_unique' => true],
    'plan_name' => ['field_label' => 'Plan Name', 'field_description' => 'Translatable public plan name.', 'type_data' => 'O', 'type_field' => 'JSE', 'is_translatable' => true, 'is_mandatory' => true],
    'type_subscription_audience' => ['field_label' => 'Subscription Audience Type', 'field_description' => 'Personal user, practitioner, establishment, or organization audience.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
    'billing_interval' => ['field_label' => 'Billing Interval', 'field_description' => 'Controlled commercial billing interval.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
    'currency_code' => ['field_label' => 'Currency Code', 'field_description' => 'ISO 4217 billing currency.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 3, 'max_character' => 3, 'is_mandatory' => true],
    'price_minor_unit' => ['field_label' => 'Price Minor Unit', 'field_description' => 'Price in the currency minor unit.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0, 'is_mandatory' => true],
    'entitlement_definition_list' => ['field_label' => 'Entitlement Definition List', 'field_description' => 'Stable capability codes and optional usage limits provisioned by this plan.', 'type_data' => 'A', 'type_field' => 'JSE'],
    'is_public' => ['field_label' => 'Public Plan', 'field_description' => 'Whether the plan may appear in public plan selection.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false],
    'status_subscription_plan' => ['field_label' => 'Subscription Plan Status', 'field_description' => 'Draft, active, paused, or retired plan lifecycle.', 'type_data' => 'S', 'type_field' => 'DDL', 'default_value' => 'DFT', 'is_mandatory' => true, 'is_indexed' => true],
    'effective_at' => ['field_label' => 'Effective At', 'field_description' => 'UTC earliest availability time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'retired_at' => ['field_label' => 'Retired At', 'field_description' => 'UTC plan retirement time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'revision_number' => ['field_label' => 'Revision Number', 'field_description' => 'Monotonic optimistic-concurrency token.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 1, 'default_value' => 1, 'is_mandatory' => true],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC latest accepted update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
];
$subscription_plan_subfield_property = [
    'plan_name.*.text' => ['field_label' => 'Plan Name Text', 'field_description' => 'Public plan name in one language.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 2, 'max_character' => 120, 'is_mandatory' => true],
    'plan_name.*.method_translation' => ['field_label' => 'Plan Name Translation Method', 'field_description' => 'Translation provenance for the plan name.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'plan_name.*.status_review' => ['field_label' => 'Plan Name Review Status', 'field_description' => 'Review state for the localized plan name.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'entitlement_definition_list.entitlement_code' => ['field_label' => 'Entitlement Code', 'field_description' => 'Stable commercial feature capability code.', 'type_data' => 'S', 'type_field' => 'TXT', 'min_character' => 3, 'max_character' => 120, 'is_mandatory' => true],
    'entitlement_definition_list.entitlement_limit' => ['field_label' => 'Entitlement Limit', 'field_description' => 'Optional maximum uses or units within each reset interval.', 'type_data' => 'I', 'type_field' => 'NMB', 'min_number' => 0],
    'entitlement_definition_list.interval_entitlement_reset' => ['field_label' => 'Entitlement Reset Interval', 'field_description' => 'No reset, daily, monthly, or annual usage-reset interval.', 'type_data' => 'S', 'type_field' => 'DDL', 'is_mandatory' => true],
];
$subscription_plan_index_list = [['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10], ['index_key' => 'plan_code_unique', 'index_name' => 'uq_subscription_plan_plan_code', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'plan_code', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 20], ['index_key' => 'status', 'index_name' => 'ix_subscription_plan_status', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => false, 'index_field_list' => [['field_name' => 'status_subscription_plan', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 30]];
$subscription_plan_boundary = ['owns' => ['commercial plan catalog and entitlement template'], 'reference_field_list' => [], 'does_not_own' => ['payment transaction', 'user authority', 'rank', 'reputation', 'active user entitlement']];
return ['multilingual_text_sample' => $multilingual_text_sample, 'subscription_plan_default' => $subscription_plan_default, 'subscription_plan' => $subscription_plan, 'subscription_plan_field_order' => $subscription_plan_field_order, 'subscription_plan_embedded_structure' => $subscription_plan_embedded_structure, 'subscription_plan_field_property' => $subscription_plan_field_property, 'subscription_plan_subfield_property' => $subscription_plan_subfield_property, 'subscription_plan_index_list' => $subscription_plan_index_list, 'subscription_plan_boundary' => $subscription_plan_boundary];
