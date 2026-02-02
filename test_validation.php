<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Validator;

$data = ['pricing_plan_id' => '2']; // String ID from form
$rules = ['pricing_plan_id' => 'required|exists:pricing_plans,id'];

$validator = Validator::make($data, $rules);

if ($validator->fails()) {
    echo "Validation FAILED!" . PHP_EOL;
    print_r($validator->errors()->all());
} else {
    echo "Validation PASSED!" . PHP_EOL;
}

// Try with integer
$data2 = ['pricing_plan_id' => 2];
$validator2 = Validator::make($data2, $rules);

if ($validator2->fails()) {
    echo "Validation with integer FAILED!" . PHP_EOL;
    print_r($validator2->errors()->all());
} else {
    echo "Validation with integer PASSED!" . PHP_EOL;
}
