@extends('layouts.workspace', ['navActive' => 'contributions'])

@section('title', __('workspace.contribution_title'))
@section('page-title', __('workspace.contribution_title'))
@section('page-context', __('workspace.contribution_intro'))

@section('page-actions')
<a href="{{ route('workspace.contribution.establishment.create') }}" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-ember-500 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">
    {{ __('workspace.contribution_add_establishment') }}
</a>
<a href="{{ route('workspace.contribution.practitioner.create') }}" class="inline-flex shrink-0 items-center justify-center rounded-xl border border-ember-300 bg-white px-5 py-2.5 text-sm font-bold text-ember-700 transition hover:bg-ember-50 dark:border-ember-800 dark:bg-ink-900 dark:text-ember-300 dark:hover:bg-ember-950">
    {{ __('workspace.contribution_add_practitioner') }}
</a>
@endsection

@section('content')
<div class="mx-auto max-w-5xl">
    <p class="text-sm font-bold uppercase tracking-[0.18em] text-ember-600 dark:text-ember-400">{{ __('workspace.nav_activity') }}</p>

    @if (session('status'))
        <div class="mt-6 rounded-xl border border-leaf-200 bg-leaf-50 p-4 font-semibold text-leaf-800 dark:border-leaf-800 dark:bg-leaf-950 dark:text-leaf-300" role="status">{{ session('status') }}</div>
    @endif

    <div class="mt-8 space-y-4">
        @forelse ($contributions as $contribution)
            <article class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start">
                    <div>
                        @if ($contribution->target_collection === 'practitioner_main')
                            <p class="text-xs font-bold uppercase tracking-wider text-ember-700 dark:text-ember-400">{{ __('workspace.contribution_type_practitioner') }}</p>
                            <h2 class="mt-1 text-xl font-black text-ink-950 dark:text-ink-50">{{ data_get($contribution->proposed_data, 'practitioner_name.eng.text') }}</h2>
                            <p class="mt-3 text-xs text-ink-500 dark:text-ink-400">{{ __('workspace.contribution_relationship_summary', ['relationship' => __('workspace.practitioner_relationship_'.$contribution->type_practitioner_relationship)]) }}</p>
                        @else
                            <p class="text-xs font-bold uppercase tracking-wider text-ember-700 dark:text-ember-400">{{ __('workspace.contribution_type_establishment') }}</p>
                            <h2 class="mt-1 text-xl font-black text-ink-950 dark:text-ink-50">{{ data_get($contribution->proposed_data, 'establishment.display_name.eng', data_get($contribution->proposed_data, 'display_name.eng')) }}</h2>
                            <p class="mt-2 text-sm text-ink-600 dark:text-ink-300">{{ data_get($contribution->proposed_data, 'establishment.address_public', data_get($contribution->proposed_data, 'address_public')) }}</p>
                            <p class="mt-3 text-xs text-ink-500 dark:text-ink-400">{{ __('workspace.contribution_relationship_summary', ['relationship' => __('workspace.establishment_relationship_'.$contribution->type_establishment_relationship)]) }}</p>
                        @endif
                    </div>
                    <span class="shrink-0 rounded-full bg-ink-100 px-3 py-1 text-xs font-bold text-ink-700 dark:bg-ink-800 dark:text-ink-200">{{ __('workspace.contribution_status_'.$contribution->status_contribution) }}</span>
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-ink-200 bg-white p-10 text-center text-ink-500 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-400">{{ __('workspace.contribution_empty') }}</div>
        @endforelse
    </div>

    <div class="mt-8">{{ $contributions->links() }}</div>
</div>
@endsection
