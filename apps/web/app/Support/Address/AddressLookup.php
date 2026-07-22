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
                (string) $country->getKey() => $this->label($country, 'country_name', 'country_key'),
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
                (string) $region->getKey() => $this->label($region, 'region_name', 'region_key'),
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
                (string) $city->getKey() => $this->label($city, 'city_name', 'city_key'),
            ])
            ->sort()
            ->all();
    }

    private function label(mixed $model, string $nameField, string $keyField): string
    {
        return (string) data_get($model->{$nameField}, 'eng.text', $model->{$keyField} ?? '');
    }
}
