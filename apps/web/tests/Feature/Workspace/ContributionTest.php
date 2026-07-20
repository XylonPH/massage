<?php

namespace Tests\Feature\Workspace;

use App\Models\AccessAssignment;
use App\Models\Contribution;
use App\Models\User;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class ContributionTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function setUp(): void
    {
        parent::setUp();

        Contribution::query()->delete();
        AccessAssignment::query()->delete();
    }

    protected function tearDown(): void
    {
        Contribution::query()->delete();
        AccessAssignment::query()->delete();

        parent::tearDown();
    }

    public function test_guest_must_sign_in_before_contributing(): void
    {
        $this->get('/workspace/contribution/establishment/new')->assertRedirect('/login');
        $this->get('/workspace/contribution/practitioner/new')->assertRedirect('/login');
    }

    public function test_member_can_submit_establishment_and_request_reviewed_access(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/workspace/contribution/establishment', [
            'display_name' => 'Harbor Calm Spa',
            'address_public' => '123 Bay Street, Manila',
            'type_establishment_relationship' => 'MGR',
            'is_workspace_access_requested' => '1',
            'relationship_note' => 'I manage daily operations.',
        ])->assertRedirect('/workspace/contribution');

        $contribution = Contribution::query()->where('submitted_by_user_id', (string) $user->getKey())->firstOrFail();
        $this->assertSame('establishment_main', $contribution->target_collection);
        $this->assertSame('MGR', $contribution->type_establishment_relationship);
        $this->assertSame('PND', $contribution->status_contribution);
        $this->assertTrue($contribution->is_workspace_access_requested);
        $this->assertSame('Harbor Calm Spa', data_get($contribution->proposed_data, 'display_name.eng'));
        $this->assertSame(0, AccessAssignment::query()->where('user_id', (string) $user->getKey())->count());
    }

    public function test_public_information_contributor_cannot_request_management_access(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from('/workspace/contribution/establishment/new')
            ->post('/workspace/contribution/establishment', [
                'display_name' => 'Community Spa',
                'address_public' => 'Quezon City',
                'type_establishment_relationship' => 'NON',
                'is_workspace_access_requested' => '1',
            ])
            ->assertRedirect('/workspace/contribution/establishment/new')
            ->assertSessionHasErrors('is_workspace_access_requested');

        $this->assertSame(0, Contribution::query()->count());
    }

    public function test_practitioner_can_submit_own_profile_for_review(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/workspace/contribution/practitioner', [
            'practitioner_name' => 'Maya Santos',
            'short_description' => 'Independent massage practitioner.',
            'type_practitioner_relationship' => 'SLF',
            'is_workspace_access_requested' => '1',
        ])->assertRedirect('/workspace/contribution');

        $contribution = Contribution::query()->firstOrFail();
        $this->assertSame('practitioner_main', $contribution->target_collection);
        $this->assertSame('SLF', $contribution->type_practitioner_relationship);
        $this->assertSame('Maya Santos', data_get($contribution->proposed_data, 'practitioner_name.eng.text'));
        $this->assertSame(0, AccessAssignment::query()->count());
    }

    public function test_member_sees_only_their_own_contributions(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        Contribution::query()->create([
            'type_contribution' => 'ADD',
            'target_collection' => 'establishment_main',
            'submitted_by_user_id' => (string) $other->getKey(),
            'proposed_data' => ['display_name' => ['eng' => 'Other User Spa']],
            'status_contribution' => 'PND',
            'submitted_at' => now(),
        ]);

        $this->actingAs($user)->get('/workspace/contribution')
            ->assertOk()
            ->assertDontSee('Other User Spa');
    }
}
