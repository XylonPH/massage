@extends('layouts.app')

@section('title', 'Exclusive Wellness Promos & Spa Deals — Massage Nexus')
@section('meta_description', 'Discover limited-time spa discounts, package vouchers, free service upgrades, and seasonal wellness bundles.')

@section('content')
{{-- Promos Hero Header --}}
<div class="bg-gradient-to-br from-ink-950 via-ember-950 to-charcoal-950 text-white">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.2em] text-ember-300">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20m5-17H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            <span>Promos & Special Offers</span>
        </div>
        <h1 class="mt-3 max-w-3xl text-3xl font-black tracking-tight sm:text-5xl">Exclusive Spa Deals & Wellness Vouchers</h1>
        <p class="mt-3 max-w-2xl text-base text-ink-200">Save on signature Swedish massages, traditional Hilot packages, couple's retreats, and home-service wellness bundles across partner establishments.</p>

        {{-- Category Pills --}}
        <div class="mt-8 flex flex-wrap gap-2">
            @foreach ([
                'all' => 'All Promos',
                'packages' => 'Spa Packages & Bundles',
                'first_timer' => 'First-Timer Specials',
                'couples' => 'Couple\'s Retreat Deals',
                'seasonal' => 'Seasonal Vouchers',
            ] as $key => $label)
                <a href="{{ route('promo.index', ['category' => $key]) }}"
                   class="rounded-xl px-4 py-2 text-xs font-bold transition {{ $activeCategory === $key ? 'bg-ember-500 text-white shadow-md' : 'bg-white/10 text-white/80 hover:bg-white/20' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>
</div>

