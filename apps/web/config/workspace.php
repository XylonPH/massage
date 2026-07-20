<?php

return [
    'panel_permission_map' => [
        'editorial' => 'workspace.editorial.access',
        'moderation' => 'workspace.moderation.access',
        'system' => 'workspace.system.access',
    ],

    'role_permission_map' => [
        'FND' => [
            'workspace.editorial.access',
            'workspace.moderation.access',
            'workspace.system.access',
            'article.schedule',
        ],
        'EAD' => ['workspace.editorial.access', 'article.schedule'],
        'MOD' => ['workspace.moderation.access'],
        'SYS' => ['workspace.system.access'],
        'OPR' => ['establishment.manage'],
        'THP' => ['practitioner.manage'],
    ],

    'administrative_area_list' => [
        'editorial' => [
            'url' => '/workspace/editorial',
            'title_key' => 'workspace.admin_editorial_title',
            'description_key' => 'workspace.admin_editorial_text',
        ],
        'moderation' => [
            'url' => '/workspace/moderation',
            'title_key' => 'workspace.admin_moderation_title',
            'description_key' => 'workspace.admin_moderation_text',
        ],
        'system' => [
            'url' => '/workspace/system',
            'title_key' => 'workspace.admin_system_title',
            'description_key' => 'workspace.admin_system_text',
        ],
    ],
];
