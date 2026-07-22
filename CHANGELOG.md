# Changelog

This file records notable accepted technical changes to Massage Nexus. Planning discussion,
failed experiments, temporary debugging, and routine document wording changes do not require
individual entries.

## Unreleased

- Redesigned the "Add a Spa" establishment contribution form as a 3-step Livewire wizard (who you
  are, spa details, review and submit) shared with the direct-edit editorial establishment form,
  with 6-language multilingual capture, dynamic physical-premises and closed-date rules, an
  in-person visit-request eligibility check, a duplicate-establishment warning with required
  acknowledgement, and a guide-conformant `proposed_data` contribution payload including a GeoJSON
  `location_point`. Documented in `docs/06-user-interface/add-spa-form-ui.txt`.
- Added the repository `data/` area: Shared Taxonomy field definitions split into grouped JSON
  files under `data/taxonomy/shared/`, machine-readable Massage Nexus classification data under
  `data/taxonomy/massage_nexus/`, shared reference datasets under `data/common_reference/`, and
  theme configuration data under `data/theme/`.
- Added PHP-readable database structure guides under `data/structure_guide/`, including a new
  proposed `quote_main` guide for the homepage Quote of the Day feature, with their maintenance
  standard under `docs/02-governance/`.
- Standardized every PHP structure guide around complete sample, omission-default, field-order,
  embedded-structure, field-property, subfield-property, index, boundary, and return declarations;
  added an automated guide validator and expanded field-index generation to include nested fields.
- Organized the current project specifications under the repository `docs/` directory.
- Added repository-wide contributor instructions in `AGENTS.md`.
- Added the initial public website pages and a temporary sample spa-profile data source.
- Added registration, email verification, login, logout, password protections, and authentication
  tests using Laravel's native authentication facilities.
- Added the MongoDB Laravel integration and MongoDB-backed Massage Nexus user model while
  retaining SQLite for Laravel operational storage.
- Added the root README and changelog.

## 2026-07-18 - Repository foundation

- Initialized the Laravel 13 web application under `apps/web/`.
- Added the initial Composer, npm, Vite, Tailwind CSS, PHPUnit, and Laravel project foundation.
- Established `apps/web/public/` as the web application's public document root.
