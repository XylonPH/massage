# Contribution Approval Workflow — Design

## Context

Sub-project E of the original feedback batch. Founders/admins currently have no way to act on submitted establishment contributions: `Contribution` records are created with `status_contribution = 'PND'` and nothing in the app ever lists them for review, transitions their status, or acts on them. `reviewer_user_id`, `reviewed_at`, and `decision_note` are declared on the model but never read or written anywhere. The `EstablishmentContributionSubmitted` event fires into the void (no listener exists, `app/Listeners/` doesn't even exist as a directory).

Research also confirmed the originally-reported "fields always save as null" bug (`relationship_note`, `submission_note`, `duplicate_candidate_establishment_id_list`, `visit_preferred_time_note`) was already fixed by an earlier, unrelated commit (`d1fcfb8`, 2026-07-22) — no work needed there. There is also no field literally named `pending`; the real status field is `status_contribution`.

## Decisions

- **Mirror the existing Article review pattern.** `ArticleReview` (Livewire component) + its permission gate is a proven, already-built precedent in this exact codebase for "list submitted things, let an editor act on one." A new `ContributionReview` component follows the same shape rather than inventing new conventions.
- **Scope: Approve / Reject only, no "request changes" loop.** Article review supports a three-way outcome (approve / request changes / reject) with a resubmission cycle. This pass ships only approve and reject — a rejected contribution's submitter sees the decision note on their own contribution list (already-existing self-service page) but there's no in-app resubmit-into-the-same-record flow yet. This is an explicit, budget-driven scope cut; a "request changes" loop is future work.
- **Approving promotes `proposed_data` into a real `Establishment` record via a dedicated action class**, not by reusing `EstablishmentForm`'s Livewire internals directly (that component is large, actively being modified by concurrent work, and not designed to be driven headlessly outside an HTTP request). The action owns the one place where `proposed_data`'s shape gets translated into `Establishment::create()`'s shape.
- **Known shape mismatches the promotion action must handle:**
  - `proposed_data.establishment`'s translated fields use `{lang: {text, method_translation, status_review}}`; `Establishment::$fillable`'s translated fields (e.g. `display_name`) are stored as a flat `{lang: text}` map (confirmed via existing code comments in `EstablishmentForm.php`). The action unwraps `.text` out of each language entry.
  - `proposed_data.operating_schedule` → `Establishment.operating_hours` (field is genuinely named differently in the two shapes).
  - `proposed_data.event_list` has **no corresponding field on `Establishment` at all**. Not persisted in this pass — dropped silently is wrong; log/note it's present-but-unhandled rather than pretend it round-trips. (Documented as a gap, not solved here.)
  - `proposed_data.contact_channel_list` maps 1:1 to `Establishment.contact_channel_list` (same array shape, confirmed both fillable as plain arrays).
- **Reject requires a decision note.** Approve does not require one (optional context for the record).
- **Only one target collection for now.** `proposed_data.target_collection` is currently always `'establishment_main'` in practice (only establishment contributions exist today, `type_contribution` is hardcoded `'ADD'`). The promotion action is written for this case; it does not attempt to handle a hypothetical future `EDIT` contribution type or practitioner contributions (`ContributionController::storePractitioner()` exists but that path has no duplicate/visit fields and is out of scope here).
- **Permission gate:** reuse whatever permission check gates the existing `workspace.editorial.*` routes (`EnsureActiveMember` + an editorial-access check — confirm the exact existing mechanism before implementing, matching `ArticleReview`'s gate exactly rather than inventing a new one).

## Data Model

No new collections. `Contribution` model is unchanged (its Fillable already includes everything needed: `status_contribution`, `reviewed_at`, `reviewer_user_id`, `decision_note`). Status values used: `'PND'` (existing default), `'APR'` (new — approved), `'REJ'` (new — rejected). Confirm no other status codes are already assumed elsewhere in the codebase (e.g. `DuplicateEstablishmentFinder` filters on `'PND'` specifically — approving/rejecting must correctly remove a contribution from that filter by changing its status away from `'PND'`, which happens naturally once these two new codes exist).

## Changes

### 1. `PromoteContributionToEstablishment` action
New class (`app/Actions/Contribution/PromoteContributionToEstablishment.php`) taking a `Contribution` and the reviewing `User`, returning the created `Establishment`. Performs the shape translation described above, creates the `Establishment`, and does NOT itself touch the `Contribution`'s status (that's the caller's job, keeping the action single-purpose and testable in isolation).

### 2. `ContributionReview` Livewire component
New component (`app/Livewire/Workspace/Editorial/ContributionReview.php` + view), listing `Contribution::where('status_contribution', 'PND')->where('type_contribution', 'ADD')` (establishment contributions only, matching current real-world scope), each row showing submitter, submitted date, display name (from `proposed_data.establishment.display_name`), and duplicate-candidate count if any. Selecting one shows the full proposed detail (read-only render of the proposed establishment data, reusing existing display components where sensible — e.g. the same taxonomy-label lookups the contribution form itself uses) plus the submitter's relationship/submission notes and visit-request info if present.

Two actions:
- **Approve:** calls `PromoteContributionToEstablishment`, then sets `status_contribution = 'APR'`, `reviewed_at = now()`, `reviewer_user_id = auth id`, optional `decision_note`.
- **Reject:** requires `decision_note`, sets `status_contribution = 'REJ'`, `reviewed_at`, `reviewer_user_id`. No `Establishment` created.

### 3. Route + editorial dashboard link
New route `workspace.editorial.contribution.review` (or `.index`, matching whatever naming convention `ArticleReview`'s route uses) under the existing `workspace/editorial` group, same middleware stack as the other editorial routes. Add a count/link on `EditorialHome` alongside the existing article/establishment/service/quote counts, so pending contributions are actually discoverable (currently: not linked from anywhere in the editorial dashboard at all).

### 4. Event listener (minimal)
Register a listener for `EstablishmentContributionSubmitted` — scope this pass to the minimum useful thing: nothing elaborate (no email/notification system assumed to exist), just confirm the event fires correctly and either leave a documented no-op listener as a placeholder for future notification wiring, or skip registering one at all and just note in the design that "fires into the void" is acceptable for now since there's no notification system yet to hook into. (Decide during implementation planning which is more useful — leaning toward: don't build a listener with nothing to do; note this as deferred, not a defect.)

## Testing
- Feature test: an inactive/non-editorial user cannot access the review list or act on a contribution (permission gate).
- Feature test: the review list shows a real `'PND'` `'ADD'`-type contribution and excludes non-`'PND'` ones.
- Feature test: approving a real contribution (built through the actual `EstablishmentForm` submission path, not synthetic `proposed_data`) creates a correct `Establishment` — spot-check the translated-field unwrapping, the `operating_schedule`→`operating_hours` rename, and `contact_channel_list` round-tripping.
- Feature test: approving sets `status_contribution='APR'`, `reviewed_at`, `reviewer_user_id` correctly.
- Feature test: rejecting without a `decision_note` fails validation; rejecting with one sets `status_contribution='REJ'`, creates no `Establishment`.
- Feature test: an approved/rejected contribution no longer appears in the pending review list.
- Unit test: `PromoteContributionToEstablishment` in isolation — given a known `proposed_data` shape, asserts the exact resulting `Establishment` attributes (this is where the shape-mismatch handling gets its real coverage).

## Out of scope
- "Request changes" / resubmission loop (future work, needs its own design — would need a way for the submitter to edit and resubmit the same `Contribution` or a new revision of it).
- Visit-scheduling during review (sub-project D).
- `event_list` persistence onto `Establishment` (no target field exists yet).
- Practitioner contributions (`ContributionController::storePractitioner()` path).
- Any notification/email system for reviewers or submitters.
- Editing `proposed_data` before approving (admin approves as-submitted or rejects for the submitter to redo from scratch).
