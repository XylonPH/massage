<?php

namespace Tests\Unit\Support;

use App\Models\Reference\City;
use App\Models\Reference\Country;
use App\Models\Reference\Region;
use App\Support\Address\AddressLookup;
use Tests\TestCase;

class AddressLookupTest extends TestCase
{
    protected function tearDown(): void
    {
        Country::query()->getConnection()->getCollection('country_main')->deleteMany([]);
        Region::query()->getConnection()->getCollection('region_main')->deleteMany([]);
        City::query()->getConnection()->getCollection('city_main')->deleteMany([]);
        parent::tearDown();
    }

    public function test_countries_returns_id_label_map_sorted_by_label(): void
    {
        Country::query()->getConnection()->getCollection('country_main')->insertMany([
            ['_id' => 764, 'country_name' => ['eng' => ['text' => 'Thailand']]],
            ['_id' => 608, 'country_name' => ['eng' => ['text' => 'Philippines']]],
        ]);

        $result = (new AddressLookup)->countries();

        $this->assertSame(['608' => 'Philippines', '764' => 'Thailand'], $result);
    }

    public function test_regions_filters_by_country(): void
    {
        Region::query()->getConnection()->getCollection('region_main')->insertMany([
            ['_id' => 1, 'country_id' => 608, 'region_name' => ['eng' => ['text' => 'NCR']]],
            ['_id' => 2, 'country_id' => 999, 'region_name' => ['eng' => ['text' => 'Elsewhere']]],
        ]);

        $result = (new AddressLookup)->regions(608);

        $this->assertSame(['1' => 'NCR'], $result);
    }

    public function test_cities_returns_empty_array_when_none_exist(): void
    {
        $result = (new AddressLookup)->cities(1);

        $this->assertSame([], $result);
    }

    public function test_cities_filters_by_region_when_data_exists(): void
    {
        City::query()->getConnection()->getCollection('city_main')->insertOne([
            '_id' => 1,
            'region_id' => 1,
            'city_name' => ['eng' => ['text' => 'Manila']],
        ]);

        $result = (new AddressLookup)->cities(1);

        $this->assertSame(['1' => 'Manila'], $result);
    }
}
