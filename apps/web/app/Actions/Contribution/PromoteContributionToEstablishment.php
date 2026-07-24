<?php

namespace App\Actions\Contribution;

use App\Models\Contribution;
use App\Models\Establishment;

class PromoteContributionToEstablishment
{
    /** proposed_data.establishment key => Establishment model attribute, for the 3 translated fields. */
    private const TRANSLATED_FIELDS = [
        'display_name' => 'display_name',
        'short_description' => 'short_description',
        'full_description' => 'description',
    ];

    public function execute(Contribution $contribution): Establishment
    {
        $attributes = $contribution->proposed_data['establishment'] ?? [];

        foreach (self::TRANSLATED_FIELDS as $proposedKey => $modelField) {
            $translations = $attributes[$proposedKey] ?? [];
            unset($attributes[$proposedKey]);
            $attributes[$modelField] = collect($translations)
                ->map(fn (array $entry): string => (string) ($entry['text'] ?? ''))
                ->all();
        }

        $longitude = $attributes['location_point']['coordinates'][0] ?? null;
        $latitude = $attributes['location_point']['coordinates'][1] ?? null;
        unset($attributes['location_point']);
        if (is_numeric($longitude) && is_numeric($latitude)) {
            $attributes['coordinate_longitude'] = (float) $longitude;
            $attributes['coordinate_latitude'] = (float) $latitude;
        }

        if (isset($contribution->proposed_data['operating_schedule'])) {
            $attributes['operating_hours'] = $contribution->proposed_data['operating_schedule'];
        }

        if (isset($contribution->proposed_data['contact_channel_list'])) {
            $attributes['contact_channel_list'] = $contribution->proposed_data['contact_channel_list'];
        }

        return Establishment::query()->create($attributes);
    }
}
