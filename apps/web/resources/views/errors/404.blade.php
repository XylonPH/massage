@extends('layouts.app')

@section('title', __('common.404_title'))

@section('content')
<main id="main-content" tabindex="-1">
    <div class="flex min-h-[60vh] flex-col items-center justify-center px-4 py-16 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-lg text-center">
            <div class="mb-8 flex justify-center">
                <div class="relative flex size-24 items-center justify-center rounded-full bg-ember-50">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-12 text-ember-500" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            
            <h1 class="text-4xl font-black tracking-tight text-ink-950 sm:text-5xl">
                {{ __('common.404_heading') }}
            </h1>
            
            <p class="mt-4 text-lg font-semibold text-ink-900">
                {{ __('common.404_message') }}
            </p>
            
            <p class="mt-2 text-sm text-ink-500">
                {{ __('common.404_description') }}
            </p>
            
            <div class="mt-10 flex items-center justify-center gap-4">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-xl bg-ember-500 px-6 py-3 text-sm font-bold text-white shadow-md shadow-ember-500/30 transition hover:bg-ember-600">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd"/></svg>
                    {{ __('common.back_to_home') }}
                </a>
                <a href="{{ url('/directory/spa') }}" class="inline-flex items-center gap-2 rounded-xl bg-ink-950 px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-ink-800">
                    {{ __('navigation.directory') }}
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                </a>
            </div>
        </div>
    </div>
</main>
@endsection
