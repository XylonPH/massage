# Article Form Quick Fixes Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Force explicit Target Audience / NSFW Level selection on the article editor, fix the dead/inconsistent `NsfwLevel` enum, and remove the last content-length block on draft-save.

**Architecture:** Small, targeted edits to one Blade view, one Eloquent model, one enum, and one Action class in the Laravel app at `apps/web/`. No new files, no schema changes. Tests are PHPUnit feature/unit tests run via `php artisan test`.

**Tech Stack:** Laravel, MongoDB (`mongodb/laravel-mongodb`), PHPUnit.

## Global Constraints

- Draft validation must stay minimal: a draft can be saved with a short or empty body; only Target Audience and NSFW Level become newly-required-to-select (not required-to-be-non-default, since there is no valid default left).
- `NsfwLevel::Explicit` must persist as `'E'` (not `'X'`) to match existing/real form and validation codes — this is a pure code-rename, not a behavior or data-format change, since `'X'` was never reachable through the UI.
- Do not touch `SaveArticleRequest::rules()['article_body']` or `ArticleController::submit()`'s `MINIMUM_SUBMISSION_WORDS` — both were already fixed in commit `af92a50` and are out of scope here.
- Run tests from `apps/web/`: `php artisan test --filter <TestClass>`.

---

### Task 1: Force explicit Target Audience / NSFW Level selection

**Files:**
- Modify: `apps/web/resources/views/workspace/article/editor.blade.php:329-337`
- Modify: `apps/web/app/Models/Article/Article.php:69,96`
- Test: `apps/web/tests/Feature/Article/ArticleWorkspaceTest.php`

**Interfaces:**
- Consumes: `$audiences` (array of `ArticleAudience` enum cases, passed into the view by `ArticleController::editorData()` — unchanged), `$article` (nullable `Article` model, unchanged).
- Produces: nothing consumed by later tasks in this plan.

- [ ] **Step 1: Write the failing test**

Add this test to `apps/web/tests/Feature/Article/ArticleWorkspaceTest.php` (inside the `ArticleWorkspaceTest` class, alongside the other `test_editor_is_integrated_with_workspace_...` test):

```php
    public function test_new_article_form_has_no_preselected_audience_or_nsfw_level(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/workspace/article/new');

        $response->assertOk();
        $this->assertMatchesRegularExpression(
            '/<select id="target_audience"[^>]*>\s*<option value="" disabled selected>/',
            $response->getContent(),
        );
        $this->assertMatchesRegularExpression(
            '/<select id="level_nsfw"[^>]*>\s*<option value="" disabled selected>/',
            $response->getContent(),
        );
    }
```

- [ ] **Step 2: Run test to verify it fails**

Run (from `apps/web/`): `php artisan test --filter test_new_article_form_has_no_preselected_audience_or_nsfw_level`
Expected: FAIL — neither select currently renders a blank `disabled selected` placeholder option (they default to `'G'`/`'N'` selected instead).

- [ ] **Step 3: Update the Blade view**

In `apps/web/resources/views/workspace/article/editor.blade.php`, replace lines 329-337:

```blade
                                    <label for="target_audience" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">{{ __('article.audience_label') }}</label>
                                    <select id="target_audience" name="target_audience" required class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900">
                                        @foreach ($audiences as $audience)<option value="{{ $audience->value }}" @selected(old('target_audience', $article?->target_audience ?? 'G') === $audience->value)>{{ $audience->label() }}</option>@endforeach
```

with:

```blade
                                    <label for="target_audience" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">{{ __('article.audience_label') }}</label>
                                    <select id="target_audience" name="target_audience" required class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900">
                                        <option value="" disabled @selected(blank(old('target_audience', $article?->target_audience)))>{{ __('article.choose_audience') }}</option>
                                        @foreach ($audiences as $audience)<option value="{{ $audience->value }}" @selected(old('target_audience', $article?->target_audience) === $audience->value)>{{ $audience->label() }}</option>@endforeach
```

And replace the level_nsfw block (originally lines 335-337):

```blade
                                    <label for="level_nsfw" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">{{ __('article.nsfw_label') }}</label>
                                    <select id="level_nsfw" name="level_nsfw" required class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900">
                                        @foreach (['N' => __('article.none'), 'M' => __('article.mild'), 'S' => __('article.sensitive'), 'E' => __('article.explicit')] as $code => $label)<option value="{{ $code }}" @selected(old('level_nsfw', $article?->level_nsfw ?? 'N') === $code)>{{ $label }}</option>@endforeach
```

