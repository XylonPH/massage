@extends('layouts.app')

@section('title', __('workspace.contribution_title'))

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[16rem_minmax(0,1fr)]">
        <aside class="min-w-0"><x-workspace-nav active="contributions" /></aside>

        <main class="min-w-0">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-ember-600">{{ __('workspace.nav_activity') }}</p>
            <div class="mt-2 flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                <div>
                    <h1 class="text-4xl font-black text-ink-950">{{ __('workspace.contribution_title') }}</h1>
                    <p class="mt-2 max-w-2xl text-ink-600">{{ __('workspace.contribution_intro') }}</p>
                </div>
                <a href="{{ route('workspace.contribution.establishment.create') }}" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-ember-500 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-ember-500/25 transition hover:bg-ember-600">
                    {{ __('workspace.contribution_add_establishment') }}
                </a>
                <a href="{{ route('workspace.contribution.practitioner.create') }}" class="inline-flex shrink-0 items-center justify-center rounded-xl border border-ember-300 bg-white px-5 py-2.5 text-sm font-bold text-ember-700 transition hover:bg-ember-50">
                    {{ __('workspace.contribution_add_practitioner') }}
                </a>
            </div>

            @if (session('status'))
                <div class="mt-6 rounded-xl border border-leaf-200 bg-leaf-50 p-4 font-semibold text-leaf-800" role="status">{{ session('status') }}</div>
            @endif

            <div class="mt-8 space-y-4">
                @forelse ($contributions as $contribution)
                    <article class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm">
                        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start">
                            <div>
                                @if ($contribution->target_collection === 'practitioner_main')
                                    <p class="text-xs font-bold uppercase tracking-wider text-ember-700">{{ __('workspace.contribution_type_practitioner') }}</p>
                                    <h2 class="mt-1 text-xl font-black text-ink-950">{{ data_get($contribution->proposed_data, 'practitioner_name.eng.text') }}</h2>
                                    <p class="mt-3 text-xs text-ink-500">{{ __('workspace.contribution_relationship_summary', ['relationship' => __('workspace.practitioner_relationship_'.$contribution->type_practitioner_relationship)]) }}</p>
                                @else
                                    <p class="text-xs font-bold uppercase tracking-wider text-ember-700">{{ __('workspace.contribution_type_establishment') }}</p>
                                    <h2 class="mt-1 text-xl font-black text-ink-950">{{ data_get($contribution->proposed_data, 'display_name.eng') }}</h2>
                                    <p class="mt-2 text-sm text-ink-600">{{ data_get($contribution->proposed_data, 'address_public') }}</p>
                                    <p class="mt-3 text-xs text-ink-500">{{ __('workspace.contribution_relationship_summary', ['relationship' => __('workspace.establishment_relationship_'.$contribution->type_establishment_relationship)]) }}</p>
                                @endif
                            </div>
                            <span class="shrink-0 rounded-full bg-ink-100 px-3 py-1 text-xs font-bold text-ink-700">{{ __('workspace.contribution_status_'.$contribution->status_contribution) }}</span>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-ink-200 bg-white p-10 text-center text-ink-500">{{ __('workspace.contribution_empty') }}</div>
                @endforelse
            </div>

            <div class="mt-8">{{ $contributions->links() }}</div>
        </main>
    </div>
</div>
@endsection
