<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

echo "Checking database connections..." . PHP_EOL;
echo "Default: " . config('database.default') . PHP_EOL;
echo "Tenant connection driver: " . (new Tenant)->getConnection()->getDriverName() . PHP_EOL;
echo "User connection driver: " . (new User)->getConnection()->getDriverName() . PHP_EOL;

try {
    echo "Creating a dummy tenant..." . PHP_EOL;
    $tenant = Tenant::create([
        'name' => 'Reproduction Clinic',
        'slug' => 'repro-' . time(),
        'email' => 'repro' . time() . '@example.com',
        'pricing_plan_id' => 2, // Integer as per my fix
        'is_active' => true,
    ]);

    echo "Tenant created with ID: " . $tenant->id . " (Type: " . gettype($tenant->id) . ")" . PHP_EOL;

    echo "Creating a user for this tenant..." . PHP_EOL;
    $user = User::create([
        'name' => 'Repro Admin',
        'email' => 'repro-admin' . time() . '@example.com',
        'password' => Hash::make('password123'),
        'tenant_id' => $tenant->id,
        'is_system_admin' => false,
        'must_reset_password' => true,
    ]);

    echo "User created successfully!" . PHP_EOL;

} catch (\Throwable $e) {
    echo "ERROR CAUGHT: " . $e->getMessage() . PHP_EOL;
    echo "File: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
    echo "Trace: " . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
} finally {
    if (isset($tenant)) {
        echo "Cleaning up tenant..." . PHP_EOL;
        $tenant->forceDelete();
    }
}
