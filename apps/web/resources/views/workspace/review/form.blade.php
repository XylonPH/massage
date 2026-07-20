@extends('layouts.workspace', ['navActive' => 'reviews'])

@section('title', $review ? __('review.edit_title', ['target' => $target['name']]) : __('review.new_title', ['target' => $target['name']]))
@section('page-title', $review ? __('review.edit_title', ['target' => $target['name']]) : __('review.new_title', ['target' => $target['name']]))
@section('page-context', __('review.form_intro'))

@section('content')
@php
    $mode = old('mode_rating', $rating?->mode_rating ?? 'SMP');
    $storeRoute = $target['kind'] === 'spa'
        ? route('spa.review.store', ['establishment_slug' => $target['slug']])
        : route('therapist.review.store', ['therapist_slug' => $target['slug']]);
    $formAction = $review ? route('workspace.review.update', $review) : $storeRoute;
@endphp
<div class="mx-auto max-w-5xl">
    <a href="{{ $target['route'] }}" class="text-sm font-bold text-ember-700 hover:underline dark:text-ember-400">← {{ __('review.back_to_target', ['target' => $target['name']]) }}</a>

    @if (session('status'))
        <div class="mt-6 rounded-xl border border-leaf-200 bg-leaf-50 p-4 font-semibold text-leaf-800 dark:border-leaf-800 dark:bg-leaf-950 dark:text-leaf-300" role="status">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="mt-6 rounded-xl border border-ember-200 bg-ember-50 p-4 text-ember-900 dark:border-ember-800 dark:bg-ember-950 dark:text-ember-100" role="alert">
            <p class="font-bold">{{ __('review.validation_heading') }}</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="post" action="{{ $formAction }}" class="mt-6 space-y-7">
        @csrf
        @if ($review) @method('put') @endif

        <section class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
            <p class="text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">{{ __('review.target_label') }}</p>
            <p class="mt-1 text-xl font-black text-ink-950 dark:text-ink-50">{{ $target['name'] }}</p>
        </section>

        <section class="space-y-6 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
            <div>
                <label for="review_title" class="mb-2 block font-bold text-ink-900 dark:text-ink-100">{{ __('review.title_label') }}</label>
                <input id="review_title" name="review_title" required maxlength="75" value="{{ old('review_title', $review?->review_title) }}" class="w-full rounded-xl border border-ink-200 px-4 py-3 focus:border-ember-400 focus:outline-none focus:ring-4 focus:ring-ember-100 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50" aria-describedby="review-title-hint">
                <p id="review-title-hint" class="mt-1.5 text-sm text-ink-500 dark:text-ink-400">{{ __('review.title_hint') }}</p>
            </div>
            <div>
                <label for="review_body" class="mb-2 block font-bold text-ink-900 dark:text-ink-100">{{ __('review.body_label') }}</label>
                <textarea id="review_body" name="review_body" required maxlength="30000" rows="14" class="w-full rounded-xl border border-ink-200 px-4 py-3 leading-7 focus:border-ember-400 focus:outline-none focus:ring-4 focus:ring-ember-100 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50" aria-describedby="review-body-hint">{{ old('review_body', $review?->review_body) }}</textarea>
                <p id="review-body-hint" class="mt-1.5 text-sm text-ink-500 dark:text-ink-400">{{ __('review.body_hint') }}</p>
            </div>
        </section>

        <fieldset class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
            <legend class="px-1 text-lg font-black text-ink-950 dark:text-ink-50">{{ __('review.rating_mode_label') }}</legend>
            <div class="mt-2 grid gap-4 md:grid-cols-2">
                @foreach (['SMP' => ['simple_rating', 'simple_rating_hint'], 'CRT' => ['criteria_rating', 'criteria_rating_hint']] as $value => [$label, $hint])
                    <label class="rounded-xl border border-ink-200 p-4 dark:border-ink-700">
                        <span class="flex items-center gap-3 font-bold text-ink-900 dark:text-ink-100"><input type="radio" name="mode_rating" value="{{ $value }}" @checked($mode === $value) class="size-5 border-ink-300 text-ember-600">{{ __('review.'.$label) }}</span>
                        <span class="mt-1 block pl-8 text-sm text-ink-500 dark:text-ink-400">{{ __('review.'.$hint) }}</span>
                    </label>
                @endforeach
            </div>

            <div class="mt-6">
                <label for="rating_score" class="mb-2 block font-bold text-ink-900 dark:text-ink-100">{{ __('review.overall_score') }}</label>
                <select id="rating_score" name="rating_score" class="w-full rounded-xl border border-ink-200 px-4 py-3 md:max-w-sm dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50">
                    <option value="">{{ __('review.choose_score') }}</option>
                    @foreach (__('review.rating_labels') as $score => $label)
                        <option value="{{ $score }}" @selected((string) old('rating_score', $rating?->mode_rating === 'SMP' ? (int) $rating->rating_score : '') === (string) $score)>{{ __('review.score_option', ['score' => $score, 'label' => $label]) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @foreach ($target['criteria'] as $code => $label)
                    <div>
                        <label for="criterion_{{ strtolower($code) }}" class="mb-2 block font-bold text-ink-900 dark:text-ink-100">{{ $label }}</label>
                        <select id="criterion_{{ strtolower($code) }}" name="rating_criteria[{{ $code }}]" class="w-full rounded-xl border border-ink-200 px-4 py-3 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50">
                            @foreach (['NOB' => __('review.not_observed'), 'NAP' => __('review.not_applicable')] + __('review.rating_labels') as $value => $optionLabel)
                                <option value="{{ $value }}" @selected((string) old("rating_criteria.$code", $criteriaValues[$code] ?? 'NOB') === (string) $value)>{{ is_numeric($value) ? __('review.score_out_of_ten', ['score' => $value]) . ' — ' . $optionLabel : $optionLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>
        </fieldset>

        <section class="grid gap-6 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900 md:grid-cols-2">
            <div>
                <label for="date_experience" class="mb-2 block font-bold text-ink-900 dark:text-ink-100">{{ __('review.date_experience_label') }}</label>
                <input id="date_experience" type="date" name="date_experience" max="{{ now()->toDateString() }}" value="{{ old('date_experience', $review?->date_experience?->toDateString()) }}" class="w-full rounded-xl border border-ink-200 px-4 py-3 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50">
            </div>
            <div>
                <label for="service_received" class="mb-2 block font-bold text-ink-900 dark:text-ink-100">{{ __('review.service_received_label') }}</label>
                <input id="service_received" name="service_received" maxlength="160" value="{{ old('service_received', $review?->service_received) }}" placeholder="{{ __('review.service_received_hint') }}" class="w-full rounded-xl border border-ink-200 px-4 py-3 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50">
            </div>
            <div>
                <label for="amount_paid" class="mb-2 block font-bold text-ink-900 dark:text-ink-100">{{ __('review.amount_paid_label') }}</label>
                <input id="amount_paid" type="number" name="amount_paid" min="0" max="999999.99" step="0.01" value="{{ old('amount_paid', $review?->amount_paid) }}" class="w-full rounded-xl border border-ink-200 px-4 py-3 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50">
            </div>
            <div>
                <label for="language_original_id" class="mb-2 block font-bold text-ink-900 dark:text-ink-100">{{ __('review.language_label') }}</label>
                <select id="language_original_id" name="language_original_id" required class="w-full rounded-xl border border-ink-200 px-4 py-3 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50">
                    <option value="3049" @selected((int) old('language_original_id', $review?->language_original_id ?? 3049) === 3049)>{{ __('review.english') }}</option>
                    <option value="3600" @selected((int) old('language_original_id', $review?->language_original_id ?? 3049) === 3600)>{{ __('review.filipino') }}</option>
                </select>
            </div>
            <div>
                <label for="type_review_disclosure" class="mb-2 block font-bold text-ink-900 dark:text-ink-100">{{ __('review.disclosure_label') }}</label>
                <select id="type_review_disclosure" name="type_review_disclosure" required class="w-full rounded-xl border border-ink-200 px-4 py-3 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50">
                    @foreach (['SFP', 'ANN', 'SPO', 'DSC', 'CMP', 'ORQ', 'PRQ', 'MVT'] as $code)
                        <option value="{{ $code }}" @selected(old('type_review_disclosure', $review?->type_review_disclosure ?? 'SFP') === $code)>{{ __('review.disclosure_'.$code) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="level_nsfw" class="mb-2 block font-bold text-ink-900 dark:text-ink-100">{{ __('review.nsfw_label') }}</label>
                <select id="level_nsfw" name="level_nsfw" required class="w-full rounded-xl border border-ink-200 px-4 py-3 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50">
                    @foreach (['N' => 'none', 'M' => 'mild', 'S' => 'sensitive', 'E' => 'explicit'] as $code => $label)
                        <option value="{{ $code }}" @selected(old('level_nsfw', $review?->level_nsfw ?? 'N') === $code)>{{ __('review.nsfw_'.$label) }}</option>
                    @endforeach
                </select>
            </div>
            <label class="md:col-span-2 flex items-start gap-3 rounded-xl bg-ink-50 p-4 dark:bg-ink-800">
                <input type="hidden" name="is_anonymous" value="0">
                <input type="checkbox" name="is_anonymous" value="1" @checked(old('is_anonymous', $review?->is_anonymous ?? false)) class="mt-0.5 size-5 rounded border-ink-300 text-ember-600">
                <span><span class="block font-bold text-ink-900 dark:text-ink-100">{{ __('review.anonymous_label') }}</span><span class="mt-1 block text-sm text-ink-500 dark:text-ink-400">{{ __('review.anonymous_hint') }}</span></span>
            </label>
        </section>

        <button class="rounded-xl bg-ink-950 px-6 py-3 font-bold text-white hover:bg-ink-800">{{ __('review.save_draft') }}</button>
    </form>

    @if ($review)
        <form method="post" action="{{ route('workspace.review.submit', $review) }}" class="mt-4">
            @csrf
            <button class="rounded-xl border border-ember-300 bg-ember-50 px-6 py-3 font-bold text-ember-800 hover:bg-ember-100 dark:border-ember-800 dark:bg-ember-950 dark:text-ember-200 dark:hover:bg-ember-900">{{ __('review.submit_review') }}</button>
        </form>
    @endif
</div>
@endsection
