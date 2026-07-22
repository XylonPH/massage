# Add a Spa Form Design (Phase 1 of the Establishment Contribution Redesign)

- Date: 2026-07-22
- Status: Draft, pending written-spec review
- Scope: apps/web
- Depends on: a separate "PH Geographic Reference Data" spec (province/city/municipality/barangay + postal codes), to be brainstormed next. This phase does not block on it — see section 6.

## Problem

`/workspace/contribution/establishment/new` currently reuses
`App\Livewire\Workspace\Editorial\EstablishmentForm` in a "contribution mode"
added in an earlier change. That earlier change fixed *who* can submit and
*where it goes* (a reviewed `Contribution`, not a direct write), but it did not
fix the form's data shape or its UX, both of which have real problems:

1. **The submitted data does not match `data/structure_guide/establishment_main.php`
   (v1.40)**, the authoritative record shape. Multilingual text is stored as a
   flat string instead of the required `{text, method_translation,
   status_review}` object; field names diverge (`amenities` vs `amenity_list`,
   `description` vs `full_description`); contact channels and operating hours
   are embedded in the main payload instead of shaped for their owning
   collections (`establishment_contact`, `establishment_schedule`); the address
   is one free-text field plus raw lat/long instead of the guide's structured
   address + GeoJSON point; `official_name`, `payment_method_list`, and
   `level_address_visibility` are missing entirely.
2. **The UX is generic admin tooling**, not a member-facing contribution flow:
   ampersands in tab names, no icons, large uneven spacing, duplicate
   email/phone fields (Identity tab and Contact tab both ask for them), a
   `status_record_lifecycle` control that should never be shown to a
   contributor, a "relationship" tab that reads as an afterthought below the
   spa-data tabs, static dropdowns for contact-channel type that don't adapt
   the rest of the row, and no duplicate-establishment warning.
3. **Facility detail fields** (shower/sauna/steam/jacuzzi/locker/couple-room/
   private-room availability, room types, bed/mat/chair setup, curtain/divider,
   air-conditioning) exist in the taxonomy and the form but are **not in the
   structure guide** — a real governance gap.
4. **No opened/closed date capture**, so a contributor cannot add a
   already-closed spa for historical record purposes.

Decision: redesign the contribution (and, where it shares the component,
editorial) establishment form as a proper 3-step wizard that produces
guide-conformant proposals, resolves the facility-field governance gap, and
fixes the UX issues identified above. This is Phase 1 of a longer roadmap;
Phases 2-4 (media uploads, therapist roster + proof/verification, form-UI
translation) are out of scope here and only sketched at the end for context.

## Design

### 1. Governance updates (land before the code that depends on them)

- **`establishment_main` structure guide bump** (new version, per
  `docs/02-governance/structure-guide-standard.txt`): add the 11 facility
  fields (`shower_availability`, `sauna_availability`,
  `steam_room_availability`, `jacuzzi_availability`, `locker_availability`,
  `couple_room_availability`, `private_room_availability`,
  `curtain_divider_information`, `air_conditioning_information`,
  `room_types`, `bed_mat_chair_setup`); add `parking_availability_list`; add
  `date_opened` / `date_closed` with precision (`type_date_precision`:
  year/month/day) and approximate-qualifier subfields, documented as
  denormalized summaries synchronized from `establishment_event` records (the
  event collection remains authoritative for history; the main record holds
  the current best-known summary for display/query). Update field order,
  embedded structure, and field property blocks accordingly.
- **Taxonomy**: rename `establishment_classification.json` field names to
  match the guide (`amenities` → `amenity_list`, `accessibility_information` →
  `accessibility_feature_list`) preserving all existing option codes; add a
  new controlled field `parking_availability` (options: none / free on-site /
  paid on-site / street / nearby paid lot / valet / motorcycle-only). No code
  is retired or reused — this is a field-name correction, not a taxonomy
  content change, so existing option codes carry over unchanged.
- Regenerate `data/field_index.txt` in the same change
  (`php tools/script/build_field_index.php`).
