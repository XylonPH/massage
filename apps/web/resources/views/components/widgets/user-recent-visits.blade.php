<section class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm">
    <div class="flex items-center justify-between">
        <h3 class="text-base font-black tracking-tight text-ink-950">{{ __('widget.recently_viewed') }}</h3>
    </div>

    <ul class="mt-5 space-y-3">
        @foreach ([
            ['name' => 'The Tranquil Lotus', 'type' => 'Spa Profile', 'time' => '2 hours ago'],
            ['name' => 'Benefits of Deep Tissue', 'type' => 'Article', 'time' => 'Yesterday'],
        ] as $visit)
            <li>
                <a href="#" class="group block rounded-xl border border-transparent p-2 transition hover:border-ink-100 hover:bg-ink-50">
                    <p class="truncate text-sm font-bold text-ink-900 group-hover:text-ember-700">{{ $visit['name'] }}</p>
                    <p class="mt-0.5 flex items-center gap-2 text-xs text-ink-500">
                        <span class="font-semibold text-ink-700">{{ $visit['type'] }}</span>
                        <span aria-hidden="true">·</span>
                        <span>{{ $visit['time'] }}</span>
                    </p>
                </a>
            </li>
        @endforeach
    </ul>
</section>
