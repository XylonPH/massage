# Contribution Approval Workflow Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Let a founder/admin see submitted establishment contributions, approve one (promoting it into a real `Establishment` record) or reject one (with a required reason) — closing the loop that currently leaves every contribution stuck at `status_contribution='PND'` forever.

**Architecture:** Mirrors the existing, proven `ArticleIndex`/`ArticleReview` split exactly: a list component (`ContributionIndex`) and a detail-plus-actions component (`ContributionReview`), both under `app/Livewire/Workspace/Editorial/`, gated by the same `workspace.editorial.access` permission every other editorial route already uses. A new, isolated `PromoteContributionToEstablishment` action owns the one place `proposed_data`'s shape gets translated into a real `Establishment::create()` call — built first and tested in isolation so the trickiest part (translated-field unwrapping, the `location_point`→flat-coordinates reversal) has its own gate before any UI is built on top of it.

**Tech Stack:** Laravel, Livewire, MongoDB, PHPUnit.

## Global Constraints

- Two-way decision only: Approve or Reject. No "request changes"/resubmission loop in this plan (explicitly deferred — see spec).
- Reject requires a `decision_note`; Approve does not.
- Reuse the existing `workspace.editorial.access` permission string — do not invent a new one.
- `Contribution.reviewer_user_id` is a **singular** string field (unlike Article's `reviewer_user_id_list` array) — assign it directly, don't append to a list.
- `proposed_data.establishment` never contains `coordinate_latitude`/`coordinate_longitude` directly — only a GeoJSON `location_point.coordinates: [longitude, latitude]`. `Establishment::$fillable` has the reverse: flat `coordinate_latitude`/`coordinate_longitude`, no `location_point` field at all. The promotion action must convert between these.
- `proposed_data.event_list` has no corresponding field anywhere on `Establishment` — do not attempt to persist it in this plan (confirmed gap, out of scope).
- No listener for `EstablishmentContributionSubmitted` is added in this plan. It already fires with zero listeners today; there is nothing useful for a listener to do yet (no notification/email system exists to hook into), so registering an empty placeholder listener would be pure noise. This is a deliberate omission, not a missed task — do not add one.
- `proposed_data.establishment.full_description` maps back to `Establishment`'s `description` attribute (the structure-guide's field name differs from the model's — confirmed in `EstablishmentForm.php`'s own `$outputField` rename).
- Only `type_contribution === 'ADD'` contributions targeting `establishment_main` are in scope (the only kind that currently exists in practice).
- Run tests from `apps/web/`: `php artisan test --filter <TestClass>`.
- This repo works directly on the `main` branch (no feature branches/worktrees), and every commit is auto-pushed to the real GitHub `origin/main` — stage only the exact files each task names, never `git add -A`/`git add .`/`git add -u` (this repo has other concurrent agents — Codex, Antigravity — with real uncommitted work in the same tree).

---

### Task 1: `PromoteContributionToEstablishment` action

**Files:**
- Create: `apps/web/app/Actions/Contribution/PromoteContributionToEstablishment.php`
- Test: `apps/web/tests/Unit/Actions/PromoteContributionToEstablishmentTest.php`

**Interfaces:**
- Produces: `PromoteContributionToEstablishment::execute(Contribution $contribution): Establishment`. Consumed by Task 3 (`ContributionReview::approve()`).

- [ ] **Step 1: Write the failing test**

Read `apps/web/tests/Feature/Workspace/ContributionTest.php` first to find its exact valid-submission payload pattern (address fields, `country_id`/`region_id` values, contact channel structure — the plan for sub-project C already established the real field values to use, e.g. `country_id` 608, `region_id` 1, `city_name` 'Makati'). This test builds a REAL contribution through the actual submission path rather than hand-crafting `proposed_data`, so any drift between what the form produces and what this action expects gets caught for real.

Create `apps/web/tests/Unit/Actions/PromoteContributionToEstablishmentTest.php`:

