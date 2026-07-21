# Editorial Livewire Rebuild & Filament Teardown Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace the three Filament editorial resources (Establishments, Services, Quotes) with Livewire CRUD pages inside the workspace shell, add branded moderation/system placeholders, then remove Filament entirely.

**Architecture:** Full-page Livewire 3 components under `app/Livewire/Workspace/Editorial/`, rendered in the `layouts.workspace` shell from Plan A (`docs/superpowers/plans/2026-07-21-workspace-shell-and-theme.md` — must be complete first). Access is enforced by a route middleware checking `WorkspaceAccess` permissions. Taxonomy dropdown options come from a shared `TaxonomyOptions` support class. Filament is deleted last, once all replacement tests pass. Spec: `docs/superpowers/specs/2026-07-21-workspace-unification-design.md`.

**Tech Stack:** Laravel 13, Livewire 3 (bundles Alpine.js for tabs/confirm dialogs), mongodb/laravel-mongodb models, Tailwind v4, PHPUnit.

## Global Constraints

- App root is `apps/web/`; run composer/artisan/npm there. Repo-root paths start with `docs/` or `data/`.
- Plan A must be fully merged first — `layouts.workspace` and its section conventions (`page-title`, `page-actions`, `navActive`) are assumed.
- All UI strings via translation keys in `lang/eng/editorial.php` (new file) — never hard-code English in Blade.
- Models are MongoDB Eloquent (`Establishment` → `establishment_main`, `Service` → `service_main`, `Quote` → `quote_main`); string `_id` auto-generated in each model's `boot()`. No schema/model-structure changes; write through existing `$fillable` fields and English/Chinese accessor attributes (`english_name`, `chinese_short_description`, `english_text`, etc.).
- Permission gate: `workspace.editorial.access` / `workspace.moderation.access` / `workspace.system.access` resolved through `App\Support\Workspace\WorkspaceAccess::can()` (config map in `config/workspace.php` — unchanged).
- Editorial URLs: `/workspace/editorial`, `/workspace/editorial/{establishment|service|quote}[/new|/{id}/edit]`. Route names `workspace.editorial.*`.
- List pages: text search + pagination (15/page) + status badge + edit/delete only. No sorting/filters/bulk actions (spec: out of scope).
- Tests: PHPUnit style, `php artisan test`, cleanup pattern per `tests/Feature/Workspace/WorkspaceShellTest.php` (delete Mongo records in setUp/tearDown, `InteractsWithMongoUsers` for users). Grant editorial access in tests via:

```php
AccessAssignment::query()->create([
    'user_id' => (string) $user->getKey(),
    'role_workspace' => 'EAD',
    'scope_access' => 'GBL',
    'status_access_assignment' => 'ACT',
    'effective_at' => now()->subMinute(),
]);
```

- Form field styling classes (use everywhere; defined once in Task 3's primitives):
  - Input/textarea/select: `w-full rounded-lg border border-ink-200 bg-white px-3 py-2 text-sm text-ink-950 placeholder:text-ink-400 focus:border-ember-400 focus:outline-none dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50 dark:placeholder:text-ink-400`
  - Label: `mb-1.5 block text-sm font-semibold text-ink-800 dark:text-ink-200`
  - Error: `mt-1.5 text-xs font-semibold text-ember-600 dark:text-ember-400`
  - Primary button: `rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600`
  - Secondary button: `rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800`

---

### Task 1: Livewire install, permission middleware, routes, editorial landing page

**Files:**
- Modify: `composer.json` (via composer command)
- Create: `app/Http/Middleware/EnsureWorkspacePermission.php`
- Create: `app/Livewire/Workspace/Editorial/EditorialHome.php`
- Create: `resources/views/livewire/workspace/editorial/editorial-home.blade.php`
- Create: `lang/eng/editorial.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/Editorial/EditorialAccessTest.php`

**Interfaces:**
- Produces: middleware alias usage `EnsureWorkspacePermission::class.':workspace.editorial.access'`; route names `workspace.editorial.home`, and the route-group skeleton Tasks 4–6 add to; lang file `editorial.php`; the pattern `#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]` for all editorial components.

- [ ] **Step 1: Install Livewire as a direct dependency**

Run: `composer require livewire/livewire:^3.0`
Expected: success; `composer.json` gains the requirement (Filament already pulled it in, so the lock barely changes).

- [ ] **Step 2: Write the failing test**

```php
<?php

namespace Tests\Feature\Editorial;

use App\Models\AccessAssignment;
use App\Models\User;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class EditorialAccessTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function setUp(): void
    {
        parent::setUp();
        AccessAssignment::query()->delete();
    }

    protected function tearDown(): void
    {
        AccessAssignment::query()->delete();
        parent::tearDown();
    }

    private function grantEditorial(User $user): void
    {
        AccessAssignment::query()->create([
            'user_id' => (string) $user->getKey(),
            'role_workspace' => 'EAD',
            'scope_access' => 'GBL',
            'status_access_assignment' => 'ACT',
            'effective_at' => now()->subMinute(),
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/workspace/editorial')->assertRedirect('/login');
    }

    public function test_member_without_permission_is_forbidden(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/workspace/editorial')->assertForbidden();
    }

    public function test_editor_sees_landing_page_with_collection_cards(): void
    {
        $user = User::factory()->create();
        $this->grantEditorial($user);

        $this->actingAs($user)->get('/workspace/editorial')
            ->assertOk()
            ->assertSee(__('editorial.title'))
            ->assertSee(route('workspace.editorial.establishment.index', [], false), false)
            ->assertSee(route('workspace.editorial.service.index', [], false), false)
            ->assertSee(route('workspace.editorial.quote.index', [], false), false);
    }
}
```

- [ ] **Step 3: Run test to verify it fails**

Run: `php artisan test --filter=EditorialAccessTest`
Expected: FAIL — landing route currently handled by Filament panel (200 with Filament dashboard) or route-name errors.

- [ ] **Step 4: Create `app/Http/Middleware/EnsureWorkspacePermission.php`**

```php
<?php

namespace App\Http\Middleware;

use App\Support\Workspace\WorkspaceAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWorkspacePermission
{
    public function __construct(private WorkspaceAccess $workspaceAccess)
    {
    }

    public function handle(Request $request, Closure $next, string $permission): Response
    {
        abort_unless(
            $request->user() !== null && $this->workspaceAccess->can($request->user(), $permission),
            403,
        );

        return $next($request);
    }
}
```

- [ ] **Step 5: Create the landing component `app/Livewire/Workspace/Editorial/EditorialHome.php`**

```php
<?php

namespace App\Livewire\Workspace\Editorial;

use App\Models\Establishment;
use App\Models\Quote;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class EditorialHome extends Component
{
    public function render(): View
    {
        return view('livewire.workspace.editorial.editorial-home', [
            'establishmentCount' => Establishment::query()->count(),
            'serviceCount' => Service::query()->count(),
            'quoteCount' => Quote::query()->count(),
        ])->title(__('editorial.title'));
    }
}
```

Note on layout sections: Livewire renders a full-page component's layout as a Blade *component* (content arrives in `$slot`), while the existing `@extends` pages fill `@yield('content')`. Make `layouts/workspace.blade.php` (from Plan A) serve both with two small edits:

1. The main line becomes:

```blade
<main id="main-content" class="px-4 py-8 sm:px-6 lg:px-8">
    {{ $slot ?? '' }}
    @yield('content')
</main>
```

2. The `<title>` line gains a fallback to `$title` (set by Livewire's `->title(...)`):

```blade
<title>@hasSection('title')@yield('title') · {{ config('app.name') }}@else{{ ($title ?? null) ? $title.' · '.config('app.name') : config('app.name') }}@endif</title>
```

The header's `page-title`/`page-context`/`page-actions` sections stay `@yield`-only: Blade `@extends` pages keep using them, and Livewire editorial pages leave them empty and render their own heading + actions at the top of their template (as Step 6's view does).

- [ ] **Step 6: Create `resources/views/livewire/workspace/editorial/editorial-home.blade.php`**

```blade
<div class="mx-auto max-w-5xl">
    <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ __('editorial.title') }}</h1>
    <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">{{ __('editorial.intro') }}</p>

    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        @foreach ([
            ['count' => $establishmentCount, 'label' => __('editorial.establishments'), 'route' => route('workspace.editorial.establishment.index')],
            ['count' => $serviceCount, 'label' => __('editorial.services'), 'route' => route('workspace.editorial.service.index')],
            ['count' => $quoteCount, 'label' => __('editorial.quotes'), 'route' => route('workspace.editorial.quote.index')],
        ] as $card)
            <a href="{{ $card['route'] }}" wire:navigate class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm transition hover:border-ember-200 hover:shadow-md dark:border-ink-800 dark:bg-ink-900 dark:hover:border-ember-800">
                <p class="text-3xl font-black text-ink-950 dark:text-ink-50">{{ $card['count'] }}</p>
                <p class="mt-1 text-sm font-semibold text-ink-600 dark:text-ink-300">{{ $card['label'] }}</p>
            </a>
        @endforeach
    </div>
</div>
```

- [ ] **Step 7: Create `lang/eng/editorial.php`**

```php
<?php

return [
    'title' => 'Editorial',
    'intro' => 'Manage the public catalog: establishments, services, and quotes.',
    'establishments' => 'Establishments',
    'services' => 'Services',
    'quotes' => 'Quotes',
    'search_placeholder' => 'Search…',
    'new' => 'New',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'delete_confirm' => 'Delete this record? This cannot be undone.',
    'save' => 'Save',
    'cancel' => 'Cancel',
    'created' => 'Record created.',
    'updated' => 'Record updated.',
    'deleted' => 'Record deleted.',
    'empty_title' => 'Nothing here yet',
    'empty_text' => 'Records you create will appear in this list.',
    'name' => 'Name',
    'status' => 'Status',
    'updated_at' => 'Updated',
    'actions' => 'Actions',
];
```

- [ ] **Step 8: Register routes in `routes/web.php`**

Add imports at the top: `use App\Http\Middleware\EnsureWorkspacePermission;` and `use App\Livewire\Workspace\Editorial\EditorialHome;`.

Insert after the existing `workspace/review` group (line ~148):

```php
Route::prefix('workspace/editorial')
    ->name('workspace.editorial.')
    ->middleware(['auth', 'verified', EnsureActiveMember::class, EnsureWorkspacePermission::class.':workspace.editorial.access'])
    ->group(function () {
        Route::get('/', EditorialHome::class)->name('home');
        // Establishment/Service/Quote routes are added by later tasks.
    });
```

Filament's editorial panel also claims `/workspace/editorial` — the panel stays registered until Task 7, so THIS group must win. Laravel matches the first registered route; panel routes register via the provider before `routes/web.php`. Verify with `php artisan route:list --path=workspace/editorial`. If the Filament route shadows the new one, deregister the editorial panel now instead of waiting: remove `EditorialPanelProvider::class` from `bootstrap/providers.php` in this task (the panel's replacement landing page is exactly what this task builds; the resources it served are rebuilt in Tasks 4–6, and until then editors use the new pages as they land). Then update the two `WorkspaceShellTest` tests that expect `/workspace/editorial` to be served by Filament — they assert redirect-to-login for guests and 403/200 by permission, which the new route group satisfies identically, so expected: no test edits needed; run and confirm.

