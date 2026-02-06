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

        DB::table('role_has_permissions')
            ->where('role_id', $roleId)
            ->delete();

        DB::table('model_has_roles')
            ->where('role_id', $roleId)
            ->delete();

        return $role->delete();
    }

    public function getTenantPermissions(string $tenantId): Collection
    {
        return Permission::all();
    }

    public function getRolePermissions(string $roleId, string $tenantId): Collection
    {
        return DB::table('role_has_permissions')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
            ->where('role_has_permissions.role_id', $roleId)
            ->where('roles.tenant_id', $tenantId)
            ->select('permissions.*')
            ->get();
    }

    public function assignPermissionToRole(string $roleId, string $permissionId, string $tenantId): bool
    {
        $role = Role::where('_id', $roleId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$role || $role->is_system_role || !$role->is_editable) {
            return false;
        }

        $exists = DB::table('role_has_permissions')
            ->where('role_id', $roleId)
            ->where('permission_id', $permissionId)
            ->exists();

        if ($exists) {
            return true;
        }

        return DB::table('role_has_permissions')->insert([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
            'tenant_id' => $tenantId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function revokePermissionFromRole(string $roleId, string $permissionId, string $tenantId): bool
    {
        $role = Role::where('_id', $roleId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$role || $role->is_system_role || !$role->is_editable) {
            return false;
        }

        return DB::table('role_has_permissions')
            ->where('role_id', $roleId)
            ->where('permission_id', $permissionId)
            ->delete();
    }

    public function syncRolePermissions(string $roleId, string $tenantId, array $permissionIds): bool
    {
        $role = Role::where('_id', $roleId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$role || $role->is_system_role || !$role->is_editable) {
            return false;
        }

        DB::table('role_has_permissions')
            ->where('role_id', $roleId)
            ->delete();

        $permissions = array_map(function ($permissionId) use ($roleId, $tenantId) {
            return [
                'role_id' => $roleId,
                'permission_id' => $permissionId,
                'tenant_id' => $tenantId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $permissionIds);

        return DB::table('role_has_permissions')->insert($permissions);
    }

    public function assignRoleToUser(string $roleId, int $userId, string $tenantId): bool
    {
        $role = Role::where('_id', $roleId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$role) {
            return false;
        }

        $user = User::where('id', $userId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$user) {
            return false;
        }

        $exists = DB::table('model_has_roles')
            ->where('role_id', $roleId)
            ->where('model_id', $userId)
            ->where('model_type', User::class)
            ->exists();

        if ($exists) {
            return true;
        }

        return DB::table('model_has_roles')->insert([
            'role_id' => $roleId,
            'model_type' => User::class,
            'model_id' => $userId,
            'tenant_id' => $tenantId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function revokeRoleFromUser(string $roleId, int $userId, string $tenantId): bool
    {
        $role = Role::where('_id', $roleId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$role) {
            return false;
        }

        return DB::table('model_has_roles')
            ->where('role_id', $roleId)
            ->where('model_id', $userId)
            ->where('model_type', User::class)
            ->where('tenant_id', $tenantId)
            ->delete();
    }

    public function syncUserRoles(int $userId, string $tenantId, array $roleIds): bool
    {
        $user = User::where('id', $userId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$user) {
            return false;
        }

        DB::table('model_has_roles')
            ->where('model_id', $userId)
            ->where('model_type', User::class)
            ->where('tenant_id', $tenantId)
            ->delete();

        $roles = array_map(function ($roleId) use ($userId, $tenantId) {
            return [
                'role_id' => $roleId,
                'model_type' => User::class,
                'model_id' => $userId,
                'tenant_id' => $tenantId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $roleIds);

        return DB::table('model_has_roles')->insert($roles);
    }

    public function getUserRoles(int $userId, string $tenantId): Collection
    {
        return DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', $userId)
            ->where('model_has_roles.model_type', User::class)
            ->where('roles.tenant_id', $tenantId)
            ->select('roles.*')
            ->get();
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
