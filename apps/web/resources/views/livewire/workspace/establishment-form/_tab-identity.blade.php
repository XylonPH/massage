{{-- Identity --}}
<div x-show="tab === 'identity'" wire:key="tab-content-identity" class="mt-5 space-y-5">
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
    <x-form.field :label="__('editorial.est_official_name')" :help="__('editorial.est_official_name_hint')" :error="$errors->first('state.official_name')">
        <x-form.input wire:model="state.official_name" maxlength="255" />
    </x-form.field>

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
    <div class="grid gap-5 sm:grid-cols-2">
        <x-form.field :label="__('editorial.est_status_establishment')" :error="$errors->first('state.status_establishment')">
            <x-form.select wire:model.live="state.status_establishment" :options="$taxonomy['status_establishment']" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
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
    </div>

    @if (in_array($state['status_establishment'], ['TC', 'PC', 'RL'], true))
        <div class="grid gap-5 sm:grid-cols-2">
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
        </div>
    @endif

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
