<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class RbacRepository
{
    public function getTenantRoles(string $tenantId): Collection
    {
        return Role::where('tenant_id', $tenantId)
            ->orderBy('name')
            ->get();
    }

    public function getRoleByIdAndTenant(string $roleId, string $tenantId): ?Role
    {
        return Role::where('_id', $roleId)
            ->where('tenant_id', $tenantId)
            ->first();
    }

    public function getRoleByNameAndTenant(string $roleName, string $tenantId): ?Role
    {
        return Role::where('name', $roleName)
            ->where('tenant_id', $tenantId)
            ->first();
    }

    public function createRole(array $data, string $tenantId): Role
    {
        $data['tenant_id'] = $tenantId;
        $data['is_system_role'] = false;
        $data['is_editable'] = true;

        return Role::create($data);
    }

    public function updateRole(string $roleId, string $tenantId, array $data): ?Role
    {
        $role = Role::where('_id', $roleId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$role) {
            return null;
        }

        if ($role->is_system_role || !$role->is_editable) {
            return null;
        }

        unset($data['tenant_id'], $data['is_system_role'], $data['is_editable']);

        $role->update($data);
        return $role->fresh();
    }

    public function deleteRole(string $roleId, string $tenantId): bool
    {
        $role = Role::where('_id', $roleId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$role) {
            return false;
        }

        if ($role->is_system_role || !$role->is_editable) {
            return false;
        }

        // When using Spatie/HasPermissions trait with MongoDB,
        // it handles detachment when the role is deleted
        // but we can be explicit here using Eloquent if needed.
        $role->permissions()->detach();

        return $role->delete();
    }

    public function getTenantPermissions(string $tenantId): Collection
    {
        return Permission::all();
    }

    public function getRolePermissions(string $roleId, string $tenantId): Collection
    {
        $role = Role::where('_id', $roleId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$role) {
            return new Collection();
        }

        return $role->permissions;
    }

    public function assignPermissionToRole(string $roleId, string $permissionId, string $tenantId): bool
    {
        $role = Role::where('_id', $roleId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$role || $role->is_system_role || !$role->is_editable) {
            return false;
        }

        $permission = Permission::find($permissionId);
        if (!$permission) {
            return false;
        }

        $role->givePermissionTo($permission);
        return true;
    }

    public function revokePermissionFromRole(string $roleId, string $permissionId, string $tenantId): bool
    {
        $role = Role::where('_id', $roleId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$role || $role->is_system_role || !$role->is_editable) {
            return false;
        }

        $permission = Permission::find($permissionId);
        if (!$permission) {
            return false;
        }

        $role->revokePermissionTo($permission);
        return true;
    }

    public function syncRolePermissions(string $roleId, string $tenantId, array $permissionIds): bool
    {
        $role = Role::where('_id', $roleId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$role || $role->is_system_role || !$role->is_editable) {
            return false;
        }

        $permissions = Permission::whereIn('_id', $permissionIds)->get();
        $role->syncPermissions($permissions);
        
        return true;
    }

    public function assignRoleToUser(string $roleId, string $userId, string $tenantId): bool
    {
        $role = Role::where('_id', $roleId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$role) {
            return false;
        }

        $user = User::where('_id', $userId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$user) {
            return false;
        }

        $user->assignRole($role);
        return true;
    }

    public function revokeRoleFromUser(string $roleId, string $userId, string $tenantId): bool
    {
        $role = Role::where('_id', $roleId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$role) {
            return false;
        }

        $user = User::where('_id', $userId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$user) {
            return false;
        }

        $user->removeRole($role);
        return true;
    }

    public function syncUserRoles(string $userId, string $tenantId, array $roleIds): bool
    {
        $user = User::where('_id', $userId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$user) {
            return false;
        }

        $roles = Role::whereIn('_id', $roleIds)
            ->where('tenant_id', $tenantId)
            ->get();

        $user->syncRoles($roles);
        return true;
    }

    public function getUserRoles(string $userId, string $tenantId): Collection
    {
        $user = User::where('_id', $userId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$user) {
            return new Collection();
        }

        return $user->roles;
    }

    public function getAllTenants(): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Tenant::all();
    }

    public function tenantExists(string $tenantId): bool
    {
        return \App\Models\Tenant::where('_id', $tenantId)->exists();
    }
}
