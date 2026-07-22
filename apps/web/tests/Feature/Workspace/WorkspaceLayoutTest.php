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

    public function test_article_workspace_renders_inside_the_shared_shell(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/workspace/article')
            ->assertOk()
            ->assertSee('data-workspace-shell', false)
            ->assertSee('id="workspace-sidebar"', false)
            ->assertSee(__('article.your_articles'))
            ->assertSee(route('workspace.article.create', [], false), false);
    }
}
