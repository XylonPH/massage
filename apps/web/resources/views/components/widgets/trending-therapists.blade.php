<section class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">
    <div class="flex items-center justify-between">
        <h3 class="text-base font-black tracking-tight text-ink-950 dark:text-ink-50">{{ __('widget.trending_therapists') }}</h3>
    </div>

    <ul class="mt-5 grid grid-cols-2 gap-4">
        @foreach ([
            ['name' => 'Sarah J.', 'specialty' => 'Deep Tissue', 'avatar' => 'SJ'],
            ['name' => 'Michael T.', 'specialty' => 'Sports', 'avatar' => 'MT'],
            ['name' => 'Elena R.', 'specialty' => 'Swedish', 'avatar' => 'ER'],
            ['name' => 'David K.', 'specialty' => 'Shiatsu', 'avatar' => 'DK'],
        ] as $therapist)
            <li>
                <a href="#" class="group flex flex-col items-center gap-2 text-center transition-all duration-300 ease-out hover:-translate-y-1 active:translate-y-0 active:scale-95">
                    <span class="flex size-14 items-center justify-center rounded-full bg-gradient-to-br from-leaf-100 to-leaf-200 text-base font-black text-leaf-800 shadow-sm ring-2 ring-white transition-all duration-300 group-hover:shadow-md group-hover:from-ember-100 group-hover:to-ember-200 group-hover:text-ember-800 dark:from-leaf-900 dark:to-leaf-800 dark:text-leaf-200 dark:ring-ink-900 dark:group-hover:from-ember-900 dark:group-hover:to-ember-800 dark:group-hover:text-ember-200">{{ $therapist['avatar'] }}</span>
                    <span class="min-w-0 transition-colors duration-300">
                        <span class="block truncate text-sm font-bold text-ink-900 group-hover:text-ember-700 dark:text-ink-100 dark:group-hover:text-ember-400">{{ $therapist['name'] }}</span>
                        <span class="block truncate text-xs font-semibold text-ink-500 dark:text-ink-400">{{ $therapist['specialty'] }}</span>
                    </span>
                </a>
            </li>
        @endforeach
    </ul>
</section>
