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
            <div class="absolute inset-0 bg-gradient-to-r from-ink-950 via-ink-950/85 to-ink-950/30"></div>
            <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-ink-950/80 to-transparent"></div>
        </div>
    @else
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -right-32 -top-40 size-[32rem] rounded-full bg-ember-500/15 blur-3xl"></div>
            <div class="absolute -bottom-48 -left-24 size-[28rem] rounded-full bg-leaf-500/15 blur-3xl"></div>
            <div class="absolute right-1/4 top-1/3 size-64 rounded-full bg-ink-500/20 blur-3xl"></div>
            <svg class="absolute -right-8 bottom-0 h-72 w-72 text-white/[0.04]" viewBox="0 0 24 24" fill="currentColor"><path d="M6 15C6 8 11 4 19 4c0 8-4 13-11 13-1 0-2-.5-2-2Z"/></svg>
        </div>
    @endif

    <div class="relative mx-auto max-w-[1600px] px-4 pb-8 pt-7 sm:px-6 lg:px-8 lg:pt-10">
        <div class="max-w-2xl">
            <h1 class="text-balance text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl">
                {{ __('home.hero_title_find') }}
                <span class="text-ember-400">{{ __('home.hero_title_massage') }}</span><span class="text-ember-400">.</span>
            </h1>
            <p class="mt-2 max-w-xl text-base text-ink-200 sm:text-lg">{{ __('home.hero_subtitle') }}</p>
        </div>

        <div class="mt-6 flex flex-wrap gap-1" role="tablist" aria-label="{{ __('home.search_legend') }}">
            @foreach ([
                ['type' => 'spa', 'label' => __('home.search_tab_spas'), 'placeholder' => __('home.search_placeholder_spa')],
                ['type' => 'therapist', 'label' => __('home.search_tab_therapists'), 'placeholder' => __('home.search_placeholder_therapist')],
                ['type' => 'service', 'label' => __('home.search_tab_services'), 'placeholder' => __('home.search_placeholder_service')],
                ['type' => 'article', 'label' => __('home.search_tab_articles'), 'placeholder' => __('home.search_placeholder_article')],
                ['type' => 'product', 'label' => __('home.search_tab_products'), 'placeholder' => __('home.search_placeholder_product')],
            ] as $tab)
                <button type="button" role="tab" data-search-tab data-type="{{ $tab['type'] }}" data-placeholder="{{ $tab['placeholder'] }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                        class="{{ $loop->first ? 'bg-white text-ink-950' : 'bg-white/10 text-white hover:bg-white/20' }} rounded-t-xl px-4 py-2.5 text-sm font-bold backdrop-blur transition">
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        <form action="{{ url('/search') }}" method="get" class="rounded-2xl rounded-tl-none bg-white p-3 shadow-2xl shadow-ink-950/40 sm:p-4">
            <input type="hidden" name="type" value="spa" data-search-type>
            <fieldset>
                <legend class="sr-only">{{ __('home.search_legend') }}</legend>
                <div class="flex flex-col gap-3 lg:flex-row">
                    <label class="flex flex-1 items-center gap-3 rounded-xl border border-ink-200 px-4 py-3 transition focus-within:border-ember-400 focus-within:ring-2 focus-within:ring-ember-100">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-5 shrink-0 text-ink-400" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="m20 20-3.5-3.5"/></svg>
                        <span class="sr-only">{{ __('home.search_placeholder') }}</span>
                        <input type="search" name="q" data-search-query placeholder="{{ __('home.search_placeholder_spa') }}" class="w-full border-0 p-0 text-sm text-ink-950 placeholder-ink-400 focus:outline-none focus:ring-0">
                    </label>
                    <label class="flex flex-1 items-center gap-3 rounded-xl border border-ink-200 px-4 py-3 transition focus-within:border-ember-400 focus-within:ring-2 focus-within:ring-ember-100 lg:max-w-xs">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-5 shrink-0 text-ink-400" aria-hidden="true"><path d="M12 21s-7-5.5-7-11a7 7 0 1 1 14 0c0 5.5-7 11-7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                        <span class="sr-only">{{ __('home.location_placeholder') }}</span>
                        <input type="text" name="location" placeholder="{{ __('home.location_placeholder') }}" class="w-full border-0 p-0 text-sm text-ink-950 placeholder-ink-400 focus:outline-none focus:ring-0">
                    </label>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-ember-500 px-8 py-3 text-sm font-bold text-white shadow-md shadow-ember-500/30 transition hover:bg-ember-600">
                        {{ __('home.search_button') }}
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="size-4" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="m20 20-3.5-3.5"/></svg>
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
                        <label class="inline-flex cursor-pointer items-center gap-1.5 rounded-full border border-ink-200 px-3.5 py-1.5 text-xs font-semibold text-ink-700 transition hover:border-ember-300 hover:bg-ember-50 hover:text-ember-700 has-checked:border-ember-400 has-checked:bg-ember-50 has-checked:text-ember-700">
                            <input type="checkbox" name="{{ $filter['name'] }}" value="1" class="sr-only">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-3.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $filter['icon'] }}"/></svg>
                            {{ $filter['label'] }}
                        </label>
                    @endforeach
                </div>
            </fieldset>
        </form>
    </div>
