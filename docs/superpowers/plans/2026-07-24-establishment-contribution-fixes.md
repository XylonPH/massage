# Establishment Contribution Form Fixes Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Rename "Establishment Type" to "Type of Spa", fix the language-switcher duplication, expand the physical-premises gate to cover Amenities/Accessibility/Access-Model/Physical-Setting with immediate state-clearing, require a full address with privacy-aware public display, require at least one contact channel, and enforce parking mutual exclusivity — all on the establishment contribution form (`apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`).

**Architecture:** Small, targeted edits to one Livewire component, its Blade view partials, and one lang file. Reuses the component's existing `updatedState()` magic hook (already used for an analogous "clear dependent fields" case) rather than adding new per-field hook methods. No schema changes.

**Tech Stack:** Laravel, Livewire, MongoDB, PHPUnit (tests via `Livewire::test(EstablishmentForm::class)`, following the existing pattern in `apps/web/tests/Feature/Workspace/ContributionTest.php`).

## Global Constraints

- The database/model field is `type_spa`; only its lang-file *label* changes, not the field name.
- Taxonomy option wording (facility labels, parking option names) is already fixed in `data/taxonomy/massage_nexus/establishment_classification.json` and confirmed live via the taxonomy-driven `$taxonomy[...]` rendering — do not re-touch wording in this plan.
- `hasPhysicalPremises()` (`EstablishmentForm.php:188-194`) is the single source of truth for "does this establishment have a physical location" — all new gating in this plan keys off it, do not invent a parallel condition.
- `mode_access` is a single-select scalar field (not an array) — code `WI` means Walk-In.
- `parking_availability_list` is an array field; code `NONE` means "No Parking Available".
- Run tests from `apps/web/`: `php artisan test --filter <TestClass>`.
- This repo works directly on the `main` branch (no feature branches/worktrees), and every commit is auto-pushed to the real GitHub `origin/main` immediately — stage only the exact files each task names, never `git add -A`/`git add .`/`git add -u` (this repo has other concurrent agents — Codex, Antigravity — with real uncommitted work in the same tree; Antigravity was recently active in this exact file/view directory on Operating Hours UI, already merged as of this plan's base commit).

---

### Task 1: Rename "Establishment Type" → "Type of Spa"

**Files:**
- Modify: `apps/web/lang/eng/editorial.php`
- Test: `apps/web/tests/Feature/Workspace/ContributionTest.php`

**Interfaces:**
- Produces: nothing consumed by later tasks — fully independent, safe to do first.

- [ ] **Step 1: Write the failing test**

Add to `apps/web/tests/Feature/Workspace/ContributionTest.php` (inside the existing test class, near other simple rendering tests):

```php
    public function test_classification_tab_shows_type_of_spa_not_establishment_type(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/workspace/contribution/establishment/new')
            ->assertOk()
            ->assertSee('Type of Spa')
            ->assertDontSee('Establishment Type');
    }
```

- [ ] **Step 2: Run test to verify it fails**

Run (from `apps/web/`): `php artisan test --filter test_classification_tab_shows_type_of_spa_not_establishment_type`
Expected: FAIL — page currently shows "Establishment Type".

- [ ] **Step 3: Change the label**

In `apps/web/lang/eng/editorial.php`, change:

```php
    'est_type_spa' => 'Establishment Type',
```

to:

```php
    'est_type_spa' => 'Type of Spa',
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --filter test_classification_tab_shows_type_of_spa_not_establishment_type`
Expected: PASS

- [ ] **Step 5: Commit**

```bash
git add apps/web/lang/eng/editorial.php apps/web/tests/Feature/Workspace/ContributionTest.php
git commit -m "fix(establishment-form): rename Establishment Type label to Type of Spa"
```

---

### Task 2: Language switcher — single dropdown, rendered once

**Files:**
- Modify: `apps/web/resources/views/livewire/workspace/establishment-form/_language-switcher.blade.php`
- Modify: `apps/web/resources/views/livewire/workspace/establishment-form/_tab-identity.blade.php`
- Modify: `apps/web/resources/views/livewire/workspace/establishment-form/_tab-location.blade.php`
- Modify: `apps/web/resources/views/livewire/workspace/editorial/establishment-form.blade.php`
- Test: `apps/web/tests/Feature/Workspace/ContributionTest.php`

**Interfaces:**
- Consumes: `$activeLanguageTab` (existing public property on `EstablishmentForm`, `EstablishmentForm.php:63`) — unchanged.
- Produces: nothing consumed by later tasks.

- [ ] **Step 1: Write the failing test**

Add to `apps/web/tests/Feature/Workspace/ContributionTest.php`:

```php
    public function test_language_switcher_renders_exactly_once_not_once_per_tab(): void
    {
        $html = $this->actingAs(User::factory()->create())
            ->get('/workspace/contribution/establishment/new')
            ->getContent();

        $this->assertSame(1, substr_count($html, 'aria-label="Language"'));
    }
```

(This assumes the fixed switcher carries a single, tab-independent `aria-label="Language"` — see Step 3's exact markup, which sets this.)

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter test_language_switcher_renders_exactly_once_not_once_per_tab`
Expected: FAIL — currently 0 matches (the old markup uses a per-tab `aria-label="{{ $switcherLabel }} language"`, not the literal string this test looks for) or the count doesn't match what Step 3 will produce. Confirm it fails for the expected reason (wrong/missing `aria-label`) before proceeding.

- [ ] **Step 3: Convert the switcher to a dropdown**

Replace the full contents of `apps/web/resources/views/livewire/workspace/establishment-form/_language-switcher.blade.php`:

```blade
{{--
    Single, page-level language switcher for the translatable Identity/Location fields
    (display_name, short_description, description, direction_note, parking_note).
    $activeLanguageTab lives on the EstablishmentForm component. Rendered exactly once
    per establishment-form.blade.php, above the tab content — NOT included per-tab
    (a prior version included this partial separately in both the Identity and Location
    tabs, rendering the same control twice; fixed 2026-07-24).
--}}
<div class="mb-4">
    <label for="establishment-language-switcher" class="sr-only">{{ __('editorial.language_switcher_label') }}</label>
    <select id="establishment-language-switcher" wire:model.live="activeLanguageTab" aria-label="Language"
            class="w-full max-w-xs rounded-lg border border-ink-200 px-3 py-2 text-sm dark:border-ink-700 dark:bg-ink-950 dark:text-white">
        @foreach (['eng', 'fil', 'spa', 'kor', 'zho_hant', 'zho_hans'] as $lang)
            <option value="{{ $lang }}" @selected($activeLanguageTab === $lang)>{{ __('editorial.lang_'.$lang) }}</option>
        @endforeach
    </select>
</div>
```

Add the new lang key to `apps/web/lang/eng/editorial.php`:

```php
    'language_switcher_label' => 'Editing language',
```

- [ ] **Step 4: Remove the per-tab includes**

In `apps/web/resources/views/livewire/workspace/establishment-form/_tab-identity.blade.php`, remove line 3 (or wherever it currently is — read the file first to confirm the exact current line):

```blade
    @include('livewire.workspace.establishment-form._language-switcher', ['switcherLabel' => __('editorial.tab_identity')])
```

In `apps/web/resources/views/livewire/workspace/establishment-form/_tab-location.blade.php`, remove the equivalent include (currently around line 69 — confirm exact current line first).

- [ ] **Step 5: Add a single include at the spa-details-step level**

In `apps/web/resources/views/livewire/workspace/editorial/establishment-form.blade.php`, find where the tab content area begins (the `<div x-data="{ tab: $wire.entangle('activeDetailTab'), ... }"` block or wherever the 9 tab panels start rendering — read the file first to find the exact current structure) and add, immediately before the tab panels' content (but after the tab-bar navigation), a single:

```blade
@include('livewire.workspace.establishment-form._language-switcher')
```

Place it so it's visible regardless of which detail tab is active (i.e., outside any individual tab's `x-show`/`@if` block, but still only within the "spa details" step 2 — not on step 1 "who you are" or step 3 "review and submit").

- [ ] **Step 6: Run test to verify it passes**

Run: `php artisan test --filter test_language_switcher_renders_exactly_once_not_once_per_tab`
Expected: PASS

- [ ] **Step 7: Run the full contribution test suite to check for regressions**

Run: `php artisan test --filter ContributionTest`
Expected: all PASS. If any test relied on `wire:click="$set('activeLanguageTab', ...)"` button interaction (the old pill-button markup), it will need updating to use `->set('activeLanguageTab', ...)` directly on the Livewire test instance instead (the dropdown's `wire:model.live` achieves the same state change without a dedicated click-handler method) — check for any such test and adjust if found.

- [ ] **Step 8: Commit**

```bash
git add apps/web/resources/views/livewire/workspace/establishment-form/_language-switcher.blade.php apps/web/resources/views/livewire/workspace/establishment-form/_tab-identity.blade.php apps/web/resources/views/livewire/workspace/establishment-form/_tab-location.blade.php apps/web/resources/views/livewire/workspace/editorial/establishment-form.blade.php apps/web/lang/eng/editorial.php apps/web/tests/Feature/Workspace/ContributionTest.php
git commit -m "fix(establishment-form): replace duplicated per-tab language pill-buttons with one page-level dropdown"
```

---

### Task 3: Expand the physical-premises gate (tabs + immediate state-clearing + mode_access + physical setting)

**Files:**
- Modify: `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`
- Modify: `apps/web/resources/views/livewire/workspace/editorial/establishment-form.blade.php`
- Test: `apps/web/tests/Feature/Workspace/ContributionTest.php`

**Interfaces:**
- Consumes: `hasPhysicalPremises()` (existing method, `EstablishmentForm.php:188-194`, unchanged), `updatedState()` (existing magic hook, `EstablishmentForm.php:196-202`, extended in this task).
- Produces: nothing consumed by later tasks.

- [ ] **Step 1: Write the failing tests**

Add to `apps/web/tests/Feature/Workspace/ContributionTest.php`:

```php
    public function test_amenities_and_accessibility_tabs_hidden_for_home_service_only_spa(): void
    {
        $html = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.type_spa', 'HP')
            ->set('state.mode_service_delivery', ['HM'])
            ->html();

        $this->assertStringNotContainsString(__('editorial.tab_amenities'), $html);
        $this->assertStringNotContainsString(__('editorial.tab_accessibility'), $html);
    }

    public function test_amenities_and_accessibility_tabs_visible_when_physical_premises_exist(): void
    {
        $html = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.type_spa', 'CS')
            ->html();

        $this->assertStringContainsString(__('editorial.tab_amenities'), $html);
        $this->assertStringContainsString(__('editorial.tab_accessibility'), $html);
    }

    public function test_switching_to_home_service_only_immediately_clears_already_entered_amenities(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.type_spa', 'CS')
            ->set('state.amenity_list', ['WFI'])
            ->set('state.mode_access', 'WI');

        $this->assertSame(['WFI'], $test->get('state.amenity_list'));
        $this->assertSame('WI', $test->get('state.mode_access'));

        $test->set('state.type_spa', 'HP')->set('state.mode_service_delivery', ['HM']);

        $this->assertSame([], $test->get('state.amenity_list'));
        $this->assertNull($test->get('state.mode_access'));
    }

    public function test_walk_in_access_cleared_for_home_service_only_but_other_access_modes_unaffected(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.type_spa', 'HP')
            ->set('state.mode_service_delivery', ['HM'])
            ->set('state.mode_access', 'AR');

        $this->assertSame('AR', $test->get('state.mode_access'));
    }
```

Note: use `'CS'` (or whichever real non-home-service `type_spa` code exists in the taxonomy — check `data/taxonomy/massage_nexus/establishment_classification.json`'s `type_spa` field options before writing this test; substitute the correct code for "a standard fixed-premises spa type" if `CS` isn't real) for the "physical premises exist" tests.

- [ ] **Step 2: Run tests to verify they fail**

Run: `php artisan test --filter ContributionTest`
Expected: the 4 new tests FAIL (amenities/accessibility tabs currently always render regardless of `hasPhysicalPremises()`; `updatedState()` doesn't currently clear anything on `type_spa`/`mode_service_delivery`/`mode_access` changes).

- [ ] **Step 3: Extend the tab-visibility gate**

In `apps/web/resources/views/livewire/workspace/editorial/establishment-form.blade.php`, find the tab-array block (around where `'facilities' => $this->hasPhysicalPremises() ? __('editorial.tab_facilities') : null,` appears) and change the two lines immediately after it:

```blade
                        'amenities' => __('editorial.tab_amenities'),
                        'accessibility' => __('editorial.tab_accessibility'),
```

to:

```blade
                        'amenities' => $this->hasPhysicalPremises() ? __('editorial.tab_amenities') : null,
                        'accessibility' => $this->hasPhysicalPremises() ? __('editorial.tab_accessibility') : null,
```

- [ ] **Step 4: Extend `updatedState()` to clear dependent fields immediately**

In `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`, replace the current `updatedState()` method:

```php
    public function updatedState(mixed $value, string $key): void
    {
        if (preg_match('/^operating_hours\.(\d+)\.is_closed$/', $key, $matches) && $value) {
            $this->state['operating_hours'][(int) $matches[1]]['open_time'] = null;
            $this->state['operating_hours'][(int) $matches[1]]['close_time'] = null;
        }
    }
```

with:

```php
    public function updatedState(mixed $value, string $key): void
    {
        if (preg_match('/^operating_hours\.(\d+)\.is_closed$/', $key, $matches) && $value) {
            $this->state['operating_hours'][(int) $matches[1]]['open_time'] = null;
            $this->state['operating_hours'][(int) $matches[1]]['close_time'] = null;
        }

        if (in_array($key, ['type_spa', 'mode_service_delivery'], true) && ! $this->hasPhysicalPremises()) {
            $this->clearPhysicalPremisesState();
        }

        if ($key === 'mode_access' && $value === 'WI' && ! $this->hasPhysicalPremises()) {
            $this->state['mode_access'] = null;
        }
    }

    private function clearPhysicalPremisesState(): void
    {
        foreach (self::PHYSICAL_PREMISES_FIELDS as $field) {
            $this->state[$field] = in_array($field, self::LIST_FIELDS, true) ? [] : null;
        }
        $this->state['treatment_area_list'] = [];
        if ($this->state['mode_access'] === 'WI') {
            $this->state['mode_access'] = null;
        }
        $this->state['type_physical_setting'] = null;
    }
```

(Task 5 adds a separate `parking_availability_list` branch and its supporting method to this same `updatedState()` — that task owns the parking logic entirely, this task only adds the two blocks shown above.)

Also update `save()`/`submitContribution()`'s existing inline clearing block (the one this task's `clearPhysicalPremisesState()` now duplicates in spirit) to call the new shared method instead of repeating the loop — find the existing `if (! $this->hasPhysicalPremises()) { foreach (self::PHYSICAL_PREMISES_FIELDS as $field) { ... } }` blocks (there are two, in `save()` and `submitContribution()`) and replace each with a call to `$this->clearPhysicalPremisesState();` to avoid the two copies drifting apart.

- [ ] **Step 5: Run tests to verify they pass**

Run: `php artisan test --filter ContributionTest`
Expected: all PASS (the 4 new tests in this task don't touch parking at all, so they're unaffected by Task 5's separate work).

- [ ] **Step 6: Commit**

```bash
git add apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php apps/web/resources/views/livewire/workspace/editorial/establishment-form.blade.php apps/web/tests/Feature/Workspace/ContributionTest.php
git commit -m "fix(establishment-form): hide amenities/accessibility for home-service-only spas and clear dependent state immediately on type change"
```

---

### Task 4: Address required + privacy-aware public composition + official_name relocation

**Files:**
- Modify: `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`
- Modify: `apps/web/resources/views/livewire/workspace/establishment-form/_tab-location.blade.php`
- Modify: `apps/web/resources/views/livewire/workspace/establishment-form/_tab-identity.blade.php`
- Test: `apps/web/tests/Feature/Workspace/ContributionTest.php`

**Interfaces:**
- Consumes: `hasPhysicalPremises()`, `composeAddressPublic()` (existing method, `EstablishmentForm.php:261-272`, modified in this task).
- Produces: nothing consumed by later tasks.

- [ ] **Step 1: Write the failing tests**

Add to `apps/web/tests/Feature/Workspace/ContributionTest.php`:

```php
    public function test_submission_requires_full_address(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Test Spa')
            ->set('state.type_spa', 'CS')
            ->set('state.status_establishment', 'OP')
            ->call('save');

        $test->assertHasErrors([
            'state.official_name', 'state.country_id', 'state.region_id',
            'state.city_name', 'state.street_address', 'state.address_public',
        ]);
    }

    public function test_compose_address_public_excludes_street_detail_for_home_service_only_spa(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.type_spa', 'HP')
            ->set('state.mode_service_delivery', ['HM'])
            ->set('state.street_address', '123 Test Street')
            ->set('state.city_name', 'Makati City')
            ->set('state.country_id', 1)
            ->call('composeAddressPublic');

        $this->assertStringNotContainsString('123 Test Street', $test->get('state.address_public'));
        $this->assertStringContainsString('Makati City', $test->get('state.address_public'));
    }

    public function test_compose_address_public_includes_street_detail_for_fixed_premises_spa(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.type_spa', 'CS')
            ->set('state.street_address', '123 Test Street')
            ->set('state.city_name', 'Makati City')
            ->set('state.country_id', 1)
            ->call('composeAddressPublic');

        $this->assertStringContainsString('123 Test Street', $test->get('state.address_public'));
    }

    public function test_official_name_validation_error_opens_identity_tab_not_location(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.type_spa', 'CS')
            ->call('save');

        $test->assertHasErrors('state.official_name');
        $this->assertSame('identity', $test->get('activeDetailTab'));
    }
```

Adjust `country_id`/`region_id` values in the address tests to real IDs from `AddressLookup`'s reference data if `1` isn't a valid country ID — check `app/Support/Establishment/AddressLookup.php` (or wherever it lives) and the reference database for a real usable country ID before finalizing this step; use the same country/region IDs the existing duplicate-candidate or visit-eligibility tests in this file already use, if any, for consistency.

- [ ] **Step 2: Run tests to verify they fail**

Run: `php artisan test --filter ContributionTest`
Expected: the 4 new tests FAIL (address fields currently nullable; `composeAddressPublic()` currently always includes street_address; `official_name` still renders on the Location tab, so `tabForField()` would currently route its error to `'location'`, not `'identity'`).

- [ ] **Step 3: Add required validation**

In `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`'s `rules()` method, change:

```php
            'state.official_name' => ['nullable', 'string', 'max:255'],
            'state.country_id' => ['nullable', 'integer'],
            'state.region_id' => ['nullable', 'integer'],
            'state.city_name' => ['nullable', 'string', 'max:255'],
            'state.street_address' => ['nullable', 'string', 'max:255'],
```

to:

```php
            'state.official_name' => ['required', 'string', 'max:255'],
            'state.country_id' => ['required', 'integer'],
            'state.region_id' => ['required', 'integer'],
            'state.city_name' => ['required', 'string', 'max:255'],
            'state.street_address' => ['required', 'string', 'max:255'],
```

Find the `address_public` rule (search for `'state.address_public'` in `rules()`) and change it from `nullable` to `required` (read the file first to see its exact current rule array before editing).

- [ ] **Step 4: Fix `composeAddressPublic()` to respect physical-premises privacy**

Replace:

```php
    public function composeAddressPublic(): void
    {
        $lookup = app(AddressLookup::class);
        $parts = array_filter([
            $this->state['street_address'] ?? null,
            $this->state['city_name'] ?? null,
            filled($this->state['region_id'] ?? null) ? ($lookup->regions((int) $this->state['country_id'])[(string) $this->state['region_id']] ?? null) : null,
            filled($this->state['country_id'] ?? null) ? ($lookup->countries()[(string) $this->state['country_id']] ?? null) : null,
        ]);

        $this->state['address_public'] = implode(', ', $parts);
    }
```

with:

```php
    public function composeAddressPublic(): void
    {
        $lookup = app(AddressLookup::class);
        $parts = array_filter([
            $this->hasPhysicalPremises() ? ($this->state['street_address'] ?? null) : null,
            $this->state['city_name'] ?? null,
            filled($this->state['region_id'] ?? null) ? ($lookup->regions((int) $this->state['country_id'])[(string) $this->state['region_id']] ?? null) : null,
            filled($this->state['country_id'] ?? null) ? ($lookup->countries()[(string) $this->state['country_id']] ?? null) : null,
        ]);

        $this->state['address_public'] = implode(', ', $parts);
    }
```

- [ ] **Step 5: Relocate `official_name`**

Read `apps/web/resources/views/livewire/workspace/establishment-form/_tab-location.blade.php` fully to find the exact current `official_name` field markup (an `<x-form.field>`/`<x-form.input>` pair bound to `state.official_name`). Cut that block out of `_tab-location.blade.php` and paste it into `_tab-identity.blade.php`, placed near the top (before or after the display_name fields — read that file's existing layout and place it in a sensible position, e.g. immediately after the language switcher's old position or right before display_name).

In `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`'s `tabForField()` method, change:

```php
            preg_match('/^(official_name|country_id|region_id|city_name|street_address|building_name|floor_label|unit_label|postal_code|address_public|coordinate_|direction_note_|parking_|landmark_list)/', $field) === 1 => 'location',
```

to (removing `official_name|` from the pattern):

```php
            preg_match('/^(country_id|region_id|city_name|street_address|building_name|floor_label|unit_label|postal_code|address_public|coordinate_|direction_note_|parking_|landmark_list)/', $field) === 1 => 'location',
```

- [ ] **Step 6: Run tests to verify they pass**

Run: `php artisan test --filter ContributionTest`
Expected: all PASS.

- [ ] **Step 7: Run the full establishment/contribution test suites to check for regressions**

Run: `php artisan test --filter ContributionTest` and `php artisan test --filter EstablishmentCrudTest` and `php artisan test --filter ContributionPayloadShapeTest`
Expected: all PASS. Pay particular attention to any existing test that submits a minimal/valid establishment payload without a full address — those will now need `official_name`/`country_id`/`region_id`/`city_name`/`street_address`/`address_public` added to stay valid; update them if found (this is an expected consequence of adding `required`, not a regression to work around).

- [ ] **Step 8: Commit**

```bash
git add apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php apps/web/resources/views/livewire/workspace/establishment-form/_tab-location.blade.php apps/web/resources/views/livewire/workspace/establishment-form/_tab-identity.blade.php apps/web/tests/Feature/Workspace/ContributionTest.php
git commit -m "fix(establishment-form): require full address, keep street detail private for home-service-only spas, move official name to Identity"
```

(If Step 7 required updating other test files, e.g. `EstablishmentCrudTest.php` or `ContributionPayloadShapeTest.php`, add those exact paths to this `git add` line too.)

---

### Task 5: Contact required + parking mutual exclusivity

**Files:**
- Modify: `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`
- Test: `apps/web/tests/Feature/Workspace/ContributionTest.php`

**Interfaces:**
- Consumes: `updatedState()` (extended in Task 3 — this task fills in the `enforceParkingMutualExclusivity()` method that Task 3 forward-referenced).
- Produces: nothing consumed by later tasks (this is the last task).

- [ ] **Step 1: Write the failing tests**

Add to `apps/web/tests/Feature/Workspace/ContributionTest.php`:

```php
    public function test_submission_requires_at_least_one_contact_channel(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Test Spa')
            ->set('state.type_spa', 'CS')
            ->set('state.status_establishment', 'OP')
            ->call('save');

        $test->assertHasErrors('state.contact_channel_list');
    }

    public function test_no_parking_available_is_dropped_once_another_option_is_also_present(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.parking_availability_list', ['PRK_ONS_FREE', 'NONE']);

        $this->assertSame(['PRK_ONS_FREE'], $test->get('state.parking_availability_list'));
    }

    public function test_no_parking_available_is_dropped_regardless_of_which_option_arrived_second(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.parking_availability_list', ['NONE', 'PRK_STR']);

        $this->assertSame(['PRK_STR'], $test->get('state.parking_availability_list'));
    }

    public function test_no_parking_available_alone_is_left_untouched(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.parking_availability_list', ['NONE']);

        $this->assertSame(['NONE'], $test->get('state.parking_availability_list'));
    }
```

- [ ] **Step 2: Run tests to verify they fail**

Run: `php artisan test --filter ContributionTest`
Expected: the 3 new tests FAIL (`contact_channel_list` currently has no `min:1`; parking mutual exclusivity isn't enforced anywhere yet — `enforceParkingMutualExclusivity()` doesn't exist).

- [ ] **Step 3: Add the contact requirement**

In `rules()`, change:

```php
            'state.contact_channel_list' => ['array', 'max:20'],
```

to:

```php
            'state.contact_channel_list' => ['array', 'min:1', 'max:20'],
```

- [ ] **Step 4: Add parking mutual exclusivity**

Add a new branch to `updatedState()` (which Task 3 already extended with the `type_spa`/`mode_service_delivery`/`mode_access` blocks — add this alongside them, inside the same method):

```php
        if ($key === 'parking_availability_list') {
            $this->enforceParkingMutualExclusivity($value);
        }
```

inside `updatedState()`, alongside the other `if` blocks.

Add the new method (place it near `clearPhysicalPremisesState()`):

```php
    private function enforceParkingMutualExclusivity(mixed $value): void
    {
        if (! is_array($value) || count($value) <= 1 || ! in_array('NONE', $value, true)) {
            return;
        }

        $this->state['parking_availability_list'] = array_values(array_filter($value, fn (string $option): bool => $option !== 'NONE'));
    }
```

This applies one deterministic rule, independent of click order: **"No Parking Available" is suppressed whenever any other parking option is also present.** It doesn't matter whether `NONE` or the other option was selected first — the resulting array never contains both. This sidesteps needing to know which checkbox was "just" toggled (Livewire's own checkbox-array binding already computes the full new array before `updatedState()` fires; this method only needs to look at that final array, not the history of how it got there).

Note the field is rendered via `<x-form.toggle-group :options="$taxonomy['parking_availability']" model="state.parking_availability_list" :icons="$parkingIcons" />` (`_tab-location.blade.php`) — a group of `<input type="checkbox" wire:model="{{ $model }}">` elements sharing one array-typed model, Livewire's standard checkbox-group pattern. `updatedState()` receives the complete resulting array as `$value` once Livewire applies a toggle, which is exactly what this method expects.

- [ ] **Step 5: Run tests to verify they pass**

Run: `php artisan test --filter ContributionTest`
Expected: all PASS, including whichever corrected version of the two parking tests resulted from Step 4's investigation.

- [ ] **Step 6: Run the full establishment/contribution test suites to check for regressions**

Run: `php artisan test --filter ContributionTest` and `php artisan test --filter EstablishmentCrudTest` and `php artisan test --filter ContributionPayloadShapeTest`
Expected: all PASS (watch for any existing test submitting a valid payload with zero contact channels, which will now need at least one added).

- [ ] **Step 7: Commit**

```bash
git add apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php apps/web/tests/Feature/Workspace/ContributionTest.php
git commit -m "fix(establishment-form): require at least one contact channel, enforce parking mutual exclusivity"
```

---

## Final verification

- [ ] Run the full test suite: `php artisan test` (from `apps/web/`). Expected: all tests pass.
- [ ] Run `npm run build` (from `apps/web/`) to confirm no Blade/asset compilation issues (unlikely to be needed for pure Blade+PHP changes, but confirm no JS was inadvertently touched).
- [ ] Manual browser verification: open the contribution form, confirm "Type of Spa" label, confirm the language dropdown appears once (not per-tab) and switching it updates fields on both Identity and Location tabs, select Home Service Provider and confirm Amenities/Accessibility tabs disappear and any previously-entered data in them is gone, confirm Walk-in becomes unavailable/cleared for Access Model, confirm official_name now appears on Identity tab, submit without an address/contact and confirm validation errors, select "No Parking Available" then another parking option and confirm the exclusivity behavior matches whatever Task 5 Step 4 determined is correct.
