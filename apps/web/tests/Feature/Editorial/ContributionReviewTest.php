<?php

namespace Tests\Feature\Editorial;

use App\Models\Contribution;
use App\Models\Establishment;
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
        Establishment::query()->delete();
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

    private function pendingPractitionerContribution(string $practitionerName = 'Test Practitioner'): Contribution
    {
        return Contribution::query()->create([
            'type_contribution' => 'ADD',
            'target_collection' => 'practitioner_main',
            'submitted_by_user_id' => (string) User::factory()->create()->getKey(),
            'proposed_data' => ['practitioner_name' => ['eng' => ['text' => $practitionerName, 'method_translation' => 'HUM', 'status_review' => 'P']]],
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

    public function test_approving_promotes_the_contribution_and_records_the_decision(): void
    {
        $reviewer = $this->editorialUser();
        $contribution = $this->pendingContribution();

        Livewire::actingAs($reviewer)
            ->test(\App\Livewire\Workspace\Editorial\ContributionReview::class, ['contribution' => (string) $contribution->getKey()])
            ->call('requestApproval')
            ->set('approvalConfirmed', true)
            ->call('approve')
            ->assertRedirect(route('workspace.editorial.contribution.index'));

        $contribution->refresh();
        $this->assertSame('APR', $contribution->status_contribution);
        $this->assertNotNull($contribution->reviewed_at);
        $this->assertSame((string) $reviewer->getKey(), $contribution->reviewer_user_id);
        $this->assertSame(1, \App\Models\Establishment::query()->where('display_name.eng', 'Test Spa')->count());
    }

    public function test_rejecting_requires_a_decision_note_and_creates_no_establishment(): void
    {
        $reviewer = $this->editorialUser();
        $contribution = $this->pendingContribution();

        Livewire::actingAs($reviewer)
            ->test(\App\Livewire\Workspace\Editorial\ContributionReview::class, ['contribution' => (string) $contribution->getKey()])
            ->call('reject')
            ->assertHasErrors('decisionNote');

        $this->assertSame(0, \App\Models\Establishment::query()->count());
    }

    public function test_rejecting_with_a_reason_records_the_decision(): void
    {
        $reviewer = $this->editorialUser();
        $contribution = $this->pendingContribution();

        Livewire::actingAs($reviewer)
            ->test(\App\Livewire\Workspace\Editorial\ContributionReview::class, ['contribution' => (string) $contribution->getKey()])
            ->set('decisionNote', 'Missing verifiable contact information.')
            ->call('reject')
            ->assertRedirect(route('workspace.editorial.contribution.index'));

        $contribution->refresh();
        $this->assertSame('REJ', $contribution->status_contribution);
        $this->assertSame('Missing verifiable contact information.', $contribution->decision_note);
        $this->assertSame(0, \App\Models\Establishment::query()->count());
    }

    public function test_editorial_dashboard_shows_pending_contribution_count(): void
    {
        $this->pendingContribution();
        $this->pendingContribution();
        $this->pendingContribution();

        $this->actingAs($this->editorialUser())
            ->get('/workspace/editorial')
            ->assertOk()
            // Scoped to the rendered contribution-count card markup itself, not just any "3"
            // on the page (the grid layout classes contain digits like "sm:grid-cols-2").
            ->assertSee('text-3xl font-black text-ink-950 dark:text-ink-50">3</p>', false)
            ->assertSee(route('workspace.editorial.contribution.index'), false);
    }

    public function test_a_non_establishment_contribution_review_page_404s(): void
    {
        $contribution = $this->pendingPractitionerContribution();

        $this->actingAs($this->editorialUser())
            ->get(route('workspace.editorial.contribution.review', $contribution))
            ->assertNotFound();
    }

    public function test_a_non_establishment_contribution_cannot_be_approved(): void
    {
        $reviewer = $this->editorialUser();
        $contribution = $this->pendingPractitionerContribution();
        $this->actingAs($reviewer);

        // Exercised directly against the component (rather than through Livewire::test's
        // full request/snapshot cycle) so we can assert on the underlying HTTP exception the
        // establishment-only guard raises before any promotion side effect can occur.
        $component = new \App\Livewire\Workspace\Editorial\ContributionReview();
        $component->mount((string) $contribution->getKey());
        $component->showApprovalConfirmation = true;
        $component->approvalConfirmed = true;

        try {
            $component->approve();
            $this->fail('Expected a 404 response for a non-establishment contribution.');
        } catch (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception) {
            // Expected: the establishment-only review UI must refuse to touch this record at all.
        }

        $this->assertSame(0, Establishment::query()->count());
        $contribution->refresh();
        $this->assertSame('PND', $contribution->status_contribution);
    }

    public function test_a_decided_contribution_cannot_be_decided_again(): void
    {
        $reviewer = $this->editorialUser();
        $contribution = $this->pendingContribution();
        $contribution->forceFill(['status_contribution' => 'APR'])->save();

        Livewire::actingAs($reviewer)
            ->test(\App\Livewire\Workspace\Editorial\ContributionReview::class, ['contribution' => (string) $contribution->getKey()])
            ->set('decisionNote', 'Too late.')
            ->call('reject')
            ->assertStatus(409);
    }
}
