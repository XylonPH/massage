<!DOCTYPE html>
<html lang="{{ config('localization.bcp47.'.app()->getLocale(), str_replace('_', '-', app()->getLocale())) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@hasSection('title')@yield('title') · {{ config('app.name') }}@else{{ ($title ?? null) ? $title.' · '.config('app.name') : config('app.name') }}@endif</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : 0 }}" sizes="any">
    @include('partials.theme-init')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
</head>
<body data-workspace-shell class="min-h-screen bg-slate-50 font-sans text-charcoal-900 antialiased dark:bg-charcoal-950 dark:text-ink-50">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-ink-950 focus:px-4 focus:py-2 focus:text-white">
        {{ __('common.skip_to_content') }}
    </a>

    {{-- Fixed Quick Home Action Button --}}
    <a href="{{ route('home') }}" 
       title="{{ __('workspace.back_to_site') }}" 
       aria-label="{{ __('workspace.back_to_site') }}"
       class="fixed right-6 sm:right-8 top-4 z-40 inline-flex items-center justify-center rounded-2xl border border-ink-200/90 bg-white/90 p-2.5 text-ink-700 shadow-md backdrop-blur-md transition duration-150 hover:scale-105 hover:border-ember-400 hover:bg-ember-50 hover:text-ember-600 dark:border-ink-700/90 dark:bg-ink-900/90 dark:text-ink-200 dark:hover:border-ember-600 dark:hover:bg-ink-800 dark:hover:text-ember-400">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5" aria-hidden="true">
            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
    </a>

    <div class="mx-auto flex min-h-screen max-w-[1600px]">
        {{-- ============ Sidebar ============ --}}
        <aside id="workspace-sidebar"
               class="fixed inset-y-0 left-0 z-40 hidden w-[17rem] shrink-0 flex-col border-r border-ink-100 bg-white lg:static lg:flex dark:border-ink-800 dark:bg-ink-900">
            <div class="flex h-[4.5rem] items-center border-b border-ink-100 px-5 dark:border-ink-800">
                <a href="{{ route('home') }}" aria-label="{{ config('app.name') }}">
                    <span class="dark:hidden"><x-logo size="h-10" /></span>
                    <span class="hidden dark:block"><x-logo dark size="h-10" /></span>
                </a>
            </div>

            <div class="border-b border-ink-100 p-4 dark:border-ink-800">
                <x-identity-capsule :user="auth()->user()" class="w-full" />
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto p-4">
                <x-workspace-nav :active="$navActive ?? 'home'" />
            </div>

            <div class="flex items-center justify-between gap-2 border-t border-ink-100 p-4 dark:border-ink-800">
                <x-theme-toggle />
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex size-10 items-center justify-center rounded-full border border-ink-200 text-ink-600 transition hover:border-ember-300 hover:bg-ember-50 hover:text-ember-700 dark:border-ink-700 dark:text-ink-300 dark:hover:bg-ember-950 dark:hover:text-ember-400" aria-label="{{ __('auth.log_out') }}" title="{{ __('auth.log_out') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2v-3M10 12h11m0 0-3-3m3 3-3 3"/></svg>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ============ Main column ============ --}}
        <div class="min-w-0 flex-1">
            {{-- Mobile Top Bar --}}
            <div class="flex h-14 items-center justify-between border-b border-ink-100 bg-white px-4 lg:hidden dark:border-ink-800 dark:bg-ink-900">
                <button type="button" data-menu-toggle aria-expanded="false" aria-controls="workspace-sidebar"
                        class="inline-flex items-center justify-center rounded-lg p-2 text-ink-800 hover:bg-ink-50 dark:text-ink-200 dark:hover:bg-ink-800">
                    <span class="sr-only">{{ __('navigation.open_menu') }}</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-6" aria-hidden="true"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
                <a href="{{ route('home') }}" aria-label="{{ config('app.name') }}">
                    <span class="dark:hidden"><x-logo size="h-8" /></span>
                    <span class="hidden dark:block"><x-logo dark size="h-8" /></span>
                </a>
            </div>

            {{-- Desktop Header Bar (Only rendered when page-title or page-actions is defined) --}}
            @if (View::hasSection('page-title') || View::hasSection('page-actions'))
                <header class="hidden lg:block border-b border-ink-100 bg-white dark:border-ink-800 dark:bg-ink-900">
                    <div class="flex h-[4.5rem] items-center gap-4 px-4 pr-16 sm:px-6 sm:pr-20 lg:px-8">
                        <div class="min-w-0 flex-1">
                            @hasSection('page-title')
                                <h1 class="truncate text-xl font-black text-ink-950 dark:text-ink-50">@yield('page-title')</h1>
                            @endif
                            @hasSection('page-context')
                                <p class="truncate text-sm text-ink-600 dark:text-ink-300">@yield('page-context')</p>
                            @endif
                        </div>
                        <div class="flex shrink-0 items-center gap-2.5">
                            @yield('page-actions')
                        </div>
                    </div>
                </header>
            @endif

            <main id="main-content" class="px-4 py-8 sm:px-6 lg:px-8">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
