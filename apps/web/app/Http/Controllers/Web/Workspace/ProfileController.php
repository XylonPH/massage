<?php

namespace App\Http\Controllers\Web\Workspace;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            'display_name' => ['nullable', 'string', 'min:2', 'max:60'],
            'profile_biography' => ['nullable', 'string', 'min:20', 'max:1000'],
            'pronoun_text' => ['nullable', 'string', 'max:40'],
            'gender_identity' => ['nullable', Rule::in(array_keys(config('user.gender_options')))],
            'type_handedness' => ['sometimes', Rule::in(array_keys(config('user.handedness_options')))],
            'visibility_scope' => ['sometimes', Rule::in(['PUB', 'UNL', 'PRV'])],
            'visibility_gender' => ['sometimes', Rule::in(['PUB', 'PRV'])],
            'visibility_handedness' => ['sometimes', Rule::in(['PUB', 'PRV'])],
            'type_birth_date_display' => ['sometimes', Rule::in(['HID', 'AGE', 'MDY'])],
        ]);

        $privacy = array_replace($request->user()->privacy_preference ?? [], [
            'visibility_gender' => $validated['visibility_gender'] ?? ($request->user()->privacy_preference['visibility_gender'] ?? 'PRV'),
            'visibility_handedness' => $validated['visibility_handedness'] ?? ($request->user()->privacy_preference['visibility_handedness'] ?? 'PRV'),
            'type_birth_date_display' => $validated['type_birth_date_display'] ?? ($request->user()->privacy_preference['type_birth_date_display'] ?? 'HID'),
        ]);

        $request->user()->forceFill([
            'display_name' => array_key_exists('display_name', $validated) ? ($validated['display_name'] ?: null) : $request->user()->display_name,
            'profile_biography' => array_key_exists('profile_biography', $validated) ? ($validated['profile_biography'] ?: null) : $request->user()->profile_biography,
            'pronoun_text' => array_key_exists('pronoun_text', $validated) ? ($validated['pronoun_text'] ?: null) : $request->user()->pronoun_text,
            'gender_identity' => array_key_exists('gender_identity', $validated) ? ($validated['gender_identity'] ?: null) : $request->user()->gender_identity,
            'type_handedness' => $validated['type_handedness'] ?? ($request->user()->type_handedness ?: 'UN'),
            'visibility_scope' => $validated['visibility_scope'] ?? ($request->user()->visibility_scope ?: 'PRV'),
            'privacy_preference' => $privacy,
        ])->save();

        return redirect()
            ->route('workspace.profile.edit')
            ->with('status', __('workspace.profile_saved'));
    }
}