<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 space-y-12">
    {{-- Featured Spotlight Promo Card --}}
    <section aria-labelledby="featured-promo">
        <h2 id="featured-promo" class="sr-only">Featured Promo of the Week</h2>
        <div class="relative overflow-hidden rounded-3xl border border-ember-200/80 bg-gradient-to-br from-ember-50 via-white to-leaf-50 p-6 shadow-md lg:p-8 dark:border-ember-900/60 dark:from-ember-950/60 dark:via-ink-900 dark:to-leaf-950/50">
            <div class="grid gap-6 lg:grid-cols-[1fr_20rem] lg:items-center">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-ember-500 px-3 py-1 text-xs font-black uppercase tracking-wider text-white shadow-sm">
                        <span>🔥 Spotlight Offer of the Month</span>
                    </div>
                    <h3 class="mt-4 text-2xl font-black text-ink-950 sm:text-3xl dark:text-ink-50">90-Minute Signature Hilot & Warm Herbal Compress Package</h3>
                    <p class="mt-2 text-sm text-ink-700 dark:text-ink-200">Includes full-body organic banana leaf Diagnosis, warm virgin coconut oil Hilot, aromatic herbal compress, and complimentary ginger tea ceremony.</p>

                    <div class="mt-4 flex flex-wrap items-baseline gap-3">
                        <span class="text-3xl font-black text-ember-600 dark:text-ember-400">₱1,250</span>
                        <span class="text-lg font-semibold text-ink-400 line-through dark:text-ink-500">₱1,850</span>
                        <span class="rounded-lg bg-ember-100 px-2.5 py-1 text-xs font-bold text-ember-800 dark:bg-ember-950 dark:text-ember-200">32% OFF</span>
                    </div>

                    <p class="mt-2 text-xs font-semibold text-ink-500 dark:text-ink-400">Offered by <strong>NatureTouch Wellness Spa (BGC Branch)</strong> &bull; Valid until August 31, 2026</p>
                </div>

                <div x-data="{ copied: false }" class="rounded-2xl border border-ember-200 bg-white p-5 text-center shadow-sm dark:border-ember-800 dark:bg-ink-950 space-y-3">
                    <p class="text-xs font-bold uppercase tracking-wider text-ink-500">Promo Code</p>
                    <div class="rounded-xl bg-ink-50 p-3 font-mono text-xl font-black text-ember-600 border border-dashed border-ember-300 dark:bg-ink-900 dark:text-ember-400">
                        NEXUS30
                    </div>
                    <button type="button" @click="navigator.clipboard.writeText('NEXUS30'); copied = true; setTimeout(() => copied = false, 2000)"
                            class="w-full rounded-xl bg-ember-500 py-2.5 text-xs font-bold text-white shadow transition hover:bg-ember-600">
                        <span x-show="!copied">Copy Voucher Code</span>
                        <span x-show="copied" x-cloak>✓ Code Copied!</span>
                    </button>
                    <p class="text-[11px] text-ink-400">Show this voucher or quote code upon booking.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Promo Deals Grid --}}
    <section aria-labelledby="all-promos">
        <div class="flex items-center justify-between gap-4 border-b border-ink-100 pb-4 dark:border-ink-800">
            <h2 id="all-promos" class="text-2xl font-black text-ink-950 dark:text-ink-50">Active Promo Deals</h2>
            <span class="text-xs font-bold text-ink-500 dark:text-ink-400">Showing {{ count($promos) }} Deals</span>
        </div>

        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($promos as $promo)
                @php
                    $offer = $promo['offer'] ?? $promo['title'] ?? 'Special Offer';
                    $business = $promo['business'] ?? $promo['spa'] ?? 'Partner Spa';
                    $description = $promo['description'] ?? 'Limited time promo offer.';
                    $validUntil = $promo['valid_until'] ?? $promo['validUntil'] ?? 'Dec 31, 2026';
                    $tone = $promo['tone'] ?? 'from-ember-500 to-ember-700';
                @endphp
                <article class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm transition hover:-translate-y-1 hover:border-ink-200 hover:shadow-xl dark:border-ink-800 dark:bg-ink-900">
                    <div>
                        <div class="flex h-36 items-center justify-center bg-gradient-to-br {{ $tone }} p-5 text-white shadow-[inset_0_0_40px_rgba(0,0,0,0.15)]">
                            <span class="rounded-full bg-white/20 px-3.5 py-1 text-sm font-black uppercase tracking-wider backdrop-blur">
                                {{ $offer }}
                            </span>
                        </div>

                        <div class="p-5">
                            <p class="text-xs font-bold text-leaf-700 dark:text-leaf-400">{{ $business }}</p>
                            <h3 class="mt-1 text-base font-black text-ink-950 group-hover:text-ember-600 dark:text-ink-50 dark:group-hover:text-ember-400">{{ $offer }} at {{ $business }}</h3>
                            <p class="mt-2 text-xs text-ink-600 dark:text-ink-300">{{ $description }}</p>
                        </div>
                    </div>

                    <div class="border-t border-ink-100 p-5 pt-4 dark:border-ink-800">
                        <div class="flex items-baseline justify-between text-xs">
                            <span class="font-bold text-ember-600 dark:text-ember-400">Exclusive Deal</span>
                            <span class="font-semibold text-ink-500 dark:text-ink-400">Valid til {{ $validUntil }}</span>
                        </div>

                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('spa.show', \Illuminate\Support\Str::slug($business)) }}" class="flex-1 rounded-xl border border-ink-200 bg-white py-2 text-center text-xs font-bold text-ink-800 transition hover:bg-ink-50 dark:border-ink-700 dark:bg-ink-950 dark:text-ink-200 dark:hover:bg-ink-800">
                                View Spa Profile
                            </a>
                            <button type="button" onclick="alert('Promo Code: {{ strtoupper(substr(md5($offer), 0, 8)) }}\nShow code upon booking at {{ $business }}.')"
                                    class="rounded-xl bg-ember-500 px-4 py-2 text-xs font-bold text-white shadow transition hover:bg-ember-600">
                                Claim Code
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Trust Banner --}}
    <section aria-labelledby="promo-guarantee" class="rounded-2xl border border-ink-100 bg-ink-50 p-6 text-center shadow-sm dark:border-ink-800 dark:bg-charcoal-950">
        <h3 id="promo-guarantee" class="text-base font-bold text-ink-900 dark:text-ink-100">Direct Partner Guarantees</h3>
        <p class="mt-1 max-w-xl mx-auto text-xs text-ink-600 dark:text-ink-400">All promo codes are verified directly with spa partners. No hidden booking fees or unverified third-party vouchers.</p>
    </section>
</div>
@endsection
