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
            'user.view',
            'user.manage',
            'user.access.manage',
            'user.session.manage',
        ],
        'EAD' => ['workspace.editorial.access', 'article.schedule'],
        'MOD' => ['workspace.moderation.access'],
        'PLT' => ['workspace.system.access', 'user.view', 'user.manage', 'user.access.manage', 'user.session.manage'],
        'UAD' => ['workspace.system.access', 'user.view', 'user.manage', 'user.access.manage', 'user.session.manage'],
        'SYS' => ['workspace.system.access', 'user.view', 'user.session.manage'],
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
            'title_key' => 'user.user_management',
            'description_key' => 'user.user_management_intro',
        ],
    ],
];
