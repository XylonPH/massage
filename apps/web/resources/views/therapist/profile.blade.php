@extends('layouts.app')

@section('title', $therapist['name'])
@section('meta_description', $therapist['headline'])

@section('content')

{{-- ===================== Identity header ===================== --}}
<section class="relative overflow-hidden bg-ink-950 text-white">
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="absolute -right-24 -top-24 size-96 rounded-full bg-leaf-500/15 blur-3xl"></div>
        <div class="absolute -bottom-32 left-1/3 size-80 rounded-full bg-ember-500/10 blur-3xl"></div>
        <svg class="absolute -right-6 bottom-0 h-64 w-64 text-white/[0.04]" viewBox="0 0 24 24" fill="currentColor"><path d="M6 15C6 8 11 4 19 4c0 8-4 13-11 13-1 0-2-.5-2-2Z"/></svg>
    </div>

    <div class="relative mx-auto max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8">
        <nav aria-label="Breadcrumb" class="text-xs text-ink-300">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li><a href="{{ route('home') }}" class="transition hover:text-white">{{ __('therapist.breadcrumb_home') }}</a></li>
                <li aria-hidden="true">›</li>
                <li><a href="{{ url('/directory/therapist') }}" class="transition hover:text-white">{{ __('therapist.breadcrumb_therapists') }}</a></li>
                <li aria-hidden="true">›</li>
                <li aria-current="page" class="font-semibold text-white">{{ $therapist['name'] }}</li>
            </ol>
        </nav>

        <div class="mt-6 flex flex-col gap-8 lg:flex-row lg:items-start">
            <span class="flex size-24 shrink-0 items-center justify-center rounded-full text-3xl font-black sm:size-28 {{ $therapist['tone'] }}" aria-hidden="true">{{ $therapist['initials'] }}</span>

            <div class="min-w-0 flex-1">
                {{-- Separate claim / identity / booking labels, never one blanket badge --}}
                <div class="flex flex-wrap items-center gap-2">
                    @if ($therapist['is_claimed'])
                        <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-wide text-ink-200 ring-1 ring-white/20">{{ __('therapist.claimed_profile') }}</span>
                    @else
                        <span class="rounded-full bg-ember-500/20 px-3 py-1 text-xs font-bold uppercase tracking-wide text-ember-300 ring-1 ring-ember-400/40">{{ __('therapist.unclaimed_profile') }}</span>
                    @endif
                    @if ($therapist['is_identity_verified'])
                        <span class="inline-flex items-center gap-1 rounded-full bg-leaf-500/20 px-3 py-1 text-xs font-bold uppercase tracking-wide text-leaf-300 ring-1 ring-leaf-400/40">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="size-3.5" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.7-9.3a1 1 0 0 0-1.4-1.4L9 10.6 7.7 9.3a1 1 0 0 0-1.4 1.4l2 2a1 1 0 0 0 1.4 0l4-4Z" clip-rule="evenodd"/></svg>
                            {{ __('therapist.identity_verified') }}
                        </span>
                    @endif
                    <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-wide {{ $therapist['is_booking_enabled'] ? 'text-leaf-300' : 'text-ink-300' }} ring-1 ring-white/20">
                        {{ $therapist['is_booking_enabled'] ? __('therapist.booking_enabled') : __('therapist.booking_not_enabled') }}
                    </span>
                </div>

                <h1 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">{{ $therapist['name'] }}</h1>
                <p class="mt-1.5 text-ink-200">{{ $therapist['headline'] }}</p>
                <p class="mt-1 text-sm text-ink-300">{{ $therapist['area_summary'] }}</p>

                <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm">
                    {{-- Official rating, rating count, and review count as separate facts --}}
                    @if ($therapist['rating'] !== null)
                        <span class="flex items-center gap-1.5">
                            <span class="text-lg font-black text-ember-400">{{ number_format($therapist['rating'], 1) }}</span>
                            <x-rating :value="$therapist['rating']" />
                            <span class="text-ink-300">{{ trans_choice('therapist.rating_count', $therapist['rating_count'], ['count' => $therapist['rating_count']]) }} · {{ trans_choice('therapist.review_count', $therapist['review_count'], ['count' => $therapist['review_count']]) }}</span>
                        </span>
                    @else
                        <span class="text-ink-300">{{ __('therapist.not_enough_ratings') }}</span>
                    @endif
                    <span aria-hidden="true" class="hidden text-ink-600 sm:inline">|</span>
                    <span class="font-semibold {{ $therapist['availability'] === 'available' ? 'text-leaf-400' : 'text-ink-300' }}">
                        {{ __('therapist.availability_'.($therapist['availability'] === 'existing_clients' ? 'existing' : $therapist['availability'])) }}
                    </span>
                    <span class="text-ink-300">{{ __('therapist.practice_status_'.$therapist['practice_status']) }}</span>
                </div>

                @if ($therapist['starting_price'] !== null)
                    <p class="mt-3 text-sm">
                        <span class="font-bold text-white">{{ __('therapist.starting_price', ['price' => $therapist['starting_price'], 'minutes' => $therapist['starting_price_duration']]) }}</span>
                        <span class="text-ink-300"> · {{ __('therapist.price_context', ['context' => $therapist['starting_price_context']]) }}</span>
                    </p>
                    @if (count($therapist['affiliations']) > 1)
                        <p class="mt-1 text-xs text-ink-400">{{ __('therapist.price_varies') }}</p>
                    @endif
                @endif
            </div>

            {{-- Gallery preview --}}
            <div class="grid w-full shrink-0 grid-cols-3 gap-2 lg:w-72 lg:grid-cols-2" aria-label="{{ __('therapist.photo_gallery') }}">
                @if ($therapist['photo_count'] > 0)
                    <div class="flex h-24 items-center justify-center rounded-xl bg-gradient-to-br from-leaf-800 to-ink-900 lg:col-span-2 lg:h-28"><x-pictogram name="hands" class="size-8 text-white/40" /></div>
                    <div class="flex h-24 items-center justify-center rounded-xl bg-gradient-to-br from-ember-800 to-charcoal-950 lg:h-20"><x-pictogram name="aroma" class="size-7 text-white/40" /></div>
                    <div class="relative flex h-24 items-center justify-center rounded-xl bg-gradient-to-br from-ink-700 to-ink-950 lg:h-20">
                        <x-pictogram name="leaf" class="size-7 text-white/40" />
                        <a href="#gallery" class="absolute inset-0 flex items-center justify-center rounded-xl bg-ink-950/60 text-xs font-bold text-white transition hover:bg-ink-950/40">
                            {{ __('therapist.view_all_photos', ['count' => $therapist['photo_count']]) }}
                        </a>
                    </div>
                @else
                    <div class="col-span-3 flex h-24 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-xs font-semibold text-ink-400 lg:col-span-2 lg:h-28">{{ __('therapist.no_photos') }}</div>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-7 flex flex-wrap items-center gap-2.5">
            @if ($therapist['is_booking_enabled'])
                <a href="#book" class="inline-flex items-center gap-2 rounded-xl bg-ember-500 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M5 5h14a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/></svg>
                    {{ __('common.book_now') }}
                </a>
            @endif
            @foreach ([
                ['label' => __('therapist.request_availability'), 'icon' => 'M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                ['label' => __('common.contact'), 'icon' => 'M3 7.5A1.5 1.5 0 0 1 4.5 6h2.6a1 1 0 0 1 1 .76l.7 2.8a1 1 0 0 1-.5 1.12l-1.6.94a11.6 11.6 0 0 0 5.7 5.7l.94-1.6a1 1 0 0 1 1.1-.5l2.8.7a1 1 0 0 1 .76 1v2.58a1.5 1.5 0 0 1-1.5 1.5C9.7 21 3 14.3 3 7.5Z'],
                ['label' => __('common.save'), 'icon' => 'M12 20s-7.5-4.7-9.4-9A5.3 5.3 0 0 1 12 6.6 5.3 5.3 0 0 1 21.4 11c-1.9 4.3-9.4 9-9.4 9Z'],
                ['label' => __('common.follow'), 'icon' => 'M12 5v14m-7-7h14'],
                ['label' => __('common.share'), 'icon' => 'M7.5 13.5 15 18m0-12-7.5 4.5M18 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm0 13a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm-11-6.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z'],
                ['label' => __('common.compare'), 'icon' => 'M9 3v18m6-18v18M3 9h18M3 15h18'],
            ] as $action)
                <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-white/20 px-4 py-2.5 text-sm font-semibold text-ink-100 transition hover:bg-white/10">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $action['icon'] }}"/></svg>
                    {{ $action['label'] }}
                </button>
            @endforeach
            <span class="ml-auto flex items-center gap-3">
                <button type="button" class="inline-flex items-center gap-1.5 text-xs font-semibold text-ink-400 transition hover:text-ink-200">{{ __('common.suggest_correction') }}</button>
                <button type="button" class="inline-flex items-center gap-1.5 text-xs font-semibold text-ink-400 transition hover:text-ink-200">{{ __('common.report') }}</button>
            </span>
        </div>
    </div>
</section>

{{-- ===================== Tabs ===================== --}}
<nav class="sticky top-16 z-30 border-b border-ink-100 bg-white/95 shadow-sm backdrop-blur dark:border-ink-800 dark:bg-ink-900/95" aria-label="{{ $therapist['name'] }}">
    <div class="mx-auto max-w-[1600px] overflow-x-auto px-4 sm:px-6 lg:px-8">
        <ul class="flex gap-1 whitespace-nowrap py-1">
            @foreach ([
                ['href' => '#overview', 'label' => __('therapist.tab_overview')],
                ['href' => '#services', 'label' => __('therapist.tab_services')],
                ['href' => '#availability', 'label' => __('therapist.tab_availability')],
                ['href' => '#affiliations', 'label' => __('therapist.tab_affiliations')],
                ['href' => '#credentials', 'label' => __('therapist.tab_credentials')],
                ['href' => '#reviews', 'label' => __('therapist.tab_reviews')],
                ['href' => '#about', 'label' => __('therapist.tab_about')],
            ] as $tab)
                <li><a href="{{ $tab['href'] }}" class="inline-block rounded-lg px-3.5 py-2.5 text-sm font-semibold text-ink-600 transition hover:bg-ink-50 hover:text-ember-600 dark:text-ink-300 dark:hover:bg-ink-800 dark:hover:text-ember-400">{{ $tab['label'] }}</a></li>
            @endforeach
        </ul>
    </div>
</nav>

{{-- ===================== Body ===================== --}}
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_21rem]">
        <div class="min-w-0 space-y-9">

            {{-- Overview --}}
            <section id="overview" aria-labelledby="overview-heading" class="scroll-mt-32">
                <x-section-heading id="overview-heading" :title="__('therapist.about_title', ['name' => $therapist['name']])" />
                <div class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                    <p class="leading-relaxed text-ink-700 dark:text-ink-200">{{ $therapist['about'] }}</p>
                    @if ($therapist['experience_note'])
                        <p class="mt-4 border-t border-ink-100 pt-4 text-sm text-ink-500 dark:border-ink-800 dark:text-ink-400">{{ $therapist['experience_note'] }}</p>
                    @endif
                </div>
            </section>

            {{-- Specialties & Capabilities --}}
            <section aria-labelledby="specialties-heading" class="scroll-mt-32">
                <x-section-heading id="specialties-heading" :title="__('therapist.specialties_title')" accent="leaf" />
                <div class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                    <dl class="space-y-5">
                        @foreach ([
                            ['label' => __('therapist.specialty_focus'), 'items' => $therapist['specialties'], 'tone' => 'bg-leaf-50 text-leaf-700 dark:bg-leaf-950 dark:text-leaf-300'],
                            ['label' => __('therapist.massage_types'), 'items' => $therapist['massage_types'], 'tone' => 'bg-ink-50 text-ink-700 dark:bg-ink-800 dark:text-ink-200'],
                            ['label' => __('therapist.client_focus'), 'items' => $therapist['client_focus'], 'tone' => 'bg-ember-50 text-ember-700 dark:bg-ember-950 dark:text-ember-300'],
                        ] as $group)
                            <div>
                                <dt class="text-sm font-bold text-ink-900 dark:text-ink-100">{{ $group['label'] }}</dt>
                                <dd class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($group['items'] as $item)
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $group['tone'] }}">{{ $item }}</span>
                                    @endforeach
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                    <p class="mt-5 border-t border-ink-100 pt-4 text-xs text-ink-400 dark:border-ink-800 dark:text-ink-300">{{ __('therapist.capability_note') }}</p>
                </div>
            </section>

            {{-- Services & Prices --}}
            <section id="services" aria-labelledby="services-heading" class="scroll-mt-32">
                <x-section-heading id="services-heading" :title="__('therapist.services_title')" />
                @if (count($therapist['services']) > 0)
                    <ul class="divide-y divide-ink-100 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm dark:divide-ink-800 dark:border-ink-800 dark:bg-ink-900">
                        @foreach ($therapist['services'] as $service)
                            <li class="flex items-center gap-4 px-4 py-3">
                                <span class="flex size-14 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br {{ $service['tone'] }}">
                                    <x-pictogram :name="$service['icon']" class="size-7" />
                                </span>
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-bold text-ink-950 dark:text-ink-50">{{ $service['name'] }}</h3>
                                    <p class="text-sm text-ink-400 dark:text-ink-300">{{ __('therapist.minutes', ['count' => $service['duration']]) }} · {{ $service['context'] }}</p>
                                </div>
                                <p class="shrink-0 text-lg font-black text-ember-600 dark:text-ember-400">{{ $service['price'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                    <p class="mt-3 text-xs text-ink-400 dark:text-ink-300">{{ __('therapist.last_confirmed', ['date' => $therapist['last_confirmed']]) }}</p>
                @else
                    <div class="rounded-2xl border border-dashed border-ink-200 bg-ink-50/50 p-6 text-center dark:border-ink-700 dark:bg-charcoal-950">
                        <h3 class="font-bold text-ink-700 dark:text-ink-200">{{ __('therapist.no_services_title') }}</h3>
                        <p class="mx-auto mt-1.5 max-w-md text-sm text-ink-500 dark:text-ink-400">{{ __('therapist.no_services_text') }}</p>
                    </div>
                @endif
            </section>

            {{-- Availability & Booking --}}
            <section id="availability" aria-labelledby="availability-heading" class="scroll-mt-32">
                <x-section-heading id="availability-heading" :title="__('therapist.availability_title')" accent="leaf" />
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach ($therapist['affiliations'] as $context)
                        <article class="rounded-2xl border border-ink-100 bg-white p-4 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                            <h3 class="font-bold text-ink-950 dark:text-ink-50">{{ $context['is_independent'] ? __('therapist.independent_practice') : $context['name'] }}</h3>
                            <div class="mt-2.5 flex flex-wrap gap-1.5">
                                <span class="rounded-full bg-ink-50 px-2.5 py-0.5 text-[0.65rem] font-bold uppercase tracking-wide text-ink-600 dark:bg-ink-800 dark:text-ink-300">
                                    {{ __('therapist.availability_'.($context['availability'] === 'existing_clients' ? 'existing' : $context['availability'])) }}
                                </span>
                                @if ($context['booking_mode'])
                                    <span class="rounded-full bg-leaf-50 px-2.5 py-0.5 text-[0.65rem] font-bold uppercase tracking-wide text-leaf-700 dark:bg-leaf-950 dark:text-leaf-300">
                                        {{ __('therapist.booking_mode_'.$context['booking_mode']) }}
                                    </span>
                                @endif
                            </div>
                            <p class="mt-2.5 text-sm text-ink-500 dark:text-ink-400">{{ $context['detail'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            {{-- Affiliations & Independent Practice --}}
            <section id="affiliations" aria-labelledby="affiliations-heading" class="scroll-mt-32">
                <x-section-heading id="affiliations-heading" :title="__('therapist.affiliations_title')" />
                <ul class="divide-y divide-ink-100 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm dark:divide-ink-800 dark:border-ink-800 dark:bg-ink-900">
                    @foreach ($therapist['affiliations'] as $affiliation)
                        <li class="flex flex-wrap items-center gap-x-4 gap-y-2 px-4 py-3.5">
                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-ink-950 dark:text-ink-50">
                                    @if ($affiliation['spa_slug'])
                                        <a href="{{ route('spa.show', ['establishment_slug' => $affiliation['spa_slug']]) }}" class="transition hover:text-ember-600 dark:hover:text-ember-400">{{ $affiliation['name'] }}</a>
                                    @else
                                        {{ $affiliation['is_independent'] ? __('therapist.independent_practice') : $affiliation['name'] }}
                                    @endif
                                </h3>
                                <p class="mt-0.5 text-sm text-ink-500 dark:text-ink-400">
                                    {{ __('therapist.work_arrangement_'.$affiliation['work_arrangement']) }}@if ($affiliation['title']) · {{ $affiliation['title'] }}@endif @if ($affiliation['market_class']) · {{ $affiliation['market_class'] }}@endif
                                </p>
                            </div>
                            <span class="rounded-full px-2.5 py-0.5 text-[0.65rem] font-bold uppercase tracking-wide {{ $affiliation['confirmation'] === 'therapist_confirmed' ? 'bg-leaf-50 text-leaf-700 dark:bg-leaf-950 dark:text-leaf-300' : 'bg-ink-50 text-ink-500 dark:bg-ink-800 dark:text-ink-400' }}">
                                {{ __('therapist.confirmation_'.$affiliation['confirmation']) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
                <p class="mt-3 text-xs text-ink-400 dark:text-ink-300">{{ __('therapist.affiliation_note') }}</p>
            </section>

            {{-- Service Area --}}
            <section aria-labelledby="service-area-heading" class="scroll-mt-32">
                <x-section-heading id="service-area-heading" :title="__('therapist.service_area_title')" accent="leaf" />
                <div class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                    <ul class="flex flex-wrap gap-2">
                        @foreach ($therapist['service_area'] as $area)
                            <li class="rounded-full bg-ink-50 px-3.5 py-1.5 text-sm font-semibold text-ink-700 dark:bg-ink-800 dark:text-ink-200">{{ $area }}</li>
                        @endforeach
                    </ul>
                    <p class="mt-4 border-t border-ink-100 pt-4 text-xs text-ink-400 dark:border-ink-800 dark:text-ink-300">{{ __('therapist.service_area_note') }}</p>
                </div>
            </section>

            {{-- Experience & Credentials --}}
            <section id="credentials" aria-labelledby="credentials-heading" class="scroll-mt-32">
                <x-section-heading id="credentials-heading" :title="__('therapist.credentials_title')" />
                <div class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                    @php $credentials = $therapist['credential_summary']; @endphp
                    @if ($credentials['verified_active'] > 0 || $credentials['submitted'] > 0)
                        <ul class="space-y-2.5 text-sm text-ink-700 dark:text-ink-200">
                            @if ($credentials['verified_active'] > 0)
                                <li class="flex items-center gap-2.5">
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-5 shrink-0 text-leaf-600 dark:text-leaf-400" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.7-9.3a1 1 0 0 0-1.4-1.4L9 10.6 7.7 9.3a1 1 0 0 0-1.4 1.4l2 2a1 1 0 0 0 1.4 0l4-4Z" clip-rule="evenodd"/></svg>
                                    {{ trans_choice('therapist.credentials_verified_active', $credentials['verified_active'], ['count' => $credentials['verified_active']]) }}
                                </li>
                            @endif
                            @if ($credentials['submitted'] > 0)
                                <li class="flex items-center gap-2.5">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5 shrink-0 text-ink-400 dark:text-ink-300" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                    {{ trans_choice('therapist.credentials_submitted', $credentials['submitted'], ['count' => $credentials['submitted']]) }}
                                </li>
                            @endif
                        </ul>
                    @else
                        <p class="text-sm text-ink-500 dark:text-ink-400">{{ __('therapist.credentials_none') }}</p>
                    @endif
                    <p class="mt-5 border-t border-ink-100 pt-4 text-xs text-ink-400 dark:border-ink-800 dark:text-ink-300">{{ __('therapist.credentials_note') }}</p>
                </div>
            </section>

            {{-- Reviews --}}
            <section id="reviews" aria-labelledby="reviews-heading" class="scroll-mt-32">
                <x-section-heading id="reviews-heading" :title="__('therapist.reviews_title')" accent="leaf" />
                <div class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                    <div class="flex flex-col gap-8 md:flex-row">
                        <div class="shrink-0 text-center md:w-44">
                            <p class="text-sm font-bold text-ink-500 dark:text-ink-400">{{ __('therapist.rating_summary') }}</p>
                            @if ($therapist['rating'] !== null)
                                <p class="mt-1 text-5xl font-black text-ink-950 dark:text-ink-50">{{ number_format($therapist['rating'], 1) }}</p>
                                <x-rating :value="$therapist['rating']" size="size-5" class="mt-2 justify-center" />
                                <p class="mt-1.5 text-xs text-ink-400 dark:text-ink-300">{{ trans_choice('therapist.rating_count', $therapist['rating_count'], ['count' => $therapist['rating_count']]) }}</p>
                                <p class="text-xs text-ink-400 dark:text-ink-300">{{ trans_choice('therapist.review_count', $therapist['review_count'], ['count' => $therapist['review_count']]) }}</p>
                            @else
                                <p class="mt-3 rounded-xl bg-ink-50 px-3 py-4 text-sm font-semibold text-ink-500 dark:bg-ink-800 dark:text-ink-400">{{ __('therapist.not_enough_ratings') }}</p>
                                <p class="mt-1.5 text-xs text-ink-400 dark:text-ink-300">{{ trans_choice('therapist.rating_count', $therapist['rating_count'], ['count' => $therapist['rating_count']]) }}</p>
                                <p class="mt-1.5 text-xs text-ink-400 dark:text-ink-300">{{ trans_choice('therapist.review_count', $therapist['review_count'], ['count' => $therapist['review_count']]) }}</p>
                            @endif
                        </div>
                        <ul class="min-w-0 flex-1 space-y-5">
                            @forelse ($therapist['reviews'] as $review)
                                <li class="flex gap-3.5 {{ $loop->last ? '' : 'border-b border-ink-100 pb-5 dark:border-ink-800' }}">
                                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-ember-100 text-sm font-bold text-ember-700 dark:bg-ember-900 dark:text-ember-300">{{ $review['initials'] }}</span>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                            <p class="text-sm font-bold text-ink-950 dark:text-ink-50">{{ $review['byline'] }}</p>
                                            <p class="text-xs text-ink-400 dark:text-ink-300">{{ optional($review['published_at'])->format('M j, Y') }}</p>
                                            <span class="rounded-md bg-ink-950 px-2 py-1 text-xs font-black text-white">{{ __('review.score_out_of_ten', ['score' => number_format($review['score'], 1)]) }}</span>
                                        </div>
                                        @if ($review['service_received'])<p class="mt-0.5 text-xs text-ink-400 dark:text-ink-300">{{ $review['service_received'] }}</p>@endif
                                        <a href="{{ route('review.show', $review['slug']) }}" class="mt-1.5 block font-bold text-ink-900 hover:text-ember-700 dark:text-ink-100 dark:hover:text-ember-300">{{ $review['title'] }}</a>
                                        <p class="mt-1 text-sm leading-relaxed text-ink-600 dark:text-ink-300">{{ $review['short_description'] }}</p>
                                    </div>
                                </li>
                            @empty
                                <li class="rounded-xl bg-ink-50 p-5 text-sm text-ink-500 dark:bg-ink-800 dark:text-ink-400">{{ __('therapist.no_reviews') }}</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="mt-6 flex flex-wrap justify-end gap-3">
                        <a href="{{ route('therapist.review.create', ['therapist_slug' => $therapist['slug']]) }}" class="inline-flex items-center rounded-xl bg-ember-500 px-4 py-2 text-sm font-bold text-white hover:bg-ember-600">{{ __('therapist.write_review') }}</a>
                        <a href="{{ route('review.therapist', ['target' => $therapist['slug']]) }}" class="inline-flex items-center gap-1 rounded-xl border border-ink-200 px-4 py-2 text-sm font-bold text-ink-700 transition hover:border-ember-300 hover:text-ember-600 dark:border-ink-700 dark:text-ink-200 dark:hover:border-ember-700 dark:hover:text-ember-400">
                            {{ __('therapist.view_all_reviews') }}
                            <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h9.69L10.22 6.03a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                        </a>
                    </div>
                </div>
            </section>

            {{-- Verification & Sources --}}
            <section id="about" aria-labelledby="verification-heading" class="scroll-mt-32">
                <x-section-heading id="verification-heading" :title="__('therapist.verification_title')" />
                <div class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-ink-600 dark:text-ink-300">
                        @if ($therapist['is_claimed'])
                            <span class="inline-flex items-center gap-1.5 font-semibold text-leaf-700 dark:text-leaf-300">
                                <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.7-9.3a1 1 0 0 0-1.4-1.4L9 10.6 7.7 9.3a1 1 0 0 0-1.4 1.4l2 2a1 1 0 0 0 1.4 0l4-4Z" clip-rule="evenodd"/></svg>
                                {{ __('therapist.managed_by_therapist') }}
                            </span>
                            <span aria-hidden="true" class="text-ink-300 dark:text-ink-600">·</span>
                        @endif
                        <span>{{ __('therapist.last_confirmed', ['date' => $therapist['last_confirmed']]) }}</span>
                    </div>
                    @unless ($therapist['is_claimed'])
                        <p class="mt-3 border-t border-ink-100 pt-3 text-sm text-ink-500 dark:border-ink-800 dark:text-ink-400">
                            {{ __('therapist.are_you_therapist', ['name' => $therapist['name']]) }}
                            <a href="{{ url('/claim/therapist') }}" class="font-bold text-ember-600 transition hover:text-ember-700 dark:text-ember-400 dark:hover:text-ember-300">{{ __('therapist.claim_this_profile') }}</a>
                        </p>
                    @endunless
                </div>
            </section>
        </div>

        {{-- ===================== Booking panel / unclaimed notice ===================== --}}
        <aside class="min-w-0">
            @if ($therapist['is_booking_enabled'])
                <section id="book" aria-labelledby="book-heading" class="scroll-mt-32 rounded-2xl border border-ink-100 bg-white p-4 shadow-md lg:sticky lg:top-36 dark:border-ink-800 dark:bg-ink-900">
                    <h2 id="book-heading" class="text-lg font-black text-ink-950 dark:text-ink-50">{{ __('therapist.book_title', ['name' => $therapist['name']]) }}</h2>
                    <form class="mt-5 space-y-4" action="#book" method="get">
                        <div>
                            <label for="booking-context" class="mb-1 block text-sm font-bold text-ink-900 dark:text-ink-100">{{ __('therapist.select_context') }}</label>
                            <select id="booking-context" name="context" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50 dark:placeholder:text-ink-400">
                                @foreach ($therapist['affiliations'] as $context)
                                    @if ($context['booking_mode'])
                                        <option value="{{ \Illuminate\Support\Str::slug($context['name']) }}">{{ $context['is_independent'] ? __('therapist.independent_practice') : $context['name'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="booking-service" class="mb-1 block text-sm font-bold text-ink-900 dark:text-ink-100">{{ __('therapist.select_service') }}</label>
                            <select id="booking-service" name="service" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50 dark:placeholder:text-ink-400">
                                <option value="">{{ __('therapist.choose_service') }}</option>
                                @foreach ($therapist['services'] as $service)
                                    <option value="{{ \Illuminate\Support\Str::slug($service['name']) }}">{{ $service['name'] }} — {{ $service['price'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="booking-date" class="mb-1 block text-sm font-bold text-ink-900 dark:text-ink-100">{{ __('therapist.select_date') }}</label>
                            <input id="booking-date" name="date" type="date" min="{{ now()->toDateString() }}" value="{{ now()->addDay()->toDateString() }}"
                                   class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50 dark:placeholder:text-ink-400">
                        </div>
                        <div>
                            <label for="booking-time" class="mb-1 block text-sm font-bold text-ink-900 dark:text-ink-100">{{ __('therapist.select_time') }}</label>
                            <select id="booking-time" name="time" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50 dark:placeholder:text-ink-400">
                                @foreach ($therapist['booking_times'] as $time)
                                    <option value="{{ $time }}">{{ $time }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full rounded-xl bg-ember-500 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">
                            {{ __('common.book_now') }}
                        </button>
                        <p class="text-center text-xs text-ink-400 dark:text-ink-300">{{ __('therapist.book_note') }}</p>
                        <p class="rounded-xl bg-ink-50 px-3.5 py-2.5 text-center text-xs text-ink-500 dark:bg-ink-800 dark:text-ink-400">{{ __('therapist.booking_mode_request') }}</p>
                    </form>
                </section>
            @else
                <section aria-labelledby="unclaimed-heading" class="rounded-2xl border border-ember-200 bg-ember-50 p-5 shadow-sm lg:sticky lg:top-36 dark:border-ember-800 dark:bg-ember-950">
                    <h2 id="unclaimed-heading" class="text-lg font-black text-ink-950 dark:text-ink-50">{{ __('therapist.unclaimed_notice_title') }}</h2>
                    <p class="mt-2 text-sm leading-relaxed text-ink-700 dark:text-ink-200">{{ __('therapist.unclaimed_notice_text') }}</p>
                    <a href="{{ url('/claim/therapist') }}" class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-ember-500 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">
                        {{ __('therapist.invite_to_claim') }}
                    </a>
                </section>
            @endif
        </aside>
    </div>
</div>
@endsection
