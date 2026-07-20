<div id="cookie-banner" hidden
     class="fixed inset-x-0 bottom-0 z-50 border-t border-ink-200 bg-white/97 p-4 shadow-[0_-4px_20px_rgba(0,0,0,0.08)] backdrop-blur sm:p-5 dark:border-ink-700 dark:bg-ink-900/97"
     role="dialog" aria-live="polite" aria-label="{{ __('cookies.banner_label') }}">
    <div class="mx-auto max-w-5xl">
        <div data-cookie-summary class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-ink-600 dark:text-ink-300">
                {{ __('cookies.summary') }}
                <a href="{{ url('/legal/privacy') }}" class="font-semibold text-ember-600 transition hover:text-ember-700 dark:text-ember-400 dark:hover:text-ember-300">{{ __('cookies.learn_more') }}</a>
            </p>
            <div class="flex shrink-0 flex-wrap gap-2.5">
                <button type="button" data-cookie-manage class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-700 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:border-ink-600 dark:hover:bg-ink-800">
                    {{ __('cookies.manage_preferences') }}
                </button>
                <button type="button" data-cookie-reject class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-700 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:border-ink-600 dark:hover:bg-ink-800">
                    {{ __('cookies.reject_non_essential') }}
                </button>
                <button type="button" data-cookie-accept class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">
                    {{ __('cookies.accept_all') }}
                </button>
            </div>
        </div>

        <div data-cookie-preferences hidden class="mt-4 space-y-4 border-t border-ink-100 pt-4 dark:border-ink-800">
            <label class="flex items-start gap-3 opacity-70">
                <input type="checkbox" checked disabled class="mt-0.5 size-4 rounded accent-ink-400">
                <span>
                    <span class="block text-sm font-bold text-ink-900 dark:text-ink-100">{{ __('cookies.necessary_title') }}</span>
                    <span class="block text-xs text-ink-500 dark:text-ink-400">{{ __('cookies.necessary_text') }}</span>
                </span>
            </label>
            <label class="flex cursor-pointer items-start gap-3">
                <input type="checkbox" data-cookie-analytics-toggle class="mt-0.5 size-4 rounded accent-ember-500">
                <span>
                    <span class="block text-sm font-bold text-ink-900 dark:text-ink-100">{{ __('cookies.analytics_title') }}</span>
                    <span class="block text-xs text-ink-500 dark:text-ink-400">{{ __('cookies.analytics_text') }}</span>
                </span>
            </label>
            <button type="button" data-cookie-save class="rounded-lg bg-ink-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-ink-800 dark:bg-ink-100 dark:text-ink-900 dark:hover:bg-white">
                {{ __('cookies.save_preferences') }}
            </button>
        </div>
    </div>
</div>
