<?php

namespace App\Support\Establishment;

use App\Models\Contribution;
use App\Models\Establishment;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Loads every Establishment and pending establishment Contribution into memory
 * and normalizes/matches in PHP (no DB-side text index exists yet). Acceptable
 * at current data volume; revisit with a text index if the establishment count
 * grows large enough to matter.
 */
class DuplicateEstablishmentFinder
{
    private const MAX_RESULTS = 5;

    /** @return Collection<int, array{id: string, display_name: string, address_public: ?string, source: string}> */
    public function find(string $displayName, ?string $regionLabel = null): Collection
    {
        $normalized = $this->normalize($displayName);

        if ($normalized === '') {
            return collect();
        }

        $liveMatches = Establishment::query()
            ->get(['_id', 'display_name', 'address_public'])
            ->filter(fn (Establishment $establishment) => $this->normalize((string) data_get($establishment->display_name, 'eng', '')) === $normalized)
            ->map(fn (Establishment $establishment) => [
                'id' => (string) $establishment->getKey(),
                'display_name' => (string) data_get($establishment->display_name, 'eng', ''),
                'address_public' => $establishment->address_public,
                'source' => 'establishment',
            ]);

        $pendingMatches = Contribution::query()
            ->where('target_collection', 'establishment_main')
            ->where('status_contribution', 'PND')
            ->get(['_id', 'proposed_data'])
            ->filter(fn (Contribution $contribution) => $this->normalize((string) $this->resolveDisplayName($contribution)) === $normalized)
            ->map(fn (Contribution $contribution) => [
                'id' => (string) $contribution->getKey(),
                'display_name' => (string) $this->resolveDisplayName($contribution),
                'address_public' => data_get($contribution->proposed_data, 'establishment.address_public', data_get($contribution->proposed_data, 'address_public')),
                'source' => 'contribution',
            ]);

        return $liveMatches->concat($pendingMatches)->take(self::MAX_RESULTS)->values();
    }

    /**
     * Resolves a pending contribution's display name across every shape it may be
     * stored in. Tried in newest-to-oldest order so older still-pending contributions
     * keep matching correctly across each schema change:
     *   1. `establishment.display_name.eng.text` — current shape (Task 17+), the
     *      guide's {text, method_translation, status_review} object, namespaced under
     *      'establishment' by Task 15.
     *   2. `establishment.display_name.eng` — Task 15/16 namespaced-but-flat-string
     *      shape (no per-language object wrapper yet).
     *   3. `display_name.eng.text` / `display_name.eng` — pre-Task-15 shapes, not yet
     *      namespaced under 'establishment'.
     * Each candidate is skipped (rather than returned) when it resolves to an array,
     * since that means the path landed one level too shallow for that shape (e.g.
     * path 2 hitting the Task 17 object) instead of a leaf string.
     */
    private function resolveDisplayName(Contribution $contribution): string
    {
        foreach ([
            'establishment.display_name.eng.text',
            'establishment.display_name.eng',
            'display_name.eng.text',
            'display_name.eng',
        ] as $path) {
            $value = data_get($contribution->proposed_data, $path);

            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return '';
    }

    private function normalize(string $name): string
    {
        return Str::of($name)->lower()->replaceMatches('/[^a-z0-9]+/', ' ')->squish()->toString();
    }
}
