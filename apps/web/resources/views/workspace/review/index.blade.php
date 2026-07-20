@extends('layouts.app')

@section('title', __('review.workspace_title'))

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[16rem_minmax(0,1fr)]">
        <aside class="min-w-0"><x-workspace-nav active="reviews" /></aside>
        <main class="min-w-0">
    <h1 class="text-3xl font-black text-ink-950">{{ __('review.workspace_title') }}</h1>
    <p class="mt-1 text-sm text-ink-600">{{ __('review.workspace_intro') }}</p>

    @if (session('status'))
        <div class="mt-6 rounded-xl border border-leaf-200 bg-leaf-50 p-4 font-semibold text-leaf-800" role="status">{{ session('status') }}</div>
    @endif

    <nav class="mt-7 flex flex-wrap gap-2" aria-label="{{ __('review.workspace_title') }}">
        @foreach (['all' => 'index', 'drafts' => 'draft', 'submitted' => 'submitted', 'published' => 'published'] as $label => $route)
            @php $active = $filter === ($label === 'drafts' ? 'draft' : $label); @endphp
            <a href="{{ route('workspace.review.'.$route) }}" class="rounded-lg px-4 py-2 text-sm font-bold {{ $active ? 'bg-ink-950 text-white' : 'border border-ink-200 bg-white text-ink-700 hover:bg-ink-50' }}">{{ __('review.'.$label) }}</a>
        @endforeach
    </nav>

    <div class="mt-7 space-y-4">
        @forelse ($reviews as $item)
            <article class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-ember-700">{{ $item['target']['name'] ?? __('review.target_unavailable') }}</p>
                        <h2 class="mt-1 text-xl font-black text-ink-950">{{ $item['title'] }}</h2>
                        <p class="mt-2 text-sm text-ink-600">{{ $item['short_description'] }}</p>
                    </div>
                    <span class="shrink-0 rounded-full bg-ink-100 px-3 py-1 text-xs font-bold text-ink-700">{{ __('review.status_'.$item['status_review']) }}</span>
                </div>
                <div class="mt-4 flex flex-wrap items-center gap-4 text-sm">
                    @if ($item['score'] !== null)<span class="font-black text-ember-700">{{ __('review.score_out_of_ten', ['score' => number_format($item['score'], 1)]) }}</span>@endif
                    <span class="text-ink-500">{{ optional($item['updated_at'])->format('M j, Y') }}</span>
                    @if ($item['status_review'] === 'NR')
                        <a href="{{ route('workspace.review.edit', $item['id']) }}" class="font-bold text-ember-700 hover:underline">{{ __('review.edit_title', ['target' => $item['target']['name'] ?? __('review.target_unavailable')]) }}</a>
                    @elseif ($item['status_publication'] === 'P')
                        <a href="{{ route('review.show', $item['slug']) }}" class="font-bold text-ember-700 hover:underline">{{ __('review.view_review') }}</a>
                    @endif
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-ink-200 bg-white p-10 text-center text-ink-500">{{ __('review.empty_workspace') }}</div>
        @endforelse
    </div>

    <div class="mt-8">{{ $reviews->links() }}</div>
        </main>
    </div>
</div>
@endsection
