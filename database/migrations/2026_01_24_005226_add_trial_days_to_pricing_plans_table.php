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
            $table->integer('trial_days')->default(0);
            $table->boolean('is_popular')->default(false);
            $table->string('badge_text')->nullable();
            $table->string('badge_color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->dropColumn(['trial_days', 'is_popular', 'badge_text', 'badge_color']);
        });
    }
};
