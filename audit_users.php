<?php

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- Central DB Users Audit ---\n";
// Force Central connection
$centralUsers = DB::connection('mongodb_central')->table('users')->get(['name', 'email', 'role', 'tenant_id']);
echo "Total Users in Central: " . $centralUsers->count() . "\n";
foreach ($centralUsers as $u) {
    echo "- Name: {$u->name} | Email: {$u->email} | Role: " . ($u->role ?? 'N/A') . " | TenantID: " . ($u->tenant_id ?? 'N/A') . "\n";
}

$slug = 'cudalblanco';
$tenant = Tenant::where('slug', $slug)->first();

if ($tenant) {
    echo "\n--- Tenant DB ($slug) Users Audit ---\n";
    $dbName = 'db_' . $slug;
    
    Config::set('database.connections.mongodb.database', $dbName);
    DB::purge('mongodb');
    DB::reconnect('mongodb');

    $tenantUsers = DB::connection('mongodb')->table('users')->get(['name', 'email', 'role', 'tenant_id']);
    echo "Total Users in $dbName: " . $tenantUsers->count() . "\n";
    foreach ($tenantUsers as $u) {
        echo "- Name: {$u->name} | Email: {$u->email} | Role: " . ($u->role ?? 'N/A') . " | TenantID: " . ($u->tenant_id ?? 'N/A') . "\n";
    }
} else {
    echo "\nTenant $slug not found.\n";
}
