<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;
use App\Models\Tenant;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = Tenant::first();

        if (!$tenant) {
            $this->command->error('No tenant found. Please create a tenant first.');
            return;
        }

        $this->command->info("Seeding medicines for tenant: {$tenant->name} ({$tenant->slug})");

        $medicines = [
            ['name' => 'Amoxicillin 500mg', 'generic_name' => 'Amoxicillin', 'dosage' => '500 mg Capsule', 'is_active' => true],
            ['name' => 'Paracetamol 500mg', 'generic_name' => 'Acetaminophen', 'dosage' => '500 mg Tablet', 'is_active' => true],
            ['name' => 'Ibuprofen 400mg', 'generic_name' => 'Ibuprofen', 'dosage' => '400 mg Tablet', 'is_active' => true],
            ['name' => 'Mefenamic Acid 500mg', 'generic_name' => 'Mefenamic Acid', 'dosage' => '500 mg Capsule', 'is_active' => true],
            ['name' => 'Clindamycin 300mg', 'generic_name' => 'Clindamycin', 'dosage' => '300 mg Capsule', 'is_active' => true],
            ['name' => 'Azithromycin 500mg', 'generic_name' => 'Azithromycin', 'dosage' => '500 mg Tablet', 'is_active' => true],
            ['name' => 'Cephalexin 500mg', 'generic_name' => 'Cephalexin', 'dosage' => '500 mg Capsule', 'is_active' => true],
            ['name' => 'Metronidazole 500mg', 'generic_name' => 'Metronidazole', 'dosage' => '500 mg Tablet', 'is_active' => true],
            ['name' => 'Dexamethasone 0.5mg', 'generic_name' => 'Dexamethasone', 'dosage' => '0.5 mg Tablet', 'is_active' => true],
            ['name' => 'Prednisone 20mg', 'generic_name' => 'Prednisone', 'dosage' => '20 mg Tablet', 'is_active' => true],
            ['name' => 'Chlorhexidine Mouthwash', 'generic_name' => 'Chlorhexidine Gluconate', 'dosage' => '0.12% Solution', 'is_active' => true],
            ['name' => 'Lidocaine Gel', 'generic_name' => 'Lidocaine', 'dosage' => '2% Topical Gel', 'is_active' => true],
            ['name' => 'Benzocaine Gel', 'generic_name' => 'Benzocaine', 'dosage' => '20% Topical Gel', 'is_active' => true],
            ['name' => 'Vitamin C 500mg', 'generic_name' => 'Ascorbic Acid', 'dosage' => '500 mg Tablet', 'is_active' => true],
            ['name' => 'Multivitamins', 'generic_name' => 'Multivitamin Complex', 'dosage' => 'Tablet', 'is_active' => true],
        ];

        foreach ($medicines as $medicine) {
            Medicine::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'name' => $medicine['name'],
                ],
                [
                    'generic_name' => $medicine['generic_name'],
                    'dosage' => $medicine['dosage'],
                    'is_active' => $medicine['is_active'],
                ]
            );
        }

        $this->command->info('Medicines seeded successfully.');
    }
}
