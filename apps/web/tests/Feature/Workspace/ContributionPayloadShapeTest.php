<?php

namespace Tests\Feature\Workspace;

use App\Livewire\Workspace\Editorial\EstablishmentForm;
use App\Models\Contribution;
use App\Models\User;
use Livewire\Livewire;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

/**
 * Guards proposed_data.establishment against drifting away from the field contract
 * documented in data/structure_guide/establishment_main.php (see
 * data/structure_guide/user_contribution.php: "proposed_data follows the target
 * collection's field contracts"). System-owned fields (assigned by editorial review,
 * never by a contributor) are excluded from the allowed set.
 */
class ContributionPayloadShapeTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function tearDown(): void
    {
        Contribution::query()->delete();
        parent::tearDown();
    }

    public function test_proposed_data_establishment_keys_are_all_in_the_guide_field_order(): void
    {
        $guide = require base_path('../../data/structure_guide/establishment_main.php');
        $systemOwned = [
            '_id', 'establishment_slug', 'previous_slug_list', 'status_record_lifecycle',
            'revision_number', 'created_at', 'updated_at', 'last_confirmed_at',
        ];
        $allowedKeys = array_diff($guide['establishment_main_field_order'], $systemOwned);

        $user = User::factory()->create();
        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 3)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->set('state.official_name', 'Harbor Calm Spa Inc.')
            ->set('state.country_id', 608)
            ->set('state.region_id', 1)
            ->set('state.city_name', 'Makati')
            ->set('state.street_address', '123 Bay Street')
            ->set('state.address_public', '123 Bay Street, Makati')
            ->set('duplicateAcknowledged', true)
            ->call('addRow', 'contact_channel_list')
            ->set('state.contact_channel_list.0.type_contact_channel', 'EML')
            ->set('state.contact_channel_list.0.contact_label', 'Front desk')
            ->set('state.contact_channel_list.0.contact_value', 'hello@example.com')
            ->set('state.contact_channel_list.0.contact_url', 'https://example.com')
            ->call('save');

        $contribution = Contribution::query()->where('submitted_by_user_id', (string) $user->getKey())->firstOrFail();

        $this->assertArrayHasKey('establishment', $contribution->proposed_data);

        foreach (array_keys($contribution->proposed_data['establishment']) as $key) {
            $this->assertContains($key, $allowedKeys, "proposed_data.establishment has an unexpected key: {$key}");
        }
    }

    public function test_proposed_data_separates_contact_channels_operating_schedule_and_events_from_establishment(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 3)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->set('state.official_name', 'Harbor Calm Spa Inc.')
            ->set('state.country_id', 608)
            ->set('state.region_id', 1)
            ->set('state.city_name', 'Makati')
            ->set('state.street_address', '123 Bay Street')
            ->set('state.address_public', '123 Bay Street, Makati')
            ->set('state.date_opened', '2026-01-01')
            ->call('addRow', 'contact_channel_list')
            ->set('state.contact_channel_list.0.type_contact_channel', 'EML')
            ->set('state.contact_channel_list.0.contact_label', 'Front desk')
            ->set('state.contact_channel_list.0.contact_value', 'hello@example.com')
            ->set('state.contact_channel_list.0.contact_url', 'https://example.com')
            ->set('duplicateAcknowledged', true)
            ->call('save');

        $contribution = Contribution::query()->where('submitted_by_user_id', (string) $user->getKey())->firstOrFail();

        $this->assertArrayNotHasKey('contact_channel_list', $contribution->proposed_data['establishment']);
        $this->assertArrayNotHasKey('operating_hours', $contribution->proposed_data['establishment']);
        $this->assertSame('EML', data_get($contribution->proposed_data, 'contact_channel_list.0.type_contact_channel'));
        $this->assertIsArray($contribution->proposed_data['operating_schedule']);
        $this->assertSame('OP', data_get($contribution->proposed_data, 'event_list.0.type_business_event'));
        $this->assertSame('2026-01-01', data_get($contribution->proposed_data, 'event_list.0.effective_date'));
    }

    public function test_proposed_data_establishment_does_not_leak_editorial_only_or_non_owned_fields(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 3)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->set('state.official_name', 'Harbor Calm Spa Inc.')
            ->set('state.country_id', 608)
            ->set('state.region_id', 1)
            ->set('state.city_name', 'Makati')
            ->set('state.street_address', '123 Bay Street')
            ->set('state.address_public', '123 Bay Street, Makati')
            ->set('duplicateAcknowledged', true)
            ->call('addRow', 'contact_channel_list')
            ->set('state.contact_channel_list.0.type_contact_channel', 'EML')
            ->set('state.contact_channel_list.0.contact_label', 'Front desk')
            ->set('state.contact_channel_list.0.contact_value', 'hello@example.com')
            ->set('state.contact_channel_list.0.contact_url', 'https://example.com')
            ->call('save');

        $contribution = Contribution::query()->where('submitted_by_user_id', (string) $user->getKey())->firstOrFail();
        $establishment = $contribution->proposed_data['establishment'];

        // email/contact_number are editorial-only and hidden from the contribution UI;
        // region_id/city_name are transient location-tab inputs already folded into
        // address_public; status_record_lifecycle is system-owned.
        $this->assertArrayNotHasKey('email', $establishment);
        $this->assertArrayNotHasKey('contact_number', $establishment);
        $this->assertArrayNotHasKey('region_id', $establishment);
        $this->assertArrayNotHasKey('city_name', $establishment);
        $this->assertArrayNotHasKey('status_record_lifecycle', $establishment);
        $this->assertArrayNotHasKey('coordinate_latitude', $establishment);
        $this->assertArrayNotHasKey('coordinate_longitude', $establishment);
    }

    public function test_coordinates_are_translated_into_a_geojson_location_point(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 3)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->set('state.official_name', 'Harbor Calm Spa Inc.')
            ->set('state.country_id', 608)
            ->set('state.region_id', 1)
            ->set('state.city_name', 'Makati')
            ->set('state.street_address', '123 Bay Street')
            ->set('state.address_public', '123 Bay Street, Makati')
            ->set('state.coordinate_latitude', 14.5547)
            ->set('state.coordinate_longitude', 121.0244)
            ->set('duplicateAcknowledged', true)
            ->call('addRow', 'contact_channel_list')
            ->set('state.contact_channel_list.0.type_contact_channel', 'EML')
            ->set('state.contact_channel_list.0.contact_label', 'Front desk')
            ->set('state.contact_channel_list.0.contact_value', 'hello@example.com')
            ->set('state.contact_channel_list.0.contact_url', 'https://example.com')
            ->call('save');

        $contribution = Contribution::query()->where('submitted_by_user_id', (string) $user->getKey())->firstOrFail();

        $this->assertSame('Point', data_get($contribution->proposed_data, 'establishment.location_point.type'));
        $this->assertSame([121.0244, 14.5547], data_get($contribution->proposed_data, 'establishment.location_point.coordinates'));
    }

    public function test_description_field_is_renamed_to_full_description_for_guide_conformance(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 3)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->set('state.official_name', 'Harbor Calm Spa Inc.')
            ->set('state.country_id', 608)
            ->set('state.region_id', 1)
            ->set('state.city_name', 'Makati')
            ->set('state.street_address', '123 Bay Street')
            ->set('state.address_public', '123 Bay Street, Makati')
            ->set('state.description_eng', 'A calm neighborhood spa.')
            ->set('duplicateAcknowledged', true)
            ->call('addRow', 'contact_channel_list')
            ->set('state.contact_channel_list.0.type_contact_channel', 'EML')
            ->set('state.contact_channel_list.0.contact_label', 'Front desk')
            ->set('state.contact_channel_list.0.contact_value', 'hello@example.com')
            ->set('state.contact_channel_list.0.contact_url', 'https://example.com')
            ->call('save');

        $contribution = Contribution::query()->where('submitted_by_user_id', (string) $user->getKey())->firstOrFail();

        $this->assertArrayNotHasKey('description', $contribution->proposed_data['establishment']);
        $this->assertSame('A calm neighborhood spa.', data_get($contribution->proposed_data, 'establishment.full_description.eng.text'));
    }
}
