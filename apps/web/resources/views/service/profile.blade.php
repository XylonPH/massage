@extends('layouts.app')

@section('title', $service->english_name ?? 'Service Profile')
@section('meta_description', $service->english_short_description ?? 'Learn more about this service')

@section('content')

{{-- ===================== Identity header ===================== --}}
<section class="relative overflow-hidden bg-ink-950 text-white">
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="absolute -right-24 -top-24 size-96 rounded-full bg-water-500/15 blur-3xl"></div>
        <div class="absolute -bottom-32 left-1/3 size-80 rounded-full bg-amethyst-500/10 blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-[1600px] px-4 py-8 sm:px-6 lg:px-8">
        <nav aria-label="Breadcrumb" class="text-xs text-ink-300 mb-6">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li><a href="{{ route('home') }}" class="transition hover:text-white">Home</a></li>
                <li aria-hidden="true">›</li>
                <li><a href="{{ url('/directory/service') }}" class="transition hover:text-white">Services</a></li>
                <li aria-hidden="true">›</li>
                @if($service->group_service_family)
                <li><span class="text-ink-400">{{ $service->group_service_family }}</span></li>
                <li aria-hidden="true">›</li>
                @endif
                <li aria-current="page" class="font-semibold text-white">{{ $service->english_name }}</li>
            </ol>
        </nav>

        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            <div class="flex-1 max-w-4xl">
                <h1 class="text-4xl sm:text-5xl font-bold tracking-tight mb-4">
                    {{ $service->english_name }}
                </h1>
                
                @if($service->english_short_description)
                <p class="text-xl text-ink-200 leading-relaxed max-w-3xl mb-6">
                    {{ $service->english_short_description }}
                </p>
                @endif

                <div class="flex flex-wrap items-center gap-3 text-sm">
                    @if($service->group_service_sector)
                    <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 font-medium text-white backdrop-blur-sm">
                        {{ $service->group_service_sector }}
                    </span>
                    @endif
                    @if($service->group_service_domain)
                    <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 font-medium text-white backdrop-blur-sm">
                        {{ $service->group_service_domain }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Primary Actions --}}
            <div class="flex flex-wrap gap-3 lg:shrink-0">
                <button class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-ink-950 transition hover:bg-ink-100">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Find Establishments
                </button>
                <button class="inline-flex items-center justify-center gap-2 rounded-lg bg-white/10 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur-md transition hover:bg-white/20 border border-white/10">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Find Therapists
                </button>
                <button class="inline-flex size-10 items-center justify-center rounded-lg bg-white/10 text-white backdrop-blur-md transition hover:bg-white/20 hover:text-ember-400 border border-white/10" aria-label="Save">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </button>
            </div>
        </div>
    </div>
</section>

{{-- ===================== Content Tabs & Sections ===================== --}}
<div class="mx-auto max-w-[1600px] px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex flex-col lg:flex-row gap-12">
        {{-- Main Column --}}
        <div class="flex-1 lg:max-w-[1000px]">
            @if($service->english_overview)
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-ink-900 mb-6 border-b border-ink-100 pb-2">Overview</h2>
                <div class="prose prose-ink max-w-none text-ink-600 leading-relaxed">
                    {{-- Assuming text for now, could be markdown/html depending on editor --}}
                    {{ $service->english_overview }}
                </div>
            </section>
            @endif

            <section class="mb-12">
                <h2 class="text-2xl font-bold text-ink-900 mb-6 border-b border-ink-100 pb-2">What to Expect</h2>
                <div class="rounded-xl bg-ink-50 p-6 border border-ink-100 text-ink-500 text-center">
                    Detailed sequence and preparation information for this service is not yet available. 
                    <button class="text-amethyst-600 font-semibold hover:underline ml-2">Suggest Information</button>
                </div>
            </section>
            
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-ink-900 mb-6 border-b border-ink-100 pb-2">Safety and Professional Scope</h2>
                <div class="rounded-xl bg-amber-50 p-6 border border-amber-200">
                    <p class="text-amber-800 text-sm">
                        <strong class="font-bold">Important:</strong> A service profile must not imply diagnosis, treatment, cure, rehabilitation, or medical authority beyond the documented legal and professional scope. Please consult a qualified healthcare professional regarding any medical conditions.
                    </p>
                </div>
            </section>

        </div>

        {{-- Sidebar --}}
        <aside class="w-full lg:w-[320px] xl:w-[400px] shrink-0 space-y-8">
            <div class="rounded-xl border border-ink-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-ink-100 bg-ink-50 px-5 py-4">
                    <h3 class="font-bold text-ink-900">Service Classification</h3>
                </div>
                <div class="p-5">
                    <dl class="space-y-4 text-sm">
                        @if($service->group_service_sector)
                        <div>
                            <dt class="font-medium text-ink-500 mb-1">Sector</dt>
                            <dd class="text-ink-900">{{ $service->group_service_sector }}</dd>
                        </div>
                        @endif
                        @if($service->group_service_domain)
                        <div>
                            <dt class="font-medium text-ink-500 mb-1">Domain</dt>
                            <dd class="text-ink-900">{{ $service->group_service_domain }}</dd>
                        </div>
                        @endif
                        @if($service->group_service_family)
                        <div>
                            <dt class="font-medium text-ink-500 mb-1">Family</dt>
                            <dd class="text-ink-900">{{ $service->group_service_family }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
            
            <div class="rounded-xl border border-ink-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-ink-100 bg-ink-50 px-5 py-4">
                    <h3 class="font-bold text-ink-900">Therapists offering this</h3>
                </div>
                <div class="p-5 text-center text-ink-500 text-sm">
                    No verified therapists available in your area yet.
                </div>
            </div>
            
            <x-widgets.sidebar-container :content-length="3000" />
        </aside>
    </div>
</div>

@endsection
