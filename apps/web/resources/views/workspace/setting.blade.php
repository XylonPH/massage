@extends('layouts.workspace', ['navActive' => 'settings'])

@section('title', __('workspace.settings_title'))
@section('page-title', __('workspace.settings_title'))
@section('page-context', __('workspace.settings_intro'))

@section('content')
<div class="mx-auto max-w-5xl">
    <div class="max-w-2xl">
        @if (session('status'))
            <p class="mt-6 rounded-xl border border-leaf-200 bg-leaf-50 px-4 py-3 text-sm font-semibold text-leaf-800 dark:border-leaf-800 dark:bg-leaf-950 dark:text-leaf-300" role="status">{{ session('status') }}</p>
        @endif

        <section aria-labelledby="settings-account" class="mt-7 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
            <h2 id="settings-account" class="font-black text-ink-950 dark:text-ink-50">{{ __('workspace.settings_account_heading') }}</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div class="flex flex-wrap items-baseline gap-x-3">
                    <dt class="w-32 shrink-0 font-bold text-ink-900 dark:text-ink-100">{{ __('workspace.settings_username') }}</dt>
                    <dd class="text-ink-700 dark:text-ink-200">{{ '@'.$user->username }}</dd>
                </div>
                <div class="flex flex-wrap items-baseline gap-x-3">
                    <dt class="w-32 shrink-0 font-bold text-ink-900 dark:text-ink-100">{{ __('workspace.settings_email') }}</dt>
                    <dd class="text-ink-700 dark:text-ink-200">
                        {{ $user->email }}
                        @if ($user->hasVerifiedEmail())
                            <span class="ml-1.5 rounded-full bg-leaf-50 px-2 py-0.5 text-xs font-bold text-leaf-700 dark:bg-leaf-950 dark:text-leaf-300">{{ __('workspace.settings_email_verified') }}</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </section>

        <form method="post" action="{{ route('workspace.setting.update') }}" class="mt-6 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
            @csrf
            @method('put')
            <h2 class="font-black text-ink-950 dark:text-ink-50">{{ __('workspace.settings_marketing_heading') }}</h2>
            <label class="mt-4 flex cursor-pointer items-start gap-2.5 text-sm text-ink-700 dark:text-ink-200">
                <input type="checkbox" name="marketing_opt_in" value="1" @checked($user->is_marketing_email_opt_in) class="mt-0.5 size-4 shrink-0 rounded accent-ember-500">
                <span>{{ __('workspace.settings_marketing_label') }}</span>
            </label>
            <p class="mt-2 text-xs text-ink-400 dark:text-ink-300">{{ __('workspace.settings_marketing_hint') }}</p>
            <button type="submit" class="mt-5 rounded-xl bg-ember-500 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">
                {{ __('workspace.save') }}
            </button>
        </form>
    </div>
</div>
@endsection
