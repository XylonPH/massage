@extends('layouts.app')

@section('title', __('cookies.page_title'))

@section('content')
<section class="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold tracking-tight text-ink-950">{{ __('cookies.page_title') }}</h1>
    <p class="mt-3 text-sm leading-relaxed text-ink-600">{{ __('cookies.page_intro') }}</p>

    <div class="mt-8 space-y-5">
        <div class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm">
            <h2 class="font-bold text-ink-950">{{ __('cookies.necessary_title') }}</h2>
            <p class="mt-1.5 text-sm text-ink-600">{{ __('cookies.page_necessary_detail') }}</p>
        </div>
        <div class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm">
            <h2 class="font-bold text-ink-950">{{ __('cookies.page_preference_title') }}</h2>
            <p class="mt-1.5 text-sm text-ink-600">{{ __('cookies.page_preference_detail') }}</p>
        </div>
        <div class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm">
            <h2 class="font-bold text-ink-950">{{ __('cookies.analytics_title') }}</h2>
            <p class="mt-1.5 text-sm text-ink-600">{{ __('cookies.page_analytics_detail') }}</p>
        </div>
    </div>

    <a href="{{ route('home') }}" class="mt-10 inline-flex items-center gap-1.5 text-sm font-semibold text-ember-600 transition hover:text-ember-700">
        <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H6.56l3.22 3.22a.75.75 0 1 1-1.06 1.06l-4.5-4.5a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 1.06L6.56 9.25h9.69A.75.75 0 0 1 17 10Z" clip-rule="evenodd"/></svg>
        {{ __('auth.back_to_home') }}
    </a>
</section>
@endsection
