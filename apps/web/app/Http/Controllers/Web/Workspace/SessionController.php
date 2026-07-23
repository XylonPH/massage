<?php

namespace App\Http\Controllers\Web\Workspace;

use App\Http\Controllers\Controller;
use App\Models\UserDevice;
use App\Models\UserSession;
use App\Services\UserSessionManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function destroy(Request $request, UserSession $session, UserSessionManager $manager): RedirectResponse
    {
        abort_unless((string) $session->user_id === (string) $request->user()->getKey(), 404);
        $isCurrent = $manager->current($request)?->is($session) ?? false;
        $manager->revoke($session, (string) $request->user()->getKey());

        if ($isCurrent) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('home');
        }

        return back()->with('status', __('user.session_revoked'));
    }

    public function destroyOthers(Request $request, UserSessionManager $manager): RedirectResponse
    {
        $currentId = $manager->current($request)?->getKey();
        UserSession::where('user_id', (string) $request->user()->getKey())
            ->where('status_user_session', 'ACT')->get()
            ->reject(fn (UserSession $session) => $session->getKey() === $currentId)
            ->each(fn (UserSession $session) => $manager->revoke($session, (string) $request->user()->getKey()));

        return back()->with('status', __('user.other_sessions_revoked'));
    }

    public function updateDevice(Request $request, UserDevice $device): RedirectResponse
    {
        abort_unless((string) $device->user_id === (string) $request->user()->getKey(), 404);
        $validated = $request->validate(['device_name' => ['nullable', 'string', 'max:80']]);
        $device->forceFill(['device_name' => $validated['device_name'] ?: null, 'is_recognized' => true, 'revision_number' => ((int) $device->revision_number) + 1])->save();

        return back()->with('status', __('user.device_recognized'));
    }

    public function distrustDevice(Request $request, UserDevice $device, UserSessionManager $manager): RedirectResponse
    {
        abort_unless((string) $device->user_id === (string) $request->user()->getKey(), 404);
        $device->forceFill(['is_recognized' => false, 'status_user_device' => 'DST', 'distrusted_at' => now(), 'revision_number' => ((int) $device->revision_number) + 1])->save();
        UserSession::where('user_device_id', (string) $device->getKey())->where('status_user_session', 'ACT')->get()
            ->each(fn (UserSession $session) => $manager->revoke($session, (string) $request->user()->getKey()));

        return back()->with('status', __('user.device_distrusted'));
    }
}