- `contribution_main` structure guide: add `submission_note` (nullable,
  multilingual not required — internal reviewer-facing text) if not already
  present.

### 2. Component and step architecture

One shared Livewire component (`EstablishmentForm`), same two modes as today
(`isContribution` vs. editorial direct-edit), restructured:

- **Contribution mode — 3 steps**, each a distinct `x-data` view state (not
  Livewire multi-page — stays one component, one URL, client-side step
  transitions, server-validated per step before advancing):
  - **Step 1 — "Who you are"**: declared connection to the spa (renamed from
    "relationship" — label becomes "Your connection to this spa"), workspace
    access request, connection note. This is the existing
    `type_establishment_relationship` / `is_workspace_access_requested` /
    `relationship_note` fields, just relocated and relabeled.
  - **Step 2 — "Spa details"**: the tab bar (Identity, Classification, Access,
    Location, Contact, Hours, Facilities, Amenities, Accessibility — see
    section 4).
  - **Step 3 — "Review and submit"**: read-only summary of everything entered
    (grouped by the same sections as step 2), duplicate-check results (section
    7), visit-request fields shown only when eligible (section 4, Location),
    submission note, submit button.
- **Editorial mode**: unchanged single-page tab bar (step 2 content only) +
  Save, direct edit to `Establishment`. No relationship/access/visit fields,
  no review step — editors already see what they're creating.
- Blade split from the current single 290-line file into
  `resources/views/livewire/workspace/establishment-form/` partials: one per
  tab plus `_who-you-are.blade.php` and `_review-submit.blade.php`, included
  from a slim parent view that only holds the step/tab shell.

### 3. Wording, layout, and visual fixes

- Page renamed **"Add a Spa"** everywhere in contribution mode (title,
  `<title>`, nav link text via `workspace.contribution_add_establishment`);
  editorial mode keeps its existing "New/Edit — Establishments" title.
