<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Rejects usernames that imply platform authority, ownership, administration,
 * moderation, security, billing, legal authority, official brand status, or
 * system operation, per docs/07-accounts/account-and-authentication-system.txt
 * sections 4.4 and 4.5. This is a curated starting registry, not the full
 * "maintained protected-name registry" the spec anticipates long-term.
 */
class ProtectedUsername implements ValidationRule
{
    /**
     * Long/specific enough roots that a substring match stays low-risk of
     * blocking a legitimate unrelated username.
     *
     * @var list<string>
     */
    private const HIGH_RISK_CONTAINS = [
        'admin',
        'moderator',
        'support',
        'staff',
        'official',
        'security',
        'webmaster',
        'postmaster',
        'massagenexus',
        'nexus',
        'superuser',
        'founder',
        'owner',
        'operator',
        'system',
        'root',
        'abuse',
    ];

    /**
     * Short or common words that are only rejected as a whole normalized
     * username (after stripping a trailing numeric suffix), not as a
     * substring, to avoid rejecting unrelated usernames that merely contain
     * these letters.
     *
     * @var list<string>
     */
    private const EXACT_MATCH_ONLY = [
        'mod', 'mods', 'moderation',
        'account', 'accounts', 'login', 'signin', 'signup', 'register',
        'api', 'mail', 'email', 'www',
        'billing', 'payment', 'legal', 'privacy', 'team', 'help', 'helpdesk', 'supporter',
        'spa', 'directory', 'workspace', 'campus', 'promo', 'promos', 'search',
    ];

    private const LEETSPEAK_MAP = [
        '0' => 'o',
        '1' => 'i',
        '3' => 'e',
        '4' => 'a',
        '5' => 's',
        '7' => 't',
        '@' => 'a',
        '$' => 's',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        $normalized = strtr(strtolower($value), self::LEETSPEAK_MAP);

        foreach (self::HIGH_RISK_CONTAINS as $root) {
            if (str_contains($normalized, $root)) {
                $fail(__('auth.username_reserved'));

                return;
            }
        }

        $withoutSuffix = preg_replace('/[0-9]+$/', '', $normalized);

        if (in_array($withoutSuffix, self::EXACT_MATCH_ONLY, true)) {
            $fail(__('auth.username_reserved'));
        }
    }
}
