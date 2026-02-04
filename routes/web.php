<?php
declare(strict_types=1);

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TenantController;
use Illuminate\Support\Facades\Route;

$baseDomain = env('LOCAL_BASE_DOMAIN', 'dcmsapp.local');

/**
 * 🏢 TENANT ROUTES (subdomain.dcmsapp.local)
 */
Route::domain('{tenant}.' . $baseDomain)->middleware(['tenant'])->group(function () {
    Route::get('/', function (\App\Models\Tenant $tenant) {
        return redirect()->route('login');
    });

    // Tenant login (Handled by Fortify)
    // Route::get('/login', [\App\Http\Controllers\Tenant\TenantLoginController::class, 'showLoginForm'])->name('tenant.login');
    // Route::post('/login', [\App\Http\Controllers\Tenant\TenantLoginController::class, 'login'])->name('tenant.login.submit');

    // Tenant Authentication routes
    // Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout'); (Handled by Fortify)
    Route::get('/impersonate/{user}', [\App\Http\Controllers\Tenant\TenantLoginController::class, 'impersonate'])->name('impersonate')->middleware('signed');

    // Tenant Subscription & Payment (Authenticated)
    Route::middleware(['auth'])->prefix('subscription')->name('tenant.subscription.')->group(function () {
        Route::get('/select-plan', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'selectPlan'])->name('select-plan');
        Route::post('/initiate/{plan}', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'initiatePayment'])->name('initiate');
        Route::post('/confirm-payment/{plan}', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'confirmPayment'])->name('confirm-payment');
        // Route::post('/process-payment', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'processPayment'])->name('process-payment'); // Refactored to separate steps
        Route::get('/payment/{plan}', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'showPayment'])->name('payment');
        // Route::post('/confirm-payment/{plan}', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'confirmPayment'])->name('confirm-payment'); // Replaced by AJAX version
        Route::get('/success', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'success'])->name('success');
        Route::get('/cancel', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'cancel'])->name('cancel');
    });

    // Tenant Setup Wizard (Authenticated)
    Route::middleware(['auth'])->prefix('setup')->name('tenant.setup.')->group(function () {
        Route::get('/{step?}', [\App\Http\Controllers\Tenant\SetupController::class, 'show'])->name('show')->where('step', '[1-5]');
        Route::post('/branding', [\App\Http\Controllers\Tenant\SetupController::class, 'updateBranding'])->name('branding');
        Route::post('/details', [\App\Http\Controllers\Tenant\SetupController::class, 'updateDetails'])->name('details');
        Route::post('/consent', [\App\Http\Controllers\Tenant\SetupController::class, 'updateConsent'])->name('consent');
        Route::post('/defaults', [\App\Http\Controllers\Tenant\SetupController::class, 'updateDefaults'])->name('defaults');
        Route::post('/complete', [\App\Http\Controllers\Tenant\SetupController::class, 'complete'])->name('complete');
        Route::get('/success', [\App\Http\Controllers\Tenant\SetupController::class, 'success'])->name('success');
    });

    // Tenant Dashboard & Modules (Protected)
    Route::middleware(['auth'])->name('tenant.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Tenant\DashboardController::class, 'index'])->name('dashboard');
        
        // User Management (Owner only)
        Route::get('/users/{user}/view-portal', [\App\Http\Controllers\Tenant\UserController::class, 'viewPortal'])->name('users.view-portal');
        Route::resource('users', \App\Http\Controllers\Tenant\UserController::class);
        
        // Module routes
        Route::resource('patients', \App\Http\Controllers\Tenant\PatientController::class);
        
        Route::resource('appointments', \App\Http\Controllers\Tenant\AppointmentController::class);
        Route::patch('/appointments/{appointment}/status', [\App\Http\Controllers\Tenant\AppointmentController::class, 'updateStatus'])->name('appointments.update-status');
        Route::get('/services', function(\App\Models\Tenant $tenant) { 
            return view('tenant.services.index', compact('tenant')); 
        })->name('services.index');
        Route::get('/role-permission', function(\App\Models\Tenant $tenant) { 
            return view('tenant.RolePermission', compact('tenant')); 
        })->name('role-permission.index');
        
        Route::get('/expenses', function(\App\Models\Tenant $tenant) { 
            return view('tenant.expenses.index', compact('tenant')); 
        })->name('expenses.index');
        Route::get('/settings', [\App\Http\Controllers\Tenant\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\Tenant\SettingsController::class, 'update'])->name('settings.update');
        Route::put('/settings/profile-photo', [\App\Http\Controllers\Tenant\SettingsController::class, 'updateProfilePhoto'])->name('settings.profile-photo.update');
        Route::get('/settings/theme-builder', [\App\Http\Controllers\Admin\ThemeController::class, 'builder'])->name('settings.theme-builder');
        Route::post('/settings/theme', [\App\Http\Controllers\Admin\ThemeController::class, 'storeTenantTheme'])->name('settings.theme.store');
    });
});

