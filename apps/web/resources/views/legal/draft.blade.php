@extends('layouts.app')

@section('title', $title)

@section('content')
<section class="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8">
    <span class="inline-flex items-center gap-1.5 rounded-full bg-ember-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-ember-700">
        {{ __('legal.draft_badge') }}
    </span>
    <h1 class="mt-4 text-3xl font-bold tracking-tight text-ink-950">{{ $title }}</h1>
    <p class="mt-1 text-sm text-ink-400">{{ __('legal.version_label', ['version' => $version]) }}</p>

    <div class="mt-6 rounded-2xl border border-ember-200 bg-ember-50 p-5 text-sm text-ember-900">
        {{ __('legal.draft_notice') }}
    </div>

    <div class="prose prose-sm mt-8 max-w-none text-ink-700">
        <p>{{ $summary }}</p>
        <ul class="mt-4 space-y-2">
            @foreach ($points as $point)
                <li class="flex items-start gap-2.5">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="mt-0.5 size-4 shrink-0 text-leaf-600" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.7-9.3a1 1 0 0 0-1.4-1.4L9 10.6 7.7 9.3a1 1 0 0 0-1.4 1.4l2 2a1 1 0 0 0 1.4 0l4-4Z" clip-rule="evenodd"/></svg>
                    <span>{{ $point }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    <a href="{{ route('home') }}" class="mt-10 inline-flex items-center gap-1.5 text-sm font-semibold text-ember-600 transition hover:text-ember-700">
        <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H6.56l3.22 3.22a.75.75 0 1 1-1.06 1.06l-4.5-4.5a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 1.06L6.56 9.25h9.69A.75.75 0 0 1 17 10Z" clip-rule="evenodd"/></svg>
        {{ __('auth.back_to_home') }}
    </a>
</section>
@endsection