```php
<?php

namespace Tests\Unit\Actions;

use App\Actions\Contribution\PromoteContributionToEstablishment;
use App\Livewire\Workspace\Editorial\EstablishmentForm;
use App\Models\Contribution;
use App\Models\Establishment;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class PromoteContributionToEstablishmentTest extends TestCase
{
    protected function tearDown(): void
    {
        Establishment::query()->delete();
        Contribution::query()->delete();
        parent::tearDown();
    }

    private function submitRealContribution(): Contribution
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.short_description_eng', 'A calm neighborhood spa.')
            ->set('state.description_eng', 'A calm neighborhood spa with full-service treatments.')
            ->set('state.type_spa', 'CS')
            ->set('state.status_establishment', 'OP')
            ->set('state.official_name', 'Harbor Calm Spa Inc.')
            ->set('state.country_id', 608)
            ->set('state.region_id', 1)
            ->set('state.city_name', 'Makati')
            ->set('state.street_address', '123 Bay Street')
            ->set('state.address_public', '123 Bay Street, Makati')
            ->set('state.coordinate_latitude', 14.5547)
            ->set('state.coordinate_longitude', 121.0244)
            ->call('addRow', 'contact_channel_list')
            ->set('state.contact_channel_list.0.type_contact_channel', 'EML')
            ->set('state.contact_channel_list.0.contact_label', 'Front desk')
            ->set('state.contact_channel_list.0.contact_value', 'hello@harborcalm.test')
            ->set('state.contact_channel_list.0.contact_url', '')
            ->call('save');

        return Contribution::query()->where('submitted_by_user_id', (string) $user->getKey())->firstOrFail();
    }

    public function test_promoting_a_real_contribution_creates_a_correctly_shaped_establishment(): void
    {
        $contribution = $this->submitRealContribution();

        $establishment = (new PromoteContributionToEstablishment)->execute($contribution);

        $this->assertInstanceOf(Establishment::class, $establishment);
        $this->assertTrue($establishment->exists);
        $this->assertSame('Harbor Calm Spa', $establishment->display_name['eng'] ?? null);
        $this->assertSame('A calm neighborhood spa.', $establishment->short_description['eng'] ?? null);
        $this->assertSame('A calm neighborhood spa with full-service treatments.', $establishment->description['eng'] ?? null);
        $this->assertSame('CS', $establishment->type_spa);
        $this->assertSame('Harbor Calm Spa Inc.', $establishment->official_name);
        $this->assertSame(608, $establishment->country_id);
        $this->assertSame('Makati', $establishment->city_name);
        $this->assertEqualsWithDelta(14.5547, $establishment->coordinate_latitude, 0.0001);
        $this->assertEqualsWithDelta(121.0244, $establishment->coordinate_longitude, 0.0001);
        $this->assertSame('EML', data_get($establishment->contact_channel_list, '0.type_contact_channel'));
        $this->assertNull($establishment->getAttribute('location_point'));
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run (from `apps/web/`): `php artisan test --filter PromoteContributionToEstablishmentTest`
Expected: FAIL — `App\Actions\Contribution\PromoteContributionToEstablishment` does not exist yet.

- [ ] **Step 3: Create the action**

Create `apps/web/app/Actions/Contribution/PromoteContributionToEstablishment.php`:

```php
<?php

namespace App\Actions\Contribution;

use App\Models\Contribution;
use App\Models\Establishment;

class PromoteContributionToEstablishment
{
    /** proposed_data.establishment key => Establishment model attribute, for the 3 translated fields. */
    private const TRANSLATED_FIELDS = [
        'display_name' => 'display_name',
        'short_description' => 'short_description',
        'full_description' => 'description',
    ];

