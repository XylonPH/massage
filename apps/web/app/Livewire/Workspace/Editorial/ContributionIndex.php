<?php

namespace App\Livewire\Workspace\Editorial;

use App\Models\Contribution;
use App\Support\Workspace\WorkspaceAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class ContributionIndex extends Component
{
    use WithPagination;

    public function mount(): void
    {
        $this->authorizeEditorialAccess();
    }

    private function authorizeEditorialAccess(): void
    {
        $user = Auth::user();
        abort_unless($user && app(WorkspaceAccess::class)->can($user, 'workspace.editorial.access'), 403);
    }

    public function render(): View
    {
        $contributions = Contribution::query()
            ->where('status_contribution', 'PND')
            ->where('type_contribution', 'ADD')
            ->where('target_collection', 'establishment_main')
            ->orderByDesc('submitted_at')
            ->paginate(15);

        return view('livewire.workspace.editorial.contribution-index', [
            'contributions' => $contributions,
        ])->title(__('editorial.contribution_review_title'));
    }
}