For the temporary create/edit route names referenced by the landing page before Tasks 4–6 exist: the three `*.index` routes must exist for `route()` calls to resolve. Add placeholder routes in the same group NOW, pointing at the index components created in Tasks 4–6 — to keep this task shippable, create the three index component classes in this task as empty shells (they get their real implementation in Tasks 4–6):

```php
Route::get('/establishment', EstablishmentIndex::class)->name('establishment.index');
Route::get('/service', ServiceIndex::class)->name('service.index');
Route::get('/quote', QuoteIndex::class)->name('quote.index');
```

Empty shell pattern (repeat for `ServiceIndex`, `QuoteIndex` with their names/labels), file `app/Livewire/Workspace/Editorial/EstablishmentIndex.php`:

```php
<?php

namespace App\Livewire\Workspace\Editorial;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class EstablishmentIndex extends Component
{
    public function render(): View
    {
        return view('livewire.workspace.editorial.establishment-index')
            ->title(__('editorial.establishments'));
    }
}
```

with view `resources/views/livewire/workspace/editorial/establishment-index.blade.php`:

```blade
<div class="mx-auto max-w-5xl">
    <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ __('editorial.establishments') }}</h1>
</div>
```

(same pattern: `service-index.blade.php`, `quote-index.blade.php`).

- [ ] **Step 9: Run tests**

Run: `php artisan test --filter=EditorialAccessTest`
Expected: PASS (3 tests).
Run: `php artisan test --filter=WorkspaceShellTest`
Expected: PASS — pay attention to `test_founder_assignment_automatically_adds_all_administrative_areas` (hits `/workspace/editorial`, must be 200) and `test_ordinary_member_cannot_open_administrative_panels` (must be 403).

- [ ] **Step 10: Commit**

```bash
git add -A apps/web
git commit -m "feat: editorial landing page, permission middleware, Livewire routes"
```

---

### Task 2: Shared TaxonomyOptions support class

**Files:**
- Create: `app/Support/Taxonomy/TaxonomyOptions.php`
- Modify: `tests/Unit/Filament/EstablishmentTaxonomySourceTest.php` → move to `tests/Unit/Support/TaxonomyOptionsTest.php`

**Interfaces:**
- Produces: `TaxonomyOptions::for(string $fieldName): array<string,string>` — option code ⇒ label. Consumed by all three form components (Tasks 4–6).

- [ ] **Step 1: Read the existing unit test** `tests/Unit/Filament/EstablishmentTaxonomySourceTest.php` to see what behavior it pins (it currently exercises `EstablishmentForm::getTaxonomyOptions` indirectly or via reflection). Recreate the same assertions against the new class.

- [ ] **Step 2: Write the new test** at `tests/Unit/Support/TaxonomyOptionsTest.php`:

```php
<?php

namespace Tests\Unit\Support;

use App\Support\Taxonomy\TaxonomyOptions;
use PHPUnit\Framework\TestCase;

class TaxonomyOptionsTest extends TestCase
{
    public function test_returns_options_for_known_establishment_field(): void
    {
        $options = TaxonomyOptions::for('type_spa');

        $this->assertNotEmpty($options);
        foreach ($options as $code => $label) {
            $this->assertIsString($code);
            $this->assertIsString($label);
        }
    }

    public function test_returns_options_from_shared_contact_taxonomy(): void
    {
        $this->assertNotEmpty(TaxonomyOptions::for('type_contact_channel'));
    }

    public function test_unknown_field_returns_empty_array(): void
    {
        $this->assertSame([], TaxonomyOptions::for('no_such_field'));
    }
}
```

(If the old test pinned additional specific option codes, carry those assertions over verbatim.)

Note: `TaxonomyOptions` uses `base_path()`, which needs the app booted — if `PHPUnit\Framework\TestCase` fails on that, extend `Tests\TestCase` instead.

- [ ] **Step 3: Run to verify it fails** — `php artisan test --filter=TaxonomyOptionsTest` — expected: class not found.

- [ ] **Step 4: Create `app/Support/Taxonomy/TaxonomyOptions.php`** (logic lifted from `EstablishmentForm::getTaxonomyOptions`, unchanged behavior):

```php
<?php

namespace App\Support\Taxonomy;

use Illuminate\Support\Facades\File;

class TaxonomyOptions
{
    /** @return array<string, string> option code => label */
    public static function for(string $fieldName): array
    {
        $repositoryRoot = dirname(base_path(), 2);
        $paths = [
            $repositoryRoot.'/data/taxonomy/massage_nexus/establishment_classification.json',
            $repositoryRoot.'/data/taxonomy/shared/person_identity_and_contact.json',
        ];

        foreach ($paths as $path) {
            if (! File::exists($path)) {
                continue;
            }

            $data = json_decode(File::get($path), true);

            foreach ($data as $field) {
                if ($field['field_name'] === $fieldName) {
                    $options = [];
                    foreach ($field['field_option'] ?? [] as $option) {
                        $options[$option['option_code']] = $option['option_label'];
                    }

                    return $options;
                }
            }
        }

        return [];
    }
}
```

- [ ] **Step 5: Delete the old test file**, run `php artisan test --filter=TaxonomyOptionsTest` — expected PASS; run full `php artisan test` — expected PASS.

- [ ] **Step 6: Commit**

```bash
git add -A apps/web
git commit -m "feat: shared TaxonomyOptions support class"
```

---

### Task 3: Form primitive Blade components

**Files:**
- Create: `resources/views/components/form/field.blade.php`
- Create: `resources/views/components/form/input.blade.php`
- Create: `resources/views/components/form/textarea.blade.php`
- Create: `resources/views/components/form/select.blade.php`
- Create: `resources/views/components/form/toggle-group.blade.php`
- Create: `resources/views/components/editorial/tab-bar.blade.php`

**Interfaces:**
- Produces (consumed by Tasks 4–6 forms):
  - `<x-form.field :label="__('...')" name="wire-model-name" :error="$errors->first('state.x')">…input…</x-form.field>` — label + slot + error line.
  - `<x-form.input wire:model="state.x" />`, `<x-form.textarea rows="3" wire:model="state.x" />`, `<x-form.select :options="$array" wire:model="state.x" :placeholder="__('...')" />`, `<x-form.select multiple …>`.
  - `<x-form.toggle-group :options="$array" wire:model="state.x" />` — multi-select pill buttons (checkbox-based).
  - `<x-editorial.tab-bar :tabs="['identity' => __('...'), ...]" />` — Alpine tab buttons; expects parent `x-data="{ tab: 'identity' }"`; panels use `x-show="tab === 'identity'"`.

