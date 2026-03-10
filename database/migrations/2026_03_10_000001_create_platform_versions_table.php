<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mongodb_central')->create('platform_versions', function (Blueprint $collection) {
            $collection->string('version')->unique();
            $collection->string('release_type'); // major, minor, patch, hotfix
            $collection->string('status'); // draft, testing, stable, deprecated
            $collection->text('release_notes')->nullable();
            $collection->string('min_database_version')->default('1.0.0');
            $collection->string('rollback_version')->nullable();
            $collection->boolean('is_auto_deploy')->default(false);
            $collection->timestamp('deployed_at')->nullable();
            $collection->string('created_by');
            $collection->string('update_channel')->default('stable'); // stable, beta, alpha
            $collection->string('download_url')->nullable();
            $collection->string('checksum')->nullable();
            $collection->integer('file_size')->default(0);
            $collection->timestamps();
        });

        // Add update fields to platform_settings
        Schema::connection('mongodb_central')->table('platform_settings', function (Blueprint $collection) {
            $collection->string('current_version')->default('1.0.0');
            $collection->string('min_supported_version')->default('1.0.0');
            $collection->string('update_channel')->default('stable');
            $collection->boolean('auto_update_enabled')->default(true);
            $collection->boolean('maintenance_mode')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb_central')->dropIfExists('platform_versions');
        
        Schema::connection('mongodb_central')->table('platform_settings', function (Blueprint $collection) {
            $collection->dropColumn([
                'current_version',
                'min_supported_version',
                'update_channel',
                'auto_update_enabled',
                'maintenance_mode',
            ]);
        });
    }
};
