@props([
    'name',
    'label',
    'endpoint',
    'selected' => [],
    'multiple' => true,
    'placeholder' => null,
    'type' => 'record',
])

<div data-entity-picker
     data-endpoint="{{ $endpoint }}"
     data-field-name="{{ $name }}"
     data-multiple="{{ $multiple ? 'true' : 'false' }}"
     data-entity-type="{{ $type }}"
     data-searching-label="{{ __('article.searching_records') }}"
     data-empty-label="{{ __('article.no_search_results') }}"
     data-error-label="{{ __('article.search_records_error') }}"
     class="relative">
    <span class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">{{ $label }}</span>
    <div data-picker-selected class="mb-2 flex flex-wrap gap-2">
        @foreach ($selected as $option)
            <span data-picker-chip data-id="{{ $option['id'] }}" class="inline-flex max-w-full items-center gap-1.5 rounded-full bg-ink-100 px-3 py-1.5 text-xs font-semibold text-ink-800 dark:bg-ink-800 dark:text-ink-100">
                <span class="truncate">{{ $option['label'] }}</span>
                <button type="button" data-picker-remove aria-label="{{ __('article.remove_selection', ['label' => $option['label']]) }}" class="rounded-full text-ink-500 hover:text-ember-700 dark:text-ink-300 dark:hover:text-ember-300">&times;</button>
                <input type="hidden" name="{{ $multiple ? $name.'[]' : $name }}" value="{{ $option['id'] }}">
            </span>
        @endforeach
    </div>
    <input type="search"
           data-picker-search
           autocomplete="off"
           placeholder="{{ $placeholder ?? __('article.search_records_placeholder') }}"
           aria-label="{{ $label }}"
           class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2.5 text-sm text-ink-950 shadow-2xs focus:border-ember-500 focus:outline-none focus:ring-2 focus:ring-ember-500/20 dark:border-ink-700 dark:bg-ink-950 dark:text-white">
    <div data-picker-results hidden role="listbox" class="absolute z-30 mt-1 max-h-64 w-full overflow-y-auto rounded-xl border border-ink-200 bg-white p-1 shadow-xl dark:border-ink-700 dark:bg-ink-900"></div>
    <p data-picker-status class="mt-1 text-xs text-ink-500 dark:text-ink-400" aria-live="polite">{{ __('article.search_records_hint') }}</p>
</div>
