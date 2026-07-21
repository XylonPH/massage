@props(['active' => 'home'])
@inject('workspaceAccess', 'App\Support\Workspace\WorkspaceAccess')

@php
    $icons = [
        'home' => 'M3 10.5 12 3l9 7.5M5 9.5V21h5v-6h4v6h5V9.5',
        'profile' => 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 8a7 7 0 0 1 14 0',
        'settings' => 'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm7.4-3a7.4 7.4 0 0 0-.1-1l2-1.6-2-3.4-2.4 1a7.4 7.4 0 0 0-1.7-1l-.4-2.6h-4l-.4 2.6a7.4 7.4 0 0 0-1.7 1l-2.4-1-2 3.4 2 1.6a7.4 7.4 0 0 0 0 2l-2 1.6 2 3.4 2.4-1a7.4 7.4 0 0 0 1.7 1l.4 2.6h4l.4-2.6a7.4 7.4 0 0 0 1.7-1l2.4 1 2-3.4-2-1.6c.1-.3.1-.7.1-1Z',
        'reviews' => 'M12 3l2.5 5.3 5.5.7-4 4 1 5.7-5-2.8-5 2.8 1-5.7-4-4 5.5-.7L12 3Z',
        'articles' => 'M6 3h9l4 4v14H6V3Zm9 0v4h4M9 11h7M9 15h7M9 7h3',
        'contributions' => 'M12 5v14m-7-7h14',
        'listing-spa' => 'M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6',
        'listing-therapist' => 'M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM4 21a8 8 0 0 1 16 0',
        'admin-editorial' => 'M4 20l4-1L20 7l-3-3L5 16l-1 4Zm11-14 3 3',
        'admin-moderation' => 'M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4Z',
        'admin-system' => 'M4 6h16M4 12h16M4 18h16M8 6v0m0 6v0m0 6v0',
    ];

    $groups = [
        ['heading' => __('workspace.nav_personal'), 'items' => [
            ['key' => 'home', 'label' => __('workspace.nav_home'), 'route' => 'workspace.home'],
            ['key' => 'profile', 'label' => __('workspace.nav_profile'), 'route' => 'workspace.profile.edit'],
            ['key' => 'settings', 'label' => __('workspace.nav_settings'), 'route' => 'workspace.setting.edit'],
        ]],
        ['heading' => __('workspace.nav_activity'), 'items' => [
            ['key' => 'articles', 'label' => __('workspace.nav_articles'), 'route' => 'workspace.article.index'],
            ['key' => 'reviews', 'label' => __('workspace.nav_reviews'), 'route' => 'workspace.review.index'],
            ['key' => 'contributions', 'label' => __('workspace.nav_contributions'), 'route' => 'workspace.contribution.index'],
        ]],
        ['heading' => __('workspace.nav_managed'), 'items' => [
            ['key' => 'listing-spa', 'label' => __('workspace.nav_listing_spa'), 'route' => 'workspace.listing.spa'],
            ['key' => 'listing-therapist', 'label' => __('workspace.nav_listing_therapist'), 'route' => 'workspace.listing.therapist'],
        ]],
    ];

    $administrativeItems = collect($workspaceAccess->administrativeAreas(auth()->user()))
        ->map(fn (array $area) => [
            'key' => 'admin-'.$area['key'],
            'label' => $area['title'],
            'url' => $area['url'],
        ])
        ->all();

    if ($administrativeItems !== []) {
        $groups[] = ['heading' => __('workspace.nav_administration'), 'items' => $administrativeItems];
    }
@endphp

<nav aria-label="{{ __('workspace.title') }}" {{ $attributes }}>
    @foreach ($groups as $group)
        <h2 class="{{ $loop->first ? '' : 'mt-5' }} px-3 text-xs font-bold uppercase tracking-[0.14em] text-ink-400">{{ $group['heading'] }}</h2>
        <ul class="mt-1.5 space-y-0.5">
            @foreach ($group['items'] as $item)
                <li>
                    <a href="{{ isset($item['route']) ? route($item['route']) : url($item['url']) }}"
                       @if ($active === $item['key']) aria-current="page" @endif
                       class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-semibold transition {{ $active === $item['key'] ? 'bg-ink-950 text-white dark:bg-ember-500 dark:text-white' : 'text-ink-700 hover:bg-ink-50 hover:text-ember-600 dark:text-ink-200 dark:hover:bg-ink-800 dark:hover:text-ember-400' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4.5 shrink-0" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$item['key']] ?? $icons['home'] }}"/></svg>
                        <span class="truncate">{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    @endforeach
</nav>