- [ ] **Step 1: Create the components**

`components/form/field.blade.php`:

```blade
@props(['label', 'name' => null, 'error' => null, 'help' => null])
<div {{ $attributes }}>
    <label @if ($name) for="{{ $name }}" @endif class="mb-1.5 block text-sm font-semibold text-ink-800 dark:text-ink-200">{{ $label }}</label>
    {{ $slot }}
    @if ($help)
        <p class="mt-1.5 text-xs text-ink-500 dark:text-ink-400">{{ $help }}</p>
    @endif
    @if ($error)
        <p class="mt-1.5 text-xs font-semibold text-ember-600 dark:text-ember-400">{{ $error }}</p>
    @endif
</div>
```

`components/form/input.blade.php`:

```blade
<input {{ $attributes->merge(['type' => 'text', 'class' => 'w-full rounded-lg border border-ink-200 bg-white px-3 py-2 text-sm text-ink-950 placeholder:text-ink-400 focus:border-ember-400 focus:outline-none dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50 dark:placeholder:text-ink-400']) }}>
```

`components/form/textarea.blade.php`:

```blade
<textarea {{ $attributes->merge(['rows' => 3, 'class' => 'w-full rounded-lg border border-ink-200 bg-white px-3 py-2 text-sm text-ink-950 placeholder:text-ink-400 focus:border-ember-400 focus:outline-none dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50 dark:placeholder:text-ink-400']) }}></textarea>
```

`components/form/select.blade.php`:

```blade
@props(['options' => [], 'placeholder' => null])
<select {{ $attributes->merge(['class' => 'w-full rounded-lg border border-ink-200 bg-white px-3 py-2 text-sm text-ink-950 focus:border-ember-400 focus:outline-none dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50']) }}>
    @if ($placeholder !== null && ! $attributes->has('multiple'))
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach ($options as $value => $label)
        <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
</select>
```

`components/form/toggle-group.blade.php` (multi-select checkboxes styled as pills; works with `wire:model` on each checkbox sharing an array property):

```blade
@props(['options' => [], 'model'])
<div class="flex flex-wrap gap-2" {{ $attributes }}>
    @foreach ($options as $value => $label)
        <label class="cursor-pointer">
            <input type="checkbox" value="{{ $value }}" wire:model="{{ $model }}" class="peer sr-only">
            <span class="inline-block rounded-full border border-ink-200 px-3 py-1.5 text-xs font-semibold text-ink-700 transition peer-checked:border-ember-500 peer-checked:bg-ember-50 peer-checked:text-ember-700 dark:border-ink-700 dark:text-ink-200 dark:peer-checked:bg-ember-950 dark:peer-checked:text-ember-400">{{ $label }}</span>
        </label>
    @endforeach
</div>
```

`components/editorial/tab-bar.blade.php`:

```blade
@props(['tabs' => []])
<div class="flex flex-wrap gap-1 border-b border-ink-100 dark:border-ink-800" role="tablist">
    @foreach ($tabs as $key => $label)
        <button type="button" role="tab" @click="tab = '{{ $key }}'"
                :aria-selected="(tab === '{{ $key }}').toString()"
                :class="tab === '{{ $key }}'
                    ? 'border-ember-500 text-ember-600 dark:text-ember-400'
                    : 'border-transparent text-ink-600 hover:text-ink-950 dark:text-ink-300 dark:hover:text-ink-50'"
                class="-mb-px border-b-2 px-3.5 py-2.5 text-sm font-semibold transition">
            {{ $label }}
        </button>
    @endforeach
</div>
```

- [ ] **Step 2: Verify** — `npm run build` succeeds; `php artisan view:clear && php artisan test` still PASS (components unused yet).

- [ ] **Step 3: Commit**

```bash
git add -A apps/web
git commit -m "feat: shared form primitive components for editorial forms"
```

---

### Task 4: Quote CRUD

**Files:**
- Modify: `app/Livewire/Workspace/Editorial/QuoteIndex.php` (replace Task 1 shell)
- Modify: `resources/views/livewire/workspace/editorial/quote-index.blade.php`
- Create: `app/Livewire/Workspace/Editorial/QuoteForm.php`
- Create: `resources/views/livewire/workspace/editorial/quote-form.blade.php`
- Modify: `routes/web.php` (add create/edit routes)
- Modify: `lang/eng/editorial.php`
- Test: `tests/Feature/Editorial/QuoteCrudTest.php`

**Interfaces:**
- Consumes: `TaxonomyOptions` — not needed here (Quote uses enums); form primitives (Task 3); layout pattern (Task 1).
- Produces: route names `workspace.editorial.quote.create` / `.edit`; the Index/Form component pattern that Tasks 5–6 mirror (search property `$search`, `deleteRecord($id)` action, form `$state` array + `save()`).

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature\Editorial;

use App\Models\AccessAssignment;
use App\Models\Quote;
use App\Models\User;
use Livewire\Livewire;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class QuoteCrudTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function setUp(): void
    {
        parent::setUp();
        AccessAssignment::query()->delete();
        Quote::query()->delete();
    }

    protected function tearDown(): void
    {
        AccessAssignment::query()->delete();
        Quote::query()->delete();
        parent::tearDown();
    }

    private function editor(): User
    {
        $user = User::factory()->create();
        AccessAssignment::query()->create([
            'user_id' => (string) $user->getKey(),
            'role_workspace' => 'EAD',
            'scope_access' => 'GBL',
            'status_access_assignment' => 'ACT',
            'effective_at' => now()->subMinute(),
        ]);

        return $user;
    }

    public function test_index_lists_and_searches_quotes(): void
    {
        $user = $this->editor();
        Quote::query()->create(['quote_text' => ['eng' => ['text' => 'Stillness heals.']]]);
        Quote::query()->create(['quote_text' => ['eng' => ['text' => 'Movement restores.']]]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Workspace\Editorial\QuoteIndex::class)
            ->assertSee('Stillness heals.')
            ->assertSee('Movement restores.')
            ->set('search', 'Stillness')
            ->assertSee('Stillness heals.')
            ->assertDontSee('Movement restores.');
    }

    public function test_editor_can_create_a_quote(): void
    {
        $user = $this->editor();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Workspace\Editorial\QuoteForm::class)
            ->set('state.english_text', 'Rest is productive.')
            ->set('state.attribution_name', 'Unknown')
            ->call('save')
            ->assertRedirect(route('workspace.editorial.quote.index'));

        $this->assertSame(1, Quote::query()->count());
        $this->assertSame('Rest is productive.', Quote::query()->first()->english_text);
    }

    public function test_create_requires_english_text(): void
    {
        Livewire::actingAs($this->editor())
            ->test(\App\Livewire\Workspace\Editorial\QuoteForm::class)
            ->set('state.english_text', '')
            ->call('save')
            ->assertHasErrors(['state.english_text' => 'required']);
    }

    public function test_editor_can_update_a_quote(): void
    {
        $user = $this->editor();
        $quote = Quote::query()->create(['quote_text' => ['eng' => ['text' => 'Old text.']]]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Workspace\Editorial\QuoteForm::class, ['quote' => (string) $quote->getKey()])
            ->assertSet('state.english_text', 'Old text.')
            ->set('state.english_text', 'New text.')
            ->call('save');

        $this->assertSame('New text.', $quote->refresh()->english_text);
    }

    public function test_editor_can_delete_a_quote(): void
    {
        $user = $this->editor();
        $quote = Quote::query()->create(['quote_text' => ['eng' => ['text' => 'Doomed.']]]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Workspace\Editorial\QuoteIndex::class)
            ->call('deleteRecord', (string) $quote->getKey());

        $this->assertSame(0, Quote::query()->count());
    }
}
```

- [ ] **Step 2: Run to verify failure** — `php artisan test --filter=QuoteCrudTest` — expected FAIL (`QuoteForm` missing, index has no `search`).

- [ ] **Step 3: Implement `QuoteIndex`**

```php
<?php

namespace App\Livewire\Workspace\Editorial;

