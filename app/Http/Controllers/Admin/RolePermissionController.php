<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditService;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    protected AuditService $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->isSystemAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        $tenantId = $request->query('tenant_id');
        $tenant = $tenantId ? \App\Models\Tenant::find($tenantId) : null;

        $roles = $tenantId
            ? Role::where('tenant_id', $tenantId)->get()
            : Role::whereNull('tenant_id')->get();

        $permissionsByModule = $this->getGroupedPermissions();

        $selectedRole = null;
        if ($request->has('role_id')) {
            $selectedRole = Role::find($request->query('role_id'));
        } elseif ($roles->count() > 0) {
            $selectedRole = $roles->first();
        }

        $rolePermissions = $selectedRole
            ? $selectedRole->permissions->pluck('name')->toArray()
            : [];

        return view('admin.role-permission.index', compact(
            'roles',
            'tenant',
            'tenantId',
            'permissionsByModule',
            'selectedRole',
            'rolePermissions',
            'user'
        ));
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->isSystemAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        $tenantId = $request->query('tenant_id');
        $permissionsByModule = $this->getGroupedPermissions();

        return view('admin.role-permission.create', compact(
            'permissionsByModule',
            'tenantId',
            'user'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->isSystemAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
            'tenant_id' => 'nullable|exists:tenants,_id',
        ]);

        if (!empty($validated['tenant_id'])) {
            $this->auditService->logSuperAdminAttempt(
                'role.create',
                'role',
                null,
                $validated['tenant_id'],
                $user->id,
                'Super Admin attempted to create role for tenant'
            );

            abort(403, 'Super Admin cannot create roles within tenant scope. Tenant admins must manage their own roles.');
        }

        $exists = Role::where('name', $validated['name'])
            ->whereNull('tenant_id')
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['name' => 'The role name already exists.'])->withInput();
        }

        DB::transaction(function () use ($validated) {
            $role = Role::create([
                'name' => $validated['name'],
                'guard_name' => 'web',
                'tenant_id' => null,
                'is_system_role' => true,
                'is_editable' => false,
            ]);

            if (!empty($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }
        });

        return redirect()->route('admin.role-permission.index')
            ->with('success', 'System role created successfully.');
    }

    public function edit(Role $role)
    {
        $user = auth()->user();

        if (!$user || !$user->isSystemAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        if ($role->tenant_id) {
            $this->auditService->logSuperAdminAttempt(
                'role.edit',
                'role',
                $role->id,
                $role->tenant_id,
                $user->id,
                'Super Admin attempted to edit tenant role'
            );

            abort(403, 'Super Admin cannot modify tenant roles. Tenant admins must manage their own roles.');
        }

        if ($role->is_system_role && !$role->is_editable) {
            abort(403, 'System roles cannot be modified.');
        }

        $permissionsByModule = $this->getGroupedPermissions();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.role-permission.edit', compact(
            'role',
            'permissionsByModule',
            'rolePermissions',
            'user'
        ));
    }

    public function update(Request $request, Role $role)
    {
        $user = auth()->user();

        if (!$user || !$user->isSystemAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        if ($role->tenant_id) {
            $this->auditService->logSuperAdminAttempt(
                'role.update',
                'role',
                $role->id,
                $role->tenant_id,
                $user->id,
                'Super Admin attempted to update tenant role'
            );

            abort(403, 'Super Admin cannot modify tenant roles.');
        }

        if ($role->is_system_role && !$role->is_editable) {
            abort(403, 'System roles cannot be modified.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
        ]);

        $exists = Role::where('name', $validated['name'])
            ->whereNull('tenant_id')
            ->where('id', '!=', $role->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['name' => 'The role name already exists.'])->withInput();
        }

        DB::transaction(function () use ($validated, $role) {
            $role->update(['name' => $validated['name']]);
            $role->syncPermissions($validated['permissions'] ?? []);
        });

        return redirect()->route('admin.role-permission.index')
            ->with('success', 'System role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $user = auth()->user();

        if (!$user || !$user->isSystemAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        if ($role->tenant_id) {
            $this->auditService->logSuperAdminAttempt(
                'role.delete',
                'role',
                $role->id,
                $role->tenant_id,
                $user->id,
                'Super Admin attempted to delete tenant role'
            );

            abort(403, 'Super Admin cannot delete tenant roles.');
        }

        if ($role->is_system_role && !$role->is_editable) {
            abort(403, 'System roles cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('admin.role-permission.index')
            ->with('success', 'System role deleted.');
    }

    public function viewTenantRoles(Request $request, string $tenantId)
    {
        $user = auth()->user();

        if (!$user || !$user->isSystemAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        $roles = Role::where('tenant_id', $tenantId)->get();
        $permissionsByModule = $this->getGroupedPermissions();

        $selectedRole = null;
        if ($request->has('role_id')) {
            $selectedRole = Role::find($request->query('role_id'));
        } elseif ($roles->count() > 0) {
            $selectedRole = $roles->first();
        }

        $rolePermissions = $selectedRole
            ? $selectedRole->permissions->pluck('name')->toArray()
            : [];

        return view('admin.role-permission.index', compact(
            'roles',
            'tenant',
            'tenantId',
            'permissionsByModule',
            'selectedRole',
            'rolePermissions',
            'user'
        ));
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