    public function execute(Contribution $contribution): Establishment
    {
        $attributes = $contribution->proposed_data['establishment'] ?? [];

        foreach (self::TRANSLATED_FIELDS as $proposedKey => $modelField) {
            $translations = $attributes[$proposedKey] ?? [];
            unset($attributes[$proposedKey]);
            $attributes[$modelField] = collect($translations)
                ->map(fn (array $entry): string => (string) ($entry['text'] ?? ''))
                ->all();
        }

        $longitude = $attributes['location_point']['coordinates'][0] ?? null;
        $latitude = $attributes['location_point']['coordinates'][1] ?? null;
        unset($attributes['location_point']);
        if (is_numeric($longitude) && is_numeric($latitude)) {
            $attributes['coordinate_longitude'] = (float) $longitude;
            $attributes['coordinate_latitude'] = (float) $latitude;
        }

        if (isset($contribution->proposed_data['operating_schedule'])) {
            $attributes['operating_hours'] = $contribution->proposed_data['operating_schedule'];
        }

        if (isset($contribution->proposed_data['contact_channel_list'])) {
            $attributes['contact_channel_list'] = $contribution->proposed_data['contact_channel_list'];
        }

        return Establishment::query()->create($attributes);
    }
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --filter PromoteContributionToEstablishmentTest`
Expected: PASS. If it fails on a specific field, read the actual `$contribution->proposed_data` shape via `dd($contribution->proposed_data)` temporarily to see what `EstablishmentForm::submitContribution()` really produced for the fields in question (concurrent work may have shifted the exact shape since this plan was written) and adjust the action's mapping to match reality — the test's job is to catch exactly this kind of drift.

- [ ] **Step 5: Commit**

```bash
git add apps/web/app/Actions/Contribution/PromoteContributionToEstablishment.php apps/web/tests/Unit/Actions/PromoteContributionToEstablishmentTest.php
git commit -m "feat(contribution): add PromoteContributionToEstablishment action"
```

---

### Task 2: `ContributionIndex` — pending contributions list

**Files:**
- Create: `apps/web/app/Livewire/Workspace/Editorial/ContributionIndex.php`
- Create: `apps/web/resources/views/livewire/workspace/editorial/contribution-index.blade.php`
- Modify: `apps/web/routes/web.php`
- Modify: `apps/web/lang/eng/editorial.php`
- Test: `apps/web/tests/Feature/Editorial/ContributionReviewTest.php`

**Interfaces:**
- Consumes: `Contribution` model (existing), `WorkspaceAccess` (existing permission-check service, same one `ArticleReview` uses).
- Produces: route `workspace.editorial.contribution.index`, linked to by Task 3's detail links and Task 4's dashboard card.

- [ ] **Step 1: Write the failing test**

Read `apps/web/app/Livewire/Workspace/Editorial/ArticleReview.php` FULLY first (its `authorizeEditorialAccess()` method, the exact `WorkspaceAccess` injection/call pattern) and mirror its permission-check style exactly. Also read `apps/web/routes/web.php`'s `workspace/editorial` route group to confirm the current exact middleware chain and import-alias style before adding new routes/imports.

Create `apps/web/tests/Feature/Editorial/ContributionReviewTest.php`:

```php
<?php

namespace Tests\Feature\Editorial;

use App\Models\Contribution;
use App\Models\User;
use App\Models\UserAccess;
use Livewire\Livewire;
use App\Livewire\Workspace\Editorial\ContributionIndex;
use Tests\TestCase;

class ContributionReviewTest extends TestCase
{
    protected function tearDown(): void
    {
        Contribution::query()->delete();
        UserAccess::query()->delete();
        parent::tearDown();
    }

    private function editorialUser(): User
    {
        $user = User::factory()->create();
        UserAccess::query()->create([
            'user_id' => (string) $user->getKey(),
            'role_workspace' => 'EAD',
            'permission_code_list' => [],
            'scope_access' => 'GBL',
            'status_user_access' => 'ACT',
            'assigned_by_user_id' => (string) $user->getKey(),
            'assignment_reason' => 'Contribution review test.',
        ]);

        return $user;
    }

    private function pendingContribution(string $displayName = 'Test Spa'): Contribution
    {
        return Contribution::query()->create([
            'type_contribution' => 'ADD',
            'target_collection' => 'establishment_main',
            'submitted_by_user_id' => (string) User::factory()->create()->getKey(),
            'proposed_data' => ['establishment' => ['display_name' => ['eng' => ['text' => $displayName, 'method_translation' => 'HUM', 'status_review' => 'P']]]],
            'status_contribution' => 'PND',
            'submitted_at' => now(),
        ]);
    }

    public function test_non_editorial_user_cannot_access_the_contribution_list(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/workspace/editorial/contribution')
            ->assertForbidden();
    }

    public function test_editorial_user_sees_pending_contributions_only(): void
    {
        $pending = $this->pendingContribution('Pending Spa');
        $decided = $this->pendingContribution('Already Decided Spa');
        $decided->forceFill(['status_contribution' => 'APR'])->save();

        Livewire::actingAs($this->editorialUser())
            ->test(ContributionIndex::class)
            ->assertSee('Pending Spa')
            ->assertSee((string) $pending->getKey())
            ->assertDontSee('Already Decided Spa');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter ContributionReviewTest`
Expected: FAIL — `ContributionIndex` doesn't exist, route doesn't exist.

- [ ] **Step 3: Create the component**

Create `apps/web/app/Livewire/Workspace/Editorial/ContributionIndex.php`:

```php
<?php

namespace App\Livewire\Workspace\Editorial;

use App\Models\Contribution;
use App\Support\Workspace\WorkspaceAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ContributionIndex extends Component
{
    use WithPagination;

    public function mount(): void
    {
        $this->authorizeEditorialAccess();
    }

    private function authorizeEditorialAccess(): void
    {
        $user = Auth::user();
        abort_unless($user && app(WorkspaceAccess::class)->can($user, 'workspace.editorial.access'), 403);
    }

    public function render(): View
    {
        $contributions = Contribution::query()
            ->where('status_contribution', 'PND')
            ->where('type_contribution', 'ADD')
            ->where('target_collection', 'establishment_main')
            ->orderByDesc('submitted_at')
            ->paginate(15);

        return view('livewire.workspace.editorial.contribution-index', [
            'contributions' => $contributions,
        ])->title(__('editorial.contribution_review_title'));
    }
}
```

- [ ] **Step 4: Create the view**

Create `apps/web/resources/views/livewire/workspace/editorial/contribution-index.blade.php`. Read `apps/web/resources/views/livewire/workspace/editorial/article-index.blade.php` first for the established list-page layout convention (header, empty state, table/card rows, pagination) and match its structure. A minimal version:

```blade
<div>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ __('editorial.contribution_review_title') }}</h1>
    </div>

