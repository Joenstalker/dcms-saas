<?php

use App\Models\Role;
use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- RBAC Connection Audit ---\n";

// 1. Central Context (No tenant bound)
$role = new Role();
echo "Central Context Connection: " . $role->getConnectionName() . " (Expected: mongodb_central)\n";

// 2. Tenant Context (cudalblanco)
$tenant = Tenant::where('slug', 'cudalblanco')->first();
if ($tenant) {
    app()->instance('tenant', $tenant);
    
    // Simulate Middleware DB switching for 'mongodb' connection
    $dbName = 'db_' . $tenant->slug;
    Config::set('database.connections.mongodb.database', $dbName);
    DB::purge('mongodb');
    DB::reconnect('mongodb');

    $tenantRole = new Role();
    echo "Tenant Context ($tenant->slug) Connection: " . $tenantRole->getConnectionName() . " (Expected: mongodb)\n";
    echo "Active Database for 'mongodb': " . DB::connection('mongodb')->getDatabaseName() . " (Expected: $dbName)\n";
} else {
    echo "Tenant 'cudalblanco' not found for testing.\n";
}

echo "\n--- Permission Connection Audit ---\n";
$perm = new \App\Models\Permission();
echo "Permission Connection: " . $perm->getConnectionName() . "\n";
