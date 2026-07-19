<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * The real Terms of Use and Privacy Notice documents have not been drafted
 * (see docs/01-project/simple-checklist.txt, section 23). These honest
 * placeholder pages exist so registration can record a real versioned
 * acceptance without fabricating binding legal text.
 */
class LegalController extends Controller
{
    public function terms(): View
    {
        return view('legal.draft', [
            'title' => __('auth.terms_of_use'),
            'version' => config('legal.terms_version'),
            'summary' => __('legal.terms_summary'),
            'points' => __('legal.terms_points'),
        ]);
    }

    public function privacy(): View
    {
        return view('legal.draft', [
            'title' => __('auth.privacy_notice'),
            'version' => config('legal.privacy_version'),
            'summary' => __('legal.privacy_summary'),
            'points' => __('legal.privacy_points'),
        ]);
    }

    public function cookies(): View
    {
        return view('legal.cookies');
    }
}
