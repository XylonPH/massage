<div class="space-y-6">
    <div class="border-b border-ink-100 pb-4 dark:border-ink-800">
        <h2 class="text-xl font-black text-ink-950 dark:text-ink-50">{{ __('workspace.add_spa_step_who_you_are') }}</h2>
        <p class="mt-1 text-xs text-ink-500 dark:text-ink-400">Declare your factual connection to this establishment for verification.</p>
    </div>

    <div>
        <x-form.field :label="__('workspace.contribution_connection_label')" :error="$errors->first('type_establishment_relationship')">
            <x-form.select wire:model="type_establishment_relationship" :options="$relationshipOptions" />
        </x-form.field>
        <p class="mt-1.5 text-xs text-ink-500 dark:text-ink-400">{{ __('workspace.contribution_relationship_hint') }}</p>
    </div>

    <div>
        <x-form.field :label="__('workspace.contribution_relationship_note_label')" :error="$errors->first('relationship_note')">
            <x-form.textarea wire:model="relationship_note" rows="3" maxlength="1000" placeholder="Provide any additional context regarding your relationship or ownership..." />
        </x-form.field>
    </div>

    <div class="rounded-2xl border border-ink-100 bg-ink-50/60 p-4 transition hover:border-ember-300 dark:border-ink-800 dark:bg-ink-950/60 dark:hover:border-ember-700">
        <label class="flex cursor-pointer items-start gap-3.5">
            <input type="checkbox" wire:model="is_workspace_access_requested" class="mt-1 size-4 rounded border-ink-300 text-ember-600 focus:ring-ember-500 dark:border-ink-700">
            <div>
                <span class="block text-sm font-bold text-ink-950 dark:text-ink-50">{{ __('workspace.contribution_access_label') }}</span>
                <span class="mt-0.5 block text-xs leading-relaxed text-ink-600 dark:text-ink-300">{{ __('workspace.contribution_access_hint') }}</span>
            </div>
        </label>
        @error('is_workspace_access_requested')<p class="mt-2 text-xs font-semibold text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
    </div>

    <div class="flex justify-end border-t border-ink-100 pt-6 dark:border-ink-800">
        <button type="button" wire:click="nextStep" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-ember-500 to-ember-600 px-6 py-2.5 text-sm font-bold text-white shadow-2xs transition hover:from-ember-600 hover:to-ember-700 hover:shadow-md">
            <span>{{ __('editorial.next') }}</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14 M12 5l7 7-7 7"/></svg>
        </button>
    </div>
</div>
