<?php

/**
 * Massage Nexus Structure Guide
 * Collection: service_main
 * 
 * Purpose: Defines the normalized reference page for one reusable service concept, 
 * independently of any one establishment or therapist.
 * 
 * Reference: docs/05-directory/service-profile.txt
 */

return [
    '_id' => [
        'type' => 'string',
        'description' => 'Application-generated 16-character Base62 identifier.',
        'required' => true,
    ],
    'service_slug' => [
        'type' => 'string',
        'description' => 'Unique URL-friendly identifier for the public service profile.',
        'required' => true,
    ],
    'service_name' => [
        'type' => 'array',
        'description' => 'Preferred service name translated by language code.',
        'required' => true,
        'example' => [
            'eng' => 'Thai Massage'
        ]
    ],
    'short_description' => [
        'type' => 'array',
        'description' => 'A concise description or tagline for the service.',
        'required' => false,
    ],
    'service_description_overview' => [
        'type' => 'array',
        'description' => 'Extended description, purpose, setting, and common session structure.',
        'required' => false,
    ],
    'group_service_sector' => [
        'type' => 'string',
        'description' => 'Top-level hierarchy, e.g., "Health, Wellness and Care".',
        'required' => false,
    ],
    'group_service_domain' => [
        'type' => 'string',
        'description' => 'Second-level hierarchy, e.g., "Spa and Wellness".',
        'required' => false,
    ],
    'group_service_family' => [
        'type' => 'string',
        'description' => 'Primary family classification, e.g., "Massage".',
        'required' => true,
    ],
    'status_record_lifecycle' => [
        'type' => 'string',
        'description' => 'Lifecycle status: ACT (Active), INA (Inactive), ARC (Archived), DEL (Deleted).',
        'required' => true,
        'default' => 'ACT'
    ],
    'created_at' => [
        'type' => 'datetime',
        'description' => 'Record creation timestamp.',
    ],
    'updated_at' => [
        'type' => 'datetime',
        'description' => 'Record last update timestamp.',
    ],
];
