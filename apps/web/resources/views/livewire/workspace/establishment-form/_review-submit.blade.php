<div class="space-y-6">
    <div class="border-b border-ink-100 pb-4 dark:border-ink-800">
        <h2 class="text-xl font-black text-ink-950 dark:text-ink-50">{{ __('workspace.add_spa_step_review') }}</h2>
        <p class="mt-1 text-xs text-ink-500 dark:text-ink-400">{{ __('workspace.add_spa_review_intro') }}</p>
    </div>

    {{-- Submission Preview Card --}}
    <div class="rounded-2xl border border-ink-100 bg-ink-50/70 p-5 shadow-2xs dark:border-ink-800 dark:bg-ink-950/70">
        <div class="flex items-center gap-3">
            <div class="inline-flex size-10 shrink-0 items-center justify-center rounded-xl bg-white text-ember-600 shadow-2xs dark:bg-ink-900 dark:text-ember-400">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5" aria-hidden="true">
                    <path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-base text-ink-950 dark:text-ink-50">{{ $state['display_name_eng'] }}</p>
                <p class="mt-0.5 text-xs text-ink-600 dark:text-ink-300">{{ $state['address_public'] ?: 'No address specified' }}</p>
            </div>
        </div>
    </div>

    {{-- Duplicate Candidate Warning Box --}}
    @if ($duplicateCandidates->isNotEmpty())
        <div class="rounded-2xl border border-amber-300 bg-amber-50/90 p-5 shadow-2xs dark:border-amber-800 dark:bg-amber-950/90">
            <div class="flex items-start gap-3">
                <div class="inline-flex size-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <p class="font-bold text-amber-900 dark:text-amber-200">{{ __('workspace.add_spa_duplicate_warning_title') }}</p>
                    <ul class="mt-2 space-y-1.5 text-xs font-medium text-amber-800 dark:text-amber-300">
                        @foreach ($duplicateCandidates as $candidate)
                            <li class="flex items-center gap-2">
                                <span class="size-1.5 rounded-full bg-amber-500"></span>
                                <span>{{ $candidate['display_name'] }} — {{ $candidate['address_public'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <label class="mt-4 flex cursor-pointer items-center gap-2.5 text-xs font-bold text-amber-900 dark:text-amber-200">
                        <input type="checkbox" wire:model.live="duplicateAcknowledged" class="size-4 rounded border-amber-400 text-ember-600 focus:ring-ember-500">
                        <span>{{ __('workspace.add_spa_duplicate_confirm') }}</span>
                    </label>
                    @error('duplicateAcknowledged')<p class="mt-2 text-xs font-semibold text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    @endif

    {{-- Verification Visit Request Box --}}
    @if ($this->visitEligible())
        <div class="rounded-2xl border border-ink-200 bg-white p-5 shadow-2xs dark:border-ink-700 dark:bg-ink-950">
            <label class="flex cursor-pointer items-start gap-3">
                <input type="checkbox" wire:model.live="is_visit_requested" class="mt-0.5 size-4 rounded border-ink-300 text-ember-600 focus:ring-ember-500">
                <span class="font-bold text-sm text-ink-950 dark:text-ink-50">{{ __('workspace.add_spa_visit_label') }}</span>
            </label>
            @if ($is_visit_requested)
                <x-form.field :label="__('workspace.add_spa_visit_time_label')" :error="$errors->first('visit_preferred_time_note')" class="mt-4">
                    <x-form.input wire:model="visit_preferred_time_note" maxlength="500" placeholder="e.g. Weekdays between 10am and 3pm" />
                </x-form.field>
            @endif
        </div>
    @endif

    {{-- Reviewer Submission Note --}}
    <div>
        <x-form.field :label="__('workspace.contribution_submission_note_label')" :error="$errors->first('submission_note')">
            <x-form.textarea wire:model="submission_note" rows="3" maxlength="2000" placeholder="Any special notes or verification sources for the editorial team..." />
        </x-form.field>
    </div>

    {{-- Action Controls --}}
    <div class="flex items-center justify-between gap-3 border-t border-ink-100 pt-6 dark:border-ink-800">
        <button type="button" wire:click="prevStep" class="inline-flex items-center gap-2 rounded-xl border border-ink-200 bg-white px-5 py-2.5 text-sm font-bold text-ink-800 shadow-2xs transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-200 dark:hover:bg-ink-800">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5 M12 19l-7-7 7-7"/></svg>
            <span>{{ __('editorial.back') }}</span>
        </button>
        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-ember-500 to-ember-600 px-6 py-2.5 text-sm font-bold text-white shadow-2xs transition hover:from-ember-600 hover:to-ember-700 hover:shadow-md">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span>{{ __('workspace.contribution_submit') }}</span>
        </button>
    </div>
</div>
