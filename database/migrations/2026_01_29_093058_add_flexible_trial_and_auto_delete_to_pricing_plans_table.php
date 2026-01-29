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
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->integer('trial_duration')->nullable()->after('billing_cycle');
            $table->enum('trial_unit', ['minutes', 'hours', 'days', 'weeks', 'months'])->default('days')->after('trial_duration');
            $table->boolean('auto_delete_after_trial')->default(false)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->dropColumn(['trial_duration', 'trial_unit', 'auto_delete_after_trial']);
        });
    }
};
