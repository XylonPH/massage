<section class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">
    <div class="flex items-center justify-between">
        <h3 class="text-base font-black tracking-tight text-ink-950 dark:text-ink-50">{{ __('widget.trending_spas') }}</h3>
        <span class="inline-flex items-center gap-1 rounded-md bg-leaf-50 px-2 py-1 text-xs font-bold text-leaf-700 dark:bg-leaf-950 dark:text-leaf-300">
            <svg viewBox="0 0 20 20" fill="currentColor" class="size-3.5" aria-hidden="true"><path fill-rule="evenodd" d="M12.577 4.878a.75.75 0 0 1 .919-.53l4.78 1.281a.75.75 0 0 1 .531.919l-1.281 4.78a.75.75 0 0 1-1.448-.387l.81-3.022a19.407 19.407 0 0 0-5.594 5.203.75.75 0 0 1-1.139.093L7 10.06l-4.72 4.72a.75.75 0 0 1-1.06-1.061l5.25-5.25a.75.75 0 0 1 1.06 0l4.07 4.07a20.923 20.923 0 0 1 5.187-4.872l-3.213-.86a.75.75 0 0 1-.53-.919Z" clip-rule="evenodd"/></svg>
            Top 3
        </span>
    </div>

    <ul class="mt-5 space-y-4">
        @foreach ([
            ['name' => 'The Tranquil Lotus', 'rating' => '9.8', 'reviews' => '124', 'area' => 'Downtown'],
            ['name' => 'Zenith Wellness Center', 'rating' => '9.5', 'reviews' => '89', 'area' => 'Westside'],
            ['name' => 'Healing Hands Studio', 'rating' => '9.2', 'reviews' => '210', 'area' => 'North District'],
        ] as $index => $spa)
            <li>
                <a href="#" class="group flex items-start gap-3 rounded-xl p-2 transition-all duration-300 ease-out hover:bg-ink-50 hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] dark:hover:bg-ink-800">
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-ink-100 text-xs font-bold text-ink-500 transition-colors duration-300 group-hover:bg-ember-100 group-hover:text-ember-700 dark:bg-ink-800 dark:text-ink-400 dark:group-hover:bg-ember-900 dark:group-hover:text-ember-300">{{ $index + 1 }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold text-ink-900 group-hover:text-ember-700 dark:text-ink-100 dark:group-hover:text-ember-300">{{ $spa['name'] }}</p>
                        <p class="mt-0.5 flex items-center gap-1 text-xs text-ink-500 dark:text-ink-400">
                            <span class="font-bold text-ink-700 dark:text-ink-200">{{ $spa['rating'] }}</span>
                            <span aria-hidden="true">·</span>
                            <span>{{ $spa['reviews'] }} reviews</span>
                            <span aria-hidden="true">·</span>
                            <span class="truncate">{{ $spa['area'] }}</span>
                        </p>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
</section>
