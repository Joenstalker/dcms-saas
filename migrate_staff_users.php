<?php

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting Staff User Migration...\n";

// 1. Fetch staff from Central DB
$staffUsers = DB::connection('mongodb_central')->table('users')
    ->whereNotIn('role', [User::ROLE_SYSTEM_ADMIN, User::ROLE_TENANT])
    ->get();

echo "Found " . $staffUsers->count() . " staff users to migrate.\n";

foreach ($staffUsers as $rawUserData) {
    // Convert to array to handle keys easily
    $userData = (array)$rawUserData;
    $email = $userData['email'];
    $tenantId = $userData['tenant_id'];
    
    // Determine the ID key (usually _id for raw Mongo, or id for query builder)
    $id = $userData['_id'] ?? $userData['id'] ?? null;
    
    if (!$tenantId) {
        echo "[SKIP] User $email has no tenant_id.\n";
        continue;
    }

    $tenant = Tenant::find($tenantId);
    if (!$tenant) {
        echo "[SKIP] Tenant not found for ID: $tenantId (User: $email).\n";
        continue;
    }

    $dbName = 'db_' . $tenant->slug;
    echo "Migrating $email to $dbName...\n";

    // Switch connection to target tenant DB
    Config::set('database.connections.mongodb.database', $dbName);
    DB::purge('mongodb');
    DB::reconnect('mongodb');

    try {
        // Insert into Tenant DB
        // Use insertIgnore or check if exists to be safe
        DB::connection('mongodb')->table('users')->updateOrInsert(['email' => $email], $userData);
        
        // Remove from Central DB
        if ($id) {
            DB::connection('mongodb_central')->table('users')->where('_id', $id)->delete();
            // Also try by 'id' if '_id' failed (standard query builder)
            DB::connection('mongodb_central')->table('users')->where('id', $id)->delete();
        }
        echo "[SUCCESS] Migrated $email.\n";
    } catch (\Exception $e) {
        echo "[ERROR] Failed to migrate $email: " . $e->getMessage() . "\n";
    }
}

echo "Migration process completed.\n";
