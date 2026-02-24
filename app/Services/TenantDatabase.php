<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class TenantDatabase
{
    /**
     * Switch the mongodb connection to a specific tenant's database.
     */
    public static function switchToTenant(Tenant|string $tenant): void
    {
        $slug = $tenant instanceof Tenant ? $tenant->slug : $tenant;
        $dbName = 'db_' . $slug;

        config(['database.connections.mongodb.database' => $dbName]);
        DB::purge('mongodb');
        DB::reconnect('mongodb');
    }

    /**
     * Switch the mongodb connection back to the central (platform) database.
     */
    public static function switchToCentral(): void
    {
        $centralDb = env('MONGODB_CENTRAL_DATABASE', 'Dcms_saas_db');
        config(['database.connections.mongodb.database' => $centralDb]);
        DB::purge('mongodb');
        DB::reconnect('mongodb');
    }

    /**
     * Run a callback within a specific tenant's database context,
     * then automatically restore the central connection afterwards.
     *
     * @template TResult
     * @param Tenant|string $tenant
     * @param callable(): TResult $callback
     * @return TResult
     */
    public static function forTenant(Tenant|string $tenant, callable $callback): mixed
    {
        static::switchToTenant($tenant);

        try {
            return $callback();
        } finally {
            static::switchToCentral();
        }
    }

    /**
     * Get the database name for a given tenant slug.
     */
    public static function getDatabaseName(string $slug): string
    {
        return 'db_' . $slug;
    }
}
