<div class="space-y-5">
    <h2 class="text-lg font-bold text-ink-950 dark:text-ink-50">{{ __('workspace.add_spa_step_who_you_are') }}</h2>

    <x-form.field :label="__('workspace.contribution_connection_label')" :error="$errors->first('type_establishment_relationship')">
        <x-form.select wire:model="type_establishment_relationship" :options="$relationshipOptions" />
    </x-form.field>
    <p class="text-sm text-ink-500 dark:text-ink-400">{{ __('workspace.contribution_relationship_hint') }}</p>

    <x-form.field :label="__('workspace.contribution_relationship_note_label')" :error="$errors->first('relationship_note')">
        <x-form.textarea wire:model="relationship_note" rows="3" maxlength="1000" />
    </x-form.field>

    <div class="rounded-xl border border-ink-200 bg-ink-50 p-4 dark:border-ink-700 dark:bg-ink-800">
        <label class="flex items-start gap-3">
            <input type="checkbox" wire:model="is_workspace_access_requested" class="mt-1 rounded border-ink-300 text-ember-600 focus:ring-ember-500">
            <span>
                <span class="block font-bold text-ink-900 dark:text-ink-100">{{ __('workspace.contribution_access_label') }}</span>
                <span class="mt-1 block text-sm text-ink-600 dark:text-ink-300">{{ __('workspace.contribution_access_hint') }}</span>
            </span>
        </label>
        @error('is_workspace_access_requested')<p class="mt-2 text-sm text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
    </div>

    <div class="flex justify-end border-t border-ink-100 pt-5 dark:border-ink-800">
        <button type="button" wire:click="nextStep" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('editorial.next') }}</button>
    </div>
</div>
