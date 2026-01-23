<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['pricing_plan_id']);
            
            // Make pricing_plan_id nullable
            $table->foreignId('pricing_plan_id')->nullable()->change();
            
            // Re-add the foreign key constraint
            $table->foreign('pricing_plan_id')->references('id')->on('pricing_plans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['pricing_plan_id']);
            
            // Make pricing_plan_id required again
            $table->foreignId('pricing_plan_id')->nullable(false)->change();
            
            // Re-add the foreign key constraint
            $table->foreign('pricing_plan_id')->references('id')->on('pricing_plans');
        });
    }
};
