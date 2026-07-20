@extends('layouts.app')

@section('title', $heading)

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-bold uppercase tracking-wider text-ember-700">{{ config('app.name') }}</p>
            <h1 class="mt-2 text-4xl font-black text-ink-950">{{ $heading }}</h1>
        </div>
        <nav class="flex flex-wrap gap-2" aria-label="{{ __('review.public_title') }}">
            <a href="{{ route('review.index') }}" class="rounded-lg border border-ink-200 bg-white px-3 py-2 text-sm font-bold text-ink-700 hover:bg-ink-50">{{ __('review.browse_all') }}</a>
            <a href="{{ route('review.spa') }}" class="rounded-lg border border-ink-200 bg-white px-3 py-2 text-sm font-bold text-ink-700 hover:bg-ink-50">{{ __('review.browse_spas') }}</a>
            <a href="{{ route('review.therapist') }}" class="rounded-lg border border-ink-200 bg-white px-3 py-2 text-sm font-bold text-ink-700 hover:bg-ink-50">{{ __('review.browse_therapists') }}</a>
        </nav>
    </div>

    <div class="mt-8 grid gap-6 md:grid-cols-2">
        @forelse ($reviews as $item)
            <article class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <p class="text-sm font-bold text-ember-700">{{ $item['target']['name'] ?? __('review.target_unavailable') }}</p>
                    @if ($item['score'] !== null)<span class="rounded-lg bg-ink-950 px-3 py-1.5 text-sm font-black text-white">{{ __('review.score_out_of_ten', ['score' => number_format($item['score'], 1)]) }}</span>@endif
                </div>
                <h2 class="mt-3 text-2xl font-black text-ink-950"><a href="{{ route('review.show', $item['slug']) }}" class="hover:text-ember-700">{{ $item['title'] }}</a></h2>
                <p class="mt-3 leading-7 text-ink-600">{{ $item['short_description'] }}</p>
                <div class="mt-5 flex flex-wrap gap-3 text-xs text-ink-500">
                    <span>{{ $item['byline'] }}</span>
                    <span>{{ optional($item['published_at'])->format('M j, Y') }}</span>
                    @if ($item['level_nsfw'] !== 'N')<span class="font-bold text-ember-700">{{ __('review.sensitive_notice') }}</span>@endif
                </div>
            </article>
        @empty
            <p class="md:col-span-2 rounded-2xl border border-dashed border-ink-200 bg-white p-10 text-center text-ink-500">{{ __('review.no_public_reviews') }}</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $reviews->links() }}</div>
</div>
@endsection
