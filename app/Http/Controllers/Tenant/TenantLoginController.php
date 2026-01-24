<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TenantLoginController extends Controller
{
    /**
     * Show the tenant-specific login form
     */
    public function showLoginForm(): View
    {
        // Get tenant from middleware session
        $tenantId = session('tenant_id');
        if (!$tenantId) {
            abort(404, 'Clinic not found.');
        }

        $tenant = Tenant::find($tenantId);
        if (!$tenant || !$tenant->is_active) {
            abort(404, 'Clinic not found or inactive.');
        }

        return view('tenant.TenantLogin', compact('tenant'));
    }

    /**
     * Handle tenant login
     */
    public function login(Request $request): RedirectResponse
    {
        // Get tenant from middleware session
        $tenantId = session('tenant_id');
        if (!$tenantId) {
            abort(404, 'Clinic not found.');
        }

        $tenant = Tenant::find($tenantId);
        if (!$tenant || !$tenant->is_active) {
            abort(404, 'Clinic not found or inactive.');
        }

        // Validate input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find user in this tenant
        $user = User::where('email', $credentials['email'])
            ->where('tenant_id', $tenant->id)
            ->first();

        // Check if user exists and password is correct
        if (!$user || !\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['auth_failed' => 'Invalid email or password for this clinic.']);
        }

        // Verify user's email
        if (!$user->email_verified_at) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Please verify your email before logging in.']);
        }

        // Check tenant subscription
        if (!$tenant->hasActiveSubscription()) {
            return redirect()->route('tenant.subscription.suspended', $tenant)
                ->with('error', 'Your clinic subscription has expired.');
        }

        // Login the user
        Auth::login($user);
        
        // Regenerate session for security
        $request->session()->regenerate();
        
        // Ensure tenant identity is locked in session
        session(['tenant_id' => $tenant->id, 'tenant_slug' => $tenant->slug]);

        // Redirect to tenant dashboard
        return redirect()->route('tenant.dashboard', ['tenant' => $tenant->slug])
            ->with('success', 'Welcome back! ' . $tenant->name);
    }
}
