<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$connection = 'mongodb_central';
$collectionsToDrop = [
    'appointments',
    'audit_logs',
    'consent_templates',
    'prescription_templates',
    'tenant_medicines',
    'tenant_services',
    'tenant_templates',
    'patients',
    'payments',
    'certificate_templates',
    'medical_conditions',
    'medicines',
    'services',
];

echo "Starting cleanup of Central DB ($connection)...\n";

foreach ($collectionsToDrop as $collection) {
    echo "Dropping collection: $collection... ";
    try {
        Schema::connection($connection)->dropIfExists($collection);
        echo "Done.\n";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

echo "Cleanup completed successfully.\n";
