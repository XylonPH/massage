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
            <a href="{{ route('workspace.profile.edit') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-ember-600 transition hover:text-ember-700 dark:text-ember-400 dark:hover:text-ember-300">
                {{ __('workspace.nav_profile') }} &rarr;
            </a>
        </section>

        <section aria-labelledby="ws-claim" class="rounded-2xl border border-leaf-200 bg-leaf-50 p-5 shadow-sm dark:border-leaf-800 dark:bg-leaf-950">
            <h2 id="ws-claim" class="font-black text-ink-950 dark:text-ink-50">{{ __('workspace.card_claim_title') }}</h2>
            <p class="mt-2 text-sm text-ink-700 dark:text-ink-200">{{ __('workspace.card_claim_text') }}</p>
            <a href="{{ route('workspace.contribution.establishment.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-leaf-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-leaf-700 dark:bg-leaf-700 dark:hover:bg-leaf-600">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4" aria-hidden="true">
                    <path d="M12 21c-2.5-1.5-4-4-4-7 0-2.5 1.5-5.5 4-8 2.5 2.5 4 5.5 4 8 0 3-1.5 5.5-4 7Z"/>
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                <span>Add a Spa</span>
            </a>
        </section>
    </div>

    {{-- Quick actions (Large Icon Cards) --}}
    <section aria-labelledby="ws-quick" class="mt-8">
        <h2 id="ws-quick" class="text-xl font-black text-ink-950 dark:text-ink-50">{{ __('workspace.quick_actions_title') }}</h2>
        <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">Fast paths to contribute listings, share knowledge, and manage your contributions.</p>

        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Card 1: Add a Spa --}}
            <a href="{{ route('workspace.contribution.establishment.create') }}" class="group relative flex flex-col justify-between rounded-2xl border border-ink-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-leaf-300 hover:shadow-md dark:border-ink-800 dark:bg-ink-900 dark:hover:border-leaf-700">
                <div>
                    <div class="inline-flex size-12 items-center justify-center rounded-xl bg-leaf-100 text-leaf-700 transition group-hover:scale-110 group-hover:bg-leaf-600 group-hover:text-white dark:bg-leaf-950/80 dark:text-leaf-300">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-6" aria-hidden="true">
                            <path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4M8 11h2m4 0h2"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-base font-black text-ink-950 transition group-hover:text-leaf-700 dark:text-ink-50 dark:group-hover:text-leaf-300">Add a Spa</h3>
                    <p class="mt-1 text-xs text-ink-500 dark:text-ink-400">Contribute a new spa or wellness establishment listing.</p>
                </div>
                <div class="mt-4 flex items-center gap-1 text-xs font-bold text-leaf-700 dark:text-leaf-300">
                    <span>Start contribution</span>
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-4 transition-transform group-hover:translate-x-1" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h9.69L10.22 6.03a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                </div>
            </a>

            {{-- Card 2: Write Article --}}
            <a href="{{ route('workspace.article.create') }}" class="group relative flex flex-col justify-between rounded-2xl border border-ink-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-ember-300 hover:shadow-md dark:border-ink-800 dark:bg-ink-900 dark:hover:border-ember-700">
                <div>
                    <div class="inline-flex size-12 items-center justify-center rounded-xl bg-ember-100 text-ember-700 transition group-hover:scale-110 group-hover:bg-ember-500 group-hover:text-white dark:bg-ember-950/80 dark:text-ember-300">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-6" aria-hidden="true">
                            <path d="M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-base font-black text-ink-950 transition group-hover:text-ember-600 dark:text-ink-50 dark:group-hover:text-ember-400">Write Article</h3>
                    <p class="mt-1 text-xs text-ink-500 dark:text-ink-400">Share wellness knowledge, tips, or educational guides.</p>
                </div>
                <div class="mt-4 flex items-center gap-1 text-xs font-bold text-ember-600 dark:text-ember-400">
                    <span>Create draft</span>
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-4 transition-transform group-hover:translate-x-1" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h9.69L10.22 6.03a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                </div>
            </a>

            {{-- Card 3: Add a Practitioner --}}
            <a href="{{ route('workspace.contribution.practitioner.create') }}" class="group relative flex flex-col justify-between rounded-2xl border border-ink-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-ink-300 hover:shadow-md dark:border-ink-800 dark:bg-ink-900 dark:hover:border-ink-600">
                <div>
                    <div class="inline-flex size-12 items-center justify-center rounded-xl bg-ink-100 text-ink-800 transition group-hover:scale-110 group-hover:bg-ink-900 group-hover:text-white dark:bg-ink-800 dark:text-ink-200">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-6" aria-hidden="true">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="8.5" cy="7" r="4"/>
                            <line x1="20" y1="8" x2="20" y2="14"/>
                            <line x1="17" y1="11" x2="23" y2="11"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-base font-black text-ink-950 transition group-hover:text-ink-800 dark:text-ink-50 dark:group-hover:text-white">Add a Practitioner</h3>
                    <p class="mt-1 text-xs text-ink-500 dark:text-ink-400">Contribute or claim a massage therapist profile.</p>
                </div>
                <div class="mt-4 flex items-center gap-1 text-xs font-bold text-ink-700 dark:text-ink-300">
                    <span>Submit profile</span>
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-4 transition-transform group-hover:translate-x-1" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h9.69L10.22 6.03a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                </div>
            </a>

            {{-- Card 4: Share a Review --}}
            <a href="{{ route('workspace.review.index') }}" class="group relative flex flex-col justify-between rounded-2xl border border-ink-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-amber-300 hover:shadow-md dark:border-ink-800 dark:bg-ink-900 dark:hover:border-amber-700">
                <div>
                    <div class="inline-flex size-12 items-center justify-center rounded-xl bg-amber-100 text-amber-800 transition group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white dark:bg-amber-950/80 dark:text-amber-300">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-6" aria-hidden="true">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-base font-black text-ink-950 transition group-hover:text-amber-700 dark:text-ink-50 dark:group-hover:text-amber-300">Share a Review</h3>
                    <p class="mt-1 text-xs text-ink-500 dark:text-ink-400">View written reviews and rate spa or therapist experiences.</p>
                </div>
                <div class="mt-4 flex items-center gap-1 text-xs font-bold text-amber-700 dark:text-amber-300">
                    <span>Manage reviews</span>
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-4 transition-transform group-hover:translate-x-1" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h9.69L10.22 6.03a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                </div>
            </a>
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
