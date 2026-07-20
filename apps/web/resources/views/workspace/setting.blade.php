@extends('layouts.app')

@section('title', __('workspace.settings_title'))

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[16rem_minmax(0,1fr)]">
        <aside class="min-w-0"><x-workspace-nav active="settings" /></aside>

        <div class="min-w-0 max-w-2xl">
            <h1 class="text-4xl font-black text-ink-950">{{ __('workspace.settings_title') }}</h1>
            <p class="mt-2 text-ink-600">{{ __('workspace.settings_intro') }}</p>

            @if (session('status'))
                <p class="mt-6 rounded-xl border border-leaf-200 bg-leaf-50 px-4 py-3 text-sm font-semibold text-leaf-800" role="status">{{ session('status') }}</p>
            @endif

            <section aria-labelledby="settings-account" class="mt-7 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm">
                <h2 id="settings-account" class="font-black text-ink-950">{{ __('workspace.settings_account_heading') }}</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex flex-wrap items-baseline gap-x-3">
                        <dt class="w-32 shrink-0 font-bold text-ink-900">{{ __('workspace.settings_username') }}</dt>
                        <dd class="text-ink-700">{{ '@'.$user->username }}</dd>
                    </div>
                    <div class="flex flex-wrap items-baseline gap-x-3">
                        <dt class="w-32 shrink-0 font-bold text-ink-900">{{ __('workspace.settings_email') }}</dt>
                        <dd class="text-ink-700">
                            {{ $user->email }}
                            @if ($user->hasVerifiedEmail())
                                <span class="ml-1.5 rounded-full bg-leaf-50 px-2 py-0.5 text-xs font-bold text-leaf-700">{{ __('workspace.settings_email_verified') }}</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </section>

            <form method="post" action="{{ route('workspace.setting.update') }}" class="mt-6 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm">
                @csrf
                @method('put')
                <h2 class="font-black text-ink-950">{{ __('workspace.settings_marketing_heading') }}</h2>
                <label class="mt-4 flex cursor-pointer items-start gap-2.5 text-sm text-ink-700">
                    <input type="checkbox" name="marketing_opt_in" value="1" @checked($user->is_marketing_email_opt_in) class="mt-0.5 size-4 shrink-0 rounded accent-ember-500">
                    <span>{{ __('workspace.settings_marketing_label') }}</span>
                </label>
                <p class="mt-2 text-xs text-ink-400">{{ __('workspace.settings_marketing_hint') }}</p>
                <button type="submit" class="mt-5 rounded-xl bg-ember-500 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">
                    {{ __('workspace.save') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
