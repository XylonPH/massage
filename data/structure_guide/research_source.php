<?php
/**
 * Title: Massage Nexus Research Source Structure Guide
 * Version: 1.00
 * Collection: research_source
 * Description: Stores one reusable source identity and its provenance, reliability, access, and lifecycle metadata.
 * Purpose: Documents sources independently from observations so several assertions may cite the same thread, website, interview, visit, document, or media item.
 */

$created_at = '2026-07-21T08:15:45Z';
$updated_at = '2026-07-21T08:15:45Z';
$research_source_default = ['source_url' => null, 'source_author_snapshot' => null, 'status_source_reliability' => 'UNK', 'visibility_scope' => 'PRV', 'status_record_lifecycle' => 'ACT'];
$research_source = [
    '_id' => 'Sr7K2pQ9xR4tV8zN', // Canonical 16-character source identifier.
    'type_source' => 'WEB', // Controlled source medium or origin.
    'source_title' => 'Official Sample Wellness Spa website', // Human-readable source title.
    'source_url' => 'https://example.invalid/sample-wellness-spa', // Original source locator when lawful to retain.
    'source_external_id' => null, // Provider, thread, post, registry, or document identifier.
    'source_organization' => 'Sample Wellness Spa', // Organization responsible for the source.
    'source_author_snapshot' => null, // Author or account name as observed without creating identity.
    'source_published_at' => '2026-07-20T05:00:00Z', // When the source was published when known.
    'accessed_at' => '2026-07-21T06:00:00Z', // When Massage Nexus accessed the source.
    'language_original_id' => 3049, // Numeric common_reference.language_main identifier.
    'status_source_reliability' => 'OFF', // Reliability assessment, not truth of every assertion.
    'visibility_scope' => 'PRV', // Audience allowed to see source metadata.
    'retention_until' => null, // Optional retention or review deadline.
    'created_at' => $created_at, // UTC record creation time.
    'created_by_user_id' => 'U2pR7vX4kT9mC5qL', // Researcher or system actor adding the source.
    'updated_at' => $updated_at, // UTC record update time.
    'status_record_lifecycle' => 'ACT', // Database lifecycle state.
];
$research_source_field_order = ['_id', 'type_source', 'source_title', 'source_url', 'source_external_id', 'source_organization', 'source_author_snapshot', 'source_published_at', 'accessed_at', 'language_original_id', 'status_source_reliability', 'visibility_scope', 'retention_until', 'created_at', 'created_by_user_id', 'updated_at', 'status_record_lifecycle'];
$research_source_embedded_structure = [];
$research_source_field_property = [
    '_id' => ['field_label' => 'Research Source ID', 'field_description' => 'Canonical source identifier.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'type_source' => ['field_label' => 'Source Type', 'field_description' => 'Controlled source medium or origin.', 'type_data' => 'S', 'is_mandatory' => true, 'is_indexed' => true],
    'source_title' => ['field_label' => 'Source Title', 'field_description' => 'Human-readable source title.', 'type_data' => 'S', 'is_mandatory' => true],
    'source_url' => ['field_label' => 'Source URL', 'field_description' => 'Original lawful source locator.', 'type_data' => 'S', 'is_indexed' => true],
    'source_external_id' => ['field_label' => 'Source External ID', 'field_description' => 'External provider or publication identifier.', 'type_data' => 'S', 'is_indexed' => true],
    'source_organization' => ['field_label' => 'Source Organization', 'field_description' => 'Organization responsible for publication.', 'type_data' => 'S'],
    'source_author_snapshot' => ['field_label' => 'Source Author Snapshot', 'field_description' => 'Observed author or account label without identity inference.', 'type_data' => 'S'],
    'source_published_at' => ['field_label' => 'Source Published At', 'field_description' => 'Source publication time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'accessed_at' => ['field_label' => 'Accessed At', 'field_description' => 'UTC source access time.', 'type_data' => 'S', 'type_field' => 'DTS', 'is_mandatory' => true],
    'language_original_id' => ['field_label' => 'Original Language ID', 'field_description' => 'Numeric common_reference language identifier.', 'type_data' => 'I', 'is_relational' => true],
    'status_source_reliability' => ['field_label' => 'Source Reliability Status', 'field_description' => 'Source-level reliability assessment.', 'type_data' => 'S', 'is_indexed' => true],
    'visibility_scope' => ['field_label' => 'Visibility Scope', 'field_description' => 'Audience visibility rule.', 'type_data' => 'S', 'is_indexed' => true],
    'retention_until' => ['field_label' => 'Retention Until', 'field_description' => 'Optional retention or review deadline.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'created_at' => ['field_label' => 'Created At', 'field_description' => 'UTC record creation time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'created_by_user_id' => ['field_label' => 'Created By User ID', 'field_description' => 'Researcher or system user identifier.', 'type_data' => 'S', 'is_relational' => true],
    'updated_at' => ['field_label' => 'Updated At', 'field_description' => 'UTC record update time.', 'type_data' => 'S', 'type_field' => 'DTS'],
    'status_record_lifecycle' => ['field_label' => 'Record Lifecycle Status', 'field_description' => 'Database lifecycle state.', 'type_data' => 'S', 'is_indexed' => true],
];
$research_source_subfield_property = [];
$research_source_index_list = [
    ['index_key' => 'primary', 'index_name' => '_id_', 'type_index' => 'STD', 'is_unique' => true, 'is_sparse' => false, 'index_field_list' => [['field_name' => '_id', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 10],
    ['index_key' => 'source_url_lookup', 'index_name' => 'ix_research_source_url', 'type_index' => 'STD', 'is_unique' => false, 'is_sparse' => true, 'index_field_list' => [['field_name' => 'source_url', 'type_index_mode' => 'ASC', 'sort_order' => 10]], 'sort_order' => 20],
];
$research_source_boundary = ['owns' => ['source identity, locator, publication/access context, reliability, visibility, and retention metadata'], 'references' => ['common_reference.language_main', 'user_main'], 'does_not_own' => ['assertions extracted from the source', 'accepted target facts', 'large source captures or restricted documents']];
return compact('research_source_default', 'research_source', 'research_source_field_order', 'research_source_embedded_structure', 'research_source_field_property', 'research_source_subfield_property', 'research_source_index_list', 'research_source_boundary');
