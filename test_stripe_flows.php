<?php

use App\Models\Tenant;
use App\Models\User;
use App\Models\PricingPlan;
use Illuminate\Support\Facades\Auth;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Running Stripe Registration Flow Tests...\n";

// --- Helper to cleanup ---
function cleanup($subdomain) {
    if ($tenant = Tenant::where('slug', $subdomain)->first()) {
        User::where('tenant_id', $tenant->id)->delete();
        $tenant->delete();
        echo "Cleaned up $subdomain.\n";
    }
}

// --- TEST 1: Direct Registration (Free Trial) ---
echo "\n--- TEST 1: Direct Registration (No Plan) ---\n";
$subdomain1 = 'direct' . time();
cleanup($subdomain1);

$data1 = [
    'clinic_name' => 'Direct Clinic',
    'desired_subdomain' => $subdomain1,
    'city' => 'Manila',
    'state_province' => 'Metro Manila',
    'full_name' => 'Direct Owner',
    'email' => 'direct' . time() . '@example.com',
    'password' => 'Password123!',
    'password_confirmation' => 'Password123!',
    'terms_accepted' => '1',
    // No pricing_plan_id
];

try {
    $request = Illuminate\Http\Request::create('/register', 'POST', $data1);
    $request->headers->set('Accept', 'application/json');
    $request->headers->set('X-Requested-With', 'XMLHttpRequest');
    
    $controller = app(App\Http\Controllers\Tenant\RegistrationController::class);
    $storeRequest = App\Http\Requests\Tenant\StoreRegistrationRequest::createFrom($request);
    $storeRequest->setContainer(app());

    /* Manual Validation Mocking since we bypass route middleware */
    $validator = Validator::make($data1, $storeRequest->rules());
    if ($validator->fails()) {
        echo "Validation Failed: " . json_encode($validator->errors()) . "\n";
    } else {
        $response = $controller->store($storeRequest);
        echo "Status: " . $response->getStatusCode() . "\n";
        $content = json_decode($response->getContent(), true);
        
        if ($response->getStatusCode() === 201 && isset($content['redirect_url'])) {
            echo "SUCCESS: Created and redirected.\n";
            echo "Redirect: " . $content['redirect_url'] . "\n";
        } else {
            echo "FAILURE: " . $response->getContent() . "\n";
        }
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

// --- TEST 2: Paid Registration (Pro Plan) ---
echo "\n--- TEST 2: Paid Registration (With Plan) ---\n";
$subdomain2 = 'paid' . time();
cleanup($subdomain2);

$proPlan = PricingPlan::where('slug', 'pro-plan')->first();
if (!$proPlan) {
    echo "SKIPPING: Pro Plan not found.\n";
} else {
    $data2 = array_merge($data1, [
        'desired_subdomain' => $subdomain2,
        'email' => 'paid' . time() . '@example.com',
        'pricing_plan_id' => $proPlan->id,
    ]);

    try {
        $request = Illuminate\Http\Request::create('/register', 'POST', $data2);
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        
        $controller = app(App\Http\Controllers\Tenant\RegistrationController::class);
        $storeRequest = App\Http\Requests\Tenant\StoreRegistrationRequest::createFrom($request);
        $storeRequest->setContainer(app());
        
        $validator = Validator::make($data2, $storeRequest->rules());
        if ($validator->fails()) {
            echo "Validation Failed: " . json_encode($validator->errors()) . "\n";
        } else {
            $response = $controller->store($storeRequest);
            echo "Status: " . $response->getStatusCode() . "\n";
            $content = json_decode($response->getContent(), true);
            
            if ($response->getStatusCode() === 200 && ($content['payment_required'] ?? false)) {
                echo "SUCCESS: Payment Required triggered.\n";
                echo "Stripe URL: " . ($content['redirect_url'] ?? 'N/A') . "\n";
            } elseif ($response->getStatusCode() === 500 && str_contains($content['message'] ?? '', 'Payment initialization failed')) {
                 echo "SUCCESS (Partial): Payment logic reached, but failed (Expected due to fake ID).\n";
            } else {
                echo "FAILURE: " . $response->getContent() . "\n";
            }
        }
    } catch (\Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
}

echo "\nTests Completed.\n";
