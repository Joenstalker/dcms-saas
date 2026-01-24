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
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Try all features free for 7 days - Perfect to get started',
                'price' => 0.00,
                'billing_cycle' => 'monthly',
                'trial_days' => 7,
                'features' => [
                    'Patient Management',
                    'Appointment Scheduling',
                    'Basic Reports',
                    'Email Support',
                ],
                'max_users' => 2,
                'max_patients' => 50,
                'is_active' => true,
                'is_popular' => false,
                'badge_text' => 'Free Trial',
                'badge_color' => 'badge-info',
                'sort_order' => 0,
            ],
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Essential features for small clinics',
                'price' => 999.00,
                'billing_cycle' => 'monthly',
                'trial_days' => 7,
                'features' => [
                    'Patient Management',
                    'Appointment Scheduling',
                    'Basic Reports',
                    'Invoice & Receipts',
                    'Email Support',
                ],
                'max_users' => 3,
                'max_patients' => 500,
                'is_active' => true,
                'is_popular' => false,
                'badge_text' => 'Best for Starters',
                'badge_color' => 'badge-success',
                'sort_order' => 1,
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'Advanced features for growing clinics',
                'price' => 2499.00,
                'billing_cycle' => 'monthly',
                'trial_days' => 14,
                'features' => [
                    'All Basic Features',
                    'Advanced Reports & Analytics',
                    'Inventory Management',
                    'Financial Management',
                    'SMS Notifications',
                    'Priority Support',
                ],
                'max_users' => 10,
                'max_patients' => 2000,
                'is_active' => true,
                'is_popular' => true,
                'badge_text' => 'Most Popular',
                'badge_color' => 'badge-primary',
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Complete solution for large clinics and multi-branch operations',
                'price' => 4999.00,
                'billing_cycle' => 'monthly',
                'trial_days' => 14,
                'features' => [
                    'All Professional Features',
                    'Multi-Branch Management',
                    'Custom Branding',
                    'API Access',
                    'Advanced Customization',
                    'Dedicated Account Manager',
                    '24/7 Priority Support',
                    'Unlimited Everything',
                ],
                'max_users' => null, // Unlimited
                'max_patients' => null, // Unlimited
                'is_active' => true,
                'is_popular' => false,
                'badge_text' => 'Best Value',
                'badge_color' => 'badge-warning',
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            PricingPlan::updateOrCreate(
                ['slug' => $plan['slug']], // Find by slug
                $plan // Update or create with these values
            );
        }
    }
}
