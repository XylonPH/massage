# Article Form Quick Fixes — Design

## Context

This is sub-project A of a larger batch of feedback covering the article authoring flow (`workspace/article/new`) and the establishment contribution flow (`workspace/contribution/establishment/new`). The batch was decomposed into independent sub-projects (A–F); this spec covers only the article-form quick fixes. Later sub-projects (article image embedding, contribution form fixes, owner-submission features, contribution approval workflow) are out of scope here and will get their own specs.

Two user-reported issues on the article editor:

1. Target Audience and NSFW Level fields silently default to "General" / "None" instead of requiring the author to choose.
2. Saving a draft sometimes fails with no visible explanation, suspected to be a body-length issue.

Research into the codebase surfaced two additional, related problems worth fixing as part of this same change:

3. `App\Enums\NsfwLevel` is a dead/inconsistent enum — its codes (`N/S/M/X`) don't match what the form, validation, and persisted data actually use (`N/M/S/E`). `Explicit` is unreachable through the UI as currently coded.
4. The article editor only ever calls "Save Draft" — there is no separate "Publish" action on this page. Real publishing happens through a separate review flow (`ArticleController::submit()`), which currently performs **zero** content validation. Meanwhile, draft-save is over-strict: it blocks on a `min:20`-character sanitized-HTML rule *and* a separate hard block if visible word count is 0. The strict content check is on the wrong action.

## Decisions

- **Target Audience / NSFW Level:** remove the defaults entirely. Both fields render blank/unselected until the author actively picks a value. Backend `required` validation (already present) is the actual enforcement; removing the defaults is what makes that enforcement visible instead of silently satisfied.
- **NSFW enum fix:** align `NsfwLevel::Explicit` to code `'E'` (matching real persisted data and the form), then derive both the blade `<select>` options and the `SaveArticleRequest` validation rule from `NsfwLevel::cases()` so the two can't drift apart again.
- **Draft-save validation:** minimal. A draft only needs whatever already-required non-body fields it needs (title, category, audience, nsfw level, etc. — unchanged). Body content can be short or empty while in draft.
- **Submit-for-review validation:** this is where real content-readiness is enforced. Moving/adding the meaningful-content check (real title, short description, minimum visible word count) to `ArticleController::submit()` instead of blocking on every draft save.

## Changes

### 1. Force explicit Target Audience / NSFW Level selection

- `apps/web/resources/views/workspace/article/editor.blade.php`: remove the `?? 'G'` / `?? 'N'` fallbacks on the `target_audience` and `level_nsfw` `<select>` elements (lines ~322, ~328) so an unselected/placeholder option renders for a new article with no stored value.
- `apps/web/app/Models/Article/Article.php`: remove the `'target_audience' => 'G'` and `'level_nsfw' => 'N'` entries from `sparseDefaults()` (lines ~69, ~96) so the model itself no longer resurrects a default when the field is unset.
- No validation rule changes needed — `SaveArticleRequest::rules()` already marks both `required`.

### 2. Fix the dead/inconsistent NSFW enum

- `apps/web/app/Enums/NsfwLevel.php`: change `Explicit`'s code from `'X'` to `'E'`.
- `apps/web/resources/views/workspace/article/editor.blade.php`: replace the hardcoded NSFW option list with one generated from `NsfwLevel::cases()`.
- `apps/web/app/Http/Requests/Article/SaveArticleRequest.php`: replace `Rule::in(['N', 'M', 'S', 'E'])` with `Rule::enum(NsfwLevel::class)` (matching how `target_audience` already validates against `ArticleAudience`).

### 3. Relax draft-save content validation

- `apps/web/app/Http/Requests/Article/SaveArticleRequest.php`: change `article_body` rule from `['required', 'string', 'min:20', 'max:120000']` to `['nullable', 'string', 'max:120000']`.
- `apps/web/app/Actions/Article/SaveArticleDraft.php`: remove the `if ($metrics['word_count'] === 0) { throw ... }` block (lines ~30-34). Saving with an empty/short body is now allowed; `ArticleBody`/`ArticleRevision` are written with whatever word count results (including 0).

### 4. Add real content-readiness validation to submit-for-review

- `apps/web/app/Http/Controllers/Web/Workspace/ArticleController.php::submit()`: before flipping `status_review` to `'P'`, validate that the article has a non-empty title, a non-empty short description, and that its current `ArticleBody` has a minimum visible word count (20 words, matching the previous draft-save threshold, so the effective bar for "ready to be reviewed" doesn't get lower than it was). On failure, redirect back with validation errors (consistent with how `SaveArticleRequest` failures are surfaced elsewhere on this page).

## Testing

- Feature test: saving a new article as a draft with an empty/very short body succeeds.
- Feature test: saving a draft with no `target_audience`/`level_nsfw` selected fails validation (already covered by existing `required` rule, but worth a regression test now that the silent default is gone).
- Feature test: `NsfwLevel::Explicit` round-trips as `'E'` and is selectable from the article form.
- Feature test: `submit()` on an article with a too-short body / missing title / missing short description fails with a validation error and does not flip `status_review`.
- Feature test: `submit()` on a fully-populated article succeeds (existing behavior, regression check).

## Out of scope

- Article image embedding (sub-project B).
- Any establishment-contribution changes (sub-projects C–E).
- Git branch cleanup (sub-project F) — handled separately, not a design task.
