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
     * stored in: the current namespaced+flat-string shape produced by
     * EstablishmentForm::submitContribution() (`establishment.display_name.eng` as a
     * plain string) is tried first since that is what real submissions produce now,
     * falling back to the pre-Task-15 flat shapes for any older pending contribution
     * still sitting in PND status (`display_name.eng.text` or `display_name.eng`).
     */
    private function resolveDisplayName(Contribution $contribution): string
    {
        return (string) data_get(
            $contribution->proposed_data,
            'establishment.display_name.eng',
            data_get($contribution->proposed_data, 'display_name.eng.text', data_get($contribution->proposed_data, 'display_name.eng'))
        );
    }

    private function normalize(string $name): string
    {
        return Str::of($name)->lower()->replaceMatches('/[^a-z0-9]+/', ' ')->squish()->toString();
    }
}