    @if ($contributions->isEmpty())
        <div class="rounded-2xl border border-ink-100 bg-white p-10 text-center shadow-sm dark:border-ink-800 dark:bg-ink-900">
            <p class="text-ink-600 dark:text-ink-300">{{ __('editorial.no_pending_contributions') }}</p>
        </div>
    @else
        <div class="divide-y divide-ink-100 rounded-2xl border border-ink-100 bg-white shadow-sm dark:divide-ink-800 dark:border-ink-800 dark:bg-ink-900">
            @foreach ($contributions as $contribution)
                @php($displayName = data_get($contribution->proposed_data, 'establishment.display_name.eng.text', __('editorial.untitled_contribution')))
                <a href="{{ route('workspace.editorial.contribution.review', $contribution) }}" wire:navigate class="flex items-center justify-between gap-4 p-5 transition hover:bg-ink-50/70 dark:hover:bg-ink-800/35">
                    <div>
                        <p class="font-black text-ink-950 dark:text-white">{{ $displayName }}</p>
                        <p class="mt-1 text-xs text-ink-500 dark:text-ink-400">{{ __('editorial.submitted', ['date' => $contribution->submitted_at?->format('M j, Y g:i A')]) }}</p>
                    </div>
                    @if (! empty($contribution->duplicate_candidate_establishment_id_list))
                        <span class="rounded-full bg-ember-50 px-2.5 py-1 text-xs font-bold text-ember-700 dark:bg-ember-950 dark:text-ember-300">{{ __('editorial.possible_duplicate') }}</span>
                    @endif
                </a>
            @endforeach
        </div>
        <div class="mt-6">{{ $contributions->links() }}</div>
    @endif
</div>
```

- [ ] **Step 5: Add the route**

In `apps/web/routes/web.php`, add the import alias near the other `Editorial\*` imports:

```php
use App\Livewire\Workspace\Editorial\ContributionIndex as EditorialContributionIndex;
```

Add inside the existing `workspace/editorial` route group (same group `article.index`/`article.review` are already in):

```php
        Route::get('/contribution', EditorialContributionIndex::class)->name('contribution.index');
```

- [ ] **Step 6: Add lang keys**

In `apps/web/lang/eng/editorial.php`, add:

```php
    'contribution_review_title' => 'Contribution Review',
    'no_pending_contributions' => 'No contributions are waiting for review.',
    'untitled_contribution' => 'Untitled submission',
    'submitted' => 'Submitted :date',
    'possible_duplicate' => 'Possible duplicate',
```

- [ ] **Step 7: Run test to verify it passes**

Run: `php artisan test --filter ContributionReviewTest`
Expected: PASS.

- [ ] **Step 8: Commit**

```bash
git add apps/web/app/Livewire/Workspace/Editorial/ContributionIndex.php apps/web/resources/views/livewire/workspace/editorial/contribution-index.blade.php apps/web/routes/web.php apps/web/lang/eng/editorial.php apps/web/tests/Feature/Editorial/ContributionReviewTest.php
git commit -m "feat(editorial): add pending-contributions list page"
```

---

### Task 3: `ContributionReview` — detail view + approve/reject

**Files:**
- Create: `apps/web/app/Livewire/Workspace/Editorial/ContributionReview.php`
- Create: `apps/web/resources/views/livewire/workspace/editorial/contribution-review.blade.php`
- Modify: `apps/web/routes/web.php`
- Modify: `apps/web/lang/eng/editorial.php`
- Test: `apps/web/tests/Feature/Editorial/ContributionReviewTest.php`

**Interfaces:**
- Consumes: `PromoteContributionToEstablishment` (Task 1), `Contribution` model, `WorkspaceAccess` (same pattern as Task 2).
- Produces: route `workspace.editorial.contribution.review`, linked to by Task 2's list rows.

- [ ] **Step 1: Write the failing tests**

Add to `apps/web/tests/Feature/Editorial/ContributionReviewTest.php` (the file created in Task 2):

```php
    public function test_approving_promotes_the_contribution_and_records_the_decision(): void
    {
        $reviewer = $this->editorialUser();
        $contribution = $this->pendingContribution();

        Livewire::actingAs($reviewer)
            ->test(\App\Livewire\Workspace\Editorial\ContributionReview::class, ['contribution' => (string) $contribution->getKey()])
            ->call('requestApproval')
            ->set('approvalConfirmed', true)
            ->call('approve')
            ->assertRedirect(route('workspace.editorial.contribution.index'));

        $contribution->refresh();
        $this->assertSame('APR', $contribution->status_contribution);
        $this->assertNotNull($contribution->reviewed_at);
        $this->assertSame((string) $reviewer->getKey(), $contribution->reviewer_user_id);
        $this->assertSame(1, \App\Models\Establishment::query()->where('display_name.eng', 'Test Spa')->count());
    }

