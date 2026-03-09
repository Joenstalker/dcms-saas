<?php

$t = \App\Models\Tenant::where('slug', 'cudalblanco')->first();
$p = $t->pricingPlan;

$output = "Plan name: " . $p->name . "\n";
$output .= "Features: " . implode(', ', $p->features ?? []) . "\n";

$limitService = app(\App\Services\CheckPlanLimits::class);
$featuresToCheck = ['Custom Clinic Branding', 'all_features', 'online_booking', 'billing', 'inventory'];

foreach ($featuresToCheck as $f) {
    $output .= "Access to $f: " . ($limitService->canAccessFeature($t, $f) ? 'Yes' : 'No') . "\n";
}

file_put_contents('d:/dentistmng/dcms-saas/feature_output.txt', $output);
