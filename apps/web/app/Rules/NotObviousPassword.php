<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Rejects passwords that equal or substantially reproduce the username or
 * email, use only trivial repetition or sequences, or match an obvious
 * Massage Nexus password pattern, per docs/07-accounts/account-identity-
 * registration-and-authentication-system.txt section 5.2. This runs
 * alongside Laravel's Password::min(15)->uncompromised() rule rather than
 * replacing it.
 */
class NotObviousPassword implements ValidationRule
{
    /** @var list<string> */
    private const BLOCKED_SUBSTRINGS = [
        'massagenexus',
        'passwordpassword',
        'adminpassword',
    ];

    public function __construct(
        private readonly ?string $username = null,
        private readonly ?string $email = null,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        $normalized = strtolower(preg_replace('/[^a-z0-9]/i', '', $value) ?? '');

        foreach (self::BLOCKED_SUBSTRINGS as $blocked) {
            if (str_contains($normalized, $blocked)) {
                $fail(__('auth.password_obvious_pattern'));

                return;
            }
        }

        if ($normalized !== '' && preg_match('/^(.)\1*$/', $normalized) === 1) {
            $fail(__('auth.password_obvious_pattern'));

            return;
        }

        if ($this->isTrivialSequence($normalized)) {
            $fail(__('auth.password_obvious_pattern'));

            return;
        }

        foreach ([$this->username, $this->localPartOf($this->email)] as $identity) {
            if ($identity === null || strlen($identity) < 4) {
                continue;
            }

            $identityNormalized = strtolower($identity);

            if (str_contains($normalized, $identityNormalized) || str_contains($identityNormalized, $normalized)) {
                $fail(__('auth.password_matches_identity'));

                return;
            }
        }
    }

    private function isTrivialSequence(string $normalized): bool
    {
        if (strlen($normalized) < 6) {
            return false;
        }

        $ascending = true;
        $descending = true;

        for ($i = 1; $i < strlen($normalized); $i++) {
            $diff = ord($normalized[$i]) - ord($normalized[$i - 1]);
            $ascending = $ascending && $diff === 1;
            $descending = $descending && $diff === -1;
        }

        return $ascending || $descending;
    }

    private function localPartOf(?string $email): ?string
    {
        if ($email === null || ! str_contains($email, '@')) {
            return null;
        }

        return strstr($email, '@', true) ?: null;
    }
}
