<?php

namespace App\Http\Middleware;

use App\Services\UserSessionManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforceUserSession
{
    public function __construct(private readonly UserSessionManager $sessions) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ($record = $this->sessions->current($request))) {
            if ($record->status_user_session !== 'ACT' || ($record->expires_at && $record->expires_at->isPast())) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors(['identifier' => __('user.session_ended')]);
            }

            if (! $record->last_activity_at || $record->last_activity_at->lt(now()->subMinutes(5))) {
                $record->forceFill(['last_activity_at' => now()])->save();
            }
        }

        return $next($request);
    }
}
