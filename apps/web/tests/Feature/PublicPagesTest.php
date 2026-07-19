<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_homepage_renders_core_sections(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(__('home.featured_spas'));
        $response->assertSee(__('home.featured_therapists'));
        $response->assertSee(__('home.browse_by_area'));
        $response->assertSee(__('home.browse_by_type'));
        $response->assertSee(__('home.latest_articles'));
        $response->assertSee(__('footer.compass_guide'));
    }

    public function test_spa_profile_renders_for_known_sample_slug(): void
    {
        $response = $this->get('/spa/the-resting-leaf');

        $response->assertStatus(200);
        $response->assertSee('The Resting Leaf');
        $response->assertSee(__('spa.popular_services'));
        $response->assertSee(__('spa.book_title'));
    }

    public function test_spa_profile_returns_not_found_for_unknown_slug(): void
    {
        $this->get('/spa/does-not-exist')->assertStatus(404);
    }

    public function test_planned_sections_render_coming_soon_pages(): void
    {
        foreach (['/directory', '/article', '/campus', '/promo'] as $path) {
            $response = $this->get($path);

            $response->assertStatus(200);
            $response->assertSee(__('common.coming_soon_badge'));
        }
    }

    public function test_login_page_renders(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee(__('auth.identifier_label'));
        $response->assertSee(__('auth.remember_me'));
    }

    public function test_register_page_renders(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee(__('auth.username_label'));
        $response->assertSee(__('auth.birth_date_label'));
        $response->assertSee(__('auth.username_hint'));
    }

    public function test_legal_pages_render(): void
    {
        $this->get('/legal/terms')->assertStatus(200)->assertSee(__('auth.terms_of_use'));
        $this->get('/legal/privacy')->assertStatus(200)->assertSee(__('auth.privacy_notice'));
        $this->get('/legal/cookies')->assertStatus(200)->assertSee(__('cookies.page_title'));
    }

    public function test_forgot_password_page_renders(): void
    {
        $this->get('/forgot-password')
            ->assertStatus(200)
            ->assertSee(__('auth.password_request_title'))
            ->assertSee(__('auth.password_request_button'));
    }
}
