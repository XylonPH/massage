<?php

namespace App\Http\Controllers\Web\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Establishment;
use App\Models\Practitioner;
use App\Support\Workspace\WorkspaceAccess;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Operator and practitioner managed-listing areas
 * (/workspace/listing/spa and /workspace/listing/therapist per the Site
 * Structure Map section 35). Management authority comes from the claim
 * workflow and explicit scoped access assignments. A person may manage
 * several establishments without selecting a separate role context.
 */
class ListingController extends Controller
{
    public function spaIndex(Request $request, WorkspaceAccess $workspaceAccess): View
    {
        $establishmentIds = $workspaceAccess->scopedRecordIds(
            $request->user(),
            'establishment.manage',
            'EST',
        );

        return view('workspace.listing.spa', [
            'establishments' => Establishment::query()->whereIn('_id', $establishmentIds)->get(),
        ]);
    }

    public function therapistIndex(Request $request, WorkspaceAccess $workspaceAccess): View
    {
        $practitionerIds = $workspaceAccess->scopedRecordIds(
            $request->user(),
            'practitioner.manage',
            'PRA',
        );

        return view('workspace.listing.therapist', [
            'practitioners' => Practitioner::query()->whereIn('_id', $practitionerIds)->get(),
        ]);
    }
}
