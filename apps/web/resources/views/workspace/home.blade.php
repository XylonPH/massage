@extends('layouts.app')

@section('title', __('workspace.home_title'))

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[16rem_minmax(0,1fr)]">
        <aside class="min-w-0"><x-workspace-nav active="home" /></aside>

        <div class="min-w-0">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-ember-600">{{ __('workspace.workspace_overview') }}</p>
            <h1 class="mt-2 text-4xl font-black text-ink-950">{{ __('workspace.greeting', ['name' => $user->publicName()]) }}</h1>
            <p class="mt-2 max-w-2xl text-ink-600">{{ __('workspace.workspace_note') }}</p>

            <div class="mt-8 grid gap-5 sm:grid-cols-2">
                <section aria-labelledby="ws-account" class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm">
                    <h2 id="ws-account" class="font-black text-ink-950">{{ __('workspace.card_account_title') }}</h2>
                    <p class="mt-2 text-sm text-ink-700">{{ '@'.$user->username }}</p>
                    <p class="mt-1 text-sm text-ink-500">{{ $user->email }}</p>
                    <p class="mt-1 text-xs text-ink-400">{{ __('workspace.card_account_member_since', ['date' => $user->created_at?->format('M j, Y')]) }}</p>
                    <a href="{{ route('workspace.profile.edit') }}" class="mt-4 inline-block text-sm font-bold text-ember-600 transition hover:text-ember-700">{{ __('workspace.nav_profile') }} →</a>
                </section>

                <section aria-labelledby="ws-reviews" class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm">
                    <h2 id="ws-reviews" class="font-black text-ink-950">{{ __('workspace.card_reviews_title') }}</h2>
                    <p class="mt-2 text-sm text-ink-700">{{ trans_choice('workspace.card_reviews_text', $reviewCount, ['count' => $reviewCount]) }}</p>
                    <a href="{{ route('workspace.review.index') }}" class="mt-4 inline-block text-sm font-bold text-ember-600 transition hover:text-ember-700">{{ __('workspace.open') }} →</a>
                </section>

                <section aria-labelledby="ws-articles" class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm">
                    <h2 id="ws-articles" class="font-black text-ink-950">{{ __('workspace.card_articles_title') }}</h2>
                    <p class="mt-2 text-sm text-ink-700">{{ trans_choice('workspace.card_articles_text', $articleCount, ['count' => $articleCount]) }}</p>
                    <div class="mt-4 flex flex-wrap gap-x-4 gap-y-2 text-sm font-bold">
                        <a href="{{ route('workspace.article.index') }}" class="text-ember-600 transition hover:text-ember-700">{{ __('workspace.open') }} &rarr;</a>
                        <a href="{{ route('workspace.article.create') }}" class="text-ink-700 transition hover:text-ink-950">{{ __('article.new_article') }} +</a>
                    </div>
                </section>

                <section aria-labelledby="ws-claim" class="rounded-2xl border border-leaf-200 bg-leaf-50 p-5 shadow-sm">
                    <h2 id="ws-claim" class="font-black text-ink-950">{{ __('workspace.card_claim_title') }}</h2>
                    <p class="mt-2 text-sm text-ink-700">{{ __('workspace.card_claim_text') }}</p>
                    <a href="{{ route('workspace.contribution.establishment.create') }}" class="mt-4 inline-block text-sm font-bold text-leaf-700 transition hover:text-leaf-800">{{ __('workspace.card_claim_action') }} &rarr;</a>
                </section>
            </div>

            @if ($administrativeAreas !== [])
                <section aria-labelledby="ws-administration" class="mt-8">
                    <h2 id="ws-administration" class="text-2xl font-black text-ink-950">{{ __('workspace.administration_title') }}</h2>
                    <p class="mt-1 text-sm text-ink-600">{{ __('workspace.administration_intro') }}</p>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($administrativeAreas as $area)
                            <a href="{{ url($area['url']) }}" class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm transition hover:border-ember-200 hover:shadow-md">
                                <h3 class="font-black text-ink-950">{{ $area['title'] }}</h3>
                                <p class="mt-2 text-sm text-ink-600">{{ $area['description'] }}</p>
                                <span class="mt-4 inline-block text-sm font-bold text-ember-600">{{ __('workspace.open') }} &rarr;</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            <section aria-labelledby="ws-coming" class="mt-6 rounded-2xl border border-dashed border-ink-200 bg-ink-50/50 p-5">
                <h2 id="ws-coming" class="font-bold text-ink-700">{{ __('workspace.coming_soon_title') }}</h2>
                <p class="mt-1.5 text-sm text-ink-500">{{ __('workspace.coming_soon_text') }}</p>
            </section>
        </div>
    </div>
</div>
@endsection
