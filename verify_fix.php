<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

// Simulate the logic in TenantController store/update
$request = new Request();
$request->merge(['pricing_plan_id' => '2']); // String ID from form

echo "Original ID in request: [" . $request->pricing_plan_id . "] Type: " . gettype($request->pricing_plan_id) . PHP_EOL;

if ($request->has('pricing_plan_id')) {
    $request->merge(['pricing_plan_id' => (int) $request->pricing_plan_id]);
}

echo "Casted ID in request: [" . $request->pricing_plan_id . "] Type: " . gettype($request->pricing_plan_id) . PHP_EOL;

$rules = ['pricing_plan_id' => 'required|exists:pricing_plans,id'];
$validator = Validator::make($request->all(), $rules);

if ($validator->fails()) {
    echo "Validation FAILED!" . PHP_EOL;
    print_r($validator->errors()->all());
} else {
    echo "Validation PASSED!" . PHP_EOL;
}
