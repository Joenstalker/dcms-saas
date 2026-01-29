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
                'name' => 'Free Trial',
                'slug' => 'free-trial',
                'description' => 'Try the full experience before you commit.',
                'price' => 0.00,
                'billing_cycle' => 'monthly',
                'trial_days' => 7,
                'trial_duration' => 7,
                'trial_unit' => 'days',
                'auto_delete_after_trial' => true,
                'features' => [
                    'Calendar View',
                    'Patient Management (Limited)',
                    'Invoices',
                    'Staff Management',
                ],
                'max_users' => 3,
                'max_patients' => 150,
                'is_active' => true,
                'is_popular' => false,
                'badge_text' => 'Risk Free',
                'badge_color' => 'badge-info',
                'sort_order' => 0,
            ],
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Essential tools for small clinics.',
                'price' => 999.00,
                'billing_cycle' => 'monthly',
                'trial_days' => 0, // Paid plan
                'auto_delete_after_trial' => false,
                'features' => [
                    'Owner, Dentist & Assistant Portal (Max 3 Users)',
                    'Patient Management (150 Limit)',
                    'Appointment Calendar',
                    'Billing & Invoices',
                    'Staff Management (Add/Edit/Remove)',
                ],
                'max_users' => 3,
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
                'trial_days' => 14, // Can offer trial for Pro too, or 0 if immediate pay
                'auto_delete_after_trial' => false,
                'features' => [
                    'Everything in Basic',
                    'Up to 4 Users (Flexible Roles)',
                    'Up to 500 Patients',
                    'Online Booking (QR Code)',
                    'Payments & Collections',
                    'SMS Notifications',
                    'Enhanced Reports',
                    'Dashboard & Domain Customization',
                    'Full Staff Management View',
                ],
                'max_users' => 4,
                'max_patients' => 500,
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
                'trial_days' => 14,
                'auto_delete_after_trial' => false,
                'features' => [
                    'Everything in Pro',
                    '10 Users & 1000 Patients',
                    'Advanced Analytics',
                    'Full Customization',
                    'Multi-branch Readiness',
                    'Customizable Themes',
                ],
                'max_users' => 10,
                'max_patients' => 1000,
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
