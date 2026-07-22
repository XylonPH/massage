<?php

namespace Tests\Feature\Reference;

use App\Models\Reference\Country;
use App\Models\Reference\Region;
use Tests\TestCase;

class CountryRegionModelTest extends TestCase
{
    public function test_country_model_reads_from_the_reference_connection(): void
    {
        Country::query()->getConnection()->getCollection('country_main')->insertOne([
            '_id' => 608,
            'country_key' => 'philippines',
            'iso_alpha_2_code' => 'PH',
            'country_name' => ['eng' => ['text' => 'Philippines']],
        ]);

        $country = Country::query()->find(608);

        $this->assertNotNull($country);
        $this->assertSame('philippines', $country->country_key);
        $this->assertSame('mongodb_reference', $country->getConnectionName());
    }

    public function test_region_model_filters_by_country(): void
    {
        Region::query()->getConnection()->getCollection('region_main')->insertMany([
            ['_id' => 1, 'country_id' => 608, 'region_key' => 'ph_ncr', 'region_name' => ['eng' => ['text' => 'National Capital Region']]],
            ['_id' => 2, 'country_id' => 999, 'region_key' => 'other', 'region_name' => ['eng' => ['text' => 'Elsewhere']]],
        ]);

        $regions = Region::query()->where('country_id', 608)->get();

        $this->assertCount(1, $regions);
        $this->assertSame('ph_ncr', $regions->first()->region_key);
    }

    protected function tearDown(): void
    {
        Country::query()->getConnection()->getCollection('country_main')->deleteMany([]);
        Region::query()->getConnection()->getCollection('region_main')->deleteMany([]);
        parent::tearDown();
    }
}
