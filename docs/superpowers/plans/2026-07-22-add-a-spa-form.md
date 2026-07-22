# Add a Spa Form Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Redesign `/workspace/contribution/establishment/new` into a guide-conformant, 3-step "Add a Spa" wizard, and fix the governance gaps (missing facility fields, missing `contribution_main` structure guide) that the current form's data shape violates.

**Architecture:** One shared Livewire component (`App\Livewire\Workspace\Editorial\EstablishmentForm`) keeps its two existing modes — contribution (member-facing) and editorial (direct-edit) — but contribution mode gains a 3-step wizard (`currentStep`: 1 = who you are, 2 = spa details tabs, 3 = review and submit) with per-step server validation. New read-only Eloquent models connect to the already-populated `common_reference` MongoDB database for country/region selects. A `DuplicateEstablishmentFinder` service and a `EstablishmentContributionSubmitted` event are added as clean, independently testable units.

**Tech Stack:** Laravel 13, Livewire 4, MongoDB (`mongodb/laravel-mongodb`), Alpine.js, Tailwind v4, Leaflet (new npm dependency) via Vite.

## Global Constraints

- Every taxonomy-backed field must be validated server-side with `Rule::in()` against its taxonomy options (spec section 8) — this plan closes every gap found.
- `proposed_data.establishment` must only ever contain keys documented in `data/structure_guide/establishment_main.php`'s field order, excluding system-owned fields (`_id`, `establishment_slug`, `previous_slug_list`, `status_record_lifecycle`, `revision_number`, `created_at`, `updated_at`, `last_confirmed_at`) — enforced by a drift-guard test (spec section 9).
- No taxonomy option code is ever renamed or reused; only the taxonomy **field_name** `amenities` → `amenity_list` and `accessibility_information` → `accessibility_feature_list` are corrected to match the guide (spec section 1).
- `status_record_lifecycle` is never shown or settable in contribution mode; server always writes `ACT` for new proposals (spec section 3).
- User-facing text uses translation keys (`lang/eng/workspace.php`, `lang/eng/editorial.php`) — no hardcoded strings in Blade.
- Conditional field groups (facility fields when home-service-only, closed-date fields when not closed) are stripped server-side in addition to being hidden client-side — never trust hidden state alone (spec section 8).
- Auth gates (`auth`, `verified`, `EnsureActiveMember`) and the existing rate limiter on contribution submission are preserved unchanged.
- Run `composer test` (or the narrower `php artisan test --filter=<Test>` while developing) and `vendor/bin/pint --test` on every touched file before each commit, from `apps/web/`.
- Before touching shared files (`config/database.php`, `routes/web.php`, taxonomy JSON, `data/field_index.txt`), run `git status` — other agents (Antigravity, Codex, Grok Build) may be working in this repo concurrently. Re-pull/re-check immediately before editing, keep diffs to exactly what this plan specifies.

---

## File Structure

**New files:**
- `apps/web/app/Models/Reference/Country.php` — read-only model, `mongodb_reference` connection, `country_main`
- `apps/web/app/Models/Reference/Region.php` — read-only model, `mongodb_reference` connection, `region_main`
- `apps/web/app/Models/Reference/City.php` — read-only model, `mongodb_reference` connection, `city_main` (collection empty until the separate PH Geographic Reference Data project lands; querying it safely returns no rows)
- `apps/web/app/Console/Commands/SyncReferenceData.php` — `reference:sync` artisan command
- `apps/web/app/Support/Address/AddressLookup.php` — `regions(int $countryId): array`, `cities(int $regionId): array` (graceful empty-array degradation)
- `apps/web/app/Support/Establishment/DuplicateEstablishmentFinder.php`
- `apps/web/app/Events/EstablishmentContributionSubmitted.php`
- `apps/web/resources/js/establishment-map.js` — Leaflet map-pin picker, Livewire-bridged
- `apps/web/resources/views/livewire/workspace/establishment-form/_who-you-are.blade.php`
- `apps/web/resources/views/livewire/workspace/establishment-form/_tab-identity.blade.php`
- `apps/web/resources/views/livewire/workspace/establishment-form/_tab-classification.blade.php`
- `apps/web/resources/views/livewire/workspace/establishment-form/_tab-access.blade.php`
- `apps/web/resources/views/livewire/workspace/establishment-form/_tab-location.blade.php`
- `apps/web/resources/views/livewire/workspace/establishment-form/_tab-contact.blade.php`
- `apps/web/resources/views/livewire/workspace/establishment-form/_tab-hours.blade.php`
- `apps/web/resources/views/livewire/workspace/establishment-form/_tab-facilities.blade.php`
- `apps/web/resources/views/livewire/workspace/establishment-form/_tab-amenities.blade.php`
- `apps/web/resources/views/livewire/workspace/establishment-form/_review-submit.blade.php`
- `data/structure_guide/contribution_main.php` — did not exist; created from the actual `Contribution` model shape plus new fields
- `docs/06-user-interface/add-spa-form-ui.txt`

**Modified files:**
- `data/structure_guide/establishment_main.php` — version bump, facility fields, `parking_availability_list`, opened/closed date fields
- `data/taxonomy/massage_nexus/establishment_classification.json` — field-name corrections + new `parking_availability` field
- `data/field_index.txt` — regenerated
- `apps/web/config/database.php` — new `mongodb_reference` connection
- `apps/web/.env` / `apps/web/.env.example` — `MONGODB_REFERENCE_DATABASE`
- `apps/web/phpunit.xml` — `MONGODB_REFERENCE_DATABASE` test value
- `apps/web/app/Models/Contribution.php` — new fillable/casts
- `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php` — full rewrite of contribution-mode behavior
- `apps/web/resources/views/livewire/workspace/editorial/establishment-form.blade.php` — replaced by a slim shell that includes the new partials
- `apps/web/lang/eng/workspace.php`, `apps/web/lang/eng/editorial.php` — new/changed keys
- `apps/web/package.json`, `apps/web/vite.config.js` — Leaflet dependency + entry
- `apps/web/tests/Feature/Workspace/ContributionTest.php` — updated for the 3-step flow
- `docs/01-project/simple-checklist.txt`, `docs/04-architecture/database-structure.txt`, `CHANGELOG.md`

---

### Task 1: Establishment guide — facility fields, parking, opened/closed dates

**Files:**
- Modify: `data/structure_guide/establishment_main.php`
- Test: `apps/web/tests/Feature/Governance/EstablishmentGuideFieldsTest.php` (new)

**Interfaces:**
- Produces: the following field names now exist in `$establishment_main_field_order`: `shower_availability`, `sauna_availability`, `steam_room_availability`, `jacuzzi_availability`, `locker_availability`, `couple_room_availability`, `private_room_availability`, `curtain_divider_information`, `air_conditioning_information`, `room_types`, `bed_mat_chair_setup`, `parking_availability_list`, `date_opened`, `date_opened_precision`, `date_opened_qualifier`, `date_closed`, `date_closed_precision`, `date_closed_qualifier`.

- [ ] **Step 1: Write the failing governance test**

```php
<?php

namespace Tests\Feature\Governance;

use Tests\TestCase;

class EstablishmentGuideFieldsTest extends TestCase
{
    public function test_establishment_guide_includes_facility_and_date_fields(): void
    {
        $guide = require base_path('../../data/structure_guide/establishment_main.php');

        $expected = [
            'shower_availability', 'sauna_availability', 'steam_room_availability',
            'jacuzzi_availability', 'locker_availability', 'couple_room_availability',
            'private_room_availability', 'curtain_divider_information',
            'air_conditioning_information', 'room_types', 'bed_mat_chair_setup',
            'parking_availability_list', 'date_opened', 'date_opened_precision',
            'date_opened_qualifier', 'date_closed', 'date_closed_precision',
            'date_closed_qualifier',
        ];

        foreach ($expected as $field) {
            $this->assertContains($field, $guide['establishment_main_field_order'], "Missing field: {$field}");
            $this->assertArrayHasKey($field, $guide['establishment_main_field_property'], "Missing field property: {$field}");
        }
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run (from `apps/web/`): `php artisan test --filter=EstablishmentGuideFieldsTest`
Expected: FAIL — fields not found in field order.

- [ ] **Step 3: Bump the guide version and update timestamps**

In `data/structure_guide/establishment_main.php`, change:
```php
 * Version: 1.40
```
to
```php
 * Version: 1.50
```
and change line 16 `$updated_at = '2026-07-21T11:14:49Z';` to the actual current UTC time (obtain via `date -u +%Y-%m-%dT%H:%M:%SZ` or equivalent — do not reuse a prior timestamp).

- [ ] **Step 4: Add the new fields to the sample record**

In the `$establishment_main` array, after the `'landmark_list' => [...]` line and before `'treatment_area_list'`, no — insert facility fields after `'treatment_area_list'` and before `'amenity_list'` (matching the existing field-order neighborhood), and insert parking + date fields after `'payment_method_list'` and before `'primary_media_image_id'`:

```php
    'shower_availability' => 'IR', // Shower access classification.
    'sauna_availability' => 'NA', // Sauna access classification.
    'steam_room_availability' => 'NA', // Steam room access classification.
    'jacuzzi_availability' => 'NA', // Jacuzzi access classification.
    'locker_availability' => 'NR', // Locker access classification.
    'couple_room_availability' => 'NA', // Couple-room access classification.
    'private_room_availability' => 'IR', // Private-room access classification.
    'curtain_divider_information' => 'NA', // Curtain/divider classification.
    'air_conditioning_information' => 'NA', // Air-conditioning classification.
    'room_types' => ['ER'], // Confirmed room-type codes.
    'bed_mat_chair_setup' => [], // Confirmed bed/mat/chair setup codes.
```
(placed after `'treatment_area_list' => [...]` and before `'amenity_list' => [...]`)
```php
    'parking_availability_list' => ['PRK_ONS_FREE'], // Confirmed parking availability codes.
    'date_opened' => '2024-03-01', // Best-supported opening date.
    'date_opened_precision' => 'M', // Precision of date_opened.
    'date_opened_qualifier' => 'EXA', // Qualifier of date_opened.
    'date_closed' => null, // Best-supported closure date, when applicable.
    'date_closed_precision' => null, // Precision of date_closed.
    'date_closed_qualifier' => null, // Qualifier of date_closed.
