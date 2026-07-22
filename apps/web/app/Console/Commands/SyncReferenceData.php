<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SyncReferenceData extends Command
{
    protected $signature = 'reference:sync {--dry-run : Report what would change without writing}';

    protected $description = 'Idempotently upsert data/common_reference/*.json into the common_reference database.';

    /** Collection filename => Mongo collection name. */
    private const DATASETS = [
        'country_main' => 'country_main',
        'region_main' => 'region_main',
        'area_hierarchy_profile' => 'area_hierarchy_profile',
        'currency_main' => 'currency_main',
        'language_main' => 'language_main',
        'time_zone_main' => 'time_zone_main',
        'dog_breed_main' => 'dog_breed_main',
    ];

    public function handle(): int
    {
        $repositoryRoot = dirname(base_path(), 2);
        $dryRun = (bool) $this->option('dry-run');

        foreach (self::DATASETS as $file => $collection) {
            $path = "{$repositoryRoot}/data/common_reference/{$file}.json";

            if (! File::exists($path)) {
                $this->warn("Skipping {$collection}: {$path} not found.");

                continue;
            }

            $records = json_decode(File::get($path), true, flags: JSON_THROW_ON_ERROR);
            $upserted = 0;

            foreach ($records as $record) {
                if (! isset($record['_id'])) {
                    $this->warn("Skipping a {$collection} record with no _id.");

                    continue;
                }

                if (! $dryRun) {
                    DB::connection('mongodb_reference')
                        ->getCollection($collection)
                        ->replaceOne(['_id' => $record['_id']], $record, ['upsert' => true]);
                }

                $upserted++;
            }

            $verb = $dryRun ? 'would upsert' : 'upserted';
            $this->info("{$collection}: {$verb} {$upserted} record(s).");
        }

        return self::SUCCESS;
    }
}
