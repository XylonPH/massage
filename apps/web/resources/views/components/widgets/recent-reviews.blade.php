<section class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">
    <div class="flex items-center justify-between">
        <h3 class="text-base font-black tracking-tight text-ink-950 dark:text-ink-50">{{ __('widget.recent_reviews') }}</h3>
    </div>

    <div class="mt-5 space-y-4">
        @foreach ([
            ['user' => 'Alex M.', 'spa' => 'The Tranquil Lotus', 'rating' => 10, 'text' => 'Absolutely phenomenal experience. The therapist was attentive and the ambiance was perfect.'],
            ['user' => 'Anonymous', 'spa' => 'Healing Hands Studio', 'rating' => 9, 'text' => 'Great deep tissue work, though the room was slightly chilly.'],
        ] as $review)
            <article class="group cursor-pointer rounded-xl border border-ink-50 bg-ink-50/50 p-4 transition-all duration-300 ease-out hover:-translate-y-1 hover:border-ember-200 hover:bg-white hover:shadow-lg active:translate-y-0 active:scale-[0.98] dark:border-ink-800 dark:bg-charcoal-950 dark:hover:border-ember-800 dark:hover:bg-ink-900">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold text-ink-900 dark:text-ink-100">{{ $review['user'] }}</p>
                    <span class="inline-flex items-center rounded bg-ink-900 px-1.5 py-0.5 text-[10px] font-black tracking-wider text-white dark:bg-ink-700">
                        {{ $review['rating'] }}/10
                    </span>
                </div>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">@ {{ $review['spa'] }}</p>
                <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-ink-700 dark:text-ink-200">"{{ $review['text'] }}"</p>
            </article>
        @endforeach
    </div>
</section>
