@extends('layouts.auth')

@section('title', __('auth.password_request_title'))

@section('content')
<h1 class="text-2xl font-black tracking-tight text-ink-950">{{ __('auth.password_request_title') }}</h1>
<p class="mt-1.5 text-sm text-ink-500">{{ __('auth.password_request_subtitle') }}</p>

@if (session('password_reset_notice'))
    <div class="mt-5 rounded-xl border border-leaf-200 bg-leaf-50 px-4 py-3 text-sm text-leaf-900" role="status">
        <p class="font-semibold">{{ session('password_reset_notice') }}</p>
        <p class="mt-1 text-xs text-leaf-700">{{ __('auth.password_request_help') }}</p>
    </div>
@endif

<form method="post" action="{{ route('password.email') }}" class="mt-7 space-y-5">
    @csrf

    <div>
        <label for="email" class="mb-1.5 block text-sm font-bold text-ink-900">{{ __('auth.email_label') }}</label>
        <input id="email" name="email" type="email" required autofocus autocomplete="email"
               value="{{ old('email') }}"
               class="w-full rounded-xl border {{ $errors->has('email') ? 'border-ember-400' : 'border-ink-200' }} bg-white px-4 py-3 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100">
        @error('email')
            <p class="mt-1.5 text-xs font-semibold text-ember-600" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="w-full rounded-xl bg-ember-500 px-4 py-3 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">
        {{ __('auth.password_request_button') }}
    </button>
</form>

<p class="mt-6 text-center text-sm">
    <a href="{{ route('login') }}" class="font-bold text-ember-600 transition hover:text-ember-700">{{ __('auth.back_to_login') }}</a>
</p>
@endsection
