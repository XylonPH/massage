<section class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm">
    <div class="flex items-center justify-between">
        <h3 class="text-base font-black tracking-tight text-ink-950">{{ __('widget.trending_therapists') }}</h3>
    </div>

    <ul class="mt-5 grid grid-cols-2 gap-4">
        @foreach ([
            ['name' => 'Sarah J.', 'specialty' => 'Deep Tissue', 'avatar' => 'SJ'],
            ['name' => 'Michael T.', 'specialty' => 'Sports', 'avatar' => 'MT'],
            ['name' => 'Elena R.', 'specialty' => 'Swedish', 'avatar' => 'ER'],
            ['name' => 'David K.', 'specialty' => 'Shiatsu', 'avatar' => 'DK'],
        ] as $therapist)
            <li>
                <a href="#" class="group flex flex-col items-center gap-2 text-center transition">
                    <span class="flex size-14 items-center justify-center rounded-full bg-gradient-to-br from-leaf-100 to-leaf-200 text-base font-black text-leaf-800 shadow-sm ring-2 ring-white transition group-hover:from-ember-100 group-hover:to-ember-200 group-hover:text-ember-800">{{ $therapist['avatar'] }}</span>
                    <span class="min-w-0">
                        <span class="block truncate text-sm font-bold text-ink-900 group-hover:text-ember-700">{{ $therapist['name'] }}</span>
                        <span class="block truncate text-xs font-semibold text-ink-500">{{ $therapist['specialty'] }}</span>
                    </span>
                </a>
            </li>
        @endforeach
    </ul>
</section>
