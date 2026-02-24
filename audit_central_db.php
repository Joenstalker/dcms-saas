<?php

use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$connection = 'mongodb_central';
echo "Verifying Central DB Cleanliness ($connection)...\n";

try {
    $collections = DB::connection($connection)->listCollections();
    
    $results = [];
    foreach ($collections as $collection) {
        $name = $collection->getName();
        $count = DB::connection($connection)->table($name)->count();
        $results[] = [
            'name' => $name,
            'count' => $count
        ];
    }
    
    usort($results, function($a, $b) {
        return strcmp($a['name'], $b['name']);
    });

    echo str_pad("Collection", 30) . " | " . "Count" . "\n";
    echo str_repeat("-", 40) . "\n";
    foreach ($results as $res) {
        echo str_pad($res['name'], 30) . " | " . $res['count'] . "\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
