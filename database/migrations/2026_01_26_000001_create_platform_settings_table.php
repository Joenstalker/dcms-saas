<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            $table->string('default_theme_primary', 7)->nullable();
            $table->string('default_theme_secondary', 7)->nullable();
            $table->string('default_sidebar_position', 10)->nullable();
            $table->string('default_font_family')->nullable();
            $table->string('default_logo_path')->nullable();
            $table->string('default_favicon_path')->nullable();
            $table->json('available_theme_colors')->nullable();
            $table->json('available_fonts')->nullable();
            $table->json('default_dashboard_widgets')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};
