<section class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm">
    <div class="flex items-center justify-between">
        <h3 class="text-base font-black tracking-tight text-ink-950">{{ __('widget.recent_discussions', 'Community Buzz') }}</h3>
    </div>

    <ul class="mt-5 divide-y divide-ink-50">
        @foreach ([
            ['title' => 'Best post-massage hydration tips?', 'replies' => 12, 'time' => '1h ago'],
            ['title' => 'My first Thai massage experience', 'replies' => 34, 'time' => '3h ago'],
            ['title' => 'Should I tip if gratuity is included?', 'replies' => 89, 'time' => '5h ago'],
        ] as $discussion)
            <li class="py-3 first:pt-0 last:pb-0">
                <a href="#" class="group block transition">
                    <h4 class="text-sm font-bold leading-snug text-ink-900 group-hover:text-ember-700">{{ $discussion['title'] }}</h4>
                    <p class="mt-1 flex items-center gap-2 text-xs text-ink-500">
                        <span class="flex items-center gap-1 font-semibold text-ink-700">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="size-3.5" aria-hidden="true"><path fill-rule="evenodd" d="M10 2c-5.523 0-10 4.477-10 10s4.477 10 10 10 10-4.477 10-10S15.523 2 10 2ZM8.5 7.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm-2 5a3.5 3.5 0 1 1 7 0h-7Z" clip-rule="evenodd"/></svg>
                            {{ $discussion['replies'] }} replies
                        </span>
                        <span aria-hidden="true">·</span>
                        <span>{{ $discussion['time'] }}</span>
                    </p>
                </a>
            </li>
        @endforeach
    </ul>
</section>