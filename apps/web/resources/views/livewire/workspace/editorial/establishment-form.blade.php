@vite(['resources/js/establishment-map.js'])
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
        @else
            <div x-data="{ tab: 'identity' }">
                <x-editorial.tab-bar :tabs="[
                    'identity' => __('editorial.tab_identity'),
                    'classification' => __('editorial.tab_classification'),
                    'access' => __('editorial.tab_access'),
                    'location' => __('editorial.tab_location'),
                    'contact' => __('editorial.tab_contact'),
                    'hours' => __('editorial.tab_hours'),
                    'facilities' => __('editorial.tab_facilities'),
                    'amenities' => __('editorial.tab_amenities'),
                ]" />

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
                    <button type="button" wire:click="prevStep" class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('editorial.back') }}</button>
                    <button type="button" wire:click="nextStep" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('editorial.next') }}</button>
                @else
                    <a href="{{ route('workspace.editorial.establishment.index') }}" wire:navigate class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('editorial.cancel') }}</a>
                    <button type="submit" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('editorial.save') }}</button>
                @endif
            </div>
        @endif

        @if ($isContribution && $currentStep === 3)
            @include('livewire.workspace.establishment-form._review-submit')
        @endif
    </form>
</div>
