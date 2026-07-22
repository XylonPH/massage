<?php

namespace Tests\Unit\Support;

use App\Models\Contribution;
use App\Models\Establishment;
use App\Support\Establishment\DuplicateEstablishmentFinder;
use Tests\TestCase;

class DuplicateEstablishmentFinderTest extends TestCase
{
    protected function tearDown(): void
    {
        Establishment::query()->delete();
        Contribution::query()->delete();
        parent::tearDown();
    }

    public function test_finds_a_normalized_name_match_among_live_establishments(): void
    {
        Establishment::query()->create([
            'display_name' => ['eng' => 'Harbor  Calm Spa!'],
            'address_public' => 'Makati City',
        ]);

        $matches = (new DuplicateEstablishmentFinder)->find('harbor calm spa');

        $this->assertCount(1, $matches);
        $this->assertSame('establishment', $matches->first()['source']);
    }

    public function test_finds_a_match_among_other_pending_contributions(): void
    {
        Contribution::query()->create([
            'type_contribution' => 'ADD',
            'target_collection' => 'establishment_main',
            'submitted_by_user_id' => 'Us7K2pQ9xR4tV8zN',
            'status_contribution' => 'PND',
            'proposed_data' => ['display_name' => ['eng' => ['text' => 'Ocean Breeze Spa']]],
        ]);

        $matches = (new DuplicateEstablishmentFinder)->find('Ocean Breeze Spa');

        $this->assertCount(1, $matches);
        $this->assertSame('contribution', $matches->first()['source']);
    }

    public function test_returns_no_matches_for_a_distinct_name(): void
    {
        Establishment::query()->create(['display_name' => ['eng' => 'Harbor Calm Spa']]);

        $matches = (new DuplicateEstablishmentFinder)->find('Totally Different Wellness Center');

        $this->assertCount(0, $matches);
    }

    public function test_finds_a_match_using_the_current_flat_contribution_shape(): void
    {
        Contribution::query()->create([
            'type_contribution' => 'ADD',
            'target_collection' => 'establishment_main',
            'submitted_by_user_id' => 'Us7K2pQ9xR4tV8zN',
            'status_contribution' => 'PND',
            'proposed_data' => [
                'display_name' => ['eng' => 'Sunset Wellness Spa'],
                'address_public' => 'Taguig City',
            ],
        ]);

        $matches = (new DuplicateEstablishmentFinder)->find('Sunset Wellness Spa');

        $this->assertCount(1, $matches);
        $this->assertSame('Taguig City', $matches->first()['address_public']);
    }
}
