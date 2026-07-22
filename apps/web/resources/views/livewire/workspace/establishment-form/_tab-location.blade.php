{{-- Location --}}
<div x-show="tab === 'location'" x-cloak class="mt-5 space-y-5">
    <x-form.field :label="__('editorial.est_address_public')" :error="$errors->first('state.address_public')">
        <x-form.textarea wire:model="state.address_public" rows="2" />
    </x-form.field>
    <div class="grid gap-5 sm:grid-cols-2">
        <x-form.field :label="__('editorial.est_coordinate_latitude')" :error="$errors->first('state.coordinate_latitude')">
            <x-form.input wire:model="state.coordinate_latitude" type="number" step="any" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_coordinate_longitude')" :error="$errors->first('state.coordinate_longitude')">
            <x-form.input wire:model="state.coordinate_longitude" type="number" step="any" />
        </x-form.field>
    </div>
    <x-form.field :label="__('editorial.est_direction_note_eng')" :error="$errors->first('state.direction_note_eng')">
        <x-form.textarea wire:model="state.direction_note_eng" rows="2" />
    </x-form.field>
    <x-form.field :label="__('editorial.est_parking_note_eng')" :error="$errors->first('state.parking_note_eng')">
        <x-form.textarea wire:model="state.parking_note_eng" rows="2" />
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
            </div>
        @endforeach
        <button type="button" wire:click="addRow('landmark_list')" class="rounded-lg border border-dashed border-ink-300 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-ember-400 hover:text-ember-600 dark:border-ink-600 dark:text-ink-300">{{ __('editorial.add_row') }}</button>
    </div>
</div>
