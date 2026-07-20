<?php

namespace App\Support\Directory;

final class PublicContactLink
{
    /**
     * Remove malformed or unsafe public contact actions and identify links
     * that should open in a separate browser context.
     *
     * @param  array<int, array<string, mixed>>  $channels
     * @return array<int, array<string, mixed>>
     */
    public function present(array $channels): array
    {
        $presented = [];

        foreach ($channels as $channel) {
            $url = trim((string) ($channel['contact_url'] ?? ''));

            if (! $this->allows($url)) {
                continue;
            }

            $channel['contact_url'] = $url;
            $channel['is_external'] = str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
            $presented[] = $channel;
        }

        return $presented;
    }

    public function allows(string $url): bool
    {
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        if (in_array($scheme, ['http', 'https'], true)) {
            return filter_var($url, FILTER_VALIDATE_URL) !== false;
        }

        if ($scheme === 'tel' || $scheme === 'sms') {
            return preg_match('/^(?:tel|sms):\+?[0-9]{7,15}$/', $url) === 1;
        }

        if ($scheme === 'mailto') {
            return filter_var(substr($url, 7), FILTER_VALIDATE_EMAIL) !== false;
        }

        return false;
    }
}
