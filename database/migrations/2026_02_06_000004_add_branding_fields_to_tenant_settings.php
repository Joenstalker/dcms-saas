<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenant_settings', function (Blueprint $table) {
            $table->string('theme_mode', 20)->nullable();
            $table->string('theme_color_accent', 7)->nullable();
            $table->string('theme_color_neutral', 7)->nullable();
            $table->string('theme_color_info', 7)->nullable();
            $table->string('theme_color_success', 7)->nullable();
            $table->string('theme_color_warning', 7)->nullable();
            $table->string('theme_color_error', 7)->nullable();
            $table->boolean('sidebar_collapsed')->default(false);
            $table->string('font_family_heading', 100)->nullable();
            $table->string('font_family_body', 100)->nullable();
            $table->string('dark_logo_path')->nullable();
            $table->string('login_bg_path')->nullable();
            $table->string('custom_brand_name', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tenant_settings', function (Blueprint $table) {
            $columns = [
                'theme_mode',
                'theme_color_accent',
                'theme_color_neutral',
                'theme_color_info',
                'theme_color_success',
                'theme_color_warning',
                'theme_color_error',
                'sidebar_collapsed',
                'font_family_heading',
                'font_family_body',
                'dark_logo_path',
                'login_bg_path',
                'custom_brand_name',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('tenant_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
