@if (!empty($quote))
    <section aria-labelledby="quote-heading" class="relative overflow-hidden rounded-2xl border p-5 shadow-sm transition-all duration-300 bg-gradient-to-br {{ $gradientClass }}">
        <div class="flex items-center justify-between gap-2">
            <h2 id="quote-heading" class="text-xs font-bold uppercase tracking-wider text-ink-600 dark:text-ink-300">
                {{ __('home.quote_of_the_day') }}
            </h2>
            @if ($category)
                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badgeClass }}">
                    <span>{{ $category->getLabel() }}</span>
                </span>
            @endif
        </div>

        <blockquote class="mt-4">
            <svg viewBox="0 0 24 24" fill="currentColor" class="size-6 {{ $accentClass }} opacity-80" aria-hidden="true">
                <path d="M9.6 5C6 6.7 4 9.5 4 13.1c0 3 1.8 5 4.3 5 2 0 3.6-1.5 3.6-3.5 0-1.9-1.4-3.3-3.2-3.3h-.6c.4-1.9 1.6-3.4 3.5-4.5L9.6 5Zm9 0c-3.6 1.7-5.6 4.5-5.6 8.1 0 3 1.8 5 4.3 5 2 0 3.6-1.5 3.6-3.5 0-1.9-1.4-3.3-3.2-3.3h-.6c.4-1.9 1.6-3.4 3.5-4.5L18.6 5Z"/>
            </svg>
            <p class="mt-2 text-base font-semibold italic leading-relaxed text-ink-950 dark:text-ink-50">
                “{{ $quote['text'] }}”
            </p>

            @if (!empty($quote['attribution_label']))
                <footer class="mt-3 text-sm font-medium text-ink-700 dark:text-ink-300">
                    — {{ $quote['attribution_label'] }}
                    @if (!empty($quote['source_title']))
                        <span class="text-xs text-ink-500 dark:text-ink-400">({{ $quote['source_title'] }})</span>
                    @endif
                </footer>
            @endif
        </blockquote>

        @if (!empty($quote['original_language_key']) && !empty($quote['is_original']) && $quote['original_language_key'] !== 'eng' && $quote['original_language_key'] !== app()->getLocale())
            <div class="mt-3 border-t border-ink-200/50 pt-2.5 text-xs text-ink-500 dark:border-ink-800/50 dark:text-ink-400">
                <span class="inline-flex items-center gap-1 font-semibold uppercase tracking-wider text-ink-600 dark:text-ink-300">
                    Original in {{ strtoupper($quote['original_language_key']) }}
                </span>
            </div>
        @elseif (!empty($quote['is_original']) === false && !empty($quote['original_text']))
            <div x-data="{ showOriginal: false }" class="mt-3 border-t border-ink-200/50 pt-2.5 text-xs text-ink-500 dark:border-ink-800/50 dark:text-ink-400">
                <button type="button" @click="showOriginal = !showOriginal" class="font-semibold text-ember-600 hover:underline dark:text-ember-400">
                    <span x-show="!showOriginal">View original ({{ strtoupper($quote['original_language_key']) }})</span>
                    <span x-show="showOriginal" x-cloak>Hide original</span>
                </button>
                <div x-show="showOriginal" x-cloak class="mt-1.5 rounded-lg bg-black/5 p-2 italic text-ink-700 dark:bg-white/5 dark:text-ink-200">
                    “{{ $quote['original_text'] }}”
                </div>
            </div>
        @endif
    </section>
@endif
