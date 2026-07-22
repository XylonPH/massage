{{--
    Shared language-tab switcher for the translatable Identity/Location fields
    (display_name, short_description, description, direction_note, parking_note).
    $activeLanguageTab lives on the EstablishmentForm component, so the selection
    persists across tabs. Callers must pass $switcherLabel (e.g. the current tab's own
    label) so the aria-label correctly identifies which section's language switch this
    is, since the partial is included on more than one tab.
--}}
<div class="flex flex-wrap gap-1.5" role="tablist" aria-label="{{ $switcherLabel }} language">
    @foreach (['eng', 'fil', 'spa', 'kor', 'zho_hant', 'zho_hans'] as $lang)
        <button type="button" wire:click="$set('activeLanguageTab', '{{ $lang }}')"
                class="rounded-full border px-3 py-1 text-xs font-semibold transition {{ $activeLanguageTab === $lang ? 'border-ember-500 bg-ember-50 text-ember-700 dark:bg-ember-950 dark:text-ember-400' : 'border-ink-200 text-ink-600 dark:border-ink-700 dark:text-ink-300' }}">
            {{ __('editorial.lang_'.$lang) }}
        </button>
    @endforeach
</div>
