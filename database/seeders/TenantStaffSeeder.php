<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // For CLI seeding, we need to target the specific tenant database
        $tenantSlug = env('SEED_TENANT_SLUG', 'cudalblanco');
        $tenant = Tenant::where('slug', $tenantSlug)->first();

        if (!$tenant) {
            $this->command->error("Tenant with slug '{$tenantSlug}' not found.");
            return;
        }

        // Set the mongodb connection to target the tenant database
        $databaseName = "db_{$tenantSlug}";
        Config::set('database.connections.mongodb.database', $databaseName);
        DB::purge('mongodb');
        DB::reconnect('mongodb');

        $this->command->info("Seeding database: {$databaseName}");

        // Create Dentist
        User::on('mongodb')->updateOrCreate(
            ['email' => 'dentist@test.com'],
            [
                'name' => 'Dr. Cudal',
                'password' => Hash::make('password'),
                'role' => 'dentist',
                'tenant_id' => $tenant->id,
                'status' => 'active',
                'must_reset_password' => false,
            ]
        );

        // Create Assistant
        User::on('mongodb')->updateOrCreate(
            ['email' => 'assistant@test.com'],
            [
                'name' => 'Sarah Assistant',
                'password' => Hash::make('password'),
                'role' => 'assistant',
                'tenant_id' => $tenant->id,
                'status' => 'active',
                'must_reset_password' => false,
            ]
        );

        $this->command->info('Tenant staff seeded successfully.');
    }
}
