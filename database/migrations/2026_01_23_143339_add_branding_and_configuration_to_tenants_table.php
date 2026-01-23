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
            $table->boolean('setup_completed')->default(false)->after('is_active');
            
            // Branding fields
            $table->string('primary_color')->nullable()->after('logo');
            $table->string('secondary_color')->nullable()->after('primary_color');
            $table->text('invoice_header')->nullable()->after('secondary_color');
            $table->text('receipt_header')->nullable()->after('invoice_header');
            
            // Business hours (stored as JSON)
            $table->json('business_hours')->nullable()->after('receipt_header');
            
            // Consent & certificates configuration
            $table->json('consent_forms')->nullable()->after('business_hours');
            $table->json('certificate_templates')->nullable()->after('consent_forms');
            
            // Default configurations
            $table->json('default_hmo_providers')->nullable()->after('certificate_templates');
            $table->json('default_dental_services')->nullable()->after('default_hmo_providers');
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
