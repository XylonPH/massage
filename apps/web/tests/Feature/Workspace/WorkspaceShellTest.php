<?php

namespace Tests\Feature\Workspace;

use App\Models\User;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class WorkspaceShellTest extends TestCase
{
    use InteractsWithMongoUsers;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/workspace/home')->assertRedirect('/login');
    }

    public function test_unverified_user_cannot_enter_the_workspace(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'status_account' => 'pending_email_verification',
            'status_membership' => 'pending_eligibility',
        ]);

        $this->actingAs($user)->get('/workspace/home')->assertRedirect('/verify-email');
    }

    public function test_workspace_root_redirects_to_home(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/workspace')->assertRedirect('/workspace/home');
    }

    public function test_active_member_sees_personal_dashboard(): void
    {
        $user = User::factory()->create(['username' => 'workspacefan1']);

        $response = $this->actingAs($user)->get('/workspace/home');

        $response->assertStatus(200);
        $response->assertSee(__('workspace.greeting', ['name' => 'workspacefan1']));
        $response->assertSee(__('workspace.card_account_title'));
        $response->assertSee(__('workspace.card_claim_title'));
        $response->assertSee(__('workspace.nav_listing_spa'));
        $response->assertSee(__('workspace.nav_listing_therapist'));
    }

    public function test_dashboard_greets_with_display_name_when_set(): void
    {
        $user = User::factory()->create(['username' => 'workspacefan2']);
        $user->forceFill(['display_name' => 'Wellness Fan'])->save();

        $this->actingAs($user)->get('/workspace/home')
            ->assertSee(__('workspace.greeting', ['name' => 'Wellness Fan']));
    }

    public function test_member_can_update_public_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/workspace/profile', [
            'display_name' => 'Calm Explorer',
            'bio' => 'Weekend spa explorer.',
        ]);

        $response->assertRedirect('/workspace/profile');

        $user->refresh();
        $this->assertSame('Calm Explorer', $user->display_name);
        $this->assertSame('Weekend spa explorer.', $user->bio);
    }

    public function test_profile_update_rejects_overlong_display_name(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from('/workspace/profile')
            ->put('/workspace/profile', ['display_name' => str_repeat('a', 61)])
            ->assertSessionHasErrors('display_name');
    }

    public function test_member_can_toggle_marketing_opt_in(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/workspace/setting', ['marketing_opt_in' => '1'])
            ->assertRedirect('/workspace/setting');
        $this->assertTrue($user->refresh()->is_marketing_email_opt_in);

        $this->actingAs($user)->put('/workspace/setting', [])
            ->assertRedirect('/workspace/setting');
        $this->assertFalse($user->refresh()->is_marketing_email_opt_in);
    }

    public function test_managed_listing_areas_render_claim_empty_states(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/workspace/listing/spa')
            ->assertStatus(200)
            ->assertSee(__('workspace.listing_spa_empty_title'))
            ->assertSee('/claim/spa', false);

        $this->actingAs($user)->get('/workspace/listing/therapist')
            ->assertStatus(200)
            ->assertSee(__('workspace.listing_therapist_empty_title'))
            ->assertSee('/claim/therapist', false);
    }

    public function test_claim_and_help_routes_render_coming_soon(): void
    {
        foreach (['/claim', '/claim/spa', '/claim/therapist', '/help', '/help/claim'] as $path) {
            $this->get($path)->assertStatus(200)->assertSee(__('common.coming_soon_badge'));
        }
    }
}
