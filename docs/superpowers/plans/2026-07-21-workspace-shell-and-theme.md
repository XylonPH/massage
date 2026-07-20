# Workspace Shell & Site-Wide Theme Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Give every authenticated workspace page a branded app shell (persistent sidebar + page header) and add a light/dark/system theme switch to every page on the site.

**Architecture:** A new `layouts/workspace.blade.php` Blade layout wraps all workspace pages; existing pages migrate onto it. Dark mode uses Tailwind v4's class strategy (`.dark` on `<html>`), applied pre-paint by an inline head script and controlled by a vanilla-JS toggle (no Alpine on public pages — the site's JS is vanilla, in `resources/js/app.js`). Spec: `docs/superpowers/specs/2026-07-21-workspace-unification-design.md`.

**Tech Stack:** Laravel 13 Blade, Tailwind CSS v4 (CSS-first config), Vite, vanilla JS, PHPUnit.

## Global Constraints

- App root is `apps/web/` — all relative paths below are relative to it unless the path starts with `docs/`.
- All user-facing strings use translation keys (`__('...')`) in `lang/eng/*.php`. Never hard-code English in Blade.
- Palette and typography are unchanged: ink/ember/leaf/charcoal tokens defined in `resources/css/app.css` `@theme`.
- No data-model or schema changes.
- Route names and URLs are unchanged in this plan.
- localStorage key for theme: `mn-theme`; values `light` / `dark`; absence of the key = system.
- Tests: run with `php artisan test` from `apps/web/`. Test classes are PHPUnit-style (see `tests/Feature/Workspace/WorkspaceShellTest.php`).
- Dark-variant class mapping (used by every styling task; apply mechanically):
  | Light class | Add dark variant |
  |---|---|
  | `bg-white` (page surface/card) | `dark:bg-ink-900` |
  | `bg-white/95` (header) | `dark:bg-charcoal-900/95` |
  | `bg-slate-50`, `bg-ink-50/50` (canvas) | `dark:bg-charcoal-950` |
  | `bg-ink-50` (tint/hover) | `dark:bg-ink-800` (same for `hover:`) |
  | `bg-ember-50` | `dark:bg-ember-950` |
  | `bg-leaf-50` | `dark:bg-leaf-950` |
  | `text-charcoal-900`, `text-ink-950` | `dark:text-ink-50` |
  | `text-ink-900` | `dark:text-ink-100` |
  | `text-ink-800`, `text-ink-700` | `dark:text-ink-200` |
  | `text-ink-600` | `dark:text-ink-300` |
  | `text-ink-500`, `text-ink-400` | `dark:text-ink-400` |
  | `text-ember-600` | `dark:text-ember-400` |
  | `hover:text-ember-700` | `dark:hover:text-ember-300` |
  | `text-leaf-700` | `dark:text-leaf-300` |
  | `border-ink-100` | `dark:border-ink-800` |
  | `border-ink-200` | `dark:border-ink-700` |
  | `border-leaf-200` | `dark:border-leaf-800` |
  | already-dark surfaces (`bg-ink-950` footer, `bg-ink-800` chips in footer) | leave unchanged |
  - Elements not in the table: pick the nearest listed equivalent; never invent new palette steps.

---

### Task 1: Dark-mode foundation (CSS variant, pre-paint script, toggle component, JS)

**Files:**
- Modify: `resources/css/app.css`
- Create: `resources/views/partials/theme-init.blade.php`
- Create: `resources/views/components/theme-toggle.blade.php`
- Modify: `resources/js/app.js`
- Modify: `resources/views/layouts/app.blade.php`
- Modify: `resources/views/layouts/auth.blade.php`
- Modify: `lang/eng/common.php`
- Test: `tests/Feature/Theme/ThemeToggleTest.php`

**Interfaces:**
- Produces: `@include('partials.theme-init')` (goes in `<head>` of every layout), `<x-theme-toggle />` (self-contained button), localStorage key `mn-theme`, `.dark` class on `<html>`. Task 2's workspace layout and Task 5/6 styling depend on these.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature\Theme;

use Tests\TestCase;

class ThemeToggleTest extends TestCase
{
    public function test_public_page_contains_theme_init_script_and_toggle(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('mn-theme', false)
            ->assertSee('data-theme-toggle', false);
    }

