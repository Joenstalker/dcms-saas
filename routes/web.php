<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TenantController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Tenant Registration (Public)
Route::prefix('register')->name('tenant.registration.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Tenant\RegistrationController::class, 'show'])->name('index');
    Route::post('/', [\App\Http\Controllers\Tenant\RegistrationController::class, 'store'])->name('store');
    Route::get('/check-subdomain', [\App\Http\Controllers\Tenant\RegistrationController::class, 'checkSubdomain'])->name('check-subdomain');
    Route::get('/success/{tenant}', [\App\Http\Controllers\Tenant\RegistrationController::class, 'success'])->name('success');
});

// Tenant Email Verification (Public)
Route::prefix('verify')->name('tenant.verification.')->group(function () {
    Route::get('/email/{token}/{email}', [\App\Http\Controllers\Tenant\VerificationController::class, 'verify'])->name('verify');
    Route::get('/success/{tenant}', [\App\Http\Controllers\Tenant\VerificationController::class, 'success'])->name('success');
    Route::get('/failed', [\App\Http\Controllers\Tenant\VerificationController::class, 'failed'])->name('failed');
});

// Tenant Subscription - Suspended page (No auth required)
Route::get('/subscription/suspended/{tenant}', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'suspended'])->name('tenant.subscription.suspended');

// Tenant Subscription & Payment (Authenticated - accessible from dashboard)
Route::middleware(['auth'])->prefix('subscription')->name('tenant.subscription.')->group(function () {
    Route::get('/select-plan/{tenant}', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'selectPlan'])->name('select-plan');
    Route::post('/process-payment/{tenant}', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'processPayment'])->name('process-payment');
    Route::get('/payment/{tenant}/{plan}', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'showPayment'])->name('payment');
    Route::post('/confirm-payment/{tenant}/{plan}', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'confirmPayment'])->name('confirm-payment');
    Route::get('/success/{tenant}', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'success'])->name('success');
    Route::get('/cancel/{tenant}', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'cancel'])->name('cancel');
});

// Tenant Setup Wizard (Authenticated - accessible from dashboard)
Route::middleware(['auth'])->prefix('setup')->name('tenant.setup.')->group(function () {
    Route::get('/{tenant}/{step?}', [\App\Http\Controllers\Tenant\SetupController::class, 'show'])->name('show')->where('step', '[1-5]');
    Route::post('/branding/{tenant}', [\App\Http\Controllers\Tenant\SetupController::class, 'updateBranding'])->name('branding');
    Route::post('/details/{tenant}', [\App\Http\Controllers\Tenant\SetupController::class, 'updateDetails'])->name('details');
    Route::post('/consent/{tenant}', [\App\Http\Controllers\Tenant\SetupController::class, 'updateConsent'])->name('consent');
    Route::post('/defaults/{tenant}', [\App\Http\Controllers\Tenant\SetupController::class, 'updateDefaults'])->name('defaults');
    Route::post('/complete/{tenant}', [\App\Http\Controllers\Tenant\SetupController::class, 'complete'])->name('complete');
    Route::get('/success/{tenant}', [\App\Http\Controllers\Tenant\SetupController::class, 'success'])->name('success');
});

// Tenant Dashboard & Modules (Protected)
Route::middleware(['auth'])->prefix('tenant/{tenant}')->name('tenant.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Tenant\DashboardController::class, 'index'])->name('dashboard');
    
    // User Management (Owner only)
    Route::resource('users', \App\Http\Controllers\Tenant\UserController::class);
    
    // Module routes (placeholder - will be implemented)
    Route::get('/patients', function(\App\Models\Tenant $tenant) { 
        return view('tenant.patients.index', compact('tenant')); 
    })->name('patients.index');
    Route::get('/appointments', function(\App\Models\Tenant $tenant) { 
        return view('tenant.appointments.index', compact('tenant')); 
    })->name('appointments.index');
    Route::get('/services', function(\App\Models\Tenant $tenant) { 
        return view('tenant.services.index', compact('tenant')); 
    })->name('services.index');
    Route::get('/masterfile', function(\App\Models\Tenant $tenant) { 
        return view('tenant.masterfile.index', compact('tenant')); 
    })->name('masterfile.index');
    Route::get('/expenses', function(\App\Models\Tenant $tenant) { 
        return view('tenant.expenses.index', compact('tenant')); 
    })->name('expenses.index');
    Route::get('/settings', function(\App\Models\Tenant $tenant) { 
        return view('tenant.settings.index', compact('tenant')); 
    })->name('settings.index');
});

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Tenants
    Route::resource('tenants', TenantController::class);
    Route::post('tenants/{tenant}/toggle-active', [TenantController::class, 'toggleActive'])->name('tenants.toggle-active');
    Route::post('tenants/{tenant}/mark-email-verified', [TenantController::class, 'markEmailVerified'])->name('tenants.mark-email-verified');
    Route::post('tenants/{tenant}/resend-verification', [TenantController::class, 'resendVerificationEmail'])->name('tenants.resend-verification');
    
    // Pricing Plans
    Route::resource('pricing-plans', \App\Http\Controllers\Admin\PricingPlanController::class);
    Route::post('pricing-plans/{pricingPlan}/toggle-active', [\App\Http\Controllers\Admin\PricingPlanController::class, 'toggleActive'])->name('pricing-plans.toggle-active');
});
