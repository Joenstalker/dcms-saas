<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$plans = App\Models\PricingPlan::all();
echo "Plans found: " . $plans->count() . PHP_EOL;
foreach($plans as $plan) {
    echo "ID: [" . $plan->id . "] Type: " . gettype($plan->id) . " Name: " . $plan->name . PHP_EOL;
    echo "Raw Attributes: " . json_encode($plan->getAttributes()) . PHP_EOL;
}
