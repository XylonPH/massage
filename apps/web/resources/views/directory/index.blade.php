@extends('layouts.app')

@section('title', 'Massage Nexus Directory — Spas, Therapists & Services')
@section('meta_description', 'Discover verified spas, certified massage therapists, signature techniques, and wellness areas across the Philippines.')

@section('content')
{{-- Directory Hero & Search Header --}}
<div class="bg-gradient-to-br from-ink-950 via-charcoal-950 to-leaf-950 text-white">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.2em] text-ember-300">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/><path stroke-linecap="round" fill="currentColor" d="M12 7l1.5 3 3.5.5-2.5 2.5.5 3.5-3-1.5-3 1.5.5-3.5-2.5-2.5 3.5-.5Z"/></svg>
            <span>Platform Directory</span>
        </div>
        <h1 class="mt-3 max-w-3xl text-3xl font-black tracking-tight sm:text-5xl">Discover Verified Spas & Certified Therapists</h1>
        <p class="mt-3 max-w-2xl text-base text-ink-200">Explore trusted wellness establishments, home-service practitioners, traditional Hilot practitioners, and specialized bodywork across major Philippine cities.</p>

        {{-- Filter Tabs --}}
        <div class="mt-8 flex flex-wrap gap-2">
            @foreach ([
                'all' => 'All Directory',
                'spas' => 'Spas & Wellness Centers',
                'therapists' => 'Therapists & Practitioners',
                'services' => 'Massage Techniques',
                'areas' => 'Areas & Cities',
            ] as $key => $label)
                <a href="{{ route('directory.index', ['type' => $key]) }}"
                   class="rounded-xl px-4 py-2 text-xs font-bold transition {{ $activeTab === $key ? 'bg-ember-500 text-white shadow-md' : 'bg-white/10 text-white/80 hover:bg-white/20' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Search Panel --}}
        <div class="mt-6 rounded-2xl bg-white/10 p-4 backdrop-blur sm:p-5">
            <form action="{{ route('directory.index') }}" method="get" class="grid gap-3 sm:grid-cols-[1fr_1fr_auto]">
                <input type="text" name="q" placeholder="Search by name, technique, or keyword..." class="w-full rounded-xl border border-white/20 bg-white px-4 py-3 text-sm text-ink-950 shadow-sm focus:outline-none focus:ring-2 focus:ring-ember-400">
                <input type="text" name="area" placeholder="City, area, or landmark..." class="w-full rounded-xl border border-white/20 bg-white px-4 py-3 text-sm text-ink-950 shadow-sm focus:outline-none focus:ring-2 focus:ring-ember-400">
                <button type="submit" class="rounded-xl bg-ember-500 px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-ember-600">
                    Search Directory
                </button>
            </form>
        </div>
    </div>
</div>

