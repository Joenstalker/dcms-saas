<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateToMongo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:to-mongo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from MySQL to MongoDB';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration from MySQL to MongoDB...');

        // Verify connections
        try {
            DB::connection('mysql')->getPdo();
            // DB::connection('mongodb')->getPdo(); // Mongo driver might behave differently, just try query
        } catch (\Exception $e) {
            $this->error("Connection failed: " . $e->getMessage());
            return 1;
        }

        $tables = [
            'users',
            'password_reset_tokens',
            'sessions',
            'pricing_plans',
            'tenants',
            'permissions',
            'roles',
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',
            'cache',
            'cache_locks',
            'jobs',
            'failed_jobs',
            'job_batches',
            'media', // if spatie-medialibrary is used
            'patients',
            'appointments',
            'payments',
            'custom_themes',
            'platform_settings',
            'tenant_settings',
        ];

        // Fetch actual tables from mysql to be safe
        $mysqlTables = DB::connection('mysql')->select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $tables = array_column($mysqlTables, 'Tables_in_' . $dbName);

        foreach ($tables as $table) {
            if ($table === 'migrations') continue;

            $this->info("Migrating table: $table");
            
            try {
                // Use chunking for memory safety
                DB::connection('mysql')->table($table)->orderBy('id')->chunk(500, function ($rows) use ($table) {
                    $insertData = [];
                    foreach ($rows as $row) {
                        $data = (array) $row;
                        // Basic type conversion? MongoDB driver handles basics.
                        // We might want to ensure 'id' becomes '_id' or just keep 'id'.
                        // Keeping 'id' for relation compatibility.
                        
                        // Handle potential JSON fields that come as strings from MySQL
                        // This assumes we know which fields are JSON. Automatic detection is hard.
                        // We will copy raw for now.

                        // Insert individually to allow updateOrInsert equivalent if valid unique key exists
                        // Bulk insert is faster.
                        $insertData[] = $data;
                    }

                    if (!empty($insertData)) {
                       // We use raw collection insert
                       DB::connection('mongodb')->table($table)->insert($insertData);
                    }
                });
            } catch (\Exception $e) {
                // Some tables might not have 'id' column (like pivots), so orderBy('id') fails.
                // Fallback to simple get()
                $this->warn("Chunking failed for $table (maybe no id?), trying generic fetch...");
                try {
                   $rows = DB::connection('mysql')->table($table)->get();
                   $insertData = [];
                   foreach ($rows as $row) {
                       $insertData[] = (array) $row;
                   }
                   if (!empty($insertData)) {
                        DB::connection('mongodb')->table($table)->insert($insertData);
                   }
                } catch (\Exception $ex) {
                    $this->error("Failed to migrate $table: " . $ex->getMessage());
                }
            }
        }

        $this->info('Migration completed.');
    }
}
