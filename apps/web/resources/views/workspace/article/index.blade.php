@extends('layouts.app')

@section('title', __('article.workspace_title'))

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[16rem_minmax(0,1fr)]">
        <aside class="min-w-0"><x-workspace-nav active="articles" /></aside>

        <main class="min-w-0">
            @if (session('status'))
                <div class="mb-5 rounded-xl border border-leaf-200 bg-leaf-50 p-4 font-semibold text-leaf-800" role="status">{{ session('status') }}</div>
            @endif

            <div class="flex flex-col justify-between gap-4 border-b border-ink-100 pb-5 sm:flex-row sm:items-end">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-ember-600">{{ __('navigation.workspace') }}</p>
                    <h1 class="mt-1 text-3xl font-black tracking-tight text-ink-950">{{ __('article.workspace_title') }}</h1>
                    <p class="mt-1 text-sm text-ink-600">{{ __('article.workspace_intro') }}</p>
                </div>
                <a href="{{ route('workspace.article.create') }}" class="inline-flex items-center justify-center rounded-xl bg-ember-500 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-ember-600">{{ __('article.new_article') }}</a>
            </div>

            <nav class="mt-5 flex flex-wrap gap-2" aria-label="{{ __('article.status') }}">
                @foreach ([[null, __('article.all')], ['draft', __('article.drafts')], ['submitted', __('article.submitted')], ['published', __('article.published_tab')]] as [$key, $label])
                    @php($route = $key ? 'workspace.article.'.$key : 'workspace.article.index')
                    <a href="{{ route($route) }}" @if ($status === $key) aria-current="page" @endif
                       class="rounded-lg px-4 py-2 text-sm font-bold {{ $status === $key ? 'bg-ink-900 text-white' : 'bg-white text-ink-700 shadow-sm ring-1 ring-ink-100 hover:bg-ink-50' }}">{{ $label }}</a>
                @endforeach
            </nav>

            <div class="mt-5 overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm">
                @if ($articles->isEmpty())
                    <div class="p-10 text-center">
                        <p class="font-semibold text-ink-600">{{ __('article.no_workspace_articles') }}</p>
                        <a href="{{ route('workspace.article.create') }}" class="mt-4 inline-flex rounded-lg bg-ink-950 px-4 py-2 text-sm font-bold text-white hover:bg-ink-800">{{ __('article.new_article') }}</a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-ink-100">
                            <thead class="bg-ink-50 text-left text-xs font-bold uppercase tracking-wider text-ink-600">
                                <tr><th class="px-5 py-3">{{ __('article.title') }}</th><th class="px-5 py-3">{{ __('article.status') }}</th><th class="px-5 py-3">{{ __('article.last_updated') }}</th><th class="px-5 py-3">{{ __('article.actions') }}</th></tr>
                            </thead>
                            <tbody class="divide-y divide-ink-100">
                                @foreach ($articles as $item)
                                    <tr>
                                        <td class="px-5 py-4 font-bold text-ink-950">{{ $item->localized('article_title') }}</td>
                                        <td class="px-5 py-4 text-sm text-ink-600">{{ $status === 'submitted' ? __('article.submitted') : (['D' => __('article.drafts'), 'S' => __('article.scheduled'), 'P' => __('article.published_tab'), 'U' => __('article.unpublished')][$item->status_publication] ?? $item->status_publication) }}</td>
                                        <td class="px-5 py-4 text-sm text-ink-600">{{ $item->updated_at?->format('M j, Y g:i A') }}</td>
                                        <td class="px-5 py-4">
                                            <div class="flex gap-3 text-sm font-bold">
                                                <a class="text-ember-700 hover:underline" href="{{ route('workspace.article.edit', $item) }}">{{ __('article.edit') }}</a>
                                                <a class="text-ink-700 hover:underline" href="{{ route('workspace.article.revisions', $item) }}">{{ __('article.revisions') }}</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-ink-100 p-5">{{ $articles->links() }}</div>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection
