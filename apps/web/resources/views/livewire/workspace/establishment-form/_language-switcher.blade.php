{{--
    Single, page-level language switcher for the translatable Identity/Location fields
    (display_name, short_description, description, direction_note, parking_note).
    $activeLanguageTab lives on the EstablishmentForm component. Rendered exactly once
    per establishment-form.blade.php, above the tab content — NOT included per-tab
    (a prior version included this partial separately in both the Identity and Location
    tabs, rendering the same control twice; fixed 2026-07-24).
--}}
<label for="establishment-language-switcher" class="sr-only">{{ __('editorial.language_switcher_label') }}</label>
<select id="establishment-language-switcher" wire:model.live="activeLanguageTab" aria-label="Language"
        class="w-full max-w-xs rounded-lg border border-ink-200 px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white">
    @foreach (['eng', 'fil', 'spa', 'kor', 'zho_hant', 'zho_hans'] as $lang)
        <option value="{{ $lang }}" @selected($activeLanguageTab === $lang)>{{ __('editorial.lang_'.$lang) }}</option>
    @endforeach
</select>
