<?php

namespace App\Http\Controllers\Web\Workspace;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(Request $request): View
    {
        return view('workspace.setting', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'marketing_opt_in' => ['sometimes', 'boolean'],
        ]);

        // Marketing consent stays a separate, revocable decision; the versioned
        // grant/withdrawal history belongs to user_policy once that exists.
        $request->user()->forceFill([
            'is_marketing_email_opt_in' => $request->boolean('marketing_opt_in'),
        ])->save();

        return redirect()
            ->route('workspace.setting.edit')
            ->with('status', __('workspace.settings_saved'));
    }
}
