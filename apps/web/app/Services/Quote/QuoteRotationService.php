<?php

namespace App\Services\Quote;

use App\Models\Quote;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class QuoteRotationService
{
    /**
     * Resolves the Quote of the Day for a given placement, locale, and calendar date.
     *
     * @return array<string, mixed>|null Returns array formatted for QuotePanel or null if no eligible quotes exist.
     */
    public function resolveDailyQuote(string $placement = 'home', ?string $locale = null, ?string $dateString = null): ?array
    {
        $locale = $locale ? strtolower($locale) : strtolower(app()->getLocale());
        $dateString ??= now()->format('Y-m-d');

        $cacheKey = "quote:daily:{$placement}:{$locale}:{$dateString}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($placement, $locale, $dateString): ?array {
            /** @var Collection<int, Quote> $eligibleQuotes */
            $eligibleQuotes = Quote::query()
                ->eligiblePublic()
                ->get()
                ->sortBy('_id')
                ->values();

            if ($eligibleQuotes->isEmpty()) {
                return null;
            }

            // Standardize locale lookup key
            $langKey = match ($locale) {
                'zh-cn', 'zho-hans', 'zh-hans' => 'zho-hans',
                'zh-tw', 'zho-hant', 'zh-hant' => 'zho-hant',
                default => strtolower(substr($locale, 0, 3)),
            };

            // Phase 1: Try matching original-language quotes
            $matchingOriginals = $eligibleQuotes->filter(function (Quote $quote) use ($langKey): bool {
                $orig = $quote->original_language_key;

                return $orig === $langKey || str_starts_with($orig, $langKey) || str_starts_with($langKey, $orig);
            })->values();

            $selectedQuote = null;
            if ($matchingOriginals->isNotEmpty()) {
                $selectedIndex = $this->calculateDeterministicIndex($dateString, $placement, $locale, $matchingOriginals->count());
                $selectedQuote = $matchingOriginals->get($selectedIndex);
            }

            // Phase 2: Try quotes with an approved translation for requested locale
            if (! $selectedQuote) {
                $matchingTranslations = $eligibleQuotes->filter(function (Quote $quote) use ($langKey): bool {
                    return isset($quote->quote_text[$langKey]['text']) && filled($quote->quote_text[$langKey]['text']);
                })->values();

                if ($matchingTranslations->isNotEmpty()) {
                    $selectedIndex = $this->calculateDeterministicIndex($dateString, $placement, $locale, $matchingTranslations->count());
                    $selectedQuote = $matchingTranslations->get($selectedIndex);
                }
            }

            // Phase 3: Fallback to any eligible quote
            if (! $selectedQuote) {
                $selectedIndex = $this->calculateDeterministicIndex($dateString, $placement, $locale, $eligibleQuotes->count());
                $selectedQuote = $eligibleQuotes->get($selectedIndex);
            }

            if (! $selectedQuote) {
                return null;
            }

            $resolved = $selectedQuote->getResolvedDisplay($locale);

            return [
                'id' => (string) $selectedQuote->getKey(),
                'text' => $resolved['text'],
                'attribution_label' => $selectedQuote->attribution_label,
                'source_title' => $selectedQuote->source_title,
                'source_url' => $selectedQuote->source_url,
                'category' => $selectedQuote->category_enum,
                'language_key' => $resolved['language_key'],
                'is_original' => $resolved['is_original'],
                'original_text' => $resolved['original_text'],
                'original_language_key' => $resolved['original_language_key'],
            ];
        });
    }

    /**
     * Clear all cached daily quotes.
     */
    public function clearCache(?string $dateString = null): void
    {
        $targetDate = $dateString ?? now()->format('Y-m-d');
        foreach (['eng', 'fil', 'ceb', 'kor', 'spa', 'zho-hans', 'zho-hant'] as $lang) {
            Cache::forget("quote:daily:home:{$lang}:{$targetDate}");
        }

        if (method_exists(Cache::store(), 'flush')) {
            try {
                Cache::tags(['quotes'])->flush();
            } catch (\Throwable $e) {
                // Ignore if cache driver does not support tags
            }
        }
    }

    /**
     * Calculates a deterministic index in the range [0, poolSize - 1] based on date, placement, locale.
     */
    private function calculateDeterministicIndex(string $dateString, string $placement, string $locale, int $poolSize): int
    {
        if ($poolSize <= 1) {
            return 0;
        }

        $hashString = "{$dateString}:{$placement}:{$locale}";
        $hashValue = abs(crc32($hashString));

        return $hashValue % $poolSize;
    }
}
