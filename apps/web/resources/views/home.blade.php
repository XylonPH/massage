@extends('layouts.app')

@section('meta_description', __('home.hero_subtitle'))

@section('content')

{{-- ===================== Hero & Search ===================== --}}
@php
    // Drop a photo at public/images/hero/home.webp (or .jpg / .png) to
    // replace the gradient backdrop with a real hero image.
    $heroImage = collect(['webp', 'jpg', 'png'])
        ->map(fn ($extension) => "images/hero/home.{$extension}")
        ->first(fn ($path) => file_exists(public_path($path)));
@endphp
<section class="relative overflow-hidden bg-ink-950">
    @if ($heroImage)
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <img src="{{ asset($heroImage) }}" alt="" class="size-full object-cover object-center">
            <div class="absolute inset-0 bg-gradient-to-r from-ink-950/90 via-ink-950/60 to-ink-950/10"></div>
            <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-ink-950/55 to-transparent"></div>
        </div>
    @else
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -right-32 -top-40 size-[32rem] rounded-full bg-ember-500/15 blur-3xl"></div>
            <div class="absolute -bottom-48 -left-24 size-[28rem] rounded-full bg-leaf-500/15 blur-3xl"></div>
            <div class="absolute right-1/4 top-1/3 size-64 rounded-full bg-ink-500/20 blur-3xl"></div>
            <svg class="absolute -right-8 bottom-0 h-72 w-72 text-white/[0.04]" viewBox="0 0 24 24" fill="currentColor"><path d="M6 15C6 8 11 4 19 4c0 8-4 13-11 13-1 0-2-.5-2-2Z"/></svg>
        </div>
    @endif

    <div class="relative mx-auto min-w-0 max-w-[1600px] px-4 pb-12 pt-10 sm:px-6 lg:px-8 lg:pt-16">
        <div class="grid min-w-0 items-start gap-8 lg:grid-cols-[minmax(0,1fr)_20rem] xl:grid-cols-[minmax(0,1fr)_24rem]">
            <div class="min-w-0">
                <div class="max-w-2xl">
                    <h1 class="text-balance text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-6xl lg:leading-[1.1]">
                        {{ __('home.hero_title_find') }}<br/>
                        <span class="text-ember-500">{{ __('home.hero_title_massage') }}</span><span class="text-ember-500">.</span>
                    </h1>
                    <p class="mt-4 max-w-xl text-lg text-white/90 sm:text-xl">{{ __('home.hero_subtitle') }}</p>
                </div>

                <div class="mt-10 flex max-w-full flex-nowrap gap-1 overflow-x-auto px-1 sm:px-0" role="tablist" aria-label="{{ __('home.search_legend') }}">
            @foreach ([
                ['type' => 'spa', 'label' => __('home.search_tab_spas'), 'placeholder' => __('home.search_placeholder_spa')],
                ['type' => 'therapist', 'label' => __('home.search_tab_therapists'), 'placeholder' => __('home.search_placeholder_therapist')],
                ['type' => 'service', 'label' => __('home.search_tab_services'), 'placeholder' => __('home.search_placeholder_service')],
                ['type' => 'article', 'label' => __('home.search_tab_articles'), 'placeholder' => __('home.search_placeholder_article')],
                ['type' => 'product', 'label' => __('home.search_tab_products'), 'placeholder' => __('home.search_placeholder_product')],
            ] as $tab)
                <button type="button" role="tab" data-search-tab data-type="{{ $tab['type'] }}" data-placeholder="{{ $tab['placeholder'] }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                        class="{{ $loop->first ? 'bg-white text-ink-950 shadow-sm' : 'bg-white/10 text-white hover:bg-white/20' }} rounded-t-xl px-5 py-3 text-sm font-bold backdrop-blur transition-all">
                    {{ $tab['label'] }}
                </button>
            @endforeach
                </div>

                <form action="{{ route('directory.index') }}" method="get" class="min-w-0 max-w-full rounded-2xl rounded-tl-none bg-white p-4 shadow-2xl shadow-ink-950/40 sm:p-5">
            <input type="hidden" name="type" value="spa" data-search-type>
            <fieldset class="min-w-0">
                <legend class="sr-only">{{ __('home.search_legend') }}</legend>
                <div class="flex min-w-0 flex-col gap-3 lg:flex-row">
                    <label class="flex min-w-0 flex-1 items-center gap-3 rounded-xl border-2 border-ink-100 px-4 py-3.5 transition focus-within:border-ember-500 focus-within:ring-4 focus-within:ring-ember-500/20 hover:border-ink-200">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="size-5 shrink-0 text-ink-400" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="m20 20-3.5-3.5"/></svg>
                        <span class="sr-only">{{ __('home.search_placeholder') }}</span>
                        <input type="search" name="q" data-search-query placeholder="{{ __('home.search_placeholder_spa') }}" class="min-w-0 w-full border-0 p-0 text-base text-ink-950 placeholder-ink-400 focus:outline-none focus:ring-0">
                    </label>
                    <label class="flex min-w-0 flex-1 items-center gap-3 rounded-xl border-2 border-ink-100 px-4 py-3.5 transition focus-within:border-ember-500 focus-within:ring-4 focus-within:ring-ember-500/20 hover:border-ink-200 lg:max-w-[320px]">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="size-5 shrink-0 text-ink-400" aria-hidden="true"><path d="M12 21s-7-5.5-7-11a7 7 0 1 1 14 0c0 5.5-7 11-7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                        <span class="sr-only">{{ __('home.location_placeholder') }}</span>
                        <input type="text" name="location" placeholder="{{ __('home.location_placeholder') }}" class="min-w-0 w-full border-0 p-0 text-base text-ink-950 placeholder-ink-400 focus:outline-none focus:ring-0">
                    </label>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-ember-600 px-10 py-3.5 text-base font-black text-white shadow-lg shadow-ember-600/30 transition-all hover:bg-ember-500 hover:-translate-y-0.5 hover:shadow-ember-500/40 active:translate-y-0 active:scale-95">
                        {{ __('home.search_button') }}
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="size-4" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="m20 20-3.5-3.5"/></svg>
                    </button>
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ([
                        ['name' => 'open_now', 'label' => __('home.filter_open_now'), 'icon' => 'M12 7v5l3 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                        ['name' => 'near_me', 'label' => __('home.filter_near_me'), 'icon' => 'M12 21s-7-5.5-7-11a7 7 0 1 1 14 0c0 5.5-7 11-7 11Z'],
                        ['name' => 'promos', 'label' => __('home.filter_promos'), 'icon' => 'M7 7h.01M3.6 3.6h6.8L21 14.2a2 2 0 0 1 0 2.8l-4 4a2 2 0 0 1-2.8 0L3.6 10.4V3.6Z'],
                        ['name' => 'verified', 'label' => __('home.filter_verified'), 'icon' => 'm9 12 2 2 4-4m5.6 2a9.6 9.6 0 1 1-19.2 0 9.6 9.6 0 0 1 19.2 0Z'],
                        ['name' => 'home_service', 'label' => __('home.filter_home_service'), 'icon' => 'M3 10.5 12 3l9 7.5M5 9.5V21h14V9.5'],
                    ] as $filter)
                        <label class="inline-flex cursor-pointer items-center gap-1.5 rounded-full border-2 border-ink-100 px-4 py-1.5 text-xs font-bold text-ink-700 transition hover:border-ink-200 hover:bg-ink-50 has-checked:border-ember-500 has-checked:bg-ember-50 has-checked:text-ember-700">
                            <input type="checkbox" name="{{ $filter['name'] }}" value="1" class="sr-only">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="size-3.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $filter['icon'] }}"/></svg>
                            {{ $filter['label'] }}
                        </label>
                    @endforeach
                </div>
            </fieldset>
                </form>

                <nav aria-label="{{ __('home.popular_searches') }}" class="mt-6 flex min-w-0 max-w-full flex-col gap-3 rounded-2xl border border-white/5 bg-ink-950/20 px-5 py-4 text-white shadow-xl shadow-ink-950/20 backdrop-blur-md sm:flex-row sm:items-center">
                    <span class="inline-flex shrink-0 items-center gap-2 text-[11px] font-black uppercase tracking-widest text-ember-400">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m13 2-1 7h7l-8 13 1-8H5l8-12Z"/></svg>
                        POPULAR RIGHT NOW
                    </span>
                    <span class="hidden h-5 w-px bg-white/10 sm:block" aria-hidden="true"></span>
                    <span class="flex min-w-0 flex-wrap gap-2">
                        @foreach (array_slice($massageTypes, 0, 4) as $massageType)
                            <a href="{{ route('directory.index', ['service' => \Illuminate\Support\Str::slug($massageType['name'])]) }}" class="rounded-full border border-white/15 bg-white/5 px-4 py-1.5 text-xs font-bold text-white transition hover:border-ember-400 hover:bg-ember-500 hover:text-white">
                                {{ $massageType['name'] }}
                            </a>
                        @endforeach
                        <a href="{{ route('directory.index') }}" class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-black text-ink-200 transition hover:text-ember-400">
                            Explore all
                            <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h9.69L10.22 6.03a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                        </a>
                    </span>
                </nav>
            </div>

            {{-- Wellness Journey stats --}}
            <section aria-labelledby="stats-heading" class="relative min-w-0 overflow-hidden rounded-3xl border border-white/10 bg-ink-950/60 p-6 text-white shadow-2xl shadow-ink-950/50 backdrop-blur-md">
                <div class="pointer-events-none absolute -right-12 -top-12 size-48 rounded-full bg-ember-500/10 blur-3xl" aria-hidden="true"></div>
                <div class="pointer-events-none absolute -bottom-12 -left-12 size-40 rounded-full bg-leaf-500/10 blur-3xl" aria-hidden="true"></div>
                <svg class="pointer-events-none absolute -right-6 -top-6 size-32 text-white/5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6 15C6 8 11 4 19 4c0 8-4 13-11 13-1 0-2-.5-2-2Z"/></svg>
                
                <h2 id="stats-heading" class="relative text-lg font-black tracking-tight">{{ __('home.stats_title') }}</h2>
                <p class="relative mt-1 text-xs text-ink-300">{{ __('home.stats_subtitle') }}</p>
                
                <dl class="relative mt-5 grid grid-cols-2 gap-3 lg:grid-cols-1 xl:grid-cols-2">
                    @foreach ([
                        ['value' => $stats['spas'], 'label' => __('home.stats_spas'), 'icon' => 'M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6'],
                        ['value' => $stats['therapists'], 'label' => __('home.stats_therapists'), 'icon' => 'M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1'],
                        ['value' => $stats['reviews'], 'label' => __('home.stats_reviews'), 'icon' => 'm12 3 2.7 5.6 6.3.8-4.6 4.3 1.2 6.1L12 16.9 6.4 19.8l1.2-6.1L3 9.4l6.3-.8L12 3Z'],
                        ['value' => $stats['articles'], 'label' => __('home.stats_articles'), 'icon' => 'M12 6.25c-2.5-1.7-5.5-1.9-8-.75v13c2.5-1.15 5.5-.95 8 .75 2.5-1.7 5.5-1.9 8-.75v-13c-2.5-1.15-5.5-.95-8 .75Zm0 0V19.5'],
                    ] as $stat)
                        <div class="rounded-2xl border border-white/5 bg-white/5 p-4 transition hover:bg-white/10">
                            <span class="flex size-9 items-center justify-center rounded-xl border border-white/10 bg-white/5 shadow-sm">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4.5 text-ember-400" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/></svg>
                            </span>
                            <dt class="sr-only">{{ $stat['label'] }}</dt>
                            <dd class="mt-3 text-2xl font-black leading-none tracking-tight text-white">{{ $stat['value'] }}</dd>
                            <dd class="mt-1.5 text-[11px] font-semibold text-ink-300">{{ $stat['label'] }}</dd>
                        </div>
                    @endforeach
                </dl>
                
                <a href="{{ url('/statistics') }}" class="relative mt-5 inline-flex items-center gap-1.5 text-xs font-bold text-ember-400 transition hover:text-ember-300">
                    {{ __('home.view_all_statistics') }}
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h9.69L10.22 6.03a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                </a>
            </section>
        </div>
    </div>
</section>

{{-- ===================== Main grid ===================== --}}
<div class="mx-auto max-w-[1600px] px-4 py-8 sm:px-6 lg:px-8">
    <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_20rem] xl:gap-10 xl:grid-cols-[minmax(0,1fr)_24rem]">
        <div class="min-w-0 space-y-10">

            {{-- Featured Spas --}}
            <section aria-labelledby="featured-spas">
                <x-section-heading id="featured-spas" :title="__('home.featured_spas')" :href="url('/directory/spa')" />
                <div class="grid gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-2 xl:grid-cols-4">
                    @foreach ($featuredSpas as $spa)
                        <article class="group min-w-0 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl hover:border-ink-200 dark:border-ink-800 dark:bg-ink-900 dark:hover:border-ink-700">
                            <a href="{{ route('spa.show', ['establishment_slug' => $spa['slug']]) }}" class="block">
                                <div class="relative flex h-32 items-center justify-center bg-gradient-to-br {{ $spa['gradient'] }} shadow-[inset_0_0_40px_rgba(0,0,0,0.2)]">
                                    <x-pictogram :name="$spa['icon']" class="size-12 text-white/50 transition duration-500 group-hover:scale-110 group-hover:text-white/70" />
                                    <div class="absolute left-2.5 top-2.5 flex gap-1">
                                        @if ($spa['is_premium'])
                                            <span class="rounded-full bg-ember-500 px-2 py-0.5 text-[0.6rem] font-bold uppercase tracking-wide text-white">{{ __('common.premium') }}</span>
                                        @endif
                                        @if ($spa['is_verified'])
                                            <span class="inline-flex items-center gap-0.5 rounded-full bg-white/90 px-2 py-0.5 text-[0.6rem] font-bold uppercase tracking-wide text-leaf-700">
                                                <svg viewBox="0 0 20 20" fill="currentColor" class="size-2.5" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.7-9.3a1 1 0 0 0-1.4-1.4L9 10.6 7.7 9.3a1 1 0 0 0-1.4 1.4l2 2a1 1 0 0 0 1.4 0l4-4Z" clip-rule="evenodd"/></svg>
                                                {{ __('common.verified') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-[15px] font-black text-ink-950 transition group-hover:text-ember-600 truncate dark:text-ink-50 dark:group-hover:text-ember-400">{{ $spa['name'] }}</h3>
                                    <div class="mt-1 flex items-center gap-1.5 text-[13px]">
                                        <span class="font-black text-ink-900 dark:text-ink-100">{{ number_format($spa['rating'], 1) }}</span>
                                        <x-rating :value="$spa['rating']" size="size-3.5" />
                                        <span class="font-semibold text-ink-400 dark:text-ink-300">({{ $spa['review_count'] }})</span>
                                    </div>
                                    <p class="mt-2.5 truncate text-xs font-semibold text-ink-500 dark:text-ink-400">{{ $spa['area'] }}</p>
                                    <p class="mt-0.5 truncate text-xs text-ink-500 dark:text-ink-400">{{ $spa['services'] }}</p>
                                    <p class="mt-1.5 text-[0.7rem] font-semibold">
                                        <span class="text-leaf-600 dark:text-leaf-400">{{ __('common.open') }}</span>
                                        <span class="text-ink-400 dark:text-ink-300"> · {{ __('common.closes_at', ['time' => $spa['closes']]) }}</span>
                                    </p>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>

            {{-- Featured Therapists --}}
            <section aria-labelledby="featured-therapists">
                <x-section-heading id="featured-therapists" :title="__('home.featured_therapists')" :href="url('/directory/therapist')" accent="leaf" />
                <div class="grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-2 xl:grid-cols-4">
                    @foreach ($featuredTherapists as $therapist)
                        <article class="min-w-0 rounded-2xl border border-ink-100 bg-white p-5 text-center shadow-sm transition-all hover:-translate-y-1 hover:border-ink-200 hover:shadow-xl dark:border-ink-800 dark:bg-ink-900 dark:hover:border-ink-700">
                            <span class="mx-auto flex size-[72px] items-center justify-center rounded-full text-xl font-black {{ $therapist['tone'] }} shadow-sm">{{ $therapist['initials'] }}</span>
                            <h3 class="mt-4 text-[15px] font-black text-ink-950 dark:text-ink-50">
                                @if (!empty($therapist['slug']))
                                    <a href="{{ route('therapist.show', ['therapist_slug' => $therapist['slug']]) }}" class="transition hover:text-ember-600 dark:hover:text-ember-400">{{ $therapist['name'] }}</a>
                                @else
                                    {{ $therapist['name'] }}
                                @endif
                            </h3>
                            <div class="mt-1.5 flex items-center justify-center gap-1.5 text-[13px]">
                                <span class="font-black text-ink-900 dark:text-ink-100">{{ number_format($therapist['rating'], 1) }}</span>
                                <x-rating :value="$therapist['rating']" size="size-3.5" />
                                <span class="font-semibold text-ink-400 dark:text-ink-300">({{ $therapist['review_count'] }})</span>
                            </div>
                            <p class="mt-2 text-[13px] font-semibold text-ink-500 dark:text-ink-400">{{ $therapist['specialties'] }}</p>
                            <p class="mt-0.5 truncate text-xs text-ink-400 dark:text-ink-300">{{ $therapist['area'] }}</p>
                            <p class="mt-3 inline-block rounded-full bg-leaf-50 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-leaf-700 dark:bg-leaf-950 dark:text-leaf-300">
                                {{ $therapist['availability'] === 'today' ? __('common.available_today') : __('common.available_tomorrow') }}
                            </p>
                        </article>
                    @endforeach
                </div>
            </section>

            {{-- Latest Articles --}}
            <section aria-labelledby="latest-articles">
                <x-section-heading id="latest-articles" :title="__('home.latest_articles')" :href="route('article.index')" />
                <div class="grid gap-5 sm:grid-cols-3">
                    @foreach ($articles as $article)
                        <article class="group overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm transition-all hover:-translate-y-1 hover:border-ink-200 hover:shadow-xl dark:border-ink-800 dark:bg-ink-900 dark:hover:border-ink-700">
                            <a href="{{ ! empty($article['slug']) ? route('article.show', $article['slug']) : route('article.index') }}" class="block">
                                <div class="flex h-32 items-center justify-center bg-gradient-to-br {{ $article['tone'] }} shadow-[inset_0_0_40px_rgba(0,0,0,0.1)]">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-10 text-white/50 transition duration-500 group-hover:scale-110 group-hover:text-white/70" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25c-2.5-1.7-5.5-1.9-8-.75v13c2.5-1.15 5.5-.95 8 .75 2.5-1.7 5.5-1.9 8-.75v-13c-2.5-1.15-5.5-.95-8 .75Zm0 0V19.5"/></svg>
                                </div>
                                <div class="p-5">
                                    <div class="flex items-center gap-1.5 text-ember-600 dark:text-ember-400">
                                        <x-article-category-icon :category="$article['category']" class="size-3.5 shrink-0" />
                                        <span class="text-[10px] font-black uppercase tracking-widest">{{ $article['category'] }}</span>
                                    </div>
                                    <h3 class="mt-2 text-[15px] font-black leading-snug text-ink-950 transition group-hover:text-ember-600 dark:text-ink-50 dark:group-hover:text-ember-400">{{ $article['title'] }}</h3>
                                    <p class="mt-3 text-[11px] font-semibold text-ink-400 dark:text-ink-300">{{ $article['date'] }}</p>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>

            {{-- Latest Reviews --}}
            <section aria-labelledby="latest-reviews">
                <x-section-heading id="latest-reviews" :title="__('home.latest_reviews')" :href="url('/review')" accent="leaf" />
                <div class="grid gap-5 sm:grid-cols-3">
                    @foreach ($latestReviews as $review)
                        <article class="group rounded-2xl border border-ink-100 bg-white p-5 shadow-sm transition-all hover:-translate-y-1 hover:border-ink-200 hover:shadow-xl dark:border-ink-800 dark:bg-ink-900 dark:hover:border-ink-700">
                            <div class="flex items-center gap-3">
                                <span class="flex size-11 shrink-0 items-center justify-center rounded-full text-[15px] font-black {{ $review['tone'] }}">{{ $review['initials'] }}</span>
                                <div class="min-w-0">
                                    <p class="truncate text-[15px] font-black text-ink-950 dark:text-ink-50">{{ $review['reviewer'] }}</p>
                                    <p class="text-[11px] text-ink-400 dark:text-ink-300">{{ __('home.reviewed') }} <span class="font-bold text-ink-600 dark:text-ink-300">{{ $review['target'] }}</span></p>
                                </div>
                            </div>
                            <x-rating :value="$review['rating']" size="size-3.5" class="mt-3.5" />
                            <p class="mt-2.5 text-[13px] leading-relaxed font-medium text-ink-600 dark:text-ink-300">“{{ $review['excerpt'] }}”</p>
                            <p class="mt-3 text-[11px] font-semibold text-ink-400 dark:text-ink-300">{{ $review['time'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            {{-- Promos & Deals --}}
            <section aria-labelledby="promos-deals">
                <x-section-heading id="promos-deals" :title="__('home.promos_deals')" :href="route('promo.index')" />
                <div class="grid gap-5 sm:grid-cols-3">
                    @foreach ($promos as $promo)
                        <article class="group overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm transition-all hover:-translate-y-1 hover:border-ink-200 hover:shadow-xl dark:border-ink-800 dark:bg-ink-900 dark:hover:border-ink-700">
                            <div class="bg-gradient-to-r {{ $promo['tone'] }} px-5 py-6">
                                <p class="text-[26px] font-black tracking-tight text-white">{{ $promo['offer'] }}</p>
                            </div>
                            <div class="p-5">
                                <h3 class="text-[15px] font-black text-ink-950 dark:text-ink-50">{{ $promo['business'] }}</h3>
                                <p class="mt-1.5 text-[13px] text-ink-600 font-medium dark:text-ink-300">{{ $promo['description'] }}</p>
                                <p class="mt-3 text-[11px] font-semibold text-ink-400 dark:text-ink-300">{{ __('home.valid_until', ['date' => $promo['valid_until']]) }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            {{-- Wellness Finds --}}
            <section aria-labelledby="wellness-finds">
                <x-section-heading id="wellness-finds" :title="__('home.wellness_finds')" :href="url('/directory/product')" accent="leaf" />
                <div class="grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-2 xl:grid-cols-4">
                    @foreach ($wellnessFinds as $product)
                        <article class="group min-w-0 rounded-2xl border border-ink-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg dark:border-ink-800 dark:bg-ink-900">
                            <div class="flex h-24 items-center justify-center rounded-xl bg-gradient-to-br {{ $product['tone'] }}">
                                <x-pictogram :name="$product['icon']" class="size-9 transition group-hover:scale-110" />
                            </div>
                            <h3 class="mt-3 truncate text-sm font-bold leading-snug text-ink-950 dark:text-ink-50">{{ $product['name'] }}</h3>
                            <div class="mt-1.5 flex items-center justify-between">
                                <p class="text-sm font-black text-ember-600 dark:text-ember-400">{{ $product['price'] }}</p>
                                <div class="flex items-center gap-1 text-xs text-ink-500 dark:text-ink-400">
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-3.5 text-ember-400" aria-hidden="true"><path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.28 3.95a1 1 0 0 0 .95.69h4.16c.97 0 1.37 1.24.59 1.81l-3.37 2.44a1 1 0 0 0-.36 1.12l1.28 3.95c.3.92-.75 1.69-1.54 1.12l-3.36-2.44a1 1 0 0 0-1.18 0l-3.36 2.44c-.79.57-1.84-.2-1.54-1.12l1.28-3.95a1 1 0 0 0-.36-1.12L2.05 9.38c-.78-.57-.38-1.81.6-1.81h4.15a1 1 0 0 0 .95-.69l1.3-3.95Z"/></svg>
                                    {{ number_format($product['rating'], 1) }}
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            {{-- Trending --}}
            <div class="grid gap-8 md:grid-cols-2">
                <section aria-labelledby="trending-spas">
                    <x-section-heading id="trending-spas" :title="__('home.trending_spas')" :href="url('/directory/spa')" />
                    <ol class="divide-y divide-ink-100 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm dark:divide-ink-800 dark:border-ink-800 dark:bg-ink-900">
                        @foreach ($trendingSpas as $item)
                            <li>
                                <a href="{{ route('spa.show', ['establishment_slug' => $item['slug']]) }}" class="flex items-center gap-4 px-4 py-3.5 transition hover:bg-ink-50 dark:hover:bg-ink-800">
                                    <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-ember-50 text-sm font-black text-ember-600 dark:bg-ember-950 dark:text-ember-400">{{ $item['rank'] }}</span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-sm font-bold text-ink-950 dark:text-ink-50">{{ $item['name'] }}</span>
                                        <span class="block text-xs text-ink-400 dark:text-ink-300">{{ $item['area'] }}</span>
                                    </span>
                                    <span class="flex shrink-0 items-center gap-1 text-xs">
                                        <span class="font-bold text-ink-900 dark:text-ink-100">{{ number_format($item['rating'], 1) }}</span>
                                        <x-rating :value="$item['rating']" size="size-3" />
                                        <span class="text-ink-400 dark:text-ink-300">({{ $item['review_count'] }})</span>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </section>

                <section aria-labelledby="trending-therapists">
                    <x-section-heading id="trending-therapists" :title="__('home.trending_therapists')" :href="url('/directory/therapist')" accent="leaf" />
                    <ol class="divide-y divide-ink-100 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm dark:divide-ink-800 dark:border-ink-800 dark:bg-ink-900">
                        @foreach ($trendingTherapists as $item)
                            <li>
                                <a href="{{ url('/directory/therapist') }}" class="flex items-center gap-4 px-4 py-3.5 transition hover:bg-ink-50 dark:hover:bg-ink-800">
                                    <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-leaf-50 text-sm font-black text-leaf-600 dark:bg-leaf-950 dark:text-leaf-400">{{ $item['rank'] }}</span>
                                    <span class="flex size-9 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $item['tone'] }}">{{ $item['initials'] }}</span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-sm font-bold text-ink-950 dark:text-ink-50">{{ $item['name'] }}</span>
                                        <span class="block text-xs text-ink-400 dark:text-ink-300">{{ $item['area'] }}</span>
                                    </span>
                                    <span class="flex shrink-0 items-center gap-1 text-xs">
                                        <span class="font-bold text-ink-900 dark:text-ink-100">{{ number_format($item['rating'], 1) }}</span>
                                        <x-rating :value="$item['rating']" size="size-3" />
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </section>
            </div>

            {{-- New & Freshly Updated --}}
            <div class="grid gap-8 md:grid-cols-2">
                <section aria-labelledby="new-listings">
                    <x-section-heading id="new-listings" :title="__('home.new_on_platform')" :href="url('/directory/new')" />
                    <ul class="divide-y divide-ink-100 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm dark:divide-ink-800 dark:border-ink-800 dark:bg-ink-900">
                        @foreach ($newListings as $listing)
                            <li class="flex items-center gap-4 px-4 py-3.5">
                                <span class="rounded-full px-2.5 py-0.5 text-[0.65rem] font-bold uppercase tracking-wide {{ $listing['type'] === 'Spa' ? 'bg-ink-100 text-ink-700 dark:bg-ink-800 dark:text-ink-200' : 'bg-leaf-100 text-leaf-700 dark:bg-leaf-900 dark:text-leaf-300' }}">{{ $listing['type'] }}</span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-bold text-ink-950 dark:text-ink-50">{{ $listing['name'] }}</span>
                                    <span class="block truncate text-xs text-ink-400 dark:text-ink-300">{{ $listing['area'] }}</span>
                                </span>
                                <span class="shrink-0 text-xs text-ink-400 dark:text-ink-300">{{ $listing['added'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section aria-labelledby="updated-profiles">
                    <x-section-heading id="updated-profiles" :title="__('home.freshly_updated')" :href="url('/directory/updated')" accent="leaf" />
                    <ul class="divide-y divide-ink-100 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm dark:divide-ink-800 dark:border-ink-800 dark:bg-ink-900">
                        @foreach ($updatedProfiles as $profile)
                            <li class="px-4 py-3.5">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="truncate text-sm font-bold text-ink-950 dark:text-ink-50">{{ $profile['name'] }}</p>
                                    <p class="shrink-0 text-xs text-ink-400 dark:text-ink-300">{{ $profile['updated'] }}</p>
                                </div>
                                <p class="mt-0.5 truncate text-xs text-ink-500 dark:text-ink-400">{{ $profile['change'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                </section>
            </div>
        </div>

        {{-- ===================== Sidebar ===================== --}}
        <aside class="min-w-0 space-y-4">

            {{-- Premium Spotlight (sponsored) --}}
            <section aria-labelledby="spotlight-heading" class="overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm dark:border-ink-800 dark:bg-ink-900">
                <div class="flex items-center justify-between px-5 pt-4 pb-1">
                    <h2 id="spotlight-heading" class="text-[15px] font-black text-ink-950 dark:text-ink-50">{{ __('home.premium_spotlight') }}</h2>
                    <span class="rounded-full bg-ember-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-widest text-ember-600 dark:bg-ember-950 dark:text-ember-400">{{ __('common.sponsored') }}</span>
                </div>
                <div class="p-5">
                    <a href="{{ route('spa.show', ['establishment_slug' => 'the-resting-leaf']) }}" class="group block">
                        <div class="relative flex h-32 items-center justify-center rounded-xl bg-gradient-to-br from-leaf-700 via-leaf-900 to-ink-950 shadow-[inset_0_0_40px_rgba(0,0,0,0.2)]">
                            <x-pictogram name="leaf" class="size-12 text-white/50 transition duration-500 group-hover:scale-110 group-hover:text-white/70" />
                            <span class="absolute left-3 top-3 rounded-full bg-ember-500 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-widest text-white">{{ __('common.premium') }}</span>
                        </div>
                        <h3 class="mt-4 text-base font-black tracking-tight text-ink-950 transition group-hover:text-ember-600 dark:text-ink-50 dark:group-hover:text-ember-400">The Resting Leaf</h3>
                    </a>
                    <div class="mt-1.5 flex items-center gap-1.5 text-sm">
                        <span class="font-black text-ink-900 dark:text-ink-100">4.8</span>
                        <x-rating :value="4.8" size="size-4" />
                        <span class="font-semibold text-ink-400 dark:text-ink-300">(342)</span>
                    </div>
                    <p class="mt-2 text-[13px] font-semibold text-ink-500 dark:text-ink-400">Mandaluyong City, Metro Manila</p>
                    <p class="mt-1 text-xs text-ink-500 dark:text-ink-400"><span class="font-bold text-ember-600 dark:text-ember-400">{{ __('home.signature') }}:</span> Relaxation, Aromatherapy, Hot Stone</p>
                    <a href="{{ route('spa.show', ['establishment_slug' => 'the-resting-leaf']) }}" class="mt-5 block rounded-xl border-2 border-ember-100 px-4 py-2.5 text-center text-sm font-black text-ember-600 transition hover:bg-ember-50 hover:border-ember-200 dark:border-ember-800 dark:text-ember-400 dark:hover:bg-ember-950 dark:hover:border-ember-700">
                        {{ __('common.view_profile') }}
                    </a>
                </div>
            </section>

            {{-- Community Pulse --}}
            <x-community-pulse />

            {{-- Campus Quiz --}}
            <section aria-labelledby="quiz-heading" class="rounded-2xl border border-ink-100 bg-white p-4 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                <div class="flex items-center gap-2">
                    <span class="flex size-8 items-center justify-center rounded-lg bg-leaf-100 dark:bg-leaf-900">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4.5 text-leaf-700 dark:text-leaf-300" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3 2 8l10 5 10-5-10-5Zm-6 7.5V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-5.5"/></svg>
                    </span>
                    <h2 id="quiz-heading" class="text-base font-bold text-ink-950 dark:text-ink-50">{{ __('home.campus_quiz') }}</h2>
                </div>
                <form data-quiz data-quiz-answer="hot_stone" class="mt-3">
                    <p class="text-sm font-semibold text-ink-700 dark:text-ink-200">{{ __('home.quiz_question') }}</p>
                    <div class="mt-3 space-y-2">
                        @foreach (['swedish' => __('home.quiz_option_swedish'), 'hot_stone' => __('home.quiz_option_hot_stone'), 'shiatsu' => __('home.quiz_option_shiatsu'), 'hilot' => __('home.quiz_option_hilot')] as $value => $label)
                            <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border border-ink-200 px-3.5 py-2.5 text-sm text-ink-700 transition hover:border-leaf-300 hover:bg-leaf-50 has-checked:border-leaf-400 has-checked:bg-leaf-50 dark:border-ink-700 dark:text-ink-200 dark:hover:border-leaf-800 dark:hover:bg-leaf-950 dark:has-checked:border-leaf-600 dark:has-checked:bg-leaf-950">
                                <input type="radio" name="quiz_answer" value="{{ $value }}" class="size-4 accent-leaf-600">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                    <button type="submit" class="mt-3 w-full rounded-xl bg-leaf-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-leaf-700">{{ __('home.quiz_submit') }}</button>
                    <p data-quiz-correct hidden class="mt-3 rounded-xl bg-leaf-50 px-4 py-3 text-sm font-semibold text-leaf-800 dark:bg-leaf-950 dark:text-leaf-100">{{ __('home.quiz_correct') }}</p>
                    <p data-quiz-incorrect hidden class="mt-3 rounded-xl bg-ember-50 px-4 py-3 text-sm font-semibold text-ember-800 dark:bg-ember-950 dark:text-ember-100">{{ __('home.quiz_incorrect') }}</p>
                </form>
                <a href="{{ route('campus.index') }}" class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-leaf-700 transition hover:text-leaf-800 dark:text-leaf-300 dark:hover:text-leaf-100">
                    {{ __('home.quiz_more') }}
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-3.5" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h9.69L10.22 6.03a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                </a>
            </section>

            {{-- Quote of the Day --}}
            <x-quote-panel :quote="$quote" />


            {{-- Upcoming Campaign --}}
            <section aria-labelledby="campaign-heading" class="relative overflow-hidden rounded-2xl bg-ink-950 p-4 text-white shadow-md">
                <div class="pointer-events-none absolute inset-0" aria-hidden="true">
                    <div class="absolute -bottom-14 -right-10 size-40 rounded-full bg-ember-500/25 blur-2xl"></div>
                    <div class="absolute -left-10 -top-14 size-36 rounded-full bg-leaf-500/20 blur-2xl"></div>
                </div>
                <div class="relative">
                    <h2 id="campaign-heading" class="text-xs font-bold uppercase tracking-wider text-ink-300">{{ __('home.campaign_title') }}</h2>
                    <p class="mt-2 text-2xl font-black leading-tight">{{ __('home.campaign_world_massage_day') }}</p>
                    <p class="text-lg font-bold text-ember-400">July 11, {{ date('Y') + 1 }}</p>
                    <p class="mt-1.5 text-sm text-ink-200">{{ __('home.campaign_tagline') }}</p>
                    <a href="{{ url('/campaign') }}" class="mt-4 inline-block rounded-xl bg-white/10 px-4 py-2 text-sm font-bold backdrop-blur transition hover:bg-white/20">{{ __('common.learn_more') }}</a>
                </div>
            </section>
        </aside>
    </div>

    {{-- Campus banner --}}
    <section aria-labelledby="campus-banner" class="relative mt-9 overflow-hidden rounded-3xl bg-ink-950 px-6 py-8 text-white sm:px-10">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -right-24 -top-24 size-80 rounded-full bg-ember-500/15 blur-3xl"></div>
            <div class="absolute -bottom-24 left-1/4 size-64 rounded-full bg-leaf-500/15 blur-3xl"></div>
        </div>
        <div class="relative grid items-center gap-8 lg:grid-cols-2">
            <div>
                <h2 id="campus-banner" class="text-3xl font-black tracking-tight">{{ __('home.campus_banner_title') }}</h2>
                <p class="mt-2 max-w-md text-ink-200">{{ __('home.campus_banner_subtitle') }}</p>
                <a href="{{ route('campus.index') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-ember-500 px-6 py-3 text-sm font-bold text-white shadow-md shadow-ember-500/30 transition hover:bg-ember-600">
                    {{ __('home.campus_banner_button') }}
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h9.69L10.22 6.03a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                </a>
            </div>
            <ul class="space-y-4">
                @foreach ([
                    ['title' => __('home.campus_banner_lessons'), 'text' => __('home.campus_banner_lessons_text'), 'icon' => 'M12 3 2 8l10 5 10-5-10-5Zm-6 7.5V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-5.5'],
                    ['title' => __('home.campus_banner_badges'), 'text' => __('home.campus_banner_badges_text'), 'icon' => 'M12 15a6 6 0 1 0 0-12 6 6 0 0 0 0 12Zm0 0v6m0-6-3.5 5M12 15l3.5 5'],
                    ['title' => __('home.campus_banner_everyone'), 'text' => __('home.campus_banner_everyone_text'), 'icon' => 'M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1'],
                ] as $point)
                    <li class="flex items-start gap-3.5">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-white/10">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="size-5 text-ember-400" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $point['icon'] }}"/></svg>
                        </span>
                        <div>
                            <p class="font-bold">{{ $point['title'] }}</p>
                            <p class="text-sm text-ink-300">{{ $point['text'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
</div>
@endsection
