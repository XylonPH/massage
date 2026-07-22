{{-- Operating hours --}}
<div x-show="tab === 'hours'" x-cloak class="mt-5 space-y-3">
    <p class="text-sm font-semibold text-ink-800 dark:text-ink-200">{{ __('editorial.operating_hours') }}</p>
    @foreach ($state['operating_hours'] as $i => $row)
        <div class="grid gap-3 rounded-xl border border-ink-100 p-3 sm:grid-cols-[1fr_1fr_1fr_auto] dark:border-ink-800" wire:key="hours-{{ $i }}">
            <x-form.field :label="__('editorial.day_of_week')" :error="$errors->first('state.operating_hours.'.$i.'.day_of_week')">
                <x-form.select wire:model="state.operating_hours.{{ $i }}.day_of_week" :options="$dayOfWeekOptions" :placeholder="__('editorial.select_placeholder')" />
            </x-form.field>
            <x-form.field :label="__('editorial.open_time')" :error="$errors->first('state.operating_hours.'.$i.'.open_time')">
                <x-form.input wire:model="state.operating_hours.{{ $i }}.open_time" type="time" />
            </x-form.field>
            <x-form.field :label="__('editorial.close_time')" :error="$errors->first('state.operating_hours.'.$i.'.close_time')">
                <x-form.input wire:model="state.operating_hours.{{ $i }}.close_time" type="time" />
            </x-form.field>
            <button type="button" wire:click="removeRow('operating_hours', {{ $i }})" class="self-end rounded-lg border border-ink-200 px-3 py-2 text-sm font-semibold text-ink-500 transition hover:border-ember-300 hover:text-ember-600 dark:border-ink-700 dark:text-ink-400">{{ __('editorial.remove') }}</button>
        </div>
    @endforeach
    <button type="button" wire:click="addRow('operating_hours')" class="rounded-lg border border-dashed border-ink-300 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-ember-400 hover:text-ember-600 dark:border-ink-600 dark:text-ink-300">{{ __('editorial.add_row') }}</button>
</div>
