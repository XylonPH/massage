<div>
    <a href="{{ route('workspace.editorial.contribution.index') }}" wire:navigate class="mb-4 inline-block text-sm font-semibold text-ink-600 hover:text-ember-700 dark:text-ink-300 dark:hover:text-ember-400">{{ __('editorial.back_to_contributions') }}</a>

    @php($displayName = data_get($record->proposed_data, 'establishment.display_name.eng.text', __('editorial.untitled_contribution')))
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_20rem]">
        <div class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
            <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ $displayName }}</h1>
            <p class="mt-1 text-sm text-ink-500 dark:text-ink-400">{{ __('editorial.submitted', ['date' => $record->submitted_at?->format('M j, Y g:i A')]) }}</p>

            @if (! empty($record->duplicate_candidate_establishment_id_list))
                <div class="mt-4 rounded-xl border border-ember-200 bg-ember-50 p-4 text-sm text-ember-900 dark:border-ember-800 dark:bg-ember-950/60 dark:text-ember-200">
                    {{ __('editorial.possible_duplicate') }}: {{ implode(', ', $record->duplicate_candidate_establishment_id_list) }}
                </div>
            @endif

            @if (filled($record->relationship_note))
                <p class="mt-4"><span class="font-bold">{{ __('editorial.relationship_note_label') }}:</span> {{ $record->relationship_note }}</p>
            @endif
            @if (filled($record->submission_note))
                <p class="mt-2"><span class="font-bold">{{ __('editorial.submission_note_label') }}:</span> {{ $record->submission_note }}</p>
            @endif
            @if ($record->is_visit_requested)
                <p class="mt-2"><span class="font-bold">{{ __('editorial.visit_requested_label') }}:</span> {{ $record->visit_preferred_time_note }}</p>
            @endif

            <pre class="mt-6 overflow-x-auto rounded-xl bg-ink-50 p-4 text-xs dark:bg-ink-950">{{ json_encode($record->proposed_data, JSON_PRETTY_PRINT) }}</pre>
        </div>

        <aside class="sticky top-6 self-start rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">
            <h2 class="font-black text-ink-950 dark:text-white">{{ __('editorial.decision_title') }}</h2>
            <label class="mt-4 block">
                <span class="text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">{{ __('editorial.decision_note_label') }}</span>
                <textarea wire:model="decisionNote" maxlength="2000" rows="4" class="mt-1 w-full rounded-xl border border-ink-200 px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white"></textarea>
                @error('decisionNote')<p class="mt-1 text-xs font-semibold text-ember-700 dark:text-ember-300">{{ $message }}</p>@enderror
            </label>

            <div class="mt-5 flex flex-col gap-2">
                <button type="button" wire:click="requestApproval" class="rounded-xl bg-leaf-500 px-4 py-2.5 text-sm font-bold text-white hover:bg-leaf-600">{{ __('editorial.approve') }}</button>
                <button type="button" wire:click="reject" wire:confirm="{{ __('editorial.reject_contribution_confirm') }}" class="rounded-xl border border-ember-300 bg-ember-50 px-4 py-2.5 text-sm font-bold text-ember-800 hover:bg-ember-100 dark:border-ember-800 dark:bg-ember-950 dark:text-ember-300">{{ __('editorial.reject') }}</button>
            </div>
        </aside>
    </div>

    @if ($showApprovalConfirmation)
        <div role="dialog" aria-modal="true" class="fixed inset-0 z-50 flex items-center justify-center bg-ink-950/50 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-ink-900">
                <h3 class="font-black text-ink-950 dark:text-white">{{ __('editorial.approval_confirmation_title') }}</h3>
                <p class="mt-2 text-sm text-ink-600 dark:text-ink-300">{{ __('editorial.approval_confirmation_body') }}</p>
                <label class="mt-4 flex items-start gap-2 text-sm">
                    <input type="checkbox" wire:model.live="approvalConfirmed" class="mt-0.5">
                    <span>{{ __('editorial.approval_confirmation_checkbox') }}</span>
                </label>
                @error('approvalConfirmed')<p class="mt-1 text-xs font-semibold text-ember-700 dark:text-ember-300">{{ $message }}</p>@enderror
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" wire:click="cancelApproval" class="rounded-xl px-4 py-2 text-sm font-bold text-ink-600 hover:bg-ink-100 dark:text-ink-300 dark:hover:bg-ink-800">{{ __('editorial.cancel') }}</button>
                    <button type="button" wire:click="approve" wire:loading.attr="disabled" wire:target="approve" @disabled(! $approvalConfirmed) class="rounded-xl bg-leaf-500 px-4 py-2 text-sm font-bold text-white hover:bg-leaf-600 disabled:opacity-50">{{ __('editorial.confirm_approval') }}</button>
                </div>
            </div>
        </div>
    @endif
</div>
