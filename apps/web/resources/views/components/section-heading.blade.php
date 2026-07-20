@props(['title', 'href' => null, 'accent' => 'ember'])

<div {{ $attributes->merge(['class' => 'mb-3 flex items-end justify-between gap-4']) }}>
    <h2 class="flex items-center gap-2.5 text-lg font-bold tracking-tight text-ink-950 dark:text-ink-50">
        <span class="inline-block h-5 w-1.5 rounded-full {{ $accent === 'leaf' ? 'bg-leaf-500' : 'bg-ember-500' }}" aria-hidden="true"></span>
        {{ $title }}
    </h2>
    @if ($href)
        <a href="{{ $href }}" class="group inline-flex shrink-0 items-center gap-1 text-sm font-semibold text-ink-600 transition hover:text-ember-600 dark:text-ink-300 dark:hover:text-ember-400">
            {{ __('common.view_all') }}
            <svg viewBox="0 0 20 20" fill="currentColor" class="size-4 transition-transform group-hover:translate-x-0.5" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h9.69L10.22 6.03a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
        </a>
    @endif
</div>
