<!DOCTYPE html>
<html lang="{{ config('localization.bcp47.'.app()->getLocale(), str_replace('_', '-', app()->getLocale())) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : 0 }}" sizes="any">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-charcoal-900 antialiased">
    <div class="flex min-h-screen">
        {{-- Branded panel --}}
        <aside class="relative hidden w-[44%] overflow-hidden bg-ink-950 lg:flex lg:flex-col lg:justify-between" aria-hidden="true">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -right-24 top-1/4 size-96 rounded-full bg-ember-500/15 blur-3xl"></div>
                <div class="absolute -left-24 bottom-1/4 size-80 rounded-full bg-leaf-500/15 blur-3xl"></div>
                <svg class="absolute -bottom-10 -right-10 h-80 w-80 text-white/[0.04]" viewBox="0 0 24 24" fill="currentColor"><path d="M6 15C6 8 11 4 19 4c0 8-4 13-11 13-1 0-2-.5-2-2Z"/></svg>
            </div>
            <div class="relative p-10">
                <a href="{{ route('home') }}"><x-logo dark size="h-11" /></a>
            </div>
            <div class="relative p-10">
                <h2 class="max-w-md text-3xl font-black leading-tight tracking-tight text-white">{!! __('auth.aside_title') !!}</h2>
                <ul class="mt-8 space-y-5">
                    @foreach ([
                        ['text' => __('auth.aside_point_discover'), 'icon' => 'm9 12 2 2 4-4m5.6 2a9.6 9.6 0 1 1-19.2 0 9.6 9.6 0 0 1 19.2 0Z'],
                        ['text' => __('auth.aside_point_book'), 'icon' => 'M8 7V3m8 4V3M5 11h14M5 5h14a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z'],
                        ['text' => __('auth.aside_point_learn'), 'icon' => 'M12 3 2 8l10 5 10-5-10-5Zm-6 7.5V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-5.5'],
                        ['text' => __('auth.aside_point_rewards'), 'icon' => 'M12 15a6 6 0 1 0 0-12 6 6 0 0 0 0 12Zm0 0v6m0-6-3.5 5M12 15l3.5 5'],
                    ] as $point)
                        <li class="flex items-start gap-3.5">
                            <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-white/10">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="size-4.5 text-ember-400"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $point['icon'] }}"/></svg>
                            </span>
                            <p class="pt-1.5 text-sm text-ink-200">{{ $point['text'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="relative p-10">
                <p class="text-sm font-semibold text-ember-400">{{ __('footer.motto') }}</p>
                <p class="mt-1 text-xs text-ink-400">{{ __('footer.copyright', ['year' => date('Y')]) }}</p>
            </div>
        </aside>

        {{-- Form panel --}}
        <main class="flex flex-1 flex-col overflow-y-auto">
            <div class="flex items-center justify-between p-6 lg:justify-end">
                <a href="{{ route('home') }}" class="lg:hidden"><x-logo size="h-9" /></a>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-ink-500 transition hover:text-ink-800">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H6.56l3.22 3.22a.75.75 0 1 1-1.06 1.06l-4.5-4.5a.75.75 0 0 1 0-1.06l4.5-4.5a.75.75 0 0 1 1.06 1.06L6.56 9.25h9.69A.75.75 0 0 1 17 10Z" clip-rule="evenodd"/></svg>
                    {{ __('auth.back_to_home') }}
                </a>
            </div>
            <div class="flex flex-1 items-center justify-center px-6 pb-12">
                <div class="w-full max-w-md">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <x-cookie-banner />
</body>
</html>
