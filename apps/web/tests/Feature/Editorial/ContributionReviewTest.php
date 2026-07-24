<?php

namespace Tests\Feature\Editorial;

use App\Models\Contribution;
use App\Models\User;
use App\Models\UserAccess;
use Livewire\Livewire;
use App\Livewire\Workspace\Editorial\ContributionIndex;
use Tests\TestCase;

class ContributionReviewTest extends TestCase
{
    protected function tearDown(): void
    {
        Contribution::query()->delete();
        UserAccess::query()->delete();
        parent::tearDown();
    }

    private function editorialUser(): User
    {
        $user = User::factory()->create();
        UserAccess::query()->create([
            'user_id' => (string) $user->getKey(),
            'role_workspace' => 'EAD',
            'permission_code_list' => [],
            'scope_access' => 'GBL',
            'status_user_access' => 'ACT',
            'assigned_by_user_id' => (string) $user->getKey(),
            'assignment_reason' => 'Contribution review test.',
        ]);

        return $user;
    }

    private function pendingContribution(string $displayName = 'Test Spa'): Contribution
    {
        return Contribution::query()->create([
            'type_contribution' => 'ADD',
            'target_collection' => 'establishment_main',
            'submitted_by_user_id' => (string) User::factory()->create()->getKey(),
            'proposed_data' => ['establishment' => ['display_name' => ['eng' => ['text' => $displayName, 'method_translation' => 'HUM', 'status_review' => 'P']]]],
            'status_contribution' => 'PND',
            'submitted_at' => now(),
        ]);
    }

    public function test_non_editorial_user_cannot_access_the_contribution_list(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/workspace/editorial/contribution')
            ->assertForbidden();
    }

    public function test_editorial_user_sees_pending_contributions_only(): void
    {
        $pending = $this->pendingContribution('Pending Spa');
        $decided = $this->pendingContribution('Already Decided Spa');
        $decided->forceFill(['status_contribution' => 'APR'])->save();

        Livewire::actingAs($this->editorialUser())
            ->test(ContributionIndex::class)
            ->assertSee('Pending Spa')
            ->assertSee((string) $pending->getKey())
            ->assertDontSee('Already Decided Spa');
    }
}