    public function test_auth_layout_contains_theme_init_script_and_toggle(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('mn-theme', false)
            ->assertSee('data-theme-toggle', false);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=ThemeToggleTest`
Expected: FAIL — `mn-theme` not found in response.

- [ ] **Step 3: Add the dark variant and color-scheme to `resources/css/app.css`**

Immediately after the `@import 'tailwindcss';` line add:

```css
@custom-variant dark (&:where(.dark, .dark *));
```

And extend the existing `@layer base` block to:

```css
@layer base {
    html {
        scroll-behavior: smooth;
        color-scheme: light;
    }

    html.dark {
        color-scheme: dark;
    }
}
```

- [ ] **Step 4: Create `resources/views/partials/theme-init.blade.php`**

```blade
{{-- Applies the stored theme before first paint to avoid a flash of the wrong
     theme. Keep dependency-free and tiny: it blocks rendering. --}}
<script>
    (() => {
        const stored = localStorage.getItem('mn-theme');
        if (stored === 'dark' || (stored !== 'light' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    })();
</script>
```

- [ ] **Step 5: Create `resources/views/components/theme-toggle.blade.php`**

```blade
{{-- Three-state theme switch: light → dark → system. State lives in
     localStorage ('mn-theme'); behavior is wired in resources/js/app.js. --}}
<button type="button" data-theme-toggle data-theme-state="system"
        data-label-light="{{ __('common.theme_light') }}"
        data-label-dark="{{ __('common.theme_dark') }}"
        data-label-system="{{ __('common.theme_system') }}"
        aria-label="{{ __('common.theme_system') }}"
        {{ $attributes->merge(['class' => 'inline-flex size-10 items-center justify-center rounded-full border border-ink-200 text-ink-600 transition hover:border-ember-300 hover:bg-ember-50 hover:text-ember-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ember-500 dark:border-ink-700 dark:text-ink-300 dark:hover:bg-ember-950 dark:hover:text-ember-400']) }}>
    <svg data-theme-icon="light" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path stroke-linecap="round" d="M12 2v2m0 16v2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M2 12h2m16 0h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
    <svg data-theme-icon="dark" hidden viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.8A8.5 8.5 0 1 1 11.2 3a6.6 6.6 0 0 0 9.8 9.8Z"/></svg>
    <svg data-theme-icon="system" hidden viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true"><rect x="3" y="4" width="18" height="13" rx="2"/><path stroke-linecap="round" d="M8 21h8m-4-4v4"/></svg>
</button>
```

- [ ] **Step 6: Add toggle behavior to `resources/js/app.js`**

Append at the end of the file (matches the file's existing vanilla-JS style):

```js
// Theme switch: light → dark → system. The inline head script in
// partials/theme-init.blade.php already applied the stored theme pre-paint;
// this block keeps the <html> class, button icons, and labels in sync.
const THEME_KEY = 'mn-theme';
const themeMedia = window.matchMedia('(prefers-color-scheme: dark)');

function currentThemeMode() {
    const stored = localStorage.getItem(THEME_KEY);
    return stored === 'light' || stored === 'dark' ? stored : 'system';
}

function applyTheme() {
    const mode = currentThemeMode();
    const dark = mode === 'dark' || (mode === 'system' && themeMedia.matches);
    document.documentElement.classList.toggle('dark', dark);
    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.dataset.themeState = mode;
        const label = button.dataset[`label${mode.charAt(0).toUpperCase()}${mode.slice(1)}`];
        if (label) button.setAttribute('aria-label', label);
        button.querySelectorAll('[data-theme-icon]').forEach((icon) => {
            icon.toggleAttribute('hidden', icon.dataset.themeIcon !== mode);
        });
    });
}

document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const order = ['light', 'dark', 'system'];
        const next = order[(order.indexOf(currentThemeMode()) + 1) % order.length];
        if (next === 'system') {
            localStorage.removeItem(THEME_KEY);
        } else {
            localStorage.setItem(THEME_KEY, next);
        }
        applyTheme();
    });
});

themeMedia.addEventListener('change', () => {
    if (currentThemeMode() === 'system') applyTheme();
});

