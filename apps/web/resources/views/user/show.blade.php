@extends('layouts.app')

@section('title', $user->publicName())
@section('meta_description', $user->profile_biography ?: __('user.public_profile_description', ['name' => $user->publicName()]))

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <section class="overflow-hidden rounded-3xl border border-ink-100 bg-white shadow-sm dark:border-ink-800 dark:bg-ink-900">
        <div class="h-28 bg-gradient-to-r from-ember-200 via-leaf-100 to-ink-100 dark:from-ember-950 dark:via-leaf-950 dark:to-ink-800" aria-hidden="true"></div>
        <div class="px-6 pb-8 sm:px-10">
            <div class="-mt-10 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                <div class="flex items-end gap-4">
                    <div class="flex size-20 items-center justify-center rounded-2xl border-4 border-white bg-ink-950 text-2xl font-black text-white shadow-sm dark:border-ink-900 dark:bg-ember-500" aria-hidden="true">{{ mb_strtoupper(mb_substr($user->publicName(), 0, 1)) }}</div>
                    <div class="pb-1">
                        <h1 class="text-3xl font-black text-ink-950 dark:text-white">{{ $user->publicName() }}</h1>
                        <p class="text-sm font-semibold text-ink-500 dark:text-ink-300">{{ '@'.$user->username }}</p>
                    </div>
                </div>
                @if ($isOwner)
                    <a href="{{ route('workspace.profile.edit') }}" class="rounded-xl border border-ink-200 px-4 py-2 text-sm font-bold text-ink-800 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-100 dark:hover:bg-ink-800">{{ __('user.edit_profile') }}</a>
                @endif
            </div>

            @if ($roles->isNotEmpty())
                <div class="mt-6 flex flex-wrap gap-2" aria-label="{{ __('user.public_roles') }}">
                    @foreach ($roles as $role)
                        <span class="rounded-full bg-ember-50 px-3 py-1 text-xs font-bold text-ember-800 dark:bg-ember-950 dark:text-ember-300">{{ config('user.role_labels.'.$role->role_workspace, $role->role_workspace) }}</span>
                    @endforeach
                </div>
            @endif

            <div class="mt-8 grid gap-8 lg:grid-cols-[minmax(0,2fr)_minmax(16rem,1fr)]">
                <div>
                    <h2 class="text-lg font-black text-ink-950 dark:text-white">{{ __('user.about') }}</h2>
                    <p class="mt-3 whitespace-pre-line leading-7 text-ink-700 dark:text-ink-200">{{ $user->profile_biography ?: __('user.no_biography') }}</p>
                    @if ($user->pronoun_text)
                        <p class="mt-4 text-sm text-ink-500 dark:text-ink-300"><span class="font-bold">{{ __('user.pronouns') }}:</span> {{ $user->pronoun_text }}</p>
                    @endif
                </div>
                @php($privacy = $user->privacy_preference ?? [])
                <aside class="rounded-2xl bg-ink-50 p-5 dark:bg-ink-950" aria-labelledby="profile-details">
                    <h2 id="profile-details" class="font-black text-ink-950 dark:text-white">{{ __('user.profile_details') }}</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        @if (($privacy['visibility_gender'] ?? 'PRV') === 'PUB' && $user->gender_identity)
                            <div><dt class="font-bold text-ink-500 dark:text-ink-400">{{ __('user.gender') }}</dt><dd class="text-ink-800 dark:text-ink-100">{{ config('user.gender_options.'.$user->gender_identity, $user->gender_identity) }}</dd></div>
                        @endif
                        @if (($privacy['visibility_handedness'] ?? 'PRV') === 'PUB' && $user->type_handedness !== 'UN')
                            <div><dt class="font-bold text-ink-500 dark:text-ink-400">{{ __('user.handedness') }}</dt><dd class="text-ink-800 dark:text-ink-100">{{ config('user.handedness_options.'.$user->type_handedness) }}</dd></div>
                        @endif
                        @if (($privacy['type_birth_date_display'] ?? 'HID') === 'AGE' && $user->birth_date)
                            <div><dt class="font-bold text-ink-500 dark:text-ink-400">{{ __('user.age') }}</dt><dd class="text-ink-800 dark:text-ink-100">{{ \Illuminate\Support\Carbon::parse($user->birth_date)->age }}</dd></div>
                        @endif
                        @if (($privacy['type_birth_date_display'] ?? 'HID') === 'MDY' && $user->birth_date)
                            <div><dt class="font-bold text-ink-500 dark:text-ink-400">{{ __('user.birthday') }}</dt><dd class="text-ink-800 dark:text-ink-100">{{ $user->birth_date->format('F j') }}</dd></div>
                        @endif
                    </dl>
                </aside>
            </div>
        </div>
    </section>
</div>
@endsection
