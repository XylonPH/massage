@extends('layouts.workspace', ['navActive' => 'articles'])

@section('title', __('article.revision_history'))
@section('page-title', __('article.revision_history'))
@section('page-context', $article->localized('article_title'))

@section('page-actions')
<a href="{{ route('workspace.article.edit', $article) }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-bold text-ink-700 transition hover:bg-ink-50 hover:text-ink-950 dark:text-ink-200 dark:hover:bg-ink-800 dark:hover:text-white">
    <span aria-hidden="true">&larr;</span>
    {{ __('article.back_to_editor') }}
</a>
@endsection

@section('content')
<div class="mx-auto max-w-6xl">
    @if ($selectedRevision)
        <form method="get" action="{{ route('workspace.article.revisions', $article) }}" class="mb-6 grid gap-4 rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900 sm:grid-cols-[1fr_1fr_auto] sm:items-end">
            <div>
                <label for="revision" class="block text-sm font-bold text-ink-800 dark:text-ink-200">{{ __('article.selected_revision') }}</label>
                <select id="revision" name="revision" class="mt-2 w-full rounded-xl border border-ink-200 bg-white px-3 py-2.5 text-sm text-ink-950 focus:border-ember-500 focus:ring-ember-500 dark:border-ink-700 dark:bg-ink-950 dark:text-white">
                    @foreach ($availableRevisions as $option)
                        <option value="{{ $option->revision_number }}" @selected($selectedRevision->revision_number === $option->revision_number)>{{ __('article.revision_number', ['number' => $option->revision_number]) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="compare" class="block text-sm font-bold text-ink-800 dark:text-ink-200">{{ __('article.compare_with') }}</label>
                <select id="compare" name="compare" class="mt-2 w-full rounded-xl border border-ink-200 bg-white px-3 py-2.5 text-sm text-ink-950 focus:border-ember-500 focus:ring-ember-500 dark:border-ink-700 dark:bg-ink-950 dark:text-white">
                    <option value="">{{ __('article.no_comparison') }}</option>
                    @foreach ($availableRevisions as $option)
                        @continue($selectedRevision->revision_number === $option->revision_number)
                        <option value="{{ $option->revision_number }}" @selected($comparisonRevision?->revision_number === $option->revision_number)>{{ __('article.revision_number', ['number' => $option->revision_number]) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="rounded-xl bg-ink-950 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-ink-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember-500 dark:bg-white dark:text-ink-950 dark:hover:bg-ink-100">{{ __('article.compare') }}</button>
        </form>

        <x-article.revision-comparison :revision="$selectedRevision" :comparison="$comparisonRevision" />
    @endif

    <section class="mt-8" aria-labelledby="revision-timeline-title">
        <h2 id="revision-timeline-title" class="text-xl font-black text-ink-950 dark:text-white">{{ __('article.revision_timeline') }}</h2>
        <ol class="mt-4 space-y-4">
            @foreach ($revisions as $revision)
                <li class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h3 class="text-lg font-black text-ink-950 dark:text-ink-50">{{ __('article.revision_number', ['number' => $revision->revision_number]) }}</h3>
                        <a href="{{ route('workspace.article.revisions', ['article' => $article, 'revision' => $revision->revision_number]) }}" class="rounded-lg px-3 py-2 text-sm font-bold text-ember-700 transition hover:bg-ember-50 dark:text-ember-400 dark:hover:bg-ember-950/50">{{ __('article.view_and_compare') }}</a>
                    </div>
                    <p class="mt-3 text-ink-700 dark:text-ink-200">{{ $revision->revision_note ?: __('article.no_revision_note') }}</p>
                    @if ($revision->review_note)
                        <div class="mt-4 rounded-xl border border-ember-200 bg-ember-50 p-4 text-sm text-ember-900 dark:border-ember-800 dark:bg-ember-950/50 dark:text-ember-200">
                            <p class="font-bold">{{ __('article.editor_review_note') }}</p>
                            <p class="mt-1">{{ $revision->review_note }}</p>
                        </div>
                    @endif
                    <div class="mt-4 flex flex-wrap gap-4 text-sm text-ink-500 dark:text-ink-400">
                        <span>{{ __('article.revision_saved', ['date' => $revision->created_at?->format('M j, Y g:i A')]) }}</span>
                        <span>{{ $revision->submitted_at ? __('article.revision_submitted', ['date' => $revision->submitted_at->format('M j, Y g:i A')]) : __('article.revision_not_submitted') }}</span>
                        <span>{{ trans_choice('article.revision_words', $revision->word_count, ['count' => $revision->word_count]) }}</span>
                    </div>
                </li>
            @endforeach
        </ol>
        <div class="mt-8">{{ $revisions->links() }}</div>
    </section>
</div>
@endsection
