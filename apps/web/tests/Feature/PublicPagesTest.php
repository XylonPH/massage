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
        $response->assertSee('/directory/area', false);
        $response->assertSee('/directory/type-spa', false);
        $response->assertDontSee('/browse/', false);
    }

    public function test_spa_profile_renders_for_known_sample_slug(): void
    {
        $this->assertSame(
            url('/spa/the-resting-leaf'),
            route('spa.show', ['establishment_slug' => 'the-resting-leaf']),
        );

        $response = $this->get('/spa/the-resting-leaf');

        $response->assertStatus(200);
        $response->assertSee('The Resting Leaf');
        $response->assertSee(__('spa.popular_services'));
        $response->assertSee(__('spa.book_title'));
    }

    public function test_spa_profile_renders_location_directions_parking_and_public_contact_channels(): void
    {
        $response = $this->get('/spa/the-resting-leaf');

        $response->assertStatus(200);
        $response->assertSee('Unit 3A, 2F, Luna Bldg.');
        $response->assertSee('Use the Pineda Street entrance');
        $response->assertSee('Paid basement parking');
        $response->assertSee('SM Megamall');
        $response->assertSee('query=14.5764%2C121.0482', false);
        $response->assertSee('href="tel:+63285550148"', false);
        $response->assertSee('Viber');
        $response->assertSee('Official website');
        $response->assertSee('Facebook');
        $response->assertSee('Instagram');
    }

    public function test_spa_profile_returns_not_found_for_unknown_slug(): void
    {
        $this->get('/spa/does-not-exist')->assertStatus(404);
    }

    public function test_therapist_profile_renders_for_claimed_sample_slug(): void
    {
        $this->assertSame(
            url('/therapist/maya-santos'),
            route('therapist.show', ['therapist_slug' => 'maya-santos']),
        );

        $response = $this->get('/therapist/maya-santos');

        $response->assertStatus(200);
        $response->assertSee('Maya Santos');
        $response->assertSee(__('therapist.claimed_profile'));
        $response->assertSee(__('therapist.booking_enabled'));
        $response->assertSee(__('therapist.book_title', ['name' => 'Maya Santos']));
        $response->assertSee(__('therapist.affiliations_title'));
        // Ratings and reviews come from the shared review domain (empty in the
        // test database), so the profile shows the below-threshold state while
        // still keeping rating and review facts separate.
        $response->assertSee(__('therapist.not_enough_ratings'));
        $response->assertSee(__('therapist.rating_summary'));
    }

    public function test_unclaimed_therapist_profile_shows_no_booking_panel(): void
    {
        $response = $this->get('/therapist/dennis-aquino');

        $response->assertStatus(200);
        $response->assertSee('Dennis Aquino');
        $response->assertSee(__('therapist.unclaimed_profile'));
        $response->assertSee(__('therapist.unclaimed_notice_title'));
        $response->assertSee(__('therapist.invite_to_claim'));
        // Below the rating display threshold, no official score is shown.
        $response->assertSee(__('therapist.not_enough_ratings'));
        // An unclaimed profile must not offer a confirmed booking flow.
        $response->assertDontSee(__('therapist.book_title', ['name' => 'Dennis Aquino']));
        $response->assertDontSee(__('therapist.booking_enabled'));
    }

    public function test_therapist_profile_returns_not_found_for_unknown_slug(): void
    {
        $this->get('/therapist/does-not-exist')->assertStatus(404);
    }

    public function test_homepage_links_featured_therapist_with_profile(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('/therapist/maya-santos', false);
    }

    public function test_planned_sections_render_coming_soon_pages(): void
    {
        foreach (['/directory', '/directory/area', '/directory/type-spa', '/campus', '/promo'] as $path) {
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
        $this->get('/legal/cookie')->assertStatus(200)->assertSee(__('cookies.page_title'));
    }

    public function test_forgot_password_page_renders(): void
    {
        $this->get('/forgot-password')
            ->assertStatus(200)
            ->assertSee(__('auth.password_request_title'))
            ->assertSee(__('auth.password_request_button'));
    }
}
