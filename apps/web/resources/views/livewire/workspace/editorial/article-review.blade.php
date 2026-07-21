<div class="mx-auto max-w-6xl">
    <a href="{{ route('workspace.editorial.article.index') }}" wire:navigate class="text-sm font-bold text-ember-600 hover:underline">&larr; {{ __('editorial.article_review_queue') }}</a>

    <div class="mt-4 grid gap-6 xl:grid-cols-[minmax(0,1fr)_20rem]">
        <article class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900 sm:p-8">
            <p class="text-xs font-bold uppercase tracking-wider text-ember-600">{{ __('editorial.submitted_revision', ['number' => $revision->revision_number]) }}</p>
            <h1 class="mt-2 text-3xl font-black tracking-tight text-ink-950 dark:text-ink-50">{{ $record->localized('article_title') }}</h1>
            <p class="mt-3 text-lg text-ink-600 dark:text-ink-300">{{ $record->localized('short_description') }}</p>
            <div class="mt-5 flex flex-wrap gap-3 text-sm text-ink-500 dark:text-ink-400">
                <span>{{ trans_choice('editorial.article_words', $revision->word_count, ['count' => $revision->word_count]) }}</span>
                <span>{{ __('editorial.submitted_at_value', ['date' => $revision->submitted_at?->format('M j, Y g:i A')]) }}</span>
            </div>
            <div class="prose prose-ink mt-8 max-w-none dark:prose-invert">{!! $revision->article_body !!}</div>
        </article>

        <aside class="self-start rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900 xl:sticky xl:top-24">
            <h2 class="text-lg font-black text-ink-950 dark:text-ink-50">{{ __('editorial.editorial_decision') }}</h2>
            <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">{{ __('editorial.editorial_decision_hint') }}</p>

            <label for="review-note" class="mt-5 block text-sm font-bold text-ink-800 dark:text-ink-200">{{ __('editorial.review_note') }}</label>
            <textarea id="review-note" wire:model="reviewNote" rows="6" maxlength="2000" class="mt-2 w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-950 focus:border-ember-500 focus:ring-ember-500 dark:border-ink-700 dark:bg-ink-950 dark:text-ink-50"></textarea>
            @error('reviewNote') <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror

            <div class="mt-5 grid gap-2">
                <button type="button" wire:click="approve" wire:confirm="{{ __('editorial.approve_article_confirm') }}" class="rounded-lg bg-leaf-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-leaf-700">{{ __('editorial.approve_and_publish') }}</button>
                <button type="button" wire:click="requestChanges" class="rounded-lg bg-ember-500 px-4 py-2.5 text-sm font-bold text-white hover:bg-ember-600">{{ __('editorial.request_changes') }}</button>
                <button type="button" wire:click="reject" wire:confirm="{{ __('editorial.reject_article_confirm') }}" class="rounded-lg border border-ink-200 px-4 py-2.5 text-sm font-bold text-ink-700 hover:border-red-300 hover:text-red-700 dark:border-ink-700 dark:text-ink-200">{{ __('editorial.reject_article') }}</button>
            </div>
        </aside>
    </div>
</div>
