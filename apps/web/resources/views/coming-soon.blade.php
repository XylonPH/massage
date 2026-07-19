@extends('layouts.app')

@section('title', __($sectionKey))

@section('content')
<section class="relative overflow-hidden bg-ink-950">
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="absolute -right-32 -top-40 size-[28rem] rounded-full bg-ember-500/15 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-24 size-[24rem] rounded-full bg-leaf-500/15 blur-3xl"></div>
    </div>
    <div class="relative mx-auto flex max-w-3xl flex-col items-center px-4 py-24 text-center sm:px-6">
        <span class="rounded-full bg-white/10 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-ember-400">{{ __('common.coming_soon_badge') }}</span>
        <h1 class="mt-5 text-4xl font-bold tracking-tight text-white sm:text-5xl">{{ __($sectionKey) }}</h1>
        <p class="mt-4 max-w-xl text-lg text-ink-200">{{ __('common.coming_soon_text') }}</p>
        <a href="{{ route('home') }}" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-ember-500 px-6 py-3 text-sm font-bold text-white shadow-md shadow-ember-500/30 transition hover:bg-ember-600">
            <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H6.56l3.22 3.22a.75.75 0 1 1-1.06 1.06l-4.5-4.5a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 1.06L6.56 9.25h9.69A.75.75 0 0 1 17 10Z" clip-rule="evenodd"/></svg>
            {{ __('common.back_to_home') }}
        </a>
    </div>
</section>
@endsection
