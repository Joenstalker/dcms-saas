<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreUserRequest;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
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
    public function viewPortal(Tenant $tenant, User $user): View
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
            return $dashboardController->dentistDashboard($tenant);
        } elseif ($user->isAssistant()) {
            $dashboardController = new DashboardController();
            return $dashboardController->assistantDashboard($tenant);
        }

        abort(404, 'Portal not available for this user role.');
    }

    public function index(Tenant $tenant): View
    {
        // Ensure user belongs to this tenant
        if (auth()->user()->tenant_id !== $tenant->id) {
            abort(403);
        }

        $users = User::where('tenant_id', $tenant->id)
            ->get()
            ->map(function ($user) {
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

            DB::commit();

            return redirect()->route('tenant.users.index', $tenant)
                ->with('success', ucfirst($request->role) . ' added successfully!')
                ->with('temp_password', $randomPassword)
                ->with('staff_email', $request->email);

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

    public function show(Tenant $tenant, User $user): View
    {
        // Ensure user belongs to this tenant and is viewing their own tenant's user
        if (auth()->user()->tenant_id !== $tenant->id || $user->tenant_id !== $tenant->id) {
            abort(403);
        }

        $user->role_name = $user->role ?? 'No Role';

        return view('tenant.users.show', compact('tenant', 'user'));
    }

    public function edit(Tenant $tenant, User $user): View
    {
        // Ensure user belongs to this tenant and owner can't edit themselves
        if (auth()->user()->tenant_id !== $tenant->id || $user->tenant_id !== $tenant->id) {
            abort(403);
        }

        // Prevent editing the owner
        if ($user->isOwner()) {
            return redirect()->route('tenant.users.index', $tenant)
                ->with('error', 'Owner account cannot be edited here.');
        }

        $user->current_role = $user->role;

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
                \Illuminate\Validation\Rule::unique('users', 'email')
                    ->ignore($user->id)
                    ->where(function ($query) use ($tenant) {
                        return $query->where('tenant_id', $tenant->id);
                    }),
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
            $user->delete();

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
