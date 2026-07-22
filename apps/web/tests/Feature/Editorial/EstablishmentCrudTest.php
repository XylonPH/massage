<?php

namespace Tests\Feature\Editorial;

use App\Livewire\Workspace\Editorial\EstablishmentForm;
use App\Livewire\Workspace\Editorial\EstablishmentIndex;
use App\Models\Establishment;
use App\Models\User;
use App\Models\UserAccess;
use Livewire\Livewire;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class EstablishmentCrudTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function setUp(): void
    {
        parent::setUp();
        UserAccess::query()->delete();
        Establishment::query()->delete();
    }

    protected function tearDown(): void
    {
        UserAccess::query()->delete();
        Establishment::query()->delete();
        parent::tearDown();
    }

    private function editor(): User
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

    public function test_index_lists_and_searches_establishments(): void
    {
        $user = $this->editor();
        Establishment::query()->create(['display_name' => ['eng' => 'Calm Springs']]);
        Establishment::query()->create(['display_name' => ['eng' => 'Ocean Breeze']]);

        Livewire::actingAs($user)
            ->test(EstablishmentIndex::class)
            ->assertSee('Calm Springs')
            ->assertSee('Ocean Breeze')
            ->set('search', 'Calm')
            ->assertSee('Calm Springs')
            ->assertDontSee('Ocean Breeze');
    }

    public function test_editor_can_create_an_establishment_with_only_required_fields(): void
    {
        $user = $this->editor();

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('state.display_name_eng', 'Calm Springs')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->call('save')
            ->assertRedirect(route('workspace.editorial.establishment.index'));

        $this->assertSame(1, Establishment::query()->count());
        $record = Establishment::query()->first();
        $this->assertSame('Calm Springs', $record->display_name['eng']);
        $this->assertSame('DY', $record->type_spa);
        $this->assertSame('OP', $record->status_establishment);
    }

    public function test_create_requires_display_name_type_spa_and_status_establishment(): void
    {
        Livewire::actingAs($this->editor())
            ->test(EstablishmentForm::class)
            ->set('state.display_name_eng', '')
            ->set('state.type_spa', '')
            ->set('state.status_establishment', '')
            ->call('save')
            ->assertHasErrors([
                'state.display_name_eng' => 'required',
                'state.type_spa' => 'required',
                'state.status_establishment' => 'required',
            ]);
    }

    public function test_editor_can_update_an_establishment(): void
    {
        $user = $this->editor();
        $establishment = Establishment::query()->create([
            'display_name' => ['eng' => 'Old Name'],
            'type_spa' => 'DY',
            'status_establishment' => 'OP',
        ]);

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class, ['establishment' => (string) $establishment->getKey()])
            ->assertSet('state.display_name_eng', 'Old Name')
            ->set('state.display_name_eng', 'New Name')
            ->call('save');

        $this->assertSame('New Name', $establishment->refresh()->display_name['eng']);
    }

    public function test_editor_can_delete_an_establishment(): void
    {
        $user = $this->editor();
        $establishment = Establishment::query()->create([
            'display_name' => ['eng' => 'Doomed'],
            'type_spa' => 'DY',
            'status_establishment' => 'OP',
        ]);

        Livewire::actingAs($user)
            ->test(EstablishmentIndex::class)
            ->call('deleteRecord', (string) $establishment->getKey());

        $this->assertSame(0, Establishment::query()->count());
    }

    public function test_landmark_repeater_rows_persist(): void
    {
        $user = $this->editor();

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('state.display_name_eng', 'Calm Springs')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->call('addRow', 'landmark_list')
            ->set('state.landmark_list.0.landmark_name', 'City Hall')
            ->set('state.landmark_list.0.walking_duration_minute', 5)
            ->call('save');

        $record = Establishment::query()->first();
        $this->assertSame('City Hall', $record->landmark_list[0]['landmark_name']);
    }

    public function test_new_establishment_defaults_to_seven_operating_hours_rows(): void
    {
        $user = $this->editor();

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->assertCount('state.operating_hours', 7)
            ->assertSet('state.operating_hours.0.day_of_week', 'Monday');
    }

    public function test_operating_hours_persist(): void
    {
        $user = $this->editor();

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('state.display_name_eng', 'Calm Springs')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->set('state.operating_hours.0.open_time', '09:00')
            ->set('state.operating_hours.0.close_time', '18:00')
            ->call('save');

        $record = Establishment::query()->first();
        $this->assertSame('09:00', $record->operating_hours[0]['open_time']);
        $this->assertSame('18:00', $record->operating_hours[0]['close_time']);
    }

    public function test_editing_existing_establishment_does_not_pad_operating_hours(): void
    {
        $user = $this->editor();
        $establishment = Establishment::query()->create([
            'display_name' => ['eng' => 'Two-Day Spa'],
            'type_spa' => 'DY',
            'status_establishment' => 'OP',
            'operating_hours' => [
                ['day_of_week' => 'Monday', 'open_time' => '09:00', 'close_time' => '17:00'],
                ['day_of_week' => 'Tuesday', 'open_time' => '09:00', 'close_time' => '17:00'],
            ],
        ]);

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class, ['establishment' => (string) $establishment->getKey()])
            ->assertCount('state.operating_hours', 2);
    }

    public function test_editor_can_set_facility_and_parking_fields(): void
    {
        $user = $this->editor();

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('state.display_name_eng', 'Calm Springs')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->set('state.shower_availability', 'IR')
            ->set('state.parking_availability_list', ['PRK_ONS_FREE'])
            ->call('save');

        $record = Establishment::query()->first();
        $this->assertSame('IR', $record->shower_availability);
        $this->assertSame(['PRK_ONS_FREE'], $record->parking_availability_list);
    }

    public function test_editing_establishment_preserves_approximate_date_qualifier(): void
    {
        $user = $this->editor();
        $establishment = Establishment::query()->create([
            'display_name' => ['eng' => 'Old Name'],
            'type_spa' => 'DY',
            'status_establishment' => 'OP',
            'date_opened' => '2020-01-01',
            'date_opened_qualifier' => 'APP',
        ]);

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class, ['establishment' => (string) $establishment->getKey()])
            ->assertSet('date_opened_is_approximate', true)
            ->set('state.display_name_eng', 'New Name')
            ->call('save');

        $this->assertSame('APP', $establishment->refresh()->date_opened_qualifier);
    }

    public function test_editor_can_set_structured_address_fields(): void
    {
        $user = $this->editor();

        Livewire::actingAs($user)
            ->test(EstablishmentForm::class)
            ->set('state.display_name_eng', 'Calm Springs')
            ->set('state.type_spa', 'DY')
            ->set('state.status_establishment', 'OP')
            ->set('state.official_name', 'Calm Springs Wellness Inc.')
            ->set('state.country_id', 608)
            ->set('state.region_id', 1)
            ->set('state.city_name', 'Makati')
            ->set('state.street_address', '123 Bay Street')
            ->set('state.building_name', 'Tower A')
            ->set('state.floor_label', '2nd Floor')
            ->set('state.unit_label', 'Unit 201')
            ->set('state.postal_code', '1200')
            ->call('save');

        $record = Establishment::query()->first();
        $this->assertSame('Calm Springs Wellness Inc.', $record->official_name);
        $this->assertSame(608, $record->country_id);
        $this->assertSame('123 Bay Street', $record->street_address);
        $this->assertSame('1200', $record->postal_code);
    }
}
