<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TenantProvisioningService
{
    public function provision(Tenant $tenant): bool
    {
        try {
            DB::beginTransaction();

            // 1. Create default roles for the tenant
            $this->createDefaultRoles($tenant);

            // 2. Assign owner role to the owner user
            $this->assignOwnerRole($tenant);

            // 3. Create default masterfiles
            $this->createDefaultMasterfiles($tenant);

            // 4. Set up subdomain/domain
            $this->setupDomain($tenant);

            // 5. Create dashboard modules based on plan
            $this->setupDashboardModules($tenant);

            DB::commit();

            Log::info('Tenant environment provisioned successfully', [
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
            ]);

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to provision tenant environment', [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    protected function createDefaultRoles(Tenant $tenant): void
    {
        // Create tenant-specific roles: Owner, Dentist, Assistant
        // Owner can be business owner or dentist itself
        $roles = [
            [
                'name' => 'owner',
                'guard_name' => 'web',
            ],
            [
                'name' => 'dentist',
                'guard_name' => 'web',
            ],
            [
                'name' => 'assistant',
                'guard_name' => 'web',
            ],
        ];

        foreach ($roles as $roleData) {
            // Create role scoped to this tenant
            $role = Role::firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'name' => $roleData['name'],
                    'guard_name' => $roleData['guard_name'],
                ]
            );

            // Assign permissions based on role
            $this->assignRolePermissions($role, $roleData['name'], $tenant);
        }
    }

    protected function assignRolePermissions(Role $role, string $roleType, Tenant $tenant): void
    {
        $permissions = [];

        switch ($roleType) {
            case 'owner':
                // Owner has all permissions
                $permissions = [
                    'manage-patients', 'view-patients', 'create-patients', 'edit-patients', 'delete-patients',
                    'manage-appointments', 'view-appointments', 'create-appointments', 'edit-appointments', 'delete-appointments',
                    'manage-users', 'view-users', 'create-users', 'edit-users', 'delete-users',
                    'manage-services', 'manage-medicines', 'manage-templates',
                    'view-reports', 'manage-settings', 'manage-billing',
                ];
                break;

            case 'dentist':
                // Dentist can manage patients and appointments
                $permissions = [
                    'manage-patients', 'view-patients', 'create-patients', 'edit-patients',
                    'manage-appointments', 'view-appointments', 'create-appointments', 'edit-appointments',
                    'view-services', 'view-medicines', 'view-templates',
                    'view-reports',
                ];
                break;

            case 'assistant':
                // Assistant has limited access
                $permissions = [
                    'view-patients', 'create-patients', 'edit-patients',
                    'view-appointments', 'create-appointments', 'edit-appointments',
                    'view-services', 'view-medicines',
                ];
                break;
        }

        // Create permissions if they don't exist and assign to role
        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(
                [
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]
            );

            if (!$role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
            }
        }
    }

    protected function assignOwnerRole(Tenant $tenant): void
    {
        // Find the owner user (the one who signed up)
        $owner = User::where('tenant_id', $tenant->id)
            ->where('email', $tenant->email)
            ->first();

        if ($owner) {
            // Assign owner role (scoped to this tenant)
            $role = Role::where('tenant_id', $tenant->id)
                ->where('name', 'owner')
                ->first();
            
            if ($role && !$owner->hasRole($role)) {
                $owner->assignRole($role);
            }
        }
    }

    protected function createDefaultMasterfiles(Tenant $tenant): void
    {
        // Create default dental services
        $this->createDefaultServices($tenant);

        // Create default medicines
        $this->createDefaultMedicines($tenant);

        // Create default templates
        $this->createDefaultTemplates($tenant);
    }

    protected function createDefaultServices(Tenant $tenant): void
    {
        // Use default_dental_services from tenant setup if available
        if ($tenant->default_dental_services && count($tenant->default_dental_services) > 0) {
            $services = $tenant->default_dental_services;
        } else {
            // Fallback to system defaults
            $services = [
                ['name' => 'Dental Cleaning', 'price' => 500.00, 'category' => 'Preventive'],
                ['name' => 'Tooth Extraction', 'price' => 800.00, 'category' => 'Surgical'],
                ['name' => 'Filling', 'price' => 1200.00, 'category' => 'Restorative'],
                ['name' => 'Root Canal', 'price' => 5000.00, 'category' => 'Endodontic'],
                ['name' => 'Teeth Whitening', 'price' => 3000.00, 'category' => 'Cosmetic'],
            ];
        }

        foreach ($services as $service) {
            // Handle both array and string formats
            if (is_array($service)) {
                DB::table('tenant_services')->insert([
                    'tenant_id' => $tenant->id,
                    'name' => $service['name'] ?? 'Service',
                    'price' => $service['price'] ?? 0.00,
                    'category' => $service['category'] ?? 'General',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('tenant_services')->insert([
                    'tenant_id' => $tenant->id,
                    'name' => $service,
                    'price' => 0.00,
                    'category' => 'General',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    protected function createDefaultMedicines(Tenant $tenant): void
    {
        $defaultMedicines = [
            ['name' => 'Paracetamol 500mg', 'unit' => 'tablet', 'stock' => 0],
            ['name' => 'Amoxicillin 500mg', 'unit' => 'capsule', 'stock' => 0],
            ['name' => 'Ibuprofen 400mg', 'unit' => 'tablet', 'stock' => 0],
            ['name' => 'Chlorhexidine Mouthwash', 'unit' => 'bottle', 'stock' => 0],
            ['name' => 'Dental Floss', 'unit' => 'pack', 'stock' => 0],
        ];

        foreach ($defaultMedicines as $medicine) {
            DB::table('tenant_medicines')->insert([
                'tenant_id' => $tenant->id,
                'name' => $medicine['name'],
                'unit' => $medicine['unit'],
                'stock' => $medicine['stock'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    protected function createDefaultTemplates(Tenant $tenant): void
    {
        // Use consent_forms and certificate_templates from tenant setup if available
        $consentForms = $tenant->consent_forms ?? ['Treatment Consent Form'];
        $certificateTemplates = $tenant->certificate_templates ?? ['Medical Certificate'];

        // Create consent form templates
        foreach ($consentForms as $formName) {
            DB::table('tenant_templates')->insert([
                'tenant_id' => $tenant->id,
                'name' => is_array($formName) ? ($formName['name'] ?? 'Consent Form') : $formName,
                'type' => 'consent',
                'content' => is_array($formName) ? ($formName['content'] ?? 'I consent to the proposed dental treatment...') : 'I consent to the proposed dental treatment...',
                'is_active' => true,
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create certificate templates
        foreach ($certificateTemplates as $templateName) {
            DB::table('tenant_templates')->insert([
                'tenant_id' => $tenant->id,
                'name' => is_array($templateName) ? ($templateName['name'] ?? 'Certificate') : $templateName,
                'type' => 'certificate',
                'content' => is_array($templateName) ? ($templateName['content'] ?? 'This certifies that...') : 'This certifies that...',
                'is_active' => true,
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    protected function setupDomain(Tenant $tenant): void
    {
        // Set up subdomain
        if (!$tenant->domain) {
            $tenant->update([
                'domain' => $tenant->slug . '.dcmsapp.com',
            ]);
        }
    }

    protected function setupDashboardModules(Tenant $tenant): void
    {
        // Get modules based on pricing plan features
        $plan = $tenant->pricingPlan;
        
        if (!$plan) {
            return;
        }

        $modules = [];

        // Base modules available to all plans
        $baseModules = [
            'patients',
            'appointments',
            'basic_reports',
        ];

        $modules = array_merge($modules, $baseModules);

        // Add modules based on plan features
        if ($plan->features && is_array($plan->features)) {
            // Map feature names to module names
            $featureToModuleMap = [
                'patients' => 'patients',
                'appointments' => 'appointments',
                'basic_reports' => 'basic_reports',
                'advanced_reports' => 'advanced_reports',
                'inventory' => 'inventory',
                'financial_management' => 'financial_management',
                'customization' => 'customization',
                'api_access' => 'api_access',
                'priority_support' => 'priority_support',
            ];

            foreach ($plan->features as $feature) {
                if (isset($featureToModuleMap[$feature])) {
                    $modules[] = $featureToModuleMap[$feature];
                } else {
                    $modules[] = $feature; // Use feature name as module name
                }
            }
        }

        // Store modules in tenant settings
        $settings = $tenant->settings ?? [];
        $settings['dashboard_modules'] = array_values(array_unique($modules));
        $settings['provisioned_at'] = now()->toDateTimeString();
        
        $tenant->update([
            'settings' => $settings,
        ]);
    }
}
