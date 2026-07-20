<?php

/**
 * Massage Nexus Structure Guide
 * Collection: establishment_user
 * Version: 0.10
 *
 * Purpose: Stores one factual relationship between a user and an
 * establishment. Multiple users may relate to one establishment, one user may
 * relate to several establishments, and one user may hold several separately
 * evidenced relationship records for the same establishment.
 *
 * This collection does not grant workspace access. Operational permissions
 * belong to access_assignment so ownership, investment, management, and
 * application access cannot be mistaken for one another.
 */

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

return [
    '_id' => ['type' => 'string', 'required' => true, 'description' => 'Canonical application-generated 16-character Base62 identifier.'],
    'establishment_id' => ['type' => 'string', 'required' => true, 'relationship' => 'establishment_main._id'],
    'user_id' => ['type' => 'string', 'required' => true, 'relationship' => 'user_main._id'],
    'type_establishment_relationship' => ['type' => 'string', 'required' => true, 'taxonomy' => 'workspace_access.json'],
    'status_establishment_relationship' => ['type' => 'string', 'required' => true, 'default' => 'PND', 'taxonomy' => 'workspace_access.json'],
    'verified_by_user_id' => ['type' => 'string', 'required' => false, 'relationship' => 'user_main._id'],
    'verified_at' => ['type' => 'datetime', 'required' => false],
    'ended_at' => ['type' => 'datetime', 'required' => false],
    'created_at' => ['type' => 'datetime'],
    'updated_at' => ['type' => 'datetime'],
];
