@extends('layouts.workspace', ['navActive' => 'profile'])

@section('title', __('workspace.profile_title'))
@section('page-title', __('workspace.profile_title'))
@section('page-context', __('user.profile_intro'))
@section('page-actions')
    <a href="{{ route('user.show', $user->profileRouteKey()) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl border border-ink-200 bg-white px-3.5 py-2 text-sm font-bold text-ink-700 shadow-sm transition hover:border-ember-300 hover:bg-ember-50 hover:text-ember-600 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-200 dark:hover:border-ember-700 dark:hover:bg-ink-800 dark:hover:text-ember-400">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4 text-ink-500 dark:text-ink-400" aria-hidden="true">
            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>
        <span>{{ __('user.preview_profile') }}</span>
    </a>
@endsection

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    @if (session('status'))
        <div class="flex items-center gap-3 rounded-2xl border border-leaf-200 bg-leaf-50/90 p-4 text-sm font-semibold text-leaf-800 shadow-sm dark:border-leaf-800 dark:bg-leaf-950/80 dark:text-leaf-300" role="status">
            <div class="inline-flex size-8 shrink-0 items-center justify-center rounded-xl bg-leaf-100 text-leaf-700 dark:bg-leaf-900 dark:text-leaf-300">
                <svg viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"/></svg>
            </div>
            <span>{{ session('status') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="flex items-center gap-3 rounded-2xl border border-ember-200 bg-ember-50/90 p-4 text-sm font-semibold text-ember-900 shadow-sm dark:border-ember-800 dark:bg-ember-950/80 dark:text-ember-200" role="alert">
            <div class="inline-flex size-8 shrink-0 items-center justify-center rounded-xl bg-ember-100 text-ember-700 dark:bg-ember-900 dark:text-ember-300">
                <svg viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
            </div>
            <span>{{ __('user.fix_profile_errors') }}</span>
        </div>
    @endif

    {{-- Branded Identity Banner Card --}}
    <section class="relative overflow-hidden rounded-3xl border border-ink-100 bg-gradient-to-br from-white via-white to-ember-50/60 p-6 shadow-sm dark:border-ink-800 dark:from-ink-900 dark:via-ink-900 dark:to-ember-950/40 sm:p-8" aria-labelledby="profile-overview">
        <div class="pointer-events-none absolute -right-12 -top-12 size-64 rounded-full bg-ember-400/10 blur-3xl dark:bg-ember-500/10" aria-hidden="true"></div>
        
        <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-4 sm:items-center">
                {{-- Initial Avatar Tile --}}
                <div class="relative flex size-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-ember-500 to-amber-600 font-black text-2xl text-white shadow-md ring-4 ring-white dark:ring-ink-900">
                    {{ strtoupper(substr($user->display_name ?: $user->username, 0, 1)) }}
                    <span class="absolute -bottom-1 -right-1 size-4 rounded-full border-2 border-white bg-leaf-500 dark:border-ink-900" title="Active Account"></span>
                </div>

                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1 rounded-full bg-ember-100/80 px-2.5 py-0.5 text-xs font-black uppercase tracking-wider text-ember-700 dark:bg-ember-950/80 dark:text-ember-300">
                            {{ '@'.$user->username }}
                        </span>
                        @if ($user->hasVerifiedEmail())
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-leaf-600 dark:text-leaf-400" title="Email Verified">
                                <svg viewBox="0 0 16 16" fill="currentColor" class="size-3.5" aria-hidden="true"><path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14Zm3.844-8.791a.75.75 0 0 0-1.188-.918l-3.7 4.79-1.649-1.833a.75.75 0 1 0-1.114 1.004l2.25 2.5a.75.75 0 0 0 1.15-.043l4.25-5.5Z" clip-rule="evenodd"/></svg>
                                <span>Verified Account</span>
                            </span>
                        @endif
                    </div>
                    <h2 id="profile-overview" class="mt-1 text-2xl font-black tracking-tight text-ink-950 dark:text-white sm:text-3xl">
                        {{ $user->publicName() }}
                    </h2>
                    <p class="mt-1 text-sm leading-relaxed text-ink-600 dark:text-ink-300">
                        {{ __('user.profile_overview_hint') }}
                    </p>
                </div>
            </div>

            {{-- Current Visibility Status Pill --}}
            <div class="shrink-0 rounded-2xl border border-ink-200/80 bg-white/90 p-4 shadow-xs backdrop-blur-xs dark:border-ink-700/80 dark:bg-ink-950/80">
                <div class="text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">
                    {{ __('user.current_visibility') }}
                </div>
                <div class="mt-1 flex items-center gap-2">
                    @php($scope = $user->visibility_scope ?? 'PRV')
                    <span class="size-2.5 rounded-full {{ match($scope) { 'PUB' => 'bg-leaf-500 shadow-xs shadow-leaf-500/50', 'UNL' => 'bg-amber-500', default => 'bg-ink-400' } }}"></span>
                    <span class="font-black text-ink-900 dark:text-white">
                        {{ __(match ($scope) { 'PUB' => 'user.visibility_public', 'UNL' => 'user.visibility_unlisted', default => 'user.visibility_private' }) }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    <form method="post" action="{{ route('workspace.profile.update') }}" class="space-y-6">
        @csrf @method('put')

        {{-- Section 1: Public Identity --}}
        <section class="rounded-3xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900 sm:p-7" aria-labelledby="public-identity">
            <div class="flex items-center gap-3 border-b border-ink-100 pb-4 dark:border-ink-800">
                <div class="inline-flex size-10 items-center justify-center rounded-xl bg-ember-100 text-ember-600 dark:bg-ember-950/80 dark:text-ember-400">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5" aria-hidden="true">
                        <circle cx="12" cy="8" r="5"/>
                        <path d="M20 21a8 8 0 0 0-16 0"/>
                    </svg>
                </div>
                <div>
                    <h3 id="public-identity" class="text-lg font-black text-ink-950 dark:text-white">{{ __('user.public_identity') }}</h3>
                    <p class="text-xs text-ink-500 dark:text-ink-400">{{ __('user.public_identity_hint') }}</p>
                </div>
            </div>

            <div class="mt-6 grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="display_name" class="mb-2 block text-sm font-bold text-ink-900 dark:text-ink-100">
                        {{ __('workspace.display_name_label') }}
                    </label>
                    <input id="display_name" name="display_name" maxlength="60" value="{{ old('display_name', $user->display_name) }}" 
                           placeholder="e.g. Wellness Enthusiast"
                           class="w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-950 shadow-2xs transition focus:border-ember-500 focus:outline-none focus:ring-2 focus:ring-ember-500/20 dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-400">
                    <p class="mt-1.5 text-xs text-ink-500 dark:text-ink-400">{{ __('workspace.display_name_hint') }}</p>
                    @error('display_name')<p class="mt-1.5 text-xs font-semibold text-ember-600 dark:text-ember-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="pronoun_text" class="mb-2 block text-sm font-bold text-ink-900 dark:text-ink-100">
                        {{ __('user.pronouns') }}
                    </label>
                    <input id="pronoun_text" name="pronoun_text" maxlength="40" value="{{ old('pronoun_text', $user->pronoun_text) }}" 
                           placeholder="e.g. she/her, they/them" 
                           class="w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-950 shadow-2xs transition focus:border-ember-500 focus:outline-none focus:ring-2 focus:ring-ember-500/20 dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-400">
                    <p class="mt-1.5 text-xs text-ink-500 dark:text-ink-400">Optional pronouns shown on public profile</p>
                    @error('pronoun_text')<p class="mt-1.5 text-xs font-semibold text-ember-600 dark:text-ember-400">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="profile_biography" class="mb-2 block text-sm font-bold text-ink-900 dark:text-ink-100">
                        {{ __('workspace.bio_label') }}
                    </label>
                    <textarea id="profile_biography" name="profile_biography" rows="5" maxlength="1000" 
                              placeholder="Tell the community a little about your wellness journey or favorite experiences..."
                              class="w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-950 shadow-2xs transition focus:border-ember-500 focus:outline-none focus:ring-2 focus:ring-ember-500/20 dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-400">{{ old('profile_biography', $user->profile_biography) }}</textarea>
                    <div class="mt-1.5 flex items-center justify-between text-xs text-ink-500 dark:text-ink-400">
                        <span>{{ __('user.biography_hint') }}</span>
                        <span>Max 1,000 chars</span>
                    </div>
                    @error('profile_biography')<p class="mt-1.5 text-xs font-semibold text-ember-600 dark:text-ember-400">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        {{-- Section 2: Personal Details & Privacy --}}
        @php($privacy = $user->privacy_preference ?? [])
        <section class="rounded-3xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900 sm:p-7" aria-labelledby="personal-details">
            <div class="flex items-center gap-3 border-b border-ink-100 pb-4 dark:border-ink-800">
                <div class="inline-flex size-10 items-center justify-center rounded-xl bg-leaf-100 text-leaf-700 dark:bg-leaf-950/80 dark:text-leaf-400">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <div>
                    <h3 id="personal-details" class="text-lg font-black text-ink-950 dark:text-white">{{ __('user.personal_details') }}</h3>
                    <p class="text-xs text-ink-500 dark:text-ink-400">{{ __('user.personal_details_hint') }}</p>
                </div>
            </div>

            <div class="mt-6 grid gap-6 sm:grid-cols-2">
                {{-- Gender Identity --}}
                <div class="space-y-4 rounded-2xl border border-ink-100 bg-ink-50/50 p-4 dark:border-ink-800 dark:bg-ink-950/50">
                    <div>
                        <label for="gender_identity" class="mb-1.5 block text-sm font-bold text-ink-900 dark:text-white">
                            {{ __('user.gender') }}
                        </label>
                        <select id="gender_identity" name="gender_identity" 
                                class="w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-950 shadow-2xs transition focus:border-ember-500 focus:outline-none focus:ring-2 focus:ring-ember-500/20 dark:border-ink-700 dark:bg-ink-900 dark:text-white">
                            <option value="">{{ __('user.not_set') }}</option>
                            @foreach(config('user.gender_options') as $code => $label)
                                <option value="{{ $code }}" @selected(old('gender_identity', $user->gender_identity)===$code)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="visibility_gender" class="mb-1.5 block text-xs font-bold text-ink-600 dark:text-ink-300">
                            {{ __('user.gender_visibility') }}
                        </label>
                        <select id="visibility_gender" name="visibility_gender" 
                                class="w-full rounded-xl border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-950 shadow-2xs transition focus:border-ember-500 focus:outline-none focus:ring-2 focus:ring-ember-500/20 dark:border-ink-700 dark:bg-ink-900 dark:text-white">
                            <option value="PRV" @selected(old('visibility_gender', $privacy['visibility_gender'] ?? 'PRV')==='PRV')>{{ __('user.private') }}</option>
                            <option value="PUB" @selected(old('visibility_gender', $privacy['visibility_gender'] ?? 'PRV')==='PUB')>{{ __('user.public') }}</option>
                        </select>
                    </div>
                </div>

                {{-- Handedness --}}
                <div class="space-y-4 rounded-2xl border border-ink-100 bg-ink-50/50 p-4 dark:border-ink-800 dark:bg-ink-950/50">
                    <div>
                        <label for="type_handedness" class="mb-1.5 block text-sm font-bold text-ink-900 dark:text-white">
                            {{ __('user.handedness') }}
                        </label>
                        <select id="type_handedness" name="type_handedness" 
                                class="w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-950 shadow-2xs transition focus:border-ember-500 focus:outline-none focus:ring-2 focus:ring-ember-500/20 dark:border-ink-700 dark:bg-ink-900 dark:text-white">
                            @foreach(config('user.handedness_options') as $code => $label)
                                <option value="{{ $code }}" @selected(old('type_handedness', $user->type_handedness ?? 'UN')===$code)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="visibility_handedness" class="mb-1.5 block text-xs font-bold text-ink-600 dark:text-ink-300">
                            {{ __('user.handedness_visibility') }}
                        </label>
                        <select id="visibility_handedness" name="visibility_handedness" 
                                class="w-full rounded-xl border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-950 shadow-2xs transition focus:border-ember-500 focus:outline-none focus:ring-2 focus:ring-ember-500/20 dark:border-ink-700 dark:bg-ink-900 dark:text-white">
                            <option value="PRV" @selected(old('visibility_handedness', $privacy['visibility_handedness'] ?? 'PRV')==='PRV')>{{ __('user.private') }}</option>
                            <option value="PUB" @selected(old('visibility_handedness', $privacy['visibility_handedness'] ?? 'PRV')==='PUB')>{{ __('user.public') }}</option>
                        </select>
                    </div>
                </div>

                {{-- Birth Date --}}
                <div class="space-y-4 rounded-2xl border border-ink-100 bg-ink-50/50 p-4 dark:border-ink-800 dark:bg-ink-950/50 sm:col-span-2">
                    <div class="grid gap-4 sm:grid-cols-2 sm:items-center">
                        <div>
                            <label class="mb-1.5 block text-sm font-bold text-ink-900 dark:text-white">
                                {{ __('user.birth_date') }}
                            </label>
                            <div class="rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-800 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-200">
                                {{ $user->birth_date?->format('F j, Y') ?: 'Not recorded' }}
                            </div>
                            <p class="mt-1 text-xs text-ink-500 dark:text-ink-400">{{ __('user.birth_date_private') }}</p>
                        </div>
                        <div>
                            <label for="type_birth_date_display" class="mb-1.5 block text-sm font-bold text-ink-900 dark:text-white">
                                {{ __('user.birth_date_display') }}
                            </label>
                            <select id="type_birth_date_display" name="type_birth_date_display" 
                                    class="w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-950 shadow-2xs transition focus:border-ember-500 focus:outline-none focus:ring-2 focus:ring-ember-500/20 dark:border-ink-700 dark:bg-ink-900 dark:text-white">
                                <option value="HID" @selected(old('type_birth_date_display', $privacy['type_birth_date_display'] ?? 'HID')==='HID')>{{ __('user.hidden') }}</option>
                                <option value="AGE" @selected(old('type_birth_date_display', $privacy['type_birth_date_display'] ?? 'HID')==='AGE')>{{ __('user.age_only') }}</option>
                                <option value="MDY" @selected(old('type_birth_date_display', $privacy['type_birth_date_display'] ?? 'HID')==='MDY')>{{ __('user.month_day_only') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section 3: Overall Profile Visibility --}}
        <section class="rounded-3xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900 sm:p-7" aria-labelledby="profile-visibility">
            <div class="flex items-center gap-3 border-b border-ink-100 pb-4 dark:border-ink-800">
                <div class="inline-flex size-10 items-center justify-center rounded-xl bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-400">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="2" y1="12" x2="22" y2="12"/>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                    </svg>
                </div>
                <div>
                    <h3 id="profile-visibility" class="text-lg font-black text-ink-950 dark:text-white">{{ __('user.profile_visibility') }}</h3>
                    <p class="text-xs text-ink-500 dark:text-ink-400">Choose who can access your public profile page</p>
                </div>
            </div>

            <div class="mt-6">
                <select name="visibility_scope" 
                        class="w-full max-w-xl rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-950 shadow-2xs transition focus:border-ember-500 focus:outline-none focus:ring-2 focus:ring-ember-500/20 dark:border-ink-700 dark:bg-ink-950 dark:text-white">
                    <option value="PUB" @selected(old('visibility_scope', $user->visibility_scope ?? 'PRV')==='PUB')>{{ __('user.visibility_public') }}</option>
                    <option value="UNL" @selected(old('visibility_scope', $user->visibility_scope ?? 'PRV')==='UNL')>{{ __('user.visibility_unlisted') }}</option>
                    <option value="PRV" @selected(old('visibility_scope', $user->visibility_scope ?? 'PRV')==='PRV')>{{ __('user.visibility_private') }}</option>
                </select>
            </div>
        </section>

        {{-- Floating Save Bar --}}
        <div class="sticky bottom-4 z-20 flex items-center justify-between rounded-2xl border border-ink-100/90 bg-white/90 p-4 shadow-xl backdrop-blur-md dark:border-ink-800/90 dark:bg-ink-900/90">
            <span class="text-xs font-semibold text-ink-500 dark:text-ink-400 hidden sm:inline">
                Unsaved changes will be applied to your profile
            </span>
            <div class="flex items-center gap-3 ml-auto">
                <button type="submit" 
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-ember-500 to-ember-600 px-6 py-3 text-sm font-bold text-white shadow-sm transition duration-150 hover:-translate-y-0.5 hover:from-ember-600 hover:to-ember-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-ember-500 focus:ring-offset-2 dark:focus:ring-offset-ink-900">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"/></svg>
                    <span>{{ __('workspace.save') }}</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
