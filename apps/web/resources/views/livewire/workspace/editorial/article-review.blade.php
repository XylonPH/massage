<div class="mx-auto max-w-6xl">
    <a href="{{ route('workspace.editorial.article.index') }}" wire:navigate class="text-sm font-bold text-ember-600 hover:underline">&larr; {{ __('editorial.article_review_queue') }}</a>

    <header class="mt-4 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900 sm:p-8">
        <p class="text-xs font-bold uppercase tracking-wider text-ember-600 dark:text-ember-400">{{ __('editorial.submitted_revision', ['number' => $revision->revision_number]) }}</p>
        <h1 class="mt-2 text-3xl font-black tracking-tight text-ink-950 dark:text-ink-50">{{ $record->localized('article_title') }}</h1>
        <p class="mt-3 text-lg text-ink-600 dark:text-ink-300">{{ $record->localized('short_description') }}</p>
        <div class="mt-5 flex flex-wrap gap-3 text-sm text-ink-500 dark:text-ink-400">
            <span>{{ trans_choice('editorial.article_words', $revision->word_count, ['count' => $revision->word_count]) }}</span>
            <span>{{ __('editorial.submitted_at_value', ['date' => $revision->submitted_at?->format('M j, Y g:i A')]) }}</span>
        </div>
    </header>

    <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_20rem]">
        <x-article.revision-comparison
            :revision="$revision"
            :comparison="$previousRevision"
            :revision-label="__('editorial.submitted_version')"
            :comparison-label="__('editorial.previous_version')"
            :empty-label="__('article.no_earlier_revision')"
            :empty-hint="__('article.no_earlier_revision_hint')"
        />

        <aside class="self-start rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900 xl:sticky xl:top-24">
            <h2 class="text-lg font-black text-ink-950 dark:text-ink-50">{{ __('editorial.editorial_decision') }}</h2>
            <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">{{ __('editorial.editorial_decision_hint') }}</p>

            <label for="review-note" class="mt-5 block text-sm font-bold text-ink-800 dark:text-ink-200">{{ __('editorial.review_note') }}</label>
            <textarea id="review-note" wire:model="reviewNote" rows="6" maxlength="2000" class="mt-2 w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-950 focus:border-ember-500 focus:ring-ember-500 dark:border-ink-700 dark:bg-ink-950 dark:text-ink-50"></textarea>
            @error('reviewNote') <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror

            <div class="mt-5 grid gap-2">
                <button type="button" wire:click="requestApproval" class="rounded-lg bg-leaf-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-leaf-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-leaf-500">{{ __('editorial.review_approval') }}</button>
                <button type="button" wire:click="requestChanges" class="rounded-lg bg-ember-500 px-4 py-2.5 text-sm font-bold text-white hover:bg-ember-600">{{ __('editorial.request_changes') }}</button>
                <button type="button" wire:click="reject" wire:confirm="{{ __('editorial.reject_article_confirm') }}" class="rounded-lg border border-ink-200 px-4 py-2.5 text-sm font-bold text-ink-700 hover:border-red-300 hover:text-red-700 dark:border-ink-700 dark:text-ink-200">{{ __('editorial.reject_article') }}</button>
            </div>
        </aside>
    </div>

    @if ($showApprovalConfirmation)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-ink-950/75 p-4 backdrop-blur-sm" role="presentation" wire:key="approval-confirmation">
            <section role="dialog" aria-modal="true" aria-labelledby="approval-dialog-title" aria-describedby="approval-dialog-description" class="w-full max-w-lg rounded-2xl border border-ink-100 bg-white p-6 shadow-2xl dark:border-ink-700 dark:bg-ink-900 sm:p-7">
                <div class="flex items-start gap-4">
                    <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-leaf-100 text-leaf-700 dark:bg-leaf-950 dark:text-leaf-300" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" /></svg>
                    </span>
                    <div>
                        <h2 id="approval-dialog-title" class="text-xl font-black text-ink-950 dark:text-white">{{ __('editorial.approval_dialog_title') }}</h2>
                        <p id="approval-dialog-description" class="mt-2 text-sm leading-6 text-ink-600 dark:text-ink-300">{{ __('editorial.approval_dialog_description', ['revision' => $revision->revision_number, 'title' => $record->localized('article_title')]) }}</p>
                    </div>
                </div>

                <div class="mt-5 rounded-xl border border-ember-200 bg-ember-50 p-4 text-sm leading-6 text-ember-900 dark:border-ember-800 dark:bg-ember-950/50 dark:text-ember-200">
                    <strong>{{ __('editorial.publication_effect_title') }}</strong>
                    {{ __('editorial.publication_effect') }}
                </div>

                <label class="mt-5 flex cursor-pointer items-start gap-3 rounded-xl border border-ink-200 p-4 text-sm font-semibold text-ink-800 dark:border-ink-700 dark:text-ink-200">
                    <input type="checkbox" wire:model.live="approvalConfirmed" class="mt-0.5 size-4 rounded border-ink-300 text-leaf-600 focus:ring-leaf-500 dark:border-ink-600 dark:bg-ink-950">
                    <span>{{ __('editorial.approval_acknowledgement', ['revision' => $revision->revision_number]) }}</span>
                </label>
                @error('approvalConfirmed') <p class="mt-2 text-sm font-semibold text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

                <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                    <button type="button" wire:click="cancelApproval" class="rounded-lg border border-ink-200 px-4 py-2.5 text-sm font-bold text-ink-700 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('editorial.cancel') }}</button>
                    <button type="button" wire:click="approve" wire:loading.attr="disabled" wire:target="approve" @disabled(! $approvalConfirmed) class="rounded-lg bg-leaf-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-leaf-700 disabled:cursor-not-allowed disabled:opacity-50">{{ __('editorial.confirm_approve_and_publish') }}</button>
                </div>
            </section>
        </div>
    @endif
</div>
