@props(['active' => 'home'])
@inject('workspaceAccess', 'App\Support\Workspace\WorkspaceAccess')

@php
    $groups = [
        ['heading' => __('workspace.nav_personal'), 'items' => [
            ['key' => 'home', 'label' => __('workspace.nav_home'), 'route' => 'workspace.home'],
            ['key' => 'profile', 'label' => __('workspace.nav_profile'), 'route' => 'workspace.profile.edit'],
            ['key' => 'settings', 'label' => __('workspace.nav_settings'), 'route' => 'workspace.setting.edit'],
        ]],
        ['heading' => __('workspace.nav_activity'), 'items' => [
            ['key' => 'reviews', 'label' => __('workspace.nav_reviews'), 'route' => 'workspace.review.index'],
            ['key' => 'articles', 'label' => __('workspace.nav_articles'), 'route' => 'workspace.article.index'],
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

<nav aria-label="{{ __('workspace.title') }}" class="rounded-2xl border border-ink-100 bg-white p-4 shadow-sm">
    @foreach ($groups as $group)
        <h2 class="{{ $loop->first ? '' : 'mt-5' }} px-3 text-xs font-bold uppercase tracking-[0.14em] text-ink-400">{{ $group['heading'] }}</h2>
        <ul class="mt-1.5 space-y-0.5">
            @foreach ($group['items'] as $item)
                <li>
                    <a href="{{ isset($item['route']) ? route($item['route']) : url($item['url']) }}"
                       @if ($active === $item['key']) aria-current="page" @endif
                       class="block rounded-lg px-3 py-2 text-sm font-semibold transition {{ $active === $item['key'] ? 'bg-ink-950 text-white' : 'text-ink-700 hover:bg-ink-50 hover:text-ember-600' }}">
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endforeach
</nav>
