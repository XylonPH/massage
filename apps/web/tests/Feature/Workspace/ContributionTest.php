<?php

namespace Tests\Feature\Workspace;

use App\Livewire\Workspace\Editorial\EstablishmentForm;
use App\Models\Contribution;
use App\Models\Reference\Country;
use App\Models\Reference\Region;
use App\Models\User;
use App\Models\UserAccess;
use Livewire\Livewire;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class ContributionTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function setUp(): void
    {
        parent::setUp();

        Contribution::query()->delete();
        UserAccess::query()->delete();
    }

    protected function tearDown(): void
    {
        Contribution::query()->delete();
        UserAccess::query()->delete();

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

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.address_public', '123 Bay Street, Manila')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->set('type_establishment_relationship', 'MGR')
            ->set('is_workspace_access_requested', true)
            ->set('relationship_note', 'I manage daily operations.')
            ->call('save')
            ->assertRedirect(route('workspace.contribution.index'));

        $contribution = Contribution::query()->where('submitted_by_user_id', (string) $user->getKey())->firstOrFail();
        $this->assertSame('establishment_main', $contribution->target_collection);
        $this->assertSame('MGR', $contribution->type_establishment_relationship);
        $this->assertSame('PND', $contribution->status_contribution);
        $this->assertTrue($contribution->is_workspace_access_requested);
        $this->assertSame('Harbor Calm Spa', data_get($contribution->proposed_data, 'display_name.eng'));
        $this->assertSame('123 Bay Street, Manila', data_get($contribution->proposed_data, 'address_public'));
        $this->assertSame(0, UserAccess::query()->where('user_id', (string) $user->getKey())->count());
    }

    public function test_public_information_contributor_cannot_request_management_access(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('state.display_name_eng', 'Community Spa')
            ->set('state.address_public', 'Quezon City')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->set('type_establishment_relationship', 'NON')
            ->set('is_workspace_access_requested', true)
            ->call('save')
            ->assertHasErrors('is_workspace_access_requested');

        $this->assertSame(0, Contribution::query()->count());
    }

    public function test_contribution_route_renders_the_robust_establishment_form(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/workspace/contribution/establishment/new')
            ->assertOk()
            ->assertSeeLivewire(EstablishmentForm::class)
            ->assertSee(__('workspace.contribution_establishment_title'))
            ->assertSee(__('workspace.contribution_connection_label'))
            ->assertSee(__('editorial.next'));
    }

    public function test_editorial_route_still_renders_direct_edit_form_without_relationship_tab(): void
    {
        $editor = User::factory()->create();
        UserAccess::query()->create([
            'user_id' => (string) $editor->getKey(),
            'role_workspace' => 'EAD',
            'scope_access' => 'GBL',
            'status_user_access' => 'ACT',
            'effective_at' => now()->subMinute(),
        ]);

        $this->actingAs($editor)
            ->get('/workspace/editorial/establishment/new')
            ->assertOk()
            ->assertSeeLivewire(EstablishmentForm::class)
            ->assertDontSee(__('workspace.contribution_connection_label'));
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
        $this->assertSame(0, UserAccess::query()->count());
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

    public function test_contribution_mode_starts_on_step_one(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->assertSet('currentStep', 1);
    }

    public function test_who_you_are_step_shows_before_spa_details_and_review(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/workspace/contribution/establishment/new');

        $response->assertOk();
        $response->assertSee(__('workspace.add_spa_step_who_you_are'));
        $response->assertSee(__('workspace.contribution_connection_label'));
        $response->assertDontSee(__('editorial.tab_identity'));
    }

    public function test_editorial_mode_has_no_step_concept(): void
    {
        Livewire::actingAs($this->editorForWizardTest())
            ->test(EstablishmentForm::class)
            ->assertSet('currentStep', 1);
    }

    private function editorForWizardTest(): User
    {
        $user = User::factory()->create();
        UserAccess::query()->create([
            'user_id' => (string) $user->getKey(),
            'role_workspace' => 'EAD',
            'scope_access' => 'GBL',
            'status_user_access' => 'ACT',
            'effective_at' => now()->subMinute(),
        ]);

        return $user;
    }

    public function test_contribution_identity_tab_hides_email_contact_and_lifecycle_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/workspace/contribution/establishment/new');

        $response->assertDontSee(__('editorial.est_status_record_lifecycle'));
    }

    public function test_contribution_identity_tab_actually_hides_the_fields_at_step_two(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2);

        $test->assertDontSee(__('editorial.est_status_record_lifecycle'));
        $test->assertDontSee(__('editorial.est_email'));
    }

    public function test_editorial_identity_tab_still_shows_the_fields(): void
    {
        Livewire::actingAs($this->editorForWizardTest())
            ->test(EstablishmentForm::class)
            ->assertSee(__('editorial.est_status_record_lifecycle'))
            ->assertSee(__('editorial.est_email'));
    }

    public function test_closed_date_is_required_when_status_is_permanently_closed(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.status_establishment', 'PC')
            ->set('state.date_closed', '')
            ->call('nextStep')
            ->assertHasErrors(['state.date_closed' => 'required']);
    }

    public function test_closed_date_not_required_when_status_is_operating(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.display_name_eng', 'Calm Springs')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->call('nextStep')
            ->assertHasNoErrors(['state.date_closed']);
    }

    public function test_contact_channel_hides_phone_type_for_email_channels(): void
    {
        $user = User::factory()->create();

        $test = Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->call('addRow', 'contact_channel_list')
            ->set('state.contact_channel_list.0.type_contact_channel', 'EML');

        $this->assertFalse($test->instance()->channelNeedsPhoneType('EML'));
        $this->assertTrue($test->instance()->channelNeedsPhoneType('PHN'));
    }

    public function test_contact_channel_type_toggles_the_rendered_fields(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->call('addRow', 'contact_channel_list')
            ->set('state.contact_channel_list.0.type_contact_channel', 'EML');

        $test->assertDontSee(__('editorial.est_type_contact_number'));

        $test->set('state.contact_channel_list.0.type_contact_channel', 'PHN');

        $test->assertSee(__('editorial.est_type_contact_number'));
    }

    public function test_location_tab_offers_region_select_and_auto_composes_address(): void
    {
        Country::query()->getConnection()->getCollection('country_main')->insertOne([
            '_id' => 608, 'country_name' => ['eng' => ['text' => 'Philippines']],
        ]);
        Region::query()->getConnection()->getCollection('region_main')->insertOne([
            '_id' => 1, 'country_id' => 608, 'region_name' => ['eng' => ['text' => 'National Capital Region']],
        ]);

        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.country_id', 608)
            ->set('state.region_id', 1)
            ->set('state.city_name', 'Makati')
            ->set('state.street_address', '123 Bay Street')
            ->call('composeAddressPublic');

        $test->assertSet('state.address_public', '123 Bay Street, Makati, National Capital Region, Philippines');

        Region::query()->getConnection()->getCollection('region_main')->deleteMany([]);
        Country::query()->getConnection()->getCollection('country_main')->deleteMany([]);
    }
}
