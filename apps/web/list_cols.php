<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cols = DB::connection('mongodb')->getDatabase()->listCollectionNames();
foreach ($cols as $col) {
    echo $col . PHP_EOL;
}