applyTheme();
```

- [ ] **Step 7: Add translation keys to `lang/eng/common.php`**

Add to the returned array (keep alphabetical position consistent with the file's existing style):

```php
'theme_light' => 'Theme: light — switch to dark',
'theme_dark' => 'Theme: dark — switch to system',
'theme_system' => 'Theme: follows your device — switch to light',
```

- [ ] **Step 8: Wire into `layouts/app.blade.php` and `layouts/auth.blade.php`**

In both files:
1. In `<head>`, directly before the `@vite([...])` line, add: `@include('partials.theme-init')`
2. `layouts/app.blade.php`: add `<x-theme-toggle />` as the first element inside the desktop actions div (`<div class="ml-auto hidden items-center gap-2.5 xl:flex">`), and inside the mobile menu add `<x-theme-toggle class="size-11" />` as a sibling before the auth block's forms (inside `<nav>` at the end, wrapped: `<div class="pt-2"><x-theme-toggle class="size-11" /></div>`).
3. `layouts/auth.blade.php`: add `<x-theme-toggle class="absolute right-4 top-4 z-10" />` as the first element inside `<body>` (adjust position class if the layout has a header — place it visually top-right without overlapping existing controls).

- [ ] **Step 9: Run test to verify it passes**

Run: `php artisan test --filter=ThemeToggleTest`
Expected: PASS (2 tests).

- [ ] **Step 10: Build assets and run the full suite**

Run: `npm run build` — expected: build succeeds, no unknown-class warnings.
Run: `php artisan test` — expected: all tests pass.

- [ ] **Step 11: Commit**

```bash
git add -A apps/web
git commit -m "feat: add light/dark/system theme switch foundation"
```

---

### Task 2: Workspace shell layout and upgraded sidebar

**Files:**
- Create: `resources/views/layouts/workspace.blade.php`
- Modify: `resources/views/components/workspace-nav.blade.php`
- Modify: `lang/eng/workspace.php`
- Test: `tests/Feature/Workspace/WorkspaceLayoutTest.php`

**Interfaces:**
- Consumes: `partials.theme-init`, `<x-theme-toggle />` (Task 1).
- Produces: layout `layouts.workspace` with sections `title`, `page-title`, `page-context`, `page-actions`, `content`, and a `$navActive` variable convention (`@extends('layouts.workspace', ['navActive' => 'home'])` — passed through to `<x-workspace-nav :active="$navActive" />`). Tasks 3–4 and every page in Plan B render inside it.

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature\Workspace;

use App\Models\User;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class WorkspaceLayoutTest extends TestCase
{
    use InteractsWithMongoUsers;

    public function test_workspace_home_renders_the_workspace_shell(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/workspace/home')
            ->assertOk()
            ->assertSee('data-workspace-shell', false)
            ->assertSee('data-theme-toggle', false)
            ->assertSee('id="workspace-sidebar"', false);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=WorkspaceLayoutTest`
Expected: FAIL — `data-workspace-shell` not found.

- [ ] **Step 3: Create `resources/views/layouts/workspace.blade.php`**

```blade
<!DOCTYPE html>
<html lang="{{ config('localization.bcp47.'.app()->getLocale(), str_replace('_', '-', app()->getLocale())) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@hasSection('title')@yield('title') · {{ config('app.name') }}@else{{ config('app.name') }}@endif</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : 0 }}" sizes="any">
    @include('partials.theme-init')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body data-workspace-shell class="min-h-screen bg-slate-50 font-sans text-charcoal-900 antialiased dark:bg-charcoal-950 dark:text-ink-50">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-ink-950 focus:px-4 focus:py-2 focus:text-white">
        {{ __('common.skip_to_content') }}
    </a>

    <div class="mx-auto flex min-h-screen max-w-[1600px]">
        {{-- ============ Sidebar ============ --}}
        <aside id="workspace-sidebar"
               class="fixed inset-y-0 left-0 z-40 hidden w-[17rem] shrink-0 flex-col border-r border-ink-100 bg-white lg:static lg:flex dark:border-ink-800 dark:bg-ink-900">
            <div class="flex h-[4.5rem] items-center border-b border-ink-100 px-5 dark:border-ink-800">
                <a href="{{ route('home') }}" aria-label="{{ config('app.name') }}">
                    <x-logo size="h-10" />
                </a>
            </div>

            <div class="border-b border-ink-100 p-4 dark:border-ink-800">
                <x-identity-capsule :user="auth()->user()" class="w-full" />
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto p-4">
                <x-workspace-nav :active="$navActive ?? 'home'" />
            </div>

            <div class="flex items-center justify-between gap-2 border-t border-ink-100 p-4 dark:border-ink-800">
                <x-theme-toggle />
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex size-10 items-center justify-center rounded-full border border-ink-200 text-ink-600 transition hover:border-ember-300 hover:bg-ember-50 hover:text-ember-700 dark:border-ink-700 dark:text-ink-300 dark:hover:bg-ember-950 dark:hover:text-ember-400" aria-label="{{ __('auth.log_out') }}" title="{{ __('auth.log_out') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2v-3M10 12h11m0 0-3-3m3 3-3 3"/></svg>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ============ Main column ============ --}}
        <div class="min-w-0 flex-1">
            <header class="sticky top-0 z-30 border-b border-ink-100 bg-white/95 backdrop-blur dark:border-ink-800 dark:bg-charcoal-900/95">
                <div class="flex h-[4.5rem] items-center gap-4 px-4 sm:px-6 lg:px-8">
                    <button type="button" data-menu-toggle aria-expanded="false" aria-controls="workspace-sidebar"
                            class="inline-flex items-center justify-center rounded-lg p-2 text-ink-800 hover:bg-ink-50 lg:hidden dark:text-ink-200 dark:hover:bg-ink-800">
                        <span class="sr-only">{{ __('navigation.open_menu') }}</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-6" aria-hidden="true"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                    </button>
                    <div class="min-w-0 flex-1">
                        <h1 class="truncate text-xl font-black text-ink-950 dark:text-ink-50">@yield('page-title')</h1>
                        @hasSection('page-context')
                            <p class="truncate text-sm text-ink-600 dark:text-ink-300">@yield('page-context')</p>
                        @endif
                    </div>
                    <div class="flex shrink-0 items-center gap-2.5">
                        @yield('page-actions')
                        <a href="{{ route('home') }}" class="hidden rounded-lg px-3 py-2 text-sm font-semibold text-ink-700 transition hover:bg-ink-50 hover:text-ink-950 sm:block dark:text-ink-200 dark:hover:bg-ink-800 dark:hover:text-ink-50">
                            {{ __('workspace.back_to_site') }}
                        </a>
                    </div>
                </div>
            </header>

            <main id="main-content" class="px-4 py-8 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
```

