@extends('layouts.auth')

@section('title', __('auth.verify_email_title'))

@section('content')
<div class="flex justify-center">
    <span class="flex size-14 items-center justify-center rounded-2xl bg-ember-50 text-ember-600 dark:bg-ember-950 dark:text-ember-400">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="size-7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5A1.5 1.5 0 0 1 4.5 6h15A1.5 1.5 0 0 1 21 7.5v9a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 16.5v-9Zm0 0 9 6 9-6"/></svg>
    </span>
</div>

<h1 class="mt-5 text-center text-2xl font-black tracking-tight text-ink-950 dark:text-ink-50">
    {{ session('registered_email') ? __('auth.register_success_title') : __('auth.verify_email_title') }}
</h1>

<p class="mt-2 text-center text-sm text-ink-500 dark:text-ink-400">
    @if (session('registered_email'))
        {{ __('auth.register_success_text', ['email' => session('registered_email')]) }}
    @else
        {{ __('auth.verify_email_text') }}
    @endif
</p>

@if (session('verify_notice'))
    <p class="mt-5 rounded-xl border border-ink-200 bg-ink-50 px-4 py-3 text-center text-sm font-semibold text-ink-700 dark:border-ink-700 dark:bg-ink-800 dark:text-ink-200" role="status">
        {{ session('verify_notice') }}
    </p>
@endif

<form method="post" action="{{ route('verification.resend') }}" class="mt-7 space-y-4">
    @csrf
    <div>
        <label for="resend-email" class="mb-1.5 block text-sm font-bold text-ink-900 dark:text-ink-100">{{ __('auth.email_label') }}</label>
        <input id="resend-email" name="email" type="email" required autocomplete="email"
               value="{{ old('email', session('registered_email')) }}"
               class="w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50 dark:placeholder:text-ink-400">
        @error('email')
            <p class="mt-1.5 text-xs font-semibold text-ember-600 dark:text-ember-400">{{ $message }}</p>
        @enderror
    </div>
    <button type="submit" class="w-full rounded-xl border border-ink-200 px-4 py-3 text-sm font-bold text-ink-800 transition hover:border-ember-300 hover:bg-ember-50 dark:border-ink-700 dark:text-ink-200 dark:hover:border-ember-700 dark:hover:bg-ember-950">
        {{ __('auth.verify_email_resend_button') }}
    </button>
</form>

<p class="mt-6 text-center text-sm text-ink-500 dark:text-ink-400">
    <a href="{{ route('login') }}" class="font-bold text-ember-600 transition hover:text-ember-700 dark:text-ember-400 dark:hover:text-ember-300">{{ __('auth.log_in_link') }}</a>
</p>
@endsection
