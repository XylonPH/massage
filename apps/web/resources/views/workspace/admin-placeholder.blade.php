@extends('layouts.workspace', ['navActive' => 'admin-'.$areaKey])

@section('title', $areaTitle)
@section('page-title', $areaTitle)

@section('content')
<div class="mx-auto max-w-3xl">
    <section class="rounded-2xl border border-dashed border-ink-200 bg-ink-50/50 p-8 text-center dark:border-ink-700 dark:bg-charcoal-950">
        <h2 class="text-xl font-black text-ink-950 dark:text-ink-50">{{ $areaTitle }}</h2>
        <p class="mx-auto mt-2 max-w-md text-sm text-ink-600 dark:text-ink-300">{{ __('workspace.admin_placeholder_text') }}</p>
        <a href="{{ route('workspace.home') }}" class="mt-5 inline-block rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('workspace.nav_home') }}</a>
    </section>
</div>
@endsection
