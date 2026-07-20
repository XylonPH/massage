<section class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm">
    <div class="flex items-center justify-between">
        <h3 class="text-base font-black tracking-tight text-ink-950">{{ __('widget.recent_reviews', 'Recent Reviews') }}</h3>
    </div>

    <div class="mt-5 space-y-4">
        @foreach ([
            ['user' => 'Alex M.', 'spa' => 'The Tranquil Lotus', 'rating' => 10, 'text' => 'Absolutely phenomenal experience. The therapist was attentive and the ambiance was perfect.'],
            ['user' => 'Anonymous', 'spa' => 'Healing Hands Studio', 'rating' => 9, 'text' => 'Great deep tissue work, though the room was slightly chilly.'],
        ] as $review)
            <article class="group rounded-xl border border-ink-50 bg-ink-50/50 p-4 transition hover:bg-white hover:shadow-md">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold text-ink-900">{{ $review['user'] }}</p>
                    <span class="inline-flex items-center rounded bg-ink-900 px-1.5 py-0.5 text-[10px] font-black tracking-wider text-white">
                        {{ $review['rating'] }}/10
                    </span>
                </div>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-wider text-ink-500">@ {{ $review['spa'] }}</p>
                <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-ink-700">"{{ $review['text'] }}"</p>
            </article>
        @endforeach
    </div>
</section>