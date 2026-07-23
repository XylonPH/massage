<?php

namespace Tests\Feature\Workspace;

use App\Models\User;
use App\Models\UserAccess;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class SystemUserManagementTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function setUp(): void
    {
        parent::setUp();
        UserAccess::query()->delete();
    }

    protected function tearDown(): void
    {
        UserAccess::query()->delete();
        parent::tearDown();
    }

    public function test_founder_can_review_user_and_assign_a_role(): void
    {
        $founder = User::factory()->create();
        $member = User::factory()->create();
        UserAccess::create(['user_id' => (string) $founder->getKey(), 'role_workspace' => 'FND', 'scope_access' => 'GBL', 'status_user_access' => 'ACT']);

        $this->actingAs($founder)->get(route('workspace.system.user.show', $member))->assertOk()->assertSee($member->email);
        $this->actingAs($founder)->post(route('workspace.system.user.access.store', $member), [
            'role_workspace' => 'EAD', 'grant_reason' => 'Assigned to support the editorial review queue.', 'is_role_public' => '1',
        ])->assertRedirect();

        $assignment = UserAccess::where('user_id', (string) $member->getKey())->where('role_workspace', 'EAD')->firstOrFail();
        $this->assertSame('ACT', $assignment->status_user_access);
        $this->assertTrue($assignment->is_role_public);
    }

    public function test_sole_founder_cannot_revoke_own_founder_access_or_suspend_self(): void
    {
        $founder = User::factory()->create();
        $assignment = UserAccess::create(['user_id' => (string) $founder->getKey(), 'role_workspace' => 'FND', 'scope_access' => 'GBL', 'status_user_access' => 'ACT']);

        $this->actingAs($founder)->delete(route('workspace.system.user.access.destroy', [$founder, $assignment]), [
            'revocation_reason' => 'Testing the sole founder safety protection.',
        ])->assertStatus(422);
        $this->actingAs($founder)->put(route('workspace.system.user.status', $founder), [
            'status_account' => 'SUS', 'reason' => 'Testing the self suspension safety protection.',
        ])->assertStatus(422);

        $this->assertSame('ACT', $assignment->refresh()->status_user_access);
        $this->assertSame('ACT', $founder->refresh()->status_account);
    }
}
