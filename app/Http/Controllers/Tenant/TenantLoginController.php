<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        // If user is already logged in to this tenant, redirect to dashboard
        if (Auth::check() && Auth::user()->tenant_id === $tenant->id) {
            return redirect()->route('tenant.dashboard', ['tenant' => $tenant->slug]);
        }

        // If user is logged in to a DIFFERENT tenant, logout first
        if (Auth::check() && Auth::user()->tenant_id !== $tenant->id) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            // Re-establish tenant context
            session(['tenant_id' => $tenant->id, 'tenant_slug' => $tenant->slug]);
        }
        
        // Validate input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        $normalizedEmail = strtolower(trim($credentials['email']));
        $user = User::whereRaw('LOWER(email) = ?', [$normalizedEmail])
            ->where('tenant_id', $tenant->id)
            ->first();

        // Check if user exists and password is correct
        if (!$user || !\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            Log::warning('Tenant login failed', [
                'email' => $normalizedEmail,
                'tenant_id' => $tenant->id,
                'tenant_slug' => $tenant->slug,
                'reason' => $user ? 'password_mismatch' : 'user_not_found',
            ]);
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

        if (!$tenant->email_verified_at) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Please verify your clinic email before logging in.']);
        }

        // Login the user
        Auth::login($user);
        
        // Regenerate session for security
        $request->session()->regenerate();
        
        // Ensure tenant identity is locked in session
        session(['tenant_id' => $tenant->id, 'tenant_slug' => $tenant->slug]);

        return redirect()->route('tenant.dashboard', ['tenant' => $tenant->slug])
            ->with('success', 'Welcome back! ' . $tenant->name);
    }

}
