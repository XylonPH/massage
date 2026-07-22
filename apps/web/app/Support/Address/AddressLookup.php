<?php

namespace App\Support\Address;

use App\Models\Reference\City;
use App\Models\Reference\Country;
use App\Models\Reference\Region;

class AddressLookup
{
    /** @return array<string, string> country_id => label, sorted by label */
    public function countries(): array
    {
        return Country::query()
            ->get()
            ->mapWithKeys(fn (Country $country) => [
                (string) $country->getKey() => (string) data_get($country->country_name, 'eng.text', $country->country_key),
            ])
            ->sort()
            ->all();
    }

    /** @return array<string, string> region_id => label, sorted by label */
    public function regions(int $countryId): array
    {
        return Region::query()
            ->where('country_id', $countryId)
            ->get()
            ->mapWithKeys(fn (Region $region) => [
                (string) $region->getKey() => (string) data_get($region->region_name, 'eng.text', $region->region_key),
            ])
            ->sort()
            ->all();
    }

    /**
     * @return array<string, string> city_id => label. Empty until city_main is
     *                               populated by the separate PH Geographic Reference Data
     *                               project — callers must treat an empty result as "no data
     *                               yet", not "no cities".
     */
    public function cities(int $regionId): array
    {
        return City::query()
            ->where('region_id', $regionId)
            ->get()
            ->mapWithKeys(fn (City $city) => [
                (string) $city->getKey() => (string) data_get($city->city_name, 'eng.text', $city->city_key ?? ''),
            ])
            ->sort()
            ->all();
    }
}
