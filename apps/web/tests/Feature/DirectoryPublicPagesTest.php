<?php

namespace Tests\Feature;

use Tests\TestCase;

class DirectoryPublicPagesTest extends TestCase
{
    public function test_directory_page_renders_successfully(): void
    {
        $response = $this->get(route('directory.index'));

        $response->assertStatus(200);
        $response->assertSee('Discover Verified Spas');
    }

    public function test_promo_page_renders_successfully(): void
    {
        $response = $this->get(route('promo.index'));

        $response->assertStatus(200);
        $response->assertSee('Exclusive Spa Deals');
    }

    public function test_campus_page_renders_successfully(): void
    {
        $response = $this->get(route('campus.index'));

        $response->assertStatus(200);
        $response->assertSee('Massage Campus Education Portal');
        $response->assertSee('Institute Class Sign-In');
    }
}
