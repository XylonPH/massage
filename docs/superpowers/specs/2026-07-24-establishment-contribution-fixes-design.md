# Establishment Contribution Form Fixes — Design

## Context

Sub-project C of the original feedback batch, covering `workspace/contribution/establishment/new` (Livewire component `apps/web/app/Livewire/Workspace/Editorial/EstablishmentForm.php`). This is a corrected/expanded scope after a review conversation surfaced real gaps in the original proposal — see decisions below.

Related work already done separately (not part of this spec):
- A large backlog of bigger features (Legal & Verification tab, multi-step wizard expansion, new collections, external integrations) was captured as documentation-only in `docs/01-project/roadmap.txt` / `simple-checklist.txt` / `features.txt` and is explicitly out of scope here.
- Taxonomy wording changes (facility-option labels) and new amenity codes were already applied directly to `data/taxonomy/massage_nexus/establishment_classification.json` and are confirmed live in the UI (options render via `$taxonomy['...']`, sourced dynamically from that JSON) — no code change needed for those.

## Decisions

- **Field naming clarified, not changed:** the database/model field is `type_spa`; `est_type_spa` is only its lang-file label key (`editorial.php`). Only the *label text* changes, not any field/column name.
- **Tab-hiding must clear state, not just hide-and-discard-at-submit.** When a change makes `hasPhysicalPremises()` false, any already-entered data in the now-irrelevant fields must be cleared from Livewire state immediately, not silently dropped only when the form is eventually saved.
- **The physical-premises gate is broader than Amenities/Accessibility.** `hasPhysicalPremises() === false` should also: prevent `mode_access` from including Walk-in (remove it from the selection, not null the whole field — other access modes like phone/online booking remain valid for a home-service business), and clear `type_physical_setting` (a physical-setting classification makes no sense without a physical setting).
- **Address: full detail always required, public display reduced for home-service-only.** Every establishment must provide a complete structured address (country/region/city/street) — this is stored and required regardless of type. What's *shown publicly* differs: fixed-premises establishments show the full address; home-service-only establishments show only city/region/country (`address_public`), with the full street-level address kept for internal/verification purposes only. This reuses the existing-but-unwired `address_public` field and `composeAddressPublic()` method rather than inventing a new mechanism.
- **`official_name` relocates to the Identity tab** (a UI-grouping fix only — the larger question of whether this field should actually represent a DTI/SEC-registered legal name, with associated registration-number fields, is captured as a future backlog item and explicitly NOT addressed here).
- **Language switcher: single dropdown, rendered once.** Confirmed via screenshots that `_language-switcher.blade.php` is currently `@include`d separately in both `_tab-identity.blade.php` and `_tab-location.blade.php`, rendering the same 6-option control twice. Fix: convert it from a 6-button pill row to a compact `<select>` dropdown, and render it exactly once at the spa-details-step level (above the tab strip, not inside any individual tab), since the editing-language choice is a single global context, not a per-tab concept.
- **Parking: mutual exclusivity.** "No Parking Available" must be mutually exclusive with every other parking option (selecting it clears the others and vice versa), both client-side (immediate UX) and server-side (validation).
- **Contact: at least one channel required.** `contact_channel_list` currently allows a fully empty submission; require `min:1`.

## Changes

### 1. Rename "Establishment Type" → "Type of Spa"
`apps/web/lang/eng/editorial.php`: `'est_type_spa' => 'Establishment Type'` → `'Type of Spa'`.

