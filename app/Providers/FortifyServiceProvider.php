<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        // Custom authentication for tenant users
        Fortify::authenticateUsing(function (Request $request) {
            $tenantId = session('tenant_id');
            if (!$tenantId) {
                return null;
            }

            $normalizedEmail = strtolower(trim($request->email));
            $user = \App\Models\User::whereRaw('LOWER(email) = ?', [$normalizedEmail])
                ->where('tenant_id', $tenantId)
                ->first();

            if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                return $user;
            }

            return null;
        });

        // Configure Fortify views for tenant subdomain only
        Fortify::loginView(function () {
            $tenantId = session('tenant_id');
            if (!$tenantId) {
                abort(404, 'Clinic not found.');
            }

            $tenant = \App\Models\Tenant::find($tenantId);
            if (!$tenant || !$tenant->is_active) {
                abort(404, 'Clinic not found or inactive.');
            }

            return view('tenant.TenantLogin', compact('tenant'));
        });

        Fortify::requestPasswordResetLinkView(fn () => view('tenant.auth.forgot-password'));
        Fortify::resetPasswordView(fn ($request) => view('tenant.auth.reset-password', ['request' => $request]));
        Fortify::confirmPasswordView(fn () => view('tenant.auth.confirm-password'));
        Fortify::twoFactorChallengeView(fn () => view('tenant.auth.two-factor-challenge'));

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
