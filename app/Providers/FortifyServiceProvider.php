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
        $this->app->singleton(\Laravel\Fortify\Contracts\LoginResponse::class, \App\Http\Responses\LoginResponse::class);
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
        // Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        // Custom authentication for tenant users
        Fortify::authenticateUsing(function (Request $request) {
            $tenantId = session('tenant_id');
            
            \Illuminate\Support\Facades\Log::debug('Fortify authenticateUsing', [
                'email' => $request->email,
                'session_tenant_id' => $tenantId,
            ]);

            if (!$tenantId) {
                \Illuminate\Support\Facades\Log::warning('Fortify: No tenant_id in session');
                return null;
            }

            $normalizedEmail = strtolower(trim($request->email));
            // Use MongoDB-compatible case-insensitive regex for email matching
            $user = \App\Models\User::where('email', 'regex', '/^' . preg_quote($normalizedEmail, '/') . '$/i')
                ->where('tenant_id', $tenantId)
                ->first();

            \Illuminate\Support\Facades\Log::debug('Fortify: User lookup result', [
                'email' => $normalizedEmail,
                'tenant_id' => $tenantId,
                'user_found' => $user ? 'yes' : 'no',
                'user_id' => $user?->id,
            ]);

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
