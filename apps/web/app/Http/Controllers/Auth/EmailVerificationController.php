<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Per docs/07-accounts/account-identity-registration-and-authentication-system.txt
 * section 6, an unverified user is never logged in, so verification here is
 * driven entirely by the signed link rather than Laravel's default
 * EmailVerificationRequest (which assumes an authenticated user).
 */
class EmailVerificationController extends Controller
{
    public function notice(): View
    {
        return view('auth.verify-email');
    }

    public function verify(Request $request, string $id, string $hash): RedirectResponse
    {
        $user = User::find($id);

        if (! $user || ! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return redirect()
                ->route('verification.notice')
                ->with('verify_notice', __('auth.verify_email_invalid'));
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()
                ->route('login')
                ->with('verify_notice', __('auth.verify_email_already'));
        }

        $user->markEmailAsVerified();
        $user->forceFill([
            'status_account' => 'ACT',
            'status_membership' => 'ACT',
        ])->save();

        return redirect()
            ->route('login')
            ->with('verify_notice', __('auth.verify_email_success'));
    }

    public function resend(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', $request->string('email')->toString())->first();

        if ($user && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        return redirect()
            ->route('verification.notice')
            ->with('verify_notice', __('auth.verify_email_resend_sent'));
    }
}
