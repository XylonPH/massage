<?php

namespace App\Support\Review;

use App\Support\Demo\SampleContent;

class ReviewTarget
{
    public const ESTABLISHMENT = 'establishment_main';

    public const PRACTITIONER = 'practitioner_main';

    /** @return array<string, mixed>|null */
    public function spa(string $slug): ?array
    {
        $profile = SampleContent::spaProfile($slug);

        return $profile ? $this->shape(self::ESTABLISHMENT, $profile) : null;
    }

    /** @return array<string, mixed>|null */
    public function therapist(string $slug): ?array
    {
        $profile = SampleContent::therapistProfile($slug);

        return $profile ? $this->shape(self::PRACTITIONER, $profile) : null;
    }

    /** @return array<string, mixed>|null */
    public function byReference(string $collection, string $id): ?array
    {
        $slugs = $collection === self::ESTABLISHMENT
            ? ['the-resting-leaf']
            : ['maya-santos', 'dennis-aquino'];

        foreach ($slugs as $slug) {
            $target = $collection === self::ESTABLISHMENT ? $this->spa($slug) : $this->therapist($slug);

            if (($target['id'] ?? null) === $id) {
                return $target;
            }
        }

        return null;
    }

    /** @return array<string, string> */
    public function criteria(string $collection): array
    {
        return $collection === self::ESTABLISHMENT
            ? ['CLN' => 'Cleanliness', 'CFT' => 'Comfort', 'PRV' => 'Privacy', 'SRV' => 'Service', 'VAL' => 'Value']
            : ['TEC' => 'Technique', 'PRS' => 'Pressure', 'PRO' => 'Professionalism', 'COM' => 'Communication', 'REL' => 'Reliability'];
    }

    /** @param array<string, mixed> $profile @return array<string, mixed> */
    private function shape(string $collection, array $profile): array
    {
        $route = $collection === self::ESTABLISHMENT ? 'spa.show' : 'therapist.show';
        $parameter = $collection === self::ESTABLISHMENT ? 'establishment_slug' : 'therapist_slug';

        return [
            'id' => $profile['id'],
            'collection' => $collection,
            'slug' => $profile['slug'],
            'name' => $profile['name'],
            'route' => route($route, [$parameter => $profile['slug']]),
            'kind' => $collection === self::ESTABLISHMENT ? 'spa' : 'therapist',
            'criteria' => $this->criteria($collection),
        ];
    }
}
