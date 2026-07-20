{{-- Three-state theme switch: light → dark → system. State lives in
     localStorage ('mn-theme'); behavior is wired in resources/js/app.js. --}}
<button type="button" data-theme-toggle data-theme-state="system"
        data-label-light="{{ __('common.theme_light') }}"
        data-label-dark="{{ __('common.theme_dark') }}"
        data-label-system="{{ __('common.theme_system') }}"
        aria-label="{{ __('common.theme_system') }}"
        {{ $attributes->merge(['class' => 'inline-flex size-10 items-center justify-center rounded-full border border-ink-200 text-ink-600 transition hover:border-ember-300 hover:bg-ember-50 hover:text-ember-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember-500 dark:border-ink-700 dark:text-ink-300 dark:hover:bg-ember-950 dark:hover:text-ember-400']) }}>
    <svg data-theme-icon="light" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path stroke-linecap="round" d="M12 2v2m0 16v2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M2 12h2m16 0h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
    <svg data-theme-icon="dark" hidden viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.8A8.5 8.5 0 1 1 11.2 3a6.6 6.6 0 0 0 9.8 9.8Z"/></svg>
    <svg data-theme-icon="system" hidden viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true"><rect x="3" y="4" width="18" height="13" rx="2"/><path stroke-linecap="round" d="M8 21h8m-4-4v4"/></svg>
</button>
