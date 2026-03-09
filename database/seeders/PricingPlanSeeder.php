<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PricingPlan;
use Illuminate\Database\Seeder;

class PricingPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Essential tools for small clinics.',
                'price' => 999.00,
                'billing_cycle' => 'monthly',
                'trial_days' => 7,
                'auto_delete_after_trial' => false,
                'features' => [
                    'online_booking',
                    'appointments',
                    'patients',
                    'billing_pos',
                    'basic_reports',
                ],
                'max_users' => 4,
                'max_patients' => 150,
                'is_active' => true,
                'is_popular' => false,
                'badge_text' => 'Starter',
                'badge_color' => 'badge-secondary',
                'sort_order' => 1,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'Advanced features for growing practices.',
                'price' => 2499.00,
                'billing_cycle' => 'monthly',
                'trial_days' => 0,
                'auto_delete_after_trial' => false,
                'features' => [
                    'online_booking',
                    'appointments',
                    'patients',
                    'billing_pos',
                    'basic_reports',
                    'sms_notifications',
                    'enhanced_reports',
                    'custom_branding',
                ],
                'max_users' => 6,
                'max_patients' => 500, // "Higher" limit
                'is_active' => true,
                'is_popular' => true,
                'badge_text' => 'Most Popular',
                'badge_color' => 'badge-primary',
                'sort_order' => 2,
            ],
            [
                'name' => 'Ultimate',
                'slug' => 'ultimate',
                'description' => 'Maximum power for multi-branch clinics.',
                'price' => 4999.00,
                'billing_cycle' => 'monthly',
                'trial_days' => 0,
                'auto_delete_after_trial' => false,
                'features' => [
                    'online_booking',
                    'appointments',
                    'patients',
                    'billing_pos',
                    'basic_reports',
                    'sms_notifications',
                    'enhanced_reports',
                    'custom_branding',
                    'advanced_analytics',
                    'priority_support',
                    'multi_branch',
                ],
                'max_users' => 10,
                'max_patients' => 0, // Unlimited
                'is_active' => true,
                'is_popular' => false,
                'badge_text' => 'Best Value',
                'badge_color' => 'badge-warning',
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            PricingPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
