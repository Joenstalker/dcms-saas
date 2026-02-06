<?php

namespace App\Services;

use App\Repositories\RbacRepository;
use App\Models\Role;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RbacService
{
    protected RbacRepository $repository;
    protected AuditService $auditService;
    protected const FEATURE_RBAC = 'rbac';

    public function __construct(RbacRepository $repository, AuditService $auditService)
    {
        $this->repository = $repository;
        $this->auditService = $auditService;
    }

    public function isSuperAdmin(?int $userId = null): bool
    {
        $userId = $userId ?? (Auth::check() ? Auth::id() : null);
        if (!$userId) {
            return false;
        }

        $user = \App\Models\User::find($userId);
        return $user && $user->is_system_admin;
    }

    public function canManageRbac(Tenant $tenant): bool
    {
        if ($this->isSuperAdmin()) {
            return false;
        }

        return $tenant->hasFeature(self::FEATURE_RBAC);
    }

    public function requireSubscriptionAccess(Tenant $tenant, string $action): void
    {
        if ($this->isSuperAdmin()) {
            $this->auditService->logSuperAdminAttempt(
                $action,
                'rbac',
                null,
                $tenant->id,
                Auth::id(),
                'Super Admin attempted to perform RBAC action'
            );

            throw new HttpException(403, 'Super Admin cannot modify tenant RBAC data');
        }

        if (!$tenant->hasFeature(self::FEATURE_RBAC)) {
            $this->auditService->logSubscriptionViolation(
                $action,
                'rbac',
                null,
                $tenant->id,
                Auth::id(),
                self::FEATURE_RBAC
            );

            throw new HttpException(403, 'RBAC feature is not available in your subscription plan');
        }
    }

    public function requireTenantOwnership(string $resourceTenantId, string $action): void
    {
        $currentTenant = app('tenant');

        if (!$currentTenant || $currentTenant->id !== $resourceTenantId) {
            $this->auditService->logCrossTenantAttempt(
                $action,
                'rbac',
                null,
                $resourceTenantId,
                Auth::id(),
                'Attempted to access resources from different tenant'
            );

            throw new HttpException(403, 'Access denied. You can only access resources within your tenant.');
        }
    }

    public function requireEditableRole(Role $role, string $action): void
    {
        if ($role->is_system_role || !$role->is_editable) {
            throw new HttpException(403, 'This role is system-defined and cannot be modified');
        }
    }

    public function getRoles(Tenant $tenant)
    {
        return $this->repository->getTenantRoles($tenant->id);
    }

    public function getRole(Tenant $tenant, string $roleId)
    {
        $role = $this->repository->getRoleByIdAndTenant($roleId, $tenant->id);

        if (!$role) {
            throw new HttpException(404, 'Role not found');
        }

        return $role;
    }

    public function createRole(Tenant $tenant, array $data)
    {
        $this->requireSubscriptionAccess($tenant, 'role.create');

        return $this->repository->createRole($data, $tenant->id);
    }

    public function updateRole(Tenant $tenant, string $roleId, array $data)
    {
        $role = $this->getRole($tenant, $roleId);
        $this->requireEditableRole($role, 'role.update');
        $this->requireSubscriptionAccess($tenant, 'role.update');

        $updated = $this->repository->updateRole($roleId, $tenant->id, $data);

        if (!$updated) {
            throw new HttpException(500, 'Failed to update role');
        }

        return $updated;
    }

    public function deleteRole(Tenant $tenant, string $roleId)
    {
        $role = $this->getRole($tenant, $roleId);
        $this->requireEditableRole($role, 'role.delete');
        $this->requireSubscriptionAccess($tenant, 'role.delete');

        $deleted = $this->repository->deleteRole($roleId, $tenant->id);

        if (!$deleted) {
            throw new HttpException(500, 'Failed to delete role');
        }

        return true;
    }

    public function getRolePermissions(Tenant $tenant, string $roleId)
    {
        $this->getRole($tenant, $roleId);

        return $this->repository->getRolePermissions($roleId, $tenant->id);
    }

    public function assignPermission(Tenant $tenant, string $roleId, string $permissionId)
    {
        $role = $this->getRole($tenant, $roleId);
        $this->requireEditableRole($role, 'permission.assign');
        $this->requireSubscriptionAccess($tenant, 'permission.assign');

        $assigned = $this->repository->assignPermissionToRole($roleId, $permissionId, $tenant->id);

        if (!$assigned) {
            throw new HttpException(500, 'Failed to assign permission');
        }

        return true;
    }

    public function revokePermission(Tenant $tenant, string $roleId, string $permissionId)
    {
        $role = $this->getRole($tenant, $roleId);
        $this->requireEditableRole($role, 'permission.revoke');
        $this->requireSubscriptionAccess($tenant, 'permission.revoke');

        $revoked = $this->repository->revokePermissionFromRole($roleId, $permissionId, $tenant->id);

        if (!$revoked) {
            throw new HttpException(500, 'Failed to revoke permission');
        }

        return true;
    }

    public function syncPermissions(Tenant $tenant, string $roleId, array $permissionIds)
    {
        $role = $this->getRole($tenant, $roleId);
        $this->requireEditableRole($role, 'permissions.sync');
        $this->requireSubscriptionAccess($tenant, 'permissions.sync');

        $synced = $this->repository->syncRolePermissions($roleId, $tenant->id, $permissionIds);

        if (!$synced) {
            throw new HttpException(500, 'Failed to sync permissions');
        }

        return true;
    }

    public function assignUserRole(Tenant $tenant, string $roleId, int $userId)
    {
        $role = $this->getRole($tenant, $roleId);
        $this->requireSubscriptionAccess($tenant, 'user_role.assign');

        $assigned = $this->repository->assignRoleToUser($roleId, $userId, $tenant->id);

        if (!$assigned) {
            throw new HttpException(500, 'Failed to assign role to user');
        }

        return true;
    }

    public function revokeUserRole(Tenant $tenant, string $roleId, int $userId)
    {
        $role = $this->getRole($tenant, $roleId);
        $this->requireSubscriptionAccess($tenant, 'user_role.revoke');

        $revoked = $this->repository->revokeRoleFromUser($roleId, $userId, $tenant->id);

        if (!$revoked) {
            throw new HttpException(500, 'Failed to revoke role from user');
        }

        return true;
    }

    public function syncUserRoles(Tenant $tenant, int $userId, array $roleIds)
    {
        $this->requireSubscriptionAccess($tenant, 'user_roles.sync');

        $synced = $this->repository->syncUserRoles($userId, $tenant->id, $roleIds);

        if (!$synced) {
            throw new HttpException(500, 'Failed to sync user roles');
        }

        return true;
    }

    public function getUserRoles(Tenant $tenant, int $userId)
    {
        return $this->repository->getUserRoles($userId, $tenant->id);
    }

    public function getPermissions(Tenant $tenant)
    {
        return $this->repository->getTenantPermissions($tenant->id);
    }
}
