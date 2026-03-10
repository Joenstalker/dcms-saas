<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PlatformUpdatePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create platform update permissions
        $permissions = [
            'platform.update' => 'Manage platform updates',
            'platform.view' => 'View platform update information',
            'platform.settings' => 'Manage platform update settings',
        ];

        foreach ($permissions as $name => $description) {
            $permission = Permission::findOrCreate($name, 'web');
            
            // Log creation
            $this->command->info("Created permission: {$name}");
        }

        // Assign permissions to super-admin role
        $superAdminRole = Role::where('name', 'super-admin')->first();
        
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo(array_keys($permissions));
            $this->command->info('Assigned all platform permissions to super-admin role');
        }
    }
}
