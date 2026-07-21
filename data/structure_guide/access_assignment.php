<?php

/**
 * Massage Nexus Structure Guide
 * Collection: access_assignment
 * Version: 0.10
 *
 * Purpose: Stores additive role and direct-permission assignments for one
 * user. An assignment may be global or scoped to an establishment,
 * practitioner, language, editorial area, or Massage Campus course.
 *
 * Access rules:
 * - Personal user capabilities do not require an assignment.
 * - Only ACT assignments inside their effective/expiry window grant access.
 * - A role is a permission bundle; permission_code_list may add narrower
 *   direct permissions.
 * - Establishment ownership or management is not stored here. Those factual
 *   relationships belong to establishment_user.
 */

$access_assignment_field_order = [
    '_id',
    'user_id',
    'role_workspace',
    'permission_code_list',
    'scope_access',
    'scope_record_id',
    'status_access_assignment',
    'effective_at',
    'expires_at',
    'assigned_by_user_id',
    'assignment_reason',
    'revoked_at',
    'created_at',
    'updated_at',
];

return [
    '_id' => ['type' => 'string', 'required' => true, 'description' => 'Canonical application-generated 16-character Base62 identifier.'],
    'user_id' => ['type' => 'string', 'required' => true, 'relationship' => 'user_main._id'],
    'role_workspace' => ['type' => 'string', 'required' => false, 'taxonomy' => 'workspace_access.json'],
    'permission_code_list' => [
        'type' => 'array',
        'required' => false,
        'description' => 'Stable application permission codes granted directly by this assignment. Permission behavior is defined in application authorization, not by taxonomy labels.',
        'item_type' => 'string',
    ],
    'scope_access' => ['type' => 'string', 'required' => true, 'default' => 'GBL', 'taxonomy' => 'workspace_access.json'],
    'scope_record_id' => [
        'type' => 'string',
        'required' => false,
        'description' => 'Required target identifier for a non-global scope; omitted for GBL.',
    ],
    'status_access_assignment' => ['type' => 'string', 'required' => true, 'default' => 'PND', 'taxonomy' => 'workspace_access.json'],
    'effective_at' => ['type' => 'datetime', 'required' => false],
    'expires_at' => ['type' => 'datetime', 'required' => false],
    'assigned_by_user_id' => ['type' => 'string', 'required' => true, 'relationship' => 'user_main._id'],
    'assignment_reason' => ['type' => 'string', 'required' => true, 'maximum' => 1000],
    'revoked_at' => ['type' => 'datetime', 'required' => false],
    'created_at' => ['type' => 'datetime'],
    'updated_at' => ['type' => 'datetime'],
];