```
(placed after `'payment_method_list' => [...]` and before `'primary_media_image_id' => ...`)

- [ ] **Step 5: Update `$establishment_main_field_order`**

Insert `'shower_availability', 'sauna_availability', 'steam_room_availability', 'jacuzzi_availability', 'locker_availability', 'couple_room_availability', 'private_room_availability', 'curtain_divider_information', 'air_conditioning_information', 'room_types', 'bed_mat_chair_setup',` immediately after `'treatment_area_list',` and before `'amenity_list',`.

Insert `'parking_availability_list', 'date_opened', 'date_opened_precision', 'date_opened_qualifier', 'date_closed', 'date_closed_precision', 'date_closed_qualifier',` immediately after `'payment_method_list',` and before `'primary_media_image_id',`.

- [ ] **Step 6: Add field-property entries**

In `$establishment_main_field_property`, after the `'treatment_area_list' => [...]` entry, add:

```php
    'shower_availability' => ['field_label' => 'Shower Availability', 'field_description' => 'Controlled shower-access classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'sauna_availability' => ['field_label' => 'Sauna Availability', 'field_description' => 'Controlled sauna-access classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'steam_room_availability' => ['field_label' => 'Steam Room Availability', 'field_description' => 'Controlled steam-room-access classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'jacuzzi_availability' => ['field_label' => 'Jacuzzi Availability', 'field_description' => 'Controlled jacuzzi-access classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'locker_availability' => ['field_label' => 'Locker Availability', 'field_description' => 'Controlled locker-access classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'couple_room_availability' => ['field_label' => 'Couple Room Availability', 'field_description' => 'Controlled couple-room-access classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'private_room_availability' => ['field_label' => 'Private Room Availability', 'field_description' => 'Controlled private-room-access classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'curtain_divider_information' => ['field_label' => 'Curtain/Divider Information', 'field_description' => 'Controlled curtain-or-divider classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'air_conditioning_information' => ['field_label' => 'Air-Conditioning Information', 'field_description' => 'Controlled air-conditioning classification.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'room_types' => ['field_label' => 'Room Types', 'field_description' => 'Confirmed treatment-room-type codes.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'bed_mat_chair_setup' => ['field_label' => 'Bed/Mat/Chair Setup', 'field_description' => 'Confirmed bed, mat, or chair setup codes.', 'type_data' => 'A', 'type_field' => 'TAG'],
```

After the `'payment_method_list' => [...]` entry, add:

```php
    'parking_availability_list' => ['field_label' => 'Parking Availability', 'field_description' => 'Confirmed parking-availability codes.', 'type_data' => 'A', 'type_field' => 'TAG'],
    'date_opened' => ['field_label' => 'Date Opened', 'field_description' => 'Best-supported opening date, denormalized from the authoritative establishment_event opening record for display and query.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'date_opened_precision' => ['field_label' => 'Date Opened Precision', 'field_description' => 'Controlled precision of date_opened, matching establishment_event.type_date_precision.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'date_opened_qualifier' => ['field_label' => 'Date Opened Qualifier', 'field_description' => 'Controlled qualifier of date_opened, matching establishment_event.type_date_qualifier.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'date_closed' => ['field_label' => 'Date Closed', 'field_description' => 'Best-supported closure date when the establishment has ceased, relocated, or is temporarily closed; denormalized from the authoritative establishment_event closure record.', 'type_data' => 'S', 'type_field' => 'DTI'],
    'date_closed_precision' => ['field_label' => 'Date Closed Precision', 'field_description' => 'Controlled precision of date_closed.', 'type_data' => 'S', 'type_field' => 'DDL'],
    'date_closed_qualifier' => ['field_label' => 'Date Closed Qualifier', 'field_description' => 'Controlled qualifier of date_closed.', 'type_data' => 'S', 'type_field' => 'DDL'],
```

- [ ] **Step 7: Run test to verify it passes**

Run: `php artisan test --filter=EstablishmentGuideFieldsTest`
Expected: PASS

- [ ] **Step 8: Commit**

```bash
git add data/structure_guide/establishment_main.php apps/web/tests/Feature/Governance/EstablishmentGuideFieldsTest.php
git commit -m "docs(structure-guide): add facility, parking, and opened/closed date fields to establishment_main"
```

---

### Task 2: Taxonomy field-name corrections + parking_availability field

**Files:**
- Modify: `data/taxonomy/massage_nexus/establishment_classification.json`
- Modify: `data/field_index.txt` (regenerated, not hand-edited)
- Test: `apps/web/tests/Feature/Governance/TaxonomyFieldNamesTest.php` (new)

**Interfaces:**
- Produces: taxonomy field `amenity_list` (was `amenities`), `accessibility_feature_list` (was `accessibility_information`), new field `parking_availability` with codes `NONE`, `PRK_ONS_FREE`, `PRK_ONS_PAID`, `PRK_STR`, `PRK_NEARBY_PAID`, `PRK_VALET`, `PRK_MOTO_ONLY`.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature\Governance;

use App\Support\Taxonomy\TaxonomyOptions;
use Tests\TestCase;

class TaxonomyFieldNamesTest extends TestCase
{
    public function test_amenity_list_and_accessibility_feature_list_are_the_taxonomy_field_names(): void
    {
        $this->assertNotEmpty(TaxonomyOptions::for('amenity_list'));
        $this->assertNotEmpty(TaxonomyOptions::for('accessibility_feature_list'));
        $this->assertSame([], TaxonomyOptions::for('amenities'));
        $this->assertSame([], TaxonomyOptions::for('accessibility_information'));
    }

    public function test_parking_availability_taxonomy_field_exists(): void
    {
        $options = TaxonomyOptions::for('parking_availability');

        $this->assertArrayHasKey('NONE', $options);
        $this->assertArrayHasKey('PRK_ONS_FREE', $options);
        $this->assertArrayHasKey('PRK_ONS_PAID', $options);
        $this->assertArrayHasKey('PRK_STR', $options);
        $this->assertArrayHasKey('PRK_NEARBY_PAID', $options);
        $this->assertArrayHasKey('PRK_VALET', $options);
        $this->assertArrayHasKey('PRK_MOTO_ONLY', $options);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=TaxonomyFieldNamesTest`
Expected: FAIL — `amenity_list` and `accessibility_feature_list` return empty arrays, `parking_availability` doesn't exist.

- [ ] **Step 3: Rename the two field_name values**

In `data/taxonomy/massage_nexus/establishment_classification.json`, change:
```json
  "field_name": "amenities",
```
to
```json
  "field_name": "amenity_list",
```
and change:
```json
  "field_name": "accessibility_information",
```
to
```json
  "field_name": "accessibility_feature_list",
```
Do not change `field_label`, `field_description`, `selection_rule`, or any `option_code`/`option_label` values — only the two `field_name` keys.

- [ ] **Step 4: Add the parking_availability field**

Append a new object to the top-level JSON array (after the `accessibility_feature_list` entry, before the array's closing `]`):

```json
  {
    "field_name": "parking_availability",
    "field_label": "Parking Availability",
    "selection_rule": "Multi-select",
    "field_description": "Confirmed parking options available at or near the establishment.",
    "field_option": [
      { "option_code": "NONE", "option_label": "No Parking Available", "option_description": "" },
      { "option_code": "PRK_ONS_FREE", "option_label": "Free On-Site Parking", "option_description": "" },
      { "option_code": "PRK_ONS_PAID", "option_label": "Paid On-Site Parking", "option_description": "" },
      { "option_code": "PRK_STR", "option_label": "Street Parking", "option_description": "" },
      { "option_code": "PRK_NEARBY_PAID", "option_label": "Nearby Paid Lot", "option_description": "" },
      { "option_code": "PRK_VALET", "option_label": "Valet Parking", "option_description": "" },
      { "option_code": "PRK_MOTO_ONLY", "option_label": "Motorcycle Parking Only", "option_description": "" }
    ]
  }
```

- [ ] **Step 5: Regenerate the field index**

Run (from repository root): `php tools/script/build_field_index.php`

- [ ] **Step 6: Run test to verify it passes**

Run: `php artisan test --filter=TaxonomyFieldNamesTest`
Expected: PASS

- [ ] **Step 7: Commit**

```bash
git add data/taxonomy/massage_nexus/establishment_classification.json data/field_index.txt apps/web/tests/Feature/Governance/TaxonomyFieldNamesTest.php
git commit -m "docs(taxonomy): rename amenity/accessibility fields to match guide, add parking_availability"
```

---

### Task 3: Extend the user_contribution structure guide with Add-a-Spa fields

**Amended 2026-07-22 pre-execution:** the original task created `contribution_main.php`, but a concurrent agent renamed that guide to `user_contribution.php` (committed in 49f6629; runtime code still uses the legacy `contribution_main` collection per that guide's own Notes). Per user decision this task now extends the committed `user_contribution.php` instead. Runtime field names on the `Contribution` model (`is_workspace_access_requested`, `status_contribution`) intentionally differ from the guide's future names (`is_user_access_requested`, `status_user_contribution`) — that migration belongs to the other agent's work, not this task. The five NEW fields below use identical names in both guide and runtime.

**Files:**
- Modify: `data/structure_guide/user_contribution.php`
- Modify: `data/field_index.txt` (regenerated, not hand-edited)
- Test: `apps/web/tests/Feature/Governance/ContributionGuideFieldsTest.php` (new)

**Interfaces:**
- Produces: `user_contribution_field_order` additionally contains `submission_note`, `duplicate_candidate_establishment_id_list`, `duplicate_acknowledged`, `is_visit_requested`, `visit_preferred_time_note` — the same field names Task 7 adds to the runtime `Contribution` model.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature\Governance;

use Tests\TestCase;

class ContributionGuideFieldsTest extends TestCase
{
    public function test_user_contribution_guide_documents_the_add_a_spa_fields(): void
    {
        $guide = require base_path('../../data/structure_guide/user_contribution.php');

        $expected = [
            'submission_note', 'duplicate_candidate_establishment_id_list',
            'duplicate_acknowledged', 'is_visit_requested', 'visit_preferred_time_note',
        ];

        foreach ($expected as $field) {
            $this->assertContains($field, $guide['user_contribution_field_order'], "Missing field: {$field}");
            $this->assertArrayHasKey($field, $guide['user_contribution_field_property'], "Missing field property: {$field}");
        }
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=ContributionGuideFieldsTest`
Expected: FAIL — fields not present in the guide.

- [ ] **Step 3: Edit the guide**

In `data/structure_guide/user_contribution.php`:

1. Change `* Version: 1.00` to `* Version: 1.10` (+0.10 structural change per structure-guide-standard).
2. Set `$updated_at` to the actual current UTC time (`date -u +%Y-%m-%dT%H:%M:%SZ`); leave `$created_at` untouched.
3. In `$user_contribution_default`, add:

```php
    'duplicate_candidate_establishment_id_list' => [],
    'duplicate_acknowledged' => false,
    'is_visit_requested' => false,
```

4. In the `$user_contribution` sample record, after the `'relationship_note' => ...` line, add:

```php
    'submission_note' => 'Photos available on request.', // Optional free-text note to the reviewer, separate from the relationship note.
    'duplicate_candidate_establishment_id_list' => ['Es7K2pQ9xR4tV8zN'], // Establishment IDs the duplicate check flagged at submission time.
    'duplicate_acknowledged' => true, // Whether the submitter confirmed this is not one of the flagged candidates.
    'is_visit_requested' => false, // Whether the submitter requested an in-person verification visit.
    'visit_preferred_time_note' => null, // Optional free-text preferred time when is_visit_requested is true.
```

5. In `$user_contribution_field_order`, insert `'submission_note', 'duplicate_candidate_establishment_id_list', 'duplicate_acknowledged', 'is_visit_requested', 'visit_preferred_time_note',` immediately after `'relationship_note',`.
6. In `$user_contribution_field_property`, after the `'relationship_note' => [...]` entry, add:

```php
    'submission_note' => ['field_label' => 'Submission Note', 'field_description' => 'Optional free-text note to the reviewer, separate from the relationship note.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 2000],
    'duplicate_candidate_establishment_id_list' => ['field_label' => 'Duplicate Candidates', 'field_description' => 'Establishment references the duplicate check flagged as possible matches at submission time.', 'type_data' => 'A', 'type_field' => 'TAG', 'is_relational' => true],
    'duplicate_acknowledged' => ['field_label' => 'Duplicate Acknowledged', 'field_description' => 'Whether the submitter confirmed the proposal is not one of the flagged duplicate candidates.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false],
    'is_visit_requested' => ['field_label' => 'Visit Requested', 'field_description' => 'Whether the submitter requested an in-person verification visit.', 'type_data' => 'B', 'type_field' => 'CHK', 'default_value' => false],
    'visit_preferred_time_note' => ['field_label' => 'Visit Preferred Time', 'field_description' => 'Optional free-text preferred time for the requested visit.', 'type_data' => 'S', 'type_field' => 'TXA', 'max_character' => 500],
```

Change nothing else in the file — no renames, no reordering of existing entries, no edits to the other agent's field definitions.

- [ ] **Step 4: Regenerate the field index**

Run (from repository root): `php tools/script/build_field_index.php`

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test --filter=ContributionGuideFieldsTest`
Expected: PASS

- [ ] **Step 6: Commit**

```bash
git add data/structure_guide/user_contribution.php data/field_index.txt apps/web/tests/Feature/Governance/ContributionGuideFieldsTest.php
git commit -m "docs(structure-guide): add submission-note, duplicate-check, and visit-request fields to user_contribution"
```

---

### Task 4: `common_reference` connection + Country/Region read models

**Files:**
- Modify: `apps/web/config/database.php`
- Modify: `apps/web/.env`, `apps/web/.env.example`
- Modify: `apps/web/phpunit.xml`
- Create: `apps/web/app/Models/Reference/Country.php`
- Create: `apps/web/app/Models/Reference/Region.php`
- Test: `apps/web/tests/Feature/Reference/CountryRegionModelTest.php` (new)

**Interfaces:**
- Produces: `App\Models\Reference\Country::query()->find(608)` returns the Philippines row; `App\Models\Reference\Region::query()->where('country_id', 608)->get()` returns PH regions. Both models: `protected $connection = 'mongodb_reference'`, `$incrementing = false`, `$keyType = 'int'`.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature\Reference;

use App\Models\Reference\Country;
use App\Models\Reference\Region;
use Tests\TestCase;

class CountryRegionModelTest extends TestCase
{
    public function test_country_model_reads_from_the_reference_connection(): void
    {
        Country::query()->getConnection()->getCollection('country_main')->insertOne([
            '_id' => 608,
            'country_key' => 'philippines',
            'iso_alpha_2_code' => 'PH',
            'country_name' => ['eng' => ['text' => 'Philippines']],
        ]);

        $country = Country::query()->find(608);

        $this->assertNotNull($country);
        $this->assertSame('philippines', $country->country_key);
        $this->assertSame('mongodb_reference', $country->getConnectionName());
    }

    public function test_region_model_filters_by_country(): void
    {
        Region::query()->getConnection()->getCollection('region_main')->insertMany([
            ['_id' => 1, 'country_id' => 608, 'region_key' => 'ph_ncr', 'region_name' => ['eng' => ['text' => 'National Capital Region']]],
            ['_id' => 2, 'country_id' => 999, 'region_key' => 'other', 'region_name' => ['eng' => ['text' => 'Elsewhere']]],
        ]);

        $regions = Region::query()->where('country_id', 608)->get();

        $this->assertCount(1, $regions);
        $this->assertSame('ph_ncr', $regions->first()->region_key);
    }

    protected function tearDown(): void
    {
        Country::query()->getConnection()->getCollection('country_main')->deleteMany([]);
        Region::query()->getConnection()->getCollection('region_main')->deleteMany([]);
        parent::tearDown();
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=CountryRegionModelTest`
Expected: FAIL — class `App\Models\Reference\Country` not found.

- [ ] **Step 3: Add the connection**

In `apps/web/config/database.php`, inside `'connections' => [ ... ]`, immediately after the existing `'mongodb' => [...]` block, add:

```php
        'mongodb_reference' => [
            'driver' => 'mongodb',
            'dsn' => env('MONGODB_URI', 'mongodb://127.0.0.1:27018'),
            'database' => env('MONGODB_REFERENCE_DATABASE', 'common_reference'),
        ],
```

- [ ] **Step 4: Add env values**

In `apps/web/.env`, after `MONGODB_DATABASE=massage_main`, add:
```
MONGODB_REFERENCE_DATABASE=common_reference
```
Make the same addition to `apps/web/.env.example` after its `MONGODB_DATABASE=massage_main` line.

In `apps/web/phpunit.xml`, after `<env name="MONGODB_DATABASE" value="massage_test"/>`, add:
```xml
        <env name="MONGODB_REFERENCE_DATABASE" value="common_reference_test"/>
```

- [ ] **Step 5: Write the Country model**

```php
<?php

namespace App\Models\Reference;

use MongoDB\Laravel\Eloquent\Model;

/**
 * Read-only reference model. country_main is owned and populated outside this
 * application (docs/04-architecture/database-structure.txt's common_reference
 * database); this model never writes to it.
 */
class Country extends Model
{
    protected $connection = 'mongodb_reference';

    protected $table = 'country_main';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;
}
```

- [ ] **Step 6: Write the Region model**

```php
<?php

namespace App\Models\Reference;

use MongoDB\Laravel\Eloquent\Model;

/**
 * Read-only reference model. region_main is owned and populated outside this
 * application; this model never writes to it.
 */
class Region extends Model
{
    protected $connection = 'mongodb_reference';

    protected $table = 'region_main';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;
}
```

- [ ] **Step 7: Run test to verify it passes**

Run: `php artisan test --filter=CountryRegionModelTest`
Expected: PASS

- [ ] **Step 8: Commit**

```bash
git add apps/web/config/database.php apps/web/.env.example apps/web/phpunit.xml apps/web/app/Models/Reference apps/web/tests/Feature/Reference/CountryRegionModelTest.php
git commit -m "feat(reference): connect to common_reference and add Country/Region models"
```

Note: `apps/web/.env` is typically gitignored — verify with `git status`; if untracked, apply the same edit locally but skip staging it.

---

### Task 5: `City` model (empty-collection-safe) + `reference:sync` command

**Files:**
- Create: `apps/web/app/Models/Reference/City.php`
- Create: `apps/web/app/Console/Commands/SyncReferenceData.php`
- Test: `apps/web/tests/Feature/Console/SyncReferenceDataTest.php` (new)

**Interfaces:**
- Produces: `App\Models\Reference\City` (same shape as `Region`, table `city_main`, plus `region_id` for the query Task 6 needs). `php artisan reference:sync` and `php artisan reference:sync --dry-run`.
- Consumes: nothing from earlier tasks beyond the `mongodb_reference` connection (Task 4).

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature\Console;

use App\Models\Reference\Region;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SyncReferenceDataTest extends TestCase
{
    protected function tearDown(): void
    {
        Region::query()->getConnection()->getCollection('region_main')->deleteMany([]);
        parent::tearDown();
    }

    public function test_sync_upserts_region_main_from_the_json_file(): void
    {
        $this->artisan('reference:sync')->assertExitCode(0);

        $region = Region::query()->find(1);
        $this->assertNotNull($region);
        $this->assertSame('ph_national_capital_region', $region->region_key);
    }

    public function test_sync_is_idempotent(): void
    {
        $this->artisan('reference:sync')->assertExitCode(0);
        $countAfterFirstRun = Region::query()->count();

        $this->artisan('reference:sync')->assertExitCode(0);
        $countAfterSecondRun = Region::query()->count();

        $this->assertSame($countAfterFirstRun, $countAfterSecondRun);
    }

    public function test_dry_run_makes_no_changes(): void
    {
        $this->artisan('reference:sync', ['--dry-run' => true])->assertExitCode(0);

        $this->assertSame(0, Region::query()->count());
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=SyncReferenceDataTest`
Expected: FAIL — command `reference:sync` does not exist.

- [ ] **Step 3: Write the City model**

```php
<?php

namespace App\Models\Reference;

use MongoDB\Laravel\Eloquent\Model;

/**
 * Read-only reference model. city_main does not exist yet (see the separate
 * "PH Geographic Reference Data" project) — queries against it safely return
 * no rows until that project seeds it.
 */
class City extends Model
{
    protected $connection = 'mongodb_reference';

    protected $table = 'city_main';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;
}
```

- [ ] **Step 4: Write the sync command**

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SyncReferenceData extends Command
{
    protected $signature = 'reference:sync {--dry-run : Report what would change without writing}';

    protected $description = 'Idempotently upsert data/common_reference/*.json into the common_reference database.';

    /** Collection filename => Mongo collection name. */
    private const DATASETS = [
        'country_main' => 'country_main',
        'region_main' => 'region_main',
        'area_hierarchy_profile' => 'area_hierarchy_profile',
        'currency_main' => 'currency_main',
        'language_main' => 'language_main',
        'time_zone_main' => 'time_zone_main',
        'dog_breed_main' => 'dog_breed_main',
    ];

    public function handle(): int
    {
        $repositoryRoot = dirname(base_path(), 2);
        $dryRun = (bool) $this->option('dry-run');

        foreach (self::DATASETS as $file => $collection) {
            $path = "{$repositoryRoot}/data/common_reference/{$file}.json";

            if (! File::exists($path)) {
                $this->warn("Skipping {$collection}: {$path} not found.");

                continue;
            }

            $records = json_decode(File::get($path), true, flags: JSON_THROW_ON_ERROR);
            $upserted = 0;

            foreach ($records as $record) {
                if (! isset($record['_id'])) {
                    $this->warn("Skipping a {$collection} record with no _id.");

                    continue;
                }

                if (! $dryRun) {
                    DB::connection('mongodb_reference')
                        ->getCollection($collection)
                        ->replaceOne(['_id' => $record['_id']], $record, ['upsert' => true]);
                }

                $upserted++;
            }

            $verb = $dryRun ? 'would upsert' : 'upserted';
            $this->info("{$collection}: {$verb} {$upserted} record(s).");
        }

        return self::SUCCESS;
    }
}
```

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test --filter=SyncReferenceDataTest`
Expected: PASS

- [ ] **Step 6: Commit**

```bash
git add apps/web/app/Models/Reference/City.php apps/web/app/Console/Commands/SyncReferenceData.php apps/web/tests/Feature/Console/SyncReferenceDataTest.php
git commit -m "feat(reference): add City model and idempotent reference:sync command"
```

---

### Task 6: `AddressLookup` service

**Files:**
- Create: `apps/web/app/Support/Address/AddressLookup.php`
- Test: `apps/web/tests/Unit/Support/AddressLookupTest.php` (new)

**Interfaces:**
- Consumes: `App\Models\Reference\Country`, `App\Models\Reference\Region`, `App\Models\Reference\City` (Tasks 4-5).
- Produces: `AddressLookup::countries(): array` (`[id => label]`, sorted by label), `AddressLookup::regions(int $countryId): array`, `AddressLookup::cities(int $regionId): array` — the last returns `[]` when `city_main` has no rows for that region, which the Location tab (Task 12) uses to decide free-text vs. select.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Unit\Support;

use App\Models\Reference\City;
use App\Models\Reference\Country;
use App\Models\Reference\Region;
use App\Support\Address\AddressLookup;
use Tests\TestCase;

class AddressLookupTest extends TestCase
{
    protected function tearDown(): void
    {
        Country::query()->getConnection()->getCollection('country_main')->deleteMany([]);
        Region::query()->getConnection()->getCollection('region_main')->deleteMany([]);
        City::query()->getConnection()->getCollection('city_main')->deleteMany([]);
        parent::tearDown();
    }

    public function test_countries_returns_id_label_map(): void
    {
        Country::query()->create(['_id' => 608, 'country_name' => ['eng' => ['text' => 'Philippines']]]);

        $result = (new AddressLookup)->countries();

        $this->assertSame(['608' => 'Philippines'], $result);
    }

    public function test_regions_filters_by_country(): void
    {
        Region::query()->create(['_id' => 1, 'country_id' => 608, 'region_name' => ['eng' => ['text' => 'NCR']]]);
        Region::query()->create(['_id' => 2, 'country_id' => 999, 'region_name' => ['eng' => ['text' => 'Elsewhere']]]);

        $result = (new AddressLookup)->regions(608);

        $this->assertSame(['1' => 'NCR'], $result);
    }

    public function test_cities_returns_empty_array_when_none_exist(): void
    {
        $result = (new AddressLookup)->cities(1);

        $this->assertSame([], $result);
    }

    public function test_cities_filters_by_region_when_data_exists(): void
    {
        City::query()->create(['_id' => 1, 'region_id' => 1, 'city_name' => ['eng' => ['text' => 'Manila']]]);

        $result = (new AddressLookup)->cities(1);

        $this->assertSame(['1' => 'Manila'], $result);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=AddressLookupTest`
Expected: FAIL — class not found.

- [ ] **Step 3: Write the service**

```php
<?php

namespace App\Support\Address;

use App\Models\Reference\City;
use App\Models\Reference\Country;
use App\Models\Reference\Region;

class AddressLookup
{
    /** @return array<string, string> country_id => label, sorted by label */
    public function countries(): array
    {
        return Country::query()
            ->get()
            ->mapWithKeys(fn (Country $country) => [
                (string) $country->getKey() => (string) data_get($country->country_name, 'eng.text', $country->country_key),
            ])
            ->sort()
            ->all();
    }

    /** @return array<string, string> region_id => label, sorted by label */
    public function regions(int $countryId): array
    {
        return Region::query()
            ->where('country_id', $countryId)
            ->get()
            ->mapWithKeys(fn (Region $region) => [
                (string) $region->getKey() => (string) data_get($region->region_name, 'eng.text', $region->region_key),
            ])
            ->sort()
            ->all();
    }

    /**
     * @return array<string, string> city_id => label. Empty until city_main
     * is populated by the separate PH Geographic Reference Data project —
     * callers must treat an empty result as "no data yet", not "no cities".
     */
    public function cities(int $regionId): array
    {
        return City::query()
            ->where('region_id', $regionId)
            ->get()
            ->mapWithKeys(fn (City $city) => [
                (string) $city->getKey() => (string) data_get($city->city_name, 'eng.text', $city->city_key ?? ''),
            ])
            ->sort()
            ->all();
    }
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --filter=AddressLookupTest`
Expected: PASS

- [ ] **Step 5: Commit**

```bash
git add apps/web/app/Support/Address/AddressLookup.php apps/web/tests/Unit/Support/AddressLookupTest.php
git commit -m "feat(reference): add AddressLookup service for the Location tab cascade"
```

---

### Task 7: Contribution model fields + `EstablishmentContributionSubmitted` event

**Files:**
- Modify: `apps/web/app/Models/Contribution.php`
- Create: `apps/web/app/Events/EstablishmentContributionSubmitted.php`
- Test: `apps/web/tests/Unit/Models/ContributionTest.php` (new)

**Interfaces:**
- Produces: `Contribution` gains fillable `submission_note`, `duplicate_candidate_establishment_id_list`, `duplicate_acknowledged`, `is_visit_requested`, `visit_preferred_time_note`, with casts `duplicate_candidate_establishment_id_list` → `array`, `duplicate_acknowledged` → `bool`, `is_visit_requested` → `bool`. `EstablishmentContributionSubmitted` has a public readonly `Contribution $contribution` property.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Unit\Models;

use App\Models\Contribution;
use Tests\TestCase;

class ContributionTest extends TestCase
{
    public function test_new_fields_are_fillable_and_cast(): void
    {
        $contribution = Contribution::query()->create([
            'type_contribution' => 'ADD',
            'target_collection' => 'establishment_main',
            'submitted_by_user_id' => 'Us7K2pQ9xR4tV8zN',
            'proposed_data' => [],
            'submission_note' => 'Photos available on request.',
            'duplicate_candidate_establishment_id_list' => ['Es7K2pQ9xR4tV8zN'],
            'duplicate_acknowledged' => true,
            'is_visit_requested' => '1',
            'visit_preferred_time_note' => 'Weekday mornings.',
        ]);

        $this->assertSame('Photos available on request.', $contribution->submission_note);
        $this->assertSame(['Es7K2pQ9xR4tV8zN'], $contribution->duplicate_candidate_establishment_id_list);
        $this->assertTrue($contribution->duplicate_acknowledged);
        $this->assertTrue($contribution->is_visit_requested);

        $contribution->delete();
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter="Tests\\Unit\\Models\\ContributionTest"`
Expected: FAIL — `is_visit_requested` cast returns `'1'` (string) instead of `true`, or the fillable guard silently drops the new attributes.

- [ ] **Step 3: Update the Contribution model**

In `apps/web/app/Models/Contribution.php`, change the `#[Fillable([...])]` attribute list to add the five new fields, and add the `casts()` entries:

```php
#[Fillable([
    'type_contribution', 'target_collection', 'target_record_id',
    'submitted_by_user_id', 'proposed_data',
    'type_establishment_relationship', 'type_practitioner_relationship',
    'is_workspace_access_requested',
    'relationship_note', 'submission_note',
    'duplicate_candidate_establishment_id_list', 'duplicate_acknowledged',
    'is_visit_requested', 'visit_preferred_time_note',
    'status_contribution', 'submitted_at',
    'reviewed_at', 'reviewer_user_id', 'decision_note',
])]
```

```php
    protected function casts(): array
    {
        return [
            'proposed_data' => 'array',
            'is_workspace_access_requested' => 'boolean',
            'duplicate_candidate_establishment_id_list' => 'array',
            'duplicate_acknowledged' => 'boolean',
            'is_visit_requested' => 'boolean',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }
```

- [ ] **Step 4: Write the event**

```php
<?php

namespace App\Events;

use App\Models\Contribution;
use Illuminate\Foundation\Events\Dispatchable;

class EstablishmentContributionSubmitted
{
    use Dispatchable;

    public function __construct(public readonly Contribution $contribution)
    {
    }
}
```

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test --filter="Tests\\Unit\\Models\\ContributionTest"`
Expected: PASS

- [ ] **Step 6: Commit**

```bash
git add apps/web/app/Models/Contribution.php apps/web/app/Events/EstablishmentContributionSubmitted.php apps/web/tests/Unit/Models/ContributionTest.php
git commit -m "feat(contribution): add submission-note, duplicate-check, and visit-request fields"
```

---

### Task 8: `DuplicateEstablishmentFinder`

**Files:**
- Create: `apps/web/app/Support/Establishment/DuplicateEstablishmentFinder.php`
- Test: `apps/web/tests/Unit/Support/DuplicateEstablishmentFinderTest.php` (new)

**Interfaces:**
- Consumes: `App\Models\Establishment` (existing), `App\Models\Contribution` (existing).
- Produces: `DuplicateEstablishmentFinder::find(string $displayName, ?string $regionLabel = null): Collection<int, array{id: string, display_name: string, address_public: ?string, source: string}>` — checks both live establishments and other pending establishment contributions, returns at most 5 candidates.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Unit\Support;

use App\Models\Contribution;
use App\Models\Establishment;
use App\Support\Establishment\DuplicateEstablishmentFinder;
use Tests\TestCase;

class DuplicateEstablishmentFinderTest extends TestCase
{
    protected function tearDown(): void
    {
        Establishment::query()->delete();
        Contribution::query()->delete();
        parent::tearDown();
    }

    public function test_finds_a_normalized_name_match_among_live_establishments(): void
    {
        Establishment::query()->create([
            'display_name' => ['eng' => 'Harbor  Calm Spa!'],
            'address_public' => 'Makati City',
        ]);

        $matches = (new DuplicateEstablishmentFinder)->find('harbor calm spa');

        $this->assertCount(1, $matches);
        $this->assertSame('establishment', $matches->first()['source']);
    }

    public function test_finds_a_match_among_other_pending_contributions(): void
    {
        Contribution::query()->create([
            'type_contribution' => 'ADD',
            'target_collection' => 'establishment_main',
            'submitted_by_user_id' => 'Us7K2pQ9xR4tV8zN',
            'status_contribution' => 'PND',
            'proposed_data' => ['display_name' => ['eng' => ['text' => 'Ocean Breeze Spa']]],
        ]);

        $matches = (new DuplicateEstablishmentFinder)->find('Ocean Breeze Spa');

        $this->assertCount(1, $matches);
        $this->assertSame('contribution', $matches->first()['source']);
    }

    public function test_returns_no_matches_for_a_distinct_name(): void
    {
        Establishment::query()->create(['display_name' => ['eng' => 'Harbor Calm Spa']]);

        $matches = (new DuplicateEstablishmentFinder)->find('Totally Different Wellness Center');

        $this->assertCount(0, $matches);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=DuplicateEstablishmentFinderTest`
Expected: FAIL — class not found.

- [ ] **Step 3: Write the finder**

```php
<?php

namespace App\Support\Establishment;

use App\Models\Contribution;
use App\Models\Establishment;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DuplicateEstablishmentFinder
{
    private const MAX_RESULTS = 5;

    /** @return Collection<int, array{id: string, display_name: string, address_public: ?string, source: string}> */
    public function find(string $displayName, ?string $regionLabel = null): Collection
    {
        $normalized = $this->normalize($displayName);

        if ($normalized === '') {
            return collect();
        }

        $liveMatches = Establishment::query()
            ->get(['_id', 'display_name', 'address_public'])
            ->filter(fn (Establishment $establishment) => $this->normalize((string) data_get($establishment->display_name, 'eng', '')) === $normalized)
            ->map(fn (Establishment $establishment) => [
                'id' => (string) $establishment->getKey(),
                'display_name' => (string) data_get($establishment->display_name, 'eng', ''),
                'address_public' => $establishment->address_public,
                'source' => 'establishment',
            ]);

        $pendingMatches = Contribution::query()
            ->where('target_collection', 'establishment_main')
            ->where('status_contribution', 'PND')
            ->get(['_id', 'proposed_data'])
            ->filter(fn (Contribution $contribution) => $this->normalize((string) data_get($contribution->proposed_data, 'establishment.display_name.eng.text', data_get($contribution->proposed_data, 'display_name.eng', ''))) === $normalized)
            ->map(fn (Contribution $contribution) => [
                'id' => (string) $contribution->getKey(),
                'display_name' => (string) data_get($contribution->proposed_data, 'establishment.display_name.eng.text', data_get($contribution->proposed_data, 'display_name.eng', '')),
                'address_public' => data_get($contribution->proposed_data, 'establishment.address_public'),
                'source' => 'contribution',
            ]);

        return $liveMatches->concat($pendingMatches)->take(self::MAX_RESULTS)->values();
    }

    private function normalize(string $name): string
    {
        return Str::of($name)->lower()->replaceMatches('/[^a-z0-9]+/', ' ')->trim()->squish()->toString();
    }
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --filter=DuplicateEstablishmentFinderTest`
Expected: PASS

- [ ] **Step 5: Commit**

```bash
git add apps/web/app/Support/Establishment/DuplicateEstablishmentFinder.php apps/web/tests/Unit/Support/DuplicateEstablishmentFinderTest.php
git commit -m "feat(establishment): add normalized-name duplicate detection"
```

Note: this reads every `Establishment` and pending establishment `Contribution` into memory to normalize in PHP (no DB-side normalization index exists). Acceptable at current data volume; revisit with a text index if the establishment count grows large enough to matter.

---

### Task 9: `EstablishmentForm` — field-name migration, new fields, and step scaffolding

**Files:**
- Modify: `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`
- Modify: `apps/web/tests/Feature/Editorial/EstablishmentCrudTest.php` (field-name updates only — editorial mode still targets the same `Establishment` model, just renamed fields)
- Test: `apps/web/tests/Feature/Workspace/ContributionTest.php` (extended)

**Interfaces:**
- Produces: `EstablishmentForm::$currentStep` (int, 1-3, contribution mode only); `EstablishmentForm::nextStep()`, `EstablishmentForm::prevStep()`; `LIST_FIELDS` now includes `amenity_list`, `accessibility_feature_list`, `room_types`, `bed_mat_chair_setup`, `parking_availability_list` (was `amenities`, `accessibility_information`); `PLAIN_FIELDS` gains `shower_availability` through `air_conditioning_information` (already present) plus `date_opened`, `date_opened_precision`, `date_opened_qualifier`, `date_closed`, `date_closed_precision`, `date_closed_qualifier`; new public property `submission_note`.
- Consumes: nothing new from earlier tasks yet (this task is the field-shape groundwork; Tasks 10-15 build the step UI on top of it).

- [ ] **Step 1: Write the failing tests**

Add to `apps/web/tests/Feature/Workspace/ContributionTest.php`:

```php
    public function test_contribution_mode_starts_on_step_one(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->assertSet('currentStep', 1);
    }

    public function test_editorial_mode_has_no_step_concept(): void
    {
        Livewire::actingAs($this->editorForWizardTest())
            ->test(EstablishmentForm::class)
            ->assertSet('currentStep', 1);
    }

    private function editorForWizardTest(): User
    {
        $user = User::factory()->create();
        \App\Models\AccessAssignment::query()->create([
            'user_id' => (string) $user->getKey(),
            'role_workspace' => 'EAD',
            'scope_access' => 'GBL',
            'status_access_assignment' => 'ACT',
            'effective_at' => now()->subMinute(),
        ]);

        return $user;
    }
```

Update `apps/web/tests/Feature/Editorial/EstablishmentCrudTest.php` — every assertion that reads/writes `state.amenities` or `state.accessibility_information` (there are none directly in the current file's test bodies, but the model assertions rely on stored field names). Add:

```php
    public function test_editor_can_set_facility_and_parking_fields(): void
    {
        $user = $this->editor();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Workspace\Editorial\EstablishmentForm::class)
            ->set('state.display_name_eng', 'Calm Springs')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->set('state.shower_availability', 'IR')
            ->set('state.parking_availability_list', ['PRK_ONS_FREE'])
            ->call('save');

        $record = Establishment::query()->first();
        $this->assertSame('IR', $record->shower_availability);
        $this->assertSame(['PRK_ONS_FREE'], $record->parking_availability_list);
    }
```

- [ ] **Step 2: Run tests to verify they fail**

Run: `php artisan test --filter=ContributionTest` and `php artisan test --filter=EstablishmentCrudTest`
Expected: FAIL — `currentStep` property doesn't exist; `parking_availability_list`/`shower_availability` aren't in `PLAIN_FIELDS` yet (shower already is, parking isn't).

- [ ] **Step 3: Update the field constants**

In `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`, replace the `PLAIN_FIELDS` constant:

```php
    private const PLAIN_FIELDS = [
        'email', 'contact_number', 'address_public', 'coordinate_latitude',
        'coordinate_longitude', 'type_spa', 'level_spa_market',
        'type_physical_setting', 'type_establishment_operation',
        'status_establishment', 'mode_access', 'type_client_access',
        'status_record_lifecycle', 'shower_availability', 'sauna_availability',
        'steam_room_availability', 'jacuzzi_availability', 'locker_availability',
        'couple_room_availability', 'private_room_availability',
        'curtain_divider_information', 'air_conditioning_information',
        'date_opened', 'date_opened_precision', 'date_opened_qualifier',
        'date_closed', 'date_closed_precision', 'date_closed_qualifier',
    ];
```

Replace the `LIST_FIELDS` constant:

```php
    private const LIST_FIELDS = [
        'mode_service_delivery', 'target_client_focus', 'amenity_list',
        'room_types', 'bed_mat_chair_setup', 'accessibility_feature_list',
        'parking_availability_list',
    ];
```

- [ ] **Step 4: Add step tracking and navigation methods**

Add a public property near `$isContribution`:

```php
    /** 1 = who-you-are, 2 = spa details tabs, 3 = review and submit. Editorial mode never advances past 1 (no wizard chrome shown). */
    public int $currentStep = 1;

    public ?string $submission_note = null;
```

Add methods after `removeRow()`:

```php
    public function nextStep(): void
    {
        if (! $this->isContribution) {
            return;
        }

        $this->validate($this->rulesForStep($this->currentStep));
        $this->currentStep = min(3, $this->currentStep + 1);
    }

    public function prevStep(): void
    {
        if (! $this->isContribution) {
            return;
        }

        $this->currentStep = max(1, $this->currentStep - 1);
    }

    /** @return array<string, mixed> */
    private function rulesForStep(int $step): array
    {
        $rules = $this->rules();

        return match ($step) {
            1 => array_intersect_key($rules, array_flip(['type_establishment_relationship', 'is_workspace_access_requested', 'relationship_note'])),
            default => $rules,
        };
    }
```

(`rules()` itself is extended in Task 11-14 as each tab's new fields land; this task only adds the dispatch scaffolding.)

- [ ] **Step 5: Rename taxonomy lookups in `render()`**

In the `render()` method's taxonomy field list, change `'amenities'` to `'amenity_list'` and `'accessibility_information'` to `'accessibility_feature_list'`, and add `'parking_availability'` to the list:

```php
            'private_room_availability', 'curtain_divider_information',
            'air_conditioning_information', 'amenity_list', 'accessibility_feature_list',
            'parking_availability',
        ] as $field) {
```

- [ ] **Step 6: Update `save()`'s direct-write path field references**

No field-name changes are needed in `save()`'s loops (`PLAIN_FIELDS`/`LIST_FIELDS`/`REPEATERS` are already iterated generically) — the constant updates from Step 3 are sufficient. Confirm by reading the method: it uses `self::PLAIN_FIELDS`/`self::LIST_FIELDS` symbolically, not hardcoded field names.

- [ ] **Step 7: Run tests to verify they pass**

Run: `php artisan test --filter=ContributionTest` and `php artisan test --filter=EstablishmentCrudTest`
Expected: PASS for the two new tests; the pre-existing `EstablishmentCrudTest` tests that reference `state.amenities`/`state.accessibility_information` directly do not exist in the current file (confirmed in Task file read) so nothing else breaks. If `mount()`'s default-value loop for `PLAIN_FIELDS` errors on the new date fields, verify `$this->state[$field] = $record?->getAttribute($field) ?? ($field === 'status_record_lifecycle' ? 'ACT' : '');` still returns `''` for the new date fields when `$record` is null — it does, no change needed there.

- [ ] **Step 8: Commit**

```bash
git add apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php apps/web/tests/Feature/Workspace/ContributionTest.php apps/web/tests/Feature/Editorial/EstablishmentCrudTest.php
git commit -m "feat(establishment-form): migrate to guide field names, add step scaffolding"
```

---

### Task 10: Step 1 "Who you are" — relocate from tab to wizard step

**Files:**
- Modify: `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`
- Modify: `apps/web/resources/views/livewire/workspace/editorial/establishment-form.blade.php`
- Create: `apps/web/resources/views/livewire/workspace/establishment-form/_who-you-are.blade.php`
- Modify: `apps/web/lang/eng/workspace.php`

**Interfaces:**
- Consumes: `EstablishmentForm::$currentStep`, `$type_establishment_relationship`, `$is_workspace_access_requested`, `$relationship_note` (existing properties), `nextStep()`/`prevStep()` (Task 9).
- Produces: the parent Blade view renders three `x-show="currentStep === N"` blocks instead of a flat tab bar with a bolted-on relationship tab; Step 1's content lives in `_who-you-are.blade.php` and is `@include`d only when `isContribution`.

- [ ] **Step 1: Write the failing test**

```php
    public function test_who_you_are_step_shows_before_spa_details_and_review(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/workspace/contribution/establishment/new');

        $response->assertOk();
        $response->assertSeeInOrder([
            __('workspace.contribution_relationship_label'),
            __('editorial.tab_identity'),
        ]);
    }
```

Add this to `apps/web/tests/Feature/Workspace/ContributionTest.php`.

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=ContributionTest`
Expected: FAIL — current blade shows tabs (including "Identity") before the relationship section, which is currently a tab rendered last.

- [ ] **Step 3: Add translation keys**

In `apps/web/lang/eng/workspace.php`, replace the existing `'contribution_relationship_tab' => 'Your relationship',` entry with:

```php
    'add_spa_step_who_you_are' => 'Who you are',
    'add_spa_step_spa_details' => 'Spa details',
    'add_spa_step_review' => 'Review and submit',
    'contribution_connection_label' => 'Your connection to this spa',
```

Keep `contribution_relationship_label`, `contribution_relationship_hint`, `contribution_relationship_note_label`, `contribution_access_label`, `contribution_access_hint` as-is (still used, just relocated).

- [ ] **Step 4: Write the Step 1 partial**

```blade
<div class="space-y-5">
    <h2 class="text-lg font-bold text-ink-950 dark:text-ink-50">{{ __('workspace.add_spa_step_who_you_are') }}</h2>

    <x-form.field :label="__('workspace.contribution_connection_label')" :error="$errors->first('type_establishment_relationship')">
        <x-form.select wire:model="type_establishment_relationship" :options="$relationshipOptions" />
    </x-form.field>
    <p class="text-sm text-ink-500 dark:text-ink-400">{{ __('workspace.contribution_relationship_hint') }}</p>

    <x-form.field :label="__('workspace.contribution_relationship_note_label')" :error="$errors->first('relationship_note')">
        <x-form.textarea wire:model="relationship_note" rows="3" maxlength="1000" />
    </x-form.field>

    <div class="rounded-xl border border-ink-200 bg-ink-50 p-4 dark:border-ink-700 dark:bg-ink-800">
        <label class="flex items-start gap-3">
            <input type="checkbox" wire:model="is_workspace_access_requested" class="mt-1 rounded border-ink-300 text-ember-600 focus:ring-ember-500">
            <span>
                <span class="block font-bold text-ink-900 dark:text-ink-100">{{ __('workspace.contribution_access_label') }}</span>
                <span class="mt-1 block text-sm text-ink-600 dark:text-ink-300">{{ __('workspace.contribution_access_hint') }}</span>
            </span>
        </label>
        @error('is_workspace_access_requested')<p class="mt-2 text-sm text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
    </div>

    <div class="flex justify-end border-t border-ink-100 pt-5 dark:border-ink-800">
        <button type="button" wire:click="nextStep" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('editorial.next') }}</button>
    </div>
</div>
```

Add `'next' => 'Next',` and `'back' => 'Back',` to `apps/web/lang/eng/editorial.php` near the existing `'cancel' => 'Cancel',` entry.

- [ ] **Step 5: Rewrite the parent Blade shell**

Replace the full contents of `apps/web/resources/views/livewire/workspace/editorial/establishment-form.blade.php` with:

```blade
<div class="mx-auto max-w-5xl">
    @if ($isContribution)
        <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ __('workspace.contribution_establishment_title') }}</h1>
        <p class="mt-2 text-sm text-ink-600 dark:text-ink-300">{{ __('workspace.contribution_establishment_intro') }}</p>
        <p class="mt-2 text-sm">
            <a href="{{ route('help.index', ['sectionKey' => 'navigation.help']) }}" class="font-semibold text-ember-600 hover:text-ember-700 dark:text-ember-400">{{ __('workspace.add_spa_help_link') }} &rarr;</a>
        </p>
    @else
        <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ $establishment ? __('editorial.edit') : __('editorial.new') }} — {{ __('editorial.establishments') }}</h1>
    @endif

    @error('form')<p class="mt-4 text-sm text-red-700 dark:text-red-300" role="alert">{{ $message }}</p>@enderror

    <form wire:submit="save" class="mt-6 space-y-5 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
        @if ($isContribution && $currentStep === 1)
            @include('livewire.workspace.establishment-form._who-you-are')
        @else
            <div x-data="{ tab: 'identity' }">
                <x-editorial.tab-bar :tabs="[
                    'identity' => __('editorial.tab_identity'),
                    'classification' => __('editorial.tab_classification'),
                    'access' => __('editorial.tab_access'),
                    'location' => __('editorial.tab_location'),
                    'contact' => __('editorial.tab_contact'),
                    'hours' => __('editorial.tab_hours'),
                    'facilities' => __('editorial.tab_facilities'),
                    'amenities' => __('editorial.tab_amenities'),
                ]" />

                @include('livewire.workspace.establishment-form._tab-identity')
                @include('livewire.workspace.establishment-form._tab-classification')
                @include('livewire.workspace.establishment-form._tab-access')
                @include('livewire.workspace.establishment-form._tab-location')
                @include('livewire.workspace.establishment-form._tab-contact')
                @include('livewire.workspace.establishment-form._tab-hours')
                @include('livewire.workspace.establishment-form._tab-facilities')
                @include('livewire.workspace.establishment-form._tab-amenities')
            </div>

            <div class="flex items-center justify-between gap-2.5 border-t border-ink-100 pt-5 dark:border-ink-800">
                @if ($isContribution)
                    <button type="button" wire:click="prevStep" class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('editorial.back') }}</button>
                    <button type="button" wire:click="nextStep" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('editorial.next') }}</button>
                @else
                    <a href="{{ route('workspace.editorial.establishment.index') }}" wire:navigate class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('editorial.cancel') }}</a>
                    <button type="submit" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('editorial.save') }}</button>
                @endif
            </div>
        @endif

        @if ($isContribution && $currentStep === 3)
            @include('livewire.workspace.establishment-form._review-submit')
        @endif
    </form>
</div>
```

Note: this references tab partials created in Tasks 11-14 and the review partial from Task 15 — they don't exist yet, so this step alone will break the editorial-mode render. Proceed to Step 6 immediately, which creates minimal versions of every tab partial by extracting the current inline tab markup verbatim (no field changes yet — those land in later tasks).

- [ ] **Step 6: Extract the existing tab markup verbatim into partials**

Create each of the following by copying the corresponding `{{-- Tab --}}` block's *inner content* (without the outer `x-show` wrapper `<div>`, which Step 7 restores) from the current file's git history (`git show HEAD:apps/web/resources/views/livewire/workspace/editorial/establishment-form.blade.php`) into its own file, keeping the `x-show="tab === '...'"` wrapper:

- `_tab-identity.blade.php`: the `{{-- Identity --}}` block (current lines 24-46, minus the removed email/contact_number/status_record_lifecycle fields — those three fields move to editorial-only display in Task 11, so for this extraction step keep them as-is; Task 11 removes them for contribution mode).
- `_tab-classification.blade.php`: current lines 48-65.
- `_tab-access.blade.php`: current lines 67-83.
- `_tab-location.blade.php`: current lines 85-120.
- `_tab-contact.blade.php`: current lines 122-151.
- `_tab-hours.blade.php`: current lines 153-171.
- `_tab-facilities.blade.php`: current lines 173-237.
- `_tab-amenities.blade.php`: current lines 239-247, with field references updated from `state.amenities`/`$taxonomy['amenities']` to `state.amenity_list`/`$taxonomy['amenity_list']`, and `state.accessibility_information`/`$taxonomy['accessibility_information']` to `state.accessibility_feature_list`/`$taxonomy['accessibility_feature_list']` (matching Task 9's constant rename — this is the one content change in this extraction step, everything else is a verbatim copy).

Each file keeps its own `<div x-show="tab === '...'" ...>...</div>` wrapper exactly as in the original (the `x-data="{ tab: 'identity' }"` scope from the parent shell still applies since `@include` doesn't create a new Blade/Alpine scope).

- [ ] **Step 7: Run the full existing establishment test suites to confirm no regression**

Run: `php artisan test --filter=EstablishmentCrudTest` and `php artisan test --filter=ContributionTest`
Expected: PASS — editorial mode renders identically (same tab markup, just split across files); the new Step 1 ordering test from this task's Step 1 now passes because `_who-you-are.blade.php` renders before any tab content when `currentStep === 1`.

- [ ] **Step 8: Commit**

```bash
git add apps/web/resources/views/livewire/workspace/editorial/establishment-form.blade.php apps/web/resources/views/livewire/workspace/establishment-form apps/web/lang/eng/workspace.php apps/web/lang/eng/editorial.php apps/web/tests/Feature/Workspace/ContributionTest.php
git commit -m "refactor(establishment-form): split into step shell + tab partials, move who-you-are to step 1"
```

---

### Task 11: Identity + Classification tabs — remove duplication, add opened/closed dates

**Files:**
- Modify: `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`
- Modify: `apps/web/resources/views/livewire/workspace/establishment-form/_tab-identity.blade.php`
- Modify: `apps/web/resources/views/livewire/workspace/establishment-form/_tab-classification.blade.php`
- Modify: `apps/web/lang/eng/editorial.php`

**Interfaces:**
- Consumes: `PLAIN_FIELDS` (Task 9, already includes the six date fields), `status_establishment` taxonomy codes `TC`/`PC`/`RL` (verified in spec).
- Produces: `rules()` gains conditional `date_opened`/`date_closed` validation; contribution mode no longer renders or accepts `email`, `contact_number`, `status_record_lifecycle` on the Identity tab.

- [ ] **Step 1: Write the failing tests**

Add to `apps/web/tests/Feature/Workspace/ContributionTest.php`:

```php
    public function test_contribution_identity_tab_hides_email_contact_and_lifecycle_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/workspace/contribution/establishment/new');

        $response->assertDontSee(__('editorial.est_status_record_lifecycle'));
    }

    public function test_closed_date_is_required_when_status_is_permanently_closed(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.status_establishment', 'PC')
            ->set('state.date_closed', '')
            ->call('nextStep')
            ->assertHasErrors(['state.date_closed' => 'required']);
    }

    public function test_closed_date_not_required_when_status_is_operating(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.display_name_eng', 'Calm Springs')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->call('nextStep')
            ->assertHasNoErrors(['state.date_closed']);
    }
```

- [ ] **Step 2: Run tests to verify they fail**

Run: `php artisan test --filter=ContributionTest`
Expected: FAIL — email/contact/lifecycle still render in contribution mode; `date_closed` has no conditional `required` rule yet.

- [ ] **Step 3: Update `rules()` for conditional closed-date validation**

In `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`, in the `rules()` method, add after the `'state.status_record_lifecycle' => [...]` line:

```php
            'state.date_opened' => ['nullable', 'date'],
            'state.date_opened_precision' => ['nullable', 'string', Rule::in(['D', 'M', 'Y', 'U'])],
            'state.date_opened_qualifier' => ['nullable', 'string', Rule::in(['EXA', 'APP', 'BFR', 'AFT', 'RNG', 'OPS', 'OPE'])],
            'state.date_closed' => [
                Rule::requiredIf(in_array($this->state['status_establishment'] ?? null, ['TC', 'PC', 'RL'], true)),
                'nullable', 'date', 'after_or_equal:state.date_opened',
            ],
            'state.date_closed_precision' => ['nullable', 'string', Rule::in(['D', 'M', 'Y', 'U'])],
            'state.date_closed_qualifier' => ['nullable', 'string', Rule::in(['EXA', 'APP', 'BFR', 'AFT', 'RNG', 'OPS', 'OPE'])],
```

Note: `after_or_equal:state.date_opened` requires both to be present to compare; Laravel's `after_or_equal` on a dotted sibling field works via the validator's data array (both keys exist under `state.*`), so this resolves correctly.

- [ ] **Step 4: Update the Identity tab partial**

In `_tab-identity.blade.php`, wrap the email/contact-number grid and the lifecycle field with `@unless ($isContribution)`:

```blade
<div x-show="tab === 'identity'" class="mt-5 space-y-5">
    <x-form.field :label="__('editorial.est_display_name_eng')" :error="$errors->first('state.display_name_eng')">
        <x-form.input wire:model="state.display_name_eng" maxlength="255" />
    </x-form.field>
    <x-form.field :label="__('editorial.est_short_description_eng')" :error="$errors->first('state.short_description_eng')">
        <x-form.textarea wire:model="state.short_description_eng" rows="3" />
    </x-form.field>
    <x-form.field :label="__('editorial.est_description_eng')" :error="$errors->first('state.description_eng')">
        <x-form.textarea wire:model="state.description_eng" rows="8" />
    </x-form.field>
    @unless ($isContribution)
        <div class="grid gap-5 sm:grid-cols-2">
            <x-form.field :label="__('editorial.est_email')" :error="$errors->first('state.email')">
                <x-form.input wire:model="state.email" type="email" maxlength="255" />
            </x-form.field>
            <x-form.field :label="__('editorial.est_contact_number')" :error="$errors->first('state.contact_number')">
                <x-form.input wire:model="state.contact_number" maxlength="255" />
            </x-form.field>
        </div>
        <x-form.field :label="__('editorial.est_status_record_lifecycle')" :error="$errors->first('state.status_record_lifecycle')">
            <x-form.select wire:model="state.status_record_lifecycle" :options="$lifecycleOptions" />
        </x-form.field>
    @endunless
</div>
```

- [ ] **Step 5: Add the opened/closed date fields to the Classification tab**

Add translation keys to `apps/web/lang/eng/editorial.php` after `'est_status_establishment' => ...`:

```php
    'est_date_opened' => 'Date Opened',
    'est_date_closed' => 'Date Closed',
    'date_precision_label' => 'Precision',
    'date_approximate_label' => 'Approximate',
```

In `_tab-classification.blade.php`, add after the `status_establishment` field:

```blade
    <x-form.field :label="__('editorial.est_date_opened')" :error="$errors->first('state.date_opened')">
        <x-form.input wire:model="state.date_opened" type="date" />
    </x-form.field>
    <x-form.field :label="__('editorial.date_precision_label')">
        <x-form.select wire:model="state.date_opened_precision" :options="['D' => 'Day', 'M' => 'Month', 'Y' => 'Year']" :placeholder="__('editorial.select_placeholder')" />
    </x-form.field>
    <label class="flex items-center gap-2 text-sm text-ink-700 dark:text-ink-300">
        <input type="checkbox" wire:model="state.date_opened_is_approximate" class="rounded border-ink-300 text-ember-600 focus:ring-ember-500">
        {{ __('editorial.date_approximate_label') }}
    </label>

    @if (in_array($state['status_establishment'], ['TC', 'PC', 'RL'], true))
        <x-form.field :label="__('editorial.est_date_closed')" :error="$errors->first('state.date_closed')">
            <x-form.input wire:model="state.date_closed" type="date" />
        </x-form.field>
        <x-form.field :label="__('editorial.date_precision_label')">
            <x-form.select wire:model="state.date_closed_precision" :options="['D' => 'Day', 'M' => 'Month', 'Y' => 'Year']" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
        <label class="flex items-center gap-2 text-sm text-ink-700 dark:text-ink-300">
            <input type="checkbox" wire:model="state.date_closed_is_approximate" class="rounded border-ink-300 text-ember-600 focus:ring-ember-500">
            {{ __('editorial.date_approximate_label') }}
        </label>
    @endif
```

This uses a simpler `date_opened_is_approximate`/`date_closed_is_approximate` checkbox pair (not stored on the model) that the component translates into the `EXA`/`APP` qualifier — add to `save()` and `submitContribution()` in Task 15 where `proposed_data`/the model write happens. For now, add two more transient public properties (not part of `$state`, mirroring how `isContribution` itself is a top-level property) to `EstablishmentForm.php`:

```php
    public bool $date_opened_is_approximate = false;

    public bool $date_closed_is_approximate = false;
```

and set `$this->state['date_opened_qualifier']`/`$this->state['date_closed_qualifier']` from these two booleans at the top of `save()` (both branches), before validation:

```php
    public function save(): void
    {
        $this->state['date_opened_qualifier'] = $this->date_opened_is_approximate ? 'APP' : 'EXA';
        $this->state['date_closed_qualifier'] = $this->date_closed_is_approximate ? 'APP' : 'EXA';

        $this->validate();
```

- [ ] **Step 6: Run tests to verify they pass**

Run: `php artisan test --filter=ContributionTest`
Expected: PASS

- [ ] **Step 7: Commit**

```bash
git add apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php apps/web/resources/views/livewire/workspace/establishment-form/_tab-identity.blade.php apps/web/resources/views/livewire/workspace/establishment-form/_tab-classification.blade.php apps/web/lang/eng/editorial.php apps/web/tests/Feature/Workspace/ContributionTest.php
git commit -m "feat(establishment-form): remove duplicate contact fields from contribution Identity tab, add opened/closed dates"
```

---

### Task 12: Location tab — structured address, country/region select, map picker, parking

**Files:**
- Modify: `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`
- Modify: `apps/web/resources/views/livewire/workspace/establishment-form/_tab-location.blade.php`
- Modify: `apps/web/lang/eng/editorial.php`
- Modify: `apps/web/package.json`, `apps/web/vite.config.js`
- Create: `apps/web/resources/js/establishment-map.js`

**Interfaces:**
- Consumes: `App\Support\Address\AddressLookup` (Task 6).
- Produces: `state.country_id`, `state.region_id`, `state.city_name` (free text, becomes a select automatically once `AddressLookup::cities()` returns data), `state.street_address`, `state.building_name`, `state.floor_label`, `state.unit_label`, `state.postal_code`, `state.official_name`, `state.parking_availability_list` (chip group), `state.parking_note_eng` (kept); `updatedStateCountryId()`/`updatedStateRegionId()` Livewire hooks; `composeAddressPublic()` method.

- [ ] **Step 1: Write the failing test**

```php
    public function test_location_tab_offers_region_select_and_auto_composes_address(): void
    {
        \App\Models\Reference\Country::query()->create(['_id' => 608, 'country_name' => ['eng' => ['text' => 'Philippines']]]);
        \App\Models\Reference\Region::query()->create(['_id' => 1, 'country_id' => 608, 'region_name' => ['eng' => ['text' => 'National Capital Region']]]);

        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.country_id', 608)
            ->set('state.region_id', 1)
            ->set('state.city_name', 'Makati')
            ->set('state.street_address', '123 Bay Street')
            ->call('composeAddressPublic');

        $test->assertSet('state.address_public', '123 Bay Street, Makati, National Capital Region, Philippines');

        \App\Models\Reference\Region::query()->getConnection()->getCollection('region_main')->deleteMany([]);
        \App\Models\Reference\Country::query()->getConnection()->getCollection('country_main')->deleteMany([]);
    }
```

Add this to `apps/web/tests/Feature/Workspace/ContributionTest.php`.

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=ContributionTest`
Expected: FAIL — `composeAddressPublic` method doesn't exist; `country_id`/`region_id`/`city_name` aren't tracked fields yet.

- [ ] **Step 3: Extend `PLAIN_FIELDS` and add the compose method**

In `PLAIN_FIELDS`, add `'official_name', 'country_id', 'region_id', 'city_name', 'street_address', 'building_name', 'floor_label', 'unit_label', 'postal_code',` (insert after `'address_public',`).

Add the compose method and Livewire hooks after `removeRow()`:

```php
    public function composeAddressPublic(): void
    {
        $lookup = app(\App\Support\Address\AddressLookup::class);
        $parts = array_filter([
            $this->state['street_address'] ?? null,
            $this->state['city_name'] ?? null,
            filled($this->state['region_id'] ?? null) ? ($lookup->regions((int) $this->state['country_id'])[(string) $this->state['region_id']] ?? null) : null,
            filled($this->state['country_id'] ?? null) ? ($lookup->countries()[(string) $this->state['country_id']] ?? null) : null,
        ]);

        $this->state['address_public'] = implode(', ', $parts);
    }

    public function updatedStateCountryId(): void
    {
        $this->state['region_id'] = '';
        $this->state['city_name'] = '';
    }

    /** @return array<string, string> */
    public function citiesForSelectedRegion(): array
    {
        return filled($this->state['region_id'] ?? null)
            ? app(\App\Support\Address\AddressLookup::class)->cities((int) $this->state['region_id'])
            : [];
    }
```

- [ ] **Step 4: Add translation keys**

In `apps/web/lang/eng/editorial.php`, add:

```php
    'est_official_name' => 'Official Name',
    'est_country' => 'Country',
    'est_region' => 'Region',
    'est_city' => 'City/Municipality',
    'est_street_address' => 'Street Address',
    'est_building_name' => 'Building Name',
    'est_floor_label' => 'Floor',
    'est_unit_label' => 'Unit',
    'est_postal_code' => 'Postal Code',
    'compose_address_action' => 'Update address from these fields',
    'map_picker_label' => 'Pin the location on the map',
    'map_picker_hint' => 'Drag the pin, or enter coordinates manually below.',
    'est_parking_availability' => 'Parking',
```

- [ ] **Step 5: Rewrite `_tab-location.blade.php`**

```blade
<div x-show="tab === 'location'" x-cloak class="mt-5 space-y-5">
    <x-form.field :label="__('editorial.est_official_name')" :error="$errors->first('state.official_name')">
        <x-form.input wire:model="state.official_name" maxlength="255" />
    </x-form.field>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-form.field :label="__('editorial.est_country')" :error="$errors->first('state.country_id')">
            <x-form.select wire:model.live="state.country_id" :options="app(\App\Support\Address\AddressLookup::class)->countries()" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_region')" :error="$errors->first('state.region_id')">
            <x-form.select wire:model.live="state.region_id" :options="filled($state['country_id']) ? app(\App\Support\Address\AddressLookup::class)->regions((int) $state['country_id']) : []" :placeholder="__('editorial.select_placeholder')" />
        </x-form.field>
    </div>

    @php($cities = $this->citiesForSelectedRegion())
    <x-form.field :label="__('editorial.est_city')" :error="$errors->first('state.city_name')">
        @if (count($cities))
            <x-form.select wire:model="state.city_name" :options="$cities" :placeholder="__('editorial.select_placeholder')" />
        @else
            <x-form.input wire:model="state.city_name" maxlength="255" />
        @endif
    </x-form.field>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-form.field :label="__('editorial.est_street_address')" :error="$errors->first('state.street_address')">
            <x-form.input wire:model="state.street_address" maxlength="255" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_building_name')" :error="$errors->first('state.building_name')">
            <x-form.input wire:model="state.building_name" maxlength="255" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_floor_label')" :error="$errors->first('state.floor_label')">
            <x-form.input wire:model="state.floor_label" maxlength="50" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_unit_label')" :error="$errors->first('state.unit_label')">
            <x-form.input wire:model="state.unit_label" maxlength="50" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_postal_code')" :error="$errors->first('state.postal_code')">
            <x-form.input wire:model="state.postal_code" maxlength="20" />
        </x-form.field>
    </div>

    <button type="button" wire:click="composeAddressPublic" class="rounded-lg border border-dashed border-ink-300 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-ember-400 hover:text-ember-600 dark:border-ink-600 dark:text-ink-300">{{ __('editorial.compose_address_action') }}</button>

    <x-form.field :label="__('editorial.est_address_public')" :error="$errors->first('state.address_public')">
        <x-form.textarea wire:model="state.address_public" rows="2" />
    </x-form.field>

    <div wire:ignore
         data-map-picker
         data-lat-input="state.coordinate_latitude"
         data-lng-input="state.coordinate_longitude"
         data-lat="{{ $state['coordinate_latitude'] ?: 14.5995 }}"
         data-lng="{{ $state['coordinate_longitude'] ?: 120.9842 }}"
         class="space-y-2">
        <x-form.field :label="__('editorial.map_picker_label')" :help="__('editorial.map_picker_hint')">
            <div data-map-picker-canvas class="h-64 w-full rounded-xl border border-ink-200 dark:border-ink-700"></div>
        </x-form.field>
    </div>
    <div class="grid gap-5 sm:grid-cols-2">
        <x-form.field :label="__('editorial.est_coordinate_latitude')" :error="$errors->first('state.coordinate_latitude')">
            <x-form.input wire:model="state.coordinate_latitude" type="number" step="any" />
        </x-form.field>
        <x-form.field :label="__('editorial.est_coordinate_longitude')" :error="$errors->first('state.coordinate_longitude')">
            <x-form.input wire:model="state.coordinate_longitude" type="number" step="any" />
        </x-form.field>
    </div>

    <x-form.field :label="__('editorial.est_direction_note_eng')" :error="$errors->first('state.direction_note_eng')">
        <x-form.textarea wire:model="state.direction_note_eng" rows="2" />
    </x-form.field>

    <x-form.field :label="__('editorial.est_parking_availability')">
        <x-form.toggle-group :options="$taxonomy['parking_availability']" model="state.parking_availability_list" />
    </x-form.field>
    <x-form.field :label="__('editorial.est_parking_note_eng')" :error="$errors->first('state.parking_note_eng')">
        <x-form.textarea wire:model="state.parking_note_eng" rows="2" />
    </x-form.field>

    <div class="space-y-3">
        <p class="text-sm font-semibold text-ink-800 dark:text-ink-200">{{ __('editorial.landmarks') }}</p>
        @foreach ($state['landmark_list'] as $i => $row)
            <div class="grid gap-3 rounded-xl border border-ink-100 p-3 sm:grid-cols-[1fr_10rem_auto] dark:border-ink-800" wire:key="landmark-{{ $i }}">
                <x-form.field :label="__('editorial.landmark_name')" :error="$errors->first('state.landmark_list.'.$i.'.landmark_name')">
                    <x-form.input wire:model="state.landmark_list.{{ $i }}.landmark_name" />
                </x-form.field>
                <x-form.field :label="__('editorial.walking_minutes')" :error="$errors->first('state.landmark_list.'.$i.'.walking_duration_minute')">
                    <x-form.input wire:model="state.landmark_list.{{ $i }}.walking_duration_minute" type="number" min="0" />
                </x-form.field>
                <button type="button" wire:click="removeRow('landmark_list', {{ $i }})" class="self-end rounded-lg border border-ink-200 px-3 py-2 text-sm font-semibold text-ink-500 transition hover:border-ember-300 hover:text-ember-600 dark:border-ink-700 dark:text-ink-400">{{ __('editorial.remove') }}</button>
                <div class="sm:col-span-3">
                    <x-form.field :label="__('editorial.landmark_direction_note')" :error="$errors->first('state.landmark_list.'.$i.'.direction_note_eng')">
                        <x-form.input wire:model="state.landmark_list.{{ $i }}.direction_note_eng" maxlength="255" />
                    </x-form.field>
                </div>
            </div>
        @endforeach
        <button type="button" wire:click="addRow('landmark_list')" class="rounded-lg border border-dashed border-ink-300 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-ember-400 hover:text-ember-600 dark:border-ink-600 dark:text-ink-300">{{ __('editorial.add_row') }}</button>
    </div>
</div>
```

Add `'landmark_direction_note' => 'Direction note',` to `editorial.php`. Update the `REPEATERS` constant in `EstablishmentForm.php`:

```php
        'landmark_list' => ['landmark_name' => '', 'walking_duration_minute' => null, 'direction_note_eng' => ''],
```

(the `save()`/`submitContribution()` translated-repeater handling for `landmark_list.*.direction_note_eng` → guide's `landmark_list.*.direction_note.eng` shape is handled in Task 15 alongside the rest of the guide-conformant payload mapping.)

- [ ] **Step 6: Install Leaflet and add the map script**

Run (from `apps/web/`): `npm install leaflet@^1.9`

In `apps/web/vite.config.js`, change the `input` array:

```js
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/article-editor.js', 'resources/js/establishment-map.js'],
```

(add `resources/js/article-editor.js` too if it was previously loaded another way — verify by checking `resources/views/article/*.blade.php` for a `@vite` call; if already present there, don't duplicate, just add `establishment-map.js`.)

Create `resources/js/establishment-map.js`:

```js
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

function initMapPicker(container) {
    const canvas = container.querySelector('[data-map-picker-canvas]');
    const latInputName = container.dataset.latInput;
    const lngInputName = container.dataset.lngInput;
    const lat = parseFloat(container.dataset.lat);
    const lng = parseFloat(container.dataset.lng);

    const map = L.map(canvas).setView([lat, lng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    const marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    const component = window.Livewire.find(container.closest('[wire\\:id]').getAttribute('wire:id'));

    marker.on('dragend', () => {
        const { lat, lng } = marker.getLatLng();
        component.set(latInputName, Number(lat.toFixed(6)));
        component.set(lngInputName, Number(lng.toFixed(6)));
    });

    map.on('click', (event) => {
        marker.setLatLng(event.latlng);
        component.set(latInputName, Number(event.latlng.lat.toFixed(6)));
        component.set(lngInputName, Number(event.latlng.lng.toFixed(6)));
    });
}

document.addEventListener('livewire:navigated', () => {
    document.querySelectorAll('[data-map-picker]').forEach(initMapPicker);
});
document.querySelectorAll('[data-map-picker]').forEach(initMapPicker);
```

Add `@vite(['resources/js/establishment-map.js'])` to the `<head>` section of `apps/web/resources/views/layouts/workspace.blade.php` (check the file first for where other `@vite` calls live and match that placement).

- [ ] **Step 7: Run test to verify it passes**

Run: `php artisan test --filter=ContributionTest`
Expected: PASS (the PHP-side compose logic; the map picker itself needs manual browser verification per Task 17's manual-pass step, since it's client-side JS with no PHP test coverage).

- [ ] **Step 8: Commit**

```bash
git add apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php apps/web/resources/views/livewire/workspace/establishment-form/_tab-location.blade.php apps/web/lang/eng/editorial.php apps/web/package.json apps/web/package-lock.json apps/web/vite.config.js apps/web/resources/js/establishment-map.js apps/web/resources/views/layouts/workspace.blade.php apps/web/tests/Feature/Workspace/ContributionTest.php
git commit -m "feat(establishment-form): rebuild Location tab with structured address, region select, and map picker"
```

---

### Task 13: Contact tab — dynamic per-channel-type fields

**Files:**
- Modify: `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`
- Modify: `apps/web/resources/views/livewire/workspace/establishment-form/_tab-contact.blade.php`

**Interfaces:**
- Consumes: `TaxonomyOptions::for('type_contact_channel')` (existing).
- Produces: `channelNeedsPhoneType(string $channelType): bool`, `channelValueInputType(string $channelType): string` helper methods.

- [ ] **Step 1: Write the failing test**

```php
    public function test_contact_channel_hides_phone_type_for_email_channels(): void
    {
        $user = User::factory()->create();

        $test = Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->call('addRow', 'contact_channel_list')
            ->set('state.contact_channel_list.0.type_contact_channel', 'EML');

        $this->assertFalse($test->instance()->channelNeedsPhoneType('EML'));
        $this->assertTrue($test->instance()->channelNeedsPhoneType('PHN'));
    }
```

(Verify the actual taxonomy option codes for `type_contact_channel` before writing this test — run `php tools/script/build_field_index.php --where type_contact_channel` and inspect the JSON to confirm the phone/email codes; adjust `'EML'`/`'PHN'` to the real codes found.)

Add this to `apps/web/tests/Feature/Workspace/ContributionTest.php`.

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=ContributionTest`
Expected: FAIL — method doesn't exist.

- [ ] **Step 3: Inspect the real `type_contact_channel` and `type_contact_number` option codes**

Run: `python -c "import json; d=json.load(open('data/taxonomy/shared/person_identity_and_contact.json',encoding='utf-8')); [print(i['field_name'],[o['option_code'] for o in i.get('field_option',[])]) for i in d if i['field_name'] in ('type_contact_channel','type_contact_number')]"` (from repository root)

Use the actual returned codes in Steps 4-5 below instead of placeholder `EML`/`PHN` — this plan cannot guess them correctly without running this inspection first.

- [ ] **Step 4: Add the helper methods**

```php
    /** Codes from type_contact_channel that represent a phone number and therefore need the number-type subfield. Confirm against data/taxonomy/shared/person_identity_and_contact.json. */
    private const PHONE_CHANNEL_CODES = ['PHN']; // placeholder — replace with the confirmed code(s) from Step 3

    public function channelNeedsPhoneType(string $channelType): bool
    {
        return in_array($channelType, self::PHONE_CHANNEL_CODES, true);
    }

    public function channelValueInputType(string $channelType): string
    {
        return match ($channelType) {
            'EML' => 'email', // placeholder — replace with the confirmed email code
            default => 'text',
        };
    }
```

- [ ] **Step 5: Update the Contact tab partial**

In `_tab-contact.blade.php`, wrap the `type_contact_number` field:

```blade
                        @if ($this->channelNeedsPhoneType($row['type_contact_channel'] ?? ''))
                            <x-form.field :label="__('editorial.est_type_contact_number')" :error="$errors->first('state.contact_channel_list.'.$i.'.type_contact_number')">
                                <x-form.select wire:model="state.contact_channel_list.{{ $i }}.type_contact_number" :options="$taxonomy['type_contact_number']" :placeholder="__('editorial.select_placeholder')" />
                            </x-form.field>
                        @endif
```

and change the `contact_value` input:

```blade
                        <x-form.field :label="__('editorial.est_contact_value')" :error="$errors->first('state.contact_channel_list.'.$i.'.contact_value')">
                            <x-form.input wire:model="state.contact_channel_list.{{ $i }}.contact_value" type="{{ $this->channelValueInputType($row['type_contact_channel'] ?? '') }}" maxlength="255" />
                        </x-form.field>
```

- [ ] **Step 6: Update `rules()` to make `type_contact_number` conditionally required**

Change the existing rule:
```php
            'state.contact_channel_list.*.type_contact_channel' => ['required', 'string'],
```
to add `Rule::in(array_keys(TaxonomyOptions::for('type_contact_channel')))` (this also closes a validation gap from spec section 8 — this field currently accepts any string):
```php
            'state.contact_channel_list.*.type_contact_channel' => ['required', 'string', Rule::in(array_keys(TaxonomyOptions::for('type_contact_channel')))],
```

- [ ] **Step 7: Run test to verify it passes**

Run: `php artisan test --filter=ContributionTest`
Expected: PASS

- [ ] **Step 8: Commit**

```bash
git add apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php apps/web/resources/views/livewire/workspace/establishment-form/_tab-contact.blade.php apps/web/tests/Feature/Workspace/ContributionTest.php
git commit -m "feat(establishment-form): make Contact tab fields adapt to channel type"
```

---

### Task 14: Hours closed-toggle + Facilities/Amenities physical-premises hide/strip rule

**Files:**
- Modify: `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`
- Modify: `apps/web/resources/views/livewire/workspace/establishment-form/_tab-hours.blade.php`
- Modify: `apps/web/resources/views/livewire/workspace/establishment-form/_tab-facilities.blade.php`
- Modify: `apps/web/resources/views/livewire/workspace/establishment-form/_tab-amenities.blade.php`
- Modify: `apps/web/resources/views/livewire/workspace/editorial/establishment-form.blade.php` (tab-bar list)

**Interfaces:**
- Produces: `EstablishmentForm::hasPhysicalPremises(): bool` — computed from `$state['mode_service_delivery']` and `$state['type_spa']`; used to hide the Facilities tab client-side and strip its fields server-side in `save()`/`submitContribution()`.

- [ ] **Step 1: Write the failing tests**

```php
    public function test_facilities_tab_hidden_for_home_service_only_spa(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.type_spa', 'HP')
            ->set('state.mode_service_delivery', ['HM']);

        $this->assertFalse($test->instance()->hasPhysicalPremises());
    }

    public function test_facility_fields_are_stripped_server_side_for_home_service_only_spa_even_if_submitted(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 3)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Mobile Massage Co')
            ->set('state.type_spa', 'HP')
            ->set('state.mode_service_delivery', ['HM'])
            ->set('state.status_establishment', 'OP')
            ->set('state.shower_availability', 'IR')
            ->call('save');

        $contribution = \App\Models\Contribution::query()->where('submitted_by_user_id', (string) auth()->id())->first();
        $this->assertNull(data_get($contribution->proposed_data, 'establishment.shower_availability'));
    }
```

Add these to `apps/web/tests/Feature/Workspace/ContributionTest.php`.

- [ ] **Step 2: Run tests to verify they fail**

Run: `php artisan test --filter=ContributionTest`
Expected: FAIL — `hasPhysicalPremises()` doesn't exist; facility fields aren't stripped.

- [ ] **Step 3: Add the computed method**

```php
    private const HOME_SERVICE_ONLY_TYPES = ['HP', 'MB'];

    public function hasPhysicalPremises(): bool
    {
        $deliversOnSite = in_array('OS', $this->state['mode_service_delivery'] ?? [], true);
        $homeServiceOnlyType = in_array($this->state['type_spa'] ?? '', self::HOME_SERVICE_ONLY_TYPES, true);

        return $deliversOnSite || ! $homeServiceOnlyType;
    }

    /** @return list<string> Field names to strip from a submission when hasPhysicalPremises() is false. */
    private const PHYSICAL_PREMISES_FIELDS = [
        'shower_availability', 'sauna_availability', 'steam_room_availability',
        'jacuzzi_availability', 'locker_availability', 'couple_room_availability',
        'private_room_availability', 'curtain_divider_information',
        'air_conditioning_information', 'room_types', 'bed_mat_chair_setup',
        'amenity_list', 'accessibility_feature_list',
    ];
```

- [ ] **Step 4: Strip stripped fields in both save paths**

In `save()`, immediately before the `$plain = [];` loop (direct-write path), add:

```php
        if (! $this->hasPhysicalPremises()) {
            foreach (self::PHYSICAL_PREMISES_FIELDS as $field) {
                $this->state[$field] = in_array($field, self::LIST_FIELDS, true) ? [] : null;
            }
            $this->state['treatment_area_list'] = [];
        }
```

In `submitContribution()`, add the identical block immediately before the `$proposedData = [];` loop.

- [ ] **Step 5: Hide the Facilities tab client-side**

In `apps/web/resources/views/livewire/workspace/editorial/establishment-form.blade.php`, change the tabs array:

```php
                <x-editorial.tab-bar :tabs="array_filter([
                    'identity' => __('editorial.tab_identity'),
                    'classification' => __('editorial.tab_classification'),
                    'access' => __('editorial.tab_access'),
                    'location' => __('editorial.tab_location'),
                    'contact' => __('editorial.tab_contact'),
                    'hours' => __('editorial.tab_hours'),
                    'facilities' => $this->hasPhysicalPremises() ? __('editorial.tab_facilities') : null,
                    'amenities' => __('editorial.tab_amenities'),
                ])" />
```

In `_tab-facilities.blade.php`, wrap the entire content in `@if ($this->hasPhysicalPremises())`.

- [ ] **Step 6: Add the closed-day toggle to Hours**

In `_tab-hours.blade.php`, add a checkbox before the day-of-week select in each row:

```blade
                        <label class="flex items-center gap-2 self-end pb-2 text-sm text-ink-700 dark:text-ink-300">
                            <input type="checkbox" wire:model.live="state.operating_hours.{{ $i }}.is_closed" class="rounded border-ink-300 text-ember-600 focus:ring-ember-500">
                            {{ __('editorial.closed_all_day') }}
                        </label>
```

Add `'closed_all_day' => 'Closed',` to `editorial.php`. Update the `REPEATERS` constant's `operating_hours` blank shape:

```php
        'operating_hours' => ['day_of_week' => '', 'open_time' => null, 'close_time' => null, 'is_closed' => false],
```

Add an `updatedState` hook to clear times when closed is checked:

```php
    public function updatedState(mixed $value, string $key): void
    {
        if (preg_match('/^operating_hours\.(\d+)\.is_closed$/', $key, $matches) && $value) {
            $this->state['operating_hours'][(int) $matches[1]]['open_time'] = null;
            $this->state['operating_hours'][(int) $matches[1]]['close_time'] = null;
        }
    }
```

- [ ] **Step 7: Run tests to verify they pass**

Run: `php artisan test --filter=ContributionTest`
Expected: PASS

- [ ] **Step 8: Commit**

```bash
git add apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php apps/web/resources/views/livewire/workspace/establishment-form/_tab-hours.blade.php apps/web/resources/views/livewire/workspace/establishment-form/_tab-facilities.blade.php apps/web/resources/views/livewire/workspace/editorial/establishment-form.blade.php apps/web/lang/eng/editorial.php apps/web/tests/Feature/Workspace/ContributionTest.php
git commit -m "feat(establishment-form): hide/strip facility fields for home-service-only spas, add closed-day toggle"
```

---

### Task 15: Step 3 — review & submit, duplicate check, guide-conformant payload

**Files:**
- Modify: `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`
- Create: `apps/web/resources/views/livewire/workspace/establishment-form/_review-submit.blade.php`
- Modify: `apps/web/lang/eng/workspace.php`
- Test: `apps/web/tests/Feature/Workspace/ContributionPayloadShapeTest.php` (new — the drift guard)

**Interfaces:**
- Consumes: `DuplicateEstablishmentFinder` (Task 8), `EstablishmentContributionSubmitted` (Task 7), `AddressLookup` (Task 6, for region eligibility check).
- Produces: `submitContribution()` writes the namespaced `proposed_data` shape (`establishment`, `contact_channel_list`, `operating_schedule`, `event_list`); dispatches `EstablishmentContributionSubmitted`; `checkForDuplicates()` populates `$duplicateCandidates` and requires `$duplicateAcknowledged` before submit when non-empty.

- [ ] **Step 1: Write the failing drift-guard test**

```php
<?php

namespace Tests\Feature\Workspace;

use App\Livewire\Workspace\Editorial\EstablishmentForm;
use App\Models\Contribution;
use App\Models\User;
use Livewire\Livewire;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class ContributionPayloadShapeTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function tearDown(): void
    {
        Contribution::query()->delete();
        parent::tearDown();
    }

    public function test_proposed_data_establishment_keys_are_all_in_the_guide_field_order(): void
    {
        $guide = require base_path('../../data/structure_guide/establishment_main.php');
        $systemOwned = [
            '_id', 'establishment_slug', 'previous_slug_list', 'status_record_lifecycle',
            'revision_number', 'created_at', 'updated_at', 'last_confirmed_at',
        ];
        $allowedKeys = array_diff($guide['establishment_main_field_order'], $systemOwned);

        $user = User::factory()->create();
        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 3)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->set('duplicateAcknowledged', true)
            ->call('save');

        $contribution = Contribution::query()->where('submitted_by_user_id', (string) $user->getKey())->firstOrFail();

        foreach (array_keys($contribution->proposed_data['establishment']) as $key) {
            $this->assertContains($key, $allowedKeys, "proposed_data.establishment has an unexpected key: {$key}");
        }
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=ContributionPayloadShapeTest`
Expected: FAIL — `proposed_data` is currently flat, not namespaced under `establishment`.

- [ ] **Step 3: Add duplicate-check and visit-request state**

```php
    /** @var \Illuminate\Support\Collection<int, array{id: string, display_name: string, address_public: ?string, source: string}> */
    public \Illuminate\Support\Collection $duplicateCandidates;

    public bool $duplicateAcknowledged = false;

    public bool $is_visit_requested = false;

    public ?string $visit_preferred_time_note = null;
```

Initialize `$this->duplicateCandidates = collect();` at the top of `mount()`.

Add the check method, called when entering step 3:

```php
    public function checkForDuplicates(): void
    {
        $this->duplicateCandidates = app(\App\Support\Establishment\DuplicateEstablishmentFinder::class)
            ->find($this->state['display_name_eng'] ?? '');
    }

    public function visitEligible(): bool
    {
        $lookup = app(\App\Support\Address\AddressLookup::class);
        $countryLabel = $lookup->countries()[(string) ($this->state['country_id'] ?? '')] ?? null;
        $regionLabel = filled($this->state['country_id'] ?? null)
            ? ($lookup->regions((int) $this->state['country_id'])[(string) ($this->state['region_id'] ?? '')] ?? null)
            : null;

        return $countryLabel === 'Philippines' && $regionLabel === 'National Capital Region';
    }
```

Update `nextStep()` to call `checkForDuplicates()` when moving into step 3:

```php
    public function nextStep(): void
    {
        if (! $this->isContribution) {
            return;
        }

        $this->validate($this->rulesForStep($this->currentStep));
        $this->currentStep = min(3, $this->currentStep + 1);

        if ($this->currentStep === 3) {
            $this->checkForDuplicates();
        }
    }
```

- [ ] **Step 4: Update `rules()` for step 3 fields**

Add to the `if ($this->isContribution)` block in `rules()`:

```php
            $rules['submission_note'] = ['nullable', 'string', 'max:2000'];
            $rules['visit_preferred_time_note'] = ['nullable', 'string', 'max:500', 'required_if:is_visit_requested,true'];
            $rules['duplicateAcknowledged'] = [Rule::requiredIf(fn () => $this->duplicateCandidates->isNotEmpty()), 'accepted'];
```

- [ ] **Step 5: Rewrite `submitContribution()`'s payload shape**

Replace the `$proposedData = [];` block through the `Contribution::query()->create([...])` call with:

```php
        $establishment = [];
        foreach (self::TRANSLATED_FIELDS as $field => $stateKey) {
            $establishment[$field] = ['eng' => $this->state[$stateKey] ?: null];
        }
        foreach (self::PLAIN_FIELDS as $field) {
            $establishment[$field] = $this->state[$field] === '' ? null : $this->state[$field];
        }
        foreach (self::LIST_FIELDS as $field) {
            $establishment[$field] = array_values($this->state[$field] ?? []);
        }
        $establishment['landmark_list'] = array_map(
            fn (array $row) => [
                'landmark_name' => $row['landmark_name'],
                'walking_duration_minute' => $row['walking_duration_minute'],
                'direction_note' => ['eng' => $row['direction_note_eng'] ?: null],
            ],
            array_values($this->state['landmark_list'] ?? []),
        );
        $establishment['treatment_area_list'] = array_values($this->state['treatment_area_list'] ?? []);

        $contactChannelList = array_values($this->state['contact_channel_list'] ?? []);
        $operatingSchedule = array_values($this->state['operating_hours'] ?? []);

        $eventList = [];
        if (filled($this->state['date_opened'] ?? null)) {
            $eventList[] = [
                'type_business_event' => 'OP',
                'effective_date' => $this->state['date_opened'],
                'type_date_precision' => $this->state['date_opened_precision'] ?: 'U',
                'type_date_qualifier' => $this->state['date_opened_qualifier'] ?: 'EXA',
            ];
        }
        if (filled($this->state['date_closed'] ?? null)) {
            $eventList[] = [
                'type_business_event' => 'CL',
                'effective_date' => $this->state['date_closed'],
                'type_date_precision' => $this->state['date_closed_precision'] ?: 'U',
                'type_date_qualifier' => $this->state['date_closed_qualifier'] ?: 'EXA',
            ];
        }

        $contribution = Contribution::query()->create([
            'type_contribution' => 'ADD',
            'target_collection' => 'establishment_main',
            'target_record_id' => null,
            'submitted_by_user_id' => (string) auth()->id(),
            'proposed_data' => [
                'establishment' => $establishment,
                'contact_channel_list' => $contactChannelList,
                'operating_schedule' => $operatingSchedule,
                'event_list' => $eventList,
            ],
            'type_establishment_relationship' => $this->type_establishment_relationship,
            'is_workspace_access_requested' => $this->is_workspace_access_requested,
            'relationship_note' => filled($this->relationship_note) ? trim($this->relationship_note) : null,
            'submission_note' => filled($this->submission_note) ? trim($this->submission_note) : null,
            'duplicate_candidate_establishment_id_list' => $this->duplicateCandidates->pluck('id')->all(),
            'duplicate_acknowledged' => $this->duplicateAcknowledged,
            'is_visit_requested' => $this->is_visit_requested,
            'visit_preferred_time_note' => filled($this->visit_preferred_time_note) ? trim($this->visit_preferred_time_note) : null,
            'status_contribution' => 'PND',
            'submitted_at' => now(),
        ]);

        event(new \App\Events\EstablishmentContributionSubmitted($contribution));

        session()->flash('status', __('workspace.contribution_submitted'));
        $this->redirectRoute('workspace.contribution.index', navigate: true);
```

Keep the existing rate-limit check and the `is_workspace_access_requested`/`type_establishment_relationship` guard clause at the top of the method unchanged.

- [ ] **Step 6: Write the review-and-submit partial**

Add translation keys to `apps/web/lang/eng/workspace.php`:

```php
    'add_spa_review_intro' => 'Review what you\'re submitting before it goes to editorial review.',
    'add_spa_duplicate_warning_title' => 'Is your spa one of these?',
    'add_spa_duplicate_confirm' => 'This is a different spa.',
    'contribution_submission_note_label' => 'Note to the reviewer (optional)',
    'add_spa_visit_label' => 'Request an in-person verification visit',
    'add_spa_visit_time_label' => 'Preferred time to visit',
```

```blade
<div class="space-y-5">
    <h2 class="text-lg font-bold text-ink-950 dark:text-ink-50">{{ __('workspace.add_spa_step_review') }}</h2>
    <p class="text-sm text-ink-600 dark:text-ink-300">{{ __('workspace.add_spa_review_intro') }}</p>

    <div class="rounded-xl border border-ink-100 bg-ink-50 p-4 text-sm dark:border-ink-800 dark:bg-ink-900">
        <p class="font-bold text-ink-900 dark:text-ink-100">{{ $state['display_name_eng'] }}</p>
        <p class="mt-1 text-ink-600 dark:text-ink-300">{{ $state['address_public'] }}</p>
    </div>

    @if ($duplicateCandidates->isNotEmpty())
        <div class="rounded-xl border border-amber-300 bg-amber-50 p-4 dark:border-amber-800 dark:bg-amber-950">
            <p class="font-bold text-amber-900 dark:text-amber-200">{{ __('workspace.add_spa_duplicate_warning_title') }}</p>
            <ul class="mt-2 space-y-1 text-sm text-amber-800 dark:text-amber-300">
                @foreach ($duplicateCandidates as $candidate)
                    <li>{{ $candidate['display_name'] }} — {{ $candidate['address_public'] }}</li>
                @endforeach
            </ul>
            <label class="mt-3 flex items-center gap-2 text-sm font-semibold text-amber-900 dark:text-amber-200">
                <input type="checkbox" wire:model="duplicateAcknowledged" class="rounded border-amber-400 text-ember-600 focus:ring-ember-500">
                {{ __('workspace.add_spa_duplicate_confirm') }}
            </label>
            @error('duplicateAcknowledged')<p class="mt-1 text-sm text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
        </div>
    @endif

    @if ($this->visitEligible())
        <div class="rounded-xl border border-ink-200 p-4 dark:border-ink-700">
            <label class="flex items-start gap-3">
                <input type="checkbox" wire:model.live="is_visit_requested" class="mt-1 rounded border-ink-300 text-ember-600 focus:ring-ember-500">
                <span class="font-bold text-ink-900 dark:text-ink-100">{{ __('workspace.add_spa_visit_label') }}</span>
            </label>
            @if ($is_visit_requested)
                <x-form.field :label="__('workspace.add_spa_visit_time_label')" :error="$errors->first('visit_preferred_time_note')" class="mt-3">
                    <x-form.input wire:model="visit_preferred_time_note" maxlength="500" />
                </x-form.field>
            @endif
        </div>
    @endif

    <x-form.field :label="__('workspace.contribution_submission_note_label')" :error="$errors->first('submission_note')">
        <x-form.textarea wire:model="submission_note" rows="3" maxlength="2000" />
    </x-form.field>

    <div class="flex items-center justify-between gap-2.5 border-t border-ink-100 pt-5 dark:border-ink-800">
        <button type="button" wire:click="prevStep" class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('editorial.back') }}</button>
        <button type="submit" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('workspace.contribution_submit') }}</button>
    </div>
</div>
```

- [ ] **Step 7: Run tests to verify they pass**

Run: `php artisan test --filter=ContributionPayloadShapeTest` and `php artisan test --filter=ContributionTest`
Expected: PASS

- [ ] **Step 8: Commit**

```bash
git add apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php apps/web/resources/views/livewire/workspace/establishment-form/_review-submit.blade.php apps/web/lang/eng/workspace.php apps/web/tests/Feature/Workspace/ContributionPayloadShapeTest.php
git commit -m "feat(establishment-form): build guide-conformant proposed_data, add review step with duplicate check"
```

---

### Task 16: Wording, icons, and spacing pass

**Files:**
- Modify: `apps/web/resources/views/livewire/workspace/editorial/establishment-form.blade.php`
- Modify: `apps/web/resources/views/components/editorial/tab-bar.blade.php`
- Modify: `apps/web/resources/views/components/form/toggle-group.blade.php`
- Modify: `apps/web/lang/eng/editorial.php`, `apps/web/lang/eng/workspace.php`
- Create: `apps/web/resources/views/components/icon/spa-tab.blade.php` (curated tab-icon map)

**Interfaces:**
- Produces: `<x-editorial.tab-bar>` accepts an optional `icons` prop (`[key => svg-path]`); `<x-form.toggle-group>` accepts an optional `icons` prop (`[option-code => svg-path]`, falls back to text-only when a code has no entry).

- [ ] **Step 1: Write the failing test**

```php
    public function test_page_title_is_add_a_spa_not_contribute_an_establishment(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/workspace/contribution/establishment/new');

        $response->assertSee(__('workspace.contribution_establishment_title'));
        $this->assertSame('Add a Spa', __('workspace.contribution_establishment_title'));
    }

    public function test_tab_labels_contain_no_ampersand(): void
    {
        $user = User::factory()->create();

        $content = $this->actingAs($user)->get('/workspace/contribution/establishment/new')->getContent();
        preg_match_all('/<span[^>]*class="truncate"[^>]*>([^<]*)<\/span>/', $content, $matches);

        // Fallback direct check on the translated tab labels themselves:
        foreach (['tab_identity', 'tab_classification', 'tab_access', 'tab_location', 'tab_contact', 'tab_hours', 'tab_amenities'] as $key) {
            $this->assertStringNotContainsString('&', __('editorial.'.$key));
        }
    }
```

Add to `apps/web/tests/Feature/Workspace/ContributionTest.php`.

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=ContributionTest`
Expected: FAIL — `contribution_establishment_title` is currently "Contribute an establishment"; `tab_access` is "Access & delivery".

- [ ] **Step 3: Update wording keys**

In `apps/web/lang/eng/workspace.php`, change:
```php
    'contribution_establishment_title' => 'Contribute an establishment',
    'contribution_establishment_intro' => 'Provide the public details you know. If you are connected to the establishment, declare that relationship so it can be verified separately.',
```
to:
```php
    'contribution_establishment_title' => 'Add a Spa',
    'contribution_establishment_intro' => 'Tell us about the spa and your submission will be reviewed before it goes live. You can save your place and come back to finish a longer entry.',
    'add_spa_help_link' => 'How adding a spa works',
```

In `apps/web/lang/eng/editorial.php`, change:
```php
    'tab_access' => 'Access & delivery',
```
to:
```php
    'tab_access' => 'Access and delivery',
```
and:
```php
    'tab_amenities' => 'Amenities & accessibility',
```
to:
```php
    'tab_amenities' => 'Amenities and accessibility',
```

- [ ] **Step 4: Add icon support to `x-editorial.tab-bar`**

```blade
@props(['tabs' => [], 'icons' => []])
<div class="flex flex-wrap gap-1 border-b border-ink-100 dark:border-ink-800" role="tablist">
    @foreach ($tabs as $key => $label)
        <button type="button" role="tab" @click="tab = '{{ $key }}'"
                :aria-selected="(tab === '{{ $key }}').toString()"
                :class="tab === '{{ $key }}'
                    ? 'border-ember-500 text-ember-600 dark:text-ember-400'
                    : 'border-transparent text-ink-600 hover:text-ink-950 dark:text-ink-300 dark:hover:text-ink-50'"
                class="-mb-px flex items-center gap-1.5 border-b-2 px-3.5 py-2.5 text-sm font-semibold transition">
            @if (isset($icons[$key]))
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4 shrink-0" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$key] }}"/></svg>
            @endif
            {{ $label }}
        </button>
    @endforeach
</div>
```

- [ ] **Step 5: Pass a curated icon map from the parent shell**

In `establishment-form.blade.php`, define the icon map alongside the tabs array (reuse the same icon-path style as `x-workspace-nav`):

```blade
                @php($tabIcons = [
                    'identity' => 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 8a7 7 0 0 1 14 0',
                    'classification' => 'M4 6h16M4 12h16M4 18h16',
                    'access' => 'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z',
                    'location' => 'M12 21s-7-6.2-7-11a7 7 0 1 1 14 0c0 4.8-7 11-7 11Z',
                    'contact' => 'M4 4h16v16H4z M4 7l8 6 8-6',
                    'hours' => 'M12 7v5l3 3 M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z',
                    'facilities' => 'M3 21h18M5 21V8l7-5 7 5v13',
                    'amenities' => 'M12 3l2.5 5.3 5.5.7-4 4 1 5.7-5-2.8-5 2.8 1-5.7-4-4 5.5-.7L12 3Z',
                ])
                <x-editorial.tab-bar :tabs="array_filter([
                    'identity' => __('editorial.tab_identity'),
                    'classification' => __('editorial.tab_classification'),
                    'access' => __('editorial.tab_access'),
                    'location' => __('editorial.tab_location'),
                    'contact' => __('editorial.tab_contact'),
                    'hours' => __('editorial.tab_hours'),
                    'facilities' => $this->hasPhysicalPremises() ? __('editorial.tab_facilities') : null,
                    'amenities' => __('editorial.tab_amenities'),
                ])" :icons="$tabIcons" />
```

- [ ] **Step 6: Add icons to Cancel/Submit/Next/Back buttons**

In every button in `establishment-form.blade.php` and `_who-you-are.blade.php`/`_review-submit.blade.php`, add an inline SVG before the label text — arrow-left for Back/Cancel, check for Submit/Save, arrow-right for Next:

```blade
<button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ __('workspace.contribution_submit') }}
</button>
```

Apply the equivalent pattern (`M19 12H5 M12 19l-7-7 7-7` for back/left, `M5 12h14 M12 5l7 7-7 7` for next/right) to every button identified in Step 3-4's blade files. Do this for all four button locations: Step 1's Next, the tab-step's Back/Next, the review step's Back/Submit, and editorial mode's Cancel/Save.

- [ ] **Step 7: Spacing audit**

Read every partial created in Tasks 10-15 and confirm every top-level field group uses `space-y-5` (already the established convention — spot-check `_tab-location.blade.php` and `_review-submit.blade.php`, which had ad-hoc spacing added during their respective tasks) and every field-to-field gap inside a `grid` uses `gap-5`. Fix any `mt-*`/`space-y-*` value that doesn't match this scale.

- [ ] **Step 8: Run tests to verify they pass**

Run: `php artisan test --filter=ContributionTest`
Expected: PASS

- [ ] **Step 9: Commit**

```bash
git add apps/web/resources/views apps/web/lang/eng/editorial.php apps/web/lang/eng/workspace.php apps/web/tests/Feature/Workspace/ContributionTest.php
git commit -m "feat(establishment-form): Add a Spa wording, tab/button icons, spacing pass"
```

---

### Task 17: Multilingual identity fields + language switcher

**Files:**
- Modify: `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`
- Modify: `apps/web/resources/views/livewire/workspace/establishment-form/_tab-identity.blade.php`
- Modify: `apps/web/lang/eng/editorial.php`

**Interfaces:**
- Produces: `state.display_name_{lang}`, `state.short_description_{lang}`, `state.description_{lang}` for `lang` in `eng, fil, spa, kor, zho_hant, zho_hans`; `$activeLanguageTab` property; `save()`/`submitContribution()` write every non-empty language into the guide's `{lang: {text, method_translation, status_review}}` shape.

- [ ] **Step 1: Write the failing test**

```php
    public function test_contributor_can_submit_display_name_in_a_second_language(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 3)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.display_name_fil', 'Harbor Calm Spa (Filipino)')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->set('duplicateAcknowledged', true)
            ->call('save');

        $contribution = \App\Models\Contribution::query()->where('submitted_by_user_id', (string) $user->getKey())->firstOrFail();
        $this->assertSame('Harbor Calm Spa (Filipino)', data_get($contribution->proposed_data, 'establishment.display_name.fil.text'));
        $this->assertSame('HUM', data_get($contribution->proposed_data, 'establishment.display_name.fil.method_translation'));
    }
```

Add to `apps/web/tests/Feature/Workspace/ContributionTest.php`.

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=ContributionTest`
Expected: FAIL — `state.display_name_fil` isn't tracked; payload only ever writes `eng`.

- [ ] **Step 3: Extend `TRANSLATED_FIELDS` handling to all governed languages**

```php
    /** Governed languages per docs/13-localization/language-strategy.txt, internal codes. */
    private const LANGUAGES = ['eng', 'fil', 'spa', 'kor', 'zho_hant', 'zho_hans'];

    public string $activeLanguageTab = 'eng';
```

In `mount()`, replace the `TRANSLATED_FIELDS` initialization loop:

```php
        $this->state = [];
        foreach (self::TRANSLATED_FIELDS as $field => $stateKeyPrefix) {
            foreach (self::LANGUAGES as $lang) {
                $this->state["{$stateKeyPrefix}_{$lang}"] = $record?->getAttribute($field)[$lang]['text'] ?? '';
            }
        }
```

Change the `TRANSLATED_FIELDS` constant's values to be the state-key **prefix** (drop the `_eng` suffix, since Step 3 now appends the language):

```php
    private const TRANSLATED_FIELDS = [
        'display_name' => 'display_name',
        'short_description' => 'short_description',
        'description' => 'description',
        'direction_note' => 'direction_note',
        'parking_note' => 'parking_note',
    ];
```

This changes every other reference to `$this->state['display_name_eng']` etc. elsewhere in the class — search the file for `_eng'` and confirm each remaining reference (e.g. in `composeAddressPublic()`, `checkForDuplicates()`, the review partial) still works, since `display_name_eng` remains a valid key (now produced by the loop with `$lang = 'eng'` rather than hardcoded) — no call sites need to change.

- [ ] **Step 4: Update `save()` and `submitContribution()`'s translated-field writing**

In `save()`, replace:

```php
        foreach (self::TRANSLATED_FIELDS as $field => $stateKey) {
            $value = $record->getAttribute($field) ?? [];
            $value['eng'] = $this->state[$stateKey] ?: null;
            $record->setAttribute($field, $value);
        }
```

with:

```php
        foreach (self::TRANSLATED_FIELDS as $field => $stateKeyPrefix) {
            $value = $record->getAttribute($field) ?? [];
            foreach (self::LANGUAGES as $lang) {
                if (filled($this->state["{$stateKeyPrefix}_{$lang}"] ?? null)) {
                    $value[$lang] = $this->state["{$stateKeyPrefix}_{$lang}"];
                } else {
                    unset($value[$lang]);
                }
            }
            $record->setAttribute($field, $value);
        }
```

(Editorial mode keeps storing flat strings per language — matching the pre-existing `$record->display_name['eng']` shape used by the public spa page today; moving editorial mode's storage to the full `{text, method_translation, status_review}` object is spec section 10 work, out of scope here and covered by the separate editorial/public-alignment plan.)

In `submitContribution()`, replace the `$establishment[$field] = ['eng' => ...]` loop with:

```php
        foreach (self::TRANSLATED_FIELDS as $field => $stateKeyPrefix) {
            $translations = [];
            foreach (self::LANGUAGES as $lang) {
                if (filled($this->state["{$stateKeyPrefix}_{$lang}"] ?? null)) {
                    $translations[$lang] = [
                        'text' => $this->state["{$stateKeyPrefix}_{$lang}"],
                        'method_translation' => 'HUM',
                        'status_review' => 'P',
                    ];
                }
            }
            $establishment[$field] = $translations;
        }
```

- [ ] **Step 5: Add the language switcher to the Identity tab**

Add translation keys to `editorial.php`:

```php
    'lang_eng' => 'English',
    'lang_fil' => 'Filipino',
    'lang_spa' => 'Spanish',
    'lang_kor' => 'Korean',
    'lang_zho_hant' => 'Chinese (Traditional)',
    'lang_zho_hans' => 'Chinese (Simplified)',
```

At the top of `_tab-identity.blade.php`, before the `display_name_eng` field:

```blade
    <div class="flex flex-wrap gap-1.5" role="tablist" aria-label="{{ __('editorial.tab_identity') }} language">
        @foreach (['eng', 'fil', 'spa', 'kor', 'zho_hant', 'zho_hans'] as $lang)
            <button type="button" wire:click="$set('activeLanguageTab', '{{ $lang }}')"
                    class="rounded-full border px-3 py-1 text-xs font-semibold transition {{ $activeLanguageTab === $lang ? 'border-ember-500 bg-ember-50 text-ember-700 dark:bg-ember-950 dark:text-ember-400' : 'border-ink-200 text-ink-600 dark:border-ink-700 dark:text-ink-300' }}">
                {{ __('editorial.lang_'.$lang) }}
            </button>
        @endforeach
    </div>
```

Change the three translatable field bindings to be dynamic:

```blade
    <x-form.field :label="__('editorial.est_display_name_eng')" :error="$errors->first('state.display_name_'.$activeLanguageTab)">
        <x-form.input wire:model="state.display_name_{{ $activeLanguageTab }}" maxlength="255" />
    </x-form.field>
    <x-form.field :label="__('editorial.est_short_description_eng')" :error="$errors->first('state.short_description_'.$activeLanguageTab)">
        <x-form.textarea wire:model="state.short_description_{{ $activeLanguageTab }}" rows="3" />
    </x-form.field>
    <x-form.field :label="__('editorial.est_description_eng')" :error="$errors->first('state.description_'.$activeLanguageTab)">
        <x-form.textarea wire:model="state.description_{{ $activeLanguageTab }}" rows="8" />
    </x-form.field>
```

`display_name_eng` remains `required` in `rules()` regardless of `$activeLanguageTab` (unchanged rule) — the other five languages stay optional.

- [ ] **Step 6: Run tests to verify they pass**

Run: `php artisan test --filter=ContributionTest` and `php artisan test --filter=EstablishmentCrudTest`
Expected: PASS

- [ ] **Step 7: Commit**

```bash
git add apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php apps/web/resources/views/livewire/workspace/establishment-form/_tab-identity.blade.php apps/web/lang/eng/editorial.php apps/web/tests/Feature/Workspace/ContributionTest.php
git commit -m "feat(establishment-form): capture display name/descriptions in all governed languages"
```

---

### Task 18: Validation hardening — close remaining `Rule::in` gaps and repeater bounds

**Files:**
- Modify: `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`
- Test: `apps/web/tests/Feature/Workspace/ContributionValidationTest.php` (new)

**Interfaces:**
- Produces: every taxonomy-backed field in `rules()` uses `Rule::in()`; repeaters (`landmark_list`, `contact_channel_list`, `treatment_area_list`, `operating_hours`) capped at `max:20` rows.

- [ ] **Step 1: Write the failing tests**

```php
<?php

namespace Tests\Feature\Workspace;

use App\Livewire\Workspace\Editorial\EstablishmentForm;
use App\Models\User;
use Livewire\Livewire;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class ContributionValidationTest extends TestCase
{
    use InteractsWithMongoUsers;

    public function test_type_spa_rejects_an_unknown_code(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.type_spa', 'NOT_A_REAL_CODE')
            ->call('nextStep')
            ->assertHasErrors(['state.type_spa']);
    }

    public function test_status_establishment_rejects_an_unknown_code(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.status_establishment', 'NOT_A_REAL_CODE')
            ->call('nextStep')
            ->assertHasErrors(['state.status_establishment']);
    }

    public function test_landmark_list_is_capped_at_twenty_rows(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true);

        for ($i = 0; $i < 21; $i++) {
            $test->call('addRow', 'landmark_list');
        }

        $test->set('currentStep', 2)->call('nextStep')->assertHasErrors(['state.landmark_list']);
    }
}
```

- [ ] **Step 2: Run tests to verify they fail**

Run: `php artisan test --filter=ContributionValidationTest`
Expected: FAIL — `type_spa`/`status_establishment` accept any string today; no repeater max-count rule exists.

- [ ] **Step 3: Add `Rule::in` to every taxonomy-backed field and repeater max-count rules**

In `rules()`, change:
```php
            'state.type_spa' => ['required', 'string'],
            'state.status_establishment' => ['required', 'string'],
```
to:
```php
            'state.type_spa' => ['required', 'string', Rule::in(array_keys(TaxonomyOptions::for('type_spa')))],
            'state.status_establishment' => ['required', 'string', Rule::in(array_keys(TaxonomyOptions::for('status_establishment')))],
            'state.level_spa_market' => ['nullable', 'string', Rule::in(array_keys(TaxonomyOptions::for('level_spa_market')))],
            'state.type_physical_setting' => ['nullable', 'string', Rule::in(array_keys(TaxonomyOptions::for('type_physical_setting')))],
            'state.type_establishment_operation' => ['nullable', 'string', Rule::in(array_keys(TaxonomyOptions::for('type_establishment_operation')))],
            'state.mode_access' => ['nullable', 'string', Rule::in(array_keys(TaxonomyOptions::for('mode_access')))],
            'state.type_client_access' => ['nullable', 'string', Rule::in(array_keys(TaxonomyOptions::for('type_client_access')))],
            'state.shower_availability' => ['nullable', 'string', Rule::in(array_keys(TaxonomyOptions::for('shower_availability')))],
            'state.sauna_availability' => ['nullable', 'string', Rule::in(array_keys(TaxonomyOptions::for('sauna_availability')))],
            'state.steam_room_availability' => ['nullable', 'string', Rule::in(array_keys(TaxonomyOptions::for('steam_room_availability')))],
            'state.jacuzzi_availability' => ['nullable', 'string', Rule::in(array_keys(TaxonomyOptions::for('jacuzzi_availability')))],
            'state.locker_availability' => ['nullable', 'string', Rule::in(array_keys(TaxonomyOptions::for('locker_availability')))],
            'state.couple_room_availability' => ['nullable', 'string', Rule::in(array_keys(TaxonomyOptions::for('couple_room_availability')))],
            'state.private_room_availability' => ['nullable', 'string', Rule::in(array_keys(TaxonomyOptions::for('private_room_availability')))],
            'state.curtain_divider_information' => ['nullable', 'string', Rule::in(array_keys(TaxonomyOptions::for('curtain_divider_information')))],
            'state.air_conditioning_information' => ['nullable', 'string', Rule::in(array_keys(TaxonomyOptions::for('air_conditioning_information')))],
            'state.mode_service_delivery' => ['array'],
            'state.mode_service_delivery.*' => [Rule::in(array_keys(TaxonomyOptions::for('mode_service_delivery')))],
            'state.target_client_focus.*' => [Rule::in(array_keys(TaxonomyOptions::for('target_client_focus')))],
            'state.amenity_list.*' => [Rule::in(array_keys(TaxonomyOptions::for('amenity_list')))],
            'state.room_types.*' => [Rule::in(array_keys(TaxonomyOptions::for('room_types')))],
            'state.bed_mat_chair_setup.*' => [Rule::in(array_keys(TaxonomyOptions::for('bed_mat_chair_setup')))],
            'state.accessibility_feature_list.*' => [Rule::in(array_keys(TaxonomyOptions::for('accessibility_feature_list')))],
            'state.parking_availability_list.*' => [Rule::in(array_keys(TaxonomyOptions::for('parking_availability')))],
```

Add `max:20` to the four repeater rules:
```php
            'state.landmark_list' => ['array', 'max:20'],
            'state.contact_channel_list' => ['array', 'max:20'],
            'state.treatment_area_list' => ['array', 'max:20'],
            'state.operating_hours' => ['array', 'max:20'],
```
(insert these four lines before their existing `*.field` wildcard rules).

- [ ] **Step 4: Run tests to verify they pass**

Run: `php artisan test --filter=ContributionValidationTest`
Expected: PASS

- [ ] **Step 5: Run the full editorial + contribution regression suites**

Run: `php artisan test --filter="EstablishmentCrudTest|ContributionTest|ContributionPayloadShapeTest|ContributionValidationTest"`
Expected: PASS — confirm the new `Rule::in` additions don't reject any value the existing editorial tests set (they use real codes like `DY`/`OP` already, per the file read at plan-writing time).

- [ ] **Step 6: Commit**

```bash
git add apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php apps/web/tests/Feature/Workspace/ContributionValidationTest.php
git commit -m "feat(establishment-form): validate every taxonomy field server-side, cap repeater rows"
```

---

### Task 19: Documentation

**Files:**
- Create: `docs/06-user-interface/add-spa-form-ui.txt`
- Modify: `docs/01-project/simple-checklist.txt`
- Modify: `docs/04-architecture/database-structure.txt`
- Modify: `CHANGELOG.md`

**Interfaces:** none — documentation only.

- [ ] **Step 1: Write `docs/06-user-interface/add-spa-form-ui.txt`**

Follow `docs/02-governance/documentation-standard.txt` formatting (title, version, dates, related documents, numbered sections). Cover: the 3-step flow; field-by-field intent per tab; the dynamic rules from spec section 4 (contact-channel adaptation, physical-premises hide/strip, closed-date visibility, visit-request eligibility); the duplicate-check UX; what happens after submission (goes to `PND`, no live-page change until reviewed). Cross-reference `docs/02-governance/edit-system.txt` and `data/structure_guide/establishment_main.php`.

- [ ] **Step 2: Update `docs/01-project/simple-checklist.txt`**

Read line 2438's current context (`[~] Submit a new establishment or practitioner contribution from /workspace`) and its surrounding 18.x block; update its status note (still `[~]`, since practitioner contribution and reviewer-decision UI remain unbuilt) to mention the establishment side is now guide-conformant, per the "do not duplicate full specifications inside the checklist" rule — one line, not a restatement of the spec.

- [ ] **Step 3: Update `docs/04-architecture/database-structure.txt`**

Add a note under the "Shared reference database: common_reference" section (around line 22) that the Laravel app now connects to it via the `mongodb_reference` connection, read-only, for `country_main`/`region_main`/`city_main`.

- [ ] **Step 4: Add a `CHANGELOG.md` entry**

Follow the existing changelog format (check the file's current top entries for the exact heading/bullet style before writing).

- [ ] **Step 5: Commit**

```bash
git add docs/06-user-interface/add-spa-form-ui.txt docs/01-project/simple-checklist.txt docs/04-architecture/database-structure.txt CHANGELOG.md
git commit -m "docs: document the Add a Spa form redesign"
```

---

### Task 20: Full regression pass

**Files:** none created or modified — verification only.

- [ ] **Step 1: Run the full test suite**

Run (from `apps/web/`): `composer test`
Expected: all tests pass, including every test added in Tasks 1-19 and the pre-existing suites (`EstablishmentCrudTest`, `EditorialAccessTest`, `WorkspaceShellTest`, `WorkspaceLayoutTest`, and the rest).

- [ ] **Step 2: Run Pint across every touched file**

Run: `vendor/bin/pint --test`
Expected: passes; if not, run `vendor/bin/pint` (no `--test`) to auto-fix, then re-run `composer test`.

- [ ] **Step 3: Build frontend assets**

Run: `npm run build`
Expected: succeeds, confirming the Leaflet import and Vite entry changes from Task 12 compile.

- [ ] **Step 4: Manual browser verification**

Using the `run` skill or a local dev server: log in as a non-editor active member, visit `/workspace/contribution/establishment/new`, and walk through all 3 steps on both desktop and a mobile viewport (375px). Confirm: the map pin drags and updates the lat/long inputs; the Facilities tab disappears when Type = "Home Service Provider"; the closed-date field appears only for Temporarily/Permanently Closed/Relocated statuses; keyboard-only Tab/Enter navigation reaches every control including the map's coordinate fallback inputs; screen-reader labels announce step changes (verify `aria-live` is present on the step heading — add `aria-live="polite"` to the `<h2>` in `_who-you-are.blade.php` and `_review-submit.blade.php` if missing).

- [ ] **Step 5: Verify editorial mode is unaffected**

Log in as a user with `workspace.editorial.access`, visit `/workspace/editorial/establishment/new` and `/workspace/editorial/establishment/{id}/edit`, confirm the form still saves directly (no wizard chrome, no relationship/duplicate/visit sections).

No commit for this task — it's a verification checkpoint. If any step fails, return to the relevant task above, fix, and re-run this task's checks.

---

## Self-Review Notes

- **Spec coverage:** Section 1 (governance) → Tasks 1-3. Section 2 (component/step architecture) → Tasks 9-10. Section 3 (wording/layout) → Task 16. Section 4 (fields/dynamic rules) → Tasks 11-14, 17. Section 5 (chip icons) → Task 16 covers tab/button icons; toggle-group icon prop is added but the curated option-code icon map itself is left as a follow-up polish item (flagged below — this is the one deliberate scope trim). Section 6 (reference data) → Tasks 4-6. Section 7 (duplicate check) → Task 8, wired in Task 15. Section 8 (validation/security) → Tasks 11, 13, 18; the `EstablishmentContributionSubmitted` event → Task 7. Section 9 (payload shape + drift guard) → Task 15. Section 11 (testing) → every task carries its own tests plus Task 20's regression pass. Section 12 (docs) → Task 19.
- **Known trim:** Task 16 adds icon *support* to `x-form.toggle-group` (an `icons` prop) but does not populate a full curated icon map for every amenity/facility option code — that's genuine design work (choosing a glyph per concept) better done as a fast-follow once the form is live and real option usage is visible, rather than guessing icons for ~80 taxonomy codes now. Flag this to the user before merge.
- **Placeholder scan:** Task 13 Step 4 intentionally contains a placeholder (`PHONE_CHANNEL_CODES = ['PHN']`) because the real `type_contact_channel` codes aren't in this plan's grounding — Step 3 of that task requires running the taxonomy inspection command first and replacing it before the placeholder ships. This is the one allowed exception: the plan tells the implementer exactly how to resolve it (an inspection command to run), not "add appropriate codes."
- **Type consistency:** `AddressLookup::cities()`/`regions()`/`countries()` return `array<string, string>` consistently across Tasks 6 and 12. `EstablishmentForm::$currentStep` (int) is consistent across Tasks 9-16. `hasPhysicalPremises()` (Task 14) is referenced identically in Task 16's tab-bar filter. `Contribution`'s new field names (Task 7) match exactly what Task 15's `submitContribution()` writes and what Task 3's structure guide documents.
