<?php

namespace Tests\Feature\Editorial;

use App\Models\AccessAssignment;
use App\Models\User;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class EditorialAccessTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function setUp(): void
    {
        parent::setUp();
        AccessAssignment::query()->delete();
    }

    protected function tearDown(): void
    {
        AccessAssignment::query()->delete();
        parent::tearDown();
    }

    private function grantEditorial(User $user): void
    {
        AccessAssignment::query()->create([
            'user_id' => (string) $user->getKey(),
            'role_workspace' => 'EAD',
            'scope_access' => 'GBL',
            'status_access_assignment' => 'ACT',
            'effective_at' => now()->subMinute(),
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/workspace/editorial')->assertRedirect('/login');
    }

    public function test_member_without_permission_is_forbidden(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/workspace/editorial')->assertForbidden();
    }

    public function test_editor_sees_landing_page_with_collection_cards(): void
    {
        $user = User::factory()->create();
        $this->grantEditorial($user);

        $this->actingAs($user)->get('/workspace/editorial')
            ->assertOk()
            ->assertSee(__('editorial.title'))
            ->assertSee(route('workspace.editorial.establishment.index', [], false), false)
            ->assertSee(route('workspace.editorial.service.index', [], false), false)
            ->assertSee(route('workspace.editorial.quote.index', [], false), false);
    }
}