use App\Models\Quote;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class QuoteIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function deleteRecord(string $id): void
    {
        Quote::query()->where('_id', $id)->delete();
        session()->flash('editorial_status', __('editorial.deleted'));
    }

    public function render(): View
    {
        $query = Quote::query()->orderBy('updated_at', 'desc');

        if ($this->search !== '') {
            $query->where('quote_text.eng.text', 'like', '%'.$this->search.'%');
        }

        return view('livewire.workspace.editorial.quote-index', [
            'quotes' => $query->paginate(15),
        ])->title(__('editorial.quotes'));
    }
}
```

- [ ] **Step 4: Implement the index view** `quote-index.blade.php` (this exact structure is the template for Tasks 5–6 index views):

```blade
<div class="mx-auto max-w-5xl">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ __('editorial.quotes') }}</h1>
            <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">{{ __('editorial.intro') }}</p>
        </div>
        <a href="{{ route('workspace.editorial.quote.create') }}" wire:navigate class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('editorial.new') }}</a>
    </div>

    @if (session('editorial_status'))
        <p class="mt-4 rounded-lg border border-leaf-200 bg-leaf-50 px-4 py-2.5 text-sm font-semibold text-leaf-700 dark:border-leaf-800 dark:bg-leaf-950 dark:text-leaf-300">{{ session('editorial_status') }}</p>
    @endif

    <div class="mt-5">
        <x-form.input wire:model.live.debounce.300ms="search" placeholder="{{ __('editorial.search_placeholder') }}" class="max-w-sm" />
    </div>

    <div class="mt-4 overflow-x-auto rounded-2xl border border-ink-100 bg-white shadow-sm dark:border-ink-800 dark:bg-ink-900">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-ink-100 text-xs font-bold uppercase tracking-wider text-ink-500 dark:border-ink-800 dark:text-ink-400">
                    <th class="px-5 py-3">{{ __('editorial.name') }}</th>
                    <th class="px-5 py-3">{{ __('editorial.status') }}</th>
                    <th class="px-5 py-3">{{ __('editorial.updated_at') }}</th>
                    <th class="px-5 py-3 text-right">{{ __('editorial.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quotes as $quote)
                    <tr class="border-b border-ink-100 last:border-0 dark:border-ink-800" wire:key="{{ $quote->getKey() }}">
                        <td class="max-w-md truncate px-5 py-3 font-semibold text-ink-950 dark:text-ink-50">{{ $quote->english_text }}</td>
                        <td class="px-5 py-3">
                            <span class="rounded-full bg-ink-50 px-2.5 py-1 text-xs font-bold text-ink-700 dark:bg-ink-800 dark:text-ink-200">{{ $quote->status_record_lifecycle?->value ?? '—' }}</span>
                        </td>
                        <td class="px-5 py-3 text-ink-600 dark:text-ink-300">{{ $quote->updated_at?->format('M j, Y') }}</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('workspace.editorial.quote.edit', $quote->getKey()) }}" wire:navigate class="text-sm font-bold text-ember-600 hover:text-ember-700 dark:text-ember-400 dark:hover:text-ember-300">{{ __('editorial.edit') }}</a>
                            <button type="button" wire:click="deleteRecord('{{ $quote->getKey() }}')" wire:confirm="{{ __('editorial.delete_confirm') }}" class="ml-3 text-sm font-bold text-ink-500 hover:text-ember-600 dark:text-ink-400 dark:hover:text-ember-400">{{ __('editorial.delete') }}</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center">
                            <p class="font-bold text-ink-700 dark:text-ink-200">{{ __('editorial.empty_title') }}</p>
                            <p class="mt-1 text-sm text-ink-500 dark:text-ink-400">{{ __('editorial.empty_text') }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $quotes->links() }}</div>
</div>
```

- [ ] **Step 5: Implement `QuoteForm`**

```php
<?php

namespace App\Livewire\Workspace\Editorial;

use App\Enums\NsfwLevel;
use App\Enums\QuoteCategory;
use App\Enums\RecordLifecycleStatus;
use App\Enums\ReviewStatus;
use App\Models\Quote;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class QuoteForm extends Component
{
    public ?string $quote = null;

    /** @var array<string, mixed> */
    public array $state = [
        'english_text' => '',
        'attribution_name' => '',
        'source_title' => '',
        'source_url' => '',
        'language_original_id' => 3049,
        'type_quote_category' => [],
        'is_display_enabled' => true,
        'display_start_date' => null,
        'display_end_date' => null,
        'status_review' => 'P',
        'level_nsfw' => 'N',
        'status_record_lifecycle' => 'ACT',
    ];

    public function mount(?string $quote = null): void
    {
        $this->quote = $quote;

        if ($quote !== null) {
            $record = Quote::query()->findOrFail($quote);
            $this->state = [
                'english_text' => $record->english_text ?? '',
                'attribution_name' => $record->attribution_name ?? '',
                'source_title' => $record->source_title ?? '',
                'source_url' => $record->source_url ?? '',
                'language_original_id' => $record->language_original_id ?? 3049,
                'type_quote_category' => $record->type_quote_category ?? [],
                'is_display_enabled' => (bool) ($record->is_display_enabled ?? true),
                'display_start_date' => $record->display_start_date?->format('Y-m-d'),
                'display_end_date' => $record->display_end_date?->format('Y-m-d'),
                'status_review' => $record->status_review?->value ?? 'P',
                'level_nsfw' => $record->level_nsfw?->value ?? 'N',
                'status_record_lifecycle' => $record->status_record_lifecycle?->value ?? 'ACT',
            ];
        }
    }

    /** @return array<string, mixed> */
    protected function rules(): array
    {
        return [
            'state.english_text' => ['required', 'string', 'max:500'],
            'state.attribution_name' => ['nullable', 'string', 'max:150'],
            'state.source_title' => ['nullable', 'string', 'max:200'],
            'state.source_url' => ['nullable', 'url', 'max:500'],
            'state.language_original_id' => ['required', 'integer'],
            'state.type_quote_category' => ['array'],
            'state.is_display_enabled' => ['boolean'],
            'state.display_start_date' => ['nullable', 'date'],
            'state.display_end_date' => ['nullable', 'date', 'after_or_equal:state.display_start_date'],
            'state.status_review' => ['required', 'string'],
            'state.level_nsfw' => ['required', 'string'],
            'state.status_record_lifecycle' => ['required', 'string'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $record = $this->quote !== null
            ? Quote::query()->findOrFail($this->quote)
            : new Quote;

        $record->english_text = $this->state['english_text'];
        $record->fill([
            'attribution_name' => $this->state['attribution_name'] ?: null,
            'source_title' => $this->state['source_title'] ?: null,
            'source_url' => $this->state['source_url'] ?: null,
            'language_original_id' => (int) $this->state['language_original_id'],
            'type_quote_category' => array_values($this->state['type_quote_category']),
            'is_display_enabled' => (bool) $this->state['is_display_enabled'],
            'display_start_date' => $this->state['display_start_date'] ?: null,
            'display_end_date' => $this->state['display_end_date'] ?: null,
            'status_review' => $this->state['status_review'],
            'level_nsfw' => $this->state['level_nsfw'],
            'status_record_lifecycle' => $this->state['status_record_lifecycle'],
        ]);
        $record->save();

        session()->flash('editorial_status', $this->quote !== null ? __('editorial.updated') : __('editorial.created'));
        $this->redirectRoute('workspace.editorial.quote.index', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.workspace.editorial.quote-form', [
            'categoryOptions' => collect(QuoteCategory::cases())->mapWithKeys(fn ($c) => [$c->value => $c->name])->all(),
            'reviewOptions' => collect(ReviewStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->name])->all(),
            'nsfwOptions' => collect(NsfwLevel::cases())->mapWithKeys(fn ($c) => [$c->value => $c->name])->all(),
            'lifecycleOptions' => collect(RecordLifecycleStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->name])->all(),
        ])->title(__('editorial.quotes'));
    }
}
```

(If the enums implement `HasLabel` or a `label()` method, use that instead of `->name` for display labels — check one enum file and match.)

- [ ] **Step 6: Implement the form view** `quote-form.blade.php`:

```blade
<div class="mx-auto max-w-3xl">
    <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ $quote ? __('editorial.edit') : __('editorial.new') }} — {{ __('editorial.quotes') }}</h1>

    <form wire:submit="save" class="mt-6 space-y-5 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
        <x-form.field :label="__('editorial.quote_text')" :error="$errors->first('state.english_text')">
            <x-form.textarea wire:model="state.english_text" rows="4" maxlength="500" />
        </x-form.field>

        <div class="grid gap-5 sm:grid-cols-2">
            <x-form.field :label="__('editorial.attribution_name')" :error="$errors->first('state.attribution_name')">
                <x-form.input wire:model="state.attribution_name" maxlength="150" />
            </x-form.field>
            <x-form.field :label="__('editorial.source_title')" :error="$errors->first('state.source_title')">
                <x-form.input wire:model="state.source_title" maxlength="200" />
            </x-form.field>
        </div>

        <x-form.field :label="__('editorial.source_url')" :error="$errors->first('state.source_url')">
            <x-form.input wire:model="state.source_url" type="url" maxlength="500" />
        </x-form.field>

        <x-form.field :label="__('editorial.quote_category')" :error="$errors->first('state.type_quote_category')">
            <x-form.toggle-group :options="$categoryOptions" model="state.type_quote_category" />
        </x-form.field>

        <div class="grid gap-5 sm:grid-cols-3">
            <x-form.field :label="__('editorial.display_start_date')" :error="$errors->first('state.display_start_date')">
                <x-form.input wire:model="state.display_start_date" type="date" />
            </x-form.field>
            <x-form.field :label="__('editorial.display_end_date')" :error="$errors->first('state.display_end_date')">
                <x-form.input wire:model="state.display_end_date" type="date" />
            </x-form.field>
            <x-form.field :label="__('editorial.display_enabled')">
                <label class="mt-2 inline-flex cursor-pointer items-center gap-2 text-sm font-semibold text-ink-700 dark:text-ink-200">
                    <input type="checkbox" wire:model="state.is_display_enabled" class="size-4 rounded border-ink-300 text-ember-500 focus:ring-ember-400">
                    {{ __('editorial.display_enabled') }}
                </label>
            </x-form.field>
        </div>

        <div class="grid gap-5 sm:grid-cols-3">
            <x-form.field :label="__('editorial.review_status')" :error="$errors->first('state.status_review')">
                <x-form.select wire:model="state.status_review" :options="$reviewOptions" />
            </x-form.field>
            <x-form.field :label="__('editorial.nsfw_level')" :error="$errors->first('state.level_nsfw')">
                <x-form.select wire:model="state.level_nsfw" :options="$nsfwOptions" />
            </x-form.field>
            <x-form.field :label="__('editorial.lifecycle_status')" :error="$errors->first('state.status_record_lifecycle')">
                <x-form.select wire:model="state.status_record_lifecycle" :options="$lifecycleOptions" />
            </x-form.field>
        </div>

        <div class="flex items-center justify-end gap-2.5 border-t border-ink-100 pt-5 dark:border-ink-800">
            <a href="{{ route('workspace.editorial.quote.index') }}" wire:navigate class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('editorial.cancel') }}</a>
            <button type="submit" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('editorial.save') }}</button>
        </div>
    </form>
