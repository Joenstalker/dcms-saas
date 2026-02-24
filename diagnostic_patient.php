<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();

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

echo "Tenant Found:\n";
echo "ID: " . $tenant->id . "\n";
echo "Slug: " . $tenant->slug . "\n";
echo "Name: " . $tenant->name . "\n";

// Check Central DB patients (if any)
$centralPatients = Patient::where('tenant_id', $tenant->id)->get();
echo "\nPatients in Central DB for this tenant: " . $centralPatients->count() . "\n";
foreach ($centralPatients as $p) {
    echo "- " . $p->first_name . " " . $p->last_name . " (ID: " . $p->id . ", tenant_id: " . $p->tenant_id . ")\n";
}

// Switch to Tenant DB
$dbName = 'db_' . $tenant->slug;
echo "\nSwitching to Tenant DB: $dbName\n";

Config::set('database.connections.mongodb.database', $dbName);
DB::purge('mongodb');
DB::reconnect('mongodb');

$currentDb = DB::connection('mongodb')->getDatabaseName();
echo "Current DB Connection: $currentDb\n";

$tenantPatients = Patient::all();
echo "Total Patients in $dbName: " . $tenantPatients->count() . "\n";

foreach ($tenantPatients as $p) {
    echo "- " . $p->first_name . " " . $p->last_name . " (ID: " . $p->id . ", tenant_id: " . $p->tenant_id . ")\n";
}

$tenantPatientsWithScope = Patient::where('tenant_id', $tenant->id)->get();
echo "Patients in $dbName with tenant_id filter (" . $tenant->id . "): " . $tenantPatientsWithScope->count() . "\n";

$output = ob_get_clean();
echo $output;
file_put_contents('diagnostic_log.txt', $output);