- New intro copy (translation key rewrite, not a placeholder) explaining what
  happens after submission (goes to review, doesn't go live immediately).
- Help link under the intro: "How adding a spa works →", pointing at the
  existing `help.index` coming-soon route (no new page yet, but a real,
  discoverable link — not a dead end with no explanation).
- Tab labels lose ampersands: "Access & delivery" → "Access and delivery" (or
  reworded where more natural), each tab gets a small inline SVG icon in the
  same style as `x-workspace-nav`'s icon map.
- Cancel/submit buttons get icons (arrow-left / check).
- Spacing pass: consistent vertical rhythm between fields and between
  sections; the reported "large gap" areas get audited and normalized to the
  existing `space-y-5` scale used elsewhere in the component.
- Identity tab loses `email` and `contact_number` (now Contact-tab-only,
  removing the duplication).
- `status_record_lifecycle` control removed from contribution mode entirely;
  server always writes `ACT` for new proposals. Editorial mode keeps the
  control (it needs it for moderation states like suspended/removed).

### 4. Fields and dynamic rules (Step 2 tabs)

- **Identity**: display name, official name, short + full description.
  Translatable fields (`display_name`, `short_description`, `full_description`)
  store the guide's multilingual object shape
  (`{text, method_translation: 'HUM', status_review: 'P'}`) per language. A
  compact language-tab switcher lets a contributor add text in any of the
  governed languages (`eng` required, `fil`/`spa`/`kor`/`zho-hant`/`zho-hans`
  optional) — this stores multilingual **data** now; translating the *form
  interface itself* into those languages is Phase 4, unrelated.
- **Classification**: type, market level, physical setting, operation type,
  operating status, plus **opened date** and, only when the selected status is
  a closed/ceased code, **closed date** — each a date input with a precision
  selector (year / month / exact day) and an "approximate" checkbox, matching
  `establishment_event`'s `type_date_precision`/`type_date_qualifier` model.
- **Access**: delivery modes, access mode, client access, client focus —
  unchanged from today except chip styling (section 5).
- **Location**: country select (default Philippines) → region select,
  DB-backed via the new `Country`/`Region` read models (section 6);
  city/municipality free-text input **today**, becoming a cascading select once
  the geographic-data dependency lands (component built against a small
  interface — see section 6 — so this upgrade is a drop-in, not a rewrite);
  street/building/floor/unit/postal code fields; an editable `address_public`
  textarea auto-composed from the structured fields on blur (contributor can
  still hand-edit it); a **map pin picker** (Leaflet, installed via npm and
  bundled — no CDN script tag, consistent with the app's existing asset
  pipeline) that writes GeoJSON `[lon, lat]`, with manual latitude/longitude
  number inputs as a fallback for contributors who already know the
  coordinates; landmark repeater (existing) gains a per-landmark direction
  note field (guide has it, form didn't); direction note; **parking**: a chip
  group bound to the new `parking_availability` taxonomy field plus the
  existing multilingual parking note (replacing the current single free-text
  field with structured options *and* a note, not either/or).
- **Contact**: the channel repeater becomes dynamic — selecting a channel type
  (email/phone/messaging app/social/website/etc.) shows only the subfields
  that apply to it (phone-number-type selector only for phone channels; the
  value input's validation and placeholder change per type — email format for
  email, URL format for link-capable channels); this replaces today's
  always-show-every-field layout.
- **Hours**: existing day-row repeater, adding a per-day "closed" toggle
  (clears open/close time when checked) so the submitted shape matches
  `establishment_schedule`'s open/closed modeling instead of relying on blank
  times to imply closed.
- **Facilities / Amenities / Accessibility**: same taxonomy-backed fields,
  chip-based instead of dropdown/checkbox-list (section 5). The entire
  physical-premises group — Facilities tab, Amenities, treatment-area
  repeater, and street-level address detail — is **hidden client-side and
  stripped server-side** when the spa has no physical premises, defined as:
  `mode_service_delivery` does not include `OS` (on-site service) **and**
  `type_spa` is not one of the home/mobile-only codes (`HP`, `MB`). This
  matches the guide's privacy posture (no reason to ask a home-service-only
  provider for shower/jacuzzi/room-type details) and is enforced twice: hidden
  in the UI via the same computed flag, and stripped in `rules()`/`save()` so
  a contributor can't bypass it by re-showing hidden inputs client-side.

### 5. Chip/icon multi-selects

- `x-form.toggle-group` (used for `mode_service_delivery`, `target_client_focus`,
  `amenity_list`, `room_types`, `bed_mat_chair_setup`,
  `accessibility_feature_list`) gains an optional icon per option, via a small
  curated map (option code → inline SVG name) for the common codes (parking,
  wifi, towels, wheelchair access, etc.); options without a curated icon fall
  back to text-only chips — no placeholder icon, no fabricated glyphs for
  concepts that don't have an obvious one.
- This is a visual upgrade to the existing component, not a new one; single
  select (`x-form.select`) fields are unaffected.

### 6. Reference data — connect to what already exists, degrade gracefully for what doesn't

- The `common_reference` MongoDB database already exists with live data in
  `country_main` (249 docs) and `region_main` (203 docs) — confirmed via
  `tinker` against the running dev database, same document shape as
  `data/common_reference/*.json`. The Laravel app has no connection to it yet
  (`config/database.php` defines only the `mongodb` connection, pointed at
  `massage_main`).
- Add a second connection, `mongodb_reference`, env-driven
  (`MONGODB_REFERENCE_DATABASE=common_reference`, reusing the existing
  `MONGODB_URI` host). This is a small, additive diff to a shared config file;
  given multiple agents may be editing concurrently, check `git status` and
  pull immediately before touching it, and keep the diff to the one new array
  entry.
- Read-only Eloquent models on that connection: `Country` (`country_main`),
  `Region` (`region_main`). Both exist and are populated now, so the Location
  tab's country → region cascade works immediately.
- `area_hierarchy_profile` (already populated, 1 doc) already declares the
  deeper levels: `province_main`, `city_main`, `municipality_main`,
  `barangay_main` — none of which exist yet. Building and importing that data
  is the separate "PH Geographic Reference Data" spec referenced at the top of
  this document (~44,000 records: province + city/municipality + barangay,
  plus a merged postal-code dataset from PHLPost, since PSGC itself doesn't
  carry postal codes).
- To avoid blocking this phase on that one: the Location tab's city/municipality
  input is built against a small `AddressLookup` interface
  (`regionsForCountry(countryId)`, `citiesForRegion(regionId)`) with two
  implementations — a `FreeTextFallback` (today: plain text input, used while
  `city_main` is empty or absent) and a `ReferenceDataLookup` (cascading select,
  activated automatically once `city_main` has data for the selected region).
  The component checks at render time whether city data exists for the chosen
  region and picks the implementation accordingly — no manual flag to flip,
  no follow-up form change needed when the geo-data phase ships.
- Small `php artisan reference:sync` command: idempotent upsert into
  `common_reference` from `data/common_reference/*.json` (report-only dry-run
  flag). Not a first-time import (data already exists in dev) — this is what
  keeps the DB in sync if the JSON changes later, and what seeds the isolated
  test database in CI, since tests run against a separate Mongo instance that
  won't have the manually-populated dev data.

### 7. Duplicate check

- Server-side, triggered on entry to Step 3: normalized-name similarity search
  against `establishment_main` and pending establishment contributions,
  narrowed to the same region (and within roughly 500m when the contributor
  placed a map pin).
- Matches render as non-blocking warning cards in Step 3 ("Is your spa one of
  these?" with name + area shown, no other establishment detail exposed).
  Contributor must tick "This is a different spa" to proceed if any match is
  shown; the flag itself (matched candidate IDs) is stored on the
  `Contribution` for the reviewer to see, not silently discarded.

### 8. Validation and security

- Every taxonomy-backed field gets server-side `Rule::in()` against its
  taxonomy options — several fields (contact channel type, treatment area
  type, day-of-week, etc.) don't have this today and currently accept any
  string.
- Repeaters get bounded max-row limits (landmarks, contact channels, treatment
  areas, operating hours already naturally bounded at 7-8 rows but not
  enforced).
- All free-text fields keep explicit `max:` length rules; dates validated
  logically (`date_closed` must be ≥ `date_opened` when both present); GeoJSON
  coordinates validated as `[-180..180, -90..90]`; conditional field groups
  (facility fields when home-service-only, closed date when not closed) are
  stripped server-side in `rules()`/`save()` regardless of what the client
  submits — never trust the hidden-state alone.
- Client-side: `wire:model.blur` validation per field plus HTML5 attributes for
  immediate feedback; an accessible error summary at the top of each step with
  focus movement to the first invalid field on a failed "Next" or "Submit".
- Auth gates unchanged (`auth`, `verified`, `EnsureActiveMember`); rate
  limiting on submission unchanged from the existing implementation.
- New `EstablishmentContributionSubmitted` event dispatched after a successful
  Step 3 submission — no listener yet, this is the seam the pending
  Ambers/Inklings/points rewards system attaches to once that documentation
  (in progress elsewhere) lands. Not building the listener now.

### 9. Contribution payload shape

`proposed_data` becomes namespaced and guide-conformant instead of the current
flat, partially-shaped object:

```
proposed_data:
  establishment: { …fields exactly matching establishment_main guide field order… }
  contact_channel_list: [ …establishment_contact-shaped rows… ]
  operating_schedule: [ …establishment_schedule-shaped rows… ]
  event_list: [ …opening/closure events, establishment_event-shaped, only when dates were entered… ]
```

Top-level `Contribution` fields keep: `type_establishment_relationship`,
`is_workspace_access_requested`, `relationship_note`, `submission_note`,
visit-request fields (section 4), duplicate-match references (section 7),
`status_contribution: PND`. A feature test asserts every key under
`proposed_data.establishment` exists in the structure guide's field order —
a drift guard so this can't silently rot again.

### 10. Editorial and public-page alignment (same phase, second half)

The contribution form producing guide-shaped data is only half the fix — the
`Establishment` model, editorial direct-edit mode, and the public spa page
still use the old field names today. Same phase, sequenced after sections 1-9
land and are reviewed (large enough to warrant its own review checkpoint, not
its own spec):

- `Establishment` model and editorial `EstablishmentForm` mode move to guide
  field names/shapes (`amenity_list`, `full_description`, structured address,
  `location_point`, multilingual objects).
- Contact channels and operating hours move out of the main record into
  `EstablishmentContact` / `EstablishmentSchedule` models per their existing
  guides; editorial form reads/writes those instead of embedding them.
- `SpaProfileController` and its views updated to the new field names.
- One-shot `establishment:migrate-guide-shape` artisan command converts
  existing dev records; `--dry-run` report mode first, applied only after
  reviewing the report.

### 11. Testing

- Feature tests: step navigation and per-step validation gating; the
  guide-conformance drift guard (section 9); every dynamic rule (contact-type
  field adaptation, facility-group hide/strip for home-service-only, closed-date
  visibility) exercised both client-visible and server-enforced; duplicate
  warning path (shown, dismissible, recorded); `Rule::in` rejection for every
  taxonomy field; `reference:sync` idempotency (run twice, no duplicate
  writes); full editorial-mode regression suite (unaffected by contribution
  changes); contribution privacy (a member only ever sees their own
  contributions, matching existing behavior).
- Manual pass: mobile viewport, keyboard-only navigation through all 3 steps,
  screen-reader labels on the step indicator and dynamic field groups.

### 12. Documentation (same change as the code it describes)

- New `docs/06-user-interface/add-spa-form-ui.txt` (per
  `docs/02-governance/documentation-standard.txt`) describing the 3-step flow,
  field-by-field intent, and the dynamic-rule list from section 4.
- Structure guide + taxonomy + field-index updates from section 1.
- `docs/01-project/simple-checklist.txt` section 18 items updated to reflect
  actual status (`[~]` stays partial where the reviewer-decision UI is still
  unbuilt — this phase doesn't touch that).
- `docs/04-architecture/database-structure.txt`: note that the
  `mongodb_reference` connection exists and what it's used for.
- `CHANGELOG.md` entry.

## Out of scope (later phases, not designed here)

- **Phase 2 — Media**: `media_image` model + protected upload pipeline; logo
  and multiple spa-photo slots in the form.
- **Phase 3 — Roster & proof**: therapist roster entries (proposed
  `practitioner_main` + `establishment_practitioner` records, name/gender/
  photos), proof-document upload (`document_main`/`claim_main`), and a real
  visit-scheduling workflow (this phase only captures a preferred-time text
  field + eligibility flag, no calendar/scheduling system).
- **Phase 4 — Form-UI localization**: translating the interface itself
  (`lang/fil`, `lang/spa`, `lang/kor`, `lang/zho_hant`, `lang/zho_hans`) — the
  *data* fields are already multilingual-capable from this phase.
- Rewards/points integration: seam left via the dispatched event (section 8);
  listener deferred until the in-progress rewards documentation lands.

## Risks and open items

- **Multi-agent concurrency**: Antigravity, Codex, and Grok Build (Kimi Code
  and GitHub Copilot possibly later) work in this same repository. Before
  implementation starts, check `git status` and recent commits; keep shared
  files (`config/database.php`, `routes/web.php`, taxonomy JSON, structure
  guides, field index) diffs minimal and re-check for conflicts immediately
  before editing them.
- **Budget**: this is the largest single chunk of Phase 1 work to date this
  month. Implementation should proceed via `writing-plans` → execution with
  attention to token cost (batch related file reads, avoid redundant
  re-exploration already captured in this spec).
- The separate "PH Geographic Reference Data" spec is a hard dependency for
  the Location tab's *cascading select* experience, but not a blocker for this
  phase shipping (graceful degradation per section 6).
