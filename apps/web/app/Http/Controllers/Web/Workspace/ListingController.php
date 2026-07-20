<?php

namespace App\Http\Controllers\Web\Workspace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Operator and practitioner managed-listing areas
 * (/workspace/listing/spa and /workspace/listing/therapist per the Site
 * Structure Map section 35). Management authority comes from the claim
 * workflow; until claim records exist, every member sees the honest empty
 * state with the claim route as the way in, matching the operator and
 * therapist workspace specifications' access model.
 */
class ListingController extends Controller
{
    public function spaIndex(Request $request): View
    {
        return view('workspace.listing.spa', [
            'establishments' => [],
        ]);
    }

    public function therapistIndex(Request $request): View
    {
        return view('workspace.listing.therapist', [
            'practitioners' => [],
        ]);
    }
}
