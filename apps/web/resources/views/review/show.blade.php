@extends('layouts.app')

@section('title', $item['title'])
@section('meta_description', $item['short_description'])

@section('content')
<article class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    @if ($item['target'])
        <a href="{{ $item['target']['route'] }}" class="text-sm font-bold text-ember-700 hover:underline dark:text-ember-300">← {{ __('review.back_to_target', ['target' => $item['target']['name']]) }}</a>
    @endif
    <p class="mt-6 text-sm font-bold uppercase tracking-wider text-ember-700 dark:text-ember-300">{{ __('review.reviewed_target', ['target' => $item['target']['name'] ?? __('review.target_unavailable')]) }}</p>
    <h1 class="mt-3 text-4xl font-black leading-tight text-ink-950 sm:text-5xl dark:text-ink-50">{{ $item['title'] }}</h1>

    <div class="mt-6 flex flex-wrap items-center gap-3 text-sm text-ink-500 dark:text-ink-400">
        <span class="flex size-10 items-center justify-center rounded-full bg-ember-100 font-black text-ember-700 dark:bg-ember-900 dark:text-ember-300" aria-hidden="true">{{ $item['initials'] }}</span>
        <span class="font-bold text-ink-800 dark:text-ink-200">{{ $item['byline'] }}</span>
        <span>{{ __('review.published_on', ['date' => optional($item['published_at'])->format('M j, Y')]) }}</span>
        <span>{{ __('review.reading_time', ['seconds' => $item['reading_duration_visual']]) }}</span>
    </div>

    <section class="mt-8 rounded-2xl bg-ink-950 p-6 text-white" aria-label="{{ __('review.rating_mode_label') }}">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div><p class="text-sm font-bold text-ink-300">{{ __('review.rating_mode_'.$item['mode_rating']) }}</p><p class="mt-1 text-4xl font-black text-ember-400">{{ __('review.score_out_of_ten', ['score' => number_format($item['score'], 1)]) }}</p></div>
            @if ($item['criteria'])
                <dl class="grid flex-1 gap-2 sm:grid-cols-3">
                    @foreach ($item['criteria'] as $criterion)
                        <div class="rounded-xl bg-white/10 px-3 py-2"><dt class="text-xs text-ink-300">{{ $criterion['label'] }}</dt><dd class="font-black">{{ $criterion['score'] !== null ? __('review.score_out_of_ten', ['score' => $criterion['score']]) : __('review.'.strtolower($criterion['observation_status'] === 'NAP' ? 'not_applicable' : 'not_observed')) }}</dd></div>
                    @endforeach
                </dl>
            @endif
        </div>
    </section>

    <div class="mt-8 whitespace-pre-line text-lg leading-8 text-ink-800 dark:text-ink-200">{{ $item['body'] }}</div>

    <dl class="mt-10 grid gap-3 rounded-2xl border border-ink-100 bg-white p-6 text-sm sm:grid-cols-2 dark:border-ink-800 dark:bg-ink-900">
        @if ($item['date_experience'])<div><dt class="font-bold text-ink-500 dark:text-ink-400">{{ __('review.date_experience_label') }}</dt><dd class="mt-1 text-ink-900 dark:text-ink-100">{{ $item['date_experience']->format('M j, Y') }}</dd></div>@endif
        @if ($item['service_received'])<div><dt class="font-bold text-ink-500 dark:text-ink-400">{{ __('review.service_received_label') }}</dt><dd class="mt-1 text-ink-900 dark:text-ink-100">{{ $item['service_received'] }}</dd></div>@endif
        @if ($item['amount_paid'] !== null)<div><dt class="font-bold text-ink-500 dark:text-ink-400">{{ __('review.amount_paid_label') }}</dt><dd class="mt-1 text-ink-900 dark:text-ink-100">₱{{ number_format((float) $item['amount_paid'], 2) }}</dd></div>@endif
        <div><dt class="font-bold text-ink-500 dark:text-ink-400">{{ __('review.disclosure_label') }}</dt><dd class="mt-1 text-ink-900 dark:text-ink-100">{{ $item['disclosure'] }}</dd></div>
    </dl>
</article>
@endsection
