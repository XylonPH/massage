# Massage Nexus Repository Instructions

## Authority and required reading

Before planning or modifying anything, read the relevant parts of:

1. `docs/02-governance/shared-project-standards.txt`
2. This file
3. The current project-specific documents relevant to the task
4. The affected code and tests

Use this authority order when instructions conflict:

1. The user's current explicit decision
2. Current project-specific specifications
3. Shared Project Standards
4. Current code and tests, where they describe implemented behavior
5. Older project documents
6. Historical or archived material
7. General conventions or assumptions

Do not use archived planning files as current authority unless the user explicitly requests historical review.

## Documentation routing

Project documentation is organized by subject under `docs/`. Read the smallest sufficient set for the task; do not load every document by default.

Common starting points:

- Project scope and identity: `docs/01-project/basic-profile.txt`
- Complete feature inventory: `docs/01-project/features.txt`
- Completion status: `docs/01-project/simple-checklist.txt`
- Dependency and release order: `docs/01-project/roadmap.txt`
- Intended repository layout: `docs/01-project/file-and-folder-structure.txt`
- Shared standards: `docs/02-governance/shared-project-standards.txt`
- Development workflow: `docs/02-governance/ai-assisted-development-workflow.txt`
- Documentation rules: `docs/02-governance/documentation-writing-and-management-standard.txt`
- Testing strategy: `docs/02-governance/testing-and-quality-assurance-strategy.txt`
- Technology direction: `docs/04-architecture/technology-stack.txt`
- Database boundaries: `docs/04-architecture/database-structure.txt`
- Collection-level structure guides (PHP-readable): `docs/04-architecture/structure-guide/`
- Security, privacy, and policy: `docs/18-policy/`

For a capability, locate its specification in the matching numbered `docs/` subfolder before implementing it. Search by exact feature, domain term, route, model, or filename rather than reading unrelated documents.

## Machine-readable data and taxonomy governance

Machine-readable repository source data lives under `data/` (not under `apps/web/`, and never under `apps/web/public/`): shared taxonomy field definitions in `data/taxonomy/shared/`, Massage Nexus classification data in `data/taxonomy/massage_nexus/`, shared reference datasets in `data/common_reference/`, and theme configuration in `data/theme/`. These files support development, validation, import, and seeding; they are not part of the deployed application package.

Before creating any new field name of any kind, check `data/field_index.txt` first — it is one compact generated list of every known field name and where each is defined, so a name check does not require reading every taxonomy file and structure guide. Regenerate it with `php tools/script/build_field_index.php` in the same change whenever a taxonomy file or structure guide adds, renames, or removes a field. For a new controlled or enumerated field, additionally search the existing taxonomy JSON files under `data/taxonomy/` by field name, label, description, meaning, and option values. Reuse or extend an existing field when it answers the same semantic question; create a new field only when the concept is genuinely distinct, and add it to the appropriate taxonomy JSON file rather than leaving the option list only in application code, a structure guide, or documentation. The classification documents under `docs/05-directory/` remain the authority for Massage Nexus classification content; update the matching JSON file in `data/taxonomy/massage_nexus/` in the same change when a classification document changes. Option codes must stay unique within their field, and code meanings must never be reused after retirement.

## Working method

Before changing files:

- inspect Git status and preserve unrelated user changes;
- confirm the task is not already implemented;
- inspect the affected code, tests, configuration, and relevant specifications;
- identify security, privacy, data, localization, accessibility, route, and compatibility effects;
- make reasonable low-risk assumptions, but do not invent unresolved product decisions;
- keep the planned change narrow and recoverable.

During implementation:

- implement the requested result directly;
- remain within scope and avoid unrelated refactoring;
- follow established framework and repository conventions;
- keep files focused without fragmenting simple behavior unnecessarily;
- reuse existing logic, components, translations, validation, and tests where appropriate;
- use translation keys for user-facing interface text;
- validate all external input and enforce authorization server-side;
- keep secrets, credentials, personal data, and environment-specific values out of code, logs, prompts, tests, and documentation;
- use synthetic data in tests;
- do not install or update dependencies unless the task requires it;
- do not weaken tests, validation, authorization, privacy, or security merely to make a change pass.

