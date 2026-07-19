# Massage Nexus

Massage Nexus is a private, proprietary multiplatform directory, discovery, content, community,
and booking project for massage, spa, therapist, wellness-service, and related information.

This repository is a monorepository. The active Laravel web application is under `apps/web/`.
Project specifications are organized under `docs/`, and repository-wide contributor instructions
are defined in `AGENTS.md`.

## Current repository structure

```text
massage/
|-- apps/
|   `-- web/          Laravel web application
|-- docs/             Current project specifications
|-- AGENTS.md         Repository-wide contributor instructions
|-- CHANGELOG.md      Accepted technical changes
|-- README.md         Repository introduction and setup
`-- .gitignore        Repository ignore rules
```

Future mobile, desktop, shared-contract, source-asset, and project-tool directories are created
only after their scope and technology are accepted. The intended desktop application path is
`apps/desktop/`.

## Web application requirements

- PHP 8.3 or newer, compatible with the locked Laravel dependencies
- Composer
- Node.js and npm
- MongoDB for Massage Nexus application data
- SQLite support for Laravel operational data used by the current application configuration

The installed dependency versions are authoritative in `apps/web/composer.lock` and
`apps/web/package-lock.json`.

## Local setup

From the repository root in PowerShell:

```powershell
Set-Location apps/web
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
```

Review `.env` and configure the MongoDB connection before running migrations. Do not commit the
resulting `.env` file or any secret value.

```powershell
php artisan migrate
composer run dev
```

The Laravel web server must expose only `apps/web/public/`, never the repository root or the
Laravel application root.

## Validation

Run relevant checks from `apps/web/`:

```powershell
composer test
vendor/bin/pint --test
npm run build
```

Use the smallest relevant test during development, then expand validation according to the risk
and scope of the change.

## Documentation and contribution rules

Before modifying the project, read `AGENTS.md` and the relevant specification under `docs/`.
The shared project standards and documentation rules are under `docs/02-governance/`.

Do not create duplicate implementations, speculative folders, implementation-note files,
handoffs, or new top-level directories without an accepted need. Preserve unrelated work in the
repository and do not commit, push, publish, or deploy unless explicitly authorized.

## Ownership and license

Massage Nexus is private and proprietary. No permission is granted to use, copy, modify,
redistribute, sublicense, or publish the source code or project materials except with the
copyright owner's explicit authorization.
