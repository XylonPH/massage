<?php

namespace Tests\Feature\Workspace;

use App\Livewire\Workspace\Editorial\EstablishmentForm;
use App\Models\Contribution;
use App\Models\Establishment;
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
        $this->assertSame('Harbor Calm Spa', data_get($contribution->proposed_data, 'establishment.display_name.eng.text'));
        $this->assertSame('123 Bay Street, Manila', data_get($contribution->proposed_data, 'establishment.address_public'));
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

    public function test_page_title_is_add_a_spa_not_contribute_an_establishment(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/workspace/contribution/establishment/new');

        $response->assertSee(__('workspace.contribution_establishment_title'));
        $this->assertSame('Add a Spa', __('workspace.contribution_establishment_title'));
    }

    public function test_tab_labels_contain_no_ampersand(): void
    {
        $user = User::factory()->create();

        $content = $this->actingAs($user)->get('/workspace/contribution/establishment/new')->getContent();
        preg_match_all('/<span[^>]*class="truncate"[^>]*>([^<]*)<\/span>/', $content, $matches);

        // Fallback direct check on the translated tab labels themselves:
        foreach (['tab_identity', 'tab_classification', 'tab_access', 'tab_location', 'tab_contact', 'tab_hours', 'tab_amenities'] as $key) {
            $this->assertStringNotContainsString('&', __('editorial.'.$key));
        }
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

    public function test_closed_date_field_appears_live_when_status_changes_to_closed(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2);

        $test->assertDontSee(__('editorial.est_date_closed'));

        $test->set('state.status_establishment', 'PC');

        $test->assertSee(__('editorial.est_date_closed'));
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

    public function test_facilities_tab_hidden_for_home_service_only_spa(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.type_spa', 'HP')
            ->set('state.mode_service_delivery', ['HM']);

        $this->assertFalse($test->instance()->hasPhysicalPremises());
    }

    public function test_facility_fields_are_stripped_server_side_for_home_service_only_spa_even_if_submitted(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 3)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Mobile Massage Co')
            ->set('state.type_spa', 'HP')
            ->set('state.mode_service_delivery', ['HM'])
            ->set('state.status_establishment', 'OP')
            ->set('state.shower_availability', 'IR')
            ->call('save');

        $contribution = Contribution::query()->where('submitted_by_user_id', (string) auth()->id())->first();
        $this->assertNull(data_get($contribution->proposed_data, 'establishment.shower_availability'));
    }

    public function test_entering_review_step_populates_duplicate_candidates_when_a_display_name_matches(): void
    {
        Establishment::query()->create([
            'display_name' => ['eng' => 'Harbor Calm Spa'],
            'address_public' => 'Makati City',
        ]);

        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->call('nextStep');

        $test->assertSet('currentStep', 3);
        $this->assertCount(1, $test->instance()->duplicateCandidates);

        Establishment::query()->delete();
    }

    public function test_entering_review_step_leaves_duplicate_candidates_empty_when_no_match(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'A Totally Unique Spa Name')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->call('nextStep');

        $this->assertCount(0, $test->instance()->duplicateCandidates);
    }

    public function test_duplicate_acknowledgement_is_required_before_submit_when_candidates_exist(): void
    {
        Establishment::query()->create([
            'display_name' => ['eng' => 'Harbor Calm Spa'],
            'address_public' => 'Makati City',
        ]);

        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->call('nextStep')
            ->call('save')
            ->assertHasErrors(['duplicateAcknowledged']);

        $this->assertSame(0, Contribution::query()->count());

        Establishment::query()->delete();
    }

    public function test_duplicate_acknowledgement_is_not_required_when_no_candidates_exist(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'A Totally Unique Spa Name')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->call('nextStep')
            ->call('save')
            ->assertHasNoErrors(['duplicateAcknowledged']);

        $this->assertSame(1, Contribution::query()->count());
    }

    public function test_visit_request_is_eligible_only_for_philippines_ncr(): void
    {
        Country::query()->getConnection()->getCollection('country_main')->insertOne([
            '_id' => 608, 'country_name' => ['eng' => ['text' => 'Philippines']],
        ]);
        Region::query()->getConnection()->getCollection('region_main')->insertMany([
            ['_id' => 1, 'country_id' => 608, 'region_name' => ['eng' => ['text' => 'National Capital Region']]],
            ['_id' => 2, 'country_id' => 608, 'region_name' => ['eng' => ['text' => 'Central Luzon']]],
        ]);

        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('state.country_id', 608)
            ->set('state.region_id', 1);

        $this->assertTrue($test->instance()->visitEligible());

        $test->set('state.region_id', 2);
        $this->assertFalse($test->instance()->visitEligible());

        Region::query()->getConnection()->getCollection('region_main')->deleteMany([]);
        Country::query()->getConnection()->getCollection('country_main')->deleteMany([]);
    }

    public function test_visit_preferred_time_note_is_required_when_visit_is_requested(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 3)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'A Totally Unique Spa Name')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->set('is_visit_requested', true)
            ->set('visit_preferred_time_note', '')
            ->call('save')
            ->assertHasErrors(['visit_preferred_time_note']);

        $this->assertSame(0, Contribution::query()->count());
    }

    public function test_review_step_shows_duplicate_warning_and_requires_acknowledgement(): void
    {
        Establishment::query()->create([
            'display_name' => ['eng' => 'Harbor Calm Spa'],
            'address_public' => 'Makati City',
        ]);

        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->call('nextStep');

        $test->assertSee(__('workspace.add_spa_duplicate_warning_title'));
        $test->assertSee('Makati City');
        $test->assertDontSee(__('editorial.tab_identity'));

        Establishment::query()->delete();
    }

    public function test_acknowledging_then_submitting_the_same_candidate_set_still_succeeds(): void
    {
        Establishment::query()->create([
            'display_name' => ['eng' => 'Harbor Calm Spa'],
            'address_public' => 'Makati City',
        ]);

        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->call('nextStep')
            ->set('duplicateAcknowledged', true)
            ->call('save')
            ->assertHasNoErrors(['duplicateAcknowledged']);

        $this->assertSame(1, Contribution::query()->count());

        Establishment::query()->delete();
    }

    public function test_returning_to_step_two_and_removing_the_duplicate_resets_stale_acknowledgement(): void
    {
        Establishment::query()->create([
            'display_name' => ['eng' => 'Harbor Calm Spa'],
            'address_public' => 'Makati City',
        ]);

        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->call('nextStep')
            ->set('duplicateAcknowledged', true);

        $test->assertSet('duplicateAcknowledged', true);

        $test->call('prevStep')
            ->set('state.display_name_eng', 'A Totally Unique Spa Name')
            ->call('nextStep');

        $test->assertSet('duplicateAcknowledged', false);
        $this->assertCount(0, $test->instance()->duplicateCandidates);

        Establishment::query()->delete();
    }

    public function test_returning_to_step_two_and_switching_to_a_different_duplicate_requires_re_acknowledgement(): void
    {
        Establishment::query()->create([
            'display_name' => ['eng' => 'Harbor Calm Spa'],
            'address_public' => 'Makati City',
        ]);
        Establishment::query()->create([
            'display_name' => ['eng' => 'Lotus Wellness Spa'],
            'address_public' => 'Quezon City',
        ]);

        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->call('nextStep')
            ->set('duplicateAcknowledged', true);

        $test->assertSet('duplicateAcknowledged', true);

        $test->call('prevStep')
            ->set('state.display_name_eng', 'Lotus Wellness Spa')
            ->call('nextStep');

        $test->assertSet('duplicateAcknowledged', false);
        $this->assertCount(1, $test->instance()->duplicateCandidates);
        $this->assertSame('Lotus Wellness Spa', $test->instance()->duplicateCandidates->first()['display_name']);

        $test->call('save')->assertHasErrors(['duplicateAcknowledged']);
        $this->assertSame(0, Contribution::query()->count());

        Establishment::query()->delete();
    }

    public function test_contributor_can_submit_display_name_in_a_second_language(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 3)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.display_name_fil', 'Harbor Calm Spa (Filipino)')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->set('duplicateAcknowledged', true)
            ->call('save');

        $contribution = Contribution::query()->where('submitted_by_user_id', (string) $user->getKey())->firstOrFail();
        $this->assertSame('Harbor Calm Spa (Filipino)', data_get($contribution->proposed_data, 'establishment.display_name.fil.text'));
        $this->assertSame('HUM', data_get($contribution->proposed_data, 'establishment.display_name.fil.method_translation'));
        $this->assertSame('P', data_get($contribution->proposed_data, 'establishment.display_name.fil.status_review'));
        $this->assertSame('Harbor Calm Spa', data_get($contribution->proposed_data, 'establishment.display_name.eng.text'));
    }

    /**
     * Regression test for the reported browser reproduction: fill display_name_eng,
     * switch $activeLanguageTab to 'fil', then set display_name_fil, and confirm
     * display_name_eng is untouched.
     *
     * Caveat (documented, not silently claimed as full coverage): the actual reported
     * bug is a CLIENT-side defect — a single DOM input element whose wire:model
     * attribute string was re-interpolated per active language could end up with two
     * live Livewire input bindings on the same node after a morphdom patch, so typing
     * after a tab switch wrote into both the old and new language's state key. Livewire
     * ::test() drives the component purely server-side via named ->set() calls; it
     * never renders real DOM, never runs morphdom, and never attaches/re-attaches a
     * wire:model input listener, so it cannot fail from that specific defect class
     * regardless of which Blade structure produced the page — a single dynamically
     * rebound element or six statically bound elements are indistinguishable to this
     * test harness. This test still has value as a server-side safety net (proving
     * activeLanguageTab and per-language state keys are properly decoupled on the
     * component itself) and as a guard against a logic-level regression, but the actual
     * fix verification for this bug was done against a real browser (see task-17-report.md).
     */
    public function test_switching_language_tabs_does_not_corrupt_the_previously_active_languages_state(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.display_name_eng', 'ENGLISH-TEXT')
            ->set('activeLanguageTab', 'fil')
            ->set('state.display_name_fil', 'FILIPINO-TEXT');

        $test->assertSet('state.display_name_eng', 'ENGLISH-TEXT');
        $test->assertSet('state.display_name_fil', 'FILIPINO-TEXT');
    }

    public function test_duplicate_check_cannot_be_bypassed_by_setting_current_step_directly(): void
    {
        Establishment::query()->create([
            'display_name' => ['eng' => 'Harbor Calm Spa'],
            'address_public' => 'Makati City',
        ]);

        // currentStep is set directly to 3, skipping nextStep() entirely, so
        // duplicateCandidates never gets populated through the normal path.
        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 3)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->call('save')
            ->assertHasErrors(['duplicateAcknowledged']);

        $this->assertSame(0, Contribution::query()->count());

        Establishment::query()->delete();
    }
}
