<?php

use App\Models\Tenant;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$slug = 'cudalblanco';
$tenant = Tenant::where('slug', $slug)->first();

if (!$tenant) {
    echo "Tenant not found: $slug\n";
    exit;
}

echo "Tenant Found: " . $tenant->id . "\n";

// Switch to Tenant DB
$dbName = 'db_' . $tenant->slug;
echo "Switching to Tenant DB: $dbName\n";

Config::set('database.connections.mongodb.database', $dbName);
DB::purge('mongodb');
DB::reconnect('mongodb');

// Fix Patients missing tenant_id
$patientsToFix = Patient::where('tenant_id', '')->orWhereNull('tenant_id')->get();
echo "Found " . $patientsToFix->count() . " patients to fix in $dbName.\n";

foreach ($patientsToFix as $patient) {
    echo "Updating patient: " . $patient->first_name . " " . $patient->last_name . "...\n";
    $patient->tenant_id = $tenant->id;
    $patient->save();
}

echo "Data fix completed.\n";
