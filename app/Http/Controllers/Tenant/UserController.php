<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreUserRequest;
use App\Models\User;
use App\Models\Tenant;
use App\Notifications\StaffInvitationNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Role;
use Illuminate\View\View;
use App\Http\Controllers\Tenant\DashboardController;

class UserController extends Controller
{
    public function __construct()
    {
        // Only owner can manage users
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->isOwner()) {
                abort(403, 'Only clinic owners can manage users.');
            }
            return $next($request);
        });
    }

    /**
     * View a staff member's portal (owner only)
     */
    public function viewPortal(Tenant $tenant, User $user): View|RedirectResponse
    {
        // Ensure owner belongs to this tenant
        if (auth()->user()->tenant_id !== $tenant->id) {
            abort(403);
        }

        // Ensure the user being viewed belongs to this tenant
        if ($user->tenant_id !== $tenant->id) {
            abort(403, 'This user does not belong to your clinic.');
        }

        // Only allow viewing dentist and assistant portals
        if ($user->isDentist()) {
            $dashboardController = new DashboardController();
            return $dashboardController->dentistDashboard($tenant, $user);
        } elseif ($user->isAssistant()) {
            $dashboardController = new DashboardController();
            return $dashboardController->assistantDashboard($tenant, $user);
        }

        abort(404, 'Portal not available for this user role.');
    }

    public function index(Tenant $tenant): View|RedirectResponse
    {
        // Ensure user belongs to this tenant
        if (auth()->user()->tenant_id !== $tenant->id) {
            abort(403);
        }

        // Staff members (Dentists/Assistants) are stored in the Tenant DB (mongodb connection)
        // Owners are in mongodb_central and are managed in Settings.
        // We explicitly use 'mongodb' which is pointed to the tenant's database by TenantMiddleware.
        $users = User::on('mongodb')
            ->where('role', '!=', 'owner')
            ->get()
            ->map(function ($user) {
                // Ensure the user instance knows it's using the mongodb connection
                $user->setConnection('mongodb');
                $user->role_name = $user->role ?? 'No Role';
                return $user;
            });

        return view('tenant.users.index', compact('tenant', 'users'));
    }

    public function create(Tenant $tenant): View
    {
        // Ensure user belongs to this tenant
        if (auth()->user()->tenant_id !== $tenant->id) {
            abort(403);
        }

        return view('tenant.users.create', compact('tenant'));
    }

    public function store(StoreUserRequest $request, Tenant $tenant): RedirectResponse
    {
        // Ensure user belongs to this tenant
        if (auth()->user()->tenant_id !== $tenant->id) {
            abort(403);
        }

        $limitService = app(\App\Services\CheckPlanLimits::class);
        if ($limitService->hasReachedUserLimit($tenant)) {
            return redirect()->route('tenant.users.index', $tenant)
                ->with('error', 'User limit reached for your current plan (' . ($tenant->pricingPlan->max_users ?? '0') . ' users). Please upgrade to add more staff.')
                ->with('show_upgrade_modal', true);
        }

        try {
            DB::beginTransaction();

            // Generate a random password
            $randomPassword = Str::random(12);

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($randomPassword),
                'tenant_id' => $tenant->id,
                'status' => $request->status,
                'role' => $request->role,
                'is_system_admin' => false,
                'must_reset_password' => true,
                'email_verified_at' => now(), // Auto-verify email - no verification required
            ]);

            // Assign the selected role (dentist or assistant)
            $role = Role::where('tenant_id', $tenant->id)
                ->where('name', $request->role)
                ->first();

            if ($role) {
                $user->assignRole($role);
            }

            // Send invitation email to the new staff member
            // Generate tenant-specific login URL using subdomain
            $baseDomain = config('app.url', 'http://dcmsapp.local');
            $baseDomain = str_replace(['http://', 'https://'], '', $baseDomain);
            $loginUrl = 'http://' . $tenant->slug . '.' . $baseDomain;
            
            try {
                $user->notify(new StaffInvitationNotification(
                    tempPassword: $randomPassword,
                    clinicName: $tenant->name,
                    loginUrl: $loginUrl,
                    role: $request->role
                ));
            } catch (\Exception $e) {
                // Log error but don't fail the user creation
                Log::warning('Failed to send staff invitation email', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }

            DB::commit();

            return redirect()->route('tenant.users.index', $tenant)
                ->with('success', ucfirst($request->role) . ' added successfully! An invitation email has been sent to ' . $request->email);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create tenant user', [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add user. Please try again.');
        }
    }

    public function show(Tenant $tenant, User $user): View|RedirectResponse
    {
        // Ensure user belongs to this tenant and is viewing their own tenant's user
        if (auth()->user()->tenant_id !== $tenant->id || $user->tenant_id !== $tenant->id) {
            abort(403);
        }

        $user->role_name = $user->role ?? 'No Role';

        if (request()->ajax()) {
            return view('tenant.users.show', compact('tenant', 'user'));
        }

        return view('tenant.users.show', compact('tenant', 'user'));
    }

    public function edit(Tenant $tenant, User $user): View|RedirectResponse
    {
        // Ensure user belongs to this tenant and owner can't edit themselves
        if (auth()->user()->tenant_id !== $tenant->id || $user->tenant_id !== $tenant->id) {
            abort(403);
        }

        // Prevent editing the owner in staff management
        if ($user->isOwner()) {
            // If it's the current user, redirect to settings
            if ($user->id === auth()->id()) {
                return redirect()->route('tenant.settings.account', $tenant);
            }
            
            return redirect()->route('tenant.users.index', $tenant)
                ->with('info', 'Owner accounts are managed in the main account settings.');
        }

        $user->current_role = $user->role;

        if (request()->ajax()) {
            // We'll create a partial-friendly version of edit or just strip layout
            return view('tenant.users.edit', compact('tenant', 'user'))->with('is_modal', true);
        }

        return view('tenant.users.edit', compact('tenant', 'user'));
    }

    public function update(Request $request, Tenant $tenant, User $user): RedirectResponse
    {
        // Ensure user belongs to this tenant
        if (auth()->user()->tenant_id !== $tenant->id || $user->tenant_id !== $tenant->id) {
            abort(403);
        }

        // Prevent editing the owner
        if ($user->isOwner()) {
            return redirect()->route('tenant.users.index', $tenant)
                ->with('error', 'Owner account cannot be edited here.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                // 1. Unique in Tenant DB (ignoring current user)
                \Illuminate\Validation\Rule::unique('mongodb.users', 'email')->ignore($user->id),
                // 2. Unique in Central DB
                \Illuminate\Validation\Rule::unique('mongodb_central.users', 'email'),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:dentist,assistant',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            // Update role
            $newRole = Role::where('tenant_id', $tenant->id)
                ->where('name', $request->role)
                ->first();

            if ($newRole) {
                // Remove old roles and assign new one
                $user->roles()->where('tenant_id', $tenant->id)->detach();
                $user->assignRole($newRole);
            }

            DB::commit();

            return redirect()->route('tenant.users.index', $tenant)
                ->with('success', 'User updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update tenant user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update user. Please try again.');
        }
    }

    public function destroy(Tenant $tenant, User $user): RedirectResponse
    {
        // Ensure user belongs to this tenant
        if (auth()->user()->tenant_id !== $tenant->id || $user->tenant_id !== $tenant->id) {
            abort(403);
        }

        // Prevent deleting the owner
        if ($user->isOwner()) {
            return redirect()->route('tenant.users.index', $tenant)
                ->with('error', 'Owner account cannot be deleted.');
        }

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('tenant.users.index', $tenant)
                ->with('error', 'You cannot delete your own account.');
        }

        try {
            $user->forceDelete();

            return redirect()->route('tenant.users.index', $tenant)
                ->with('success', 'User removed successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to delete tenant user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to remove user. Please try again.');
        }
    }
}
