{{-- Classification --}}
<div x-show="tab === 'classification'" x-cloak class="mt-5 grid gap-5 sm:grid-cols-2">
    <x-form.field :label="__('editorial.est_type_spa')" :error="$errors->first('state.type_spa')">
        <x-form.select wire:model="state.type_spa" :options="$taxonomy['type_spa']" :placeholder="__('editorial.select_placeholder')" />
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
        <x-form.select wire:model="state.status_establishment" :options="$taxonomy['status_establishment']" :placeholder="__('editorial.select_placeholder')" />
    </x-form.field>
</div>
