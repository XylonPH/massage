{{-- Classification --}}
<div x-show="tab === 'classification'" x-cloak class="mt-5 grid gap-5 sm:grid-cols-2">
    <x-form.field :label="__('editorial.est_type_spa')" :error="$errors->first('state.type_spa')">
        <x-form.select wire:model.live="state.type_spa" :options="$taxonomy['type_spa']" :placeholder="__('editorial.select_placeholder')" />
    </x-form.field>
    <x-form.field :label="__('editorial.est_level_spa_market')" :error="$errors->first('state.level_spa_market')">
        <x-form.select wire:model="state.level_spa_market" :options="$taxonomy['level_spa_market']" :placeholder="__('editorial.select_placeholder')" />
    </x-form.field>
    <x-form.field :label="__('editorial.est_type_physical_setting')" :error="$errors->first('state.type_physical_setting')">
        <x-form.select wire:model="state.type_physical_setting" :options="$taxonomy['type_physical_setting']" :placeholder="__('editorial.select_placeholder')" />
    </x-form.field>
    <x-form.field :label="__('editorial.est_type_establishment_operation')" :error="$errors->first('state.type_establishment_operation')">
        <x-form.select wire:model="state.type_establishment_operation" :options="$taxonomy['type_establishment_operation']" :placeholder="__('editorial.select_placeholder')" />
    </x-form.field>
    <x-form.field :label="__('editorial.est_status_establishment')" :error="$errors->first('state.status_establishment')">
        <x-form.select wire:model.live="state.status_establishment" :options="$taxonomy['status_establishment']" :placeholder="__('editorial.select_placeholder')" />
    </x-form.field>

    <x-form.field :label="__('editorial.est_date_opened')" :error="$errors->first('state.date_opened')">
        <x-form.input wire:model="state.date_opened" type="date" />
    </x-form.field>
    <x-form.field :label="__('editorial.date_precision_label')">
        <x-form.select wire:model="state.date_opened_precision" :options="['D' => 'Day', 'M' => 'Month', 'Y' => 'Year']" :placeholder="__('editorial.select_placeholder')" />
    </x-form.field>
    <label class="flex items-center gap-2 text-sm text-ink-700 dark:text-ink-300">
        <input type="checkbox" wire:model="date_opened_is_approximate" class="rounded border-ink-300 text-ember-600 focus:ring-ember-500">
        {{ __('editorial.date_approximate_label') }}
    </label>

    @if (in_array($state['status_establishment'], ['TC', 'PC', 'RL'], true))
        <x-form.field :label="__('editorial.est_date_closed')" :error="$errors->first('state.date_closed')">
            <x-form.input wire:model="state.date_closed" type="date" />
        </x-form.field>
        <x-form.field :label="__('editorial.date_precision_label')">
            <x-form.select wire:model="state.date_closed_precision" :options="['D' => 'Day', 'M' => 'Month', 'Y' => 'Year']" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
        <label class="flex items-center gap-2 text-sm text-ink-700 dark:text-ink-300">
            <input type="checkbox" wire:model="date_closed_is_approximate" class="rounded border-ink-300 text-ember-600 focus:ring-ember-500">
            {{ __('editorial.date_approximate_label') }}
        </label>
    @endif
</div>
