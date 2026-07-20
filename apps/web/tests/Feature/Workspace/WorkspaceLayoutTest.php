<?php

namespace Tests\Feature\Workspace;

use App\Models\User;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class WorkspaceLayoutTest extends TestCase
{
    use InteractsWithMongoUsers;

    public function test_workspace_home_renders_the_workspace_shell(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/workspace/home')
            ->assertOk()
            ->assertSee('data-workspace-shell', false)
            ->assertSee('data-theme-toggle', false)
            ->assertSee('id="workspace-sidebar"', false);
    }
}
