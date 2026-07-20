@extends('layouts.workspace', ['navActive' => 'reviews'])

@section('title', __('review.workspace_title'))
@section('page-title', __('review.workspace_title'))
@section('page-context', __('review.workspace_intro'))

@section('content')
<div class="mx-auto max-w-5xl">
    @if (session('status'))
        <div class="rounded-xl border border-leaf-200 bg-leaf-50 p-4 font-semibold text-leaf-800 dark:border-leaf-800 dark:bg-leaf-950 dark:text-leaf-300" role="status">{{ session('status') }}</div>
    @endif

    <nav class="mt-7 flex flex-wrap gap-2" aria-label="{{ __('review.workspace_title') }}">
        @foreach (['all' => 'index', 'drafts' => 'draft', 'submitted' => 'submitted', 'published' => 'published'] as $label => $route)
            @php $active = $filter === ($label === 'drafts' ? 'draft' : $label); @endphp
            <a href="{{ route('workspace.review.'.$route) }}" class="rounded-lg px-4 py-2 text-sm font-bold {{ $active ? 'bg-ink-950 text-white' : 'border border-ink-200 bg-white text-ink-700 hover:bg-ink-50 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-200 dark:hover:bg-ink-800' }}">{{ __('review.'.$label) }}</a>
        @endforeach
    </nav>

    <div class="mt-7 space-y-4">
        @forelse ($reviews as $item)
            <article class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-ember-700 dark:text-ember-400">{{ $item['target']['name'] ?? __('review.target_unavailable') }}</p>
                        <h2 class="mt-1 text-xl font-black text-ink-950 dark:text-ink-50">{{ $item['title'] }}</h2>
                        <p class="mt-2 text-sm text-ink-600 dark:text-ink-300">{{ $item['short_description'] }}</p>
                    </div>
                    <span class="shrink-0 rounded-full bg-ink-100 px-3 py-1 text-xs font-bold text-ink-700 dark:bg-ink-800 dark:text-ink-200">{{ __('review.status_'.$item['status_review']) }}</span>
                </div>
                <div class="mt-4 flex flex-wrap items-center gap-4 text-sm">
                    @if ($item['score'] !== null)<span class="font-black text-ember-700 dark:text-ember-400">{{ __('review.score_out_of_ten', ['score' => number_format($item['score'], 1)]) }}</span>@endif
                    <span class="text-ink-500 dark:text-ink-400">{{ optional($item['updated_at'])->format('M j, Y') }}</span>
                    @if ($item['status_review'] === 'NR')
                        <a href="{{ route('workspace.review.edit', $item['id']) }}" class="font-bold text-ember-700 hover:underline dark:text-ember-400">{{ __('review.edit_title', ['target' => $item['target']['name'] ?? __('review.target_unavailable')]) }}</a>
                    @elseif ($item['status_publication'] === 'P')
                        <a href="{{ route('review.show', $item['slug']) }}" class="font-bold text-ember-700 hover:underline dark:text-ember-400">{{ __('review.view_review') }}</a>
                    @endif
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-ink-200 bg-white p-10 text-center text-ink-500 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-400">{{ __('review.empty_workspace') }}</div>
        @endforelse
    </div>

    <div class="mt-8">{{ $reviews->links() }}</div>
</div>
@endsection