</div>
```

- [ ] **Step 7: Add routes and lang keys**

Routes (inside the Task 1 editorial group), plus import `use App\Livewire\Workspace\Editorial\QuoteForm as QuoteFormComponent;` — or reference with FQCN to avoid alias clutter:

```php
Route::get('/quote/new', \App\Livewire\Workspace\Editorial\QuoteForm::class)->name('quote.create');
Route::get('/quote/{quote}/edit', \App\Livewire\Workspace\Editorial\QuoteForm::class)->name('quote.edit');
```

Lang additions to `lang/eng/editorial.php`:

```php
'quote_text' => 'Quote text (English)',
'attribution_name' => 'Attribution name',
'source_title' => 'Source title',
'source_url' => 'Source URL',
'quote_category' => 'Quote category',
'display_start_date' => 'Display start date',
'display_end_date' => 'Display end date',
'display_enabled' => 'Display enabled',
'review_status' => 'Review status',
'nsfw_level' => 'NSFW level',
'lifecycle_status' => 'Lifecycle status',
```

- [ ] **Step 8: Run tests** — `php artisan test --filter=QuoteCrudTest` — expected PASS (5 tests). Full suite PASS.

- [ ] **Step 9: Commit**

```bash
git add -A apps/web
git commit -m "feat: quote CRUD in Livewire editorial workspace"
```

---

### Task 5: Service CRUD

**Files:**
- Modify: `app/Livewire/Workspace/Editorial/ServiceIndex.php`
- Modify: `resources/views/livewire/workspace/editorial/service-index.blade.php`
- Create: `app/Livewire/Workspace/Editorial/ServiceForm.php`
- Create: `resources/views/livewire/workspace/editorial/service-form.blade.php`
- Modify: `routes/web.php`, `lang/eng/editorial.php`
- Test: `tests/Feature/Editorial/ServiceCrudTest.php`

**Interfaces:**
- Consumes: primitives (Task 3), Index/Form pattern (Task 4).
- Produces: routes `workspace.editorial.service.create` / `.edit`.

Mirror Task 4 exactly with these Service-specific differences (everything else — index component shape, view structure, delete/confirm, flash, pagination — is byte-for-byte the Task 4 pattern with `Quote`→`Service`, `quote`→`service`):

- Index search field: `service_name.eng` (`$query->where('service_name.eng', 'like', ...)`); name column shows `$service->english_name`; status badge and updated column identical.
- Form `$state` keys and rules:

```php
public array $state = [
    'english_name' => '',
    'english_short_description' => '',
    'english_overview' => '',
    'chinese_name' => '',
    'chinese_short_description' => '',
    'chinese_overview' => '',
    'service_slug' => '',
    'group_service_sector' => '',
    'group_service_domain' => '',
    'group_service_family' => '',
    'status_record_lifecycle' => 'ACT',
];
```

```php
protected function rules(): array
{
    return [
        'state.english_name' => ['required', 'string', 'max:150'],
        'state.english_short_description' => ['nullable', 'string', 'max:300'],
        'state.english_overview' => ['nullable', 'string', 'max:2000'],
        'state.chinese_name' => ['nullable', 'string', 'max:150'],
        'state.chinese_short_description' => ['nullable', 'string', 'max:300'],
        'state.chinese_overview' => ['nullable', 'string', 'max:2000'],
        'state.service_slug' => ['required', 'string', 'max:100'],
        'state.group_service_sector' => ['nullable', 'string', 'max:100'],
        'state.group_service_domain' => ['nullable', 'string', 'max:100'],
        'state.group_service_family' => ['required', 'string', 'max:100'],
        'state.status_record_lifecycle' => ['required', 'string'],
    ];
}
```

- Slug uniqueness (Filament had `unique(ignoreRecord: true)`; Mongo has no validation-rule integration here) — enforce in `save()` before writing:

```php
$slugTaken = Service::query()
    ->where('service_slug', $this->state['service_slug'])
    ->when($this->service !== null, fn ($q) => $q->where('_id', '!=', $this->service))
    ->exists();

if ($slugTaken) {
    $this->addError('state.service_slug', __('editorial.slug_taken'));

    return;
}
```

- `save()` writes via accessors: `english_name`, `english_short_description`, `english_overview`, `chinese_name`, `chinese_short_description`, `chinese_overview` (skip Chinese setters when the state value is `''`), then `fill()` for `service_slug`, the three `group_service_*`, `status_record_lifecycle`.
- `mount()` loads the same fields from the record's accessors (`?? ''`).
- Form view: two-tab layout using `<x-editorial.tab-bar :tabs="['eng' => __('editorial.tab_english'), 'zho' => __('editorial.tab_chinese')]" />` inside `<div x-data="{ tab: 'eng' }">`; each tab panel `x-show="tab === 'eng'"` (`x-cloak` on the second) holds name/short-description/overview fields for that language; below the tabs: slug, sector/domain/family in a 3-column grid, lifecycle select, cancel/save buttons — all using the Task 4 form view classes.
- Routes: `/service/new` → `service.create`, `/service/{service}/edit` → `service.edit`.
- Lang additions:

```php
'tab_english' => 'English',
'tab_chinese' => 'Chinese (Traditional)',
'service_name' => 'Primary name',
'short_description' => 'Short description',
'overview' => 'Overview',
'slug' => 'Slug',
'slug_taken' => 'This slug is already in use.',
'sector' => 'Sector',
'domain' => 'Domain',
'family' => 'Family',
```

- Test file `ServiceCrudTest.php`: mirror `QuoteCrudTest` — list/search on English name, create (asserting `service_slug` and `english_name` persisted), required-field errors (`state.english_name`, `state.service_slug`, `state.group_service_family`), update, delete, plus one slug-collision test:

```php
public function test_duplicate_slug_is_rejected(): void
{
    $user = $this->editor();
    Service::query()->create(['service_slug' => 'thai-massage', 'service_name' => ['eng' => 'Thai'], 'group_service_family' => 'massage']);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Workspace\Editorial\ServiceForm::class)
        ->set('state.english_name', 'Another')
        ->set('state.service_slug', 'thai-massage')
        ->set('state.group_service_family', 'massage')
        ->call('save')
        ->assertHasErrors(['state.service_slug']);

    $this->assertSame(1, Service::query()->count());
}
```

- [ ] **Step 1: Write `ServiceCrudTest` (full mirror per above). Run: expected FAIL.**
- [ ] **Step 2: Implement `ServiceIndex` (Task 4 pattern + differences above). Run index tests: expected PASS.**
- [ ] **Step 3: Implement `ServiceForm` + view (differences above). Run: `php artisan test --filter=ServiceCrudTest` — expected PASS.**
- [ ] **Step 4: Full suite: `php artisan test` — expected PASS.**
- [ ] **Step 5: Commit**

```bash
git add -A apps/web
git commit -m "feat: service CRUD in Livewire editorial workspace"
```

---

### Task 6: Establishment CRUD

**Files:**
- Modify: `app/Livewire/Workspace/Editorial/EstablishmentIndex.php`
- Modify: `resources/views/livewire/workspace/editorial/establishment-index.blade.php`
- Create: `app/Livewire/Workspace/Editorial/EstablishmentForm.php`
- Create: `resources/views/livewire/workspace/editorial/establishment-form.blade.php`
- Modify: `routes/web.php`, `lang/eng/editorial.php`
- Test: `tests/Feature/Editorial/EstablishmentCrudTest.php`

**Interfaces:**
- Consumes: `TaxonomyOptions::for()` (Task 2), primitives incl. tab-bar and toggle-group (Task 3), Index/Form pattern (Task 4).
- Produces: routes `workspace.editorial.establishment.create` / `.edit`.

Index: Task 4 pattern; search on `display_name.eng`; name column `$establishment->english_name`.

Form component — the big one. Structure:

```php
<?php

