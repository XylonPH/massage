@extends('layouts.auth')

@section('title', __('auth.password_reset_title'))

@section('content')
<h1 class="text-2xl font-black tracking-tight text-ink-950">{{ __('auth.password_reset_title') }}</h1>
<p class="mt-1.5 text-sm text-ink-500">{{ __('auth.password_reset_subtitle') }}</p>

<form method="post" action="{{ route('password.update') }}" class="mt-7 space-y-5">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label for="email" class="mb-1.5 block text-sm font-bold text-ink-900">{{ __('auth.email_label') }}</label>
        <input id="email" name="email" type="email" required autocomplete="email"
               value="{{ old('email', $email) }}"
               class="w-full rounded-xl border {{ $errors->has('email') ? 'border-ember-400' : 'border-ink-200' }} bg-white px-4 py-3 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100">
        @error('email')
            <p class="mt-1.5 text-xs font-semibold text-ember-600" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="mb-1.5 block text-sm font-bold text-ink-900">{{ __('auth.password_label') }}</label>
        <div class="relative">
            <input id="password" name="password" type="password" required autofocus autocomplete="new-password"
                   minlength="15" maxlength="128" data-strength-input aria-describedby="password-hint"
                   class="w-full rounded-xl border {{ $errors->has('password') ? 'border-ember-400' : 'border-ink-200' }} bg-white px-4 py-3 pr-12 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100">
            <button type="button" data-password-toggle="password" aria-pressed="false"
                    class="absolute inset-y-0 right-0 flex items-center px-3.5 text-ink-400 transition hover:text-ink-700">
                <span class="sr-only">{{ __('auth.show_password') }}</span>
                <svg data-eye="closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg data-eye="open" hidden viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 5.8A9.8 9.8 0 0 1 12 5.5c6 0 9.5 6.5 9.5 6.5a17.4 17.4 0 0 1-2.7 3.5m-2.2 1.9A9 9 0 0 1 12 18.5C6 18.5 2.5 12 2.5 12a17 17 0 0 1 4-4.5M10 10.1a3 3 0 0 0 4 4.4"/></svg>
            </button>
        </div>
        <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-ink-100">
            <div data-strength-meter class="h-1.5 rounded-full" style="width: 0" aria-hidden="true"></div>
        </div>
        <p data-strength-text aria-live="polite" class="mt-1 text-xs font-semibold text-ink-500"
           data-too-short="{{ __('auth.strength_too_short') }}"
           data-fair="{{ __('auth.strength_fair') }}"
           data-good="{{ __('auth.strength_good') }}"
           data-strong="{{ __('auth.strength_strong') }}"></p>
        @error('password')
            <p class="mt-1 text-xs font-semibold text-ember-600" role="alert">{{ $message }}</p>
        @enderror
        <p id="password-hint" class="mt-1 text-xs text-ink-400">{{ __('auth.password_hint') }}</p>
    </div>

    <div>
        <label for="password_confirmation" class="mb-1.5 block text-sm font-bold text-ink-900">{{ __('auth.password_confirmation_label') }}</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
               minlength="15" maxlength="128"
               class="w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100">
    </div>

    <button type="submit" class="w-full rounded-xl bg-ember-500 px-4 py-3 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">
        {{ __('auth.password_reset_button') }}
    </button>
</form>

<p class="mt-6 text-center text-sm">
    <a href="{{ route('password.request') }}" class="font-bold text-ember-600 transition hover:text-ember-700">{{ __('auth.forgot_password') }}</a>
</p>
@endsection
