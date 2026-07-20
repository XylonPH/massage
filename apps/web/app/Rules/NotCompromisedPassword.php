<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * Checks the password against the Have I Been Pwned k-anonymity range API,
 * per docs/07-accounts/account-and-authentication-system.txt
 * section 5.2 ("appears in an approved compromised-password source").
 *
 * This intentionally fails OPEN: if the check service is unreachable, a
 * transient network problem must not block registration outright. Length,
 * blocklist, and identity-similarity checks (NotObviousPassword) still
 * apply regardless. The failure is logged so a persistently broken check
 * is visible in operations, not silently invisible.
 */
class NotCompromisedPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || $value === '') {
            return;
        }

        $hash = strtoupper(sha1($value));
        $prefix = substr($hash, 0, 5);
        $suffix = substr($hash, 5);

        try {
            $response = Http::timeout(3)->get("https://api.pwnedpasswords.com/range/{$prefix}");
        } catch (Throwable $exception) {
            Log::warning('Compromised-password check unavailable; failing open.', [
                'exception' => $exception->getMessage(),
            ]);

            return;
        }

        if ($response->failed()) {
            Log::warning('Compromised-password check returned an error response; failing open.', [
                'status' => $response->status(),
            ]);

            return;
        }

        foreach (explode("\r\n", $response->body()) as $line) {
            [$candidateSuffix, $count] = array_pad(explode(':', trim($line), 2), 2, '0');

            if (Str::upper($candidateSuffix) === $suffix && (int) $count > 0) {
                $fail(__('auth.password_obvious_pattern'));

                return;
            }
        }
    }
}