namespace App\Livewire\Workspace\Editorial;

use App\Models\Establishment;
use App\Rules\PublicContactUrl;
use App\Support\Taxonomy\TaxonomyOptions;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class EstablishmentForm extends Component
{
    public ?string $establishment = null;

    /** @var array<string, mixed> */
    public array $state = [];

    /** Scalar/plain fields copied verbatim between $state and the model. */
    private const PLAIN_FIELDS = [
        'email', 'contact_number', 'address_public', 'coordinate_latitude',
        'coordinate_longitude', 'type_spa', 'level_spa_market',
        'type_physical_setting', 'type_establishment_operation',
        'status_establishment', 'mode_access', 'type_client_access',
        'status_record_lifecycle', 'shower_availability', 'sauna_availability',
        'steam_room_availability', 'jacuzzi_availability', 'locker_availability',
        'couple_room_availability', 'private_room_availability',
        'curtain_divider_information', 'air_conditioning_information',
    ];

    /** Multi-select array fields. */
    private const LIST_FIELDS = [
        'mode_service_delivery', 'target_client_focus', 'amenities',
        'room_types', 'bed_mat_chair_setup', 'accessibility_information',
    ];

    /** Repeater fields with their blank-row shapes. */
    private const REPEATERS = [
        'landmark_list' => ['landmark_name' => '', 'walking_duration_minute' => null],
        'contact_channel_list' => ['type_contact_channel' => '', 'type_contact_number' => '', 'contact_label' => '', 'contact_value' => '', 'contact_url' => '', 'status_contact_channel' => ''],
        'treatment_area_list' => ['treatment_area_name' => '', 'type_treatment_area' => '', 'level_treatment_privacy' => '', 'type_treatment_capacity' => '', 'treatment_area_note' => ''],
        'operating_hours' => ['day_of_week' => '', 'open_time' => null, 'close_time' => null],
    ];

    /** Fixed value list for the operating_hours day_of_week select — not taxonomy-driven, mirrors the Filament source form verbatim. */
    private const DAYS_OF_WEEK = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'Public Holidays'];

    public function mount(?string $establishment = null): void
    {
        $this->establishment = $establishment;

        $record = $establishment !== null ? Establishment::query()->findOrFail($establishment) : null;

        $this->state = [
            'display_name_eng' => $record?->display_name['eng'] ?? '',
            'short_description_eng' => $record?->short_description['eng'] ?? '',
            'description_eng' => $record?->description['eng'] ?? '',
            'direction_note_eng' => $record?->direction_note['eng'] ?? '',
            'parking_note_eng' => $record?->parking_note['eng'] ?? '',
        ];

        foreach (self::PLAIN_FIELDS as $field) {
            $this->state[$field] = $record?->getAttribute($field) ?? ($field === 'status_record_lifecycle' ? 'ACT' : '');
        }

        foreach (self::LIST_FIELDS as $field) {
            $this->state[$field] = $record?->getAttribute($field) ?? [];
        }

        foreach (self::REPEATERS as $field => $blank) {
            $this->state[$field] = $record?->getAttribute($field) ?? [];
        }

        // Filament's source form defaulted new establishments to 7 blank operating_hours rows
        // (Monday–Sunday); replicate that so the Livewire form doesn't regress the authoring experience.
        if ($record === null) {
            foreach (array_slice(self::DAYS_OF_WEEK, 0, 7) as $day) {
                $this->state['operating_hours'][] = ['day_of_week' => $day, 'open_time' => null, 'close_time' => null];
            }
        }
    }

    public function addRow(string $repeater): void
    {
        abort_unless(array_key_exists($repeater, self::REPEATERS), 400);
        $this->state[$repeater][] = self::REPEATERS[$repeater];
    }

    public function removeRow(string $repeater, int $index): void
    {
        abort_unless(array_key_exists($repeater, self::REPEATERS), 400);
        unset($this->state[$repeater][$index]);
        $this->state[$repeater] = array_values($this->state[$repeater]);
    }

    /** @return array<string, mixed> */
    protected function rules(): array
    {
        return [
            'state.display_name_eng' => ['required', 'string', 'max:255'],
            'state.short_description_eng' => ['nullable', 'string'],
            'state.description_eng' => ['nullable', 'string'],
            'state.email' => ['nullable', 'email', 'max:255'],
            'state.contact_number' => ['nullable', 'string', 'max:255'],
            'state.type_spa' => ['required', 'string'],
            'state.status_establishment' => ['required', 'string'],
            'state.status_record_lifecycle' => ['required', 'string'],
            'state.coordinate_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'state.coordinate_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'state.landmark_list.*.landmark_name' => ['required', 'string', 'max:255'],
            'state.landmark_list.*.walking_duration_minute' => ['nullable', 'numeric', 'min:0'],
            'state.contact_channel_list.*.type_contact_channel' => ['required', 'string'],
            'state.contact_channel_list.*.contact_label' => ['required', 'string', 'max:100'],
            'state.contact_channel_list.*.contact_value' => ['required', 'string', 'max:255'],
            'state.contact_channel_list.*.contact_url' => ['required', 'string', 'max:2048', new PublicContactUrl],
            'state.treatment_area_list.*.treatment_area_name' => ['required', 'string', 'max:255'],
            'state.treatment_area_list.*.treatment_area_note' => ['nullable', 'string', 'max:255'],
            'state.operating_hours.*.day_of_week' => ['required', 'string', Rule::in(self::DAYS_OF_WEEK)],
            'state.operating_hours.*.open_time' => ['nullable', 'date_format:H:i'],
            'state.operating_hours.*.close_time' => ['nullable', 'date_format:H:i'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $record = $this->establishment !== null
            ? Establishment::query()->findOrFail($this->establishment)
            : new Establishment;

        foreach (['display_name' => 'display_name_eng', 'short_description' => 'short_description_eng', 'description' => 'description_eng', 'direction_note' => 'direction_note_eng', 'parking_note' => 'parking_note_eng'] as $field => $stateKey) {
            $value = $record->getAttribute($field) ?? [];
            $value['eng'] = $this->state[$stateKey] ?: null;
            $record->setAttribute($field, $value);
        }

        $plain = [];
        foreach (self::PLAIN_FIELDS as $field) {
            $plain[$field] = $this->state[$field] === '' ? null : $this->state[$field];
        }
        foreach (self::LIST_FIELDS as $field) {
            $plain[$field] = array_values($this->state[$field] ?? []);
        }
        foreach (array_keys(self::REPEATERS) as $field) {
            $plain[$field] = array_values($this->state[$field] ?? []);
        }
        $record->fill($plain);
        $record->save();

        session()->flash('editorial_status', $this->establishment !== null ? __('editorial.updated') : __('editorial.created'));
        $this->redirectRoute('workspace.editorial.establishment.index', navigate: true);
    }

    public function render(): View
    {
        $taxonomy = [];
        foreach ([
            'type_spa', 'level_spa_market', 'type_physical_setting',
            'type_establishment_operation', 'status_establishment',
            'mode_service_delivery', 'mode_access', 'type_client_access',
            'target_client_focus', 'type_contact_channel', 'type_contact_number',
            'status_contact_channel', 'type_treatment_area',
            'level_treatment_privacy', 'type_treatment_capacity', 'room_types',
            'bed_mat_chair_setup', 'shower_availability', 'sauna_availability',
            'steam_room_availability', 'jacuzzi_availability',
            'locker_availability', 'couple_room_availability',
            'private_room_availability', 'curtain_divider_information',
            'air_conditioning_information', 'amenities', 'accessibility_information',
        ] as $field) {
            $taxonomy[$field] = TaxonomyOptions::for($field);
        }

        return view('livewire.workspace.editorial.establishment-form', [
            'taxonomy' => $taxonomy,
            'lifecycleOptions' => collect(\App\Enums\RecordLifecycleStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->name])->all(),
            'dayOfWeekOptions' => collect(self::DAYS_OF_WEEK)->mapWithKeys(fn ($d) => [$d => $d])->all(),
        ])->title(__('editorial.establishments'));
    }
}
```

Form view `establishment-form.blade.php` — eight tab panels inside `<div x-data="{ tab: 'identity' }">` with `<x-editorial.tab-bar :tabs="[...]"/>`, mirroring the current Filament tab layout exactly — Filament's source form (`app/Filament/Editorial/Resources/Establishments/Schemas/EstablishmentForm.php`, as of the `operating_hours` addition) now splits the old combined Location tab into separate Location and Contact tabs and adds a dedicated Operating Hours tab; port that structure, not the older 6-tab layout. Every field uses the Task 3 primitives; selects get `:options="$taxonomy['<field>']"` with `:placeholder="__('editorial.select_placeholder')"`; multi-selects use `<x-form.toggle-group :options="$taxonomy['<field>']" model="state.<field>" />`. Tab-to-field mapping (same as the Filament form):

1. `identity`: display_name_eng (input, required), short_description_eng (textarea rows 3), description_eng (textarea rows 8), email, contact_number, status_record_lifecycle (select `$lifecycleOptions`).
2. `classification` (2-col grid): type_spa (required), level_spa_market, type_physical_setting, type_establishment_operation, status_establishment (required) — all selects.
3. `access`: mode_service_delivery (toggle-group), mode_access (select), type_client_access (select), target_client_focus (toggle-group).
4. `location`: address_public (textarea rows 2, full width), coordinate_latitude + coordinate_longitude (2-col, `type="number" step="any"`), direction_note_eng (textarea), parking_note_eng (textarea), then the landmark_list repeater only.
5. `contact`: contact_channel_list repeater only (moved out of `location`).
6. `hours`: operating_hours repeater — day_of_week (`<x-form.select :options="$dayOfWeekOptions" />`, required), open_time and close_time (`<x-form.input type="time" />`), 3-column row layout, no add/remove needed to feel required (still wire the generic addRow/removeRow so extra custom hours, e.g. a second Public Holidays entry, can be added) — see the operating-hours repeater markup below.
7. `facilities`: treatment_area_list repeater (fields: treatment_area_name input required, type_treatment_area / level_treatment_privacy / type_treatment_capacity selects, treatment_area_note input), then 3-col grid of the eleven facility selects (room_types + bed_mat_chair_setup as toggle-groups; the nine availability/information fields as selects).
8. `amenities`: amenities toggle-group, accessibility_information toggle-group.

Repeater markup pattern (landmark example — contact channels and treatment areas repeat this pattern with their fields):

```blade
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
        </div>
    @endforeach
    <button type="button" wire:click="addRow('landmark_list')" class="rounded-lg border border-dashed border-ink-300 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-ember-400 hover:text-ember-600 dark:border-ink-600 dark:text-ink-300">{{ __('editorial.add_row') }}</button>
