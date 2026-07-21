<div class="mx-auto max-w-5xl">
    <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ __('editorial.title') }}</h1>
    <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">{{ __('editorial.intro') }}</p>

    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        @foreach ([
            ['count' => $establishmentCount, 'label' => __('editorial.establishments'), 'route' => route('workspace.editorial.establishment.index')],
            ['count' => $serviceCount, 'label' => __('editorial.services'), 'route' => route('workspace.editorial.service.index')],
            ['count' => $quoteCount, 'label' => __('editorial.quotes'), 'route' => route('workspace.editorial.quote.index')],
        ] as $card)
            <a href="{{ $card['route'] }}" wire:navigate class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm transition hover:border-ember-200 hover:shadow-md dark:border-ink-800 dark:bg-ink-900 dark:hover:border-ember-800">
                <p class="text-3xl font-black text-ink-950 dark:text-ink-50">{{ $card['count'] }}</p>
                <p class="mt-1 text-sm font-semibold text-ink-600 dark:text-ink-300">{{ $card['label'] }}</p>
            </a>
        @endforeach
    </div>
</div>
