<?php

namespace App\Http\Controllers\Web\Workspace;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('workspace.profile', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'display_name' => ['nullable', 'string', 'max:60'],
            'profile_biography' => ['nullable', 'string', 'max:1000'],
        ]);

        $request->user()->forceFill([
            'display_name' => $validated['display_name'] ?: null,
            'profile_biography' => $validated['profile_biography'] ?: null,
        ])->save();

        return redirect()
            ->route('workspace.profile.edit')
            ->with('status', __('workspace.profile_saved'));
    }
}
