<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\NotCompromisedPassword;
use App\Rules\NotObviousPassword;
use App\Rules\ProtectedUsername;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Advisory client-side availability check. This never replaces the
     * authoritative uniqueness check performed in store() — it only lets
     * the form give the user a faster hint before they submit.
     */
    public function checkUsername(Request $request): JsonResponse
    {
        $username = Str::lower((string) $request->query('username', ''));

        $validator = Validator::make(['username' => $username], [
            'username' => [
                'required',
                'string',
                'regex:/^[a-z][a-z0-9]{3,29}$/',
                Rule::unique(User::class, 'username'),
                new ProtectedUsername,
            ],
        ], [
            'username.regex' => __('auth.username_hint'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'available' => false,
                'message' => $validator->errors()->first('username'),
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => __('auth.username_available'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Normalize before validation so the uniqueness check and the
        // stored value agree; email matching stays case-insensitive.
        $request->merge(['email' => Str::lower((string) $request->input('email'))]);

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'regex:/^[a-z][a-z0-9]{3,29}$/',
                Rule::unique(User::class, 'username'),
                new ProtectedUsername,
            ],
            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                Rule::unique(User::class, 'email'),
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(15)->max(128),
                new NotCompromisedPassword,
                new NotObviousPassword($request->string('username')->toString(), $request->string('email')->toString()),
            ],
            'birth_date' => [
                'required',
                'date',
                'before_or_equal:'.now()->subYears(18)->toDateString(),
                'after:1900-01-01',
            ],
            'terms_accepted' => ['accepted'],
            'marketing_opt_in' => ['sometimes', 'boolean'],
        ], [
            'username.regex' => __('auth.username_hint'),
            'email.unique' => __('auth.email_already_registered'),
            'birth_date.before_or_equal' => __('auth.birth_date_too_young'),
            'terms_accepted.accepted' => __('auth.terms_must_accept'),
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'birth_date' => $validated['birth_date'],
            'terms_accepted_at' => now(),
            'terms_accepted_version' => config('legal.terms_version'),
            'privacy_acknowledged_at' => now(),
            'privacy_acknowledged_version' => config('legal.privacy_version'),
            'is_marketing_email_opt_in' => $request->boolean('marketing_opt_in'),
        ]);

        $user->sendEmailVerificationNotification();

        // The account is created with pending_email_verification status and
        // must not be logged in until the email is verified (accounts spec
        // section 6), so we redirect to the notice page instead of the
        // authenticated area.
        return redirect()
            ->route('verification.notice')
            ->with('registered_email', $user->email);
    }
}
