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
                'description' => 'Perfect for small clinics just getting started',
                'price' => 999.00,
                'billing_cycle' => 'monthly',
                'features' => ['patients', 'appointments', 'basic_reports'],
                'max_users' => 3,
                'max_patients' => 500,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'For growing clinics with more needs',
                'price' => 2499.00,
                'billing_cycle' => 'monthly',
                'features' => ['patients', 'appointments', 'advanced_reports', 'inventory', 'financial_management'],
                'max_users' => 10,
                'max_patients' => 2000,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Ultimate',
                'slug' => 'ultimate',
                'description' => 'Complete solution for large clinics',
                'price' => 4999.00,
                'billing_cycle' => 'monthly',
                'features' => ['patients', 'appointments', 'advanced_reports', 'inventory', 'financial_management', 'customization', 'api_access', 'priority_support'],
                'max_users' => null, // Unlimited
                'max_patients' => null, // Unlimited
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            PricingPlan::create($plan);
        }
    }
}