/**
 * 🏠 CENTRAL ROUTES (dcmsapp.local, 127.0.0.1, localhost)
 */
$centralGroupOptions = app()->environment('local') ? [] : ['domain' => $baseDomain];

Route::group($centralGroupOptions, function () {
    // Home Page
    Route::get('/', function () {
        $pricingPlans = \App\Models\PricingPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        return view('welcome', compact('pricingPlans'));
    })->name('home');

    // Admin login redirect
    Route::get('/login', function () {
        return redirect()->route('home');
    })->name('login');

    // Admin Authentication
    Route::post('/login', function () {
        return redirect()->route('home')->with('error', 'Invalid login request.');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.submit');
        Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

        // Admin dashboard and management
        Route::middleware(['auth', 'admin'])->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            
            // Tenants
            Route::resource('tenants', TenantController::class);
            Route::post('tenants/{tenant}/toggle-active', [TenantController::class, 'toggleActive'])->name('tenants.toggle-active');
            Route::post('tenants/{tenant}/mark-email-verified', [TenantController::class, 'markEmailVerified'])->name('tenants.mark-email-verified');
            Route::post('tenants/{tenant}/resend-verification', [TenantController::class, 'resendVerificationEmail'])->name('tenants.resend-verification');
            Route::get('tenants/{tenant}/impersonate/{user}', [TenantController::class, 'impersonate'])->name('tenants.impersonate');
            
            // Users
            Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
            
            // Pricing Plans
            Route::resource('pricing-plans', \App\Http\Controllers\Admin\PricingPlanController::class);
            Route::post('pricing-plans/{pricingPlan}/toggle-active', [\App\Http\Controllers\Admin\PricingPlanController::class, 'toggleActive'])->name('pricing-plans.toggle-active');
            
            // Settings
            Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
            Route::post('settings/customization', [\App\Http\Controllers\Admin\SettingsController::class, 'updateCustomization'])->name('settings.customization.update');

            // Profile & Settings
            Route::get('/profile/view', [\App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
            Route::get('/profile/edit', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
            Route::patch('/profile/photo', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
            Route::put('/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('password.update');

            // Themes
            Route::get('themes/builder', [\App\Http\Controllers\Admin\ThemeController::class, 'builder'])->name('themes.builder');
            Route::resource('themes', \App\Http\Controllers\Admin\ThemeController::class);

            // Roles & Permissions
            Route::get('role-permission', [\App\Http\Controllers\Admin\RolePermissionController::class, 'index'])->name('role-permission.index');
            Route::get('role-permission/create', [\App\Http\Controllers\Admin\RolePermissionController::class, 'create'])->name('role-permission.create');
            Route::post('role-permission', [\App\Http\Controllers\Admin\RolePermissionController::class, 'store'])->name('role-permission.store');
            Route::get('role-permission/{role}/edit', [\App\Http\Controllers\Admin\RolePermissionController::class, 'edit'])->name('role-permission.edit');
            Route::put('role-permission/{role}', [\App\Http\Controllers\Admin\RolePermissionController::class, 'update'])->name('role-permission.update');
            Route::delete('role-permission/{role}', [\App\Http\Controllers\Admin\RolePermissionController::class, 'destroy'])->name('role-permission.destroy');
        });
    });

    // Public Tenant Registration
    Route::prefix('register')->name('tenant.registration.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Tenant\RegistrationController::class, 'show'])->name('index');
        Route::post('/', [\App\Http\Controllers\Tenant\RegistrationController::class, 'store'])->name('store');
        Route::get('/check-subdomain', [\App\Http\Controllers\Tenant\RegistrationController::class, 'checkSubdomain'])->name('check-subdomain');
        Route::post('/verify-email', [\App\Http\Controllers\Tenant\RegistrationController::class, 'verifyEmail'])->name('verify-email');
        Route::get('/success/{tenant}', [\App\Http\Controllers\Tenant\RegistrationController::class, 'success'])->name('success');
        Route::get('/payment-success', [\App\Http\Controllers\Tenant\RegistrationController::class, 'handlePaymentSuccess'])->name('payment-success');
    });

    // Tenant Email Verification (accessible from central domain for activation)
    Route::prefix('verify')->name('tenant.verification.')->group(function () {
        Route::get('/email/{token}/{email}', [\App\Http\Controllers\Tenant\VerificationController::class, 'verify'])->name('verify');
        Route::get('/success/{tenant}', [\App\Http\Controllers\Tenant\VerificationController::class, 'success'])->name('success');
        Route::get('/failed', [\App\Http\Controllers\Tenant\VerificationController::class, 'failed'])->name('failed');
    });
});

// Non-domain specific routes or Fallbacks
Route::get('/subscription/suspended/{tenant}', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'suspended'])->name('tenant.subscription.suspended');
