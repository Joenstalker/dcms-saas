<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Medicine;
use App\Models\MedicalCondition;
use App\Models\ConsentTemplate;
use App\Models\CertificateTemplate;
use App\Models\PrescriptionTemplate;
use App\Services\TenantDatabase;
use Illuminate\Support\Facades\DB;

class MigrateTenantsToSeparateDatabases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate-to-db {tenant_slug? : The slug of a specific tenant to migrate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate tenant-specific data (including staff users) from the central database to isolated tenant databases.';

    /**
     * Tenant-scoped models that need to be migrated (excluding User/Payment which are handled separately).
     */
    protected $models = [
        Patient::class,
        Appointment::class,
        Service::class,
        Medicine::class,
        MedicalCondition::class,
        ConsentTemplate::class,
        CertificateTemplate::class,
        PrescriptionTemplate::class,
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $slug = $this->argument('tenant_slug');
        $tenants = $slug ? Tenant::where('slug', $slug)->get() : Tenant::all();

        if ($tenants->isEmpty()) {
            $this->error('No tenants found.');
            return 1;
        }

        $this->info('Starting migration for ' . $tenants->count() . ' tenants...');

        foreach ($tenants as $tenant) {
            $this->migrateTenant($tenant);
        }

        $this->info('Migration completed successfully!');
        return 0;
    }

    /**
     * Migrate a single tenant's data.
     */
    protected function migrateTenant(Tenant $tenant)
    {
        $this->info("Migrating tenant: {$tenant->name} ({$tenant->slug})");

        TenantDatabase::forTenant($tenant, function () use ($tenant) {
            // Migrate staff users (dentist/assistant) — owner stays in central
            $this->migrateStaffUsers($tenant);

            // Migrate all tenant-scoped models
            foreach ($this->models as $modelClass) {
                $this->migrateModel($tenant, $modelClass);
            }
        });

        $this->line("Done with {$tenant->slug}\n");
    }

    /**
     * Migrate dentist and assistant users from central to tenant database.
     * The tenant owner (role = 'tenant') stays in mongodb_central.
     */
    protected function migrateStaffUsers(Tenant $tenant)
    {
        // Fetch staff from the central database directly (bypass the dynamic connection)
        $staffUsers = \App\Models\User::on('mongodb_central')
            ->where('tenant_id', $tenant->id)
            ->whereIn('role', [\App\Models\User::ROLE_DENTIST, \App\Models\User::ROLE_ASSISTANT])
            ->get();

        if ($staffUsers->isEmpty()) {
            $this->comment('  - No staff users found to migrate');
            return;
        }

        $this->comment("  - Migrating {$staffUsers->count()} staff users");

        $count = 0;
        foreach ($staffUsers as $staffUser) {
            // Check if already migrated (avoid duplication)
            $exists = DB::connection('mongodb')
                ->collection('users')
                ->where('_id', $staffUser->_id)
                ->exists();

            if ($exists) {
                $this->comment("    ~ Skipped (already exists): {$staffUser->email}");
                continue;
            }

            // Insert the staff user directly into the tenant DB
            DB::connection('mongodb')
                ->collection('users')
                ->insert($staffUser->getAttributes());

            $count++;
        }

        $this->info("    + Successfully moved {$count} staff users");
    }

    /**
     * Migrate a specific model's data for a tenant from central to tenant DB.
     */
    protected function migrateModel(Tenant $tenant, string $modelClass)
    {
        $modelName = class_basename($modelClass);

        // Fetch from central database explicitly
        $records = $modelClass::on('mongodb_central')
            ->where('tenant_id', $tenant->id)
            ->get();

        if ($records->isEmpty()) {
            $this->comment("  - No records found for {$modelName}");
            return;
        }

        $this->comment("  - Migrating " . $records->count() . " records for {$modelName}");

        $count = 0;
        foreach ($records as $oldRecord) {
            $attributes = $oldRecord->getAttributes();

            // Check if already migrated
            $collectionName = (new $modelClass)->getTable();
            $exists = DB::connection('mongodb')
                ->collection($collectionName)
                ->where('_id', $attributes['_id'])
                ->exists();

            if ($exists) {
                continue;
            }

            DB::connection('mongodb')
                ->collection($collectionName)
                ->insert($attributes);

            $count++;
        }

        $this->info("    + Successfully moved {$count} records for {$modelName}");
    }
}