### 2. Language switcher: dropdown, single render
- `apps/web/resources/views/livewire/workspace/establishment-form/_language-switcher.blade.php`: replace the pill-button row with a `<select wire:model="activeLanguageTab">` (or the component's existing equivalent binding — verify exact current binding mechanism before implementing) offering the same 6 languages.
- Remove the `@include('...​_language-switcher', ...)` from `_tab-identity.blade.php:3` and `_tab-location.blade.php:69`.
- Add a single `@include(...)` in `establishment-form.blade.php`, rendered once above the tab-content area (outside the `@foreach`/tab-switching structure), so it applies uniformly regardless of which tab is active.

### 3. Expand the physical-premises gate
- `EstablishmentForm.php`: in the method(s) that currently null out `PHYSICAL_PREMISES_FIELDS` only at save/submit time, ALSO run that clearing logic immediately when `type_spa` or `mode_service_delivery` changes in a way that flips `hasPhysicalPremises()` to false (a Livewire `updated()` hook on those two properties) — not only at save/submit.
- Extend the tab-visibility gate in `establishment-form.blade.php:146-147` (`amenities`, `accessibility` keys) to match the existing `facilities` pattern: `$this->hasPhysicalPremises() ? __('editorial.tab_amenities') : null` (and same for accessibility).
- Add `mode_access` Walk-in removal (not full nulling) and `type_physical_setting` nulling to the same clearing logic, gated the same way.

### 4. Address: required fields + privacy-aware public composition + official_name relocation
- `rules()`: change `official_name`, `country_id`, `region_id`, `city_name`, `street_address`, `address_public` from `nullable` to `required` (keep `building_name`/`floor_label`/`unit_label`/`postal_code` optional).
- `composeAddressPublic()`: when `!$this->hasPhysicalPremises()`, build the composed string from only `city_name`/region/country (drop `street_address`); when true, keep current behavior (includes street address).
- Move `official_name`'s field markup from `_tab-location.blade.php` to `_tab-identity.blade.php`.
- `tabForField()`: remove `official_name` from the location-matching regex (falls through to the default `'identity'` case).

### 5. Contact required + parking mutual exclusivity
- `rules()`: `'state.contact_channel_list' => ['array', 'max:20']` → add `'min:1'`.
- Parking field: add mutual-exclusivity handling — when "No Parking Available" is selected, clear any other selected parking options (and vice versa: selecting any other option clears "No Parking Available"). Implement via a Livewire `updated()` hook on the parking state property (client-visible immediately) plus a server-side validation rule as a backstop.

## Testing
- Feature test: renaming — page displays "Type of Spa" not "Establishment Type".
- Feature test: switching `type_spa` to Home Service Provider after filling Amenities/Accessibility clears that state immediately (assert Livewire component state, not just post-submit behavior).
- Feature test: Amenities/Accessibility tabs hidden when `hasPhysicalPremises()` is false, visible when true (mirrors existing Facilities-tab test if one exists — follow its pattern).
- Feature test: selecting Walk-in in `mode_access` while `hasPhysicalPremises()` is false gets cleared; unaffected when true.
- Feature test: submitting without official_name/country/region/city/street_address/address_public fails validation; submitting with all present succeeds.
- Feature test: `composeAddressPublic()` excludes street-level detail for a home-service-only establishment, includes it for a fixed-premises one.
- Feature test: a validation failure on `official_name` opens the Identity tab (via existing `validateWithTabFocus()`), not Location.
- Feature test: submitting with zero contact channels fails validation.
- Feature test: selecting "No Parking Available" alongside another parking option — the other option is cleared (or submission fails if that combination somehow still reaches the server, as a backstop).
- Feature test: language switcher renders exactly once in the full spa-details step's rendered HTML (not once per tab).

## Out of scope
- Legal & Verification tab, registration/tax-ID fields (captured in docs backlog).
- Multi-step wizard expansion (images/menu/staff/promos/house-rules steps), new collections, Google/AI-assisted intake (all captured in docs backlog).
- Missing generic profile fields (payment methods — already exist; pool/gym/bar/cafeteria/floor-count/staff-gender/staff-language — structure guide already updated in the docs pass, but no form/UI wiring here).
- Capacity-count fields for shared facilities, parking capacity/rate, description min/max+live counter, Access/Delivery tab split, Parking's own tab — all captured in docs backlog, not implemented here.
- Sub-project D/E items (documentation-upload step, visit-schedule redesign, approval workflow, logo/photo upload) — separate specs.
