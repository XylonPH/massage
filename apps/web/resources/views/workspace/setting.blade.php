@extends('layouts.workspace', ['navActive' => 'settings'])

@section('title', __('workspace.settings_title'))
@section('page-title', __('workspace.settings_title'))
@section('page-context', __('user.settings_intro'))

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
            <span>{{ __('user.fix_setting_errors') }}</span>
        </div>
    @endif

    {{-- Quick Anchor Navigation Bar --}}
    <div class="flex items-center gap-2 overflow-x-auto rounded-2xl border border-ink-100 bg-white p-2 shadow-2xs dark:border-ink-800 dark:bg-ink-900">
        <a href="#account-settings" class="inline-flex shrink-0 items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold text-ink-700 transition hover:bg-ink-50 hover:text-ember-600 dark:text-ink-200 dark:hover:bg-ink-800 dark:hover:text-ember-400">
            <svg viewBox="0 0 20 20" fill="currentColor" class="size-4 text-ink-400" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-5.5-2.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10 12a5.99 5.99 0 0 0-4.793 2.39A6.483 6.483 0 0 0 10 16.5a6.483 6.483 0 0 0 4.793-2.11A5.99 5.99 0 0 0 10 12Z" clip-rule="evenodd"/></svg>
            <span>Account</span>
        </a>
        <a href="#appearance-settings" class="inline-flex shrink-0 items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold text-ink-700 transition hover:bg-ink-50 hover:text-ember-600 dark:text-ink-200 dark:hover:bg-ink-800 dark:hover:text-ember-400">
            <svg viewBox="0 0 20 20" fill="currentColor" class="size-4 text-ink-400" aria-hidden="true"><path fill-rule="evenodd" d="M4.25 2A2.25 2.25 0 0 0 2 4.25v2.5A2.25 2.25 0 0 0 4.25 9h2.5A2.25 2.25 0 0 0 9 6.75v-2.5A2.25 2.25 0 0 0 6.75 2h-2.5Zm0 11.5A2.25 2.25 0 0 0 2 15.75v2.5A2.25 2.25 0 0 0 4.25 20h2.5A2.25 2.25 0 0 0 9 17.75v-2.5A2.25 2.25 0 0 0 6.75 13.5h-2.5Zm11.5-11.5A2.25 2.25 0 0 0 13.5 4.25v2.5A2.25 2.25 0 0 0 15.75 9h2.5A2.25 2.25 0 0 0 20 6.75v-2.5A2.25 2.25 0 0 0 17.75 2h-2.5Z" clip-rule="evenodd"/></svg>
            <span>Appearance</span>
        </a>
        <a href="#notification-settings" class="inline-flex shrink-0 items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold text-ink-700 transition hover:bg-ink-50 hover:text-ember-600 dark:text-ink-200 dark:hover:bg-ink-800 dark:hover:text-ember-400">
            <svg viewBox="0 0 20 20" fill="currentColor" class="size-4 text-ink-400" aria-hidden="true"><path d="M10 2a6 6 0 0 0-6 6v3.586l-.707.707A1 1 0 0 0 4 14h12a1 1 0 0 0 .707-1.707L16 11.586V8a6 6 0 0 0-6-6ZM10 18a3 3 0 0 1-3-3h6a3 3 0 0 1-3 3Z"/></svg>
            <span>Notifications</span>
        </a>
        <a href="#security-sessions" class="inline-flex shrink-0 items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold text-ink-700 transition hover:bg-ink-50 hover:text-ember-600 dark:text-ink-200 dark:hover:bg-ink-800 dark:hover:text-ember-400">
            <svg viewBox="0 0 20 20" fill="currentColor" class="size-4 text-ink-400" aria-hidden="true"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd"/></svg>
            <span>Sessions</span>
        </a>
        <a href="#recognized-devices" class="inline-flex shrink-0 items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold text-ink-700 transition hover:bg-ink-50 hover:text-ember-600 dark:text-ink-200 dark:hover:bg-ink-800 dark:hover:text-ember-400">
            <svg viewBox="0 0 20 20" fill="currentColor" class="size-4 text-ink-400" aria-hidden="true"><path fill-rule="evenodd" d="M2 4.25A2.25 2.25 0 0 1 4.25 2h11.5A2.25 2.25 0 0 1 18 4.25v8.5A2.25 2.25 0 0 1 15.75 15h-3.105a3.501 3.501 0 0 0 1.1 1.5h1.505a.75.75 0 0 1 0 1.5h-10.5a.75.75 0 0 1 0-1.5h1.505a3.501 3.501 0 0 0 1.1-1.5H4.25A2.25 2.25 0 0 1 2 12.75v-8.5Z" clip-rule="evenodd"/></svg>
            <span>Devices</span>
        </a>
        <a href="#mfa-info" class="inline-flex shrink-0 items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold text-ink-700 transition hover:bg-ink-50 hover:text-ember-600 dark:text-ink-200 dark:hover:bg-ink-800 dark:hover:text-ember-400">
            <svg viewBox="0 0 20 20" fill="currentColor" class="size-4 text-ink-400" aria-hidden="true"><path fill-rule="evenodd" d="M10 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16Zm-1 5a1 1 0 1 1 2 0v3.586l1.293 1.293a1 1 0 0 1-1.414 1.414l-1.586-1.586A1 1 0 0 1 9 11V7Z" clip-rule="evenodd"/></svg>
            <span>MFA Security</span>
        </a>
    </div>

    @php($appearance = $user->appearance_preference ?? [])
    @php($notifications = $user->notification_preference ?? [])
    @php($account = $user->account_preference ?? [])

    <form method="post" action="{{ route('workspace.setting.update') }}" class="space-y-6">
        @csrf @method('put')

        {{-- Section 1: Account and Region --}}
        <section id="account-settings" class="scroll-mt-24 rounded-3xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900 sm:p-7" aria-labelledby="account-settings-heading">
            <div class="flex items-center gap-3 border-b border-ink-100 pb-4 dark:border-ink-800">
                <div class="inline-flex size-10 items-center justify-center rounded-xl bg-ember-100 text-ember-600 dark:bg-ember-950/80 dark:text-ember-400">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5" aria-hidden="true">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div>
                    <h2 id="account-settings-heading" class="text-lg font-black text-ink-950 dark:text-white">{{ __('user.account_and_region') }}</h2>
                    <p class="text-xs text-ink-500 dark:text-ink-400">Manage account credentials, language, and regional time zones</p>
                </div>
            </div>

            {{-- Account Read-Only Details Card --}}
            <div class="mt-6 rounded-2xl border border-ink-100 bg-ink-50/50 p-4 dark:border-ink-800 dark:bg-ink-950/50">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <span class="block text-xs font-bold text-ink-500 dark:text-ink-400">{{ __('workspace.settings_username') }}</span>
                        <span class="mt-1 inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-sm font-bold text-ink-900 shadow-2xs dark:bg-ink-900 dark:text-white">
                            {{ '@'.$user->username }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-ink-500 dark:text-ink-400">{{ __('workspace.settings_email') }}</span>
                        <div class="mt-1 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-sm font-bold text-ink-900 shadow-2xs dark:bg-ink-900 dark:text-white">
                                {{ $user->email }}
                            </span>
                            @if($user->hasVerifiedEmail())
                                <span class="inline-flex items-center gap-1 rounded-full bg-leaf-100 px-2.5 py-0.5 text-xs font-bold text-leaf-700 dark:bg-leaf-950 dark:text-leaf-300">
                                    <svg viewBox="0 0 16 16" fill="currentColor" class="size-3" aria-hidden="true"><path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14Zm3.844-8.791a.75.75 0 0 0-1.188-.918l-3.7 4.79-1.649-1.833a.75.75 0 1 0-1.114 1.004l2.25 2.5a.75.75 0 0 0 1.15-.043l4.25-5.5Z" clip-rule="evenodd"/></svg>
                                    <span>{{ __('workspace.settings_email_verified') }}</span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="interface_language_id" class="mb-2 block text-sm font-bold text-ink-900 dark:text-white">
                        {{ __('user.interface_language') }}
                    </label>
                    <select id="interface_language_id" name="interface_language_id" 
                            class="w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-950 shadow-2xs transition focus:border-ember-500 focus:outline-none focus:ring-2 focus:ring-ember-500/20 dark:border-ink-700 dark:bg-ink-950 dark:text-white">
                        <option value="3049">English</option>
                    </select>
                    <p class="mt-1.5 text-xs text-ink-500 dark:text-ink-400">{{ __('user.language_rollout_note') }}</p>
                </div>
                <div>
                    <label for="time_zone_id" class="mb-2 block text-sm font-bold text-ink-900 dark:text-white">
                        {{ __('user.time_zone') }}
                    </label>
                    <select id="time_zone_id" name="time_zone_id" 
                            class="w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-950 shadow-2xs transition focus:border-ember-500 focus:outline-none focus:ring-2 focus:ring-ember-500/20 dark:border-ink-700 dark:bg-ink-950 dark:text-white">
                        @foreach($timeZones as $timeZone)
                            <option value="{{ $timeZone->time_zone_id }}" @selected((int) old('time_zone_id', $account['time_zone_id'] ?? 255)===(int) $timeZone->time_zone_id)>
                                {{ $timeZone->time_zone_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        {{-- Section 2: Appearance and Accessibility --}}
        <section id="appearance-settings" class="scroll-mt-24 rounded-3xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900 sm:p-7" aria-labelledby="appearance-settings-heading">
            <div class="flex items-center gap-3 border-b border-ink-100 pb-4 dark:border-ink-800">
                <div class="inline-flex size-10 items-center justify-center rounded-xl bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-400">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5" aria-hidden="true">
                        <circle cx="12" cy="12" r="4"/>
                        <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M18.07 4.93l1.41 1.41"/>
                    </svg>
                </div>
                <div>
                    <h2 id="appearance-settings-heading" class="text-lg font-black text-ink-950 dark:text-white">{{ __('user.appearance') }}</h2>
                    <p class="text-xs text-ink-500 dark:text-ink-400">Customize color modes, text sizing, and accessibility controls</p>
                </div>
            </div>

            <div class="mt-6 space-y-6">
                <div>
                    <label for="color_mode" class="mb-2 block text-sm font-bold text-ink-900 dark:text-white">
                        {{ __('user.color_mode') }}
                    </label>
                    <select id="color_mode" name="color_mode" 
                            class="w-full max-w-md rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-950 shadow-2xs transition focus:border-ember-500 focus:outline-none focus:ring-2 focus:ring-ember-500/20 dark:border-ink-700 dark:bg-ink-950 dark:text-white">
                        <option value="SYS" @selected(old('color_mode', $appearance['color_mode'] ?? 'SYS')==='SYS')>{{ __('user.color_mode_system') }}</option>
                        <option value="LGT" @selected(old('color_mode', $appearance['color_mode'] ?? 'SYS')==='LGT')>{{ __('user.color_mode_light') }}</option>
                        <option value="DRK" @selected(old('color_mode', $appearance['color_mode'] ?? 'SYS')==='DRK')>{{ __('user.color_mode_dark') }}</option>
                    </select>
                    <p class="mt-1.5 text-xs text-ink-500 dark:text-ink-400">{{ __('user.color_mode_hint') }}</p>
                </div>

                <div>
                    <label for="text_scale_percent" class="mb-2 block text-sm font-bold text-ink-900 dark:text-white">
                        {{ __('user.text_size') }}
                    </label>
                    <select id="text_scale_percent" name="text_scale_percent" 
                            class="w-full max-w-md rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-950 shadow-2xs transition focus:border-ember-500 focus:outline-none focus:ring-2 focus:ring-ember-500/20 dark:border-ink-700 dark:bg-ink-950 dark:text-white">
                        @foreach([90,100,110,125] as $scale)
                            <option value="{{ $scale }}" @selected(old('text_scale_percent', $appearance['text_scale_percent'] ?? 100)===$scale)>{{ $scale }}%</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="group relative flex cursor-pointer items-start gap-3.5 rounded-2xl border border-ink-100 bg-ink-50/50 p-4 transition hover:border-ember-300 dark:border-ink-800 dark:bg-ink-950/50 dark:hover:border-ember-700">
                        <input type="checkbox" name="is_high_contrast" value="1" @checked(old('is_high_contrast', $appearance['is_high_contrast'] ?? false)) 
                               class="mt-0.5 size-4 rounded-md border-ink-300 text-ember-500 focus:ring-ember-500 dark:border-ink-700">
                        <div>
                            <span class="block text-sm font-bold text-ink-950 dark:text-white">{{ __('user.high_contrast') }}</span>
                            <span class="mt-0.5 block text-xs text-ink-500 dark:text-ink-400">Increase border and text contrast across elements</span>
                        </div>
                    </label>

                    <label class="group relative flex cursor-pointer items-start gap-3.5 rounded-2xl border border-ink-100 bg-ink-50/50 p-4 transition hover:border-ember-300 dark:border-ink-800 dark:bg-ink-950/50 dark:hover:border-ember-700">
                        <input type="checkbox" name="is_reduced_motion" value="1" @checked(old('is_reduced_motion', $appearance['is_reduced_motion'] ?? false)) 
                               class="mt-0.5 size-4 rounded-md border-ink-300 text-ember-500 focus:ring-ember-500 dark:border-ink-700">
                        <div>
                            <span class="block text-sm font-bold text-ink-950 dark:text-white">{{ __('user.reduced_motion') }}</span>
                            <span class="mt-0.5 block text-xs text-ink-500 dark:text-ink-400">Minimize background animations and transitions</span>
                        </div>
                    </label>
                </div>
            </div>
        </section>

        {{-- Section 3: Notifications --}}
        <section id="notification-settings" class="scroll-mt-24 rounded-3xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900 sm:p-7" aria-labelledby="notification-settings-heading">
            <div class="flex items-center gap-3 border-b border-ink-100 pb-4 dark:border-ink-800">
                <div class="inline-flex size-10 items-center justify-center rounded-xl bg-leaf-100 text-leaf-700 dark:bg-leaf-950/80 dark:text-leaf-400">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5" aria-hidden="true">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                </div>
                <div>
                    <h2 id="notification-settings-heading" class="text-lg font-black text-ink-950 dark:text-white">{{ __('user.notifications') }}</h2>
                    <p class="text-xs text-ink-500 dark:text-ink-400">Choose how and when you receive updates and digests</p>
                </div>
            </div>

            <div class="mt-6 grid gap-6 md:grid-cols-3">
                {{-- Delivery Channels --}}
                <fieldset class="rounded-2xl border border-ink-100 bg-ink-50/50 p-4 dark:border-ink-800 dark:bg-ink-950/50">
                    <legend class="text-sm font-bold text-ink-950 dark:text-white">{{ __('user.channels') }}</legend>
                    <div class="mt-3 space-y-2.5">
                        @foreach(['WEB'=>__('user.channel_web'),'EML'=>__('user.channel_email')] as $code=>$label)
                            <label class="flex items-center gap-2.5 text-sm text-ink-800 dark:text-ink-200">
                                <input type="checkbox" name="notification_channel_list[]" value="{{ $code }}" 
                                       @checked(in_array($code, old('notification_channel_list', $notifications['notification_channel_list'] ?? ['WEB','EML']), true)) 
                                       class="size-4 rounded-md border-ink-300 text-ember-500 focus:ring-ember-500 dark:border-ink-700">
                                <span class="font-medium">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                {{-- Notification Categories --}}
                <fieldset class="rounded-2xl border border-ink-100 bg-ink-50/50 p-4 dark:border-ink-800 dark:bg-ink-950/50">
                    <legend class="text-sm font-bold text-ink-950 dark:text-white">{{ __('user.categories') }}</legend>
                    <div class="mt-3 space-y-2.5">
                        @foreach(['SEC'=>__('user.category_security'),'BKG'=>__('user.category_booking'),'CON'=>__('user.category_contribution')] as $code=>$label)
                            <label class="flex items-center gap-2.5 text-sm text-ink-800 dark:text-ink-200">
                                <input type="checkbox" name="notification_category_list[]" value="{{ $code }}" 
                                       @checked(in_array($code, old('notification_category_list', $notifications['notification_category_list'] ?? ['SEC']), true)) 
                                       class="size-4 rounded-md border-ink-300 text-ember-500 focus:ring-ember-500 dark:border-ink-700">
                                <span class="font-medium">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                {{-- Digest Timing --}}
                <div class="rounded-2xl border border-ink-100 bg-ink-50/50 p-4 dark:border-ink-800 dark:bg-ink-950/50">
                    <label for="digest_frequency" class="block text-sm font-bold text-ink-950 dark:text-white">
                        {{ __('user.digest') }}
                    </label>
                    <select id="digest_frequency" name="digest_frequency" 
                            class="mt-3 w-full rounded-xl border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-950 shadow-2xs transition focus:border-ember-500 focus:outline-none focus:ring-2 focus:ring-ember-500/20 dark:border-ink-700 dark:bg-ink-900 dark:text-white">
                        @foreach(['IMM'=>__('user.digest_immediate'),'DLY'=>__('user.digest_daily'),'WKL'=>__('user.digest_weekly'),'OFF'=>__('user.digest_off')] as $code=>$label)
                            <option value="{{ $code }}" @selected(old('digest_frequency', $notifications['digest_frequency'] ?? 'IMM')===$code)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Marketing Opt-In Preference --}}
            <div class="mt-6 rounded-2xl border border-ink-100 bg-ink-50/50 p-4 dark:border-ink-800 dark:bg-ink-950/50">
                <label class="flex cursor-pointer items-start gap-3 text-sm text-ink-800 dark:text-ink-200">
                    <input type="checkbox" name="marketing_opt_in" value="1" 
                           @checked(old('marketing_opt_in', $notifications['is_marketing_email_opt_in'] ?? $user->is_marketing_email_opt_in)) 
                           class="mt-0.5 size-4 rounded-md border-ink-300 text-ember-500 focus:ring-ember-500 dark:border-ink-700">
                    <div>
                        <span class="block font-bold text-ink-950 dark:text-white">{{ __('workspace.settings_marketing_label') }}</span>
                        <span class="mt-0.5 block text-xs text-ink-500 dark:text-ink-400">{{ __('workspace.settings_marketing_hint') }}</span>
                    </div>
                </label>
            </div>
        </section>

        {{-- Floating Save Bar --}}
        <div class="sticky bottom-4 z-20 flex items-center justify-between rounded-2xl border border-ink-100/90 bg-white/90 p-4 shadow-xl backdrop-blur-md dark:border-ink-800/90 dark:bg-ink-900/90">
            <span class="text-xs font-semibold text-ink-500 dark:text-ink-400 hidden sm:inline">
                Unsaved preference changes will take effect immediately upon saving
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

    {{-- Section 4: Security and Active Sessions --}}
    <section id="security-sessions" class="scroll-mt-24 rounded-3xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900 sm:p-7" aria-labelledby="security-sessions-heading">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-ink-100 pb-4 dark:border-ink-800">
            <div class="flex items-center gap-3">
                <div class="inline-flex size-10 items-center justify-center rounded-xl bg-ember-100 text-ember-600 dark:bg-ember-950/80 dark:text-ember-400">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5" aria-hidden="true">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <div>
                    <h2 id="security-sessions-heading" class="text-lg font-black text-ink-950 dark:text-white">{{ __('user.sessions') }}</h2>
                    <p class="text-xs text-ink-500 dark:text-ink-400">{{ __('user.sessions_hint') }}</p>
                </div>
            </div>

            @if($sessions->where('status_user_session','ACT')->count() > 1)
                <form method="post" action="{{ route('workspace.setting.session.destroy-others') }}">
                    @csrf @method('delete')
                    <button class="inline-flex items-center gap-1.5 rounded-xl border border-ember-300 bg-ember-50/50 px-3.5 py-2 text-xs font-bold text-ember-700 shadow-2xs transition hover:bg-ember-100 dark:border-ember-800 dark:bg-ember-950/50 dark:text-ember-300 dark:hover:bg-ember-900">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="size-3.5" aria-hidden="true"><path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M19 10a.75.75 0 0 0-.75-.75H9.56l1.72-1.72a.75.75 0 0 0-1.06-1.06l-3 3a.75.75 0 0 0 0 1.06l3 3a.75.75 0 1 0 1.06-1.06L9.56 10.75h8.69c.414 0 .75-.336.75-.75Z" clip-rule="evenodd"/></svg>
                        <span>{{ __('user.sign_out_others') }}</span>
                    </button>
                </form>
            @endif
        </div>

        <div class="mt-6 space-y-3">
            @forelse($sessions as $session)
                <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-ink-100 bg-ink-50/50 p-4 transition hover:border-ink-200 dark:border-ink-800 dark:bg-ink-950/50 dark:hover:border-ink-700">
                    <div class="flex items-center gap-3.5">
                        <div class="inline-flex size-10 shrink-0 items-center justify-center rounded-xl bg-white text-ink-700 shadow-2xs dark:bg-ink-900 dark:text-ink-200">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5" aria-hidden="true">
                                <rect width="20" height="14" x="2" y="3" rx="2"/>
                                <line x1="8" y1="21" x2="16" y2="21"/>
                                <line x1="12" y1="17" x2="12" y2="21"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm font-bold text-ink-950 dark:text-white">
                                    {{ $session->user_agent_summary ?: __('user.unknown_browser') }}
                                </p>
                                @if($session->status_user_session==='ACT')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-leaf-100 px-2 py-0.5 text-xs font-bold text-leaf-700 dark:bg-leaf-950 dark:text-leaf-300">
                                        <span class="size-1.5 rounded-full bg-leaf-500 animate-pulse"></span>
                                        <span>{{ __('user.active') }}</span>
                                    </span>
                                @endif
                            </div>
                            <p class="mt-0.5 text-xs text-ink-500 dark:text-ink-400">
                                {{ __('user.last_active', ['time' => optional($session->last_activity_at)->diffForHumans() ?? __('user.unknown')]) }} · 
                                {{ $session->type_authentication_method === 'PWD' ? __('user.password') : $session->type_authentication_method }}
                            </p>
                        </div>
                    </div>

                    @if($session->status_user_session==='ACT')
                        <form method="post" action="{{ route('workspace.setting.session.destroy', $session) }}">
                            @csrf @method('delete')
                            <button class="inline-flex items-center gap-1 rounded-xl border border-ink-200 bg-white px-3 py-1.5 text-xs font-bold text-ember-700 shadow-2xs transition hover:bg-ember-50 dark:border-ink-700 dark:bg-ink-900 dark:text-ember-300 dark:hover:bg-ember-950">
                                <span>{{ __('user.sign_out') }}</span>
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="py-4 text-center text-sm text-ink-500 dark:text-ink-400">{{ __('user.no_tracked_sessions') }}</p>
            @endforelse
        </div>
    </section>

    {{-- Section 5: Recognized Devices --}}
    <section id="recognized-devices" class="scroll-mt-24 rounded-3xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900 sm:p-7" aria-labelledby="recognized-devices-heading">
        <div class="flex items-center gap-3 border-b border-ink-100 pb-4 dark:border-ink-800">
            <div class="inline-flex size-10 items-center justify-center rounded-xl bg-leaf-100 text-leaf-700 dark:bg-leaf-950/80 dark:text-leaf-400">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5" aria-hidden="true">
                    <rect width="14" height="20" x="5" y="2" rx="2" ry="2"/>
                    <line x1="12" y1="18" x2="12.01" y2="18"/>
                </svg>
            </div>
            <div>
                <h2 id="recognized-devices-heading" class="text-lg font-black text-ink-950 dark:text-white">{{ __('user.devices') }}</h2>
                <p class="text-xs text-ink-500 dark:text-ink-400">{{ __('user.devices_explanation') }}</p>
            </div>
        </div>

        <div class="mt-6 space-y-3">
            @forelse($devices as $device)
                <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-ink-100 bg-ink-50/50 p-4 transition hover:border-ink-200 dark:border-ink-800 dark:bg-ink-950/50 dark:hover:border-ink-700">
                    <div class="flex items-center gap-3.5">
                        <div class="inline-flex size-10 shrink-0 items-center justify-center rounded-xl bg-white text-ink-700 shadow-2xs dark:bg-ink-900 dark:text-ink-200">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5" aria-hidden="true">
                                <rect width="18" height="12" x="3" y="4" rx="2"/>
                                <line x1="2" y1="20" x2="22" y2="20"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm font-bold text-ink-950 dark:text-white">
                                    {{ $device->device_name ?: $device->browser_summary.' on '.$device->platform_summary }}
                                </p>
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-bold {{ $device->is_recognized ? 'bg-leaf-100 text-leaf-700 dark:bg-leaf-950 dark:text-leaf-300' : 'bg-ink-200 text-ink-700 dark:bg-ink-800 dark:text-ink-300' }}">
                                    <span>{{ $device->is_recognized ? __('user.recognized') : __('user.not_recognized') }}</span>
                                </span>
                            </div>
                            <p class="mt-0.5 text-xs text-ink-500 dark:text-ink-400">
                                {{ __('user.last_seen', ['time'=>optional($device->last_seen_at)->diffForHumans() ?? __('user.unknown')]) }}
                            </p>
                        </div>
                    </div>

                    @if($device->status_user_device==='ACT')
                        <div class="flex flex-wrap items-center gap-2">
                            <form method="post" action="{{ route('workspace.setting.device.update', $device) }}" class="flex items-center gap-2">
                                @csrf @method('put')
                                <input name="device_name" value="{{ $device->device_name }}" maxlength="80" 
                                       aria-label="{{ __('user.device_name') }}" placeholder="{{ __('user.device_name') }}" 
                                       class="w-36 rounded-xl border border-ink-200 bg-white px-3 py-1.5 text-xs text-ink-950 shadow-2xs focus:border-ember-500 focus:outline-none dark:border-ink-700 dark:bg-ink-900 dark:text-white">
                                <button class="rounded-xl bg-leaf-600 px-3 py-1.5 text-xs font-bold text-white shadow-2xs transition hover:bg-leaf-700 dark:bg-leaf-700 dark:hover:bg-leaf-600">
                                    {{ __('user.recognize') }}
                                </button>
                            </form>
                            <form method="post" action="{{ route('workspace.setting.device.distrust', $device) }}">
                                @csrf @method('delete')
                                <button class="rounded-xl border border-ink-200 bg-white px-3 py-1.5 text-xs font-bold text-ember-700 shadow-2xs transition hover:bg-ember-50 dark:border-ink-700 dark:bg-ink-900 dark:text-ember-300 dark:hover:bg-ember-950">
                                    {{ __('user.distrust') }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <p class="py-4 text-center text-sm text-ink-500 dark:text-ink-400">{{ __('user.no_devices') }}</p>
            @endforelse
        </div>
    </section>

    {{-- Section 6: Multi-Factor Authentication --}}
    <section id="mfa-info" class="scroll-mt-24 rounded-3xl border border-ink-100 bg-gradient-to-br from-ink-50/80 via-white to-amber-50/30 p-6 shadow-sm dark:border-ink-800 dark:from-ink-950/80 dark:via-ink-950 dark:to-ember-950/20 sm:p-7" aria-labelledby="mfa-info-heading">
        <div class="flex items-start gap-4">
            <div class="inline-flex size-12 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-amber-800 shadow-2xs dark:bg-amber-950 dark:text-amber-300">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-6" aria-hidden="true">
                    <path d="m15.5 7.5 2.3 2.3a1 1 0 0 0 1.4 0l2.1-2.1a1 1 0 0 0 0-1.4L19 4.1a1 1 0 0 0-1.4 0l-2.1 2.1a1 1 0 0 0 0 1.3z"/>
                    <path d="m15.5 7.5-4 4"/>
                    <circle cx="7.5" cy="16.5" r="4.5"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <h2 id="mfa-info-heading" class="text-lg font-black text-ink-950 dark:text-white">{{ __('user.mfa_heading') }}</h2>
                    <span class="inline-flex items-center rounded-full bg-ink-200/80 px-2.5 py-0.5 text-xs font-bold text-ink-700 dark:bg-ink-800 dark:text-ink-200">
                        {{ __('user.not_enabled_yet') }}
                    </span>
                </div>
                <p class="mt-2 text-sm leading-relaxed text-ink-600 dark:text-ink-300">
                    {{ __('user.mfa_explanation') }}
                </p>
                <p class="mt-1.5 text-sm leading-relaxed text-ink-600 dark:text-ink-300">
                    {{ __('user.recovery_explanation') }}
                </p>
            </div>
        </div>
    </section>
</div>
@endsection
