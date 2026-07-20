@extends('layouts.app')

@section('title', __('workspace.listing_spa_title'))

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[16rem_minmax(0,1fr)]">
        <aside class="min-w-0"><x-workspace-nav active="listing-spa" /></aside>

        <div class="min-w-0">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-ember-600">{{ __('workspace.nav_managed') }}</p>
            <h1 class="mt-2 text-4xl font-black text-ink-950">{{ __('workspace.listing_spa_title') }}</h1>
            <p class="mt-2 max-w-2xl text-ink-600">{{ __('workspace.listing_spa_intro') }}</p>

            @if (count($establishments) === 0)
                <div class="mt-8 rounded-2xl border border-dashed border-ink-200 bg-ink-50/50 p-8 text-center">
                    <h2 class="text-lg font-black text-ink-800">{{ __('workspace.listing_spa_empty_title') }}</h2>
                    <p class="mx-auto mt-2 max-w-lg text-sm text-ink-500">{{ __('workspace.listing_spa_empty_text') }}</p>
                    <a href="{{ route('workspace.contribution.establishment.create') }}" class="mt-5 inline-flex items-center justify-center rounded-xl bg-ember-500 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">
                        {{ __('workspace.listing_spa_claim_action') }}
                    </a>
                    <p class="mt-3 text-xs text-ink-400">{{ __('workspace.claim_route_note') }}</p>
                </div>
            @else
                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    @foreach ($establishments as $establishment)
                        <article class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm">
                            <h2 class="text-xl font-black text-ink-950">{{ data_get($establishment->display_name, 'eng', __('workspace.listing_spa_unnamed')) }}</h2>
                            @if ($establishment->address_public)
                                <p class="mt-2 text-sm text-ink-600">{{ is_array($establishment->address_public) ? data_get($establishment->address_public, 'eng') : $establishment->address_public }}</p>
                            @endif
                            <p class="mt-4 text-xs font-bold uppercase tracking-wider text-leaf-700">{{ __('workspace.listing_spa_access_active') }}</p>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