Do not create unsolicited implementation plans, implementation-note files, handoffs, summaries, strategy documents, or speculative follow-up documents. Create an implementation record or similar artifact only when the user requests it, a governing specification requires it, or the change genuinely needs a durable operational record.

## Provisional architecture areas

`docs/01-project/file-and-folder-structure.txt` describes the intended direction, but the repository structure is still evolving. Treat it as guidance rather than permission to create every proposed directory or perform broad reorganization. Prefer the current working structure for narrow changes. When the actual repository and the document disagree, avoid creating a competing structure; resolve the mismatch through a focused decision and update the document when accepted.

`docs/04-architecture/database-structure.txt` is not a final schema. It records database and collection boundaries but does not finalize fields, indexes, validation rules, relationships, or every collection. Inspect existing models, migrations, configuration, and tests before database work. Do not make broad schema decisions from that document alone. Use additive, recoverable changes and obtain direction for unresolved or destructive data decisions.

## Documentation changes

Before creating, moving, renaming, splitting, merging, or substantially editing documentation, read:

`docs/02-governance/documentation-writing-and-management-standard.txt`

When documentation changes:

- update the authoritative document rather than creating a competing copy;
- preserve accepted requirements, constraints, examples, edge cases, and metadata;
- remove only obsolete, rejected, superseded, or truly duplicated material;
- keep repository documentation in its semantic `docs/` subfolder;
- keep repository filenames lowercase, dash-separated, unnumbered, unversioned, and `.txt` unless an explicit exception applies;
- update internal document version and Revision Date when the content revision requires it;
- preserve Date Created unless the documentation standard explicitly requires a new combined document;
- do not edit Working Notes unless the user explicitly asks;
- do not treat Working Notes as implementation authority.

If implemented behavior changes an accepted requirement, update the relevant authoritative specification in the same task when the new behavior is accepted and the documentation change is clear.

## Features, roadmap, and completion status

Use the documents for their intended responsibilities:

- `features.txt` preserves feature requirements and detailed inventory.
- `roadmap.txt` preserves dependency order, milestones, and release gates.
- `simple-checklist.txt` preserves completion status.

When a feature is fully implemented and verified, update the corresponding item in `docs/01-project/simple-checklist.txt` to `[x]` in the same change. Use `[~]` only when the checklist genuinely needs to record active partial work. Do not mark a feature complete merely because code was drafted, a document was written, or a test was added. Completion requires the accepted scope to work and the relevant validation to pass.

Do not duplicate full specifications inside the checklist or roadmap.

## Testing and validation

Every implementation change must be tested in proportion to its risk.

At minimum:

1. Run the smallest relevant test or check while developing.
2. Run the affected capability's test suite.
3. Run relevant formatting, linting, static analysis, type, build, security, or accessibility checks.
4. Run the broader regression suite at an appropriate checkpoint.
5. Inspect the final diff and remove temporary debugging code.
6. Manually verify important user-facing behavior when automated tests are insufficient.

Do not use tests written as part of the same change as the only evidence of correctness. Check edge cases, failure behavior, authorization, validation, and regression risk independently.

For the current Laravel application, run commands from `apps/web/` unless a command explicitly targets the repository root. Available baseline commands include:

```text
composer test
php artisan test --filter=<relevant-test-or-method>
vendor/bin/pint --test
npm run build
```

Use only checks relevant to the change, then expand coverage according to risk. Do not claim a check passed unless it was actually run successfully.

## Git and change safety

- Do not discard, overwrite, reformat, or include unrelated user changes.
- Do not edit generated output, dependency directories, caches, or runtime files when the source file should be changed instead.
- Do not commit, push, publish, deploy, or perform destructive database operations unless the user explicitly requests them.
- Do not rewrite Git history or use destructive cleanup commands without explicit authorization.
- Keep changes reviewable and limited to the requested outcome.
- Review the final file list and diff before reporting completion.

## Completion response

After completing a task, report only what is useful:

- the result;
- important files changed;
- tests and validation actually run;
- unresolved limitations, risks, or decisions, if any.

Do not add generic implementation commentary, repeat the request, or propose unnecessary follow-up work.