</section>

{{-- ===================== Main grid ===================== --}}
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_20rem] xl:grid-cols-[minmax(0,1fr)_22rem]">
        <div class="min-w-0 space-y-7">

            {{-- Featured Spas --}}
            <section aria-labelledby="featured-spas">
                <x-section-heading id="featured-spas" :title="__('home.featured_spas')" :href="url('/directory/spa')" />
                <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-2 xl:grid-cols-4">
                    @foreach ($featuredSpas as $spa)
                        <article class="group min-w-0 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                            <a href="{{ route('spa.show', ['establishment_slug' => $spa['slug']]) }}" class="block">
                                <div class="relative flex h-28 items-center justify-center bg-gradient-to-br {{ $spa['gradient'] }}">
                                    <x-pictogram :name="$spa['icon']" class="size-11 text-white/60 transition group-hover:scale-110" />
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
                                <div class="p-3">
                                    <h3 class="text-sm font-bold text-ink-950 transition group-hover:text-ember-600 truncate">{{ $spa['name'] }}</h3>
                                    <div class="mt-1 flex items-center gap-1 text-xs">
                                        <span class="font-bold text-ink-900">{{ number_format($spa['rating'], 1) }}</span>
                                        <x-rating :value="$spa['rating']" size="size-3" />
                                        <span class="text-ink-400">({{ $spa['review_count'] }})</span>
                                    </div>
                                    <p class="mt-1 truncate text-xs text-ink-500">{{ $spa['area'] }}</p>
                                    <p class="mt-0.5 truncate text-xs text-ink-500">{{ $spa['services'] }}</p>
                                    <p class="mt-1.5 text-[0.7rem] font-semibold">
                                        <span class="text-leaf-600">{{ __('common.open') }}</span>
                                        <span class="text-ink-400"> · {{ __('common.closes_at', ['time' => $spa['closes']]) }}</span>
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
                        <article class="min-w-0 rounded-2xl border border-ink-100 bg-white p-4 text-center shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                            <span class="mx-auto flex size-16 items-center justify-center rounded-full text-lg font-bold {{ $therapist['tone'] }}">{{ $therapist['initials'] }}</span>
                            <h3 class="mt-3 text-sm font-bold text-ink-950">{{ $therapist['name'] }}</h3>
                            <div class="mt-1 flex items-center justify-center gap-1 text-xs">
                                <span class="font-bold text-ink-900">{{ number_format($therapist['rating'], 1) }}</span>
                                <x-rating :value="$therapist['rating']" size="size-3" />
                                <span class="text-ink-400">({{ $therapist['review_count'] }})</span>
                            </div>
                            <p class="mt-1.5 text-xs text-ink-500">{{ $therapist['specialties'] }}</p>
                            <p class="mt-0.5 truncate text-xs text-ink-400">{{ $therapist['area'] }}</p>
                            <p class="mt-2 inline-block rounded-full bg-leaf-50 px-2.5 py-0.5 text-[0.65rem] font-bold text-leaf-700">
                                {{ $therapist['availability'] === 'today' ? __('common.available_today') : __('common.available_tomorrow') }}
                            </p>
                        </article>
                    @endforeach
                </div>
            </section>

            {{-- Browse by Area / Massage Type --}}
            <div class="grid gap-8 md:grid-cols-2">
                <section aria-labelledby="browse-area">
                    <x-section-heading id="browse-area" :title="__('home.browse_by_area')" :href="url('/directory/area')" />
                    <ul class="flex flex-wrap gap-2">
                        @foreach ($areas as $area)
                            <li>
                                <a href="{{ url('/directory/area/'.\Illuminate\Support\Str::slug($area)) }}" class="inline-block rounded-full border border-ink-200 bg-white px-4 py-2 text-sm font-semibold text-ink-700 transition hover:border-ember-300 hover:bg-ember-50 hover:text-ember-700">
                                    {{ $area }}
                                </a>
                            </li>
                        @endforeach
                        <li>
                            <a href="{{ url('/directory/area') }}" class="inline-flex items-center gap-1 rounded-full bg-ink-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-ink-800">
                                {{ __('home.more_areas') }}
                                <svg viewBox="0 0 20 20" fill="currentColor" class="size-3.5" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h9.69L10.22 6.03a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                            </a>
                        </li>
                    </ul>
                </section>

                <section aria-labelledby="browse-type">
                    <x-section-heading id="browse-type" :title="__('home.browse_by_type')" :href="url('/directory/type-spa')" accent="leaf" />
                    <ul class="grid grid-cols-4 gap-2.5">
                        @foreach ($massageTypes as $type)
                            <li>
                                <a href="{{ url('/directory/type-spa/'.\Illuminate\Support\Str::slug($type['name'])) }}" class="group flex flex-col items-center gap-1.5 rounded-xl border border-ink-100 bg-white px-2 py-3 text-center shadow-sm transition hover:border-leaf-300 hover:bg-leaf-50">
                                    <x-pictogram :name="$type['icon']" class="size-6 text-ink-500 transition group-hover:text-leaf-600" />
                                    <span class="text-xs font-semibold text-ink-700 group-hover:text-leaf-800">{{ $type['name'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            </div>

            {{-- Latest Articles --}}
            <section aria-labelledby="latest-articles">
                <x-section-heading id="latest-articles" :title="__('home.latest_articles')" :href="route('article.index')" />
                <div class="grid gap-5 sm:grid-cols-3">
                    @foreach ($articles as $article)
                        <article class="group overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                            <a href="{{ route('article.index') }}" class="block">
                                <div class="flex h-28 items-center justify-center bg-gradient-to-br {{ $article['tone'] }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-9 text-white/70" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25c-2.5-1.7-5.5-1.9-8-.75v13c2.5-1.15 5.5-.95 8 .75 2.5-1.7 5.5-1.9 8-.75v-13c-2.5-1.15-5.5-.95-8 .75Zm0 0V19.5"/></svg>
                                </div>
                                <div class="p-4">
                                    <p class="text-xs font-bold uppercase tracking-wide text-ember-600">{{ $article['category'] }}</p>
                                    <h3 class="mt-1.5 text-sm font-bold leading-snug text-ink-950 transition group-hover:text-ember-600">{{ $article['title'] }}</h3>
                                    <p class="mt-2 text-xs text-ink-400">{{ $article['date'] }}</p>
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
                        <article class="rounded-2xl border border-ink-100 bg-white p-4 shadow-sm">
                            <div class="flex items-center gap-3">
                                <span class="flex size-10 shrink-0 items-center justify-center rounded-full text-sm font-bold {{ $review['tone'] }}">{{ $review['initials'] }}</span>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-bold text-ink-950">{{ $review['reviewer'] }}</p>
                                    <p class="text-xs text-ink-400">{{ __('home.reviewed') }} <span class="font-semibold text-ink-600">{{ $review['target'] }}</span></p>
                                </div>
                            </div>
                            <x-rating :value="$review['rating']" class="mt-3" />
                            <p class="mt-2 text-sm leading-relaxed text-ink-600">“{{ $review['excerpt'] }}”</p>
                            <p class="mt-2.5 text-xs text-ink-400">{{ $review['time'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            {{-- Promos & Deals --}}
            <section aria-labelledby="promos-deals">
                <x-section-heading id="promos-deals" :title="__('home.promos_deals')" :href="route('promo.index')" />
                <div class="grid gap-5 sm:grid-cols-3">
                    @foreach ($promos as $promo)
                        <article class="overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                            <div class="bg-gradient-to-r {{ $promo['tone'] }} px-4 py-5">
                                <p class="text-2xl font-black tracking-tight text-white">{{ $promo['offer'] }}</p>
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-bold text-ink-950">{{ $promo['business'] }}</h3>
                                <p class="mt-1 text-sm text-ink-600">{{ $promo['description'] }}</p>
                                <p class="mt-2.5 text-xs font-semibold text-ink-400">{{ __('home.valid_until', ['date' => $promo['valid_until']]) }}</p>
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
                        <article class="group min-w-0 rounded-2xl border border-ink-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                            <div class="flex h-24 items-center justify-center rounded-xl bg-gradient-to-br {{ $product['tone'] }}">
                                <x-pictogram :name="$product['icon']" class="size-9 transition group-hover:scale-110" />
                            </div>
                            <h3 class="mt-3 truncate text-sm font-bold leading-snug text-ink-950">{{ $product['name'] }}</h3>
                            <div class="mt-1.5 flex items-center justify-between">
                                <p class="text-sm font-black text-ember-600">{{ $product['price'] }}</p>
                                <div class="flex items-center gap-1 text-xs text-ink-500">
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
                    <ol class="divide-y divide-ink-100 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm">
                        @foreach ($trendingSpas as $item)
                            <li>
                                <a href="{{ route('spa.show', ['establishment_slug' => $item['slug']]) }}" class="flex items-center gap-4 px-4 py-3.5 transition hover:bg-ink-50">
                                    <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-ember-50 text-sm font-black text-ember-600">{{ $item['rank'] }}</span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-sm font-bold text-ink-950">{{ $item['name'] }}</span>
                                        <span class="block text-xs text-ink-400">{{ $item['area'] }}</span>
                                    </span>
                                    <span class="flex shrink-0 items-center gap-1 text-xs">
                                        <span class="font-bold text-ink-900">{{ number_format($item['rating'], 1) }}</span>
                                        <x-rating :value="$item['rating']" size="size-3" />
                                        <span class="text-ink-400">({{ $item['review_count'] }})</span>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </section>

                <section aria-labelledby="trending-therapists">
                    <x-section-heading id="trending-therapists" :title="__('home.trending_therapists')" :href="url('/directory/therapist')" accent="leaf" />
                    <ol class="divide-y divide-ink-100 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm">
                        @foreach ($trendingTherapists as $item)
                            <li>
                                <a href="{{ url('/directory/therapist') }}" class="flex items-center gap-4 px-4 py-3.5 transition hover:bg-ink-50">
                                    <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-leaf-50 text-sm font-black text-leaf-600">{{ $item['rank'] }}</span>
                                    <span class="flex size-9 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $item['tone'] }}">{{ $item['initials'] }}</span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-sm font-bold text-ink-950">{{ $item['name'] }}</span>
                                        <span class="block text-xs text-ink-400">{{ $item['area'] }}</span>
                                    </span>
                                    <span class="flex shrink-0 items-center gap-1 text-xs">
                                        <span class="font-bold text-ink-900">{{ number_format($item['rating'], 1) }}</span>
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
                    <ul class="divide-y divide-ink-100 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm">
                        @foreach ($newListings as $listing)
                            <li class="flex items-center gap-4 px-4 py-3.5">
                                <span class="rounded-full px-2.5 py-0.5 text-[0.65rem] font-bold uppercase tracking-wide {{ $listing['type'] === 'Spa' ? 'bg-ink-100 text-ink-700' : 'bg-leaf-100 text-leaf-700' }}">{{ $listing['type'] }}</span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-bold text-ink-950">{{ $listing['name'] }}</span>
                                    <span class="block truncate text-xs text-ink-400">{{ $listing['area'] }}</span>
                                </span>
                                <span class="shrink-0 text-xs text-ink-400">{{ $listing['added'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section aria-labelledby="updated-profiles">
                    <x-section-heading id="updated-profiles" :title="__('home.freshly_updated')" :href="url('/directory/updated')" accent="leaf" />
                    <ul class="divide-y divide-ink-100 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm">
                        @foreach ($updatedProfiles as $profile)
                            <li class="px-4 py-3.5">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="truncate text-sm font-bold text-ink-950">{{ $profile['name'] }}</p>
                                    <p class="shrink-0 text-xs text-ink-400">{{ $profile['updated'] }}</p>
                                </div>
                                <p class="mt-0.5 truncate text-xs text-ink-500">{{ $profile['change'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                </section>
            </div>
        </div>

        {{-- ===================== Sidebar ===================== --}}
        <aside class="min-w-0 space-y-4">

            {{-- Wellness Journey stats --}}
            <section aria-labelledby="stats-heading" class="relative overflow-hidden rounded-2xl bg-ink-950 p-4 text-white shadow-md">
                <svg class="pointer-events-none absolute -right-6 -top-6 size-32 text-white/[0.06]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6 15C6 8 11 4 19 4c0 8-4 13-11 13-1 0-2-.5-2-2Z"/></svg>
                <h2 id="stats-heading" class="text-base font-bold">{{ __('home.stats_title') }}</h2>
                <p class="text-xs text-ink-300">{{ __('home.stats_subtitle') }}</p>
                <dl class="mt-3 space-y-2.5">
                    @foreach ([
                        ['value' => $stats['spas'], 'label' => __('home.stats_spas'), 'icon' => 'M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6'],
                        ['value' => $stats['therapists'], 'label' => __('home.stats_therapists'), 'icon' => 'M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1'],
                        ['value' => $stats['reviews'], 'label' => __('home.stats_reviews'), 'icon' => 'm12 3 2.7 5.6 6.3.8-4.6 4.3 1.2 6.1L12 16.9 6.4 19.8l1.2-6.1L3 9.4l6.3-.8L12 3Z'],
                        ['value' => $stats['articles'], 'label' => __('home.stats_articles'), 'icon' => 'M12 6.25c-2.5-1.7-5.5-1.9-8-.75v13c2.5-1.15 5.5-.95 8 .75 2.5-1.7 5.5-1.9 8-.75v-13c-2.5-1.15-5.5-.95-8 .75Zm0 0V19.5'],
                    ] as $stat)
                        <div class="flex items-center gap-2.5">
                            <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-white/10">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="size-4.5 text-ember-400" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/></svg>
                            </span>
                            <div>
                                <dt class="sr-only">{{ $stat['label'] }}</dt>
                                <dd class="text-lg font-black leading-tight">{{ $stat['value'] }}</dd>
                                <dd class="text-xs text-ink-300">{{ $stat['label'] }}</dd>
                            </div>
                        </div>
                    @endforeach
                </dl>
                <a href="{{ url('/statistics') }}" class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-ember-400 transition hover:text-ember-300">
                    {{ __('home.view_all_statistics') }}
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-3.5" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h9.69L10.22 6.03a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                </a>
            </section>

            {{-- Premium Spotlight (sponsored) --}}
            <section aria-labelledby="spotlight-heading" class="overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm">
                <div class="flex items-center justify-between px-4 pt-3">
                    <h2 id="spotlight-heading" class="text-base font-bold text-ink-950">{{ __('home.premium_spotlight') }}</h2>
                    <span class="rounded-full bg-ember-50 px-2.5 py-0.5 text-[0.65rem] font-bold uppercase tracking-wide text-ember-600">{{ __('common.sponsored') }}</span>
                </div>
                <div class="p-4">
                    <a href="{{ route('spa.show', ['establishment_slug' => 'the-resting-leaf']) }}" class="group block">
                        <div class="relative flex h-32 items-center justify-center rounded-xl bg-gradient-to-br from-leaf-700 via-leaf-900 to-ink-950">
                            <x-pictogram name="leaf" class="size-12 text-white/60 transition group-hover:scale-110" />
                            <span class="absolute left-3 top-3 rounded-full bg-ember-500 px-2.5 py-0.5 text-[0.65rem] font-bold uppercase tracking-wide text-white">{{ __('common.premium') }}</span>
                        </div>
                        <h3 class="mt-3 font-bold text-ink-950 transition group-hover:text-ember-600">The Resting Leaf</h3>
                    </a>
                    <div class="mt-1 flex items-center gap-1.5 text-sm">
                        <span class="font-bold text-ink-900">4.8</span>
                        <x-rating :value="4.8" />
                        <span class="text-ink-400">(342)</span>
                    </div>
                    <p class="mt-1 text-sm text-ink-500">Mandaluyong City, Metro Manila</p>
                    <p class="mt-1 text-xs text-ink-500"><span class="font-bold text-ember-600">{{ __('home.signature') }}:</span> Relaxation, Aromatherapy, Hot Stone</p>
                    <a href="{{ route('spa.show', ['establishment_slug' => 'the-resting-leaf']) }}" class="mt-4 block rounded-xl border border-ember-300 px-4 py-2 text-center text-sm font-bold text-ember-600 transition hover:bg-ember-50">
                        {{ __('common.view_profile') }}
                    </a>
                </div>
            </section>

            {{-- Community Pulse --}}
            <section aria-labelledby="pulse-heading" class="rounded-2xl border border-ink-100 bg-white p-4 shadow-sm">
                <h2 id="pulse-heading" class="text-base font-bold text-ink-950">{{ __('home.community_pulse') }}</h2>
                <form data-poll class="mt-3">
                    <div data-poll-form>
                        <p class="text-sm font-semibold text-ink-700">{{ __('home.pulse_question') }}</p>
                        <div class="mt-3 space-y-2">
                            @foreach (['weekly' => __('home.pulse_option_weekly'), 'monthly' => __('home.pulse_option_monthly'), 'occasionally' => __('home.pulse_option_occasionally'), 'first_time' => __('home.pulse_option_first_time')] as $value => $label)
                                <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border border-ink-200 px-3.5 py-2.5 text-sm text-ink-700 transition hover:border-ember-300 hover:bg-ember-50 has-checked:border-ember-400 has-checked:bg-ember-50">
                                    <input type="radio" name="pulse_answer" value="{{ $value }}" class="size-4 accent-ember-500">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                        <button type="submit" class="mt-3 w-full rounded-xl bg-ink-950 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-ink-800">{{ __('home.pulse_vote') }}</button>
                    </div>
                    <p data-poll-result hidden class="rounded-xl bg-leaf-50 px-4 py-3 text-sm font-semibold text-leaf-800">{{ __('home.pulse_thanks') }}</p>
                </form>
            </section>

            {{-- Campus Quiz --}}
            <section aria-labelledby="quiz-heading" class="rounded-2xl border border-ink-100 bg-white p-4 shadow-sm">
                <div class="flex items-center gap-2">
                    <span class="flex size-8 items-center justify-center rounded-lg bg-leaf-100">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4.5 text-leaf-700" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3 2 8l10 5 10-5-10-5Zm-6 7.5V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-5.5"/></svg>
                    </span>
                    <h2 id="quiz-heading" class="text-base font-bold text-ink-950">{{ __('home.campus_quiz') }}</h2>
                </div>
                <form data-quiz data-quiz-answer="hot_stone" class="mt-3">
                    <p class="text-sm font-semibold text-ink-700">{{ __('home.quiz_question') }}</p>
                    <div class="mt-3 space-y-2">
                        @foreach (['swedish' => __('home.quiz_option_swedish'), 'hot_stone' => __('home.quiz_option_hot_stone'), 'shiatsu' => __('home.quiz_option_shiatsu'), 'hilot' => __('home.quiz_option_hilot')] as $value => $label)
                            <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border border-ink-200 px-3.5 py-2.5 text-sm text-ink-700 transition hover:border-leaf-300 hover:bg-leaf-50 has-checked:border-leaf-400 has-checked:bg-leaf-50">
                                <input type="radio" name="quiz_answer" value="{{ $value }}" class="size-4 accent-leaf-600">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                    <button type="submit" class="mt-3 w-full rounded-xl bg-leaf-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-leaf-700">{{ __('home.quiz_submit') }}</button>
                    <p data-quiz-correct hidden class="mt-3 rounded-xl bg-leaf-50 px-4 py-3 text-sm font-semibold text-leaf-800">{{ __('home.quiz_correct') }}</p>
                    <p data-quiz-incorrect hidden class="mt-3 rounded-xl bg-ember-50 px-4 py-3 text-sm font-semibold text-ember-800">{{ __('home.quiz_incorrect') }}</p>
                </form>
                <a href="{{ route('campus.index') }}" class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-leaf-700 transition hover:text-leaf-800">
                    {{ __('home.quiz_more') }}
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-3.5" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h9.69L10.22 6.03a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                </a>
            </section>

            {{-- Quote of the Day --}}
            <section aria-labelledby="quote-heading" class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-leaf-50 to-ink-50 p-4 shadow-sm">
                <h2 id="quote-heading" class="text-base font-bold text-ink-950">{{ __('home.quote_of_the_day') }}</h2>
                <blockquote class="mt-3">
                    <svg viewBox="0 0 24 24" fill="currentColor" class="size-6 text-ember-400" aria-hidden="true"><path d="M9.6 5C6 6.7 4 9.5 4 13.1c0 3 1.8 5 4.3 5 2 0 3.6-1.5 3.6-3.5 0-1.9-1.4-3.3-3.2-3.3h-.6c.4-1.9 1.6-3.4 3.5-4.5L9.6 5Zm9 0c-3.6 1.7-5.6 4.5-5.6 8.1 0 3 1.8 5 4.3 5 2 0 3.6-1.5 3.6-3.5 0-1.9-1.4-3.3-3.2-3.3h-.6c.4-1.9 1.6-3.4 3.5-4.5L18.6 5Z"/></svg>
                    <p class="mt-2 text-base font-semibold italic leading-relaxed text-ink-800">“{{ $quote['text'] }}”</p>
                    <footer class="mt-2 text-sm text-ink-500">— {{ $quote['author'] }}</footer>
                </blockquote>
            </section>

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
