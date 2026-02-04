
echo "\n--- TEST 1: Direct Registration ---\n";
$data1 = [
    'clinic_name' => 'Tinker Direct ' . time(),
    'desired_subdomain' => 'tkdirect' . time(),
    'city' => 'Manila',
    'state_province' => 'MM',
    'full_name' => 'Direct Owner',
    'email' => 'tkdirect' . time() . '@test.com',
    'password' => 'Password123!',
    'password_confirmation' => 'Password123!',
    'terms_accepted' => '1',
];

try {
    request()->merge($data1);
    $req = App\Http\Requests\Tenant\StoreRegistrationRequest::create('/register', 'POST', $data1);
    $req->headers->set('Accept', 'application/json');
    $req->headers->set('X-Requested-With', 'XMLHttpRequest');
    $req->setContainer(app());
    
    $con = app(App\Http\Controllers\Tenant\RegistrationController::class);
    $res = $con->store($req);
    echo "Status: " . $res->getStatusCode() . "\n";
    echo "Content: " . $res->getContent() . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n--- TEST 2: Paid Registration ---\n";
$plan = App\Models\PricingPlan::where('slug', 'pro-plan')->first();
if ($plan) {
    $data2 = array_merge($data1, [
        'desired_subdomain' => 'tkpaid' . time(),
        'email' => 'tkpaid' . time() . '@test.com',
        'pricing_plan_id' => $plan->id
    ]);
    
    try {
        request()->merge($data2);
        // Use fresh request object needed for validation? Yes.
        $req2 = App\Http\Requests\Tenant\StoreRegistrationRequest::create('/register', 'POST', $data2);
        $req2->headers->set('Accept', 'application/json');
        $req2->headers->set('X-Requested-With', 'XMLHttpRequest');
        $req2->setContainer(app());
        
        $res2 = $con->store($req2);
        echo "Status: " . $res2->getStatusCode() . "\n";
        echo "Content: " . $res2->getContent() . "\n";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "Pro Plan not found.\n";
}
exit();
