<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Check if user exists and is system admin
        $user = \App\Models\User::where('email', $credentials['email'])
            ->where(function($q) {
                $q->where('role', \App\Models\User::ROLE_SYSTEM_ADMIN)
                  ->orWhere('is_system_admin', true);
            })
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records or you are not an admin.',
            ])->onlyInput('email');
        }

        // Attempt authentication
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            \Illuminate\Support\Facades\Log::debug('Admin login successful', [
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
                'is_system_admin' => Auth::user()->isSystemAdmin(),
                'session_id' => session()->getId(),
            ]);

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
