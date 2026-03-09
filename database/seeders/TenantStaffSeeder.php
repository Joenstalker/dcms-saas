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
        $dentist = User::on('mongodb')->where('email', 'dentist@test.com')->first();
        if (!$dentist) {
            $dentist = new User();
            $dentist->setConnection('mongodb');
            $dentist->email = 'dentist@test.com';
        }
        $dentist->name = 'Dr. Cudal';
        $dentist->password = Hash::make('password');
        $dentist->role = 'dentist';
        $dentist->tenant_id = $tenant->id;
        $dentist->status = 'active';
        $dentist->must_reset_password = false;
        $dentist->save();

        // Create Assistant
        $assistant = User::on('mongodb')->where('email', 'assistant@test.com')->first();
        if (!$assistant) {
            $assistant = new User();
            $assistant->setConnection('mongodb');
            $assistant->email = 'assistant@test.com';
        }
        $assistant->name = 'Sarah Assistant';
        $assistant->password = Hash::make('password');
        $assistant->role = 'assistant';
        $assistant->tenant_id = $tenant->id;
        $assistant->status = 'active';
        $assistant->must_reset_password = false;
        $assistant->save();

        $this->command->info('Tenant staff seeded successfully.');
    }
}
