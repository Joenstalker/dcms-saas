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
        // Use MongoDB-compatible case-insensitive regex for email matching
        $user = User::where('email', 'regex', '/^' . preg_quote($normalizedEmail, '/') . '$/i')
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

        // Login the user
        Auth::login($user);
        
        // Regenerate session for security
        $request->session()->regenerate();
        
        // Ensure tenant identity is locked in session
        session(['tenant_id' => $tenant->id, 'tenant_slug' => $tenant->slug]);

        return redirect()->route('tenant.dashboard', ['tenant' => $tenant->slug])
            ->with('success', 'Welcome back! ' . $tenant->name);
    }

    public function autoLogin(Request $request)
    {
        $tenantId = session('tenant_id');
        if (!$tenantId) {
            // Find tenant by subdomain if not in session (Middleware should have handled this)
            $host = $request->getHost();
            $subdomain = explode('.', $host)[0];
            $tenant = Tenant::where('slug', $subdomain)->first();
            if (!$tenant) {
                 abort(404, 'Clinic not found');
            }
        } else {
             $tenant = Tenant::find($tenantId);
        }

        $userId = $request->input('user_id');
        $timestamp = $request->input('timestamp');
        $signature = $request->input('signature');
        $redirectPath = $request->input('redirect');
        
        // Validation
        if (!$userId || !$timestamp || !$signature) {
             abort(403, 'Invalid request parameters');
        }

        // Verify expiration (10 minutes to be safe)
        if (now()->timestamp - $timestamp > 600) {
             abort(403, 'Link expired');
        }

        // Verify signature
        // Ensure tenant ID is string to match registration controller
        $tenantIdStr = (string)$tenant->getKey();
        $dataToSign = $userId . $tenantIdStr . $timestamp;
        $expectedSignature = hash_hmac('sha256', $dataToSign, config('app.key'));
        
        if (!hash_equals($expectedSignature, $signature)) {
             abort(403, 'Invalid signature');
        }

        $user = User::where('id', $userId)->where('tenant_id', $tenant->id)->firstOrFail();

        // Login
        Auth::login($user);
        $request->session()->regenerate();
        session(['tenant_id' => $tenant->id, 'tenant_slug' => $tenant->slug]);

        if ($redirectPath) {
             // Construct full URL with current host
             $port = $request->getPort();
             $portSuffix = ($port && $port != 80 && $port != 443) ? ":{$port}" : "";
             $scheme = $request->getScheme();
             $url = "{$scheme}://{$request->getHost()}{$portSuffix}{$redirectPath}";
             
             // Append query params if any (e.g. payment_required)
             $queryParams = $request->except(['user_id', 'timestamp', 'signature', 'redirect']);
             if (!empty($queryParams)) {
                 $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($queryParams);
             }
             
             return redirect()->away($url);
        }

        return redirect()->route('tenant.dashboard', ['tenant' => $tenant->slug]);
    }
}
