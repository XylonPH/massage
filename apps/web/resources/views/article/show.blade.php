@extends('layouts.app')

@section('title', $article->localized('article_title'))
@section('meta_description', $article->localized('short_description'))

@section('content')
@php
    $formatDuration = static function (int $seconds): string {
        $minutes = intdiv($seconds, 60);
        $remainder = $seconds % 60;

        return $minutes
            ? __('article.minutes_seconds', ['minutes' => $minutes, 'seconds' => $remainder])
            : __('article.seconds', ['seconds' => $remainder]);
    };
@endphp
<article>
    <header class="border-b border-ink-100 bg-white dark:border-ink-800 dark:bg-ink-900">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <nav class="mb-7 flex items-center gap-2 text-sm font-semibold text-ink-500 dark:text-ink-400" aria-label="{{ __('article.back_to_article_index') }}">
                <a href="{{ route('article.index') }}" class="transition hover:text-ember-700 dark:hover:text-ember-400">{{ __('article.back_to_article_index') }}</a>
                <span aria-hidden="true">/</span>
                <span class="truncate text-ink-700 dark:text-ink-200">{{ $article->localized('article_title') }}</span>
            </nav>
            @if ($article->cover_media_image_id)
                <img src="{{ route('media.image.show', ['media_image' => $article->cover_media_image_id]) }}" alt="" class="mt-6 aspect-[21/9] w-full rounded-2xl object-cover">
            @endif
            <div class="lg:grid lg:grid-cols-[minmax(0,1fr)_21rem] lg:gap-10">
                <div>
                    <div class="flex flex-wrap items-center gap-2 text-sm font-bold text-ink-600 dark:text-ink-300">
                        @if ($category)
                            <a href="{{ route('article.category.show', $category->slug()) }}" class="inline-flex items-center gap-1.5 rounded-full bg-ink-50 px-3 py-1.5 hover:bg-ink-100 dark:bg-ink-800 dark:hover:bg-ink-700">
                                <x-article-category-icon :category="$category" class="size-4 shrink-0 text-ember-600 dark:text-ember-400" />
                                <span>{{ $category->label() }}</span>
                            </a>
                        @endif
                        @if ($audience)
                            <a href="{{ route('article.audience.show', $audience->slug()) }}" class="inline-flex items-center gap-1.5 rounded-full bg-leaf-50 px-3 py-1.5 text-leaf-800 hover:bg-leaf-100 dark:bg-leaf-950/60 dark:text-leaf-300 dark:hover:bg-leaf-950">
                                <x-article-audience-icon :audience="$audience" class="size-4 shrink-0 text-leaf-600 dark:text-leaf-400" />
                                <span>{{ __('article.target_audience', ['audience' => $audience->label()]) }}</span>
                            </a>
                        @endif
                    </div>
                    <h1 class="mt-5 text-balance text-4xl font-black leading-tight tracking-tight text-ink-950 dark:text-white sm:text-5xl">{{ $article->localized('article_title') }}</h1>
                    <p class="mt-5 text-xl leading-8 text-charcoal-800 dark:text-ink-200">{{ $article->localized('short_description') }}</p>

                    @if ($article->level_nsfw !== 'N')
                        <div class="mt-6 rounded-xl border border-ember-200 bg-ember-50 p-4 text-ember-900 dark:border-ember-800 dark:bg-ember-950/60 dark:text-ember-200" role="note">
                            <strong>{{ __('article.sensitive_notice') }}:</strong>
                            {{ ['M' => __('article.mild'), 'S' => __('article.sensitive'), 'E' => __('article.explicit')][$article->level_nsfw] ?? '' }}
                        </div>
                    @endif

                    <div class="mt-7 flex flex-wrap gap-x-6 gap-y-2 text-sm text-ink-500 dark:text-ink-400">
                        @if ($article->is_anonymous)
                            <span class="font-bold text-ink-800 dark:text-ink-200">{{ __('article.anonymous_byline') }}</span>
                        @elseif ($authors->isNotEmpty())
                            <span>{{ __('article.written_by') }}
                                @foreach ($authors as $author)
                                    @if ($author['username'])
                                        <a class="font-bold text-ink-800 hover:text-ember-600 dark:text-ink-200 dark:hover:text-ember-400" href="{{ route('article.author.show', $author['username']) }}">{{ $author['name'] }}</a>
                                    @else
                                        <strong class="text-ink-800 dark:text-ink-200">{{ $author['name'] }}</strong>
                                    @endif@if (! $loop->last), @endif
                                @endforeach
                            </span>
                        @endif
                        <span>{{ __('article.published', ['date' => optional($article->published_at)->format('M j, Y')]) }}</span>
                        <span>{{ __('article.reading_time', ['time' => $formatDuration((int) $body->reading_duration_visual)]) }}</span>
                    </div>
                    @if ($tags->isNotEmpty())
                        <div class="mt-5 flex flex-wrap gap-2">
                            @foreach ($tags as $tag)
                                <a class="rounded-full border border-ink-200 px-3 py-1 text-sm text-ink-700 hover:border-ember-300 hover:text-ember-700 dark:border-ink-700 dark:text-ink-300 dark:hover:border-ember-700 dark:hover:text-ember-400" href="{{ route('article.tag.show', $tag->localized('tag_slug')) }}">#{{ $tag->localized('tag_title') }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <aside class="mt-8 lg:mt-0" data-article-overview>
                    <div class="rounded-2xl border border-ink-100 bg-ink-50/70 p-5 dark:border-ink-700 dark:bg-ink-950/45">
                        <h2 class="font-black text-ink-950 dark:text-white">{{ __('article.article_overview') }}</h2>
                        <p class="mt-1 text-sm text-ink-500 dark:text-ink-400">{{ __('article.article_overview_hint') }}</p>
                        <dl class="mt-5 space-y-4 text-sm">
                            @if ($category)
                                <div class="flex items-start justify-between gap-4 border-b border-ink-100 pb-3 dark:border-ink-800">
                                    <dt class="text-ink-500 dark:text-ink-400">{{ __('article.category') }}</dt>
                                    <dd class="text-right font-bold text-ink-900 dark:text-ink-100">{{ $category->label() }}</dd>
                                </div>
                            @endif
                            @if ($audience)
                                <div class="flex items-start justify-between gap-4 border-b border-ink-100 pb-3 dark:border-ink-800">
                                    <dt class="text-ink-500 dark:text-ink-400">{{ __('article.audience') }}</dt>
                                    <dd class="text-right font-bold text-ink-900 dark:text-ink-100">{{ $audience->label() }}</dd>
                                </div>
                            @endif
                            <div class="flex items-start justify-between gap-4 border-b border-ink-100 pb-3 dark:border-ink-800">
                                <dt class="text-ink-500 dark:text-ink-400">{{ __('article.visual_reading') }}</dt>
                                <dd class="text-right font-bold text-ink-900 dark:text-ink-100">{{ $formatDuration((int) $body->reading_duration_visual) }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-4 border-b border-ink-100 pb-3 dark:border-ink-800">
                                <dt class="text-ink-500 dark:text-ink-400">{{ __('article.spoken_reading') }}</dt>
                                <dd class="text-right font-bold text-ink-900 dark:text-ink-100">{{ $formatDuration((int) $body->reading_duration_spoken) }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-ink-500 dark:text-ink-400">{{ __('article.publication_date') }}</dt>
                                <dd class="text-right font-bold text-ink-900 dark:text-ink-100">{{ optional($article->published_at)->format('M j, Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                </aside>
            </div>
        </div>
    </header>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-[minmax(0,1fr)_21rem] lg:gap-10">
            <div>
                <div class="mn-article-body prose prose-lg max-w-none prose-headings:text-ink-950 prose-a:text-ember-700 prose-blockquote:border-ember-400 prose-blockquote:text-ink-800 dark:prose-invert">
                    {!! $renderedBody !!}
                </div>

                @if (! empty($article->source_reference_list))
                    <section class="mt-12 border-t border-ink-100 pt-8 dark:border-ink-800" aria-labelledby="article-sources">
                        <h2 id="article-sources" class="text-2xl font-black text-ink-950 dark:text-white">{{ __('article.sources') }}</h2>
                        <ol class="mt-4 list-decimal space-y-3 pl-5 text-sm leading-6 text-ink-700 dark:text-ink-300">
                            @foreach ($article->source_reference_list as $source)
                                <li>
                                    @if (! empty($source['source_url']))
                                        <a href="{{ $source['source_url'] }}" target="_blank" rel="noopener noreferrer" class="font-bold text-ember-700 underline dark:text-ember-400">{{ $source['source_title'] }}</a>
                                    @else
                                        <strong>{{ $source['source_title'] }}</strong>
                                    @endif
                                    @if (! empty($source['source_organization'])) — {{ $source['source_organization'] }} @endif
                                    @if (! empty($source['publication_identifier'])) ({{ $source['publication_identifier'] }}) @endif
                                </li>
                            @endforeach
                        </ol>
                    </section>
                @endif

                <section class="mt-12 rounded-2xl border border-ink-100 bg-ink-50 p-6 dark:border-ink-800 dark:bg-ink-900" aria-label="{{ __('article.interactions_coming') }}">
                    <div class="flex flex-wrap gap-3" aria-disabled="true">
                        @foreach ([__('article.react_helpful'), __('article.react_not_helpful'), __('article.save'), __('article.share')] as $label)
                            <button type="button" disabled class="cursor-not-allowed rounded-lg border border-ink-200 bg-white px-4 py-2 text-sm font-bold text-ink-400 dark:border-ink-700 dark:bg-ink-800 dark:text-ink-500">{{ $label }}</button>
                        @endforeach
                    </div>
                    <p class="mt-3 text-sm text-ink-600 dark:text-ink-300">{{ __('article.interactions_coming') }}</p>
                </section>

                @if ($relatedArticles->isNotEmpty())
                    <section class="mt-12" aria-labelledby="related-articles">
                        <h2 id="related-articles" class="text-2xl font-black text-ink-950 dark:text-white">{{ __('article.related_articles') }}</h2>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3">
                            @foreach ($relatedArticles as $related)
                                <a href="{{ route('article.show', $related['slug']) }}" class="rounded-xl border border-ink-100 bg-white p-5 font-bold text-ink-900 shadow-sm hover:border-ember-200 hover:text-ember-700 dark:border-ink-800 dark:bg-ink-900 dark:text-ink-100 dark:hover:border-ember-800 dark:hover:text-ember-400">{{ $related['title'] }}</a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside class="mt-12 hidden lg:block lg:mt-0">
                <div class="sticky top-24">
                    <x-widgets.sidebar-container :content-length="str_word_count(strip_tags($renderedBody))" />
                </div>
            </aside>
        </div>
    </div>
</article>
@endsection
