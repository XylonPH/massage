@extends('layouts.app')

@section('title', $spa['name'])
@section('meta_description', $spa['about'])

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
                <li><a href="{{ route('home') }}" class="transition hover:text-white">{{ __('spa.breadcrumb_home') }}</a></li>
                <li aria-hidden="true">›</li>
                <li><a href="{{ url('/directory/spa') }}" class="transition hover:text-white">{{ __('spa.breadcrumb_spas') }}</a></li>
                <li aria-hidden="true">›</li>
                <li aria-current="page" class="font-semibold text-white">{{ $spa['name'] }}</li>
            </ol>
        </nav>

        <div class="mt-6 flex flex-col gap-8 lg:flex-row lg:items-start">
            <div class="flex size-24 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-leaf-600 to-leaf-900 shadow-lg sm:size-28">
                <x-pictogram name="leaf" class="size-12 text-white/80" />
            </div>

            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    @if ($spa['is_verified'])
                        <span class="inline-flex items-center gap-1 rounded-full bg-leaf-500/20 px-3 py-1 text-xs font-bold uppercase tracking-wide text-leaf-300 ring-1 ring-leaf-400/40">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="size-3.5" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.7-9.3a1 1 0 0 0-1.4-1.4L9 10.6 7.7 9.3a1 1 0 0 0-1.4 1.4l2 2a1 1 0 0 0 1.4 0l4-4Z" clip-rule="evenodd"/></svg>
                            {{ __('common.verified') }}
                        </span>
                    @endif
                    @if ($spa['is_claimed'])
                        <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-wide text-ink-200 ring-1 ring-white/20">{{ __('spa.claimed_listing') }}</span>
                    @endif
                </div>

                <h1 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">{{ $spa['name'] }}</h1>
                <p class="mt-1.5 text-ink-200">{{ $spa['type'] }} · {{ $spa['market_class'] }} · {{ $spa['area'] }}</p>

                <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm">
                    <span class="text-ink-300">{{ __('spa.not_enough_ratings') }} · {{ __('common.reviews', ['count' => $spa['review_count']]) }}</span>
                    <span aria-hidden="true" class="hidden text-ink-600 sm:inline">|</span>
                    <span class="font-semibold {{ $spa['is_open'] ? 'text-leaf-400' : 'text-ember-400' }}">
                        {{ $spa['is_open'] ? __('common.open') : __('common.closed') }}
                    </span>
                    <span class="text-ink-300">{{ __('common.closes_at', ['time' => $spa['closes']]) }}</span>
                </div>

                <div class="mt-5 flex flex-wrap gap-2.5">
                    @foreach ($spa['badges'] as $badge)
                        <span class="inline-flex items-center gap-1.5 rounded-xl border border-white/15 bg-white/5 px-3.5 py-2 text-xs font-semibold text-ink-100">
                            @if ($badge === 'home_service')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4 text-ember-400" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5M5 9.5V21h14V9.5"/></svg>
                                {{ __('spa.home_service_available') }}
                            @elseif ($badge === 'gift_certificates')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4 text-ember-400" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16v4H4V8Zm2 4v8h12v-8M12 8v12M12 8s-1-4-4-4a2 2 0 0 0 0 4h4Zm0 0s1-4 4-4a2 2 0 0 1 0 4h-4Z"/></svg>
                                {{ __('spa.gift_certificates') }}
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4 text-leaf-400" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 15C6 8 11 4 19 4c0 8-4 13-11 13-1 0-2-.5-2-2Zm0 0c0 2-1 3-2 5"/></svg>
                                {{ __('spa.resorts_relaxation') }}
                            @endif
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- Gallery preview --}}
            <div class="grid w-full shrink-0 grid-cols-3 gap-2 lg:w-72 lg:grid-cols-2" aria-label="{{ __('spa.photo_gallery') }}">
                <div class="flex h-24 items-center justify-center rounded-xl bg-gradient-to-br from-leaf-800 to-ink-900 lg:col-span-2 lg:h-28"><x-pictogram name="lotus" class="size-8 text-white/40" /></div>
                <div class="flex h-24 items-center justify-center rounded-xl bg-gradient-to-br from-ember-800 to-charcoal-950 lg:h-20"><x-pictogram name="stones" class="size-7 text-white/40" /></div>
                <div class="relative flex h-24 items-center justify-center rounded-xl bg-gradient-to-br from-ink-700 to-ink-950 lg:h-20">
                    <x-pictogram name="tea" class="size-7 text-white/40" />
                    <a href="#gallery" class="absolute inset-0 flex items-center justify-center rounded-xl bg-ink-950/60 text-xs font-bold text-white transition hover:bg-ink-950/40">
                        {{ __('spa.view_all_photos', ['count' => $spa['photo_count']]) }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-7 flex flex-wrap items-center gap-2.5">
            <a href="#book" class="inline-flex items-center gap-2 rounded-xl bg-ember-500 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M5 5h14a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/></svg>
                {{ __('common.book_now') }}
            </a>
            @foreach ([
                ['label' => __('common.contact'), 'icon' => 'M3 7.5A1.5 1.5 0 0 1 4.5 6h2.6a1 1 0 0 1 1 .76l.7 2.8a1 1 0 0 1-.5 1.12l-1.6.94a11.6 11.6 0 0 0 5.7 5.7l.94-1.6a1 1 0 0 1 1.1-.5l2.8.7a1 1 0 0 1 .76 1v2.58a1.5 1.5 0 0 1-1.5 1.5C9.7 21 3 14.3 3 7.5Z'],
                ['label' => __('common.get_directions'), 'icon' => 'm9 20-5.5-2.5v-13L9 7l6-2.5L20.5 7v13L15 17.5 9 20Zm0-13v13m6-15.5v13'],
                ['label' => __('common.save'), 'icon' => 'M12 20s-7.5-4.7-9.4-9A5.3 5.3 0 0 1 12 6.6 5.3 5.3 0 0 1 21.4 11c-1.9 4.3-9.4 9-9.4 9Z'],
                ['label' => __('common.follow'), 'icon' => 'M12 5v14m-7-7h14'],
                ['label' => __('common.share'), 'icon' => 'M7.5 13.5 15 18m0-12-7.5 4.5M18 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm0 13a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm-11-6.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z'],
            ] as $action)
                <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-white/20 px-4 py-2.5 text-sm font-semibold text-ink-100 transition hover:bg-white/10">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $action['icon'] }}"/></svg>
                    {{ $action['label'] }}
                </button>
            @endforeach
            <button type="button" class="ml-auto inline-flex items-center gap-1.5 text-xs font-semibold text-ink-400 transition hover:text-ink-200">
                {{ __('common.suggest_correction') }}
            </button>
        </div>
    </div>
</section>

{{-- ===================== Tabs ===================== --}}
<nav class="sticky top-16 z-30 border-b border-ink-100 bg-white/95 shadow-sm backdrop-blur" aria-label="{{ $spa['name'] }}">
    <div class="mx-auto max-w-[1600px] overflow-x-auto px-4 sm:px-6 lg:px-8">
        <ul class="flex gap-1 whitespace-nowrap py-1">
            @foreach ([
                ['href' => '#overview', 'label' => __('spa.tab_overview')],
                ['href' => '#services', 'label' => __('spa.tab_services')],
                ['href' => '#therapists', 'label' => __('spa.tab_therapists')],
                ['href' => '#facilities', 'label' => __('spa.tab_facilities')],
                ['href' => '#reviews', 'label' => __('spa.tab_reviews')],
                ['href' => '#location', 'label' => __('spa.tab_location')],
            ] as $tab)
                <li><a href="{{ $tab['href'] }}" class="inline-block rounded-lg px-3.5 py-2.5 text-sm font-semibold text-ink-600 transition hover:bg-ink-50 hover:text-ember-600">{{ $tab['label'] }}</a></li>
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
                <x-section-heading id="overview-heading" :title="__('spa.about_title', ['name' => $spa['name']])" />
                <div class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm">
                    <p class="leading-relaxed text-ink-700">{{ $spa['about'] }}</p>
                    <dl class="mt-6 grid grid-cols-2 gap-4 border-t border-ink-100 pt-5 sm:grid-cols-3">
                        @foreach ($spa['amenities'] as $amenity)
                            <div class="flex items-center gap-3">
                                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-ink-50">
                                    <x-pictogram :name="$amenity['icon']" class="size-5 text-ink-600" />
                                </span>
                                <div>
                                    <dt class="text-sm font-bold text-ink-900">{{ $amenity['label'] }}</dt>
                                    <dd class="text-xs text-leaf-600">{{ $amenity['icon'] === 'card' ? __('spa.amenity_accepted') : __('spa.amenity_available') }}</dd>
                                </div>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </section>

            <section id="services" aria-labelledby="services-heading" class="scroll-mt-32">
                <x-section-heading id="services-heading" :title="__('spa.popular_services')" accent="leaf" />
                <ul class="divide-y divide-ink-100 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm">
                    @foreach ($spa['services'] as $service)
                        <li class="flex items-center gap-4 px-4 py-3">
                            <span class="flex size-14 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br {{ $service['tone'] }}">
                                <x-pictogram :name="$service['icon']" class="size-7" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-ink-950">{{ $service['name'] }}</h3>
                                <p class="text-sm text-ink-400">{{ __('spa.minutes', ['count' => $service['duration']]) }}</p>
                            </div>
                            <p class="shrink-0 text-lg font-black text-ember-600">{{ $service['price'] }}</p>
                        </li>
                    @endforeach
                </ul>
                <p class="mt-3 text-xs text-ink-400">{{ __('spa.last_confirmed', ['date' => $spa['last_confirmed']]) }}</p>
            </section>

            {{-- Therapists --}}
            <section id="therapists" aria-labelledby="therapists-heading" class="scroll-mt-32">
                <x-section-heading id="therapists-heading" :title="__('spa.therapists_title')" />
                <div class="grid gap-4 sm:grid-cols-3">
                    @foreach ($spa['therapists'] as $therapist)
                        <article class="rounded-2xl border border-ink-100 bg-white p-4 text-center shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                            <span class="mx-auto flex size-14 items-center justify-center rounded-full text-base font-bold {{ $therapist['tone'] }}">{{ $therapist['initials'] }}</span>
                            <h3 class="mt-3 font-bold text-ink-950">{{ $therapist['name'] }}</h3>
                            <p class="mt-0.5 text-xs font-bold uppercase tracking-wide text-ember-600">{{ $therapist['title'] }}</p>
                            <p class="mt-1.5 text-xs text-ink-500">{{ $therapist['specialties'] }}</p>
                            <div class="mt-2 flex items-center justify-center gap-1 text-xs">
                                <span class="font-bold text-ink-900">{{ number_format($therapist['rating'], 1) }}</span>
                                <x-rating :value="$therapist['rating']" size="size-3" />
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            {{-- Facilities & Treatment Areas --}}
            <section id="facilities" aria-labelledby="facilities-heading" class="scroll-mt-32">
                <x-section-heading id="facilities-heading" :title="__('spa.treatment_areas_title')" accent="leaf" />
                <div class="grid gap-4 sm:grid-cols-3">
                    @foreach ($spa['treatment_areas'] as $area)
                        <article class="rounded-2xl border border-ink-100 bg-white p-4 shadow-sm">
                            <h3 class="font-bold text-ink-950">{{ $area['name'] }}</h3>
                            <div class="mt-2.5 flex flex-wrap gap-1.5">
                                <span class="rounded-full bg-ink-50 px-2.5 py-0.5 text-[0.65rem] font-bold uppercase tracking-wide text-ink-600">
                                    {{ $area['privacy'] === 'private' ? __('spa.area_private') : __('spa.area_semi_private') }}
                                </span>
                                <span class="rounded-full bg-leaf-50 px-2.5 py-0.5 text-[0.65rem] font-bold uppercase tracking-wide text-leaf-700">
                                    {{ __('spa.area_capacity_'.$area['capacity']) }}
                                </span>
                            </div>
                            <p class="mt-2.5 text-sm text-ink-500">{{ $area['note'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            {{-- Reviews --}}
            <section id="reviews" aria-labelledby="reviews-heading" class="scroll-mt-32">
                <x-section-heading id="reviews-heading" :title="__('spa.reviews_title')" />
                <div class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-8 md:flex-row">
                        <div class="shrink-0 text-center md:w-44">
                            <p class="text-sm font-bold text-ink-500">{{ __('spa.what_people_love') }}</p>
                            <p class="mt-3 rounded-xl bg-ink-50 px-3 py-4 text-sm font-semibold text-ink-500">{{ __('spa.not_enough_ratings') }}</p>
                            <p class="mt-1.5 text-xs text-ink-400">{{ __('spa.rating_count', ['count' => $spa['rating_count']]) }}</p>
                            <p class="mt-1.5 text-xs text-ink-400">{{ __('common.reviews', ['count' => $spa['review_count']]) }}</p>
                        </div>
                        <ul class="min-w-0 flex-1 space-y-5">
                            @forelse ($spa['reviews'] as $review)
                                <li class="flex gap-3.5 {{ $loop->last ? '' : 'border-b border-ink-100 pb-5' }}">
                                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-ember-100 text-sm font-bold text-ember-700">{{ $review['initials'] }}</span>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                            <p class="text-sm font-bold text-ink-950">{{ $review['byline'] }}</p>
                                            <p class="text-xs text-ink-400">{{ optional($review['published_at'])->format('M j, Y') }}</p>
                                            <span class="rounded-md bg-ink-950 px-2 py-1 text-xs font-black text-white">{{ __('review.score_out_of_ten', ['score' => number_format($review['score'], 1)]) }}</span>
                                        </div>
                                        <a href="{{ route('review.show', $review['slug']) }}" class="mt-1.5 block font-bold text-ink-900 hover:text-ember-700">{{ $review['title'] }}</a>
                                        <p class="mt-1 text-sm leading-relaxed text-ink-600">{{ $review['short_description'] }}</p>
                                    </div>
                                </li>
                            @empty
                                <li class="rounded-xl bg-ink-50 p-5 text-sm text-ink-500">{{ __('spa.no_reviews') }}</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="mt-6 flex flex-wrap justify-end gap-3">
                        <a href="{{ route('spa.review.create', ['establishment_slug' => $spa['slug']]) }}" class="inline-flex items-center rounded-xl bg-ember-500 px-4 py-2 text-sm font-bold text-white hover:bg-ember-600">{{ __('spa.write_review') }}</a>
                        <a href="{{ route('review.spa', ['target' => $spa['slug']]) }}" class="inline-flex items-center gap-1 rounded-xl border border-ink-200 px-4 py-2 text-sm font-bold text-ink-700 transition hover:border-ember-300 hover:text-ember-600">
                            {{ __('spa.view_all_reviews') }}
                            <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h9.69L10.22 6.03a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                        </a>
                    </div>
                </div>
            </section>

            {{-- Location --}}
            <section id="location" aria-labelledby="location-heading" class="scroll-mt-32">
                <x-section-heading id="location-heading" :title="__('spa.location_title')" accent="leaf" />
                <div class="overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm">
                    <div class="relative flex h-36 items-center justify-center bg-gradient-to-br from-ink-100 to-ink-200">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="size-9 text-ember-500" aria-hidden="true"><path d="M12 21s-7-5.5-7-11a7 7 0 1 1 14 0c0 5.5-7 11-7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                        <a href="https://maps.google.com/?q={{ urlencode($spa['name'].' '.$spa['address']) }}" target="_blank" rel="noopener" class="absolute bottom-2.5 right-2.5 rounded-lg bg-ink-950 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-ink-800">
                            {{ __('spa.view_on_map') }}
                        </a>
                    </div>
                    <div class="grid gap-4 p-4 sm:grid-cols-2">
                        <div>
                            <p class="text-sm leading-relaxed text-ink-700">{{ $spa['address'] }}</p>
                            <a href="https://maps.google.com/?q={{ urlencode($spa['name'].' '.$spa['address']) }}" target="_blank" rel="noopener" class="mt-3 inline-flex items-center gap-1.5 text-sm font-bold text-ember-600 transition hover:text-ember-700">
                                {{ __('common.get_directions') }}
                                <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h9.69L10.22 6.03a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                            </a>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-ink-900">{{ __('spa.nearby_landmarks') }}</h3>
                            <ul class="mt-2 space-y-1.5 text-sm text-ink-600">
                                @foreach ($spa['landmarks'] as $landmark)
                                    <li class="flex items-center gap-2">
                                        <span class="size-1.5 rounded-full bg-leaf-500" aria-hidden="true"></span>
                                        {{ $landmark['name'] }} · {{ __('spa.walk_time', ['minutes' => $landmark['minutes']]) }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Verification --}}
            <section aria-labelledby="verification-heading">
                <x-section-heading id="verification-heading" :title="__('spa.verification_title')" />
                <div class="flex flex-wrap items-center gap-4 rounded-2xl border border-ink-100 bg-white p-5 text-sm text-ink-600 shadow-sm">
                    <span class="inline-flex items-center gap-1.5 font-semibold text-leaf-700">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.7-9.3a1 1 0 0 0-1.4-1.4L9 10.6 7.7 9.3a1 1 0 0 0-1.4 1.4l2 2a1 1 0 0 0 1.4 0l4-4Z" clip-rule="evenodd"/></svg>
                        {{ __('spa.managed_by_owner') }}
                    </span>
                    <span aria-hidden="true" class="text-ink-300">·</span>
                    <span>{{ __('spa.last_confirmed', ['date' => $spa['last_confirmed']]) }}</span>
                </div>
            </section>
        </div>

        {{-- ===================== Booking panel ===================== --}}
        <aside class="min-w-0">
            <section id="book" aria-labelledby="book-heading" class="scroll-mt-32 rounded-2xl border border-ink-100 bg-white p-4 shadow-md lg:sticky lg:top-36">
                <h2 id="book-heading" class="text-lg font-black text-ink-950">{{ __('spa.book_title') }}</h2>
                <form class="mt-5 space-y-4" action="#book" method="get">
                    <div>
                        <label for="booking-service" class="mb-1 block text-sm font-bold text-ink-900">{{ __('spa.select_service') }}</label>
                        <select id="booking-service" name="service" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100">
                            <option value="">{{ __('spa.choose_service') }}</option>
                            @foreach ($spa['services'] as $service)
                                <option value="{{ \Illuminate\Support\Str::slug($service['name']) }}">{{ $service['name'] }} — {{ $service['price'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="booking-date" class="mb-1 block text-sm font-bold text-ink-900">{{ __('spa.select_date') }}</label>
                        <input id="booking-date" name="date" type="date" min="{{ now()->toDateString() }}" value="{{ now()->addDay()->toDateString() }}"
                               class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100">
                    </div>
                    <div>
                        <label for="booking-time" class="mb-1 block text-sm font-bold text-ink-900">{{ __('spa.select_time') }}</label>
                        <select id="booking-time" name="time" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100">
                            @foreach ($spa['booking_times'] as $time)
                                <option value="{{ $time }}">{{ $time }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-ember-500 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">
                        {{ __('common.book_now') }}
                    </button>
                    <p class="text-center text-xs text-ink-400">{{ __('spa.book_note') }}</p>
                    <p class="rounded-xl bg-ink-50 px-3.5 py-2.5 text-center text-xs text-ink-500">{{ __('spa.booking_mode_request') }}</p>
                </form>
            </section>
        </aside>
    </div>
</div>
@endsection
