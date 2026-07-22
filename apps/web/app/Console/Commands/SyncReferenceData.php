<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SyncReferenceData extends Command
{
    protected $signature = 'reference:sync {--dry-run : Report what would change without writing}';

    protected $description = 'Idempotently upsert data/common_reference/*.json into the common_reference database.';

    /** Mongo collection => JSON field holding the record's numeric identifier (stored as _id). */
    private const DATASETS = [
        'country_main' => 'country_id',
        'region_main' => '_id',
        'area_hierarchy_profile' => 'area_hierarchy_profile_id',
        'currency_main' => 'currency_id',
        'language_main' => 'language_id',
        'time_zone_main' => 'time_zone_id',
        'dog_breed_main' => '_id',
    ];

    public function handle(): int
    {
        $repositoryRoot = dirname(base_path(), 2);
        $dryRun = (bool) $this->option('dry-run');

        foreach (self::DATASETS as $collection => $idField) {
            $path = "{$repositoryRoot}/data/common_reference/{$collection}.json";

            if (! File::exists($path)) {
                $this->warn("Skipping {$collection}: {$path} not found.");

                continue;
            }

            $records = json_decode(File::get($path), true, flags: JSON_THROW_ON_ERROR);
            $upserted = 0;
            $skipped = 0;

            foreach ($records as $record) {
                if (! isset($record[$idField])) {
                    $skipped++;

                    continue;
                }

                $document = $record;
                if ($idField !== '_id') {
                    $document['_id'] = $document[$idField];
                    unset($document[$idField]);
                }

                if (! $dryRun) {
                    DB::connection('mongodb_reference')
                        ->getCollection($collection)
                        ->replaceOne(['_id' => $document['_id']], $document, ['upsert' => true]);
                }

                $upserted++;
            }

            $verb = $dryRun ? 'would upsert' : 'upserted';
            $this->info("{$collection}: {$verb} {$upserted} record(s).");
            if ($skipped > 0) {
                $this->warn("{$collection}: skipped {$skipped} record(s) with no {$idField}.");
            }
        }

        return self::SUCCESS;
    }
}