    public function test_rejecting_requires_a_decision_note_and_creates_no_establishment(): void
    {
        $reviewer = $this->editorialUser();
        $contribution = $this->pendingContribution();

        Livewire::actingAs($reviewer)
            ->test(\App\Livewire\Workspace\Editorial\ContributionReview::class, ['contribution' => (string) $contribution->getKey()])
            ->call('reject')
            ->assertHasErrors('decisionNote');

        $this->assertSame(0, \App\Models\Establishment::query()->count());
    }

    public function test_rejecting_with_a_reason_records_the_decision(): void
    {
        $reviewer = $this->editorialUser();
        $contribution = $this->pendingContribution();

        Livewire::actingAs($reviewer)
            ->test(\App\Livewire\Workspace\Editorial\ContributionReview::class, ['contribution' => (string) $contribution->getKey()])
            ->set('decisionNote', 'Missing verifiable contact information.')
            ->call('reject')
            ->assertRedirect(route('workspace.editorial.contribution.index'));

        $contribution->refresh();
        $this->assertSame('REJ', $contribution->status_contribution);
        $this->assertSame('Missing verifiable contact information.', $contribution->decision_note);
        $this->assertSame(0, \App\Models\Establishment::query()->count());
    }

    public function test_a_decided_contribution_cannot_be_decided_again(): void
    {
        $reviewer = $this->editorialUser();
        $contribution = $this->pendingContribution();
        $contribution->forceFill(['status_contribution' => 'APR'])->save();

        Livewire::actingAs($reviewer)
            ->test(\App\Livewire\Workspace\Editorial\ContributionReview::class, ['contribution' => (string) $contribution->getKey()])
            ->set('decisionNote', 'Too late.')
            ->call('reject')
            ->assertStatus(409);
    }
```

- [ ] **Step 2: Run tests to verify they fail**

Run: `php artisan test --filter ContributionReviewTest`
Expected: the 4 new tests FAIL — `ContributionReview` doesn't exist yet.

- [ ] **Step 3: Create the component**

Read `apps/web/app/Livewire/Workspace/Editorial/ArticleReview.php` FULLY again for this step specifically — mirror its `decide()`/confirmation-flow structure closely, adapting field names (`decisionNote` not `reviewNote`, `reviewer_user_id` singular not `reviewer_user_id_list`, two decision codes not three, no publication side effects since Contribution has none).

Create `apps/web/app/Livewire/Workspace/Editorial/ContributionReview.php`:

```php
<?php

namespace App\Livewire\Workspace\Editorial;

use App\Actions\Contribution\PromoteContributionToEstablishment;
use App\Models\Contribution;
use App\Support\Workspace\WorkspaceAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class ContributionReview extends Component
{
    public string $contribution;

    public string $decisionNote = '';

    public bool $showApprovalConfirmation = false;

    public bool $approvalConfirmed = false;

    public function mount(string $contribution): void
    {
        $this->authorizeEditorialAccess();
        $this->contribution = $contribution;
    }

    private function authorizeEditorialAccess(): void
    {
        $user = Auth::user();
        abort_unless($user && app(WorkspaceAccess::class)->can($user, 'workspace.editorial.access'), 403);
    }

    private function pendingContribution(): Contribution
    {
        $contribution = Contribution::query()->findOrFail($this->contribution);
        abort_unless($contribution->status_contribution === 'PND', 409, __('editorial.contribution_already_decided'));

        return $contribution;
    }

    public function requestApproval(): void
    {
        $this->authorizeEditorialAccess();
        $this->pendingContribution();

        $this->resetErrorBag('approvalConfirmed');
        $this->approvalConfirmed = false;
        $this->showApprovalConfirmation = true;
    }

    public function cancelApproval(): void
    {
        $this->resetErrorBag('approvalConfirmed');
        $this->approvalConfirmed = false;
        $this->showApprovalConfirmation = false;
    }

    public function approve(): void
    {
        if (! $this->showApprovalConfirmation || ! $this->approvalConfirmed) {
            $this->addError('approvalConfirmed', __('editorial.approval_confirmation_required'));

            return;
        }

        $this->authorizeEditorialAccess();
        $contribution = $this->pendingContribution();

        (new PromoteContributionToEstablishment)->execute($contribution);

        $contribution->forceFill([
            'status_contribution' => 'APR',
            'decision_note' => trim($this->decisionNote) ?: null,
            'reviewed_at' => now(),
            'reviewer_user_id' => (string) Auth::id(),
        ])->save();

        session()->flash('editorial_status', __('editorial.contribution_approved'));
        $this->redirectRoute('workspace.editorial.contribution.index', navigate: true);
    }

    public function reject(): void
    {
        $this->validate(['decisionNote' => ['required', 'string', 'max:2000']]);

        $this->authorizeEditorialAccess();
        $contribution = $this->pendingContribution();

        $contribution->forceFill([
            'status_contribution' => 'REJ',
            'decision_note' => trim($this->decisionNote),
            'reviewed_at' => now(),
            'reviewer_user_id' => (string) Auth::id(),
        ])->save();

        session()->flash('editorial_status', __('editorial.contribution_rejected'));
        $this->redirectRoute('workspace.editorial.contribution.index', navigate: true);
    }

    public function render(): View
    {
        $contribution = Contribution::query()->findOrFail($this->contribution);

        return view('livewire.workspace.editorial.contribution-review', [
            'record' => $contribution,
        ])->title(__('editorial.contribution_review_title'));
    }
}
```

Note: `render()` deliberately does NOT call `pendingContribution()` (which aborts on non-'PND'), so the page can still render (read-only) for a contribution that was just decided — only the mutating actions (`requestApproval`/`approve`/`reject`) enforce the 409. Verify this matches the intent of `test_a_decided_contribution_cannot_be_decided_again` (that test only calls `reject()`, not a page load, so this design is consistent with it).

- [ ] **Step 4: Create the view**

Read `apps/web/resources/views/livewire/workspace/editorial/article-review.blade.php` FULLY for the established layout/modal/wire:confirm conventions and mirror them closely. Create `apps/web/resources/views/livewire/workspace/editorial/contribution-review.blade.php`:

```blade
<div>
    <a href="{{ route('workspace.editorial.contribution.index') }}" wire:navigate class="mb-4 inline-block text-sm font-semibold text-ink-600 hover:text-ember-700 dark:text-ink-300 dark:hover:text-ember-400">{{ __('editorial.back_to_contributions') }}</a>

