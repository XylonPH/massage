<?php

namespace App\Http\Controllers;

use App\Support\Demo\SampleContent;
use Illuminate\View\View;

class TherapistProfileController extends Controller
{
    public function show(string $therapist_slug): View
    {
        $therapist = SampleContent::therapistProfile($therapist_slug);

        abort_if($therapist === null, 404);

        return view('therapist.profile', ['therapist' => $therapist]);
    }
}
