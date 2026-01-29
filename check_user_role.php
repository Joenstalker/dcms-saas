<?php
// Quick diagnostic to check user role
// Run: php check_user_role.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make(Illuminate\Contracts\Console\Kernel::class);

// Get the last logged-in user
$user = \App\Models\User::latest()->first();

if ($user) {
    echo "Latest User Info:\n";
    echo "================\n";
    echo "ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Role: " . $user->role . "\n";
    echo "Tenant ID: " . $user->tenant_id . "\n";
    echo "\nRole Checks:\n";
    echo "isOwner(): " . ($user->isOwner() ? 'YES' : 'NO') . "\n";
    echo "isDentist(): " . ($user->isDentist() ? 'YES' : 'NO') . "\n";
    echo "isAssistant(): " . ($user->isAssistant() ? 'YES' : 'NO') . "\n";
    echo "isSystemAdmin(): " . ($user->isSystemAdmin() ? 'YES' : 'NO') . "\n";
} else {
    echo "No users found in database.\n";
}
