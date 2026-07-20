@extends('layouts.app')

@section('title', __('workspace.contribution_practitioner_title'))

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[16rem_minmax(0,1fr)]">
        <aside class="min-w-0"><x-workspace-nav active="contributions" /></aside>

        <main class="min-w-0">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-ember-600">{{ __('workspace.contribution_title') }}</p>
            <h1 class="mt-2 text-4xl font-black text-ink-950">{{ __('workspace.contribution_practitioner_title') }}</h1>
            <p class="mt-2 max-w-2xl text-ink-600">{{ __('workspace.contribution_practitioner_intro') }}</p>

            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800" role="alert">
                    <p class="font-bold">{{ __('workspace.contribution_fix_errors') }}</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('workspace.contribution.practitioner.store') }}" class="mt-8 max-w-3xl space-y-6 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm">
                @csrf
                <div>
                    <label for="practitioner_name" class="block text-sm font-bold text-ink-900">{{ __('workspace.contribution_practitioner_name_label') }}</label>
                    <input id="practitioner_name" name="practitioner_name" value="{{ old('practitioner_name') }}" required maxlength="160" class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-ink-950 focus:border-ember-500 focus:ring-ember-500">
                </div>
                <div>
                    <label for="short_description" class="block text-sm font-bold text-ink-900">{{ __('workspace.contribution_practitioner_description_label') }}</label>
                    <textarea id="short_description" name="short_description" rows="3" maxlength="255" class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-ink-950 focus:border-ember-500 focus:ring-ember-500">{{ old('short_description') }}</textarea>
                </div>
                <div>
                    <label for="type_practitioner_relationship" class="block text-sm font-bold text-ink-900">{{ __('workspace.contribution_relationship_label') }}</label>
                    <select id="type_practitioner_relationship" name="type_practitioner_relationship" required class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-ink-950 focus:border-ember-500 focus:ring-ember-500">
                        @foreach ($relationshipTypes as $relationshipType)
                            <option value="{{ $relationshipType }}" @selected(old('type_practitioner_relationship', 'NON') === $relationshipType)>{{ __('workspace.practitioner_relationship_'.$relationshipType) }}</option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-sm text-ink-500">{{ __('workspace.contribution_practitioner_relationship_hint') }}</p>
                </div>
                <div>
                    <label for="relationship_note" class="block text-sm font-bold text-ink-900">{{ __('workspace.contribution_relationship_note_label') }}</label>
                    <textarea id="relationship_note" name="relationship_note" rows="3" maxlength="1000" class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-ink-950 focus:border-ember-500 focus:ring-ember-500">{{ old('relationship_note') }}</textarea>
                </div>
                <div class="rounded-xl border border-ink-200 bg-ink-50 p-4">
                    <label class="flex items-start gap-3">
                        <input type="hidden" name="is_workspace_access_requested" value="0">
                        <input type="checkbox" name="is_workspace_access_requested" value="1" @checked(old('is_workspace_access_requested')) class="mt-1 rounded border-ink-300 text-ember-600 focus:ring-ember-500">
                        <span><span class="block font-bold text-ink-900">{{ __('workspace.contribution_practitioner_access_label') }}</span><span class="mt-1 block text-sm text-ink-600">{{ __('workspace.contribution_access_hint') }}</span></span>
                    </label>
                    @error('is_workspace_access_requested')<p class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
                </div>
                <div class="flex flex-wrap gap-3">
                    <button class="rounded-xl bg-ember-500 px-6 py-3 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">{{ __('workspace.contribution_submit') }}</button>
                    <a href="{{ route('workspace.contribution.index') }}" class="rounded-xl border border-ink-200 px-6 py-3 text-sm font-bold text-ink-700 hover:bg-ink-50">{{ __('workspace.contribution_cancel') }}</a>
                </div>
            </form>
        </main>
    </div>
</div>
@endsection
