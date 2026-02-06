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
                ;
            $table->timestamp('last_payment_date')->nullable();
            $table->timestamp('suspended_at')->nullable();
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
