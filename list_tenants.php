<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$tenants = App\Models\Tenant::all(['name', 'slug', 'domain', 'email']);
foreach ($tenants as $tenant) {
    echo "NAME: {$tenant->name} | SLUG: {$tenant->slug} | DOMAIN: {$tenant->domain} | EMAIL: {$tenant->email}\n";
}
