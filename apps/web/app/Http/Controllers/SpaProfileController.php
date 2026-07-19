<?php

namespace App\Http\Controllers;

use App\Support\Demo\SampleContent;
use Illuminate\View\View;

class SpaProfileController extends Controller
{
    public function show(string $slug): View
    {
        $spa = SampleContent::spaProfile($slug);

        abort_if($spa === null, 404);

        return view('spa.profile', ['spa' => $spa]);
    }
}
