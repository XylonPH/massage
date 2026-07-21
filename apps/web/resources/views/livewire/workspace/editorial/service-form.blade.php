<div class="mx-auto max-w-3xl">
    <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ $service ? __('editorial.edit') : __('editorial.new') }} — {{ __('editorial.services') }}</h1>

    <form wire:submit="save" class="mt-6 space-y-5 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
        <div x-data="{ tab: 'eng' }">
            <x-editorial.tab-bar :tabs="['eng' => __('editorial.tab_english'), 'zho' => __('editorial.tab_chinese')]" />

            <div x-show="tab === 'eng'" class="mt-5 space-y-5">
                <x-form.field :label="__('editorial.service_name')" :error="$errors->first('state.english_name')">
                    <x-form.input wire:model="state.english_name" maxlength="150" />
                </x-form.field>
                <x-form.field :label="__('editorial.short_description')" :error="$errors->first('state.english_short_description')">
                    <x-form.textarea wire:model="state.english_short_description" rows="2" maxlength="300" />
                </x-form.field>
                <x-form.field :label="__('editorial.overview')" :error="$errors->first('state.english_overview')">
                    <x-form.textarea wire:model="state.english_overview" rows="6" maxlength="2000" />
                </x-form.field>
            </div>

            <div x-show="tab === 'zho'" x-cloak class="mt-5 space-y-5">
                <x-form.field :label="__('editorial.service_name')" :error="$errors->first('state.chinese_name')">
                    <x-form.input wire:model="state.chinese_name" maxlength="150" />
                </x-form.field>
                <x-form.field :label="__('editorial.short_description')" :error="$errors->first('state.chinese_short_description')">
                    <x-form.textarea wire:model="state.chinese_short_description" rows="2" maxlength="300" />
                </x-form.field>
                <x-form.field :label="__('editorial.overview')" :error="$errors->first('state.chinese_overview')">
                    <x-form.textarea wire:model="state.chinese_overview" rows="6" maxlength="2000" />
                </x-form.field>
            </div>
        </div>

        <x-form.field :label="__('editorial.slug')" :error="$errors->first('state.service_slug')">
            <x-form.input wire:model="state.service_slug" maxlength="100" />
        </x-form.field>

        <div class="grid gap-5 sm:grid-cols-3">
            <x-form.field :label="__('editorial.sector')" :error="$errors->first('state.group_service_sector')">
                <x-form.input wire:model="state.group_service_sector" maxlength="100" />
            </x-form.field>
            <x-form.field :label="__('editorial.domain')" :error="$errors->first('state.group_service_domain')">
                <x-form.input wire:model="state.group_service_domain" maxlength="100" />
            </x-form.field>
            <x-form.field :label="__('editorial.family')" :error="$errors->first('state.group_service_family')">
                <x-form.input wire:model="state.group_service_family" maxlength="100" />
            </x-form.field>
        </div>

        <x-form.field :label="__('editorial.lifecycle_status')" :error="$errors->first('state.status_record_lifecycle')">
            <x-form.select wire:model="state.status_record_lifecycle" :options="$lifecycleOptions" />
        </x-form.field>

        <div class="flex items-center justify-end gap-2.5 border-t border-ink-100 pt-5 dark:border-ink-800">
            <a href="{{ route('workspace.editorial.service.index') }}" wire:navigate class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('editorial.cancel') }}</a>
            <button type="submit" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('editorial.save') }}</button>
        </div>
    </form>
</div>
