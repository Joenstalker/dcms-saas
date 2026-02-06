<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalCondition;
use App\Models\Tenant;

class MedicalConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first tenant to seed data for
        // In a real multi-tenant app, you might want to loop through all tenants
        // or accept a specific tenant ID. For this request, we'll seed for the first one found.
        $tenant = Tenant::first();

        if (!$tenant) {
            $this->command->error('No tenant found. Please create a tenant first.');
            return;
        }

        $this->command->info("Seeding medical conditions for tenant: {$tenant->name} ({$tenant->slug})");

        $conditions = [
            ['name' => 'Dental Caries', 'icd_code' => 'K02.9', 'remarks' => 'Tooth decay requiring restorative treatment', 'is_active' => true],
            ['name' => 'Gingivitis', 'icd_code' => 'K05.10', 'remarks' => 'Inflammation of gums', 'is_active' => true],
            ['name' => 'Periodontitis', 'icd_code' => 'K05.30', 'remarks' => 'Advanced gum disease', 'is_active' => true],
            ['name' => 'Impacted Tooth', 'icd_code' => 'K01.1', 'remarks' => 'Tooth unable to erupt normally', 'is_active' => true],
            ['name' => 'Tooth Abscess', 'icd_code' => 'K04.7', 'remarks' => 'Localized infection with pus', 'is_active' => true],
            ['name' => 'Pulpitis', 'icd_code' => 'K04.0', 'remarks' => 'Inflammation of dental pulp', 'is_active' => true],
            ['name' => 'Bruxism', 'icd_code' => 'F45.8', 'remarks' => 'Teeth grinding or clenching', 'is_active' => true],
            ['name' => 'Temporomandibular Joint Disorder', 'icd_code' => 'K07.6', 'remarks' => 'Jaw joint dysfunction', 'is_active' => true],
            ['name' => 'Pericoronitis', 'icd_code' => 'K05.20', 'remarks' => 'Inflammation around erupting tooth', 'is_active' => true],
            ['name' => 'Oral Ulcer', 'icd_code' => 'K12.1', 'remarks' => 'Painful sore inside mouth', 'is_active' => true],
            ['name' => 'Tooth Fracture', 'icd_code' => 'S02.5', 'remarks' => 'Broken or cracked tooth', 'is_active' => true],
            ['name' => 'Missing Tooth', 'icd_code' => 'K08.1', 'remarks' => 'Loss of one or more teeth', 'is_active' => true],
            ['name' => 'Malocclusion', 'icd_code' => 'K07.4', 'remarks' => 'Misalignment of teeth', 'is_active' => true],
            ['name' => 'Dry Socket', 'icd_code' => 'K10.2', 'remarks' => 'Post-extraction complication', 'is_active' => true],
            ['name' => 'Dental Erosion', 'icd_code' => 'K03.2', 'remarks' => 'Chemical loss of tooth structure', 'is_active' => true],
        ];

        foreach ($conditions as $condition) {
            MedicalCondition::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'name' => $condition['name'],
                ],
                [
                    'icd_code' => $condition['icd_code'],
                    'remarks' => $condition['remarks'],
                    'is_active' => $condition['is_active'],
                ]
            );
        }

        $this->command->info('Medical conditions seeded successfully.');
    }
}
