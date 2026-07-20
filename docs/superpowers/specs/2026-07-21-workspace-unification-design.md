# Workspace Unification Design

- Date: 2026-07-21
- Status: Approved by Xylon (design sections approved in conversation; written-spec review waived)
- Scope: apps/web

## Problem

The authenticated area is split across two visual systems. `/workspace/home`,
profile, settings, listings, contributions, articles, and reviews are hand-built
Blade + Tailwind pages on the public site layout, and they feel bare, bland, and
structurally weak (thin card-style nav, no app shell). `/workspace/editorial`,
`/workspace/moderation`, and `/workspace/system` are Filament panels with stock
Filament styling (default green, default fonts, Filament widgets). Moving
between the two feels like switching products.

Decision: unify everything on one hand-built system (Blade + Livewire + Alpine +
Tailwind) inside a single branded workspace shell, and remove Filament. This
also resolves the "exact Filament use" open decision in
`docs/04-architecture/technology-stack.txt` section 14.

Filament inventory at time of writing: the Editorial panel has three resources
(Establishments, Services, Quotes — roughly 680 lines of declarative config);
the Moderation and System panels are empty stock dashboards.

## Design

### 1. Workspace shell

A dedicated `resources/views/layouts/workspace.blade.php` app shell used by
every authenticated workspace page:

- Persistent left sidebar: brand logo at top, identity capsule
  (`x-identity-capsule`), then the existing nav groups — Personal, Activity,
  Managed, Administration — with icons, active states, and hover/focus
  treatment. The Administration group is permission-driven via the existing
  `App\Support\Workspace\WorkspaceAccess`. On mobile the sidebar collapses
  behind an Alpine toggle.
- Page header bar inside the content area: page title, short context line, and
  an actions slot (primary actions such as "New article" live here).
- Content canvas on `ink-50` background (dark: charcoal/ink dark steps) so white
  cards read as raised surfaces.
- All existing workspace pages move onto this shell: home, profile, setting,
  listing/spa, listing/therapist, contribution pages, article pages (index,
  editor, revisions), review pages.
- Home becomes a real dashboard: compact stat row (reviews, articles,
  contributions), recent-activity list, quick actions, and the restyled
  permission-gated administration cards.
- The ember/ink/leaf palette and existing typography are kept. This is
  structure and finish, not a rebrand.

### 2. Light/dark theme switch (site-wide)

- Tailwind v4 class strategy: `@custom-variant dark` bound to a `.dark` class on
  `<html>`.
- Inline head script in both layouts (`layouts/app`, `layouts/workspace`)
  applies the stored preference before first paint (no flash of wrong theme).
- Sun/moon toggle in the public header and in the workspace sidebar footer.
  States: light → dark → system. System (OS preference) is the default;
  choice persists in `localStorage` and therefore works for guests.
  Account-setting sync is explicitly out of scope for now.
- Styling pass adds `dark:` variants across all public and workspace pages.
  Dark surfaces build on existing charcoal-950/ink-900 steps with ink-100 text;
  ember/leaf accents stay as-is.

### 3. Editorial rebuild (Filament → Livewire)

- `livewire/livewire` becomes a direct composer dependency (currently only
  transitive via Filament; Livewire is already the accepted interactive layer in
  the technology stack document).
- Routes inside the existing `workspace` group (auth + verified +
  EnsureActiveMember), additionally gated by the `workspace.editorial.access`
  permission from `config/workspace.php` via a small middleware check:
  - `/workspace/editorial` — landing page: count cards for establishments,
    services, quotes linking to each list.
  - `/workspace/editorial/establishment` (+ create/edit)
  - `/workspace/editorial/service` (+ create/edit)
  - `/workspace/editorial/quote` (+ create/edit)
  - URLs match the existing admin-area cards; `config/workspace.php` is
    unchanged.
- List pages (one Livewire component per collection): text search, pagination,
  status badge, edit link, delete with confirmation; create button in the page
  header. No column sorting, filter dropdowns, or bulk actions until a real
  need appears.
- Form pages (one Livewire component per collection):
  - Establishment keeps its 6-tab organization (Identity, Classification,
    Access & Delivery, Location & Contact, Facilities, Amenities &
    Accessibility) as Alpine-driven tabs in a single Livewire component. The
    four repeaters (landmark list, contact channel list, treatment area list)
    become Livewire array fields with add/remove row controls.
  - Service and Quote are small single-section forms.
  - Field coverage, defaults, help texts, and the multilingual `field.eng`
    value shape are carried over from the current Filament schemas without
    data-model changes.
- Taxonomy options: extract `EstablishmentForm::getTaxonomyOptions()` into a
  shared `app/Support/Taxonomy/TaxonomyOptions` class reading the same JSON
  files under `data/taxonomy/`; all three forms consume it.
- Validation lives in the Livewire components, reusing the existing
  `PublicContactUrl` rule and `RecordLifecycleStatus` enum.
- Reusable form primitives as Blade components (text input, textarea, select,
  toggle-button group, tab bar, repeater row), styled once for light/dark and
  shared by editorial and member-facing forms.

### 4. Filament teardown

Sequenced last, after the new editorial pages exist and their tests pass:

- Remove `EditorialPanelProvider`, `ModerationPanelProvider`,
  `SystemPanelProvider` from `bootstrap/providers.php`; delete `app/Filament/`
  and the `AuthenticateWorkspacePanel` middleware.
- `composer remove filament/filament` (safe once `livewire/livewire` is a direct
  dependency).
- No point in the branch history where editorial has no working implementation.

### 5. Moderation and system placeholders

`/workspace/moderation` and `/workspace/system` become branded "coming soon"
pages inside the workspace shell, gated by their existing permissions
(`workspace.moderation.access`, `workspace.system.access`). Dashboard admin
cards keep working; nothing 404s. Future real features are built as Livewire
pages in the shell, like editorial.

### 6. Tests

- New editorial feature tests: permission gating (403 without
  `workspace.editorial.access`), list/search/pagination, create/edit/delete for
  all three collections, establishment repeater handling.
- `EstablishmentTaxonomySourceTest` retargets to `TaxonomyOptions`;
  `AdminServiceResourceTest` and Filament-dependent parts of
  `WorkspaceShellTest` are rewritten against the new routes.
- Shell smoke tests: each workspace page renders the new layout; theme-toggle
  markup present in both layouts.

### 7. Documentation updates

- `docs/04-architecture/technology-stack.txt`: resolve the "exact Filament use"
  open decision — Filament removed; workspace areas standardized on
  Blade + Livewire + Alpine; Livewire listed as a direct dependency; dark mode
  noted in the web stack; version bump and history entry per the document's
  convention.
- `docs/11-workspaces/admin-workspace.txt` and
  `docs/06-user-interface/admin-workspace-ui.txt`: update Filament references to
  describe the in-shell editorial/moderation/system areas.

## Out of scope

- Account-level theme preference sync (localStorage only for now).
- Column sorting, filters, and bulk actions on editorial lists.
- Real moderation and system features (placeholders only).
- Any data-model or schema changes; any rebrand of palette or typography.
