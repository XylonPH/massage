# Changelog

This file records notable accepted technical changes to Massage Nexus. Planning discussion,
failed experiments, temporary debugging, and routine document wording changes do not require
individual entries.

## Unreleased

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