<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 space-y-14">
    {{-- Section 1: Featured Spas --}}
    @if ($activeTab === 'all' || $activeTab === 'spas')
        <section aria-labelledby="dir-spas">
            <div class="flex items-center justify-between gap-4 border-b border-ink-100 pb-4 dark:border-ink-800">
                <div>
                    <h2 id="dir-spas" class="text-2xl font-black text-ink-950 dark:text-ink-50">Verified Spas & Wellness Centers</h2>
                    <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">Top-rated spas with verified credentials, public amenities, and signature offerings.</p>
                </div>
                <span class="rounded-full bg-leaf-100 px-3 py-1 text-xs font-bold text-leaf-800 dark:bg-leaf-950 dark:text-leaf-300">
                    {{ count($featuredSpas) }} Featured
                </span>
            </div>

            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredSpas as $spa)
                    @php
                        $name = $spa['name'] ?? 'Spa Establishment';
                        $slug = $spa['slug'] ?? \Illuminate\Support\Str::slug($name);
                        $area = $spa['area'] ?? $spa['location'] ?? 'Philippines';
                        $rating = $spa['rating'] ?? 4.5;
                        $reviews = $spa['review_count'] ?? 50;
                        $services = $spa['services'] ?? 'Swedish, Deep Tissue';
                        $closes = $spa['closes'] ?? '10:00 PM';
                        $gradient = $spa['gradient'] ?? 'from-leaf-700 via-leaf-900 to-ink-950';
                        $isVerified = !empty($spa['is_verified']);
                    @endphp
                    <article class="group relative flex flex-col overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm transition hover:-translate-y-1 hover:border-ink-200 hover:shadow-xl dark:border-ink-800 dark:bg-ink-900">
                        <div class="relative flex h-40 w-full items-center justify-center bg-gradient-to-br {{ $gradient }} p-5 text-white shadow-[inset_0_0_40px_rgba(0,0,0,0.2)]">
                            <h3 class="text-xl font-black tracking-tight text-white drop-shadow">{{ $name }}</h3>
                            <div class="absolute top-3 left-3">
                                @if ($isVerified)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-leaf-600/90 px-2.5 py-0.5 text-[11px] font-bold text-white backdrop-blur">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="size-3"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"/></svg>
                                        Verified Spa
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-1 flex-col justify-between p-5">
                            <div>
                                <div class="flex items-center justify-between gap-2">
                                    <h4 class="text-base font-black text-ink-950 group-hover:text-ember-600 dark:text-ink-50 dark:group-hover:text-ember-400">{{ $name }}</h4>
                                    <div class="flex items-center gap-1 text-sm font-bold text-amber-500">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="size-4"><path d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.064 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.83-4.401Z"/></svg>
                                        <span>{{ $rating }}</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-ink-500 dark:text-ink-400">{{ $area }}</p>
                                <p class="mt-3 text-xs text-ink-600 dark:text-ink-300"><strong>Services:</strong> {{ $services }}</p>
                            </div>
                            <div class="mt-4 border-t border-ink-100 pt-3 flex items-center justify-between text-xs text-ink-500 dark:border-ink-800 dark:text-ink-400">
                                <span>Closes at {{ $closes }}</span>
                                <a href="{{ route('spa.show', $slug) }}" class="font-bold text-ember-600 hover:underline dark:text-ember-400">View Profile &rarr;</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Section 2: Verified Therapists & Practitioners --}}
    @if ($activeTab === 'all' || $activeTab === 'therapists')
        <section aria-labelledby="dir-therapists">
            <div class="flex items-center justify-between gap-4 border-b border-ink-100 pb-4 dark:border-ink-800">
                <div>
                    <h2 id="dir-therapists" class="text-2xl font-black text-ink-950 dark:text-ink-50">Certified Massage Therapists</h2>
                    <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">Licensed practitioners offering home service and clinic consultations.</p>
                </div>
                <span class="rounded-full bg-ember-100 px-3 py-1 text-xs font-bold text-ember-900 dark:bg-ember-950 dark:text-ember-200">
                    {{ count($featuredTherapists) }} Practitioners
                </span>
            </div>

            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredTherapists as $therapist)
                    @php
                        $tName = $therapist['name'] ?? 'Practitioner';
                        $tSlug = $therapist['slug'] ?? \Illuminate\Support\Str::slug($tName);
                        $tRating = $therapist['rating'] ?? 4.8;
                        $tReviews = $therapist['review_count'] ?? 40;
                        $tSpecs = $therapist['specialties'] ?? 'Swedish, Hilot';
                        $tArea = $therapist['area'] ?? 'Manila';
                        $tInitials = $therapist['initials'] ?? substr($tName, 0, 2);
                        $tTone = $therapist['tone'] ?? 'bg-leaf-100 text-leaf-700';
                    @endphp
                    <article class="group relative flex flex-col overflow-hidden rounded-2xl border border-ink-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-ink-200 hover:shadow-xl dark:border-ink-800 dark:bg-ink-900">
                        <div class="flex items-start gap-4">
                            <div class="inline-flex size-14 shrink-0 items-center justify-center rounded-full font-black text-base {{ $tTone }}">
                                {{ $tInitials }}
                            </div>
                            <div>
                                <h3 class="text-base font-black text-ink-950 group-hover:text-ember-600 dark:text-ink-50 dark:group-hover:text-ember-400">{{ $tName }}</h3>
                                <p class="text-xs font-semibold text-leaf-700 dark:text-leaf-400">Certified Massage Practitioner</p>
                                <div class="mt-1 flex items-center gap-1 text-xs text-amber-500">
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-3.5"><path d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.064 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.83-4.401Z"/></svg>
                                    <span class="font-bold">{{ $tRating }}</span>
                                    <span class="text-ink-400">({{ $tReviews }} reviews)</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 space-y-2">
                            <p class="text-xs text-ink-600 dark:text-ink-300"><strong>Area:</strong> {{ $tArea }}</p>
                            <p class="text-xs text-ink-600 dark:text-ink-300"><strong>Specialties:</strong> {{ $tSpecs }}</p>
                        </div>

                        <div class="mt-5 border-t border-ink-100 pt-3 flex items-center justify-between text-xs dark:border-ink-800">
                            <span class="font-semibold text-leaf-600 dark:text-leaf-400">Available Today</span>
                            <a href="{{ route('therapist.show', $tSlug) }}" class="font-bold text-ember-600 hover:underline dark:text-ember-400">View Profile &rarr;</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Section 3: Browse by Massage Techniques --}}
    @if ($activeTab === 'all' || $activeTab === 'services')
        <section aria-labelledby="dir-techniques">
            <h2 id="dir-techniques" class="text-2xl font-black text-ink-950 dark:text-ink-50">Browse by Massage Technique</h2>
            <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">Explore traditional and modern bodywork modalities.</p>

            <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-6">
                @foreach ($massageTypes as $type)
                    <div class="group flex flex-col items-center rounded-2xl border border-ink-100 bg-white p-4 text-center shadow-sm transition hover:border-leaf-300 hover:bg-leaf-50 dark:border-ink-800 dark:bg-ink-900 dark:hover:border-leaf-800 dark:hover:bg-leaf-950">
                        <x-pictogram :name="$type['icon']" class="size-8 text-ink-500 transition group-hover:scale-110 group-hover:text-leaf-600 dark:text-ink-400 dark:group-hover:text-leaf-400" />
                        <h3 class="mt-3 text-xs font-bold text-ink-900 dark:text-ink-100">{{ $type['name'] }}</h3>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Section 4: Areas & Cities --}}
    @if ($activeTab === 'all' || $activeTab === 'areas')
        <section aria-labelledby="dir-areas">
            <h2 id="dir-areas" class="text-2xl font-black text-ink-950 dark:text-ink-50">Browse by City & Area</h2>
            <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">Find spas and therapists in your local neighborhood.</p>

            <div class="mt-6 flex flex-wrap gap-2.5">
                @foreach ($areas as $area)
                    <span class="inline-block rounded-full border border-ink-200 bg-white px-4 py-2 text-sm font-semibold text-ink-700 shadow-sm transition hover:border-ember-300 hover:bg-ember-50 hover:text-ember-700 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-200 dark:hover:border-ember-700 dark:hover:bg-ember-950">
                        {{ $area }}
                    </span>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
