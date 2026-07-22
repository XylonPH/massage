<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Models\Reference\Country;
use App\Models\Reference\Region;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SyncReferenceDataTest extends TestCase
{
    protected function tearDown(): void
    {
        $collections = [
            'country_main',
            'region_main',
            'area_hierarchy_profile',
            'currency_main',
            'language_main',
            'time_zone_main',
            'dog_breed_main',
        ];

        foreach ($collections as $collection) {
            DB::connection('mongodb_reference')->getCollection($collection)->deleteMany([]);
        }

        parent::tearDown();
    }

    public function test_sync_upserts_region_main_from_the_json_file(): void
    {
        $this->artisan('reference:sync')->assertExitCode(0);

        $region = Region::query()->find(1);
        $this->assertNotNull($region);
        $this->assertSame('ph_national_capital_region', $region->region_key);
    }

    public function test_sync_is_idempotent(): void
    {
        $this->artisan('reference:sync')->assertExitCode(0);
        $countAfterFirstRun = Region::query()->count();

        $this->artisan('reference:sync')->assertExitCode(0);
        $countAfterSecondRun = Region::query()->count();

        $this->assertSame($countAfterFirstRun, $countAfterSecondRun);
    }

    public function test_dry_run_makes_no_changes(): void
    {
        $this->artisan('reference:sync', ['--dry-run' => true])->assertExitCode(0);

        $this->assertSame(0, Region::query()->count());
    }

    public function test_sync_maps_dataset_id_fields_to_mongo_id(): void
    {
        $this->artisan('reference:sync')->assertExitCode(0);

        $philippines = Country::query()->find(608);
        $this->assertNotNull($philippines);
        $this->assertSame('philippines', $philippines->country_key);
        $this->assertNull($philippines->country_id ?? null);
    }
}