</div>
```

Operating-hours repeater markup (same structural pattern, three fields per row, day select instead of a free-text name):

```blade
<div class="space-y-3">
    <p class="text-sm font-semibold text-ink-800 dark:text-ink-200">{{ __('editorial.operating_hours') }}</p>
    @foreach ($state['operating_hours'] as $i => $row)
        <div class="grid gap-3 rounded-xl border border-ink-100 p-3 sm:grid-cols-[1fr_1fr_1fr_auto] dark:border-ink-800" wire:key="hours-{{ $i }}">
            <x-form.field :label="__('editorial.day_of_week')" :error="$errors->first('state.operating_hours.'.$i.'.day_of_week')">
                <x-form.select wire:model="state.operating_hours.{{ $i }}.day_of_week" :options="$dayOfWeekOptions" :placeholder="__('editorial.select_placeholder')" />
            </x-form.field>
            <x-form.field :label="__('editorial.open_time')" :error="$errors->first('state.operating_hours.'.$i.'.open_time')">
                <x-form.input wire:model="state.operating_hours.{{ $i }}.open_time" type="time" />
            </x-form.field>
            <x-form.field :label="__('editorial.close_time')" :error="$errors->first('state.operating_hours.'.$i.'.close_time')">
                <x-form.input wire:model="state.operating_hours.{{ $i }}.close_time" type="time" />
            </x-form.field>
            <button type="button" wire:click="removeRow('operating_hours', {{ $i }})" class="self-end rounded-lg border border-ink-200 px-3 py-2 text-sm font-semibold text-ink-500 transition hover:border-ember-300 hover:text-ember-600 dark:border-ink-700 dark:text-ink-400">{{ __('editorial.remove') }}</button>
        </div>
    @endforeach
    <button type="button" wire:click="addRow('operating_hours')" class="rounded-lg border border-dashed border-ink-300 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-ember-400 hover:text-ember-600 dark:border-ink-600 dark:text-ink-300">{{ __('editorial.add_row') }}</button>
</div>
```

Lang additions:

```php
'tab_identity' => 'Identity',
'tab_classification' => 'Classification',
'tab_access' => 'Access & delivery',
'tab_location' => 'Location',
'tab_contact' => 'Contact',
'tab_hours' => 'Operating hours',
'tab_facilities' => 'Facilities',
'tab_amenities' => 'Amenities & accessibility',
'select_placeholder' => '— Select —',
'add_row' => 'Add row',
'remove' => 'Remove',
'landmarks' => 'Nearby landmarks',
'landmark_name' => 'Landmark name',
'walking_minutes' => 'Walking time (minutes)',
'operating_hours' => 'Regular operating hours',
'day_of_week' => 'Day',
'open_time' => 'Opening time',
'close_time' => 'Closing time',
'contact_channels' => 'Public business contact channels',
'treatment_areas' => 'Treatment areas',
```

plus one label key per remaining form field, named `est_<field_name>` (e.g. `'est_type_spa' => 'Spa type'`), copying the label text from `app/Filament/Editorial/Resources/Establishments/Schemas/EstablishmentForm.php` verbatim before it is deleted in Task 8.

Test `EstablishmentCrudTest.php`: mirror `QuoteCrudTest` (editor helper, setUp/tearDown clearing `Establishment`), with: list/search on English display name; create with only required fields (`state.display_name_eng`, `state.type_spa`, `state.status_establishment`) asserting the record persists with `display_name.eng` set; required-field errors; update changes `display_name_eng`; delete; repeater round-trip test:

```php
public function test_landmark_repeater_rows_persist(): void
{
    $user = $this->editor();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Workspace\Editorial\EstablishmentForm::class)
        ->set('state.display_name_eng', 'Calm Springs')
        ->set('state.type_spa', 'DAY')
        ->set('state.status_establishment', 'OPR')
        ->call('addRow', 'landmark_list')
        ->set('state.landmark_list.0.landmark_name', 'City Hall')
        ->set('state.landmark_list.0.walking_duration_minute', 5)
        ->call('save');

    $record = Establishment::query()->first();
    $this->assertSame('City Hall', $record->landmark_list[0]['landmark_name']);
}
```

(Replace `'DAY'`/`'OPR'` with real option codes from `TaxonomyOptions::for('type_spa')` / `for('status_establishment')` — look them up in `data/taxonomy/massage_nexus/establishment_classification.json` while writing the test; validation only requires non-empty strings, so any real code works.)

Add a second repeater round-trip test for the new field, mirroring the landmark one:

```php
public function test_new_establishment_defaults_to_seven_operating_hours_rows(): void
{
    $user = $this->editor();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Workspace\Editorial\EstablishmentForm::class)
        ->assertCount('state.operating_hours', 7)
        ->assertSet('state.operating_hours.0.day_of_week', 'Monday');
}

