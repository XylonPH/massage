@extends('layouts.workspace', ['navActive' => 'contributions'])

@section('title', __('workspace.contribution_establishment_title'))
@section('page-title', __('workspace.contribution_establishment_title'))
@section('page-context', __('workspace.contribution_establishment_intro'))

@section('content')
<div class="mx-auto max-w-5xl">
    <p class="text-sm font-bold uppercase tracking-[0.18em] text-ember-600 dark:text-ember-400">{{ __('workspace.contribution_title') }}</p>

    @if ($errors->any())
        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-100" role="alert">
            <p class="font-bold">{{ __('workspace.contribution_fix_errors') }}</p>
            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('workspace.contribution.establishment.store') }}" class="mt-8 max-w-3xl space-y-6 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
        @csrf

        <div>
            <label for="display_name" class="block text-sm font-bold text-ink-900 dark:text-ink-100">{{ __('workspace.contribution_name_label') }}</label>
            <input id="display_name" name="display_name" value="{{ old('display_name') }}" required maxlength="160" class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-ink-950 focus:border-ember-500 focus:ring-ember-500 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50">
            @error('display_name')<p class="mt-1 text-sm text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="address_public" class="block text-sm font-bold text-ink-900 dark:text-ink-100">{{ __('workspace.contribution_address_label') }}</label>
            <textarea id="address_public" name="address_public" rows="3" required maxlength="500" class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-ink-950 focus:border-ember-500 focus:ring-ember-500 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50">{{ old('address_public') }}</textarea>
            @error('address_public')<p class="mt-1 text-sm text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="type_establishment_relationship" class="block text-sm font-bold text-ink-900 dark:text-ink-100">{{ __('workspace.contribution_relationship_label') }}</label>
            <select id="type_establishment_relationship" name="type_establishment_relationship" required class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-ink-950 focus:border-ember-500 focus:ring-ember-500 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50">
                @foreach ($relationshipTypes as $relationshipType)
                    <option value="{{ $relationshipType }}" @selected(old('type_establishment_relationship', 'NON') === $relationshipType)>{{ __('workspace.establishment_relationship_'.$relationshipType) }}</option>
                @endforeach
            </select>
            <p class="mt-2 text-sm text-ink-500 dark:text-ink-400">{{ __('workspace.contribution_relationship_hint') }}</p>
        </div>

        <div>
            <label for="relationship_note" class="block text-sm font-bold text-ink-900 dark:text-ink-100">{{ __('workspace.contribution_relationship_note_label') }}</label>
            <textarea id="relationship_note" name="relationship_note" rows="3" maxlength="1000" class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-ink-950 focus:border-ember-500 focus:ring-ember-500 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50">{{ old('relationship_note') }}</textarea>
        </div>

        <div class="rounded-xl border border-ink-200 bg-ink-50 p-4 dark:border-ink-700 dark:bg-ink-800">
            <label class="flex items-start gap-3">
                <input type="hidden" name="is_workspace_access_requested" value="0">
                <input type="checkbox" name="is_workspace_access_requested" value="1" @checked(old('is_workspace_access_requested')) class="mt-1 rounded border-ink-300 text-ember-600 focus:ring-ember-500">
                <span>
                    <span class="block font-bold text-ink-900 dark:text-ink-100">{{ __('workspace.contribution_access_label') }}</span>
                    <span class="mt-1 block text-sm text-ink-600 dark:text-ink-300">{{ __('workspace.contribution_access_hint') }}</span>
                </span>
            </label>
            @error('is_workspace_access_requested')<p class="mt-2 text-sm text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
        </div>

        <div class="flex flex-wrap gap-3">
            <button class="rounded-xl bg-ember-500 px-6 py-3 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">{{ __('workspace.contribution_submit') }}</button>
            <a href="{{ route('workspace.contribution.index') }}" class="rounded-xl border border-ink-200 px-6 py-3 text-sm font-bold text-ink-700 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('workspace.contribution_cancel') }}</a>
        </div>
    </form>
</div>
@endsection
