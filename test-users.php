<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tenant = \App\Models\Tenant::where('slug', 'cudalblanco')->first();

if (!$tenant) {
    echo "Tenant not found.\n";
    exit;
}

$out = "Tenant Name: {$tenant->name}\nTenant ID: {$tenant->id}\n\n";

$out .= "All Users in db_central:\n";
$centralUsers = \App\Models\User::on('mongodb_central')->get();
foreach ($centralUsers as $u) {
    if (in_array($u->email, ['assistant@test.com', 'dentist@test.com'])) {
        $out .= "- FOUND: {$u->name} ({$u->email}) [tenant_id: {$u->tenant_id}]\n";
    }
}

// Emulate middleware connection switch
config(['database.connections.mongodb.database' => 'db_' . $tenant->slug]);
\Illuminate\Support\Facades\DB::purge('mongodb');

try {
    $assistant = new \App\Models\User();
    $assistant->setConnection('mongodb');
    $assistant->name = 'Sarah Assistant';
    $assistant->email = 'assistant@test.com';
    $assistant->password = \Illuminate\Support\Facades\Hash::make('password');
    $assistant->role = 'assistant';
    $assistant->tenant_id = $tenant->id;
    $assistant->status = 'active';
    $assistant->must_reset_password = false;
    $assistant->save();

    $dentist = new \App\Models\User();
    $dentist->setConnection('mongodb');
    $dentist->name = 'Dr. Cudal';
    $dentist->email = 'dentist@test.com';
    $dentist->password = \Illuminate\Support\Facades\Hash::make('password');
    $dentist->role = 'dentist';
    $dentist->tenant_id = $tenant->id;
    $dentist->status = 'active';
    $dentist->must_reset_password = false;
    $dentist->save();
} catch (\Exception $e) {
    $out .= "Error inserting users: " . $e->getMessage() . "\n";
}

$tenantUsers = \App\Models\User::on('mongodb')->where('tenant_id', $tenant->id)->get();
$out .= "\nUsers in db_{$tenant->slug} (tenant_id = {$tenant->id}):\n";
foreach ($tenantUsers as $u) {
    $out .= "- {$u->name} ({$u->email}) [tenant_id: {$u->tenant_id}]\n";
}

file_put_contents('out.txt', $out);



