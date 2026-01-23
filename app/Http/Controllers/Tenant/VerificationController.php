<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function verify(string $token, string $email): RedirectResponse|View
    {
        $tenant = Tenant::where('email', $email)
            ->where('email_verification_token', $token)
            ->whereNull('email_verified_at')
            ->first();

        if (!$tenant) {
            return redirect()->route('tenant.verification.failed')
                ->with('error', 'Invalid or expired verification link.');
        }

        try {
            DB::beginTransaction();

            // Mark tenant as verified (but not active until subscription is paid)
            $tenant->update([
                'email_verified_at' => now(),
                'email_verification_token' => null,
                'is_active' => false, // Will be activated after payment
            ]);

            // Mark owner user as verified
            $user = User::where('tenant_id', $tenant->id)
                ->where('email', $email)
                ->first();

            if ($user) {
                $user->update([
                    'email_verified_at' => now(),
                ]);
            }

            // Activate tenant after email verification (plan will be selected later)
            $tenant->update([
                'is_active' => true, // Activate tenant after email verification
            ]);

            DB::commit();

            // Auto-login the user and redirect to dashboard
            auth()->login($user);

            return redirect()->route('tenant.dashboard', $tenant)
                ->with('success', 'Email verified successfully! Welcome to your dashboard.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('tenant.verification.failed')
                ->with('error', 'Verification failed. Please try again or contact support.');
        }
    }

    public function success(Tenant $tenant): View
    {
        return view('tenant.verification.success', compact('tenant'));
    }

    public function failed(): View
    {
        return view('tenant.verification.failed');
    }
}