public function test_operating_hours_persist(): void
{
    $user = $this->editor();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Workspace\Editorial\EstablishmentForm::class)
        ->set('state.display_name_eng', 'Calm Springs')
        ->set('state.type_spa', 'DAY')
        ->set('state.status_establishment', 'OPR')
        ->set('state.operating_hours.0.open_time', '09:00')
        ->set('state.operating_hours.0.close_time', '18:00')
        ->call('save');

    $record = Establishment::query()->first();
    $this->assertSame('09:00', $record->operating_hours[0]['open_time']);
    $this->assertSame('18:00', $record->operating_hours[0]['close_time']);
}
```

(Same `'DAY'`/`'OPR'` placeholder note applies. `assertCount` on a Livewire property asserts the array count.)

- [ ] **Step 1: Write `EstablishmentCrudTest`, including the two operating-hours tests above. Run: expected FAIL.**
- [ ] **Step 2: Implement `EstablishmentIndex` (Task 4 pattern, search `display_name.eng`). Run index tests: PASS.**
- [ ] **Step 3: Implement `EstablishmentForm` component (code above, including the `operating_hours` repeater entry, `DAYS_OF_WEEK` constant, the 7-row default in `mount()`, and the validation rules).**
- [ ] **Step 4: Implement `establishment-form.blade.php` per the 8-tab mapping (including the split Location/Contact tabs and the new Operating Hours tab with its repeater markup). Add routes (`/establishment/new` → `establishment.create`, `/establishment/{establishment}/edit` → `establishment.edit`) and lang keys.**
- [ ] **Step 5: Run: `php artisan test --filter=EstablishmentCrudTest` — expected PASS. Full suite PASS.**
- [ ] **Step 6: Manual check: create an establishment through the browser exercising all eight tabs and all four repeaters (landmarks, contact channels, treatment areas, operating hours — confirming the 7-row default appears on a new record); verify dark mode rendering of tabs/repeaters/toggle groups.**
- [ ] **Step 7: Commit**

```bash
git add -A apps/web
git commit -m "feat: establishment CRUD in Livewire editorial workspace"
```

---

### Task 7: Moderation and system placeholder pages

**Files:**
- Create: `resources/views/workspace/admin-placeholder.blade.php`
- Modify: `routes/web.php`
- Modify: `lang/eng/workspace.php`
- Test: extend `tests/Feature/Editorial/EditorialAccessTest.php`

**Interfaces:**
- Consumes: `EnsureWorkspacePermission` (Task 1), `layouts.workspace` (Plan A).
- Produces: routes `workspace.moderation.home`, `workspace.system.home` at the URLs `config/workspace.php` already points to.

- [ ] **Step 1: Write the failing tests** (add to `EditorialAccessTest`):

```php
public function test_moderation_and_system_placeholders_are_permission_gated(): void
{
    $member = User::factory()->create();
    $founder = User::factory()->create();
    AccessAssignment::query()->create([
        'user_id' => (string) $founder->getKey(),
        'role_workspace' => 'FND',
        'scope_access' => 'GBL',
        'status_access_assignment' => 'ACT',
        'effective_at' => now()->subMinute(),
    ]);

    foreach (['moderation', 'system'] as $area) {
        $this->actingAs($member)->get("/workspace/{$area}")->assertForbidden();
        $this->actingAs($founder)->get("/workspace/{$area}")
            ->assertOk()
            ->assertSee(__('workspace.admin_placeholder_text'));
    }
}
```

- [ ] **Step 2: Run — expected FAIL (Filament panels still answer these URLs with their own dashboards).**

- [ ] **Step 3: Create the shared placeholder view** `resources/views/workspace/admin-placeholder.blade.php`:

```blade
@extends('layouts.workspace', ['navActive' => 'admin-'.$areaKey])

@section('title', $areaTitle)
@section('page-title', $areaTitle)

@section('content')
<div class="mx-auto max-w-3xl">
    <section class="rounded-2xl border border-dashed border-ink-200 bg-ink-50/50 p-8 text-center dark:border-ink-700 dark:bg-charcoal-950">
        <h2 class="text-xl font-black text-ink-950 dark:text-ink-50">{{ $areaTitle }}</h2>
        <p class="mx-auto mt-2 max-w-md text-sm text-ink-600 dark:text-ink-300">{{ __('workspace.admin_placeholder_text') }}</p>
        <a href="{{ route('workspace.home') }}" class="mt-5 inline-block rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('workspace.nav_home') }}</a>
    </section>
</div>
@endsection
```

- [ ] **Step 4: Add routes** (after the editorial group in `routes/web.php`):

```php
Route::view('/workspace/moderation', 'workspace.admin-placeholder', [
        'areaKey' => 'moderation',
        'areaTitle' => __('workspace.admin_moderation_title'),
    ])
    ->middleware(['auth', 'verified', EnsureActiveMember::class, EnsureWorkspacePermission::class.':workspace.moderation.access'])
    ->name('workspace.moderation.home');

Route::view('/workspace/system', 'workspace.admin-placeholder', [
        'areaKey' => 'system',
        'areaTitle' => __('workspace.admin_system_title'),
    ])
    ->middleware(['auth', 'verified', EnsureActiveMember::class, EnsureWorkspacePermission::class.':workspace.system.access'])
    ->name('workspace.system.home');
```

If `Route::view` + `__()` evaluation order is a problem (translations resolve at route registration), convert to closures returning `view(...)`. Also remove `ModerationPanelProvider::class` and `SystemPanelProvider::class` from `bootstrap/providers.php` now so these routes take effect.

- [ ] **Step 5: Add lang key** to `lang/eng/workspace.php`: `'admin_placeholder_text' => 'This area is being prepared. Its tools will appear here as they are built.',`

- [ ] **Step 6: Run — `php artisan test --filter=EditorialAccessTest` PASS; `--filter=WorkspaceShellTest` PASS (the guest-redirect and 403 panel tests now exercise the new routes).**

- [ ] **Step 7: Commit**

```bash
git add -A apps/web
git commit -m "feat: branded moderation and system placeholder pages"
```

---

### Task 8: Filament teardown

**Files:**
- Modify: `bootstrap/providers.php` (remove any remaining Filament providers)
- Delete: `app/Filament/` (entire tree), `app/Providers/Filament/` (all three providers), `app/Http/Middleware/AuthenticateWorkspacePanel.php`
- Modify: `composer.json` via `composer remove`
- Modify/Delete: `tests/Feature/AdminServiceResourceTest.php`
- Test: full suite

- [ ] **Step 1: Check `AdminServiceResourceTest` and any other Filament-coupled tests** (`Grep -l "Filament" tests/`). Rewrite `AdminServiceResourceTest`'s behavioral assertions (service create/edit/validation) as additions to `ServiceCrudTest` if any behavior isn't already covered; then delete the Filament-coupled test files.
- [ ] **Step 2: Remove remaining Filament provider registrations** from `bootstrap/providers.php` (any of the three not already removed in Tasks 1/7).
- [ ] **Step 3: Delete directories/files:** `app/Filament/`, `app/Providers/Filament/`, `app/Http/Middleware/AuthenticateWorkspacePanel.php`. Verify nothing else references them: `Grep "Filament" app/ config/ routes/ tests/` → only comments/none.
- [ ] **Step 4: `composer remove filament/filament`** — expected: success, `livewire/livewire` stays (direct dependency since Task 1).
- [ ] **Step 5: `php artisan config:clear && php artisan route:clear && php artisan view:clear`, then `php artisan route:list --path=workspace` — expected: only the Livewire/Blade workspace routes, no `filament.` routes.**
- [ ] **Step 6: Full suite `php artisan test` — expected PASS. `npm run build` — expected: success.**
- [ ] **Step 7: Commit**

```bash
git add -A apps/web
git commit -m "chore: remove Filament panels and dependency"
```

---

### Task 9: Documentation updates

**Files:**
- Modify: `docs/04-architecture/technology-stack.txt`
- Modify: `docs/11-workspaces/admin-workspace.txt`
- Modify: `docs/06-user-interface/admin-workspace-ui.txt`

- [ ] **Step 1: `technology-stack.txt`** — bump Document Version (+0.10) and Revision Date; add a version-history entry recording: Filament removed; editorial/moderation/system workspaces standardized on Blade + Livewire + Alpine inside the shared workspace shell; livewire/livewire installed as a direct dependency; site-wide light/dark/system theme switch added. Update section 3's "Private Workspace and Administration" entry from the Filament working choice to the Livewire workspace direction. Remove the "exact Filament use across operator, therapist, editorial, and administrator workspaces" line from section 14 (Unresolved Decisions).
- [ ] **Step 2: `admin-workspace.txt` and `admin-workspace-ui.txt`** — read both; wherever they describe Filament panels, update the wording to describe the in-shell Livewire editorial area and the moderation/system placeholders. Keep each document's own version-history convention (bump version, add dated entry).
- [ ] **Step 3: Cross-check** — `Grep -li "filament" docs/` and update any other doc that states Filament as the current implementation (planning-era mentions of it as a candidate may stay as history).
- [ ] **Step 4: Commit**

```bash
git add docs
git commit -m "docs: record Filament removal and Livewire workspace direction"
```

---

### Task 10: Final verification

- [ ] **Step 1: `php artisan test` — full suite PASS.**
- [ ] **Step 2: `npm run build` — clean.**
- [ ] **Step 3: Browser pass as a founder-role user: dashboard → editorial landing → each list → create/edit/delete one record per collection → moderation/system placeholders → theme toggle in both themes across all of it.**
- [ ] **Step 4: Browser pass as an ordinary member: no administration group in sidebar; `/workspace/editorial`, `/moderation`, `/system` all 403.**
