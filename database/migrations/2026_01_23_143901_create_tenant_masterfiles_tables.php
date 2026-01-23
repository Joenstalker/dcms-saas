<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tenant Services Table
        Schema::create('tenant_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('category')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['tenant_id', 'is_active']);
        });

        // Tenant Medicines Table
        Schema::create('tenant_medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit')->default('piece'); // tablet, capsule, bottle, etc.
            $table->integer('stock')->default(0);
            $table->decimal('price', 10, 2)->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['tenant_id', 'is_active']);
        });

        // Tenant Templates Table
        Schema::create('tenant_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('name');
            $table->string('type'); // consent, certificate, invoice, receipt, etc.
            $table->text('content')->nullable();
            $table->json('fields')->nullable(); // Dynamic fields for templates
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index(['tenant_id', 'type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_templates');
        Schema::dropIfExists('tenant_medicines');
        Schema::dropIfExists('tenant_services');
    }
};
