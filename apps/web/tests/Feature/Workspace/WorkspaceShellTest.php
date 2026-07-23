<?php

namespace Tests\Feature\Workspace;

use App\Models\Establishment;
use App\Models\Practitioner;
use App\Models\User;
use App\Models\UserAccess;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class WorkspaceShellTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function setUp(): void
    {
        parent::setUp();

        UserAccess::query()->delete();
        Establishment::query()->delete();
        Practitioner::query()->delete();
    }

    protected function tearDown(): void
    {
        UserAccess::query()->delete();
        Establishment::query()->delete();
        Practitioner::query()->delete();

        parent::tearDown();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/workspace/home')->assertRedirect('/login');
    }

    public function test_administrative_panels_use_the_shared_workspace_login(): void
    {
        foreach (['editorial', 'moderation', 'system'] as $panel) {
            $this->get("/workspace/{$panel}")->assertRedirect('/login');
        }
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

    public function test_global_header_uses_one_workspace_entry_instead_of_feature_shortcuts(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/')
            ->assertOk()
            ->assertSee(route('workspace.home', [], false), false)
            ->assertDontSee(route('workspace.article.index', [], false), false)
            ->assertDontSee(route('workspace.review.index', [], false), false);
    }

    public function test_active_member_sees_adaptive_dashboard_without_administrative_areas(): void
    {
        $user = User::factory()->create(['username' => 'workspacefan1']);

        $response = $this->actingAs($user)->get('/workspace/home');

        $response->assertStatus(200);
        $response->assertSee(__('workspace.greeting', ['name' => 'workspacefan1']));
        $response->assertSee(__('workspace.card_account_title'));
        $response->assertSee(__('workspace.card_claim_title'));
        $response->assertSee(__('workspace.nav_listing_spa'));
        $response->assertSee(__('workspace.nav_listing_therapist'));
        $response->assertSee(__('workspace.nav_contributions'));
        $response->assertDontSee(__('workspace.administration_title'));
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
            'profile_biography' => 'Weekend spa explorer.',
        ]);

        $response->assertRedirect('/workspace/profile');

        $user->refresh();
        $this->assertSame('Calm Explorer', $user->display_name);
        $this->assertSame('Weekend spa explorer.', $user->profile_biography);
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
            ->assertSee(route('workspace.contribution.establishment.create', [], false), false);

        $this->actingAs($user)->get('/workspace/listing/therapist')
            ->assertStatus(200)
            ->assertSee(__('workspace.listing_therapist_empty_title'))
            ->assertSee(route('workspace.contribution.practitioner.create', [], false), false);
    }

    public function test_legacy_claim_routes_redirect_into_the_authenticated_workspace(): void
    {
        foreach (['/claim', '/claim/spa', '/claim/therapist', '/contribute'] as $path) {
            $this->get($path)->assertRedirect('/workspace/contribution/establishment/new');
        }

        $this->get('/help')->assertStatus(200)->assertSee(__('common.coming_soon_badge'));
    }

    public function test_ordinary_member_cannot_open_administrative_panels(): void
    {
        $user = User::factory()->create();

        foreach (['editorial', 'moderation', 'system'] as $panel) {
            $this->actingAs($user)->get("/workspace/{$panel}")->assertForbidden();
        }
    }

    public function test_founder_assignment_automatically_adds_all_administrative_areas(): void
    {
        $user = User::factory()->create();
        UserAccess::query()->create([
            'user_id' => (string) $user->getKey(),
            'role_workspace' => 'FND',
            'scope_access' => 'GBL',
            'status_user_access' => 'ACT',
            'effective_at' => now()->subMinute(),
        ]);

        $response = $this->actingAs($user)->get('/workspace/home');

        $response->assertOk()
            ->assertSee(__('workspace.administration_title'))
            ->assertSee('/workspace/editorial', false)
            ->assertSee('/workspace/moderation', false)
            ->assertSee('/workspace/system', false);

        $this->actingAs($user)->get('/workspace/editorial')->assertOk();
    }

    public function test_scoped_access_combines_multiple_establishments_without_a_context_switcher(): void
    {
        $user = User::factory()->create();
        $first = Establishment::query()->create(['display_name' => ['eng' => 'Calm One']]);
        $second = Establishment::query()->create(['display_name' => ['eng' => 'Calm Two']]);

        foreach ([$first, $second] as $establishment) {
            UserAccess::query()->create([
                'user_id' => (string) $user->getKey(),
                'permission_code_list' => ['establishment.manage'],
                'scope_access' => 'EST',
                'scope_record_id' => (string) $establishment->getKey(),
                'status_user_access' => 'ACT',
            ]);
        }

        $this->actingAs($user)->get('/workspace/listing/spa')
            ->assertOk()
            ->assertSee('Calm One')
            ->assertSee('Calm Two');
    }

    public function test_practitioner_assignment_appears_without_changing_workspace_mode(): void
    {
        $user = User::factory()->create();
        $practitioner = Practitioner::query()->create([
            'practitioner_name' => ['eng' => ['text' => 'Maya Santos']],
        ]);
        UserAccess::query()->create([
            'user_id' => (string) $user->getKey(),
            'role_workspace' => 'THP',
            'scope_access' => 'PRA',
            'scope_record_id' => (string) $practitioner->getKey(),
            'status_user_access' => 'ACT',
        ]);

        $this->actingAs($user)->get('/workspace/listing/therapist')
            ->assertOk()
            ->assertSee('Maya Santos');
    }

    public function test_dashboard_shows_activity_stat_row(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/workspace/home')
            ->assertOk()
            ->assertSee(__('workspace.stat_reviews'))
            ->assertSee(__('workspace.stat_articles'))
            ->assertSee(__('workspace.stat_contributions'));
    }
}
