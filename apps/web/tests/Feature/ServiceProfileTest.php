<?php

namespace Tests\Feature;

use App\Models\Service;
use Tests\TestCase;

class ServiceProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_service_profile_returns_a_successful_response(): void
    {
        // First delete any previous test data if it's there
        Service::where('service_slug', 'test-service')->delete();

        Service::create([
            'service_slug' => 'test-service',
            'service_name' => ['eng' => 'Test Service'],
            'status_record_lifecycle' => 'ACT',
        ]);

        $response = $this->get('/service/test-service');

        $response->assertStatus(200);
        $response->assertSee('Test Service');
    }
}
