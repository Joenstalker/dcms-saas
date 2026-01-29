<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'Patients' => ['view', 'create', 'edit', 'delete', 'dental-chart'],
            'Appointments' => ['view', 'create', 'edit', 'delete', 'status'],
            'Services' => ['view', 'create', 'edit', 'delete'],
            'Users' => ['view', 'create', 'edit', 'delete'],
            'Finance' => ['view', 'expenses', 'income', 'billing'],
            'Settings' => ['view', 'update', 'branding', 'themes'],
            'Dashboard' => ['view', 'stats'],
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $permissionName = strtolower($module) . '.' . $action;
                Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            }
        }

        // Create initial system admin role
        $adminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web', 'tenant_id' => null]);
        // Super admin typically has all permissions via Gate::before in AuthServiceProvider
    }
}
