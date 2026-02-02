<?php

namespace Database\Seeders;

use App\Models\CustomTheme;
use App\Models\PlatformSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SystemInitializationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Platform Settings
        if (PlatformSetting::count() === 0) {
            PlatformSetting::create([
                'default_theme_primary' => '#3b82f6',
                'default_theme_secondary' => '#10b981',
                'default_sidebar_position' => 'left',
                'default_font_family' => 'figtree',
                'available_theme_colors' => ['#3b82f6', '#10b981', '#6366f1', '#1e293b'],
                'available_fonts' => ['figtree', 'roboto', 'inter'],
                'default_dashboard_widgets' => ['stats', 'appointments', 'recent_patients'],
            ]);
        }

        // 2. Create Initial Custom Themes
        if (CustomTheme::count() === 0) {
            CustomTheme::create([
                'name' => 'Dental Care Blue',
                'slug' => 'dental-blue',
                'colors' => [
                    'primary' => '#3b82f6',
                    'secondary' => '#10b981',
                    'accent' => '#6366f1',
                    'neutral' => '#1e293b',
                    'base-100' => '#ffffff',
                ],
                'is_active' => true,
            ]);
            
            CustomTheme::create([
                'name' => 'Modern Emerald',
                'slug' => 'modern-emerald',
                'colors' => [
                    'primary' => '#10b981',
                    'secondary' => '#3b82f6',
                    'accent' => '#f59e0b',
                    'neutral' => '#2d3748',
                    'base-100' => '#ffffff',
                ],
                'is_active' => true,
            ]);
        }

        // 3. Create Initial Admin User (if none exists)
        if (User::where('email', 'admin@dcms.local')->count() === 0) {
            $admin = User::create([
                'name' => 'System Admin',
                'email' => 'admin@dcms.local',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => User::ROLE_SYSTEM_ADMIN,
                'is_system_admin' => true,
            ]);
        }
    }
}
