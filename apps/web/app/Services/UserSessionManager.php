<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDevice;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class UserSessionManager
{
    private const DEVICE_COOKIE = 'mn_device';

    public function recordLogin(Request $request, User $user): void
    {
        $device = $this->deviceFor($request, $user);
        $now = now();

        $sessionRecord = UserSession::create([
            'user_id' => (string) $user->getKey(),
            'user_device_id' => (string) $device->getKey(),
            'session_secret_hash' => $this->hash($request->session()->getId()),
            'type_authentication_method' => 'PWD',
            'ip_address' => $request->ip(),
            'user_agent_summary' => $this->userAgentSummary($request),
            'approximate_location' => null,
            'status_user_session' => 'ACT',
            'authenticated_at' => $now,
            'last_activity_at' => $now,
            'expires_at' => $request->boolean('remember')
                ? $now->copy()->addDays(30)
                : $now->copy()->addMinutes((int) config('session.lifetime', 120)),
            'created_at' => $now,
        ]);

        $request->session()->put('mn_user_session_id', (string) $sessionRecord->getKey());

        Cookie::queue(self::DEVICE_COOKIE, (string) $device->getKey(), 60 * 24 * 365, '/', null, null, true, false, 'lax');
    }

    public function current(Request $request): ?UserSession
    {
        if ($recordId = $request->session()->get('mn_user_session_id')) {
            return UserSession::where('_id', $recordId)
                ->where('user_id', (string) $request->user()?->getKey())
                ->first();
        }

        return UserSession::where('session_secret_hash', $this->hash($request->session()->getId()))
            ->where('user_id', (string) $request->user()?->getKey())
            ->first();
    }

    public function revokeCurrent(Request $request, ?string $actorId = null): void
    {
        if ($record = $this->current($request)) {
            $this->revoke($record, $actorId);
        }
    }

    public function revoke(UserSession $record, ?string $actorId): void
    {
        $record->forceFill([
            'status_user_session' => 'REV',
            'revoked_at' => now(),
            'revoked_by_user_id' => $actorId,
        ])->save();
    }

    public function revokeAllFor(User $user, ?string $actorId = null): void
    {
        UserSession::where('user_id', (string) $user->getKey())
            ->where('status_user_session', 'ACT')->get()
            ->each(fn (UserSession $session) => $this->revoke($session, $actorId));
    }

    public function hash(string $sessionId): string
    {
        return 'sha256:'.hash('sha256', $sessionId);
    }

    private function deviceFor(Request $request, User $user): UserDevice
    {
        $id = $request->cookie(self::DEVICE_COOKIE);
        $device = $id ? UserDevice::where('_id', $id)->where('user_id', (string) $user->getKey())->first() : null;
        $now = now();

        if (! $device || $device->status_user_device !== 'ACT') {
            return UserDevice::create([
                'user_id' => (string) $user->getKey(),
                'platform_summary' => $this->platform($request),
                'browser_summary' => $this->browser($request),
                'first_seen_at' => $now,
                'last_seen_at' => $now,
            ]);
        }

        $device->forceFill(['last_seen_at' => $now])->save();

        return $device;
    }

    private function userAgentSummary(Request $request): string
    {
        return $this->browser($request).' on '.$this->platform($request);
    }

    private function browser(Request $request): string
    {
        $agent = (string) $request->userAgent();

        return match (true) {
            Str::contains($agent, 'Edg/') => 'Edge',
            Str::contains($agent, 'Firefox/') => 'Firefox',
            Str::contains($agent, 'Chrome/') => 'Chrome',
            Str::contains($agent, 'Safari/') => 'Safari',
            default => 'Browser',
        };
    }

    private function platform(Request $request): string
    {
        $agent = (string) $request->userAgent();

        return match (true) {
            Str::contains($agent, 'Windows') => 'Windows',
            Str::contains($agent, ['iPhone', 'iPad']) => 'iOS',
            Str::contains($agent, 'Android') => 'Android',
            Str::contains($agent, 'Macintosh') => 'macOS',
            Str::contains($agent, 'Linux') => 'Linux',
            default => 'Unknown platform',
        };
    }
}
