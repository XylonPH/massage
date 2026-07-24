<?php

namespace Tests\Unit\Actions;

use App\Actions\Contribution\PromoteContributionToEstablishment;
use App\Livewire\Workspace\Editorial\EstablishmentForm;
use App\Models\Contribution;
use App\Models\Establishment;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class PromoteContributionToEstablishmentTest extends TestCase
{
    protected function tearDown(): void
    {
        Establishment::query()->delete();
        Contribution::query()->delete();
        parent::tearDown();
    }

    private function submitRealContribution(): Contribution
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('isContribution', true)
            ->set('currentStep', 2)
            ->set('type_establishment_relationship', 'NON')
            ->set('state.display_name_eng', 'Harbor Calm Spa')
            ->set('state.short_description_eng', 'A calm neighborhood spa.')
            ->set('state.description_eng', 'A calm neighborhood spa with full-service treatments.')
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
            ->call('addRow', 'contact_channel_list')
            ->set('state.contact_channel_list.0.type_contact_channel', 'EML')
            ->set('state.contact_channel_list.0.contact_label', 'Front desk')
            ->set('state.contact_channel_list.0.contact_value', 'hello@harborcalm.test')
            ->set('state.contact_channel_list.0.contact_url', 'https://harborcalm.test')
            ->call('save');

        return Contribution::query()->where('submitted_by_user_id', (string) $user->getKey())->firstOrFail();
    }

    public function test_promoting_a_real_contribution_creates_a_correctly_shaped_establishment(): void
    {
        $contribution = $this->submitRealContribution();

        $establishment = (new PromoteContributionToEstablishment)->execute($contribution);

        $this->assertInstanceOf(Establishment::class, $establishment);
        $this->assertTrue($establishment->exists);
        $this->assertSame('Harbor Calm Spa', $establishment->display_name['eng'] ?? null);
        $this->assertSame('A calm neighborhood spa.', $establishment->short_description['eng'] ?? null);
        $this->assertSame('A calm neighborhood spa with full-service treatments.', $establishment->description['eng'] ?? null);
        $this->assertSame('DY', $establishment->type_spa);
        $this->assertSame('Harbor Calm Spa Inc.', $establishment->official_name);
        $this->assertSame(608, $establishment->country_id);
        // city_name/region_id are deliberately excluded from proposed_data.establishment by
        // EstablishmentForm::submitContribution() (see its CONTRIBUTION_NON_ESTABLISHMENT_PLAIN_FIELDS
        // comment: they're "Location-tab inputs already folded into address_public"), so the
        // promoted Establishment has no source data to populate them from. Documenting this as
        // the real, current behavior rather than asserting a value the data cannot produce.
        $this->assertNull($establishment->city_name);
        $this->assertEqualsWithDelta(14.5547, $establishment->coordinate_latitude, 0.0001);
        $this->assertEqualsWithDelta(121.0244, $establishment->coordinate_longitude, 0.0001);
        $this->assertSame('EML', data_get($establishment->contact_channel_list, '0.type_contact_channel'));
        $this->assertNull($establishment->getAttribute('location_point'));
    }
}
