<?php

/**
 * Massage Nexus Structure Guide
 * Collection: contribution_main
 * Version: 0.10
 *
 * Purpose: Stores a user's or staff member's proposal to add, correct,
 * translate, enrich, or update a supported record. The current application
 * slice accepts new-establishment proposals inside /workspace.
 *
 * A declared establishment relationship and a request for workspace access
 * remain claims for review. They never grant management authority directly.
 */

$contribution_main_field_order = [
    '_id',
    'type_contribution',
    'target_collection',
    'target_record_id',
    'submitted_by_user_id',
    'proposed_data',
    'type_establishment_relationship',
    'is_workspace_access_requested',
    'relationship_note',
    'status_contribution',
    'submitted_at',
    'reviewer_user_id',
    'reviewed_at',
    'decision_note',
    'created_at',
    'updated_at',
];

return [
    '_id' => ['type' => 'string', 'required' => true, 'description' => 'Canonical application-generated 16-character Base62 identifier.'],
    'type_contribution' => ['type' => 'string', 'required' => true, 'taxonomy' => 'workspace_access.json'],
    'target_collection' => ['type' => 'string', 'required' => true],
    'target_record_id' => ['type' => 'string', 'required' => false, 'description' => 'Existing target identifier for corrections and other target-bound proposals; omitted when adding a record.'],
    'submitted_by_user_id' => ['type' => 'string', 'required' => true, 'relationship' => 'user_main._id'],
    'proposed_data' => ['type' => 'object', 'required' => true, 'description' => 'Validated proposed fields using the owning target collection field contracts.'],
    'type_establishment_relationship' => ['type' => 'string', 'required' => false, 'taxonomy' => 'workspace_access.json', 'description' => 'Submitter declaration only; verification remains separate.'],
    'is_workspace_access_requested' => ['type' => 'boolean', 'required' => false, 'default' => false],
    'relationship_note' => ['type' => 'string', 'required' => false, 'maximum' => 1000],
    'status_contribution' => ['type' => 'string', 'required' => true, 'default' => 'DFT', 'taxonomy' => 'workspace_access.json'],
    'submitted_at' => ['type' => 'datetime', 'required' => false],
    'reviewer_user_id' => ['type' => 'string', 'required' => false, 'relationship' => 'user_main._id'],
    'reviewed_at' => ['type' => 'datetime', 'required' => false],
    'decision_note' => ['type' => 'string', 'required' => false, 'maximum' => 2000],
    'created_at' => ['type' => 'datetime'],
    'updated_at' => ['type' => 'datetime'],
];
