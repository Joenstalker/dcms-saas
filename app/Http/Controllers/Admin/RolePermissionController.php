<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->query('tenant_id');
        $tenant = $tenantId ? \App\Models\Tenant::find($tenantId) : null;
        
        $roles = Role::where('tenant_id', $tenantId)->get();
        return view('admin.role-permission.index', compact('roles', 'tenant'));
    }

    public function create(Request $request)
    {
        $tenantId = $request->query('tenant_id');
        $tenant = $tenantId ? \App\Models\Tenant::find($tenantId) : null;
        $permissionsByModule = $this->getGroupedPermissions();
        return view('admin.role-permission.create', compact('permissionsByModule', 'tenant'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
            'tenant_id' => 'nullable|exists:tenants,id',
        ]);

        // Check uniqueness within the tenant or global
        $exists = Role::where('name', $validated['name'])
            ->where('tenant_id', $validated['tenant_id'] ?? null)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['name' => 'The role name already exists for this context.'])->withInput();
        }

        DB::transaction(function () use ($validated) {
            $role = Role::create([
                'name' => $validated['name'],
                'guard_name' => 'web',
                'tenant_id' => $validated['tenant_id'] ?? null,
            ]);

            if (!empty($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }
        });

        $redirectUrl = route('admin.role-permission.index');
        if (!empty($validated['tenant_id'])) {
            $redirectUrl .= '?tenant_id=' . $validated['tenant_id'];
        }

        return redirect($redirectUrl)->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $tenant = $role->tenant_id ? \App\Models\Tenant::find($role->tenant_id) : null;
        $permissionsByModule = $this->getGroupedPermissions();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.role-permission.edit', compact('role', 'permissionsByModule', 'rolePermissions', 'tenant'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
        ]);

        // Check uniqueness within the same context
        $exists = Role::where('name', $validated['name'])
            ->where('tenant_id', $role->tenant_id)
            ->where('id', '!=', $role->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['name' => 'The role name already exists for this context.'])->withInput();
        }

        DB::transaction(function () use ($validated, $role) {
            $role->update(['name' => $validated['name']]);
            $role->syncPermissions($validated['permissions'] ?? []);
        });

        $redirectUrl = route('admin.role-permission.index');
        if ($role->tenant_id) {
            $redirectUrl .= '?tenant_id=' . $role->tenant_id;
        }

        return redirect($redirectUrl)->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $tenantId = $role->tenant_id;
        $role->delete();

        $redirectUrl = route('admin.role-permission.index');
        if ($tenantId) {
            $redirectUrl .= '?tenant_id=' . $tenantId;
        }

        return redirect($redirectUrl)->with('success', 'Role deleted.');
    }

    protected function getGroupedPermissions()
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