    @php($displayName = data_get($record->proposed_data, 'establishment.display_name.eng.text', __('editorial.untitled_contribution')))
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_20rem]">
        <div class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
            <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ $displayName }}</h1>
            <p class="mt-1 text-sm text-ink-500 dark:text-ink-400">{{ __('editorial.submitted', ['date' => $record->submitted_at?->format('M j, Y g:i A')]) }}</p>

            @if (! empty($record->duplicate_candidate_establishment_id_list))
                <div class="mt-4 rounded-xl border border-ember-200 bg-ember-50 p-4 text-sm text-ember-900 dark:border-ember-800 dark:bg-ember-950/60 dark:text-ember-200">
                    {{ __('editorial.possible_duplicate') }}: {{ implode(', ', $record->duplicate_candidate_establishment_id_list) }}
                </div>
            @endif

            @if (filled($record->relationship_note))
                <p class="mt-4"><span class="font-bold">{{ __('editorial.relationship_note_label') }}:</span> {{ $record->relationship_note }}</p>
            @endif
            @if (filled($record->submission_note))
                <p class="mt-2"><span class="font-bold">{{ __('editorial.submission_note_label') }}:</span> {{ $record->submission_note }}</p>
            @endif
            @if ($record->is_visit_requested)
                <p class="mt-2"><span class="font-bold">{{ __('editorial.visit_requested_label') }}:</span> {{ $record->visit_preferred_time_note }}</p>
            @endif

            <pre class="mt-6 overflow-x-auto rounded-xl bg-ink-50 p-4 text-xs dark:bg-ink-950">{{ json_encode($record->proposed_data, JSON_PRETTY_PRINT) }}</pre>
        </div>

        <aside class="sticky top-6 self-start rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">
            <h2 class="font-black text-ink-950 dark:text-white">{{ __('editorial.decision_title') }}</h2>
            <label class="mt-4 block">
                <span class="text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">{{ __('editorial.decision_note_label') }}</span>
                <textarea wire:model="decisionNote" maxlength="2000" rows="4" class="mt-1 w-full rounded-xl border border-ink-200 px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white"></textarea>
                @error('decisionNote')<p class="mt-1 text-xs font-semibold text-ember-700 dark:text-ember-300">{{ $message }}</p>@enderror
            </label>

            <div class="mt-5 flex flex-col gap-2">
                <button type="button" wire:click="requestApproval" class="rounded-xl bg-leaf-500 px-4 py-2.5 text-sm font-bold text-white hover:bg-leaf-600">{{ __('editorial.approve') }}</button>
                <button type="button" wire:click="reject" wire:confirm="{{ __('editorial.reject_contribution_confirm') }}" class="rounded-xl border border-ember-300 bg-ember-50 px-4 py-2.5 text-sm font-bold text-ember-800 hover:bg-ember-100 dark:border-ember-800 dark:bg-ember-950 dark:text-ember-300">{{ __('editorial.reject') }}</button>
            </div>
        </aside>
    </div>

    @if ($showApprovalConfirmation)
        <div role="dialog" aria-modal="true" class="fixed inset-0 z-50 flex items-center justify-center bg-ink-950/50 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-ink-900">
                <h3 class="font-black text-ink-950 dark:text-white">{{ __('editorial.approval_confirmation_title') }}</h3>
                <p class="mt-2 text-sm text-ink-600 dark:text-ink-300">{{ __('editorial.approval_confirmation_body') }}</p>
                <label class="mt-4 flex items-start gap-2 text-sm">
                    <input type="checkbox" wire:model.live="approvalConfirmed" class="mt-0.5">
                    <span>{{ __('editorial.approval_confirmation_checkbox') }}</span>
                </label>
                @error('approvalConfirmed')<p class="mt-1 text-xs font-semibold text-ember-700 dark:text-ember-300">{{ $message }}</p>@enderror
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" wire:click="cancelApproval" class="rounded-xl px-4 py-2 text-sm font-bold text-ink-600 hover:bg-ink-100 dark:text-ink-300 dark:hover:bg-ink-800">{{ __('editorial.cancel') }}</button>
                    <button type="button" wire:click="approve" wire:loading.attr="disabled" wire:target="approve" @disabled(! $approvalConfirmed) class="rounded-xl bg-leaf-500 px-4 py-2 text-sm font-bold text-white hover:bg-leaf-600 disabled:opacity-50">{{ __('editorial.confirm_approval') }}</button>
                </div>
            </div>
        </div>
    @endif
