<?php

namespace Tests\Feature\Console;

use App\Models\Reference\Region;
use Tests\TestCase;

class SyncReferenceDataTest extends TestCase
{
    protected function tearDown(): void
    {
        Region::query()->getConnection()->getCollection('region_main')->deleteMany([]);
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
}
