@extends('layouts.workspace', ['navActive' => 'articles'])

@section('title', __('article.workspace_title'))
@section('page-title', __('article.workspace_title'))
@section('page-context', __('article.workspace_intro'))

@section('page-actions')
<a href="{{ route('workspace.article.create') }}"
   class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-ember-500 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-ember-500/20 transition hover:-translate-y-0.5 hover:bg-ember-600 hover:shadow-lg hover:shadow-ember-500/25 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember-500 sm:px-5">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true">
        <path stroke-linecap="round" d="M12 5v14M5 12h14" />
    </svg>
    <span>{{ __('article.new_article') }}</span>
</a>
@endsection

@section('content')
<div class="mx-auto max-w-6xl">
    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-2xl border border-leaf-200 bg-leaf-50 p-4 font-semibold text-leaf-800 shadow-sm dark:border-leaf-800 dark:bg-leaf-950 dark:text-leaf-300" role="status">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mt-0.5 size-5 shrink-0" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
        <nav class="inline-flex w-fit max-w-full gap-1 overflow-x-auto rounded-xl border border-ink-100 bg-white p-1 shadow-sm dark:border-ink-800 dark:bg-ink-900" aria-label="{{ __('article.status') }}">
            @foreach ([[null, __('article.all')], ['draft', __('article.drafts')], ['submitted', __('article.submitted')], ['published', __('article.published_tab')]] as [$key, $label])
                @php
                    $route = $key ? 'workspace.article.'.$key : 'workspace.article.index';
                @endphp
                <a href="{{ route($route) }}"
                   @if ($status === $key) aria-current="page" @endif
                   class="whitespace-nowrap rounded-lg px-4 py-2 text-sm font-bold transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember-500 {{ $status === $key ? 'bg-ink-950 text-white shadow-sm dark:bg-ember-500' : 'text-ink-600 hover:bg-ink-50 hover:text-ink-950 dark:text-ink-300 dark:hover:bg-ink-800 dark:hover:text-white' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        @if (! $articles->isEmpty())
            <p class="text-sm font-medium text-ink-500 dark:text-ink-400">
                {{ trans_choice('article.article_count', $articles->total(), ['count' => number_format($articles->total())]) }}
            </p>
        @endif
    </div>

    <section class="mt-6 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm dark:border-ink-800 dark:bg-ink-900" aria-labelledby="article-list-title">
        <div class="border-b border-ink-100 px-5 py-4 sm:px-6 dark:border-ink-800">
            <h2 id="article-list-title" class="text-base font-black text-ink-950 dark:text-white">{{ __('article.your_articles') }}</h2>
        </div>

        @if ($articles->isEmpty())
            <div class="px-6 py-14 text-center sm:py-20">
                <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-ember-50 text-ember-600 ring-8 ring-ember-50/50 dark:bg-ember-950 dark:text-ember-400 dark:ring-ember-950/40">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="size-8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 3h9l4 4v14H6V3Zm9 0v4h4M9 12h6M9 16h4" />
                    </svg>
                </div>
                <h3 class="mt-6 text-xl font-black text-ink-950 dark:text-white">{{ __('article.empty_workspace_title') }}</h3>
                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-ink-500 dark:text-ink-400">{{ __('article.no_workspace_articles') }}</p>
                <a href="{{ route('workspace.article.create') }}"
                   class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-ink-950 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-ink-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember-500 dark:bg-white dark:text-ink-950 dark:hover:bg-ink-100">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" d="M12 5v14M5 12h14" /></svg>
                    {{ __('article.new_article') }}
                </a>
            </div>
        @else
            <div class="divide-y divide-ink-100 dark:divide-ink-800">
                @foreach ($articles as $item)
                    @php
                        $isSubmitted = $status === 'submitted' || in_array((string) $item->getKey(), $submittedArticleIds ?? [], true);
                        $statusKey = match (true) {
                            $item->status_review === 'N' => 'needs_revision',
                            $isSubmitted => 'submitted',
                            $item->status_publication === 'P' => 'published',
                            $item->status_publication === 'S' => 'scheduled',
                            $item->status_publication === 'U' => 'unpublished',
                            default => 'draft',
                        };
                        $statusLabel = match ($statusKey) {
                            'needs_revision' => __('article.needs_revision'),
                            'submitted' => __('article.submitted'),
                            'published' => __('article.published_tab'),
                            'scheduled' => __('article.scheduled'),
                            'unpublished' => __('article.unpublished'),
                            default => __('article.draft_status'),
                        };
                    @endphp
                    <article class="group grid gap-4 px-5 py-5 transition hover:bg-ink-50/70 sm:px-6 lg:grid-cols-[minmax(0,1fr)_9.5rem_11rem_auto] lg:items-center dark:hover:bg-ink-800/35">
                        <div class="flex min-w-0 items-start gap-3.5">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-ink-50 text-ink-500 transition group-hover:bg-white group-hover:text-ember-600 group-hover:shadow-sm dark:bg-ink-800 dark:text-ink-300 dark:group-hover:bg-ink-800 dark:group-hover:text-ember-400">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3h9l4 4v14H6V3Zm9 0v4h4M9 12h6M9 16h4" /></svg>
                            </span>
                            <div class="min-w-0 pt-0.5">
                                <h3 class="font-black leading-6 text-ink-950 dark:text-white">
                                    <a href="{{ route('workspace.article.edit', $item) }}" class="rounded-sm decoration-ember-400 decoration-2 underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember-500">
                                        {{ $item->localized('article_title') }}
                                    </a>
                                </h3>
                                <p class="mt-1 text-xs text-ink-400 lg:hidden dark:text-ink-500">{{ __('article.updated', ['date' => $item->updated_at?->format('M j, Y g:i A')]) }}</p>
                            </div>
                        </div>

                        <div>
                            <span @class([
                                'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold ring-1 ring-inset',
                                'bg-ember-50 text-ember-700 ring-ember-200 dark:bg-ember-950/60 dark:text-ember-300 dark:ring-ember-800' => $statusKey === 'needs_revision',
                                'bg-ink-50 text-ink-700 ring-ink-200 dark:bg-ink-800 dark:text-ink-200 dark:ring-ink-700' => in_array($statusKey, ['draft', 'unpublished'], true),
                                'bg-ink-100 text-ink-700 ring-ink-200 dark:bg-ink-800 dark:text-ink-200 dark:ring-ink-700' => in_array($statusKey, ['submitted', 'scheduled'], true),
                                'bg-leaf-50 text-leaf-700 ring-leaf-200 dark:bg-leaf-950/60 dark:text-leaf-300 dark:ring-leaf-800' => $statusKey === 'published',
                            ])>
                                <span class="size-1.5 rounded-full bg-current" aria-hidden="true"></span>
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <p class="hidden text-sm text-ink-500 lg:block dark:text-ink-400">{{ __('article.updated', ['date' => $item->updated_at?->format('M j, Y')]) }}</p>

                        <div class="flex items-center gap-1 border-t border-ink-100 pt-3 lg:justify-end lg:border-0 lg:pt-0 dark:border-ink-800">
                            <a href="{{ route('workspace.article.edit', $item) }}" class="rounded-lg px-3 py-2 text-sm font-bold text-ember-700 transition hover:bg-ember-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember-500 dark:text-ember-400 dark:hover:bg-ember-950/50">{{ __('article.edit') }}</a>
                            <a href="{{ route('workspace.article.revisions', $item) }}" class="rounded-lg px-3 py-2 text-sm font-bold text-ink-600 transition hover:bg-ink-100 hover:text-ink-950 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember-500 dark:text-ink-300 dark:hover:bg-ink-800 dark:hover:text-white">{{ __('article.revisions') }}</a>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($articles->hasPages())
                <div class="border-t border-ink-100 px-5 py-4 sm:px-6 dark:border-ink-800">{{ $articles->links() }}</div>
            @endif
        @endif
    </section>
</div>
@endsection
