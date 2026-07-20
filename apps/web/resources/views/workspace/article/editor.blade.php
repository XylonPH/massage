@extends('layouts.app')

@section('title', $article ? __('article.editor_edit_title') : __('article.editor_new_title'))

@section('content')
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[16rem_minmax(0,1fr)]">
        <aside class="min-w-0"><x-workspace-nav active="articles" /></aside>

        <main class="min-w-0">
            <div class="flex flex-col justify-between gap-3 border-b border-ink-100 pb-5 sm:flex-row sm:items-start">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-ember-600">{{ __('article.workspace_title') }}</p>
                    <h1 class="mt-1 text-3xl font-black tracking-tight text-ink-950">{{ $article ? __('article.editor_edit_title') : __('article.editor_new_title') }}</h1>
                    <p class="mt-1 max-w-3xl text-sm text-ink-600">{{ __('article.editor_intro') }}</p>
                </div>
                @if ($article)
                    <a href="{{ route('workspace.article.revisions', $article) }}" class="inline-flex shrink-0 items-center justify-center rounded-lg border border-ink-200 bg-white px-4 py-2 text-sm font-bold text-ink-700 hover:bg-ink-50">{{ __('article.revisions') }}</a>
                @endif
            </div>

            @if (session('status'))
                <div class="mt-5 rounded-xl border border-leaf-200 bg-leaf-50 p-4 font-semibold text-leaf-800" role="status">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="mt-5 rounded-xl border border-ember-200 bg-ember-50 p-4 text-ember-900" role="alert">
                    <p class="font-bold">{{ __('article.fix_errors') }}</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="post"
                  action="{{ $article ? route('workspace.article.update', $article) : route('workspace.article.store') }}"
                  data-article-form
                  data-link-prompt="{{ __('article.link_prompt') }}"
                  data-unsaved-label="{{ __('article.unsaved_changes') }}"
                  data-saved-label="{{ __('article.draft_state') }}"
                  class="mt-5">
                @csrf
                @if ($article) @method('put') @endif

                <div class="grid items-start gap-5 xl:grid-cols-[minmax(0,1fr)_20rem]">
                    <div class="min-w-0 space-y-5">
                        <section class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm">
                            <div class="grid gap-4 md:grid-cols-[minmax(0,1fr)_minmax(16rem,0.55fr)]">
                                <div>
                                    <label for="article_title" class="mb-1.5 block text-sm font-bold text-ink-900">{{ __('article.title_label') }}</label>
                                    <input id="article_title" name="article_title" required maxlength="75"
                                           value="{{ old('article_title', $article?->localized('article_title')) }}"
                                           data-article-title
                                           class="w-full rounded-xl border border-ink-200 px-4 py-2.5 text-lg font-bold focus:border-ember-400 focus:outline-none focus:ring-4 focus:ring-ember-100">
                                    <div class="mt-1 flex justify-between gap-3 text-xs text-ink-400">
                                        <span>{{ __('article.title_hint') }}</span>
                                        <span data-title-count>0 / 75</span>
                                    </div>
                                </div>
                                <div>
                                    <label for="article_slug" class="mb-1.5 block text-sm font-bold text-ink-900">{{ __('article.slug_label') }}</label>
                                    <div class="flex rounded-xl border border-ink-200 bg-ink-50 focus-within:border-ember-400 focus-within:ring-4 focus-within:ring-ember-100">
                                        <span class="flex items-center border-r border-ink-200 px-3 text-xs font-semibold text-ink-400">/article/</span>
                                        <input id="article_slug" name="article_slug" maxlength="100" pattern="[a-z0-9]+(?:-[a-z0-9]+)*"
                                               value="{{ old('article_slug', $article?->localized('article_slug')) }}"
                                               data-article-slug data-slug-auto="{{ $article || old('article_slug') ? 'false' : 'true' }}"
                                               class="min-w-0 flex-1 rounded-r-xl border-0 bg-white px-3 py-2.5 text-sm focus:outline-none">
                                    </div>
                                    <p class="mt-1 text-xs text-ink-400">{{ __('article.slug_hint') }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <div class="mb-1.5 flex items-center justify-between gap-3">
                                        <label for="short_description" class="text-sm font-bold text-ink-900">{{ __('article.description_label') }}</label>
                                        <span class="text-xs font-semibold text-ink-400" data-description-count>0 / 255</span>
                                    </div>
                                    <textarea id="short_description" name="short_description" required maxlength="255" rows="2" data-article-description class="w-full resize-y rounded-xl border border-ink-200 px-4 py-2.5 text-sm leading-6 focus:border-ember-400 focus:outline-none focus:ring-4 focus:ring-ember-100">{{ old('short_description', $article?->localized('short_description')) }}</textarea>
                                    <p class="mt-1 text-xs text-ink-400">{{ __('article.description_hint') }}</p>
                                </div>
                            </div>
                        </section>

                        <section class="overflow-hidden rounded-2xl border border-ink-200 bg-white shadow-sm" aria-labelledby="article-body-label">
                            <div class="flex flex-col gap-3 border-b border-ink-200 bg-ink-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h2 id="article-body-label" class="font-black text-ink-950">{{ __('article.body_label') }}</h2>
                                    <p id="article-body-hint" class="text-xs text-ink-500">{{ __('article.body_hint') }}</p>
                                </div>
                                <div class="inline-flex self-start rounded-lg border border-ink-200 bg-white p-1" role="tablist" aria-label="{{ __('article.editor_mode') }}">
                                    <button type="button" data-editor-mode="visual" role="tab" aria-selected="true" class="rounded-md bg-ink-900 px-3 py-1.5 text-xs font-bold text-white">{{ __('article.visual_mode') }}</button>
                                    <button type="button" data-editor-mode="html" role="tab" aria-selected="false" class="rounded-md px-3 py-1.5 text-xs font-bold text-ink-600 hover:bg-ink-50">{{ __('article.html_mode') }}</button>
                                </div>
                            </div>

                            <div data-editor-visual>
                                <div class="flex flex-wrap items-center gap-1 border-b border-ink-200 bg-white p-2" role="toolbar" aria-label="{{ __('article.formatting_toolbar') }}">
                                    @foreach ([
                                        ['paragraph', __('article.toolbar_paragraph')],
                                        ['heading2', __('article.toolbar_heading_2')],
                                        ['heading3', __('article.toolbar_heading_3')],
                                        ['heading4', __('article.toolbar_heading_4')],
                                    ] as [$action, $label])
                                        <button type="button" data-editor-action="{{ $action }}" class="mn-editor-button">{{ $label }}</button>
                                    @endforeach
                                    <span class="mx-1 h-6 w-px bg-ink-200" aria-hidden="true"></span>
                                    @foreach ([
                                        ['bold', __('article.toolbar_bold')],
                                        ['italic', __('article.toolbar_italic')],
                                        ['underline', __('article.toolbar_underline')],
                                        ['strike', __('article.toolbar_strike')],
                                    ] as [$action, $label])
                                        <button type="button" data-editor-action="{{ $action }}" class="mn-editor-button">{{ $label }}</button>
                                    @endforeach
                                    <span class="mx-1 h-6 w-px bg-ink-200" aria-hidden="true"></span>
                                    <button type="button" data-editor-action="bulletList" class="mn-editor-button">{{ __('article.toolbar_bullets') }}</button>
                                    <button type="button" data-editor-action="orderedList" class="mn-editor-button">{{ __('article.toolbar_numbered') }}</button>
                                    <button type="button" data-editor-action="blockquote" class="mn-editor-button">{{ __('article.toolbar_quote') }}</button>
                                    <button type="button" data-editor-action="horizontalRule" class="mn-editor-button">{{ __('article.toolbar_rule') }}</button>
                                    <span class="mx-1 h-6 w-px bg-ink-200" aria-hidden="true"></span>
                                    <button type="button" data-editor-action="alignLeft" class="mn-editor-button" aria-label="{{ __('article.toolbar_align_left') }}">{{ __('article.toolbar_align_left_short') }}</button>
                                    <button type="button" data-editor-action="alignCenter" class="mn-editor-button" aria-label="{{ __('article.toolbar_align_center') }}">{{ __('article.toolbar_align_center_short') }}</button>
                                    <button type="button" data-editor-action="alignRight" class="mn-editor-button" aria-label="{{ __('article.toolbar_align_right') }}">{{ __('article.toolbar_align_right_short') }}</button>
                                    <span class="mx-1 h-6 w-px bg-ink-200" aria-hidden="true"></span>
                                    <button type="button" data-editor-action="link" class="mn-editor-button">{{ __('article.toolbar_link') }}</button>
                                    <button type="button" data-editor-action="unlink" class="mn-editor-button">{{ __('article.toolbar_unlink') }}</button>
                                    <button type="button" data-editor-action="clear" class="mn-editor-button">{{ __('article.toolbar_clear') }}</button>
                                    <span class="mx-1 h-6 w-px bg-ink-200" aria-hidden="true"></span>
                                    <button type="button" data-editor-action="undo" class="mn-editor-button">{{ __('article.toolbar_undo') }}</button>
                                    <button type="button" data-editor-action="redo" class="mn-editor-button">{{ __('article.toolbar_redo') }}</button>
                                </div>
                                <div data-article-editor
                                     data-read-only="{{ $article?->status_publication === 'P' ? 'true' : 'false' }}"
                                     data-placeholder="{{ __('article.editor_placeholder') }}"
                                     data-aria-label="{{ __('article.body_label') }}"
                                     aria-describedby="article-body-hint"
                                     class="mn-rich-editor mn-article-body"></div>
                            </div>

                            <div data-editor-html hidden>
                                <label for="article_html_source" class="sr-only">{{ __('article.html_source_label') }}</label>
                                <textarea id="article_html_source" data-article-html spellcheck="false" rows="24" class="min-h-[34rem] w-full resize-y border-0 bg-ink-950 p-5 font-mono text-sm leading-6 text-ink-50 outline-none" @readonly($article?->status_publication === 'P')></textarea>
                                <p class="border-t border-ink-800 bg-ink-900 px-4 py-2 text-xs text-ink-300">{{ __('article.html_mode_hint') }}</p>
                            </div>

                            <textarea name="article_body" data-article-body hidden>{{ old('article_body', $body?->article_body ?? '<p></p>') }}</textarea>
                            <div class="flex flex-wrap items-center justify-between gap-2 border-t border-ink-200 bg-ink-50 px-4 py-2 text-xs text-ink-500">
                                <div class="flex gap-4">
                                    <span>{{ __('article.word_count') }}: <strong data-editor-word-count class="text-ink-800">0</strong></span>
                                    <span>{{ __('article.reading_estimate') }}: <strong data-editor-reading-time class="text-ink-800">0 min</strong></span>
                                </div>
                                <span data-editor-save-state class="font-semibold text-ink-500">{{ __('article.draft_state') }}</span>
                            </div>
                            <noscript><p class="p-4 font-semibold text-ember-700">{{ __('article.javascript_required') }}</p></noscript>
                        </section>
                    </div>

                    <aside class="min-w-0 space-y-4 xl:sticky xl:top-20">
                        <section class="rounded-2xl border border-ink-100 bg-white p-4 shadow-sm">
                            <h2 class="font-black text-ink-950">{{ __('article.classification_title') }}</h2>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="type_article_category" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500">{{ __('article.category_label') }}</label>
                                    <select id="type_article_category" name="type_article_category" required class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm">
                                        @foreach ($categories as $category)<option value="{{ $category->value }}" @selected(old('type_article_category', $article?->type_article_category ?? 'FTM') === $category->value)>{{ $category->label() }}</option>@endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="target_audience" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500">{{ __('article.audience_label') }}</label>
                                    <select id="target_audience" name="target_audience" required class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm">
                                        @foreach ($audiences as $audience)<option value="{{ $audience->value }}" @selected(old('target_audience', $article?->target_audience ?? 'G') === $audience->value)>{{ $audience->label() }}</option>@endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="level_nsfw" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500">{{ __('article.nsfw_label') }}</label>
                                    <select id="level_nsfw" name="level_nsfw" required class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm">
                                        @foreach (['N' => __('article.none'), 'M' => __('article.mild'), 'S' => __('article.sensitive'), 'E' => __('article.explicit')] as $code => $label)<option value="{{ $code }}" @selected(old('level_nsfw', $article?->level_nsfw ?? 'N') === $code)>{{ $label }}</option>@endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="tags" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500">{{ __('article.tags_label') }}</label>
                                    <input id="tags" name="tags" maxlength="500" value="{{ old('tags', $tags) }}" class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm" aria-describedby="tags-hint">
                                    <p id="tags-hint" class="mt-1 text-xs text-ink-400">{{ __('article.tags_hint') }}</p>
                                </div>
                            </div>
                        </section>

                        <section class="rounded-2xl border border-ink-100 bg-white p-4 shadow-sm">
                            <h2 class="font-black text-ink-950">{{ __('article.publication_title') }}</h2>
                            <div class="mt-4 space-y-4">
                                <label class="flex items-start gap-3">
                                    <input type="hidden" name="is_anonymous" value="0">
                                    <input type="checkbox" name="is_anonymous" value="1" @checked(old('is_anonymous', $article?->is_anonymous ?? false)) class="mt-1 rounded border-ink-300 text-ember-600 focus:ring-ember-500">
                                    <span><span class="block text-sm font-bold text-ink-900">{{ __('article.anonymous_label') }}</span><span class="mt-0.5 block text-xs leading-5 text-ink-500">{{ __('article.anonymous_hint') }}</span></span>
                                </label>

                                @if ($canSchedule)
                                    <div>
                                        <label for="scheduled_publish_at" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500">{{ __('article.schedule_label') }}</label>
                                        <input id="scheduled_publish_at" name="scheduled_publish_at" type="datetime-local"
                                               value="{{ old('scheduled_publish_at', $article?->scheduled_publish_at?->format('Y-m-d\TH:i')) }}"
                                               class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm">
                                        <p class="mt-1 text-xs leading-5 text-ink-400">{{ __('article.schedule_hint', ['timezone' => config('app.timezone')]) }}</p>
                                    </div>
                                @endif

                                @foreach (['is_commentable' => __('article.comments_label'), 'is_shareable' => __('article.sharing_label')] as $name => $label)
                                    <label class="flex items-center gap-2 text-sm font-semibold text-ink-700"><input type="hidden" name="{{ $name }}" value="0"><input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $article?->{$name} ?? true)) class="rounded border-ink-300 text-ember-600">{{ $label }}</label>
                                @endforeach
                            </div>
                        </section>

                        <section class="rounded-2xl border border-ink-100 bg-white p-4 shadow-sm">
                            <h2 class="font-black text-ink-950">{{ __('article.editorial_details_title') }}</h2>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="source_references" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500">{{ __('article.source_label') }}</label>
                                    <textarea id="source_references" name="source_references" rows="5" maxlength="15000" class="w-full rounded-lg border border-ink-200 px-3 py-2 font-mono text-xs leading-5" aria-describedby="source-hint">{{ old('source_references', $sources) }}</textarea>
                                    <p id="source-hint" class="mt-1 text-xs leading-5 text-ink-400">{{ __('article.source_hint') }}</p>
                                </div>
                                <div>
                                    <label for="revision_note" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500">{{ __('article.revision_note_label') }}</label>
                                    <textarea id="revision_note" name="revision_note" rows="2" maxlength="1000" class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm">{{ old('revision_note') }}</textarea>
                                </div>
                            </div>
                        </section>

                        <section class="rounded-2xl border border-ink-200 bg-ink-50 p-4">
                            <h2 class="text-sm font-black text-ink-950">{{ __('article.readiness_title') }}</h2>
                            <ul class="mt-3 space-y-2 text-xs text-ink-600">
                                <li data-readiness="title">{{ __('article.readiness_title_item') }}</li>
                                <li data-readiness="description">{{ __('article.readiness_description_item') }}</li>
                                <li data-readiness="body">{{ __('article.readiness_body_item') }}</li>
                                <li data-readiness="sources">{{ __('article.readiness_sources_item') }}</li>
                            </ul>
                        </section>

                        <div class="flex flex-col gap-2">
                            @if (! $article || $article->status_publication !== 'P')
                                <button class="rounded-xl bg-ink-950 px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-ink-800">{{ __('article.save_draft') }}</button>
                            @endif
                            @if ($article && $article->status_publication !== 'P')
                                <button type="submit" form="submit-article-form" class="rounded-xl border border-ember-300 bg-ember-50 px-5 py-3 text-sm font-bold text-ember-800 hover:bg-ember-100">{{ __('article.submit_review') }}</button>
                            @endif
                            <a href="{{ route('workspace.article.index') }}" class="text-center text-sm font-bold text-ink-500 hover:text-ink-800">{{ __('article.back_to_articles') }}</a>
                        </div>
                    </aside>
                </div>
            </form>

            @if ($article && $article->status_publication !== 'P')
                <form id="submit-article-form" method="post" action="{{ route('workspace.article.submit', $article) }}">@csrf</form>
            @endif
        </main>
    </div>
</div>
@endsection