with (option list now driven by `NsfwLevel::cases()`, wired up in Task 2 — for this step, keep the literal array but drop the `?? 'N'` fallback so this step's test passes on its own; Task 2 will replace the array source):

```blade
                                    <label for="level_nsfw" class="mb-1 block text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">{{ __('article.nsfw_label') }}</label>
                                    <select id="level_nsfw" name="level_nsfw" required class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white dark:focus:border-ember-500 dark:focus:ring-ember-900">
                                        <option value="" disabled @selected(blank(old('level_nsfw', $article?->level_nsfw)))>{{ __('article.choose_nsfw_level') }}</option>
                                        @foreach (['N' => __('article.none'), 'M' => __('article.mild'), 'S' => __('article.sensitive'), 'E' => __('article.explicit')] as $code => $label)<option value="{{ $code }}" @selected(old('level_nsfw', $article?->level_nsfw) === $code)>{{ $label }}</option>@endforeach
```

Add the two new translation strings to `apps/web/lang/eng/article.php`, next to the existing `'choose_category'` entry:

```php
    'choose_audience' => 'Choose a target audience',
    'choose_nsfw_level' => 'Choose an NSFW level',
```

- [ ] **Step 4: Remove the model-level defaults**

In `apps/web/app/Models/Article/Article.php`, in `sparseDefaults()`, remove the line `'target_audience' => 'G',` (around line 69) and the line `'level_nsfw' => 'N',` (around line 96). Leave every other default in that method untouched.

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test --filter test_new_article_form_has_no_preselected_audience_or_nsfw_level`
Expected: PASS

- [ ] **Step 6: Run the full existing article test suite to check for regressions**

Run: `php artisan test --filter ArticleWorkspaceTest`
Expected: all tests PASS. If `test_active_verified_member_can_create_sanitized_draft_and_revision` or similar fails because it relied on the old default, check whether it explicitly passes `target_audience`/`level_nsfw` in its payload — `validPayload()` in that test file already sets both explicitly (`'target_audience' => 'C'`, `'level_nsfw' => 'N'`), so no changes should be needed there.

- [ ] **Step 7: Commit**

```bash
git add apps/web/resources/views/workspace/article/editor.blade.php apps/web/app/Models/Article/Article.php apps/web/lang/eng/article.php apps/web/tests/Feature/Article/ArticleWorkspaceTest.php
git commit -m "fix(article): require explicit target audience and NSFW level selection"
```

---

### Task 2: Fix the dead/inconsistent NsfwLevel enum

**Files:**
- Modify: `apps/web/app/Enums/NsfwLevel.php`
- Modify: `apps/web/app/Http/Requests/Article/SaveArticleRequest.php:74`
- Modify: `apps/web/resources/views/workspace/article/editor.blade.php` (level_nsfw option loop, edited in Task 1)
- Test: `apps/web/tests/Unit/NsfwLevelTest.php` (new file)

**Interfaces:**
- Consumes: nothing from Task 1 except the already-edited level_nsfw `<select>` block (this task only changes what feeds its option list, not the placeholder logic).
- Produces: `App\Enums\NsfwLevel::cases()` becomes the single source of truth for NSFW option codes/labels, consumed by both the Blade view and `SaveArticleRequest`.

- [ ] **Step 1: Write the failing test**

Create `apps/web/tests/Unit/NsfwLevelTest.php`:

```php
<?php

namespace Tests\Unit;

use App\Enums\NsfwLevel;
use PHPUnit\Framework\TestCase;

class NsfwLevelTest extends TestCase
{
    public function test_explicit_case_matches_the_real_persisted_and_validated_code(): void
    {
        $this->assertSame('E', NsfwLevel::Explicit->value);
    }

    public function test_case_codes_match_what_the_article_form_actually_validates(): void
    {
        $this->assertSame(['N', 'S', 'M', 'E'], array_map(fn (NsfwLevel $case) => $case->value, NsfwLevel::cases()));
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter NsfwLevelTest`
Expected: FAIL — `NsfwLevel::Explicit->value` is currently `'X'`, not `'E'`.

- [ ] **Step 3: Fix the enum**

In `apps/web/app/Enums/NsfwLevel.php`, change:

```php
    case Explicit = 'X';
```

to:

```php
    case Explicit = 'E';
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --filter NsfwLevelTest`
Expected: PASS

- [ ] **Step 5: Wire SaveArticleRequest validation to the enum**

In `apps/web/app/Http/Requests/Article/SaveArticleRequest.php:74`, replace:

```php
            'level_nsfw' => ['required', Rule::in(['N', 'M', 'S', 'E'])],
```

with:

```php
            'level_nsfw' => ['required', Rule::enum(\App\Enums\NsfwLevel::class)],
```

(Match the existing style used one line above for `target_audience` — `Rule::enum(ArticleAudience::class)` — by adding `use App\Enums\NsfwLevel;` to the top of the file instead of using the fully-qualified name inline, then referencing `Rule::enum(NsfwLevel::class)`.)

- [ ] **Step 6: Wire the Blade option list to the enum**

In `apps/web/resources/views/workspace/article/editor.blade.php`, in the level_nsfw `<select>` (edited in Task 1), replace the hardcoded array:

```blade
                                        @foreach (['N' => __('article.none'), 'M' => __('article.mild'), 'S' => __('article.sensitive'), 'E' => __('article.explicit')] as $code => $label)<option value="{{ $code }}" @selected(old('level_nsfw', $article?->level_nsfw) === $code)>{{ $label }}</option>@endforeach
```

with (labels keyed the same way the existing `lang/eng/article.php` strings already are — `none`/`mild`/`sensitive`/`explicit` — mapped from enum value):

```blade
                                        @foreach ([
                                            \App\Enums\NsfwLevel::None->value => __('article.none'),
                                            \App\Enums\NsfwLevel::Mature->value => __('article.mild'),
                                            \App\Enums\NsfwLevel::Suggestive->value => __('article.sensitive'),
                                            \App\Enums\NsfwLevel::Explicit->value => __('article.explicit'),
                                        ] as $code => $label)<option value="{{ $code }}" @selected(old('level_nsfw', $article?->level_nsfw) === $code)>{{ $label }}</option>@endforeach
```

(Kept the label mapping identical to what already existed — `'M'` still shows the "mild" label, `'S'` still shows "sensitive" — only the source of the code list changed, from a hand-written array to the enum, so behavior for existing users/labels is unchanged.)

- [ ] **Step 7: Run the full article test suite to check for regressions**

Run: `php artisan test --filter ArticleWorkspaceTest`
Expected: all tests PASS (payloads already use `'level_nsfw' => 'N'`, which remains valid).

- [ ] **Step 8: Commit**

```bash
git add apps/web/app/Enums/NsfwLevel.php apps/web/app/Http/Requests/Article/SaveArticleRequest.php apps/web/resources/views/workspace/article/editor.blade.php apps/web/tests/Unit/NsfwLevelTest.php
git commit -m "fix(article): align NsfwLevel enum codes with real form/validation values"
```

---

### Task 3: Remove the last content-length block on draft-save

**Files:**
- Modify: `apps/web/app/Actions/Article/SaveArticleDraft.php:30-34`
- Test: `apps/web/tests/Feature/Article/ArticleWorkspaceTest.php`

**Interfaces:**
- Consumes: nothing from Tasks 1–2.
- Produces: nothing consumed elsewhere in this plan.

- [ ] **Step 1: Write the failing test**

Add to `apps/web/tests/Feature/Article/ArticleWorkspaceTest.php`:

```php
    public function test_draft_with_an_empty_body_can_still_be_saved(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/workspace/article', $this->validPayload([
            'article_body' => '<p></p>',
        ]));

        $response->assertRedirect();
        $this->assertSame(1, Article::query()->count());
    }
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter test_draft_with_an_empty_body_can_still_be_saved`
Expected: FAIL with a `ValidationException` / `assertRedirect()` failure — `<p></p>` sanitizes to a 0-word body, and `SaveArticleDraft::execute()` currently throws `ValidationException::withMessages(['article_body' => ...])` for `word_count === 0`.

- [ ] **Step 3: Remove the block**

In `apps/web/app/Actions/Article/SaveArticleDraft.php`, remove lines 30-34:

```php
        if ($metrics['word_count'] === 0) {
            throw ValidationException::withMessages([
                'article_body' => __('article.validation_body_visible_text'),
            ]);
        }
```

After removal, check whether `Illuminate\Validation\ValidationException` (the `use` import at the top of the file) is still referenced elsewhere in this file — it is not used anywhere else in `SaveArticleDraft.php`, so also remove the now-unused `use Illuminate\Validation\ValidationException;` import line.

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --filter test_draft_with_an_empty_body_can_still_be_saved`
Expected: PASS

- [ ] **Step 5: Run the full article test suite to check for regressions**

Run: `php artisan test --filter ArticleWorkspaceTest`
Expected: all tests PASS.

- [ ] **Step 6: Commit**

```bash
git add apps/web/app/Actions/Article/SaveArticleDraft.php apps/web/tests/Feature/Article/ArticleWorkspaceTest.php
git commit -m "fix(article): allow saving a draft with an empty body"
```

---

## Final verification

- [ ] Run the full test suite once more: `php artisan test` (from `apps/web/`). Expected: all tests PASS, including the three new tests and the full `ArticleWorkspaceTest`/`NsfwLevelTest` classes.
