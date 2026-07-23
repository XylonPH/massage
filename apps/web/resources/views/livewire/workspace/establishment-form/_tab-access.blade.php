{{-- Access and delivery --}}
<div x-show="tab === 'access'" x-cloak wire:key="tab-content-access" class="mt-5 space-y-5">
    <x-form.field :label="__('editorial.est_mode_service_delivery')">
        <x-form.toggle-group :options="$taxonomy['mode_service_delivery']" model="state.mode_service_delivery" :live="true" />
    </x-form.field>
    <div class="grid gap-5 sm:grid-cols-2">
        <x-form.field :label="__('editorial.est_mode_access')" :error="$errors->first('state.mode_access')">
            <x-form.select wire:model="state.mode_access" :options="$taxonomy['mode_access']" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_type_client_access')" :error="$errors->first('state.type_client_access')">
            <x-form.select wire:model="state.type_client_access" :options="$taxonomy['type_client_access']" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
    </div>
    <x-form.field :label="__('editorial.est_target_client_focus')">
        <x-form.toggle-group :options="$taxonomy['target_client_focus']" model="state.target_client_focus" />
    </x-form.field>
</div>
