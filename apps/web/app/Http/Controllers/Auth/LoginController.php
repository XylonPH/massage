<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    private const MAX_ATTEMPTS = 5;

    private const DECAY_SECONDS = 60;

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::lower($validated['identifier']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            throw ValidationException::withMessages([
                'identifier' => __('auth.too_many_attempts', ['seconds' => RateLimiter::availableIn($throttleKey)]),
            ]);
        }

        $field = str_contains($validated['identifier'], '@') ? 'email' : 'username';

        $credentials = [
            $field => Str::lower($validated['identifier']),
            'password' => $validated['password'],
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, self::DECAY_SECONDS);

            throw ValidationException::withMessages([
                'identifier' => __('auth.invalid_credentials'),
            ]);
        }

        RateLimiter::clear($throttleKey);

        $user = $request->user();

        if (! $user->hasVerifiedEmail()) {
            Auth::logout();
            $user->sendEmailVerificationNotification();

            return redirect()
                ->route('login')
                ->withErrors(['identifier' => __('auth.account_not_verified', ['email' => $user->email])]);
        }

        if (! $user->isActive()) {
            Auth::logout();

            return redirect()
                ->route('login')
                ->withErrors(['identifier' => __('auth.account_not_active')]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }
}
