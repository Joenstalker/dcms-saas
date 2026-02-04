<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

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
        Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        
        // Tenant Roles
        Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'dentist', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'assistant', 'guard_name' => 'web']);
    }
}
