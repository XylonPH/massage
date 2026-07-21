<div class="mx-auto max-w-6xl">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <a href="{{ route('workspace.editorial.home') }}" wire:navigate class="text-sm font-bold text-ember-600 hover:underline">&larr; {{ __('editorial.title') }}</a>
            <h1 class="mt-2 text-2xl font-black text-ink-950 dark:text-ink-50">{{ __('editorial.article_review_queue') }}</h1>
            <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">{{ __('editorial.article_review_queue_intro') }}</p>
        </div>
        <span class="rounded-full bg-ink-100 px-3 py-1 text-sm font-bold text-ink-700 dark:bg-ink-800 dark:text-ink-200">{{ trans_choice('editorial.pending_article_count', $revisions->count(), ['count' => $revisions->count()]) }}</span>
    </div>

    @if (session('editorial_status'))
        <p class="mt-4 rounded-lg border border-leaf-200 bg-leaf-50 px-4 py-2.5 text-sm font-semibold text-leaf-700 dark:border-leaf-800 dark:bg-leaf-950 dark:text-leaf-300">{{ session('editorial_status') }}</p>
    @endif

    <div class="mt-6 overflow-x-auto rounded-2xl border border-ink-100 bg-white shadow-sm dark:border-ink-800 dark:bg-ink-900">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-ink-100 text-xs font-bold uppercase tracking-wider text-ink-500 dark:border-ink-800 dark:text-ink-400">
                <tr>
                    <th class="px-5 py-3">{{ __('editorial.article') }}</th>
                    <th class="px-5 py-3">{{ __('editorial.revision') }}</th>
                    <th class="px-5 py-3">{{ __('editorial.submitted_at') }}</th>
                    <th class="px-5 py-3 text-right">{{ __('editorial.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($revisions as $revision)
                    @php($item = $articles->get((string) $revision->article_id))
                    @continue(! $item)
                    <tr class="border-b border-ink-100 last:border-0 dark:border-ink-800" wire:key="{{ $revision->getKey() }}">
                        <td class="px-5 py-4">
                            <p class="font-bold text-ink-950 dark:text-ink-50">{{ $item->localized('article_title') }}</p>
                            <p class="mt-1 line-clamp-1 text-ink-500 dark:text-ink-400">{{ $item->localized('short_description') }}</p>
                        </td>
                        <td class="px-5 py-4 text-ink-600 dark:text-ink-300">#{{ $revision->revision_number }} &middot; {{ trans_choice('editorial.article_words', $revision->word_count, ['count' => $revision->word_count]) }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-ink-600 dark:text-ink-300">{{ $revision->submitted_at?->format('M j, Y g:i A') }}</td>
                        <td class="px-5 py-4 text-right"><a href="{{ route('workspace.editorial.article.review', $item) }}" wire:navigate class="font-bold text-ember-600 hover:underline">{{ __('editorial.review_article') }}</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-12 text-center"><p class="font-bold text-ink-700 dark:text-ink-200">{{ __('editorial.no_pending_articles') }}</p><p class="mt-1 text-ink-500 dark:text-ink-400">{{ __('editorial.no_pending_articles_text') }}</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
