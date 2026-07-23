@extends('layouts.app')

@section('title', $article ? __('article.editor_edit_title') : __('article.editor_new_title'))

@section('content')
@php
    $sourceRows = old('source_reference_list', $sources);
    $sourceRows = is_array($sourceRows) && $sourceRows !== [] ? array_values($sourceRows) : [['source_title' => '', 'source_organization' => '', 'source_url' => '', 'publication_identifier' => '']];
    $creditRows = old('author_credit_list', $authorCredits);
    $creditRows = is_array($creditRows) ? array_values($creditRows) : [];
    $selectedOwnerIds = old('article_owner_user_id_list', $ownerIds);
    $userOptionById = collect($userOptions)->keyBy('id');
@endphp
<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[16rem_minmax(0,1fr)]">
        <aside class="min-w-0"><x-workspace-nav active="articles" /></aside>

        <main class="min-w-0">
            <div class="flex flex-col justify-between gap-3 border-b border-ink-100 pb-5 sm:flex-row sm:items-start dark:border-ink-800">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-ember-600">{{ __('article.workspace_title') }}</p>
                    <h1 class="mt-1 text-3xl font-black tracking-tight text-ink-950 dark:text-ink-50">{{ $article ? __('article.editor_edit_title') : __('article.editor_new_title') }}</h1>
                    <p class="mt-1 max-w-3xl text-sm text-ink-600 dark:text-ink-300">{{ __('article.editor_intro') }}</p>
                </div>
                @if ($article)
                    <a href="{{ route('workspace.article.revisions', $article) }}" class="inline-flex shrink-0 items-center justify-center rounded-lg border border-ink-200 bg-white px-4 py-2 text-sm font-bold text-ink-700 hover:bg-ink-50 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('article.revisions') }}</a>
                @endif
            </div>

            @if ($article?->status_review === 'N')
                <div class="mt-5 rounded-xl border border-ember-200 bg-ember-50 p-4 text-ember-900 dark:border-ember-800 dark:bg-ember-950 dark:text-ember-200" role="alert">
                    <h2 class="font-black">{{ __('article.needs_revision') }}</h2>
                    <p class="mt-1 text-sm">{{ __('article.needs_revision_editor_hint') }}</p>
                </div>
            @endif

            @if (session('status'))
                <div class="mt-5 rounded-xl border border-leaf-200 bg-leaf-50 p-4 font-semibold text-leaf-800 dark:border-leaf-800 dark:bg-leaf-950 dark:text-leaf-300" role="status">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="mt-5 rounded-xl border border-ember-200 bg-ember-50 p-4 text-ember-900 dark:border-ember-800 dark:bg-ember-950 dark:text-ember-300" role="alert">
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
                  data-minimum-submission-words="300"
                  data-search-minimum-label="{{ __('article.search_minimum_characters') }}"
                  class="mt-5">
                @csrf
                @if ($article) @method('put') @endif

                <div class="grid items-start gap-5 xl:grid-cols-[minmax(0,1fr)_20rem]">
                    <div class="min-w-0 space-y-5">
                        <section class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                            <div class="grid gap-4 md:grid-cols-[minmax(0,1fr)_minmax(16rem,0.55fr)]">
                                <div>
                                    <label for="article_title" class="mb-1.5 block text-sm font-bold text-ink-900 dark:text-ink-200">{{ __('article.title_label') }}</label>
                                    <input id="article_title" name="article_title" required maxlength="75"
                                           value="{{ old('article_title', $article?->localized('article_title')) }}"
                                           data-article-title
                                           class="w-full rounded-xl border border-ink-200 px-4 py-2.5 text-lg font-bold focus:border-ember-400 focus:outline-none focus:ring-4 focus:ring-ember-100 dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900">
                                    <div class="mt-1 flex justify-between gap-3 text-xs text-ink-400 dark:text-ink-500">
                                        <span>{{ __('article.title_hint') }}</span>
                                        <span data-title-count>0 / 75</span>
                                    </div>
                                </div>
                                <div>
                                    <label for="article_slug" class="mb-1.5 block text-sm font-bold text-ink-900 dark:text-ink-200">{{ __('article.slug_label') }}</label>
                                    <div class="flex rounded-xl border border-ink-200 bg-ink-50 focus-within:border-ember-400 focus-within:ring-4 focus-within:ring-ember-100 dark:border-ink-700 dark:bg-ink-950 dark:focus-within:border-ember-500 dark:focus-within:ring-ember-900">
                                        <span class="flex items-center border-r border-ink-200 px-3 text-xs font-semibold text-ink-400 dark:border-ink-700 dark:text-ink-500">/article/</span>
                                        <input id="article_slug" name="article_slug" maxlength="100" pattern="[a-z0-9]+(?:-[a-z0-9]+)*"
                                               value="{{ old('article_slug', $article?->localized('article_slug')) }}"
                                               data-article-slug data-slug-auto="{{ $article || old('article_slug') ? 'false' : 'true' }}"
                                               class="min-w-0 flex-1 rounded-r-xl border-0 bg-transparent px-3 py-2.5 text-sm focus:outline-none dark:text-white">
                                    </div>
                                    <p class="mt-1 text-xs text-ink-400 dark:text-ink-500">{{ __('article.slug_hint') }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <div class="mb-1.5 flex items-center justify-between gap-3">
                                        <label for="short_description" class="text-sm font-bold text-ink-900 dark:text-ink-200">{{ __('article.description_label') }}</label>
                                        <span class="text-xs font-semibold text-ink-400 dark:text-ink-500" data-description-count>0 / 255</span>
                                    </div>
                                    <textarea id="short_description" name="short_description" required maxlength="255" rows="2" data-article-description class="w-full resize-y rounded-xl border border-ink-200 px-4 py-2.5 text-sm leading-6 focus:border-ember-400 focus:outline-none focus:ring-4 focus:ring-ember-100 dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900">{{ old('short_description', $article?->localized('short_description')) }}</textarea>
                                    <p class="mt-1 text-xs text-ink-400 dark:text-ink-500">{{ __('article.description_hint') }}</p>
                                </div>
                            </div>
                        </section>

                        <section class="overflow-hidden rounded-2xl border border-ink-200 bg-white shadow-sm dark:border-ink-800 dark:bg-ink-900" aria-labelledby="article-body-label">
                            <div class="flex flex-col gap-3 border-b border-ink-200 bg-ink-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between dark:border-ink-800 dark:bg-ink-900">
                                <div>
                                    <h2 id="article-body-label" class="font-black text-ink-950 dark:text-ink-50">{{ __('article.body_label') }}</h2>
                                    <p id="article-body-hint" class="text-xs text-ink-500 dark:text-ink-400">{{ __('article.body_hint') }}</p>
                                </div>
                                <div class="inline-flex self-start rounded-lg border border-ink-200 bg-white p-1 dark:border-ink-700 dark:bg-ink-950" role="tablist" aria-label="{{ __('article.editor_mode') }}">
                                    <button type="button" data-editor-mode="visual" role="tab" aria-selected="true" class="rounded-md bg-ink-900 px-3 py-1.5 text-xs font-bold text-white dark:bg-white dark:text-ink-900">{{ __('article.visual_mode') }}</button>
                                    <button type="button" data-editor-mode="html" role="tab" aria-selected="false" class="rounded-md px-3 py-1.5 text-xs font-bold text-ink-600 hover:bg-ink-50 dark:text-ink-300 dark:hover:bg-ink-800">{{ __('article.html_mode') }}</button>
                                </div>
                            </div>

                            <div data-editor-visual>
                                <div class="flex flex-wrap items-center gap-1 border-b border-ink-200 bg-white p-2 dark:border-ink-800 dark:bg-ink-950" role="toolbar" aria-label="{{ __('article.formatting_toolbar') }}">
                                    @foreach ([
                                        ['paragraph', __('article.toolbar_paragraph')],
                                        ['heading2', __('article.toolbar_heading_2')],
                                        ['heading3', __('article.toolbar_heading_3')],
                                        ['heading4', __('article.toolbar_heading_4')],
                                    ] as [$action, $label])
                                        <button type="button" data-editor-action="{{ $action }}" class="mn-editor-button" aria-label="{{ $label }}" title="{{ $label }}"><x-article-editor-icon :name="$action" /></button>
                                    @endforeach
                                    <span class="mx-1 h-6 w-px bg-ink-200" aria-hidden="true"></span>
                                    @foreach ([
                                        ['bold', __('article.toolbar_bold')],
                                        ['italic', __('article.toolbar_italic')],
                                        ['underline', __('article.toolbar_underline')],
                                        ['strike', __('article.toolbar_strike')],
                                    ] as [$action, $label])
                                        <button type="button" data-editor-action="{{ $action }}" class="mn-editor-button" aria-label="{{ $label }}" title="{{ $label }}"><x-article-editor-icon :name="$action" /></button>
                                    @endforeach
                                    <span class="mx-1 h-6 w-px bg-ink-200" aria-hidden="true"></span>
                                    @foreach ([['bulletList', __('article.toolbar_bullets')], ['orderedList', __('article.toolbar_numbered')], ['blockquote', __('article.toolbar_quote')], ['horizontalRule', __('article.toolbar_rule')]] as [$action, $label])
                                        <button type="button" data-editor-action="{{ $action }}" class="mn-editor-button" aria-label="{{ $label }}" title="{{ $label }}"><x-article-editor-icon :name="$action" /></button>
                                    @endforeach
                                    <span class="mx-1 h-6 w-px bg-ink-200" aria-hidden="true"></span>
                                    @foreach ([['alignLeft', __('article.toolbar_align_left')], ['alignCenter', __('article.toolbar_align_center')], ['alignRight', __('article.toolbar_align_right')]] as [$action, $label])
                                        <button type="button" data-editor-action="{{ $action }}" class="mn-editor-button" aria-label="{{ $label }}" title="{{ $label }}"><x-article-editor-icon :name="$action" /></button>
                                    @endforeach
                                    <span class="mx-1 h-6 w-px bg-ink-200" aria-hidden="true"></span>
                                    @foreach ([['link', __('article.toolbar_link')], ['unlink', __('article.toolbar_unlink')], ['clear', __('article.toolbar_clear')]] as [$action, $label])
                                        <button type="button" data-editor-action="{{ $action }}" class="mn-editor-button" aria-label="{{ $label }}" title="{{ $label }}"><x-article-editor-icon :name="$action" /></button>
                                    @endforeach
                                    <span class="mx-1 h-6 w-px bg-ink-200" aria-hidden="true"></span>
                                    @foreach ([['undo', __('article.toolbar_undo')], ['redo', __('article.toolbar_redo')]] as [$action, $label])
                                        <button type="button" data-editor-action="{{ $action }}" class="mn-editor-button" aria-label="{{ $label }}" title="{{ $label }}"><x-article-editor-icon :name="$action" /></button>
                                    @endforeach
                                </div>
                                <div data-article-editor
                                     data-read-only="{{ $article?->status_publication === 'P' ? 'true' : 'false' }}"
                                     data-placeholder="{{ __('article.editor_placeholder') }}"
                                     data-aria-label="{{ __('article.body_label') }}"
                                     aria-describedby="article-body-hint"
                                     class="mn-rich-editor mn-article-body dark:bg-ink-950 dark:text-ink-200"></div>
                            </div>

                            <div data-editor-html hidden>
                                <label for="article_html_source" class="sr-only">{{ __('article.html_source_label') }}</label>
                                <textarea id="article_html_source" data-article-html spellcheck="false" rows="24" class="min-h-[34rem] w-full resize-y border-0 bg-ink-950 p-5 font-mono text-sm leading-6 text-ink-50 outline-none dark:bg-ink-950 dark:text-ink-300" @readonly($article?->status_publication === 'P')></textarea>
                                <p class="border-t border-ink-800 bg-ink-900 px-4 py-2 text-xs text-ink-300 dark:border-ink-800 dark:bg-ink-900 dark:text-ink-400">{{ __('article.html_mode_hint') }}</p>
                            </div>

                            <textarea name="article_body" data-article-body hidden>{{ old('article_body', $body?->article_body ?? '<p></p>') }}</textarea>
                            <div class="flex flex-wrap items-center justify-between gap-2 border-t border-ink-200 bg-ink-50 px-4 py-2 text-xs text-ink-500 dark:border-ink-800 dark:bg-ink-900 dark:text-ink-400">
                                <div class="flex flex-wrap gap-x-4 gap-y-1">
                                    <span>{{ __('article.word_count') }}: <strong data-editor-word-count class="text-ink-800 dark:text-ink-200">0</strong></span>
                                    <span>{{ __('article.character_count') }}: <strong data-editor-character-count class="text-ink-800 dark:text-ink-200">0</strong></span>
                                    <span>{{ __('article.visual_reading_estimate') }}: <strong data-editor-visual-reading-time class="text-ink-800 dark:text-ink-200">0 min</strong></span>
                                    <span>{{ __('article.spoken_reading_estimate') }}: <strong data-editor-spoken-reading-time class="text-ink-800 dark:text-ink-200">0 min</strong></span>
                                </div>
                                <span data-editor-save-state class="font-semibold text-ink-500 dark:text-ink-400">{{ __('article.draft_state') }}</span>
                            </div>
                            <noscript><p class="p-4 font-semibold text-ember-700 dark:text-ember-400">{{ __('article.javascript_required') }}</p></noscript>
                        </section>

                        <section class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900" aria-labelledby="article-attribution-title">
                            <div class="border-b border-ink-100 pb-4 dark:border-ink-800">
                                <h2 id="article-attribution-title" class="font-black text-ink-950 dark:text-ink-50">{{ __('article.attribution_access_title') }}</h2>
                                <p class="mt-1 text-sm text-ink-500 dark:text-ink-400">{{ __('article.attribution_access_hint') }}</p>
                            </div>

                            {{-- Language Selection Field --}}
                            <div class="mt-5 rounded-xl border border-ink-100 bg-ink-50/50 p-4 dark:border-ink-800 dark:bg-ink-950/50">
                                <label for="language_original_id" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-ink-700 dark:text-ink-300">{{ __('article.language_label') }}</label>
                                <select id="language_original_id" @if (! $article) name="language_original_id" @else disabled @endif required class="w-full max-w-md rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-semibold text-ink-950 shadow-2xs focus:border-ember-500 focus:outline-none dark:border-ink-700 dark:bg-ink-900 dark:text-white">
                                    @foreach ($languages as $languageId => $language)
                                        <option value="{{ $languageId }}" @selected((int) old('language_original_id', $article?->language_original_id ?? 3049) === $languageId)>{{ __($language['label_key']) }}</option>
                                    @endforeach
                                </select>
                                @if ($article)<input type="hidden" name="language_original_id" value="{{ $article->language_original_id }}">@endif
                                <p class="mt-1.5 text-xs text-ink-500 dark:text-ink-400">{{ $article ? __('article.language_locked_hint') : __('article.language_hint') }}</p>
                            </div>

                            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                                {{-- Byline Authors Column --}}
                                <div class="flex flex-col justify-between space-y-3">
                                    <div>
                                        <div class="flex items-center justify-between gap-3">
                                            <div>
                                                <h3 class="text-sm font-black text-ink-900 dark:text-ink-200">{{ __('article.byline_title') }}</h3>
                                                <p class="mt-0.5 text-xs text-ink-500 dark:text-ink-400">{{ __('article.byline_hint') }}</p>
                                            </div>
                                            <button type="button" data-add-author class="inline-flex shrink-0 items-center gap-1 rounded-xl border border-ink-200 bg-white px-3 py-1.5 text-xs font-bold text-ink-700 shadow-2xs hover:bg-ink-50 dark:border-ink-700 dark:bg-ink-950 dark:text-ink-300 dark:hover:bg-ink-800">{{ __('article.add_author') }}</button>
                                        </div>
                                        <div class="mt-3 space-y-3" data-author-list>
                                            @foreach ($creditRows as $index => $credit)
                                                <div class="flex items-end gap-3 rounded-2xl border border-ink-100 bg-ink-50/70 p-3.5 dark:border-ink-800 dark:bg-ink-950/70" data-author-row>
                                                    <div class="flex-1 min-w-0">
                                                        @php($linkedAuthor = filled($credit['user_id'] ?? null) ? $userOptionById->get($credit['user_id']) : null)
                                                        <x-article.entity-picker
                                                            :name="'author_credit_list['.$index.'][user_id]'"
                                                            :label="__('article.linked_account_label')"
                                                            :endpoint="route('workspace.article.lookup', 'user')"
                                                            :selected="$linkedAuthor ? [$linkedAuthor] : []"
                                                            :multiple="false"
                                                            type="user"
                                                            :placeholder="__('article.search_users_placeholder')" />
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <label class="mb-1 block text-xs font-bold text-ink-700 dark:text-ink-300">{{ __('article.byline_name_label') }}</label>
                                                        <input name="author_credit_list[{{ $index }}][display_name]" value="{{ $credit['display_name'] ?? '' }}" maxlength="100" required class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-950 shadow-2xs dark:border-ink-700 dark:bg-ink-900 dark:text-white">
                                                    </div>
                                                    <button type="button" data-remove-author class="inline-flex size-9 shrink-0 items-center justify-center rounded-xl border border-ink-200 bg-white text-ink-500 shadow-2xs transition hover:border-red-300 hover:bg-red-50 hover:text-red-700 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-400 dark:hover:border-red-800 dark:hover:bg-red-950 dark:hover:text-red-400" aria-label="{{ __('article.remove_author') }}" title="{{ __('article.remove_author') }}">
                                                        <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/></svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Shared Ownership Access Column --}}
                                <div class="flex flex-col space-y-3">
                                    <div>
                                        <h3 class="text-sm font-black text-ink-900 dark:text-ink-200">{{ __('article.shared_ownership_title') }}</h3>
                                        <p class="mt-0.5 text-xs leading-5 text-ink-500 dark:text-ink-400">{{ __('article.shared_ownership_hint') }}</p>
                                    </div>
                                    @if ($canManageOwnership)
                                        <x-article.entity-picker
                                            name="article_owner_user_id_list"
                                            :label="__('article.shared_ownership_search_label')"
                                            :endpoint="route('workspace.article.lookup', 'user')"
                                            :selected="collect($selectedOwnerIds)->map(fn ($id) => $userOptionById->get($id))->filter()->values()->all()"
                                            type="user"
                                            :placeholder="__('article.search_users_placeholder')" />
                                        <p class="text-xs text-ink-400 dark:text-ink-500">{{ __('article.shared_ownership_creator_hint') }}</p>
                                    @else
                                        <div class="mt-1 flex flex-wrap gap-2">
                                            @foreach ($userOptions->whereIn('id', $ownerIds) as $option)
                                                <span class="rounded-full bg-ink-100 px-3 py-1 text-xs font-semibold text-ink-700 dark:bg-ink-900 dark:text-ink-300">{{ '@'.$option['username'] }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </section>

                        <section class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900" aria-labelledby="article-sources-title">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h2 id="article-sources-title" class="font-black text-ink-950 dark:text-ink-50">{{ __('article.source_label') }}</h2>
                                    <p class="mt-1 text-sm text-ink-500 dark:text-ink-400">{{ __('article.source_hint') }}</p>
                                </div>
                                <button type="button" data-add-source class="shrink-0 rounded-lg border border-ink-200 px-3 py-1.5 text-xs font-bold text-ink-700 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-300 dark:hover:bg-ink-800">{{ __('article.add_source') }}</button>
                            </div>
                            <div class="mt-4 space-y-3" data-source-list>
                                @foreach ($sourceRows as $index => $source)
                                    <div class="rounded-xl border border-ink-100 bg-ink-50 p-3 dark:border-ink-700 dark:bg-ink-900" data-source-row>
                                        <div class="grid gap-3 md:grid-cols-2">
                                            <div><label class="mb-1 block text-xs font-semibold text-ink-500 dark:text-ink-400">{{ __('article.source_title_label') }}</label><input name="source_reference_list[{{ $index }}][source_title]" value="{{ $source['source_title'] ?? '' }}" maxlength="200" class="w-full rounded-lg border border-ink-200 bg-white px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900"></div>
                                            <div><label class="mb-1 block text-xs font-semibold text-ink-500 dark:text-ink-400">{{ __('article.source_organization_label') }}</label><input name="source_reference_list[{{ $index }}][source_organization]" value="{{ $source['source_organization'] ?? '' }}" maxlength="200" class="w-full rounded-lg border border-ink-200 bg-white px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900"></div>
                                            <div><label class="mb-1 block text-xs font-semibold text-ink-500 dark:text-ink-400">{{ __('article.source_url_label') }}</label><input name="source_reference_list[{{ $index }}][source_url]" value="{{ $source['source_url'] ?? '' }}" type="url" maxlength="1000" placeholder="https://" class="w-full rounded-lg border border-ink-200 bg-white px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900"></div>
                                            <div><label class="mb-1 block text-xs font-semibold text-ink-500 dark:text-ink-400">{{ __('article.source_identifier_label') }}</label><input name="source_reference_list[{{ $index }}][publication_identifier]" value="{{ $source['publication_identifier'] ?? '' }}" maxlength="120" placeholder="DOI, ISBN, report number…" class="w-full rounded-lg border border-ink-200 bg-white px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900"></div>
                                        </div>
                                        <button type="button" data-remove-source class="mt-2 text-xs font-bold text-ember-700 hover:underline dark:text-ember-500">{{ __('article.remove_source') }}</button>
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <section class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900" aria-labelledby="article-related-title">
                            <h2 id="article-related-title" class="font-black text-ink-950 dark:text-ink-50">{{ __('article.related_records_title') }}</h2>
                            <p class="mt-1 text-sm text-ink-500 dark:text-ink-400">{{ __('article.related_records_hint') }}</p>
                            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ([
                                    'related_article_id_list' => ['type' => 'article', 'label' => __('article.related_articles_label')],
                                    'related_organization_id_list' => ['type' => 'organization', 'label' => __('article.related_organizations_label')],
                                    'related_establishment_id_list' => ['type' => 'establishment', 'label' => __('article.related_establishments_label')],
                                    'related_practitioner_id_list' => ['type' => 'practitioner', 'label' => __('article.related_practitioners_label')],
                                    'related_service_id_list' => ['type' => 'service', 'label' => __('article.related_services_label')],
                                    'related_product_id_list' => ['type' => 'product', 'label' => __('article.related_products_label')],
                                ] as $field => $lookup)
                                    @php($selectedRelated = old($field, $article?->{$field} ?? []))
                                    <div>
                                        @php($selectedOptions = collect($relatedOptions[$field])->whereIn('id', $selectedRelated)->values()->all())
                                        <x-article.entity-picker
                                            :name="$field"
                                            :label="$lookup['label']"
                                            :endpoint="route('workspace.article.lookup', ['type' => $lookup['type'], 'exclude' => $lookup['type'] === 'article' ? $article?->getKey() : null])"
                                            :selected="$selectedOptions"
                                            :type="$lookup['type']" />
                                        @error($field)<p class="mt-1 text-xs font-semibold text-ember-700 dark:text-ember-300">{{ $message }}</p>@enderror
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <template data-author-template>
                            <div class="flex items-end gap-3 rounded-2xl border border-ink-100 bg-ink-50/70 p-3.5 dark:border-ink-800 dark:bg-ink-950/70" data-author-row>
                                <div class="flex-1 min-w-0"><x-article.entity-picker :name="'author_credit_list[__INDEX__][user_id]'" :label="__('article.linked_account_label')" :endpoint="route('workspace.article.lookup', 'user')" :multiple="false" type="user" :placeholder="__('article.search_users_placeholder')" /></div>
                                <div class="flex-1 min-w-0"><label class="mb-1 block text-xs font-bold text-ink-700 dark:text-ink-300">{{ __('article.byline_name_label') }}</label><input name="author_credit_list[__INDEX__][display_name]" maxlength="100" required class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-950 shadow-2xs dark:border-ink-700 dark:bg-ink-900 dark:text-white"></div>
                                <button type="button" data-remove-author class="inline-flex size-9 shrink-0 items-center justify-center rounded-xl border border-ink-200 bg-white text-ink-500 shadow-2xs transition hover:border-red-300 hover:bg-red-50 hover:text-red-700 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-400 dark:hover:border-red-800 dark:hover:bg-red-950 dark:hover:text-red-400" aria-label="{{ __('article.remove_author') }}" title="{{ __('article.remove_author') }}">
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/></svg>
                                </button>
                            </div>
                        </template>
                        <template data-source-template>
                            <div class="rounded-xl border border-ink-100 bg-ink-50 p-3 dark:border-ink-700 dark:bg-ink-900" data-source-row><div class="grid gap-3 md:grid-cols-2"><div><label class="mb-1 block text-xs font-semibold text-ink-500 dark:text-ink-400">{{ __('article.source_title_label') }}</label><input name="source_reference_list[__INDEX__][source_title]" maxlength="200" class="w-full rounded-lg border border-ink-200 bg-white px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900"></div><div><label class="mb-1 block text-xs font-semibold text-ink-500 dark:text-ink-400">{{ __('article.source_organization_label') }}</label><input name="source_reference_list[__INDEX__][source_organization]" maxlength="200" class="w-full rounded-lg border border-ink-200 bg-white px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900"></div><div><label class="mb-1 block text-xs font-semibold text-ink-500 dark:text-ink-400">{{ __('article.source_url_label') }}</label><input name="source_reference_list[__INDEX__][source_url]" type="url" maxlength="1000" placeholder="https://" class="w-full rounded-lg border border-ink-200 bg-white px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900"></div><div><label class="mb-1 block text-xs font-semibold text-ink-500 dark:text-ink-400">{{ __('article.source_identifier_label') }}</label><input name="source_reference_list[__INDEX__][publication_identifier]" maxlength="120" placeholder="DOI, ISBN, report number…" class="w-full rounded-lg border border-ink-200 bg-white px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900"></div></div><button type="button" data-remove-source class="mt-2 text-xs font-bold text-ember-700 hover:underline dark:text-ember-500">{{ __('article.remove_source') }}</button></div>
                        </template>
                    </div>

                    <aside class="min-w-0 space-y-4 xl:sticky xl:top-20">
                        <section class="rounded-2xl border border-ink-100 bg-white p-4 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                            <h2 class="font-black text-ink-950 dark:text-ink-50">{{ __('article.classification_title') }}</h2>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="type_article_category" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">{{ __('article.category_label') }}</label>
                                    <select id="type_article_category" name="type_article_category" required class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900">
                                        <option value="" disabled @selected(blank(old('type_article_category', $article?->type_article_category)))>{{ __('article.choose_category') }}</option>
                                        @foreach ($categories as $category)<option value="{{ $category->value }}" @selected(old('type_article_category', $article?->type_article_category) === $category->value)>{{ $category->label() }}</option>@endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="target_audience" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">{{ __('article.audience_label') }}</label>
                                    <select id="target_audience" name="target_audience" required class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900">
                                        <option value="" disabled @selected(blank(old('target_audience', $article?->target_audience)))>{{ __('article.choose_audience') }}</option>
                                        @foreach ($audiences as $audience)<option value="{{ $audience->value }}" @selected(old('target_audience', $article?->target_audience) === $audience->value)>{{ $audience->label() }}</option>@endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="level_nsfw" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">{{ __('article.nsfw_label') }}</label>
                                    <select id="level_nsfw" name="level_nsfw" required class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900">
                                        <option value="" disabled @selected(blank(old('level_nsfw', $article?->level_nsfw)))>{{ __('article.choose_nsfw_level') }}</option>
                                        @foreach ([
                                            \App\Enums\NsfwLevel::None->value => __('article.none'),
                                            \App\Enums\NsfwLevel::Mild->value => __('article.mild'),
                                            \App\Enums\NsfwLevel::Sensitive->value => __('article.sensitive'),
                                            \App\Enums\NsfwLevel::Explicit->value => __('article.explicit'),
                                        ] as $code => $label)<option value="{{ $code }}" @selected(old('level_nsfw', $article?->level_nsfw) === $code)>{{ $label }}</option>@endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="tags" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">{{ __('article.tags_label') }}</label>
                                    <input id="tags" name="tags" maxlength="500" value="{{ old('tags', $tags) }}" class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900" aria-describedby="tags-hint">
                                    <p id="tags-hint" class="mt-1 text-xs text-ink-400 dark:text-ink-500">{{ __('article.tags_hint') }}</p>
                                </div>
                            </div>
                        </section>

                        <section class="rounded-2xl border border-ink-100 bg-white p-4 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                            <h2 class="font-black text-ink-950 dark:text-ink-50">{{ __('article.publication_title') }}</h2>
                            <div class="mt-4 space-y-4">
                                <label class="flex items-start gap-3">
                                    <input type="hidden" name="is_anonymous" value="0">
                                    <input type="checkbox" name="is_anonymous" value="1" @checked(old('is_anonymous', $article?->is_anonymous ?? false)) class="mt-1 rounded border-ink-300 text-ember-600 focus:ring-ember-500 dark:border-ink-700 dark:bg-ink-950 dark:focus:ring-ember-900">
                                    <span><span class="block text-sm font-bold text-ink-900 dark:text-ink-200">{{ __('article.anonymous_label') }}</span><span class="mt-0.5 block text-xs leading-5 text-ink-500 dark:text-ink-400">{{ __('article.anonymous_hint') }}</span></span>
                                </label>

                                @if ($canSchedule)
                                    <div>
                                        <label for="scheduled_publish_at" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">{{ __('article.schedule_label') }}</label>
                                        <input id="scheduled_publish_at" name="scheduled_publish_at" type="datetime-local"
                                               value="{{ old('scheduled_publish_at', $article?->scheduled_publish_at?->format('Y-m-d\TH:i')) }}"
                                               class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900">
                                        <p class="mt-1 text-xs leading-5 text-ink-400 dark:text-ink-500">{{ __('article.schedule_hint', ['timezone' => config('app.timezone')]) }}</p>
                                    </div>
                                @endif

                                @foreach (['is_commentable' => __('article.comments_label'), 'is_shareable' => __('article.sharing_label')] as $name => $label)
                                    <label class="flex items-center gap-2 text-sm font-semibold text-ink-700 dark:text-ink-300"><input type="hidden" name="{{ $name }}" value="0"><input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $article?->{$name} ?? true)) class="rounded border-ink-300 text-ember-600 dark:border-ink-700 dark:bg-ink-950 dark:focus:ring-ember-900">{{ $label }}</label>
                                @endforeach
                            </div>
                        </section>

                        <section class="rounded-2xl border border-ink-100 bg-white p-4 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                            <h2 class="font-black text-ink-950 dark:text-ink-50">{{ __('article.editorial_details_title') }}</h2>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="revision_note" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">{{ __('article.revision_note_label') }}</label>
                                    <textarea id="revision_note" name="revision_note" rows="2" maxlength="1000" class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900">{{ old('revision_note') }}</textarea>
                                </div>
                            </div>
                        </section>

                        <section class="rounded-2xl border border-ink-200 bg-ink-50 p-4 dark:border-ink-800 dark:bg-ink-900">
                            <h2 class="text-sm font-black text-ink-950 dark:text-ink-200">{{ __('article.readiness_title') }}</h2>
                            <ul class="mt-3 space-y-2 text-xs text-ink-600 dark:text-ink-400">
                                <li data-readiness="title">{{ __('article.readiness_title_item') }}</li>
                                <li data-readiness="description">{{ __('article.readiness_description_item') }}</li>
                                <li data-readiness="body">{{ __('article.readiness_body_item') }}</li>
                                <li data-readiness="sources">{{ __('article.readiness_sources_item') }}</li>
                            </ul>
                        </section>

                        <div class="flex flex-col gap-2">
                            @if (! $article || $article->status_publication !== 'P')
                                <button class="rounded-xl bg-ink-950 px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-ink-800 dark:bg-ink-800 dark:hover:bg-ink-700">{{ __('article.save_draft') }}</button>
                            @endif
                            @if ($article && $article->status_publication !== 'P' && ! $isSubmitted)
                                <button type="submit" form="submit-article-form" class="rounded-xl border border-ember-300 bg-ember-50 px-5 py-3 text-sm font-bold text-ember-800 hover:bg-ember-100 dark:border-ember-800 dark:bg-ember-950 dark:text-ember-300 dark:hover:bg-ember-900">{{ __('article.submit_review') }}</button>
                            @endif
                            @if ($isSubmitted)
                                <p class="rounded-xl border border-leaf-200 bg-leaf-50 px-4 py-3 text-sm font-semibold text-leaf-800 dark:border-leaf-800 dark:bg-leaf-950 dark:text-leaf-200">{{ __('article.awaiting_editorial_review') }}</p>
                            @endif
                            @if ($article?->status_publication === 'P')
                                <button type="submit" form="unpublish-article-form" class="rounded-xl border border-ember-300 bg-ember-50 px-5 py-3 text-sm font-bold text-ember-800 hover:bg-ember-100 dark:border-ember-800 dark:bg-ember-950 dark:text-ember-300 dark:hover:bg-ember-900">{{ __('article.unpublish') }}</button>
                            @endif
                            <a href="{{ route('workspace.article.index') }}" class="text-center text-sm font-bold text-ink-500 hover:text-ink-800 dark:text-ink-400 dark:hover:text-ink-300">{{ __('article.back_to_articles') }}</a>
                        </div>
                    </aside>
                </div>
            </form>

            @if ($article && $article->status_publication !== 'P' && ! $isSubmitted)
                <form id="submit-article-form" method="post" action="{{ route('workspace.article.submit', $article) }}">@csrf</form>
            @endif
            @if ($article?->status_publication === 'P')
                <form id="unpublish-article-form" method="post" action="{{ route('workspace.article.unpublish', $article) }}">@csrf</form>
            @endif
        </main>
    </div>
</div>
@endsection
