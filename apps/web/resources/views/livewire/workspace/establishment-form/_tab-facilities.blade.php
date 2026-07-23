{{-- Facilities --}}
@if ($this->hasPhysicalPremises())
<div x-show="tab === 'facilities'" x-cloak wire:key="tab-content-facilities" class="mt-5 space-y-5">
    <div class="space-y-3">
        <p class="text-sm font-semibold text-ink-800 dark:text-ink-200">{{ __('editorial.treatment_areas') }}</p>
        @foreach ($state['treatment_area_list'] as $i => $row)
            <div class="grid gap-3 rounded-xl border border-ink-100 p-3 sm:grid-cols-2 lg:grid-cols-3 dark:border-ink-800" wire:key="treatment-{{ $i }}">
                <x-form.field :label="__('editorial.est_treatment_area_name')" :error="$errors->first('state.treatment_area_list.'.$i.'.treatment_area_name')">
                    <x-form.input wire:model="state.treatment_area_list.{{ $i }}.treatment_area_name" maxlength="255" />
                </x-form.field>
                <x-form.field :label="__('editorial.est_type_treatment_area')" :error="$errors->first('state.treatment_area_list.'.$i.'.type_treatment_area')">
                    <x-form.select wire:model="state.treatment_area_list.{{ $i }}.type_treatment_area" :options="$taxonomy['type_treatment_area']" :placeholder="__('editorial.select_placeholder')" />
                </x-form.field>
                <x-form.field :label="__('editorial.est_level_treatment_privacy')" :error="$errors->first('state.treatment_area_list.'.$i.'.level_treatment_privacy')">
                    <x-form.select wire:model="state.treatment_area_list.{{ $i }}.level_treatment_privacy" :options="$taxonomy['level_treatment_privacy']" :placeholder="__('editorial.select_placeholder')" />
                </x-form.field>
                <x-form.field :label="__('editorial.est_type_treatment_capacity')" :error="$errors->first('state.treatment_area_list.'.$i.'.type_treatment_capacity')">
                    <x-form.select wire:model="state.treatment_area_list.{{ $i }}.type_treatment_capacity" :options="$taxonomy['type_treatment_capacity']" :placeholder="__('editorial.select_placeholder')" />
                </x-form.field>
                <x-form.field :label="__('editorial.est_treatment_area_note')" :error="$errors->first('state.treatment_area_list.'.$i.'.treatment_area_note')">
                    <x-form.input wire:model="state.treatment_area_list.{{ $i }}.treatment_area_note" maxlength="255" />
                </x-form.field>
                <div class="flex items-end justify-end">
                    <button type="button" wire:click="removeRow('treatment_area_list', {{ $i }})" class="rounded-lg border border-ink-200 px-3 py-2 text-sm font-semibold text-ink-500 transition hover:border-ember-300 hover:text-ember-600 dark:border-ink-700 dark:text-ink-400">{{ __('editorial.remove') }}</button>
                </div>
            </div>
        @endforeach
        <button type="button" wire:click="addRow('treatment_area_list')" class="rounded-lg border border-dashed border-ink-300 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-ember-400 hover:text-ember-600 dark:border-ink-600 dark:text-ink-300">{{ __('editorial.add_row') }}</button>
    </div>

    @php($roomTypeIcons = [
        'ER' => 'M4 4h16v16H4Z',
        'PA' => 'M4 4h16v16H4Z M12 4v16',
        'OA' => 'M4 4v4 M4 4h4 M20 4v4 M20 4h-4 M4 20v-4 M4 20h4 M20 20v-4 M20 20h-4',
        'PS' => 'M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2 M4 7h16v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2Z',
    ])
    @php($bedSetupIcons = [
        'MB' => 'M3 16h18 M5 16v3 M19 16v3 M6 16V9a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v7',
        'FM' => 'M3 9h18v6H3Z',
        'MC' => 'M7 21v-7a4 4 0 0 1 4-4h2a4 4 0 0 1 4 4v7 M7 14H5v4h2 M17 14h2v4h-2 M9 10V6a2 2 0 0 1 4 0v4',
        'RC' => 'M4 20v-6a3 3 0 0 1 3-3h2 M4 20h16 M9 11V6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v5 M15 11h3a2 2 0 0 1 2 2v3h-5',
    ])
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <x-form.field :label="__('editorial.est_room_types')">
            <x-form.toggle-group :options="$taxonomy['room_types']" model="state.room_types" :icons="$roomTypeIcons" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_bed_mat_chair_setup')">
            <x-form.toggle-group :options="$taxonomy['bed_mat_chair_setup']" model="state.bed_mat_chair_setup" :icons="$bedSetupIcons" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_shower_availability')" :error="$errors->first('state.shower_availability')">
            <x-form.select wire:model="state.shower_availability" :options="$taxonomy['shower_availability']" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_sauna_availability')" :error="$errors->first('state.sauna_availability')">
            <x-form.select wire:model="state.sauna_availability" :options="$taxonomy['sauna_availability']" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_steam_room_availability')" :error="$errors->first('state.steam_room_availability')">
            <x-form.select wire:model="state.steam_room_availability" :options="$taxonomy['steam_room_availability']" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_jacuzzi_availability')" :error="$errors->first('state.jacuzzi_availability')">
            <x-form.select wire:model="state.jacuzzi_availability" :options="$taxonomy['jacuzzi_availability']" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_locker_availability')" :error="$errors->first('state.locker_availability')">
            <x-form.select wire:model="state.locker_availability" :options="$taxonomy['locker_availability']" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_couple_room_availability')" :error="$errors->first('state.couple_room_availability')">
            <x-form.select wire:model="state.couple_room_availability" :options="$taxonomy['couple_room_availability']" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_private_room_availability')" :error="$errors->first('state.private_room_availability')">
            <x-form.select wire:model="state.private_room_availability" :options="$taxonomy['private_room_availability']" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_curtain_divider_information')" :error="$errors->first('state.curtain_divider_information')">
            <x-form.select wire:model="state.curtain_divider_information" :options="$taxonomy['curtain_divider_information']" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_air_conditioning_information')" :error="$errors->first('state.air_conditioning_information')">
            <x-form.select wire:model="state.air_conditioning_information" :options="$taxonomy['air_conditioning_information']" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
    </div>
</div>
@endif