Note: the sidebar reuses the existing `[data-menu-toggle]` handler in `app.js` — it toggles the `hidden` attribute of the element named in `aria-controls`. The `hidden` attribute combined with `lg:flex`/`lg:static` classes means: mobile = overlay drawer toggled by the button; desktop = always visible. Because `hidden` wins over `display` classes only when no `lg:flex` applies, verify on desktop width the sidebar shows regardless of toggle state.

- [ ] **Step 4: Upgrade `resources/views/components/workspace-nav.blade.php`**

Replace the file's `<nav>` block (keep the `@php` group-building block exactly as is, but add an `icon` key to each item) with:

```blade
@props(['active' => 'home'])
@inject('workspaceAccess', 'App\Support\Workspace\WorkspaceAccess')

@php
    $icons = [
        'home' => 'M3 10.5 12 3l9 7.5M5 9.5V21h5v-6h4v6h5V9.5',
        'profile' => 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 8a7 7 0 0 1 14 0',
        'settings' => 'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm7.4-3a7.4 7.4 0 0 0-.1-1l2-1.6-2-3.4-2.4 1a7.4 7.4 0 0 0-1.7-1l-.4-2.6h-4l-.4 2.6a7.4 7.4 0 0 0-1.7 1l-2.4-1-2 3.4 2 1.6a7.4 7.4 0 0 0 0 2l-2 1.6 2 3.4 2.4-1a7.4 7.4 0 0 0 1.7 1l.4 2.6h4l.4-2.6a7.4 7.4 0 0 0 1.7-1l2.4 1 2-3.4-2-1.6c.1-.3.1-.7.1-1Z',
        'reviews' => 'M12 3l2.5 5.3 5.5.7-4 4 1 5.7-5-2.8-5 2.8 1-5.7-4-4 5.5-.7L12 3Z',
        'articles' => 'M6 3h9l4 4v14H6V3Zm9 0v4h4M9 11h7M9 15h7M9 7h3',
        'contributions' => 'M12 5v14m-7-7h14',
        'listing-spa' => 'M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6',
        'listing-therapist' => 'M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM4 21a8 8 0 0 1 16 0',
        'admin-editorial' => 'M4 20l4-1L20 7l-3-3L5 16l-1 4Zm11-14 3 3',
        'admin-moderation' => 'M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4Z',
        'admin-system' => 'M4 6h16M4 12h16M4 18h16M8 6v0m0 6v0m0 6v0',
    ];

    $groups = [
        ['heading' => __('workspace.nav_personal'), 'items' => [
            ['key' => 'home', 'label' => __('workspace.nav_home'), 'route' => 'workspace.home'],
            ['key' => 'profile', 'label' => __('workspace.nav_profile'), 'route' => 'workspace.profile.edit'],
            ['key' => 'settings', 'label' => __('workspace.nav_settings'), 'route' => 'workspace.setting.edit'],
        ]],
        ['heading' => __('workspace.nav_activity'), 'items' => [
            ['key' => 'reviews', 'label' => __('workspace.nav_reviews'), 'route' => 'workspace.review.index'],
            ['key' => 'articles', 'label' => __('workspace.nav_articles'), 'route' => 'workspace.article.index'],
            ['key' => 'contributions', 'label' => __('workspace.nav_contributions'), 'route' => 'workspace.contribution.index'],
        ]],
        ['heading' => __('workspace.nav_managed'), 'items' => [
            ['key' => 'listing-spa', 'label' => __('workspace.nav_listing_spa'), 'route' => 'workspace.listing.spa'],
            ['key' => 'listing-therapist', 'label' => __('workspace.nav_listing_therapist'), 'route' => 'workspace.listing.therapist'],
        ]],
    ];

    $administrativeItems = collect($workspaceAccess->administrativeAreas(auth()->user()))
        ->map(fn (array $area) => [
            'key' => 'admin-'.$area['key'],
            'label' => $area['title'],
            'url' => $area['url'],
        ])
        ->all();

    if ($administrativeItems !== []) {
        $groups[] = ['heading' => __('workspace.nav_administration'), 'items' => $administrativeItems];
    }
@endphp

<nav aria-label="{{ __('workspace.title') }}" {{ $attributes }}>
    @foreach ($groups as $group)
        <h2 class="{{ $loop->first ? '' : 'mt-5' }} px-3 text-xs font-bold uppercase tracking-[0.14em] text-ink-400 dark:text-ink-400">{{ $group['heading'] }}</h2>
        <ul class="mt-1.5 space-y-0.5">
            @foreach ($group['items'] as $item)
                <li>
                    <a href="{{ isset($item['route']) ? route($item['route']) : url($item['url']) }}"
                       @if ($active === $item['key']) aria-current="page" @endif
                       class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-semibold transition {{ $active === $item['key'] ? 'bg-ink-950 text-white dark:bg-ember-500 dark:text-white' : 'text-ink-700 hover:bg-ink-50 hover:text-ember-600 dark:text-ink-200 dark:hover:bg-ink-800 dark:hover:text-ember-400' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4.5 shrink-0" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$item['key']] ?? $icons['home'] }}"/></svg>
                        <span class="truncate">{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    @endforeach
</nav>
```

