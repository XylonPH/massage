<?php

/**
 * Massage Nexus Structure Guide
 * Collection: establishment_main
 * Version: 0.10
 *
 * Purpose: Defines one establishment or supported provider profile. Location
 * and contact values are bounded public-profile data. Private residential
 * addresses and personal contact channels must not be copied here.
 *
 * References:
 * - docs/04-architecture/database-structure.txt section 7
 * - docs/05-directory/spa-profile.txt section 9
 * - data/taxonomy/shared/place_and_address.json
 * - data/taxonomy/shared/person_identity_and_contact.json
 *
 * Current implementation boundary:
 * - The public Spa page still uses synthetic demo records until the separate
 *   persistent-establishment checklist work is completed.
 * - email and contact_number are transitional single-value fields in the
 *   current administration form. New public channels use contact_channel_list
 *   so telephone, messaging, website, and social links share one contract.
 */

$establishment_main_field_order = [
    '_id',
    'establishment_slug',
    'display_name',
    'short_description',
    'type_spa',
    'level_spa_market',
    'type_physical_setting',
    'mode_service_delivery',
    'mode_access',
    'type_establishment_operation',
    'status_establishment',
    'type_client_access',
    'target_client_focus',
    'address_public',
    'coordinate_latitude',
    'coordinate_longitude',
    'direction_note',
    'parking_note',
    'landmark_list',
    'contact_channel_list',
    'status_record_lifecycle',
    'created_at',
    'updated_at',
    'last_confirmed_at',
];

$establishment_landmark_item_field_order = [
    'landmark_name',
    'walking_duration_minute',
    'direction_note',
];

$establishment_contact_channel_item_field_order = [
    'type_contact_channel',
    'type_contact_number',
    'contact_label',
    'contact_value',
    'contact_url',
    'status_contact_channel',
    'last_confirmed_at',
];

return [
    '_id' => [
        'type' => 'string',
        'description' => 'Canonical application-generated 16-character Base62 identifier.',
        'required' => true,
    ],
    'establishment_slug' => [
        'type' => 'string',
        'description' => 'Unique readable route value, separate from the canonical identifier and display name.',
        'required' => false,
    ],
    'display_name' => [
        'type' => 'array',
        'description' => 'Approved public operating name keyed by language code.',
        'required' => true,
    ],
    'short_description' => [
        'type' => 'array',
        'description' => 'Concise public description keyed by language code.',
        'required' => false,
    ],
    'type_spa' => ['type' => 'string', 'required' => true],
    'level_spa_market' => ['type' => 'string', 'required' => false],
    'type_physical_setting' => ['type' => 'string', 'required' => false],
    'mode_service_delivery' => ['type' => 'array', 'required' => false],
    'mode_access' => ['type' => 'string', 'required' => false],
    'type_establishment_operation' => ['type' => 'string', 'required' => false],
    'status_establishment' => ['type' => 'string', 'required' => true],
    'type_client_access' => ['type' => 'string', 'required' => false],
    'target_client_focus' => ['type' => 'array', 'required' => false],

    'address_public' => [
        'type' => 'string',
        'description' => 'Approved public destination address. Use an approximate area instead when exact-address disclosure is unsafe or not permitted.',
        'required' => false,
    ],
    'coordinate_latitude' => [
        'type' => 'float',
        'description' => 'Latitude of the usable public entrance or approved privacy-reduced map point.',
        'minimum' => -90,
        'maximum' => 90,
        'required' => false,
    ],
    'coordinate_longitude' => [
        'type' => 'float',
        'description' => 'Longitude of the usable public entrance or approved privacy-reduced map point.',
        'minimum' => -180,
        'maximum' => 180,
        'required' => false,
    ],
    'direction_note' => [
        'type' => 'array',
        'description' => 'Multilingual entrance, building, unit, transit, or approach directions.',
        'required' => false,
    ],
    'parking_note' => [
        'type' => 'array',
        'description' => 'Multilingual parking availability, access, validation, fee, and restriction information.',
        'required' => false,
    ],
    'landmark_list' => [
        'type' => 'array',
        'description' => 'Bounded nearby public landmarks useful for finding this establishment.',
        'required' => false,
        'item' => [
            'landmark_name' => ['type' => 'string', 'required' => true],
            'walking_duration_minute' => ['type' => 'integer', 'minimum' => 0, 'required' => false],
            'direction_note' => ['type' => 'array', 'required' => false],
        ],
    ],
    'contact_channel_list' => [
        'type' => 'array',
        'description' => 'Approved public business contact channels. Never contains private personal channels.',
        'required' => false,
        'item' => [
            'type_contact_channel' => [
                'type' => 'string',
                'description' => 'Controlled channel type from person_identity_and_contact.json, such as PHN, MSG, WEB, or SOC.',
                'required' => true,
            ],
            'type_contact_number' => [
                'type' => 'string',
                'description' => 'Optional controlled telephone subtype for PHN channels.',
                'required' => false,
            ],
            'contact_label' => ['type' => 'string', 'required' => true],
            'contact_value' => ['type' => 'string', 'required' => true],
            'contact_url' => [
                'type' => 'string',
                'description' => 'Normalized http, https, tel, mailto, or sms action URL.',
                'required' => true,
            ],
            'status_contact_channel' => [
                'type' => 'string',
                'description' => 'Controlled channel status from person_identity_and_contact.json.',
                'required' => false,
            ],
            'last_confirmed_at' => ['type' => 'datetime', 'required' => false],
        ],
    ],

    'status_record_lifecycle' => [
        'type' => 'string',
        'description' => 'Database record lifecycle, separate from real-world establishment status.',
        'required' => true,
        'default' => 'ACT',
    ],
    'created_at' => ['type' => 'datetime'],
    'updated_at' => ['type' => 'datetime'],
    'last_confirmed_at' => ['type' => 'datetime'],
];
