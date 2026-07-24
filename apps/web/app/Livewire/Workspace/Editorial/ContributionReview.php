<?php

namespace App\Livewire\Workspace\Editorial;

use App\Actions\Contribution\PromoteContributionToEstablishment;
use App\Models\Contribution;
use App\Support\Workspace\WorkspaceAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class ContributionReview extends Component
{
    public string $contribution;

    public string $decisionNote = '';

    public bool $showApprovalConfirmation = false;

    public bool $approvalConfirmed = false;

    public function mount(string $contribution): void
    {
        $this->authorizeEditorialAccess();
        $this->contribution = $contribution;
    }

    private function authorizeEditorialAccess(): void
    {
        $user = Auth::user();
        abort_unless($user && app(WorkspaceAccess::class)->can($user, 'workspace.editorial.access'), 403);
    }

    private function pendingContribution(): Contribution
    {
        $contribution = Contribution::query()->findOrFail($this->contribution);
        abort_unless($contribution->status_contribution === 'PND', 409, __('editorial.contribution_already_decided'));

        return $contribution;
    }

    public function requestApproval(): void
    {
        $this->authorizeEditorialAccess();
        $this->pendingContribution();

        $this->resetErrorBag('approvalConfirmed');
        $this->approvalConfirmed = false;
        $this->showApprovalConfirmation = true;
    }

    public function cancelApproval(): void
    {
        $this->resetErrorBag('approvalConfirmed');
        $this->approvalConfirmed = false;
        $this->showApprovalConfirmation = false;
    }

    public function approve(): void
    {
        if (! $this->showApprovalConfirmation || ! $this->approvalConfirmed) {
            $this->addError('approvalConfirmed', __('editorial.approval_confirmation_required'));

            return;
        }

        $this->authorizeEditorialAccess();
        $contribution = $this->pendingContribution();

        (new PromoteContributionToEstablishment)->execute($contribution);

        $contribution->forceFill([
            'status_contribution' => 'APR',
            'decision_note' => trim($this->decisionNote) ?: null,
            'reviewed_at' => now(),
            'reviewer_user_id' => (string) Auth::id(),
        ])->save();

        session()->flash('editorial_status', __('editorial.contribution_approved'));
        $this->redirectRoute('workspace.editorial.contribution.index', navigate: true);
    }

    public function reject(): void
    {
        $this->validate(['decisionNote' => ['required', 'string', 'max:2000']]);

        $this->authorizeEditorialAccess();
        $contribution = $this->pendingContribution();

        $contribution->forceFill([
            'status_contribution' => 'REJ',
            'decision_note' => trim($this->decisionNote),
            'reviewed_at' => now(),
            'reviewer_user_id' => (string) Auth::id(),
        ])->save();

        session()->flash('editorial_status', __('editorial.contribution_rejected'));
        $this->redirectRoute('workspace.editorial.contribution.index', navigate: true);
    }

    public function render(): View
    {
        $contribution = Contribution::query()->findOrFail($this->contribution);

        return view('livewire.workspace.editorial.contribution-review', [
            'record' => $contribution,
        ])->title(__('editorial.contribution_review_title'));
    }
}
