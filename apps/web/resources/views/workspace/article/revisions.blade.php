@extends('layouts.app')

@section('title', __('article.revision_history'))

@section('content')
<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <a href="{{ route('workspace.article.edit', $article) }}" class="text-sm font-bold text-ember-700 hover:underline">← {{ __('article.back_to_editor') }}</a>
    <h1 class="mt-3 text-4xl font-black text-ink-950">{{ __('article.revision_history') }}</h1>
    <p class="mt-2 text-xl text-ink-600">{{ $article->localized('article_title') }}</p>
    <ol class="mt-8 space-y-4">
        @foreach ($revisions as $revision)
            <li class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-xl font-black text-ink-950">{{ __('article.revision_number', ['number' => $revision->revision_number]) }}</h2>
                    <span class="rounded-full bg-ink-50 px-3 py-1 text-sm font-bold text-ink-700">{{ $revision->status_review }}</span>
                </div>
                <p class="mt-3 text-ink-700">{{ $revision->revision_note ?: '—' }}</p>
                <div class="mt-4 flex flex-wrap gap-4 text-sm text-ink-500">
                    <span>{{ __('article.revision_saved', ['date' => $revision->created_at?->format('M j, Y g:i A')]) }}</span>
                    <span>{{ $revision->submitted_at ? __('article.revision_submitted', ['date' => $revision->submitted_at->format('M j, Y g:i A')]) : __('article.revision_not_submitted') }}</span>
                    <span>{{ $revision->word_count }} words</span>
                </div>
            </li>
        @endforeach
    </ol>
    <div class="mt-8">{{ $revisions->links() }}</div>
</div>
@endsection
