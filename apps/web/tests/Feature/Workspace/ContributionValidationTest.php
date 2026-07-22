<?php

namespace Tests\Feature\Workspace;

use App\Livewire\Workspace\Editorial\EstablishmentForm;
use App\Models\User;
use Livewire\Livewire;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class ContributionValidationTest extends TestCase
{
    use InteractsWithMongoUsers;

    public function test_type_spa_rejects_an_unknown_code(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.type_spa', 'NOT_A_REAL_CODE')
            ->call('nextStep')
            ->assertHasErrors(['state.type_spa']);
    }

    public function test_status_establishment_rejects_an_unknown_code(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.status_establishment', 'NOT_A_REAL_CODE')
            ->call('nextStep')
            ->assertHasErrors(['state.status_establishment']);
    }

    public function test_level_spa_market_rejects_an_unknown_code(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.level_spa_market', 'NOT_A_REAL_CODE')
            ->call('nextStep')
            ->assertHasErrors(['state.level_spa_market']);
    }

    public function test_mode_service_delivery_rejects_an_unknown_code(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.mode_service_delivery', ['NOT_A_REAL_CODE'])
            ->call('nextStep')
            ->assertHasErrors(['state.mode_service_delivery.0']);
    }

    public function test_parking_availability_list_rejects_an_unknown_code(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('state.parking_availability_list', ['NOT_A_REAL_CODE'])
            ->call('nextStep')
            ->assertHasErrors(['state.parking_availability_list.0']);
    }

    public function test_landmark_list_is_capped_at_twenty_rows(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true);

        for ($i = 0; $i < 21; $i++) {
            $test->call('addRow', 'landmark_list');
        }

        $test->set('currentStep', 2)->call('nextStep')->assertHasErrors(['state.landmark_list']);
    }

    public function test_contact_channel_list_is_capped_at_twenty_rows(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true);

        for ($i = 0; $i < 21; $i++) {
            $test->call('addRow', 'contact_channel_list');
        }

        $test->set('currentStep', 2)->call('nextStep')->assertHasErrors(['state.contact_channel_list']);
    }

    public function test_treatment_area_list_is_capped_at_twenty_rows(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true);

        for ($i = 0; $i < 21; $i++) {
            $test->call('addRow', 'treatment_area_list');
        }

        $test->set('currentStep', 2)->call('nextStep')->assertHasErrors(['state.treatment_area_list']);
    }

    public function test_operating_hours_is_capped_at_twenty_rows(): void
    {
        $test = Livewire::actingAs(User::factory()->create())
            ->test(EstablishmentForm::class)
            ->set('isContribution', true);

        // mount() already seeds 7 rows; 14 more pushes the total to 21.
        for ($i = 0; $i < 14; $i++) {
            $test->call('addRow', 'operating_hours');
        }

        $test->set('currentStep', 2)->call('nextStep')->assertHasErrors(['state.operating_hours']);
    }
}
