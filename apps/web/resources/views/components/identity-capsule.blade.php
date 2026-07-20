@props(['user'])

@php
    $rewardSummary = method_exists($user, 'rewardSummary') ? $user->rewardSummary() : [];
    $rankLabel = data_get($rewardSummary, 'rank', __('auth.starting_rank'));
    $emberBalance = (int) data_get($rewardSummary, 'embers', 0);
    $inklingBalance = (int) data_get($rewardSummary, 'inklings', 0);
    $avatarUrl = method_exists($user, 'avatarUrl') ? $user->avatarUrl() : null;
    $displayName = $user->publicName();
@endphp

<a href="{{ route('workspace.profile.edit') }}"
   aria-label="{{ __('auth.open_profile_summary', ['name' => $displayName]) }}"
   {{ $attributes->merge(['class' => 'mn-identity-capsule group inline-flex min-w-[15rem] items-center gap-2.5 rounded-[1.4rem] border border-white/60 px-2 py-1.5 text-white shadow-sm transition hover:-translate-y-px hover:shadow-md focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember-500']) }}>
    <span class="relative flex size-10 shrink-0 items-center justify-center overflow-hidden rounded-full border-2 border-white/80 bg-white shadow-inner" aria-hidden="true">
        <img src="{{ $avatarUrl ?: asset('images/brand/logo-mark-color.png') }}" alt="" class="size-full object-cover">
        <span class="absolute inset-0 rounded-full ring-1 ring-inset ring-ink-950/10"></span>
    </span>

    <span class="min-w-0 flex-1 leading-none">
        <span class="block truncate text-sm font-black">{{ $displayName }}</span>
        <span class="mt-1 block truncate text-[0.65rem] font-semibold text-white/80">{{ $rankLabel }}</span>
    </span>

    <span class="flex shrink-0 items-center gap-1.5 pr-1 text-[0.65rem] font-bold" aria-label="{{ __('auth.reward_balance_summary', ['embers' => $emberBalance, 'inklings' => $inklingBalance]) }}">
        <span class="inline-flex items-center gap-0.5 rounded-full bg-white/15 px-1.5 py-1" title="{{ __('auth.embers') }}">
            <svg viewBox="0 0 20 20" fill="currentColor" class="size-3 text-ember-200" aria-hidden="true"><path d="M10.6 1.8c.5 2.7-1.4 3.8-2.6 5.4-1 1.3-1.4 2.6-.8 4.1.5-1.1 1.4-1.8 2.5-2.5-.2 1.8.9 2.7 1.8 3.7.8.9 1.1 1.8.9 2.8 1.5-1 2.5-2.7 2.5-4.6 0-3.5-2-6.8-4.3-8.9ZM7.1 11.6c-1.2 1-2 2.1-2 3.6 0 2 1.8 3.4 4.9 3.4 2.5 0 4.3-1 4.8-2.7-.8.6-1.8.9-2.9.9-2.5 0-4.5-1.7-4.8-5.2Z"/></svg>
            {{ number_format($emberBalance) }}
        </span>
        <span class="inline-flex items-center gap-0.5 rounded-full bg-white/15 px-1.5 py-1" title="{{ __('auth.inklings') }}">
            <svg viewBox="0 0 20 20" fill="currentColor" class="size-3 text-ink-100" aria-hidden="true"><path d="m10 1.7 1.5 5.1 5.1 1.5-5.1 1.5-1.5 5.1-1.5-5.1-5.1-1.5 5.1-1.5L10 1.7Zm5.1 10.5.7 2.2 2.2.7-2.2.7-.7 2.2-.7-2.2-2.2-.7 2.2-.7.7-2.2Z"/></svg>
            {{ number_format($inklingBalance) }}
        </span>
    </span>
</a>
