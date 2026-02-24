<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TenantSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:seed {slug}';

    protected $description = 'Seed a specific tenant database with initial data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $slug = $this->argument('slug');
        $tenant = \App\Models\Tenant::where('slug', $slug)->first();

        if (!$tenant) {
            $this->error("Tenant with slug '{$slug}' not found.");
            return 1;
        }

        $this->info("Starting seeding for tenant: {$tenant->name} ({$tenant->slug})");

        // Bind tenant to container for seeders to use
        app()->instance('tenant', $tenant);

        // Switch 'mongodb' connection to tenant's database
        $dbName = 'db_' . $tenant->slug;
        \Illuminate\Support\Facades\Config::set('database.connections.mongodb.database', $dbName);
        \Illuminate\Support\Facades\DB::purge('mongodb');
        \Illuminate\Support\Facades\DB::reconnect('mongodb');

        $this->info("Connected to database: {$dbName}");

        // Call the seeders
        $this->call(\Database\Seeders\ServiceSeeder::class);
        $this->call(\Database\Seeders\MedicineSeeder::class);
        $this->call(\Database\Seeders\MedicalConditionSeeder::class);

        $this->info("Seeding completed for tenant: {$tenant->slug}");
        
        return 0;
    }
}
