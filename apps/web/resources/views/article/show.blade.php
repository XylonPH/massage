@extends('layouts.app')

@section('title', $article->localized('article_title'))
@section('meta_description', $article->localized('short_description'))

@section('content')
<article>
    <header class="border-b border-ink-100 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-[minmax(0,1fr)_21rem] lg:gap-10">
                <div>
                    <div class="flex flex-wrap items-center gap-2 text-sm font-bold text-ink-600">
                        @if ($category)
                            <a href="{{ route('article.category.show', $category->slug()) }}" class="rounded-full bg-ink-50 px-3 py-1.5 hover:bg-ink-100">{{ $category->label() }}</a>
                        @endif
                        @if ($audience)
                            <a href="{{ route('article.audience.show', $audience->slug()) }}" class="rounded-full bg-leaf-50 px-3 py-1.5 text-leaf-800 hover:bg-leaf-100">{{ __('article.target_audience', ['audience' => $audience->label()]) }}</a>
                        @endif
                    </div>
                    <h1 class="mt-5 text-balance text-4xl font-black leading-tight tracking-tight text-ink-950 sm:text-5xl">{{ $article->localized('article_title') }}</h1>
                    <p class="mt-5 text-xl leading-8 text-charcoal-800">{{ $article->localized('short_description') }}</p>

                    @if ($article->level_nsfw !== 'N')
                        <div class="mt-6 rounded-xl border border-ember-200 bg-ember-50 p-4 text-ember-900" role="note">
                            <strong>{{ __('article.sensitive_notice') }}:</strong>
                            {{ ['M' => __('article.mild'), 'S' => __('article.sensitive'), 'E' => __('article.explicit')][$article->level_nsfw] ?? '' }}
                        </div>
                    @endif

                    <div class="mt-7 flex flex-wrap gap-x-6 gap-y-2 text-sm text-ink-500">
                        @if ($article->is_anonymous)
                            <span class="font-bold text-ink-800">{{ __('article.anonymous_byline') }}</span>
                        @elseif ($authors->isNotEmpty())
                            <span>{{ __('article.written_by') }}
                                @foreach ($authors as $author)
                                    <a class="font-bold text-ink-800 hover:text-ember-600" href="{{ route('article.author.show', $author->username) }}">{{ $author->username }}</a>@if (! $loop->last), @endif
                                @endforeach
                            </span>
                        @endif
                        <span>{{ __('article.published', ['date' => optional($article->published_at)->format('M j, Y')]) }}</span>
                        @php($visualMinutes = intdiv((int) $body->reading_duration_visual, 60))
                        @php($visualSeconds = (int) $body->reading_duration_visual % 60)
                        <span>{{ __('article.reading_time', ['time' => $visualMinutes ? __('article.minutes_seconds', ['minutes' => $visualMinutes, 'seconds' => $visualSeconds]) : __('article.seconds', ['seconds' => $visualSeconds])]) }}</span>
                    </div>
                    @if ($tags->isNotEmpty())
                        <div class="mt-5 flex flex-wrap gap-2">
                            @foreach ($tags as $tag)
                                <a class="rounded-full border border-ink-200 px-3 py-1 text-sm text-ink-700 hover:border-ember-300 hover:text-ember-700" href="{{ route('article.tag.show', $tag->localized('tag_slug')) }}">#{{ $tag->localized('tag_title') }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-[minmax(0,1fr)_21rem] lg:gap-10">
            <div>
                <div class="mn-article-body prose prose-lg max-w-none prose-headings:text-ink-950 prose-a:text-ember-700 prose-blockquote:border-ember-400 prose-blockquote:text-ink-800">
                    {!! $renderedBody !!}
                </div>

                @if (! empty($article->source_reference_list))
                    <section class="mt-12 border-t border-ink-100 pt-8" aria-labelledby="article-sources">
                        <h2 id="article-sources" class="text-2xl font-black text-ink-950">{{ __('article.sources') }}</h2>
                        <ol class="mt-4 list-decimal space-y-3 pl-5 text-sm leading-6 text-ink-700">
                            @foreach ($article->source_reference_list as $source)
                                <li>
                                    @if (! empty($source['source_url']))
                                        <a href="{{ $source['source_url'] }}" target="_blank" rel="noopener noreferrer" class="font-bold text-ember-700 underline">{{ $source['source_title'] }}</a>
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

                <section class="mt-12 rounded-2xl border border-ink-100 bg-ink-50 p-6" aria-label="{{ __('article.interactions_coming') }}">
                    <div class="flex flex-wrap gap-3" aria-disabled="true">
                        @foreach ([__('article.react_helpful'), __('article.react_not_helpful'), __('article.save'), __('article.share')] as $label)
                            <button type="button" disabled class="cursor-not-allowed rounded-lg border border-ink-200 bg-white px-4 py-2 text-sm font-bold text-ink-400">{{ $label }}</button>
                        @endforeach
                    </div>
                    <p class="mt-3 text-sm text-ink-600">{{ __('article.interactions_coming') }}</p>
                </section>

                @if ($relatedArticles->isNotEmpty())
                    <section class="mt-12" aria-labelledby="related-articles">
                        <h2 id="related-articles" class="text-2xl font-black text-ink-950">{{ __('article.related_articles') }}</h2>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3">
                            @foreach ($relatedArticles as $related)
                                <a href="{{ route('article.show', $related['slug']) }}" class="rounded-xl border border-ink-100 bg-white p-5 font-bold text-ink-900 shadow-sm hover:border-ember-200 hover:text-ember-700">{{ $related['title'] }}</a>
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
