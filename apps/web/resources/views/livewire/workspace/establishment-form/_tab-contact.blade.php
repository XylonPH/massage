{{-- Contact --}}
<div x-show="tab === 'contact'" x-cloak class="mt-5 space-y-3">
    <p class="text-sm font-semibold text-ink-800 dark:text-ink-200">{{ __('editorial.contact_channels') }}</p>
    @foreach ($state['contact_channel_list'] as $i => $row)
        <div class="grid gap-3 rounded-xl border border-ink-100 p-3 sm:grid-cols-2 lg:grid-cols-3 dark:border-ink-800" wire:key="contact-{{ $i }}">
            <x-form.field :label="__('editorial.est_type_contact_channel')" :error="$errors->first('state.contact_channel_list.'.$i.'.type_contact_channel')">
                <x-form.select wire:model="state.contact_channel_list.{{ $i }}.type_contact_channel" :options="$taxonomy['type_contact_channel']" :placeholder="__('editorial.select_placeholder')" />
            </x-form.field>
            @if ($this->channelNeedsPhoneType($row['type_contact_channel'] ?? ''))
                <x-form.field :label="__('editorial.est_type_contact_number')" :error="$errors->first('state.contact_channel_list.'.$i.'.type_contact_number')">
                    <x-form.select wire:model="state.contact_channel_list.{{ $i }}.type_contact_number" :options="$taxonomy['type_contact_number']" :placeholder="__('editorial.select_placeholder')" />
                </x-form.field>
            @endif
            <x-form.field :label="__('editorial.est_status_contact_channel')" :error="$errors->first('state.contact_channel_list.'.$i.'.status_contact_channel')">
                <x-form.select wire:model="state.contact_channel_list.{{ $i }}.status_contact_channel" :options="$taxonomy['status_contact_channel']" :placeholder="__('editorial.select_placeholder')" />
            </x-form.field>
            <x-form.field :label="__('editorial.est_contact_label')" :error="$errors->first('state.contact_channel_list.'.$i.'.contact_label')">
                <x-form.input wire:model="state.contact_channel_list.{{ $i }}.contact_label" maxlength="100" />
            </x-form.field>
            <x-form.field :label="__('editorial.est_contact_value')" :error="$errors->first('state.contact_channel_list.'.$i.'.contact_value')">
                <x-form.input wire:model="state.contact_channel_list.{{ $i }}.contact_value" type="{{ $this->channelValueInputType($row['type_contact_channel'] ?? '') }}" maxlength="255" />
            </x-form.field>
            <x-form.field :label="__('editorial.est_contact_url')" :error="$errors->first('state.contact_channel_list.'.$i.'.contact_url')">
                <x-form.input wire:model="state.contact_channel_list.{{ $i }}.contact_url" maxlength="2048" />
            </x-form.field>
            <div class="flex justify-end lg:col-span-3">
                <button type="button" wire:click="removeRow('contact_channel_list', {{ $i }})" class="rounded-lg border border-ink-200 px-3 py-2 text-sm font-semibold text-ink-500 transition hover:border-ember-300 hover:text-ember-600 dark:border-ink-700 dark:text-ink-400">{{ __('editorial.remove') }}</button>
            </div>
        </div>
    @endforeach
    <button type="button" wire:click="addRow('contact_channel_list')" class="rounded-lg border border-dashed border-ink-300 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-ember-400 hover:text-ember-600 dark:border-ink-600 dark:text-ink-300">{{ __('editorial.add_row') }}</button>
</div>
