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
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => ['required', new \App\Rules\Recaptcha],
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA challenge.',
        ]);

        $credentials = $request->only('email', 'password');
        $normalizedEmail = strtolower(trim($credentials['email']));

        // Check if user exists using robust matching for MongoDB
        $user = \App\Models\User::where('email', 'regex', '/^' . preg_quote($normalizedEmail, '/') . '$/i')->first();

        if (!$user) {
            $message = 'The provided credentials do not match our records.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 401);
            }
            return back()->withErrors(['email' => $message])->onlyInput('email');
        }

        // Check if user is system admin
        if (!$user->isSystemAdmin()) {
            $message = 'This account is not authorized to access the admin portal.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 403);
            }
            return back()->withErrors(['email' => $message])->onlyInput('email');
        }

        // Check if user exists and password is correct
        if (!$user || !\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            $message = 'The provided credentials do not match our records.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 401);
            }
            return back()->withErrors(['email' => $message])->onlyInput('email');
        }

        // Login the user
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        \Illuminate\Support\Facades\Log::debug('Admin login successful', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'is_system_admin' => Auth::user()->isSystemAdmin(),
            'session_id' => session()->getId(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Login successful',
                'redirect' => route('admin.dashboard')
            ]);
        }

        return redirect()->route('admin.dashboard');
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
