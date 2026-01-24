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
        Schema::table('tenants', function (Blueprint $table) {
            $table->enum('subscription_status', ['active', 'trial', 'expired', 'suspended', 'cancelled'])
                ->default('trial')
                ->after('subscription_ends_at');
            $table->timestamp('last_payment_date')->nullable()->after('subscription_status');
            $table->timestamp('suspended_at')->nullable()->after('last_payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['subscription_status', 'last_payment_date', 'suspended_at']);
        });
    }
};