</div>
```

- [ ] **Step 5: Add the route**

In `apps/web/routes/web.php`, add the import:

```php
use App\Livewire\Workspace\Editorial\ContributionReview as EditorialContributionReview;
```

Add inside the `workspace/editorial` group, after the `contribution.index` route added in Task 2:

```php
        Route::get('/contribution/{contribution}/review', EditorialContributionReview::class)->name('contribution.review');
```

- [ ] **Step 6: Add lang keys**

In `apps/web/lang/eng/editorial.php`, add:

```php
    'back_to_contributions' => 'Back to contributions',
    'relationship_note_label' => 'Relationship note',
    'submission_note_label' => 'Submission note',
    'visit_requested_label' => 'Visit requested',
    'decision_title' => 'Decision',
    'decision_note_label' => 'Decision note',
    'approve' => 'Approve',
    'reject' => 'Reject',
    'reject_contribution_confirm' => 'Reject this contribution? The submitter will see your decision note.',
    'approval_confirmation_title' => 'Confirm approval',
    'approval_confirmation_body' => 'Approving creates a new, publicly listed establishment from this submission.',
    'approval_confirmation_checkbox' => 'I have reviewed this submission and confirm it should be published.',
    'approval_confirmation_required' => 'Confirm the checkbox before approving.',
    'confirm_approval' => 'Confirm approval',
    'cancel' => 'Cancel',
    'contribution_approved' => 'Contribution approved and published.',
    'contribution_rejected' => 'Contribution rejected.',
    'contribution_already_decided' => 'This contribution has already been decided.',
