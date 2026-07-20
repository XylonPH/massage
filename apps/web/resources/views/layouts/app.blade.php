<!DOCTYPE html>
<html lang="{{ config('localization.bcp47.'.app()->getLocale(), str_replace('_', '-', app()->getLocale())) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@hasSection('title')@yield('title') · {{ config('app.name') }}@else{{ config('app.name') }}@endif</title>
    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
    @endif
    {{-- Cache-busted by file mtime so browsers pick up a replaced favicon.ico without a manual hard refresh. --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : 0 }}" sizes="any">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-charcoal-900 antialiased">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-ink-950 focus:px-4 focus:py-2 focus:text-white">
        {{ __('common.skip_to_content') }}
    </a>

    {{-- ===================== Header ===================== --}}
    <header class="sticky top-0 z-40 border-b border-ink-100 bg-white/95 shadow-sm backdrop-blur">
        <div class="mx-auto flex h-16 max-w-[1600px] items-center gap-6 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="shrink-0" aria-label="{{ config('app.name') }}">
                <x-logo size="h-9" />
            </a>

            <nav class="hidden flex-1 items-center gap-1 lg:flex" aria-label="{{ __('navigation.main_navigation') }}">
                @foreach ([
                    ['href' => route('home'), 'label' => __('navigation.home'), 'active' => request()->routeIs('home')],
                    ['href' => route('directory.index'), 'label' => __('navigation.directory'), 'active' => request()->is('directory*') || request()->is('spa*')],
                    ['href' => route('article.index'), 'label' => __('navigation.articles'), 'active' => request()->is('article*')],
                    ['href' => route('campus.index'), 'label' => __('navigation.campus'), 'active' => request()->is('campus*')],
                    ['href' => route('promo.index'), 'label' => __('navigation.promos'), 'active' => request()->is('promo*')],
                ] as $item)
                    <a href="{{ $item['href'] }}"
                       @if ($item['active']) aria-current="page" @endif
                       class="rounded-lg px-3 py-2 text-sm font-semibold transition {{ $item['active'] ? 'text-ember-600' : 'text-ink-800 hover:bg-ink-50 hover:text-ink-950' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="ml-auto hidden items-center gap-2.5 lg:flex">
                @auth
                    @if (auth()->user()->isActive() && auth()->user()->hasVerifiedEmail())
                        <a href="{{ route('workspace.home') }}" class="rounded-lg border border-ink-200 px-3 py-2 text-sm font-semibold text-ink-800 transition hover:border-ember-300 hover:bg-ember-50 hover:text-ember-700">
                            {{ __('navigation.workspace') }}
                        </a>
                        <a href="{{ route('workspace.article.index') }}" class="rounded-lg border border-ink-200 px-3 py-2 text-sm font-semibold text-ink-800 transition hover:border-ember-300 hover:bg-ember-50 hover:text-ember-700">
                            {{ __('article.workspace_title') }}
                        </a>
                        <a href="{{ route('workspace.review.index') }}" class="rounded-lg border border-ink-200 px-3 py-2 text-sm font-semibold text-ink-800 transition hover:border-ember-300 hover:bg-ember-50 hover:text-ember-700">
                            {{ __('review.workspace_title') }}
                        </a>
                    @endif
                    <span class="inline-flex items-center gap-2 rounded-lg bg-ink-50 px-3 py-2 text-sm font-semibold text-ink-800">
                        <span class="flex size-6 items-center justify-center rounded-full bg-ember-100 text-xs font-bold text-ember-700" aria-hidden="true">
                            {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                        </span>
                        {{ __('auth.logged_in_as', ['username' => auth()->user()->username]) }}
                    </span>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50">
                            {{ __('auth.log_out') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50">
                        {{ __('navigation.log_in') }}
                    </a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">
                        {{ __('navigation.sign_up') }}
                    </a>
                @endauth
            </div>

            <button type="button" data-menu-toggle aria-expanded="false" aria-controls="mobile-menu"
                    class="ml-auto inline-flex items-center justify-center rounded-lg p-2 text-ink-800 hover:bg-ink-50 lg:hidden">
                <span class="sr-only">{{ __('navigation.open_menu') }}</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-6" aria-hidden="true"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </div>

        <div id="mobile-menu" hidden class="border-t border-ink-100 bg-white lg:hidden">
            <nav class="space-y-1 px-4 py-3" aria-label="{{ __('navigation.main_navigation') }}">
                <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-ink-900 hover:bg-ink-50">{{ __('navigation.home') }}</a>
                <a href="{{ route('directory.index') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-ink-900 hover:bg-ink-50">{{ __('navigation.directory') }}</a>
                <a href="{{ route('article.index') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-ink-900 hover:bg-ink-50">{{ __('navigation.articles') }}</a>
                <a href="{{ route('campus.index') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-ink-900 hover:bg-ink-50">{{ __('navigation.campus') }}</a>
                <a href="{{ route('promo.index') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-ink-900 hover:bg-ink-50">{{ __('navigation.promos') }}</a>
                @auth
                    @if (auth()->user()->isActive() && auth()->user()->hasVerifiedEmail())
                        <a href="{{ route('workspace.home') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-ink-900 hover:bg-ink-50">{{ __('navigation.workspace') }}</a>
                        <a href="{{ route('workspace.article.index') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-ink-900 hover:bg-ink-50">{{ __('article.workspace_title') }}</a>
                        <a href="{{ route('workspace.review.index') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-ink-900 hover:bg-ink-50">{{ __('review.workspace_title') }}</a>
                    @endif
                    <div class="flex items-center justify-between gap-2.5 pt-2">
                        <span class="text-sm font-semibold text-ink-800">{{ __('auth.logged_in_as', ['username' => auth()->user()->username]) }}</span>
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800">{{ __('auth.log_out') }}</button>
                        </form>
                    </div>
                @else
                    <div class="flex gap-2.5 pt-2">
                        <a href="{{ route('login') }}" class="flex-1 rounded-lg border border-ink-200 px-4 py-2 text-center text-sm font-semibold text-ink-800">{{ __('navigation.log_in') }}</a>
                        <a href="{{ route('register') }}" class="flex-1 rounded-lg bg-ember-500 px-4 py-2 text-center text-sm font-semibold text-white">{{ __('navigation.sign_up') }}</a>
                    </div>
                @endauth
            </nav>
        </div>
    </header>

    <main id="main-content">
        @yield('content')
    </main>

    {{-- ===================== Footer ===================== --}}
    <footer class="mt-16 bg-ink-950 text-ink-200">
        <div class="mx-auto max-w-[1600px] px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-5">
                <div>
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-wider text-white">{{ __('footer.compass_guide') }}</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ url('/guide/how-it-works') }}" class="transition hover:text-ember-400">{{ __('footer.how_it_works') }}</a></li>
                        <li><a href="{{ url('/guide/search-tips') }}" class="transition hover:text-ember-400">{{ __('footer.search_tips') }}</a></li>
                        <li><a href="{{ url('/guide/glossary') }}" class="transition hover:text-ember-400">{{ __('footer.wellness_glossary') }}</a></li>
                        <li><a href="{{ url('/guide/faq') }}" class="transition hover:text-ember-400">{{ __('footer.faq') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-wider text-white">{{ __('footer.for_spa_owners') }}</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ url('/business/list-your-spa') }}" class="transition hover:text-ember-400">{{ __('footer.list_your_spa') }}</a></li>
                        <li><a href="{{ url('/business/claim') }}" class="transition hover:text-ember-400">{{ __('footer.claim_your_profile') }}</a></li>
                        <li><a href="{{ url('/business/promote') }}" class="transition hover:text-ember-400">{{ __('footer.promote_your_business') }}</a></li>
                        <li><a href="{{ url('/business/api') }}" class="transition hover:text-ember-400">{{ __('footer.platform_api') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-wider text-white">{{ __('footer.for_therapists') }}</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ url('/therapist/join') }}" class="transition hover:text-ember-400">{{ __('footer.join_as_therapist') }}</a></li>
                        <li><a href="{{ url('/therapist/claim') }}" class="transition hover:text-ember-400">{{ __('footer.claim_your_profile') }}</a></li>
                        <li><a href="{{ url('/therapist/guidelines') }}" class="transition hover:text-ember-400">{{ __('footer.therapist_guidelines') }}</a></li>
                        <li><a href="{{ route('campus.index') }}" class="transition hover:text-ember-400">{{ __('footer.wellness_campus') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-wider text-white">{{ __('footer.connect_with_us') }}</h3>
                    <ul class="flex gap-3" aria-label="{{ __('footer.connect_with_us') }}">
                        <li><a href="https://www.facebook.com/MassageNexusOfficial/" target="_blank" rel="noopener" class="inline-flex size-9 items-center justify-center rounded-full bg-ink-800 transition hover:bg-ember-500 hover:text-white" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="currentColor" class="size-4" aria-hidden="true"><path d="M13.5 21v-7h2.4l.4-3h-2.8V9.1c0-.9.2-1.5 1.5-1.5h1.4V4.9c-.3 0-1.2-.1-2.3-.1-2.3 0-3.9 1.4-3.9 4V11H7.7v3h2.5v7h3.3Z"/></svg></a></li>
                        <li><a href="https://x.com/MassageNexus" target="_blank" rel="noopener" class="inline-flex size-9 items-center justify-center rounded-full bg-ink-800 transition hover:bg-ember-500 hover:text-white" aria-label="X (formerly Twitter)"><svg viewBox="0 0 24 24" fill="currentColor" class="size-4" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a></li>
                        <li><a href="https://instagram.com" class="inline-flex size-9 items-center justify-center rounded-full bg-ink-800 transition hover:bg-ember-500 hover:text-white" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="currentColor" class="size-4" aria-hidden="true"><path d="M12 8.7a3.3 3.3 0 1 0 0 6.6 3.3 3.3 0 0 0 0-6.6Zm0-2.2a5.5 5.5 0 1 1 0 11 5.5 5.5 0 0 1 0-11Zm7-.3a1.3 1.3 0 1 1-2.6 0 1.3 1.3 0 0 1 2.6 0ZM12 4.2c-2.7 0-3 0-4.1.1-.8 0-1.5.2-2 .4-.6.2-1 .5-1.4 1-.4.4-.7.8-.9 1.3-.2.5-.4 1.2-.4 2C3.1 10 3 10.3 3 13s0 3 .1 4.1c0 .8.2 1.5.4 2 .2.5.5 1 1 1.4.4.4.8.7 1.3.9.5.2 1.2.4 2 .4 1.1.1 1.4.1 4.1.1s3 0 4.1-.1c.8 0 1.5-.2 2-.4a3.8 3.8 0 0 0 2.3-2.3c.2-.5.4-1.2.4-2 .1-1.1.1-1.4.1-4.1s0-3-.1-4.1c0-.8-.2-1.5-.4-2a3.8 3.8 0 0 0-2.3-2.3c-.5-.2-1.2-.4-2-.4-1.1-.1-1.4-.1-4.1-.1Z" transform="translate(0 -1)"/></svg></a></li>
                        <li><a href="https://youtube.com" class="inline-flex size-9 items-center justify-center rounded-full bg-ink-800 transition hover:bg-ember-500 hover:text-white" aria-label="YouTube"><svg viewBox="0 0 24 24" fill="currentColor" class="size-4" aria-hidden="true"><path d="M21.6 7.2a2.5 2.5 0 0 0-1.8-1.8C18.3 5 12 5 12 5s-6.3 0-7.8.4A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12c0 1.6.1 3.2.4 4.8a2.5 2.5 0 0 0 1.8 1.8c1.5.4 7.8.4 7.8.4s6.3 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8c.3-1.6.4-3.2.4-4.8s-.1-3.2-.4-4.8ZM10 15.2V8.8l5.2 3.2L10 15.2Z"/></svg></a></li>
                    </ul>
                    <div class="mt-6">
                        <label for="footer-language" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-ink-300">{{ __('footer.language') }}</label>
                        {{-- Planned rollout per docs/13-localization/language-strategy.txt section 4; only English is active in this build. --}}
                        <select id="footer-language" class="w-full max-w-44 rounded-lg border border-ink-700 bg-ink-900 px-3 py-2 text-sm text-white">
                            <option value="eng" selected>English</option>
                            <option value="fil">Filipino</option>
                            <option value="ind">Bahasa Indonesia</option>
                            <option value="spa">Español</option>
                            <option value="por">Português (Brasil)</option>
                            <option value="jpn">日本語</option>
                            <option value="tha">ไทย</option>
                            <option value="vie">Tiếng Việt</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-col items-start gap-3 lg:items-end">
                    <x-logo dark size="h-11" />
                    <p class="text-sm text-ink-300 lg:text-right">{{ __('footer.tagline') }}</p>
                    <p class="text-sm font-semibold text-ember-400">{{ __('footer.motto') }}</p>
                </div>
            </div>

            <div class="mt-10 flex flex-col items-center justify-between gap-4 border-t border-ink-800 pt-6 sm:flex-row">
                <p class="text-xs text-ink-400">{{ __('footer.copyright', ['year' => date('Y')]) }}</p>
                <ul class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs">
                    <li><a href="{{ route('legal.terms') }}" class="transition hover:text-ember-400">{{ __('footer.terms_of_use') }}</a></li>
                    <li><a href="{{ route('legal.privacy') }}" class="transition hover:text-ember-400">{{ __('footer.privacy_policy') }}</a></li>
                    <li><a href="{{ route('legal.cookies') }}" class="transition hover:text-ember-400">{{ __('footer.cookie_policy') }}</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <x-cookie-banner />
</body>
</html>
