<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Check if user exists and get their tenant
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        // Check if user's tenant exists and is verified
        if ($user->tenant_id) {
            $tenant = Tenant::find($user->tenant_id);
            
            if (!$tenant) {
                return back()->withErrors([
                    'email' => 'Your clinic account was not found. Please contact support.',
                ])->onlyInput('email');
            }

            // Check if email is verified
            if (!$tenant->isEmailVerified()) {
                return back()->withErrors([
                    'email' => 'Please verify your email address first. Check your inbox for the verification link.',
                ])->onlyInput('email');
            }

            // Allow access to dashboard even without plan (plan can be selected from dashboard)
            // Setup wizard is optional and can be accessed from dashboard
        }

        // Attempt authentication
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect based on user type
            if ($user->isSystemAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->tenant_id) {
                $tenant = Tenant::find($user->tenant_id);
                return redirect()->route('tenant.dashboard', $tenant);
            }

            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
