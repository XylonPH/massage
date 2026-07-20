<?php

namespace App\Http\Controllers\Web\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContributionController extends Controller
{
    private const RELATIONSHIP_TYPES = ['NON', 'OWN', 'INV', 'MGR', 'OPR', 'REP'];

    private const PRACTITIONER_RELATIONSHIP_TYPES = ['NON', 'SLF', 'REP'];

    public function index(Request $request): View
    {
        return view('workspace.contribution.index', [
            'contributions' => Contribution::query()
                ->where('submitted_by_user_id', (string) $request->user()->getKey())
                ->orderByDesc('submitted_at')
                ->paginate(15)
                ->withQueryString(),
        ]);
    }

    public function createEstablishment(): View
    {
        return view('workspace.contribution.establishment', [
            'relationshipTypes' => self::RELATIONSHIP_TYPES,
        ]);
    }

    public function storeEstablishment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:160'],
            'address_public' => ['required', 'string', 'max:500'],
            'type_establishment_relationship' => ['required', Rule::in(self::RELATIONSHIP_TYPES)],
            'is_workspace_access_requested' => ['nullable', 'boolean'],
            'relationship_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $requestsAccess = $request->boolean('is_workspace_access_requested');
        if ($requestsAccess && $validated['type_establishment_relationship'] === 'NON') {
            return back()
                ->withInput()
                ->withErrors(['is_workspace_access_requested' => __('workspace.contribution_access_relationship_required')]);
        }

        Contribution::query()->create([
            'type_contribution' => 'ADD',
            'target_collection' => 'establishment_main',
            'target_record_id' => null,
            'submitted_by_user_id' => (string) $request->user()->getKey(),
            'proposed_data' => [
                'display_name' => ['eng' => trim($validated['display_name'])],
                'address_public' => trim($validated['address_public']),
            ],
            'type_establishment_relationship' => $validated['type_establishment_relationship'],
            'is_workspace_access_requested' => $requestsAccess,
            'relationship_note' => filled($validated['relationship_note'] ?? null)
                ? trim($validated['relationship_note'])
                : null,
            'status_contribution' => 'PND',
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('workspace.contribution.index')
            ->with('status', __('workspace.contribution_submitted'));
    }

    public function createPractitioner(): View
    {
        return view('workspace.contribution.practitioner', [
            'relationshipTypes' => self::PRACTITIONER_RELATIONSHIP_TYPES,
        ]);
    }

    public function storePractitioner(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'practitioner_name' => ['required', 'string', 'max:160'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'type_practitioner_relationship' => ['required', Rule::in(self::PRACTITIONER_RELATIONSHIP_TYPES)],
            'is_workspace_access_requested' => ['nullable', 'boolean'],
            'relationship_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $requestsAccess = $request->boolean('is_workspace_access_requested');
        if ($requestsAccess && $validated['type_practitioner_relationship'] === 'NON') {
            return back()
                ->withInput()
                ->withErrors(['is_workspace_access_requested' => __('workspace.contribution_access_relationship_required')]);
        }

        $proposedData = [
            'practitioner_name' => [
                'eng' => [
                    'text' => trim($validated['practitioner_name']),
                    'method_translation' => 'HUM',
                    'status_review' => 'P',
                ],
            ],
        ];

        if (filled($validated['short_description'] ?? null)) {
            $proposedData['short_description'] = [
                'eng' => [
                    'text' => trim($validated['short_description']),
                    'method_translation' => 'HUM',
                    'status_review' => 'P',
                ],
            ];
        }

        Contribution::query()->create([
            'type_contribution' => 'ADD',
            'target_collection' => 'practitioner_main',
            'target_record_id' => null,
            'submitted_by_user_id' => (string) $request->user()->getKey(),
            'proposed_data' => $proposedData,
            'type_practitioner_relationship' => $validated['type_practitioner_relationship'],
            'is_workspace_access_requested' => $requestsAccess,
            'relationship_note' => filled($validated['relationship_note'] ?? null)
                ? trim($validated['relationship_note'])
                : null,
            'status_contribution' => 'PND',
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('workspace.contribution.index')
            ->with('status', __('workspace.contribution_submitted'));
    }
}
