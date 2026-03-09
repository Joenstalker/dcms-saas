<?php

$t = \App\Models\Tenant::where('slug', 'cudalblanco')->first();
if (!$t) {
    echo "Tenant not found\n";
    exit;
}

$p = $t->pricingPlan;
if (!$p) {
    echo "Plan not found\n";
    exit;
}

echo "Plan name: " . $p->name . "\n";
echo "Plan features count: " . count($p->features ?? []) . "\n";
print_r($p->features);

$limitService = app(\App\Services\CheckPlanLimits::class);
$featuresToCheck = ['Custom Clinic Branding', 'all_features', 'online_booking', 'billing', 'inventory'];

foreach ($featuresToCheck as $f) {
    echo "Access to $f: " . ($limitService->canAccessFeature($t, $f) ? 'Yes' : 'No') . "\n";
}

echo "Done.\n";
