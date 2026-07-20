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
