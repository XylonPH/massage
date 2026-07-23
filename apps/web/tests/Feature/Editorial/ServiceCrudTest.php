<?php

namespace Tests\Feature\Editorial;

use App\Livewire\Workspace\Editorial\ServiceForm;
use App\Livewire\Workspace\Editorial\ServiceIndex;
use App\Models\Service;
use App\Models\User;
use App\Models\UserAccess;
use Livewire\Livewire;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class ServiceCrudTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function setUp(): void
    {
        parent::setUp();
        UserAccess::query()->delete();
        Service::query()->delete();
    }

    protected function tearDown(): void
    {
        UserAccess::query()->delete();
        Service::query()->delete();
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

    public function test_index_lists_and_searches_services(): void
    {
        $user = $this->editor();
        Service::query()->create(['service_slug' => 'thai-massage', 'service_name' => ['eng' => 'Thai Massage'], 'group_service_family' => 'massage']);
        Service::query()->create(['service_slug' => 'deep-tissue', 'service_name' => ['eng' => 'Deep Tissue'], 'group_service_family' => 'massage']);

        Livewire::actingAs($user)
            ->test(ServiceIndex::class)
            ->assertSee('Thai Massage')
            ->assertSee('Deep Tissue')
            ->set('search', 'Thai')
            ->assertSee('Thai Massage')
            ->assertDontSee('Deep Tissue');
    }

    public function test_editor_can_create_a_service(): void
    {
        $user = $this->editor();

        Livewire::actingAs($user)
            ->test(ServiceForm::class)
            ->set('state.english_name', 'Hot Stone')
            ->set('state.service_slug', 'hot-stone')
            ->set('state.group_service_family', 'massage')
            ->call('save')
            ->assertRedirect(route('workspace.editorial.service.index'));

        $this->assertSame(1, Service::query()->count());
        $record = Service::query()->first();
        $this->assertSame('hot-stone', $record->service_slug);
        $this->assertSame('Hot Stone', $record->english_name);
    }

    public function test_create_requires_english_name_slug_and_family(): void
    {
        Livewire::actingAs($this->editor())
            ->test(ServiceForm::class)
            ->set('state.english_name', '')
            ->set('state.service_slug', '')
            ->set('state.group_service_family', '')
            ->call('save')
            ->assertHasErrors([
                'state.english_name' => 'required',
                'state.service_slug' => 'required',
                'state.group_service_family' => 'required',
            ]);
    }

    public function test_editor_can_update_a_service(): void
    {
        $user = $this->editor();
        $service = Service::query()->create(['service_slug' => 'old-slug', 'service_name' => ['eng' => 'Old Name'], 'group_service_family' => 'massage']);

        Livewire::actingAs($user)
            ->test(ServiceForm::class, ['service' => (string) $service->getKey()])
            ->assertSet('state.english_name', 'Old Name')
            ->set('state.english_name', 'New Name')
            ->call('save');

        $this->assertSame('New Name', $service->refresh()->english_name);
    }

    public function test_editor_can_delete_a_service(): void
    {
        $user = $this->editor();
        $service = Service::query()->create(['service_slug' => 'doomed', 'service_name' => ['eng' => 'Doomed'], 'group_service_family' => 'massage']);

        Livewire::actingAs($user)
            ->test(ServiceIndex::class)
            ->call('deleteRecord', (string) $service->getKey());

        $this->assertSame(0, Service::query()->count());
    }

    public function test_duplicate_slug_is_rejected(): void
    {
        $user = $this->editor();
        Service::query()->create(['service_slug' => 'thai-massage', 'service_name' => ['eng' => 'Thai'], 'group_service_family' => 'massage']);

        Livewire::actingAs($user)
            ->test(ServiceForm::class)
            ->set('state.english_name', 'Another')
            ->set('state.service_slug', 'thai-massage')
            ->set('state.group_service_family', 'massage')
            ->call('save')
            ->assertHasErrors(['state.service_slug']);

        $this->assertSame(1, Service::query()->count());
    }
}
