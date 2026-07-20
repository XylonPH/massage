@extends('layouts.app')

@section('title', $heading)
@section('meta_description', __('article.all_articles'))

@section('content')
<div class="bg-gradient-to-br from-ink-950 via-ink-900 to-leaf-950 text-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-ember-300">{{ __('navigation.articles') }}</p>
        <h1 class="mt-3 max-w-4xl text-4xl font-black tracking-tight sm:text-5xl">{{ $heading }}</h1>
        <form action="{{ route('article.search') }}" method="get" role="search" class="mt-8 flex max-w-3xl flex-col gap-3 sm:flex-row">
            <label for="article-search" class="sr-only">{{ __('article.search_placeholder') }}</label>
            <input id="article-search" name="q" value="{{ $search }}" maxlength="100" placeholder="{{ __('article.search_placeholder') }}"
                   class="min-w-0 flex-1 rounded-xl border border-white/20 bg-white px-4 py-3 text-ink-950 shadow-sm focus:border-ember-400 focus:outline-none focus:ring-4 focus:ring-ember-400/20">
            <button class="rounded-xl bg-ember-500 px-6 py-3 font-bold text-white transition hover:bg-ember-600">{{ __('article.search_button') }}</button>
        </form>
    </div>
</div>

<div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[minmax(0,1fr)_18rem] lg:px-8">
    <section aria-label="{{ $heading }}">
        @if ($articles->isEmpty())
            <div class="rounded-2xl border border-ink-100 bg-white p-10 text-center shadow-sm">
                <p class="text-lg font-semibold text-ink-700">{{ __('article.no_articles') }}</p>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2">
                @foreach ($articles as $item)
                    <article class="flex h-full flex-col overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="h-2 bg-gradient-to-r from-ember-500 via-ink-600 to-leaf-500" aria-hidden="true"></div>
                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-wide text-ink-500">
                                @if ($item['category'])
                                    <a href="{{ route('article.category.show', $item['category']->slug()) }}" class="rounded-full bg-ink-50 px-2.5 py-1 text-ink-700 hover:bg-ink-100">{{ $item['category']->label() }}</a>
                                @endif
                                @if ($item['level_nsfw'] !== 'N')
                                    <span class="rounded-full bg-ember-50 px-2.5 py-1 text-ember-700">{{ __('article.sensitive_notice') }}</span>
                                @endif
                            </div>
                            <h2 class="mt-4 text-2xl font-black leading-tight text-ink-950">
                                <a href="{{ route('article.show', $item['slug']) }}" class="hover:text-ember-600">{{ $item['title'] }}</a>
                            </h2>
                            <p class="mt-3 flex-1 leading-7 text-charcoal-800">{{ $item['description'] }}</p>
                            <div class="mt-6 flex items-center justify-between gap-4 border-t border-ink-100 pt-4 text-sm text-ink-500">
                                <span>{{ $item['published_at']?->format('M j, Y') }}</span>
                                @php($minutes = intdiv($item['reading_duration_visual'], 60))
                                @php($seconds = $item['reading_duration_visual'] % 60)
                                <span>{{ __('article.reading_time', ['time' => $minutes ? __('article.minutes_seconds', ['minutes' => $minutes, 'seconds' => $seconds]) : __('article.seconds', ['seconds' => $seconds])]) }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-8">{{ $articles->links() }}</div>
        @endif
    </section>

    <aside class="space-y-6" aria-label="{{ __('article.browse_categories') }}">
        <div class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm">
            <h2 class="font-black text-ink-950">{{ __('article.browse_categories') }}</h2>
            <ul class="mt-4 space-y-2 text-sm">
                @foreach ($categories as $category)
                    <li><a class="text-ink-700 hover:text-ember-600" href="{{ route('article.category.show', $category->slug()) }}">{{ $category->label() }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm">
            <h2 class="font-black text-ink-950">{{ __('article.browse_audiences') }}</h2>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($audiences as $audience)
                    <a class="rounded-full bg-leaf-50 px-3 py-1.5 text-sm font-semibold text-leaf-800 hover:bg-leaf-100" href="{{ route('article.audience.show', $audience->slug()) }}">{{ $audience->label() }}</a>
                @endforeach
            </div>
        </div>
        @if ($popularTags->isNotEmpty())
            <div class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm">
                <h2 class="font-black text-ink-950">{{ __('article.browse_tags') }}</h2>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($popularTags as $tag)
                        <a class="rounded-full border border-ink-200 px-3 py-1.5 text-sm text-ink-700 hover:border-ember-300 hover:text-ember-700" href="{{ route('article.tag.show', $tag->localized('tag_slug')) }}">#{{ $tag->localized('tag_title') }}</a>
                    @endforeach
                </div>
            </div>
        @endif
    </aside>
</div>
@endsection
