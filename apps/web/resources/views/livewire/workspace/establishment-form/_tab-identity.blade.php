{{-- Identity --}}
<div x-show="tab === 'identity'" wire:key="tab-content-identity" class="mt-5 space-y-5">
    @include('livewire.workspace.establishment-form._language-switcher', ['switcherLabel' => __('editorial.tab_identity')])

    {{--
        Six statically-bound elements per field (one per language), toggled via
        x-show, rather than a single element whose wire:model target string is
        re-interpolated per active language. A single reused element with a
        server-re-rendered wire:model attribute string is unsafe: morphdom patches the
        attribute in place on the same DOM node instead of replacing it, and the
        previously-bound Livewire input listener can keep firing alongside the new one,
        so typing after switching languages can silently write into the PREVIOUS
        language's state key too. This mirrors the already-proven-safe pattern used for
        the 8 spa-detail tabs themselves (x-show="tab === '...'" with fixed wire:model
        targets that never change per element).
    --}}
    @foreach (['eng', 'fil', 'spa', 'kor', 'zho_hant', 'zho_hans'] as $lang)
        <div x-show="$wire.activeLanguageTab === '{{ $lang }}'" wire:key="display-name-field-{{ $lang }}">
            <x-form.field :label="__('editorial.est_display_name_eng')" :error="$errors->first('state.display_name_'.$lang)">
                <x-form.input wire:model="state.display_name_{{ $lang }}" maxlength="255" />
            </x-form.field>
        </div>
    @endforeach
    @foreach (['eng', 'fil', 'spa', 'kor', 'zho_hant', 'zho_hans'] as $lang)
        <div x-show="$wire.activeLanguageTab === '{{ $lang }}'" wire:key="short-description-field-{{ $lang }}">
            <x-form.field :label="__('editorial.est_short_description_eng')" :error="$errors->first('state.short_description_'.$lang)">
                <x-form.textarea wire:model="state.short_description_{{ $lang }}" rows="3" />
            </x-form.field>
        </div>
    @endforeach
    @foreach (['eng', 'fil', 'spa', 'kor', 'zho_hant', 'zho_hans'] as $lang)
        <div x-show="$wire.activeLanguageTab === '{{ $lang }}'" wire:key="description-field-{{ $lang }}">
            <x-form.field :label="__('editorial.est_description_eng')" :error="$errors->first('state.description_'.$lang)">
                <x-form.textarea wire:model="state.description_{{ $lang }}" rows="8" />
            </x-form.field>
        </div>
    @endforeach
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
