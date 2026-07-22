<div class="mx-auto max-w-5xl">
    @if ($isContribution)
        <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ __('workspace.contribution_establishment_title') }}</h1>
        <p class="mt-2 text-sm text-ink-600 dark:text-ink-300">{{ __('workspace.contribution_establishment_intro') }}</p>
        <p class="mt-2 text-sm">
            <a href="{{ route('help.index', ['sectionKey' => 'navigation.help']) }}" class="font-semibold text-ember-600 hover:text-ember-700 dark:text-ember-400">{{ __('workspace.add_spa_help_link') }} &rarr;</a>
        </p>
    @else
        <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ $establishment ? __('editorial.edit') : __('editorial.new') }} — {{ __('editorial.establishments') }}</h1>
    @endif

    @error('form')<p class="mt-4 text-sm text-red-700 dark:text-red-300" role="alert">{{ $message }}</p>@enderror

    <form wire:submit="save" class="mt-6 space-y-5 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
        @if ($isContribution && $currentStep === 1)
            @include('livewire.workspace.establishment-form._who-you-are')
        @elseif ($isContribution && $currentStep === 3)
            @include('livewire.workspace.establishment-form._review-submit')
        @else
            <div x-data="{ tab: 'identity' }">
                @php($tabIcons = [
                    'identity' => 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 8a7 7 0 0 1 14 0',
                    'classification' => 'M4 6h16M4 12h16M4 18h16',
                    'access' => 'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z',
                    'location' => 'M12 21s-7-6.2-7-11a7 7 0 1 1 14 0c0 4.8-7 11-7 11Z',
                    'contact' => 'M4 4h16v16H4z M4 7l8 6 8-6',
                    'hours' => 'M12 7v5l3 3 M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z',
                    'facilities' => 'M3 21h18M5 21V8l7-5 7 5v13',
                    'amenities' => 'M12 3l2.5 5.3 5.5.7-4 4 1 5.7-5-2.8-5 2.8 1-5.7-4-4 5.5-.7L12 3Z',
                ])
                <x-editorial.tab-bar :tabs="array_filter([
                    'identity' => __('editorial.tab_identity'),
                    'classification' => __('editorial.tab_classification'),
                    'access' => __('editorial.tab_access'),
                    'location' => __('editorial.tab_location'),
                    'contact' => __('editorial.tab_contact'),
                    'hours' => __('editorial.tab_hours'),
                    'facilities' => $this->hasPhysicalPremises() ? __('editorial.tab_facilities') : null,
                    'amenities' => __('editorial.tab_amenities'),
                ])" :icons="$tabIcons" />

                @include('livewire.workspace.establishment-form._tab-identity')
                @include('livewire.workspace.establishment-form._tab-classification')
                @include('livewire.workspace.establishment-form._tab-access')
                @include('livewire.workspace.establishment-form._tab-location')
                @include('livewire.workspace.establishment-form._tab-contact')
                @include('livewire.workspace.establishment-form._tab-hours')
                @include('livewire.workspace.establishment-form._tab-facilities')
                @include('livewire.workspace.establishment-form._tab-amenities')
            </div>

            <div class="flex items-center justify-between gap-2.5 border-t border-ink-100 pt-5 dark:border-ink-800">
                @if ($isContribution)
                    <button type="button" wire:click="prevStep" class="inline-flex items-center gap-1.5 rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5 M12 19l-7-7 7-7"/></svg>
                        {{ __('editorial.back') }}
                    </button>
                    <button type="button" wire:click="nextStep" class="inline-flex items-center gap-1.5 rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">
                        {{ __('editorial.next') }}
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14 M12 5l7 7-7 7"/></svg>
                    </button>
                @else
                    <a href="{{ route('workspace.editorial.establishment.index') }}" wire:navigate class="inline-flex items-center gap-1.5 rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5 M12 19l-7-7 7-7"/></svg>
                        {{ __('editorial.cancel') }}
                    </a>
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ __('editorial.save') }}
                    </button>
                @endif
            </div>
        @endif
    </form>
</div>
@push('scripts')
    @vite(['resources/js/establishment-map.js'])
@endpush