(The outer card styling — `rounded-2xl border ... bg-white p-4 shadow-sm` — is removed: the layout's sidebar is now the surface.)

- [ ] **Step 5: Add translation key to `lang/eng/workspace.php`**

```php
'back_to_site' => 'Back to site',
```

- [ ] **Step 6: Point one page at the new layout so the test can pass**

In `resources/views/workspace/home.blade.php` change line 1 from `@extends('layouts.app')` to `@extends('layouts.workspace', ['navActive' => 'home'])`, and delete the wrapper markup added by the old pattern: remove the outer `<div class="mx-auto max-w-7xl ...">`, the `grid` div, and the `<aside>` containing `<x-workspace-nav>` (the layout now provides the sidebar). Keep everything currently inside `<div class="min-w-0">` as the page content. Add after the `@section('title')` line:

```blade
@section('page-title', __('workspace.greeting', ['name' => $user->publicName()]))
@section('page-context', __('workspace.workspace_note'))
```

and remove the now-duplicated `<h1>` greeting and the `workspace_note` paragraph from the content. (Task 3 rebuilds this page's content properly; this step only needs it rendering inside the new shell.)

- [ ] **Step 7: Run tests**

Run: `php artisan test --filter=WorkspaceLayoutTest`
Expected: PASS.
Run: `php artisan test --filter=WorkspaceShellTest`
Expected: PASS — if `test_active_member_sees_adaptive_dashboard_without_administrative_areas` fails on the greeting/nav assertions, the strings must still be present via the header (`page-title`) and sidebar; fix the page, not the test. All listed strings (`workspace.greeting`, `card_account_title`, `card_claim_title`, `nav_listing_spa`, `nav_listing_therapist`, `nav_contributions`) must still render.

- [ ] **Step 8: Commit**

```bash
git add -A apps/web
git commit -m "feat: add branded workspace shell layout with sidebar and header"
```

---

### Task 3: Workspace home dashboard redesign

**Files:**
- Modify: `resources/views/workspace/home.blade.php`
- Modify: `app/Http/Controllers/Workspace/WorkspaceHomeController.php` (path per existing controller — locate with `Grep "class WorkspaceHomeController"`; it already passes `$user`, `$reviewCount`, `$articleCount`, `$administrativeAreas`)
- Modify: `lang/eng/workspace.php`
- Test: `tests/Feature/Workspace/WorkspaceShellTest.php` (existing assertions keep passing; extend)

**Interfaces:**
- Consumes: `layouts.workspace` sections (Task 2).
- Produces: nothing new for later tasks; `$contributionCount` added to the controller's view data.

- [ ] **Step 1: Extend the existing test**

Add to `tests/Feature/Workspace/WorkspaceShellTest.php`:

```php
public function test_dashboard_shows_activity_stat_row(): void
{
    $user = User::factory()->create();

    $this->actingAs($user)->get('/workspace/home')
        ->assertOk()
        ->assertSee(__('workspace.stat_reviews'))
        ->assertSee(__('workspace.stat_articles'))
        ->assertSee(__('workspace.stat_contributions'));
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=test_dashboard_shows_activity_stat_row`
Expected: FAIL — translation key `workspace.stat_reviews` rendered raw or missing.

- [ ] **Step 3: Add translation keys to `lang/eng/workspace.php`**

```php
'stat_reviews' => 'Reviews written',
'stat_articles' => 'Articles',
'stat_contributions' => 'Contributions',
'quick_actions_title' => 'Quick actions',
```

- [ ] **Step 4: Add `$contributionCount` to `WorkspaceHomeController::index`**

Mirror how `$reviewCount`/`$articleCount` are computed in that controller (same query style against the user's records) using the `Contribution` model (`App\Models\Contribution`), e.g. `Contribution::query()->where('created_by_user_id', (string) $user->getKey())->count()` — match the field name the model actually uses (check `$fillable`; if contributions are linked by another field, use that). Pass it to the view.

- [ ] **Step 5: Rewrite the content section of `resources/views/workspace/home.blade.php`**

```blade
@extends('layouts.workspace', ['navActive' => 'home'])

@section('title', __('workspace.home_title'))
@section('page-title', __('workspace.greeting', ['name' => $user->publicName()]))
@section('page-context', __('workspace.workspace_note'))

@section('content')
<div class="mx-auto max-w-5xl">
    {{-- Stat row --}}
    <div class="grid gap-4 sm:grid-cols-3">
        @foreach ([
            ['label' => __('workspace.stat_reviews'), 'count' => $reviewCount, 'route' => route('workspace.review.index')],
            ['label' => __('workspace.stat_articles'), 'count' => $articleCount, 'route' => route('workspace.article.index')],
            ['label' => __('workspace.stat_contributions'), 'count' => $contributionCount, 'route' => route('workspace.contribution.index')],
        ] as $stat)
            <a href="{{ $stat['route'] }}" class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm transition hover:border-ember-200 hover:shadow-md dark:border-ink-800 dark:bg-ink-900 dark:hover:border-ember-800">
                <p class="text-3xl font-black text-ink-950 dark:text-ink-50">{{ $stat['count'] }}</p>
                <p class="mt-1 text-sm font-semibold text-ink-600 dark:text-ink-300">{{ $stat['label'] }}</p>
            </a>
        @endforeach
    </div>

    {{-- Account + claim cards --}}
    <div class="mt-6 grid gap-5 sm:grid-cols-2">
        <section aria-labelledby="ws-account" class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">
            <h2 id="ws-account" class="font-black text-ink-950 dark:text-ink-50">{{ __('workspace.card_account_title') }}</h2>
            <p class="mt-2 text-sm text-ink-700 dark:text-ink-200">{{ '@'.$user->username }}</p>
            <p class="mt-1 text-sm text-ink-500 dark:text-ink-400">{{ $user->email }}</p>
            <p class="mt-1 text-xs text-ink-400 dark:text-ink-400">{{ __('workspace.card_account_member_since', ['date' => $user->created_at?->format('M j, Y')]) }}</p>
            <a href="{{ route('workspace.profile.edit') }}" class="mt-4 inline-block text-sm font-bold text-ember-600 transition hover:text-ember-700 dark:text-ember-400 dark:hover:text-ember-300">{{ __('workspace.nav_profile') }} →</a>
        </section>

        <section aria-labelledby="ws-claim" class="rounded-2xl border border-leaf-200 bg-leaf-50 p-5 shadow-sm dark:border-leaf-800 dark:bg-leaf-950">
            <h2 id="ws-claim" class="font-black text-ink-950 dark:text-ink-50">{{ __('workspace.card_claim_title') }}</h2>
            <p class="mt-2 text-sm text-ink-700 dark:text-ink-200">{{ __('workspace.card_claim_text') }}</p>
            <a href="{{ route('workspace.contribution.establishment.create') }}" class="mt-4 inline-block text-sm font-bold text-leaf-700 transition hover:text-leaf-800 dark:text-leaf-300 dark:hover:text-leaf-200">{{ __('workspace.card_claim_action') }} &rarr;</a>
        </section>
    </div>

    {{-- Quick actions --}}
    <section aria-labelledby="ws-quick" class="mt-6 rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">
        <h2 id="ws-quick" class="font-black text-ink-950 dark:text-ink-50">{{ __('workspace.quick_actions_title') }}</h2>
        <div class="mt-3 flex flex-wrap gap-2.5">
            <a href="{{ route('workspace.article.create') }}" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('article.new_article') }}</a>
            <a href="{{ route('workspace.review.index') }}" class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('workspace.card_reviews_title') }}</a>
            <a href="{{ route('workspace.contribution.establishment.create') }}" class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('workspace.card_claim_action') }}</a>
        </div>
    </section>

    {{-- Administration (permission-gated) --}}
    @if ($administrativeAreas !== [])
        <section aria-labelledby="ws-administration" class="mt-8">
            <h2 id="ws-administration" class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ __('workspace.administration_title') }}</h2>
            <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">{{ __('workspace.administration_intro') }}</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($administrativeAreas as $area)
                    <a href="{{ url($area['url']) }}" class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm transition hover:border-ember-200 hover:shadow-md dark:border-ink-800 dark:bg-ink-900 dark:hover:border-ember-800">
                        <h3 class="font-black text-ink-950 dark:text-ink-50">{{ $area['title'] }}</h3>
                        <p class="mt-2 text-sm text-ink-600 dark:text-ink-300">{{ $area['description'] }}</p>
                        <span class="mt-4 inline-block text-sm font-bold text-ember-600 dark:text-ember-400">{{ __('workspace.open') }} &rarr;</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section aria-labelledby="ws-coming" class="mt-6 rounded-2xl border border-dashed border-ink-200 bg-ink-50/50 p-5 dark:border-ink-700 dark:bg-charcoal-950">
        <h2 id="ws-coming" class="font-bold text-ink-700 dark:text-ink-200">{{ __('workspace.coming_soon_title') }}</h2>
        <p class="mt-1.5 text-sm text-ink-500 dark:text-ink-400">{{ __('workspace.coming_soon_text') }}</p>
    </section>
</div>
@endsection
```

Note: `workspace.card_reviews_title` and `workspace.card_articles_title` remain in the lang file (still used by tests/quick actions); do not delete existing keys.

- [ ] **Step 6: Run tests**

Run: `php artisan test --filter=WorkspaceShellTest`
Expected: PASS, including the new stat-row test and all pre-existing assertions.

- [ ] **Step 7: Commit**

```bash
git add -A apps/web
git commit -m "feat: redesign workspace home as dashboard with stat row"
```

---

### Task 4: Migrate the remaining workspace pages onto the shell

**Files (all Modify):**
- `resources/views/workspace/profile.blade.php` — navActive `profile`
- `resources/views/workspace/setting.blade.php` — navActive `settings`
- `resources/views/workspace/listing/spa.blade.php` — navActive `listing-spa`
- `resources/views/workspace/listing/therapist.blade.php` — navActive `listing-therapist`
- `resources/views/workspace/contribution/index.blade.php` — navActive `contributions`
- `resources/views/workspace/contribution/establishment.blade.php` — navActive `contributions`
- `resources/views/workspace/contribution/practitioner.blade.php` — navActive `contributions`
- `resources/views/workspace/article/index.blade.php` — navActive `articles`
- `resources/views/workspace/article/editor.blade.php` — navActive `articles`
- `resources/views/workspace/article/revisions.blade.php` — navActive `articles`
- `resources/views/workspace/review/index.blade.php` — navActive `reviews`
- `resources/views/workspace/review/form.blade.php` — navActive `reviews`

**Interfaces:**
- Consumes: `layouts.workspace` sections (Task 2).

Apply this exact transformation to each file (this is the complete recipe — the only per-file variables are the navActive key above and which existing heading becomes `page-title`):

1. Line 1: `@extends('layouts.app')` → `@extends('layouts.workspace', ['navActive' => '<key from list>'])`.
2. Remove the page's own outer container that exists only to make room for the old sidebar: the `<div class="mx-auto max-w-7xl ...">` wrapper, the `grid lg:grid-cols-[16rem_...]` div, and the `<aside>` containing `<x-workspace-nav ... />`. Keep the former main-column contents (`<div class="min-w-0">...</div>` interior) as the direct `@section('content')` body, wrapped in `<div class="mx-auto max-w-5xl">…</div>`.
3. The page's existing `<h1>` (or top heading) moves to `@section('page-title', ...)` with the same string/key; a subtitle paragraph directly under it (if any) moves to `@section('page-context', ...)`. Delete them from the body.
4. If the page has one clear primary action link/button at the top (e.g. "New article" on `article/index`), move it into `@section('page-actions') ... @endsection` unchanged.
5. Apply the Global Constraints dark-variant mapping to every class list in the file.

- [ ] **Step 1: Migrate `profile.blade.php` and `setting.blade.php`; run `php artisan test --filter=WorkspaceShellTest` — expected PASS (profile update + settings toggle tests hit these views).**
- [ ] **Step 2: Migrate both `listing/*.blade.php`; run `php artisan test --filter=WorkspaceShellTest` — expected PASS (empty-state and scoped-listing tests).**
- [ ] **Step 3: Migrate the three `contribution/*.blade.php`; run `php artisan test --filter=Contribution` — expected PASS (if no Contribution feature tests exist, load each page logged-in via `php artisan test --filter=WorkspaceShellTest` and manual check).**
- [ ] **Step 4: Migrate the three `article/*.blade.php`; run `php artisan test --filter=ArticleWorkspaceTest` — expected PASS. The editor page keeps its Tiptap mount markup untouched — only the wrapper/extends change.**
- [ ] **Step 5: Migrate both `review/*.blade.php`; run `php artisan test --filter=Review` — expected PASS.**
- [ ] **Step 6: Run the full suite: `php artisan test` — expected PASS.**
- [ ] **Step 7: Commit**

```bash
git add -A apps/web
git commit -m "feat: move all workspace pages onto the workspace shell layout"
```

---

### Task 5: Dark-variant pass — shared layouts and components

**Files (all Modify):**
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/auth.blade.php`
- `resources/views/components/cookie-banner.blade.php`
- `resources/views/components/section-heading.blade.php`
- `resources/views/components/rating.blade.php`
- `resources/views/components/pictogram.blade.php`
- `resources/views/components/identity-capsule.blade.php`
- `resources/views/components/widgets/sidebar-container.blade.php`
- `resources/views/components/widgets/*.blade.php` (all seven widget files)

Apply the Global Constraints dark-variant mapping to every class list in each file. Layout-specific notes:

- `layouts/app.blade.php` body: `bg-slate-50 ... text-charcoal-900` → append `dark:bg-charcoal-950 dark:text-ink-50`. Header: `bg-white/95` → append `dark:bg-charcoal-900/95`, `border-ink-100` → append `dark:border-ink-800`. Mobile menu `bg-white` → `dark:bg-charcoal-900`. The footer is already dark (`bg-ink-950`) — leave every footer class untouched.
- `identity-capsule.blade.php`: the `mn-identity-capsule` gradient works on both themes — only adjust plain text/border/surface classes inside it per the mapping.
- Widgets: mechanical application of the mapping; no structural edits.

- [ ] **Step 1: Apply mapping to both layouts. Verify: `npm run build` succeeds.**
- [ ] **Step 2: Apply mapping to all components/widgets. Verify: `npm run build` succeeds.**
- [ ] **Step 3: Run `php artisan test` — expected PASS (class changes must not break any assertion).**
- [ ] **Step 4: Visual smoke check (dev server): load `/` , toggle theme through light → dark → system, confirm header/menu/cards/footers all readable in dark; no white flash on reload in dark mode.**
- [ ] **Step 5: Commit**

```bash
git add -A apps/web
git commit -m "style: dark theme variants for shared layouts and components"
```

---

### Task 6: Dark-variant pass — public pages

**Files (all Modify):**
- `resources/views/home.blade.php`
- `resources/views/coming-soon.blade.php`
- `resources/views/errors/404.blade.php`
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/forgot-password.blade.php`
- `resources/views/auth/reset-password.blade.php`
- `resources/views/auth/verify-email.blade.php`
- `resources/views/legal/draft.blade.php`
- `resources/views/legal/cookies.blade.php`
- `resources/views/article/index.blade.php`
- `resources/views/article/show.blade.php`
- `resources/views/review/index.blade.php`
- `resources/views/review/show.blade.php`
- `resources/views/spa/profile.blade.php`
- `resources/views/service/profile.blade.php`
- `resources/views/therapist/profile.blade.php`

Apply the Global Constraints dark-variant mapping to every class list in each file. Notes:

- `home.blade.php`: the hero section sits on a photo/dark background with white text — leave hero-internal classes unchanged; apply the mapping only to sections on light surfaces below the hero.
- Auth pages: form inputs commonly use `border-ink-200 bg-white` — append `dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50 dark:placeholder:text-ink-400` to input/textarea/select class lists.
- Any inline `bg-white` badge/chip on imagery: use judgment — if it must stay readable on a photo, leave it.

- [ ] **Step 1: Apply mapping to home, coming-soon, errors/404. Verify `npm run build`.**
- [ ] **Step 2: Apply mapping to the five auth views. Verify `npm run build`.**
- [ ] **Step 3: Apply mapping to legal, article, review views. Verify `npm run build`.**
- [ ] **Step 4: Apply mapping to spa/service/therapist profiles. Verify `npm run build`.**
- [ ] **Step 5: Run `php artisan test` — expected PASS.**
- [ ] **Step 6: Visual smoke check in dark mode: `/`, `/login`, `/register`, `/article`, one spa profile, `/workspace/home`. Confirm readable text, visible borders, no pure-white islands.**
- [ ] **Step 7: Commit**

```bash
git add -A apps/web
git commit -m "style: dark theme variants for public pages"
```

---

### Task 7: Final verification

- [ ] **Step 1: `npm run build` — expected: clean build.**
- [ ] **Step 2: `php artisan test` — expected: full suite PASS.**
- [ ] **Step 3: Manual pass on desktop + mobile widths: sidebar drawer opens/closes on mobile, persists on desktop; theme toggle cycles and survives reload; workspace pages all show sidebar + header.**
- [ ] **Step 4: Commit any straggler fixes; do not start Plan B until the suite is green.**