```

- [ ] **Step 7: Run tests to verify they pass**

Run: `php artisan test --filter ContributionReviewTest`
Expected: all PASS (list tests from Task 2 + the 4 new ones).

- [ ] **Step 8: Commit**

```bash
git add apps/web/app/Livewire/Workspace/Editorial/ContributionReview.php apps/web/resources/views/livewire/workspace/editorial/contribution-review.blade.php apps/web/routes/web.php apps/web/lang/eng/editorial.php apps/web/tests/Feature/Editorial/ContributionReviewTest.php
git commit -m "feat(editorial): add contribution approve/reject review page"
```

---

### Task 4: Editorial dashboard link

**Files:**
- Modify: `apps/web/app/Livewire/Workspace/Editorial/EditorialHome.php`
- Modify: `apps/web/resources/views/livewire/workspace/editorial/editorial-home.blade.php`
- Modify: `apps/web/lang/eng/editorial.php`
- Test: `apps/web/tests/Feature/Editorial/ContributionReviewTest.php`

**Interfaces:**
- Consumes: `workspace.editorial.contribution.index` route (Task 2).

- [ ] **Step 1: Write the failing test**

Add to `apps/web/tests/Feature/Editorial/ContributionReviewTest.php`:

```php
    public function test_editorial_dashboard_shows_pending_contribution_count(): void
    {
        $this->pendingContribution();
        $this->pendingContribution();

        $this->actingAs($this->editorialUser())
            ->get('/workspace/editorial')
            ->assertOk()
            ->assertSee('2')
            ->assertSee(route('workspace.editorial.contribution.index'), false);
    }
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter test_editorial_dashboard_shows_pending_contribution_count`
Expected: FAIL — no contribution card/count currently rendered on the dashboard.

- [ ] **Step 3: Add the count**

In `apps/web/app/Livewire/Workspace/Editorial/EditorialHome.php`, read the current `render()` method fully first, then add a `contributionCount` entry to the returned view-data array, following the exact same "pending" pattern `articleCount` already uses:

```php
            'contributionCount' => Contribution::query()->where('status_contribution', 'PND')->where('type_contribution', 'ADD')->count(),
```

Add `use App\Models\Contribution;` to the file's imports.

- [ ] **Step 4: Add the dashboard card**

In `apps/web/resources/views/livewire/workspace/editorial/editorial-home.blade.php`, add a 5th entry to the existing `@foreach` array (alongside `articleCount`/`establishmentCount`/`serviceCount`/`quoteCount`):

```blade
                        ['count' => $contributionCount, 'label' => __('editorial.contribution_review_queue'), 'route' => route('workspace.editorial.contribution.index')],
```

Change the grid's column class from `xl:grid-cols-4` to `xl:grid-cols-5` to accommodate the 5th card (read the current class first to confirm exact current value before editing).

- [ ] **Step 5: Add the lang key**

In `apps/web/lang/eng/editorial.php`:

```php
    'contribution_review_queue' => 'Contributions to review',
```

- [ ] **Step 6: Run test to verify it passes**

Run: `php artisan test --filter test_editorial_dashboard_shows_pending_contribution_count`
Expected: PASS.

- [ ] **Step 7: Run the full editorial test suite to check for regressions**

Run: `php artisan test --filter ContributionReviewTest` and `php artisan test --filter EditorialHomeTest` (or whatever the existing dashboard test file is named — check `apps/web/tests/Feature/Editorial/` for it first)
Expected: all PASS.

- [ ] **Step 8: Commit**

```bash
git add apps/web/app/Livewire/Workspace/Editorial/EditorialHome.php apps/web/resources/views/livewire/workspace/editorial/editorial-home.blade.php apps/web/lang/eng/editorial.php apps/web/tests/Feature/Editorial/ContributionReviewTest.php
git commit -m "feat(editorial): show pending contribution count on the editorial dashboard"
```

---

## Final verification

- [ ] Run the full test suite: `php artisan test` (from `apps/web/`). Expected: all tests pass.
- [ ] Manual browser verification: as an editorial user, submit a real contribution (via the actual contribution form), see it appear on `/workspace/editorial` and `/workspace/editorial/contribution`, open it, approve it via the confirmation modal, confirm a new `Establishment` now exists and appears in `/workspace/editorial/establishment`, and confirm the contribution no longer appears in the pending list. Repeat with reject and confirm no `Establishment` is created and the decision note is visible if you look the contribution up directly.
