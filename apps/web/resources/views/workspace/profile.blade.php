@extends('layouts.workspace', ['navActive' => 'profile'])

@section('title', __('workspace.profile_title'))
@section('page-title', __('workspace.profile_title'))
@section('page-context', __('workspace.profile_intro'))

@section('content')
<div class="mx-auto max-w-5xl">
    <div class="max-w-2xl">
        @if (session('status'))
            <p class="mt-6 rounded-xl border border-leaf-200 bg-leaf-50 px-4 py-3 text-sm font-semibold text-leaf-800 dark:border-leaf-800 dark:bg-leaf-950 dark:text-leaf-300" role="status">{{ session('status') }}</p>
        @endif

        <form method="post" action="{{ route('workspace.profile.update') }}" class="mt-7 space-y-6 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
            @csrf
            @method('put')

            <div>
                <label for="display_name" class="mb-1.5 block text-sm font-bold text-ink-900 dark:text-ink-100">{{ __('workspace.display_name_label') }}</label>
                <input id="display_name" name="display_name" type="text" maxlength="60"
                       value="{{ old('display_name', $user->display_name) }}"
                       aria-describedby="display-name-hint"
                       class="w-full rounded-xl border {{ $errors->has('display_name') ? 'border-ember-400 dark:border-ember-500' : 'border-ink-200 dark:border-ink-700' }} bg-white px-4 py-3 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100 dark:bg-ink-900 dark:text-ink-50">
                @error('display_name')
                    <p class="mt-1.5 text-xs font-semibold text-ember-600 dark:text-ember-400">{{ $message }}</p>
                @enderror
                <p id="display-name-hint" class="mt-1.5 text-xs text-ink-400 dark:text-ink-300">{{ __('workspace.display_name_hint') }}</p>
            </div>

            <div>
                <label for="profile_biography" class="mb-1.5 block text-sm font-bold text-ink-900 dark:text-ink-100">{{ __('workspace.bio_label') }}</label>
                <textarea id="profile_biography" name="profile_biography" rows="5" maxlength="1000"
                          aria-describedby="profile-biography-hint"
                          class="w-full rounded-xl border {{ $errors->has('profile_biography') ? 'border-ember-400 dark:border-ember-500' : 'border-ink-200 dark:border-ink-700' }} bg-white px-4 py-3 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100 dark:bg-ink-900 dark:text-ink-50">{{ old('profile_biography', $user->profile_biography) }}</textarea>
                @error('profile_biography')
                    <p class="mt-1.5 text-xs font-semibold text-ember-600 dark:text-ember-400">{{ $message }}</p>
                @enderror
                <p id="profile-biography-hint" class="mt-1.5 text-xs text-ink-400 dark:text-ink-300">{{ __('workspace.bio_hint') }}</p>
            </div>

            <button type="submit" class="rounded-xl bg-ember-500 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">
                {{ __('workspace.save') }}
            </button>
        </form>
    </div>
</div>
@endsection
