@extends('layouts.app')

@section('title', __('workspace.profile_title'))

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[16rem_minmax(0,1fr)]">
        <aside class="min-w-0"><x-workspace-nav active="profile" /></aside>

        <div class="min-w-0 max-w-2xl">
            <h1 class="text-4xl font-black text-ink-950">{{ __('workspace.profile_title') }}</h1>
            <p class="mt-2 text-ink-600">{{ __('workspace.profile_intro') }}</p>

            @if (session('status'))
                <p class="mt-6 rounded-xl border border-leaf-200 bg-leaf-50 px-4 py-3 text-sm font-semibold text-leaf-800" role="status">{{ session('status') }}</p>
            @endif

            <form method="post" action="{{ route('workspace.profile.update') }}" class="mt-7 space-y-6 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm">
                @csrf
                @method('put')

                <div>
                    <label for="display_name" class="mb-1.5 block text-sm font-bold text-ink-900">{{ __('workspace.display_name_label') }}</label>
                    <input id="display_name" name="display_name" type="text" maxlength="60"
                           value="{{ old('display_name', $user->display_name) }}"
                           aria-describedby="display-name-hint"
                           class="w-full rounded-xl border {{ $errors->has('display_name') ? 'border-ember-400' : 'border-ink-200' }} bg-white px-4 py-3 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100">
                    @error('display_name')
                        <p class="mt-1.5 text-xs font-semibold text-ember-600">{{ $message }}</p>
                    @enderror
                    <p id="display-name-hint" class="mt-1.5 text-xs text-ink-400">{{ __('workspace.display_name_hint') }}</p>
                </div>

                <div>
                    <label for="bio" class="mb-1.5 block text-sm font-bold text-ink-900">{{ __('workspace.bio_label') }}</label>
                    <textarea id="bio" name="bio" rows="5" maxlength="1000"
                              aria-describedby="bio-hint"
                              class="w-full rounded-xl border {{ $errors->has('bio') ? 'border-ember-400' : 'border-ink-200' }} bg-white px-4 py-3 text-sm text-ink-950 transition focus:border-ember-400 focus:outline-none focus:ring-2 focus:ring-ember-100">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <p class="mt-1.5 text-xs font-semibold text-ember-600">{{ $message }}</p>
                    @enderror
                    <p id="bio-hint" class="mt-1.5 text-xs text-ink-400">{{ __('workspace.bio_hint') }}</p>
                </div>

                <button type="submit" class="rounded-xl bg-ember-500 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">
                    {{ __('workspace.save') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
