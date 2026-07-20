<section class="overflow-hidden rounded-2xl border border-ember-100 bg-gradient-to-br from-white to-ember-50/50 shadow-sm transition hover:shadow-md">
    <div class="relative p-5">
        <div class="absolute -right-4 -top-4 size-24 rounded-full bg-ember-100/50 blur-2xl"></div>
        
        <div class="relative flex items-center justify-between">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-ember-100/80 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-ember-700">
                <svg viewBox="0 0 20 20" fill="currentColor" class="size-3.5" aria-hidden="true"><path d="M10 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16Zm0 14a6 6 0 1 1 0-12 6 6 0 0 1 0 12Zm0-9.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Zm-.75 4.5a.75.75 0 0 1 1.5 0v2.5a.75.75 0 0 1-1.5 0V11Z"/></svg>
                {{ __('widget.quiz_label') }}
            </span>
            <span class="text-xs font-semibold text-ink-400">1 min</span>
        </div>

        <h3 class="relative mt-4 text-base font-black leading-tight tracking-tight text-ink-950">
            What's your ideal massage pressure level?
        </h3>

        <div class="relative mt-5 space-y-2">
            @foreach(['Feather light', 'Firm but relaxing', 'Deep tissue intensity', 'Depends on the day'] as $option)
                <button type="button" class="group flex w-full items-center justify-between rounded-xl border border-ink-100 bg-white px-4 py-2.5 text-left text-sm font-semibold text-ink-700 shadow-sm transition hover:border-ember-300 hover:bg-ember-50 hover:text-ember-900 focus:outline-none focus:ring-2 focus:ring-ember-200">
                    {{ $option }}
                    <span class="flex size-5 items-center justify-center rounded-full border border-ink-200 bg-ink-50 transition group-hover:border-ember-400 group-hover:bg-white">
                        <span class="size-2 rounded-full transition group-hover:bg-ember-500"></span>
                    </span>
                </button>
            @endforeach
        </div>
    </div>
</section>
