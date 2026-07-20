@extends('layouts.app')

@section('title', $article ? __('article.editor_edit_title') : __('article.editor_new_title'))

@section('content')
<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
        <div>
            <a href="{{ route('workspace.article.index') }}" class="text-sm font-bold text-ember-700 hover:underline">← {{ __('article.workspace_title') }}</a>
            <h1 class="mt-3 text-4xl font-black text-ink-950">{{ $article ? __('article.editor_edit_title') : __('article.editor_new_title') }}</h1>
            <p class="mt-2 text-ink-600">{{ __('article.editor_intro') }}</p>
        </div>
        @if ($article)
            <a href="{{ route('workspace.article.revisions', $article) }}" class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-bold text-ink-700 hover:bg-ink-50">{{ __('article.revisions') }}</a>
        @endif
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-xl border border-leaf-200 bg-leaf-50 p-4 font-semibold text-leaf-800" role="status">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-ember-200 bg-ember-50 p-4 text-ember-900" role="alert">
            <ul class="list-disc space-y-1 pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="post" action="{{ $article ? route('workspace.article.update', $article) : route('workspace.article.store') }}" data-article-form class="space-y-7">
        @csrf
        @if ($article) @method('put') @endif

        <section class="grid gap-6 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm md:grid-cols-2">
            <div class="md:col-span-2">
                <label for="article_title" class="mb-2 block font-bold text-ink-900">{{ __('article.title_label') }}</label>
                <input id="article_title" name="article_title" required maxlength="75" value="{{ old('article_title', $article?->localized('article_title')) }}" class="w-full rounded-xl border border-ink-200 px-4 py-3 focus:border-ember-400 focus:outline-none focus:ring-4 focus:ring-ember-100">
            </div>
            <div class="md:col-span-2">
                <label for="article_slug" class="mb-2 block font-bold text-ink-900">{{ __('article.slug_label') }}</label>
                <input id="article_slug" name="article_slug" maxlength="100" pattern="[a-z0-9]+(?:-[a-z0-9]+)*" value="{{ old('article_slug', $article?->localized('article_slug')) }}" class="w-full rounded-xl border border-ink-200 px-4 py-3 focus:border-ember-400 focus:outline-none focus:ring-4 focus:ring-ember-100" aria-describedby="slug-hint">
                <p id="slug-hint" class="mt-1.5 text-sm text-ink-500">{{ __('article.slug_hint') }}</p>
            </div>
            <div class="md:col-span-2">
                <label for="short_description" class="mb-2 block font-bold text-ink-900">{{ __('article.description_label') }}</label>
                <textarea id="short_description" name="short_description" required maxlength="255" rows="3" class="w-full rounded-xl border border-ink-200 px-4 py-3 focus:border-ember-400 focus:outline-none focus:ring-4 focus:ring-ember-100">{{ old('short_description', $article?->localized('short_description')) }}</textarea>
            </div>
            <div>
                <label for="type_article_category" class="mb-2 block font-bold text-ink-900">{{ __('article.category_label') }}</label>
                <select id="type_article_category" name="type_article_category" required class="w-full rounded-xl border border-ink-200 px-4 py-3">
                    @foreach ($categories as $category)<option value="{{ $category->value }}" @selected(old('type_article_category', $article?->type_article_category ?? 'FTM') === $category->value)>{{ $category->label() }}</option>@endforeach
                </select>
            </div>
            <div>
                <label for="target_audience" class="mb-2 block font-bold text-ink-900">{{ __('article.audience_label') }}</label>
                <select id="target_audience" name="target_audience" required class="w-full rounded-xl border border-ink-200 px-4 py-3">
                    @foreach ($audiences as $audience)<option value="{{ $audience->value }}" @selected(old('target_audience', $article?->target_audience ?? 'G') === $audience->value)>{{ $audience->label() }}</option>@endforeach
                </select>
            </div>
            <div>
                <label for="level_nsfw" class="mb-2 block font-bold text-ink-900">{{ __('article.nsfw_label') }}</label>
                <select id="level_nsfw" name="level_nsfw" required class="w-full rounded-xl border border-ink-200 px-4 py-3">
                    @foreach (['N' => __('article.none'), 'M' => __('article.mild'), 'S' => __('article.sensitive'), 'E' => __('article.explicit')] as $code => $label)<option value="{{ $code }}" @selected(old('level_nsfw', $article?->level_nsfw ?? 'N') === $code)>{{ $label }}</option>@endforeach
                </select>
            </div>
            <div>
                <label for="tags" class="mb-2 block font-bold text-ink-900">{{ __('article.tags_label') }}</label>
                <input id="tags" name="tags" maxlength="500" value="{{ old('tags', $tags) }}" class="w-full rounded-xl border border-ink-200 px-4 py-3" aria-describedby="tags-hint">
                <p id="tags-hint" class="mt-1.5 text-sm text-ink-500">{{ __('article.tags_hint') }}</p>
            </div>
        </section>

        <section class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm">
            <label id="article-body-label" class="mb-2 block font-bold text-ink-900">{{ __('article.body_label') }}</label>
            <p id="article-body-hint" class="mb-4 text-sm text-ink-500">{{ __('article.body_hint') }}</p>
            <div class="flex flex-wrap gap-2 rounded-t-xl border border-ink-200 bg-ink-50 p-2" role="toolbar" aria-label="{{ __('article.body_label') }}">
                @foreach ([
                    ['formatBlock', 'p', __('article.toolbar_paragraph')], ['formatBlock', 'h2', __('article.toolbar_heading_2')], ['formatBlock', 'h3', __('article.toolbar_heading_3')],
                    ['bold', '', __('article.toolbar_bold')], ['italic', '', __('article.toolbar_italic')], ['insertUnorderedList', '', __('article.toolbar_bullets')], ['insertOrderedList', '', __('article.toolbar_numbered')],
                ] as [$command, $value, $label])
                    <button type="button" data-editor-command="{{ $command }}" data-editor-value="{{ $value }}" class="rounded-md border border-ink-200 bg-white px-3 py-2 text-sm font-bold text-ink-700 hover:bg-ink-100">{{ $label }}</button>
                @endforeach
                <button type="button" data-editor-link class="rounded-md border border-ink-200 bg-white px-3 py-2 text-sm font-bold text-ink-700 hover:bg-ink-100">{{ __('article.toolbar_link') }}</button>
            </div>
            <div contenteditable="{{ $article?->status_publication === 'P' ? 'false' : 'true' }}" role="textbox" aria-multiline="true" aria-labelledby="article-body-label" aria-describedby="article-body-hint"
                 data-article-editor class="mn-article-body min-h-96 rounded-b-xl border-x border-b border-ink-200 bg-white p-5 leading-7 outline-none focus:border-ember-400 focus:ring-4 focus:ring-ember-100">{!! old('article_body', $body?->article_body ?? '<p></p>') !!}</div>
            <textarea name="article_body" data-article-body hidden>{{ old('article_body', $body?->article_body) }}</textarea>
            <noscript><p class="mt-3 font-semibold text-ember-700">{{ __('article.javascript_required') }}</p></noscript>
        </section>

        <section class="grid gap-6 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm md:grid-cols-2">
            <div class="md:col-span-2">
                <label for="source_references" class="mb-2 block font-bold text-ink-900">{{ __('article.source_label') }}</label>
                <textarea id="source_references" name="source_references" rows="5" maxlength="15000" class="w-full rounded-xl border border-ink-200 px-4 py-3 font-mono text-sm" aria-describedby="source-hint">{{ old('source_references', $sources) }}</textarea>
                <p id="source-hint" class="mt-1.5 text-sm text-ink-500">{{ __('article.source_hint') }}</p>
            </div>
            <div class="md:col-span-2">
                <label for="revision_note" class="mb-2 block font-bold text-ink-900">{{ __('article.revision_note_label') }}</label>
                <textarea id="revision_note" name="revision_note" rows="2" maxlength="1000" class="w-full rounded-xl border border-ink-200 px-4 py-3">{{ old('revision_note') }}</textarea>
            </div>
            @foreach (['is_commentable' => __('article.comments_label'), 'is_shareable' => __('article.sharing_label')] as $name => $label)
                <label class="flex items-center gap-3 font-semibold text-ink-800"><input type="hidden" name="{{ $name }}" value="0"><input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $article?->{$name} ?? true)) class="size-5 rounded border-ink-300 text-ember-600">{{ $label }}</label>
            @endforeach
        </section>

        <div class="flex flex-wrap items-center gap-3">
            @if (! $article || $article->status_publication !== 'P')
                <button class="rounded-xl bg-ink-900 px-6 py-3 font-bold text-white hover:bg-ink-800">{{ __('article.save_draft') }}</button>
            @endif
            @if ($article && $article->status_publication !== 'P')
                <button type="submit" form="submit-article-form" class="rounded-xl border border-ember-300 bg-ember-50 px-6 py-3 font-bold text-ember-800 hover:bg-ember-100">{{ __('article.submit_review') }}</button>
            @endif
        </div>
    </form>
    @if ($article && $article->status_publication !== 'P')
        <form id="submit-article-form" method="post" action="{{ route('workspace.article.submit', $article) }}">@csrf</form>
    @endif
</div>
@endsection
