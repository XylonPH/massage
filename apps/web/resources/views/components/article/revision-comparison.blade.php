@props([
    'revision',
    'comparison' => null,
    'revisionLabel' => null,
    'comparisonLabel' => null,
    'emptyLabel' => null,
    'emptyHint' => null,
])

@php
    $statusLabels = [
        'P' => __('article.review_pending'),
        'A' => __('article.review_approved'),
        'N' => __('article.needs_revision'),
        'R' => __('article.review_rejected'),
    ];
    $wordDifference = $comparison ? $revision->word_count - $comparison->word_count : null;
@endphp

<section {{ $attributes->class('overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm dark:border-ink-800 dark:bg-ink-900') }} data-revision-comparison>
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink-100 px-5 py-4 dark:border-ink-800 sm:px-6">
        <div>
            <h2 class="text-lg font-black text-ink-950 dark:text-white">{{ __('article.compare_revisions') }}</h2>
            <p class="mt-1 text-sm text-ink-500 dark:text-ink-400">{{ __('article.compare_revisions_hint') }}</p>
        </div>
        @if ($comparison)
            <span class="rounded-full bg-ink-50 px-3 py-1 text-xs font-bold text-ink-700 dark:bg-ink-800 dark:text-ink-200">
                {{ $wordDifference > 0 ? '+' : '' }}{{ trans_choice('article.revision_word_difference', abs($wordDifference), ['count' => $wordDifference]) }}
            </span>
        @endif
    </div>

    <div class="grid divide-y divide-ink-100 dark:divide-ink-800 lg:grid-cols-2 lg:divide-x lg:divide-y-0">
        @if ($comparison)
            <article class="min-w-0 p-5 sm:p-6">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <p class="text-xs font-black uppercase tracking-[0.16em] text-ink-500 dark:text-ink-400">{{ $comparisonLabel ?? __('article.comparison_revision') }}</p>
                    <span class="rounded-full bg-ink-50 px-2.5 py-1 text-xs font-bold text-ink-700 dark:bg-ink-800 dark:text-ink-200">
                        {{ __('article.revision_number', ['number' => $comparison->revision_number]) }}
                    </span>
                </div>
                <p class="mt-3 text-xs text-ink-500 dark:text-ink-400">
                    {{ trans_choice('article.revision_words', $comparison->word_count, ['count' => $comparison->word_count]) }}
                    <span aria-hidden="true"> &middot; </span>
                    {{ $statusLabels[$comparison->status_review] ?? $comparison->status_review }}
                </p>
                <div class="mn-article-body prose prose-sm mt-6 max-w-none dark:prose-invert">{!! $comparison->article_body !!}</div>
            </article>
        @else
            <div class="flex min-h-48 items-center justify-center bg-ink-50/60 p-6 text-center dark:bg-ink-950/30">
                <div>
                    <p class="font-bold text-ink-700 dark:text-ink-200">{{ $emptyLabel ?? __('article.no_comparison_selected') }}</p>
                    <p class="mt-1 text-sm text-ink-500 dark:text-ink-400">{{ $emptyHint ?? __('article.no_comparison_selected_hint') }}</p>
                </div>
            </div>
        @endif

        <article class="min-w-0 p-5 sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <p class="text-xs font-black uppercase tracking-[0.16em] text-ember-600 dark:text-ember-400">{{ $revisionLabel ?? __('article.selected_revision') }}</p>
                <span class="rounded-full bg-ember-50 px-2.5 py-1 text-xs font-bold text-ember-700 dark:bg-ember-950/60 dark:text-ember-300">
                    {{ __('article.revision_number', ['number' => $revision->revision_number]) }}
                </span>
            </div>
            <p class="mt-3 text-xs text-ink-500 dark:text-ink-400">
                {{ trans_choice('article.revision_words', $revision->word_count, ['count' => $revision->word_count]) }}
                <span aria-hidden="true"> &middot; </span>
                {{ $statusLabels[$revision->status_review] ?? $revision->status_review }}
            </p>
            <div class="mn-article-body prose prose-sm mt-6 max-w-none dark:prose-invert">{!! $revision->article_body !!}</div>
        </article>
    </div>
</section>
