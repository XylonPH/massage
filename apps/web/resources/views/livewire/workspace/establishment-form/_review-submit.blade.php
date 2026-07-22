<div class="space-y-5">
    <h2 class="text-lg font-bold text-ink-950 dark:text-ink-50">{{ __('workspace.add_spa_step_review') }}</h2>
    <p class="text-sm text-ink-600 dark:text-ink-300">{{ __('workspace.add_spa_review_intro') }}</p>

    <div class="rounded-xl border border-ink-100 bg-ink-50 p-4 text-sm dark:border-ink-800 dark:bg-ink-900">
        <p class="font-bold text-ink-900 dark:text-ink-100">{{ $state['display_name_eng'] }}</p>
        <p class="mt-1 text-ink-600 dark:text-ink-300">{{ $state['address_public'] }}</p>
    </div>

    @if ($duplicateCandidates->isNotEmpty())
        <div class="rounded-xl border border-amber-300 bg-amber-50 p-4 dark:border-amber-800 dark:bg-amber-950">
            <p class="font-bold text-amber-900 dark:text-amber-200">{{ __('workspace.add_spa_duplicate_warning_title') }}</p>
            <ul class="mt-2 space-y-1 text-sm text-amber-800 dark:text-amber-300">
                @foreach ($duplicateCandidates as $candidate)
                    <li>{{ $candidate['display_name'] }} — {{ $candidate['address_public'] }}</li>
                @endforeach
            </ul>
            <label class="mt-3 flex items-center gap-2 text-sm font-semibold text-amber-900 dark:text-amber-200">
                <input type="checkbox" wire:model.live="duplicateAcknowledged" class="rounded border-amber-400 text-ember-600 focus:ring-ember-500">
                {{ __('workspace.add_spa_duplicate_confirm') }}
            </label>
            @error('duplicateAcknowledged')<p class="mt-1 text-sm text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
        </div>
    @endif

    @if ($this->visitEligible())
        <div class="rounded-xl border border-ink-200 p-4 dark:border-ink-700">
            <label class="flex items-start gap-3">
                <input type="checkbox" wire:model.live="is_visit_requested" class="mt-1 rounded border-ink-300 text-ember-600 focus:ring-ember-500">
                <span class="font-bold text-ink-900 dark:text-ink-100">{{ __('workspace.add_spa_visit_label') }}</span>
            </label>
            @if ($is_visit_requested)
                <x-form.field :label="__('workspace.add_spa_visit_time_label')" :error="$errors->first('visit_preferred_time_note')" class="mt-3">
                    <x-form.input wire:model="visit_preferred_time_note" maxlength="500" />
                </x-form.field>
            @endif
        </div>
    @endif

    <x-form.field :label="__('workspace.contribution_submission_note_label')" :error="$errors->first('submission_note')">
        <x-form.textarea wire:model="submission_note" rows="3" maxlength="2000" />
    </x-form.field>

    <div class="flex items-center justify-between gap-2.5 border-t border-ink-100 pt-5 dark:border-ink-800">
        <button type="button" wire:click="prevStep" class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('editorial.back') }}</button>
        <button type="submit" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('workspace.contribution_submit') }}</button>
    </div>
</div>
