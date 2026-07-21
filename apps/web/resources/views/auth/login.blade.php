@extends('layouts.auth')

@section('title', __('navigation.log_in'))

@section('content')
<h1 class="text-2xl font-black tracking-tight text-ink-950 dark:text-ink-50">{{ __('auth.log_in_title') }}</h1>
<p class="mt-1.5 text-sm text-ink-500 dark:text-ink-400">{{ __('auth.log_in_subtitle') }}</p>

@if (session('verify_notice'))
    <p class="mt-5 rounded-xl border border-ink-200 bg-ink-50 px-4 py-3 text-sm font-semibold text-ink-700 dark:border-ink-700 dark:bg-ink-800 dark:text-ink-200" role="status">
        {{ session('verify_notice') }}
    </p>
@endif

@if (session('password_reset_notice'))
    <p class="mt-5 rounded-xl border border-leaf-200 bg-leaf-50 px-4 py-3 text-sm font-semibold text-leaf-800 dark:border-leaf-800 dark:bg-leaf-950 dark:text-leaf-100" role="status">
        {{ session('password_reset_notice') }}
    </p>
@endif

@error('identifier')
    <p class="mt-5 rounded-xl border border-ember-200 bg-ember-50 px-4 py-3 text-sm font-semibold text-ember-800 dark:border-ember-800 dark:bg-ember-950 dark:text-ember-100" role="alert">
        {{ $message }}
    </p>
@enderror

<form method="post" action="{{ route('login.store') }}" class="mt-7 space-y-5">
    @csrf

    <div>
        <label for="identifier" class="mb-1.5 block text-sm font-bold text-ink-900 dark:text-ink-100">{{ __('auth.identifier_label') }}</label>
        <input id="identifier" name="identifier" type="text" required autofocus autocomplete="username"
               value="{{ old('identifier') }}"
               class="w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-950 placeholder-ink-400 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50 dark:placeholder:text-ink-400">
    </div>

    <div>
        <div class="mb-1.5 flex items-center justify-between">
            <label for="password" class="block text-sm font-bold text-ink-900 dark:text-ink-100">{{ __('auth.password_label') }}</label>
            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-ember-600 transition hover:text-ember-700 dark:text-ember-400 dark:hover:text-ember-300">{{ __('auth.forgot_password') }}</a>
        </div>
        <div class="relative">
            <input id="password" name="password" type="password" required autocomplete="current-password"
                   class="w-full rounded-xl border border-ink-200 bg-white px-4 py-3 pr-12 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50 dark:placeholder:text-ink-400">
            <button type="button" data-password-toggle="password" aria-pressed="false"
                    class="absolute inset-y-0 right-0 flex items-center px-3.5 text-ink-400 transition hover:text-ink-700 dark:text-ink-300 dark:hover:text-ink-200">
                <span class="sr-only">{{ __('auth.show_password') }}</span>
                <svg data-eye="closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg data-eye="open" hidden viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 5.8A9.8 9.8 0 0 1 12 5.5c6 0 9.5 6.5 9.5 6.5a17.4 17.4 0 0 1-2.7 3.5m-2.2 1.9A9 9 0 0 1 12 18.5C6 18.5 2.5 12 2.5 12a17 17 0 0 1 4-4.5M10 10.1a3 3 0 0 0 4 4.4"/></svg>
            </button>
        </div>
    </div>

    <label class="flex cursor-pointer items-center gap-2.5 text-sm text-ink-700 dark:text-ink-200">
        <input type="checkbox" name="remember" value="1" class="size-4 rounded accent-ember-500">
        {{ __('auth.remember_me') }}
    </label>

    <button type="submit" class="w-full rounded-xl bg-ember-500 px-4 py-3 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">
        {{ __('auth.log_in_button') }}
    </button>
</form>

<div class="mt-6 space-y-3 text-center text-sm">
    <p class="text-ink-500 dark:text-ink-400">
        {{ __('auth.no_account') }}
        <a href="{{ route('register') }}" class="font-bold text-ember-600 transition hover:text-ember-700 dark:text-ember-400 dark:hover:text-ember-300">{{ __('auth.sign_up_link') }}</a>
    </p>
    <p><a href="{{ route('verification.notice') }}" class="text-xs font-semibold text-ink-400 transition hover:text-ink-600 dark:text-ink-300 dark:hover:text-ink-200">{{ __('auth.resend_verification') }}</a></p>
</div>
@endsection
