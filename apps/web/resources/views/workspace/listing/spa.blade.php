@extends('layouts.workspace', ['navActive' => 'listing-spa'])

@section('title', __('workspace.listing_spa_title'))
@section('page-title', __('workspace.listing_spa_title'))
@section('page-context', __('workspace.listing_spa_intro'))

@section('content')
<div class="mx-auto max-w-5xl">
    <p class="text-sm font-bold uppercase tracking-[0.18em] text-ember-600 dark:text-ember-400">{{ __('workspace.nav_managed') }}</p>

    @if (count($establishments) === 0)
        <div class="mt-8 rounded-2xl border border-dashed border-ink-200 bg-ink-50/50 p-8 text-center dark:border-ink-700 dark:bg-charcoal-950">
            <h2 class="text-lg font-black text-ink-800 dark:text-ink-200">{{ __('workspace.listing_spa_empty_title') }}</h2>
            <p class="mx-auto mt-2 max-w-lg text-sm text-ink-500 dark:text-ink-400">{{ __('workspace.listing_spa_empty_text') }}</p>
            <a href="{{ route('workspace.contribution.establishment.create') }}" class="mt-5 inline-flex items-center justify-center rounded-xl bg-ember-500 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">
                {{ __('workspace.listing_spa_claim_action') }}
            </a>
            <p class="mt-3 text-xs text-ink-400 dark:text-ink-300">{{ __('workspace.claim_route_note') }}</p>
        </div>
    @else
        <div class="mt-8 grid gap-4 sm:grid-cols-2">
            @foreach ($establishments as $establishment)
                <article class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                    <h2 class="text-xl font-black text-ink-950 dark:text-ink-50">{{ data_get($establishment->display_name, 'eng', __('workspace.listing_spa_unnamed')) }}</h2>
                    @if ($establishment->address_public)
                        <p class="mt-2 text-sm text-ink-600 dark:text-ink-300">{{ is_array($establishment->address_public) ? data_get($establishment->address_public, 'eng') : $establishment->address_public }}</p>
                    @endif
                    <p class="mt-4 text-xs font-bold uppercase tracking-wider text-leaf-700 dark:text-leaf-300">{{ __('workspace.listing_spa_access_active') }}</p>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
