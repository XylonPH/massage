<div class="mx-auto max-w-3xl">
    <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ $quote ? __('editorial.edit') : __('editorial.new') }} — {{ __('editorial.quotes') }}</h1>

    <form wire:submit="save" class="mt-6 space-y-5 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
        <x-form.field :label="__('editorial.quote_text')" :error="$errors->first('state.english_text')">
            <x-form.textarea wire:model="state.english_text" rows="4" maxlength="500" />
        </x-form.field>

        <div class="grid gap-5 sm:grid-cols-2">
            <x-form.field :label="__('editorial.attribution_name')" :error="$errors->first('state.attribution_name')">
                <x-form.input wire:model="state.attribution_name" maxlength="150" />
            </x-form.field>
            <x-form.field :label="__('editorial.source_title')" :error="$errors->first('state.source_title')">
                <x-form.input wire:model="state.source_title" maxlength="200" />
            </x-form.field>
        </div>

        <x-form.field :label="__('editorial.source_url')" :error="$errors->first('state.source_url')">
            <x-form.input wire:model="state.source_url" type="url" maxlength="500" />
        </x-form.field>

        <x-form.field :label="__('editorial.quote_category')" :error="$errors->first('state.type_quote_category')">
            <x-form.toggle-group :options="$categoryOptions" model="state.type_quote_category" />
        </x-form.field>

        <div class="grid gap-5 sm:grid-cols-3">
            <x-form.field :label="__('editorial.display_start_date')" :error="$errors->first('state.display_start_date')">
                <x-form.input wire:model="state.display_start_date" type="date" />
            </x-form.field>
            <x-form.field :label="__('editorial.display_end_date')" :error="$errors->first('state.display_end_date')">
                <x-form.input wire:model="state.display_end_date" type="date" />
            </x-form.field>
            <x-form.field :label="__('editorial.display_enabled')">
                <label class="mt-2 inline-flex cursor-pointer items-center gap-2 text-sm font-semibold text-ink-700 dark:text-ink-200">
                    <input type="checkbox" wire:model="state.is_display_enabled" class="size-4 rounded border-ink-300 text-ember-500 focus:ring-ember-400">
                </label>
            </x-form.field>
        </div>

        <div class="grid gap-5 sm:grid-cols-3">
            <x-form.field :label="__('editorial.review_status')" :error="$errors->first('state.status_review')">
                <x-form.select wire:model="state.status_review" :options="$reviewOptions" />
            </x-form.field>
            <x-form.field :label="__('editorial.nsfw_level')" :error="$errors->first('state.level_nsfw')">
                <x-form.select wire:model="state.level_nsfw" :options="$nsfwOptions" />
            </x-form.field>
            <x-form.field :label="__('editorial.lifecycle_status')" :error="$errors->first('state.status_record_lifecycle')">
                <x-form.select wire:model="state.status_record_lifecycle" :options="$lifecycleOptions" />
            </x-form.field>
        </div>

        <div class="flex items-center justify-end gap-2.5 border-t border-ink-100 pt-5 dark:border-ink-800">
            <a href="{{ route('workspace.editorial.quote.index') }}" wire:navigate class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('editorial.cancel') }}</a>
            <button type="submit" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('editorial.save') }}</button>
        </div>
    </form>
</div>
