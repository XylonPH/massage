{{-- Location --}}
<div x-show="tab === 'location'" x-cloak class="mt-5 space-y-5">
    <x-form.field :label="__('editorial.est_official_name')" :error="$errors->first('state.official_name')">
        <x-form.input wire:model="state.official_name" maxlength="255" />
    </x-form.field>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-form.field :label="__('editorial.est_country')" :error="$errors->first('state.country_id')">
            <x-form.select wire:model.live="state.country_id" :options="app(\App\Support\Address\AddressLookup::class)->countries()" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_region')" :error="$errors->first('state.region_id')">
            <x-form.select wire:model.live="state.region_id" :options="filled($state['country_id']) ? app(\App\Support\Address\AddressLookup::class)->regions((int) $state['country_id']) : []" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
    </div>

    @php($cities = $this->citiesForSelectedRegion())
    <x-form.field :label="__('editorial.est_city')" :error="$errors->first('state.city_name')">
        @if (count($cities))
            <x-form.select wire:model="state.city_name" :options="$cities" :placeholder="__('editorial.select_placeholder')" />
        @else
            <x-form.input wire:model="state.city_name" maxlength="255" />
        @endif
    </x-form.field>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-form.field :label="__('editorial.est_street_address')" :error="$errors->first('state.street_address')">
            <x-form.input wire:model="state.street_address" maxlength="255" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_building_name')" :error="$errors->first('state.building_name')">
            <x-form.input wire:model="state.building_name" maxlength="255" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_floor_label')" :error="$errors->first('state.floor_label')">
            <x-form.input wire:model="state.floor_label" maxlength="50" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_unit_label')" :error="$errors->first('state.unit_label')">
            <x-form.input wire:model="state.unit_label" maxlength="50" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_postal_code')" :error="$errors->first('state.postal_code')">
            <x-form.input wire:model="state.postal_code" maxlength="20" />
        </x-form.field>
    </div>

    <button type="button" wire:click="composeAddressPublic" class="rounded-lg border border-dashed border-ink-300 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-ember-400 hover:text-ember-600 dark:border-ink-600 dark:text-ink-300">{{ __('editorial.compose_address_action') }}</button>

    <x-form.field :label="__('editorial.est_address_public')" :error="$errors->first('state.address_public')">
        <x-form.textarea wire:model="state.address_public" rows="2" />
    </x-form.field>

    <div wire:ignore
         data-map-picker
         data-lat-input="state.coordinate_latitude"
         data-lng-input="state.coordinate_longitude"
         data-lat="{{ $state['coordinate_latitude'] ?: 14.5995 }}"
         data-lng="{{ $state['coordinate_longitude'] ?: 120.9842 }}"
         class="space-y-2">
        <x-form.field :label="__('editorial.map_picker_label')" :help="__('editorial.map_picker_hint')">
            <div data-map-picker-canvas class="h-64 w-full rounded-xl border border-ink-200 dark:border-ink-700"></div>
        </x-form.field>
    </div>
    <div class="grid gap-5 sm:grid-cols-2">
        <x-form.field :label="__('editorial.est_coordinate_latitude')" :error="$errors->first('state.coordinate_latitude')">
            <x-form.input wire:model="state.coordinate_latitude" type="number" step="any" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_coordinate_longitude')" :error="$errors->first('state.coordinate_longitude')">
            <x-form.input wire:model="state.coordinate_longitude" type="number" step="any" />
        </x-form.field>
    </div>

    @include('livewire.workspace.establishment-form._language-switcher')

    <x-form.field :label="__('editorial.est_direction_note_eng')" :error="$errors->first('state.direction_note_'.$activeLanguageTab)">
        <x-form.textarea wire:model="state.direction_note_{{ $activeLanguageTab }}" rows="2" />
    </x-form.field>

    <x-form.field :label="__('editorial.est_parking_availability')">
        <x-form.toggle-group :options="$taxonomy['parking_availability']" model="state.parking_availability_list" />
    </x-form.field>
    <x-form.field :label="__('editorial.est_parking_note_eng')" :error="$errors->first('state.parking_note_'.$activeLanguageTab)">
        <x-form.textarea wire:model="state.parking_note_{{ $activeLanguageTab }}" rows="2" />
    </x-form.field>

    <div class="space-y-3">
        <p class="text-sm font-semibold text-ink-800 dark:text-ink-200">{{ __('editorial.landmarks') }}</p>
        @foreach ($state['landmark_list'] as $i => $row)
            <div class="grid gap-3 rounded-xl border border-ink-100 p-3 sm:grid-cols-[1fr_10rem_auto] dark:border-ink-800" wire:key="landmark-{{ $i }}">
                <x-form.field :label="__('editorial.landmark_name')" :error="$errors->first('state.landmark_list.'.$i.'.landmark_name')">
                    <x-form.input wire:model="state.landmark_list.{{ $i }}.landmark_name" />
                </x-form.field>
                <x-form.field :label="__('editorial.walking_minutes')" :error="$errors->first('state.landmark_list.'.$i.'.walking_duration_minute')">
                    <x-form.input wire:model="state.landmark_list.{{ $i }}.walking_duration_minute" type="number" min="0" />
                </x-form.field>
                <button type="button" wire:click="removeRow('landmark_list', {{ $i }})" class="self-end rounded-lg border border-ink-200 px-3 py-2 text-sm font-semibold text-ink-500 transition hover:border-ember-300 hover:text-ember-600 dark:border-ink-700 dark:text-ink-400">{{ __('editorial.remove') }}</button>
                <div class="sm:col-span-3">
                    <x-form.field :label="__('editorial.landmark_direction_note')" :error="$errors->first('state.landmark_list.'.$i.'.direction_note_eng')">
                        <x-form.input wire:model="state.landmark_list.{{ $i }}.direction_note_eng" maxlength="255" />
                    </x-form.field>
                </div>
            </div>
        @endforeach
        <button type="button" wire:click="addRow('landmark_list')" class="rounded-lg border border-dashed border-ink-300 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-ember-400 hover:text-ember-600 dark:border-ink-600 dark:text-ink-300">{{ __('editorial.add_row') }}</button>
    </div>
</div>
