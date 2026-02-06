<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class AuditService
{
    public function logRbacViolation(
        string $action,
        string $resourceType,
        ?string $resourceId,
        ?string $tenantId,
        ?int $userId,
        string $reason,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): ?AuditLog {
        $id = DB::table('audit_logs')->insertGetId([
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'tenant_id' => $tenantId,
            'user_id' => $userId,
            'description' => $reason,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $id ? AuditLog::find($id) : null;
    }

    public function logSuperAdminAttempt(
        string $action,
        string $resourceType,
        ?string $resourceId,
        ?string $targetTenantId,
        ?int $superAdminId,
        string $reason
    ): ?AuditLog {
        $id = DB::table('audit_logs')->insertGetId([
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'tenant_id' => $targetTenantId,
            'user_id' => $superAdminId,
            'description' => "SUPER_ADMIN_RESTRICTION: {$reason}",
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $id ? AuditLog::find($id) : null;
    }

    public function logSubscriptionViolation(
        string $action,
        string $resourceType,
        ?string $resourceId,
        string $tenantId,
        ?int $userId,
        string $missingFeature
    ): ?AuditLog {
        $id = DB::table('audit_logs')->insertGetId([
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'tenant_id' => $tenantId,
            'user_id' => $userId,
            'description' => "SUBSCRIPTION_REQUIRED: Feature '{$missingFeature}' is required for this action",
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $id ? AuditLog::find($id) : null;
    }

    public function logCrossTenantAttempt(
        string $action,
        string $resourceType,
        ?string $resourceId,
        string $targetTenantId,
        ?int $userId,
        string $reason
    ): ?AuditLog {
        $id = DB::table('audit_logs')->insertGetId([
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'tenant_id' => $targetTenantId,
            'user_id' => $userId,
            'description' => "CROSS_TENANT_VIOLATION: {$reason}",
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $id ? AuditLog::find($id) : null;
    }
}
