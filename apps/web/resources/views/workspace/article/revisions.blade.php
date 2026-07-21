@extends('layouts.app')

@section('title', __('article.revision_history'))

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[16rem_minmax(0,1fr)]">
        <aside class="min-w-0"><x-workspace-nav active="articles" /></aside>

        <main class="min-w-0">
            <a href="{{ route('workspace.article.edit', $article) }}" class="text-sm font-bold text-ember-700 hover:underline dark:text-ember-500">&larr; {{ __('article.back_to_editor') }}</a>
            <h1 class="mt-3 text-3xl font-black text-ink-950 dark:text-ink-50">{{ __('article.revision_history') }}</h1>
            <p class="mt-1 text-lg text-ink-600 dark:text-ink-300">{{ $article->localized('article_title') }}</p>
            <ol class="mt-6 space-y-4">
                @foreach ($revisions as $revision)
                    <li class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <h2 class="text-lg font-black text-ink-950 dark:text-ink-50">{{ __('article.revision_number', ['number' => $revision->revision_number]) }}</h2>
                            <span class="rounded-full bg-ink-50 px-3 py-1 text-sm font-bold text-ink-700 dark:bg-ink-800 dark:text-ink-300">{{ $revision->status_review }}</span>
                        </div>
                        <p class="mt-3 text-ink-700 dark:text-ink-200">{{ $revision->revision_note ?: '—' }}</p>
                        <div class="mt-4 flex flex-wrap gap-4 text-sm text-ink-500 dark:text-ink-400">
                            <span>{{ __('article.revision_saved', ['date' => $revision->created_at?->format('M j, Y g:i A')]) }}</span>
                            <span>{{ $revision->submitted_at ? __('article.revision_submitted', ['date' => $revision->submitted_at->format('M j, Y g:i A')]) : __('article.revision_not_submitted') }}</span>
                            <span>{{ __('article.revision_words', ['count' => $revision->word_count]) }}</span>
                        </div>
                    </li>
                @endforeach
            </ol>
            <div class="mt-8">{{ $revisions->links() }}</div>
        </main>
    </div>
</div>
@endsection
