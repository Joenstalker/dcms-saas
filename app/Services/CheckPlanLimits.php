<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Patient;
use App\Models\Tenant;
use App\Models\User;

class CheckPlanLimits
{
    /**
     * Check if a tenant has reached their user limit.
     */
    public function hasReachedUserLimit(Tenant $tenant): bool
    {
        $plan = $tenant->pricingPlan;
        if (!$plan || !$plan->max_users) {
            return false;
        }

        // Only count active/non-deleted staff from the tenant DB (exclude owners in central DB)
        $currentCount = User::on('mongodb')
            ->where('role', '!=', 'owner')
            ->whereNull('deleted_at') // Ensure soft deletes are excluded
            ->count();
            
        // Include the owner (in central db) in the total count
        return ($currentCount + 1) >= $plan->max_users;
    }

    /**
     * Check if a tenant has reached their patient limit.
     */
    public function hasReachedPatientLimit(Tenant $tenant): bool
    {
        $plan = $tenant->pricingPlan;
        if (!$plan) {
            return false;
        }

        // 0 or null max_patients means unlimited
        if (!$plan->max_patients || $plan->max_patients === 0) {
            return false;
        }

        $currentCount = Patient::where('tenant_id', $tenant->id)->count();
        return $currentCount >= $plan->max_patients;
    }

    /**
     * Check if a tenant has access to a specific feature.
     */
    public function canAccessFeature(Tenant $tenant, string $feature): bool
    {
        return $tenant->hasFeature($feature) || $tenant->hasFeature('all_features');
    }
}
