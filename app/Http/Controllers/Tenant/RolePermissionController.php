<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use App\Services\RbacService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    protected RbacService $rbacService;

    public function __construct(RbacService $rbacService)
    {
        $this->rbacService = $rbacService;
    }

    public function index(Request $request)
    {
        $tenant = app('tenant');

        if (!$tenant) {
            abort(403, 'Tenant context not found');
        }

        $planSlug = $tenant->pricingPlan?->slug ?? '';
        $allowedPlans = ['pro', 'ultimate'];

        if (!in_array($planSlug, $allowedPlans)) {
            return view('tenant.subscription-required', [
                'feature' => 'Roles & Permissions Management',
                'message' => 'Upgrade to Pro or Ultimate plan to access role-based access control features.'
            ]);
        }

        $roles = $this->rbacService->getRoles($tenant);
        $permissionsByModule = $this->getGroupedPermissions();

        $selectedRole = null;
        if ($request->has('role_id')) {
            $selectedRole = $this->rbacService->getRole($tenant, $request->query('role_id'));
        } elseif ($roles->count() > 0) {
            $selectedRole = $roles->first();
        }

        $rolePermissions = [];
        if ($selectedRole) {
            $rolePermissions = $this->rbacService->getRolePermissions($tenant, $selectedRole->_id)
                ->pluck('name')
                ->toArray();
        }

        $canManage = $tenant->hasFeature('rbac');

        return view('tenant.role-permission.index', compact(
            'roles',
            'permissionsByModule',
            'selectedRole',
            'rolePermissions',
            'canManage'
        ));
    }

    public function store(Request $request)
    {
        $tenant = app('tenant');

        if (!$tenant) {
            abort(403, 'Tenant context not found');
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,NULL,id,tenant_id,' . $tenant->id,
                'description' => 'nullable|string|max:500',
                'permissions' => 'nullable|array',
            ]);

            $role = $this->rbacService->createRole($tenant, [
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'guard_name' => 'web',
            ]);

            if (!empty($validated['permissions'])) {
                $permissionIds = Permission::whereIn('name', $validated['permissions'])->pluck('id')->toArray();
                $this->rbacService->syncPermissions($tenant, $role->_id, $permissionIds);
            }

            return redirect()->route('tenant.role-permission.index')
                ->with('success', 'Role created successfully.');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, string $roleId)
    {
        $tenant = app('tenant');

        if (!$tenant) {
            abort(403, 'Tenant context not found');
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'permissions' => 'nullable|array',
            ]);

            $role = $this->rbacService->updateRole($tenant, $roleId, [
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);

            if (!empty($validated['permissions'])) {
                $permissionIds = Permission::whereIn('name', $validated['permissions'])->pluck('id')->toArray();
                $this->rbacService->syncPermissions($tenant, $roleId, $permissionIds);
            }

            return redirect()->route('tenant.role-permission.index')
                ->with('success', 'Role updated successfully.');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(string $roleId)
    {
        $tenant = app('tenant');

        if (!$tenant) {
            abort(403, 'Tenant context not found');
        }

        try {
            $this->rbacService->deleteRole($tenant, $roleId);

            return redirect()->route('tenant.role-permission.index')
                ->with('success', 'Role deleted successfully.');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function assignPermissions(Request $request, string $roleId)
    {
        $tenant = app('tenant');

        if (!$tenant) {
            abort(403, 'Tenant context not found');
        }

        try {
            $validated = $request->validate([
                'permissions' => 'nullable|array',
            ]);

            $permissionIds = !empty($validated['permissions'])
                ? Permission::whereIn('name', $validated['permissions'])->pluck('id')->toArray()
                : [];

            $this->rbacService->syncPermissions($tenant, $roleId, $permissionIds);

            return redirect()->route('tenant.role-permission.index', ['role_id' => $roleId])
                ->with('success', 'Permissions updated successfully.');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function assignUserRole(Request $request, int $userId)
    {
        $tenant = app('tenant');

        if (!$tenant) {
            abort(403, 'Tenant context not found');
        }

        try {
            $validated = $request->validate([
                'roles' => 'required|array',
            ]);

            $roleIds = Role::whereIn('name', $validated['roles'])
                ->where('tenant_id', $tenant->id)
                ->pluck('_id')
                ->map(fn($id) => (string) $id)
                ->toArray();

            $this->rbacService->syncUserRoles($tenant, $userId, $roleIds);

            return redirect()->back()
                ->with('success', 'User roles updated successfully.');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    protected function getGroupedPermissions(): array
    {
        $permissions = Permission::all();
        $grouped = [];

        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name);
            $module = count($parts) > 1 ? ucfirst($parts[0]) : 'General';
            $grouped[$module][] = $permission;
        }

        return $grouped;
    }
}
