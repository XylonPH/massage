{{-- Identity --}}
<div x-show="tab === 'identity'" class="mt-5 space-y-5">
    @include('livewire.workspace.establishment-form._language-switcher', ['switcherLabel' => __('editorial.tab_identity')])

    <x-form.field :label="__('editorial.est_display_name_eng')" :error="$errors->first('state.display_name_'.$activeLanguageTab)">
        <x-form.input wire:model="state.display_name_{{ $activeLanguageTab }}" maxlength="255" />
    </x-form.field>
    <x-form.field :label="__('editorial.est_short_description_eng')" :error="$errors->first('state.short_description_'.$activeLanguageTab)">
        <x-form.textarea wire:model="state.short_description_{{ $activeLanguageTab }}" rows="3" />
    </x-form.field>
    <x-form.field :label="__('editorial.est_description_eng')" :error="$errors->first('state.description_'.$activeLanguageTab)">
        <x-form.textarea wire:model="state.description_{{ $activeLanguageTab }}" rows="8" />
    </x-form.field>
    @unless ($isContribution)
        <div class="grid gap-5 sm:grid-cols-2">
            <x-form.field :label="__('editorial.est_email')" :error="$errors->first('state.email')">
                <x-form.input wire:model="state.email" type="email" maxlength="255" />
            </x-form.field>
            <x-form.field :label="__('editorial.est_contact_number')" :error="$errors->first('state.contact_number')">
                <x-form.input wire:model="state.contact_number" maxlength="255" />
            </x-form.field>
        </div>
        <x-form.field :label="__('editorial.est_status_record_lifecycle')" :error="$errors->first('state.status_record_lifecycle')">
            <x-form.select wire:model="state.status_record_lifecycle" :options="$lifecycleOptions" />
        </x-form.field>
    @endunless
</div>
