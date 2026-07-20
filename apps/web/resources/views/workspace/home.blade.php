@extends('layouts.workspace', ['navActive' => 'home'])

@section('title', __('workspace.home_title'))
@section('page-title', __('workspace.greeting', ['name' => $user->publicName()]))
@section('page-context', __('workspace.workspace_note'))

@section('content')
<div class="mx-auto max-w-5xl">
    {{-- Stat row --}}
    <div class="grid gap-4 sm:grid-cols-3">
        @foreach ([
            ['label' => __('workspace.stat_reviews'), 'count' => $reviewCount, 'route' => route('workspace.review.index')],
            ['label' => __('workspace.stat_articles'), 'count' => $articleCount, 'route' => route('workspace.article.index')],
            ['label' => __('workspace.stat_contributions'), 'count' => $contributionCount, 'route' => route('workspace.contribution.index')],
        ] as $stat)
            <a href="{{ $stat['route'] }}" class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm transition hover:border-ember-200 hover:shadow-md dark:border-ink-800 dark:bg-ink-900 dark:hover:border-ember-800">
                <p class="text-3xl font-black text-ink-950 dark:text-ink-50">{{ $stat['count'] }}</p>
                <p class="mt-1 text-sm font-semibold text-ink-600 dark:text-ink-300">{{ $stat['label'] }}</p>
            </a>
        @endforeach
    </div>

    {{-- Account + claim cards --}}
    <div class="mt-6 grid gap-5 sm:grid-cols-2">
        <section aria-labelledby="ws-account" class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">
            <h2 id="ws-account" class="font-black text-ink-950 dark:text-ink-50">{{ __('workspace.card_account_title') }}</h2>
            <p class="mt-2 text-sm text-ink-700 dark:text-ink-200">{{ '@'.$user->username }}</p>
            <p class="mt-1 text-sm text-ink-500 dark:text-ink-400">{{ $user->email }}</p>
            <p class="mt-1 text-xs text-ink-400 dark:text-ink-300">{{ __('workspace.card_account_member_since', ['date' => $user->created_at?->format('M j, Y')]) }}</p>
            <a href="{{ route('workspace.profile.edit') }}" class="mt-4 inline-block text-sm font-bold text-ember-600 transition hover:text-ember-700 dark:text-ember-400 dark:hover:text-ember-300">{{ __('workspace.nav_profile') }} →</a>
        </section>

        <section aria-labelledby="ws-claim" class="rounded-2xl border border-leaf-200 bg-leaf-50 p-5 shadow-sm dark:border-leaf-800 dark:bg-leaf-950">
            <h2 id="ws-claim" class="font-black text-ink-950 dark:text-ink-50">{{ __('workspace.card_claim_title') }}</h2>
            <p class="mt-2 text-sm text-ink-700 dark:text-ink-200">{{ __('workspace.card_claim_text') }}</p>
            <a href="{{ route('workspace.contribution.establishment.create') }}" class="mt-4 inline-block text-sm font-bold text-leaf-700 transition hover:text-leaf-800 dark:text-leaf-300 dark:hover:text-leaf-200">{{ __('workspace.card_claim_action') }} &rarr;</a>
        </section>
    </div>

    {{-- Quick actions --}}
    <section aria-labelledby="ws-quick" class="mt-6 rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">
        <h2 id="ws-quick" class="font-black text-ink-950 dark:text-ink-50">{{ __('workspace.quick_actions_title') }}</h2>
        <div class="mt-3 flex flex-wrap gap-2.5">
            <a href="{{ route('workspace.article.create') }}" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('article.new_article') }}</a>
            <a href="{{ route('workspace.review.index') }}" class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('workspace.card_reviews_title') }}</a>
            <a href="{{ route('workspace.contribution.establishment.create') }}" class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('workspace.card_claim_action') }}</a>
        </div>
    </section>

    {{-- Administration (permission-gated) --}}
    @if ($administrativeAreas !== [])
        <section aria-labelledby="ws-administration" class="mt-8">
            <h2 id="ws-administration" class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ __('workspace.administration_title') }}</h2>
            <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">{{ __('workspace.administration_intro') }}</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($administrativeAreas as $area)
                    <a href="{{ url($area['url']) }}" class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm transition hover:border-ember-200 hover:shadow-md dark:border-ink-800 dark:bg-ink-900 dark:hover:border-ember-800">
                        <h3 class="font-black text-ink-950 dark:text-ink-50">{{ $area['title'] }}</h3>
                        <p class="mt-2 text-sm text-ink-600 dark:text-ink-300">{{ $area['description'] }}</p>
                        <span class="mt-4 inline-block text-sm font-bold text-ember-600 dark:text-ember-400">{{ __('workspace.open') }} &rarr;</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section aria-labelledby="ws-coming" class="mt-6 rounded-2xl border border-dashed border-ink-200 bg-ink-50/50 p-5 dark:border-ink-700 dark:bg-charcoal-950">
        <h2 id="ws-coming" class="font-bold text-ink-700 dark:text-ink-200">{{ __('workspace.coming_soon_title') }}</h2>
        <p class="mt-1.5 text-sm text-ink-500 dark:text-ink-400">{{ __('workspace.coming_soon_text') }}</p>
    </section>
</div>
@endsection
