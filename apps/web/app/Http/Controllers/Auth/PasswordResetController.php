<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\PasswordResetSuccessful;
use App\Rules\NotCompromisedPassword;
use App\Rules\NotObviousPassword;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => Str::lower(trim((string) $request->input('email'))),
        ]);

        $validated = $request->validate([
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        // A broker lookup is performed for every syntactically valid request
        // so the public response remains the same for unknown, unverified,
        // and recoverable accounts. Only verified accounts receive a token.
        $brokerEmail = $user?->hasVerifiedEmail()
            ? $user->email
            : Str::uuid().'@invalid.local';

        Password::sendResetLink(['email' => $brokerEmail]);

        return back()->with('password_reset_notice', __('auth.password_request_sent'));
    }

    public function edit(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->string('email')->toString(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => Str::lower(trim((string) $request->input('email'))),
        ]);

        $user = User::where('email', $request->string('email')->toString())->first();

        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
            'password' => [
                'required',
                'string',
                'confirmed',
                PasswordRule::min(15)->max(128),
                new NotCompromisedPassword,
                new NotObviousPassword($user?->username, $request->string('email')->toString()),
            ],
        ]);

        $resetUser = null;

        $status = Password::reset(
            $validated,
            function (User $user, string $password) use (&$resetUser): void {
                $user->forceFill(['password' => $password]);
                $user->setRememberToken(Str::random(60));
                $user->save();

                event(new PasswordReset($user));
                $resetUser = $user;
            },
        );

        if ($status !== Password::PASSWORD_RESET || ! $resetUser) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __('auth.password_reset_invalid')]);
        }

        $resetUser->notify(new PasswordResetSuccessful);

        return redirect()
            ->route('login')
            ->with('password_reset_notice', __('auth.password_reset_success'));
    }
}
