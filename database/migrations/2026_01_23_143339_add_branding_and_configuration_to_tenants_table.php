<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Setup completion flag
            $table->boolean('setup_completed')->default(false);
            
            // Branding fields
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->text('invoice_header')->nullable();
            $table->text('receipt_header')->nullable();
            
            // Business hours (stored as JSON)
            $table->json('business_hours')->nullable();
            
            // Consent & certificates configuration
            $table->json('consent_forms')->nullable();
            $table->json('certificate_templates')->nullable();
            
            // Default configurations
            $table->json('default_hmo_providers')->nullable();
            $table->json('default_dental_services')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'setup_completed',
                'primary_color',
                'secondary_color',
                'invoice_header',
                'receipt_header',
                'business_hours',
                'consent_forms',
                'certificate_templates',
                'default_hmo_providers',
                'default_dental_services',
            ]);
        });
    }
};
