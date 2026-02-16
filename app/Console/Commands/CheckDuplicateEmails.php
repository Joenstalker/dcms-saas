<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDuplicateEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:check-duplicates {--fix : Fix duplicates by keeping the first record and marking others}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for duplicate emails in users and tenants tables (case-insensitive)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for duplicate emails...');
        $this->newLine();

        $duplicatesFound = false;

        // Check Users collection
        $this->info('Checking Users collection...');
        $userDuplicates = User::raw(function($collection) {
            return $collection->aggregate([
                [
                    '$project' => [
                        'normalized_email' => ['$toLower' => ['$trim' => ['input' => '$email']]]
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$normalized_email',
                        'count' => ['$sum' => 1]
                    ]
                ],
                [
                    '$match' => [
                        'count' => ['$gt' => 1]
                    ]
                ]
            ]);
        });

        $userDuplicatesCollection = collect($userDuplicates);

        if ($userDuplicatesCollection->isNotEmpty()) {
            $duplicatesFound = true;
            $this->error('Found duplicate emails in Users collection:');
            $this->table(
                ['Email', 'Count', 'User IDs'],
                $userDuplicatesCollection->map(function ($duplicate) {
                    $email = $duplicate['_id'];
                    $users = User::where('email', 'regex', "/^{$email}$/i")->get(['_id', 'email']);

                    return [
                        $email,
                        $duplicate['count'],
                        implode(', ', $users->pluck('_id')->toArray()),
                    ];
                })
            );
        } else {
            $this->info('✓ No duplicates found in Users collection.');
        }

        $this->newLine();

        // Check Tenants collection
        $this->info('Checking Tenants collection...');
        $tenantDuplicates = Tenant::raw(function($collection) {
            return $collection->aggregate([
                [
                    '$project' => [
                        'normalized_email' => ['$toLower' => ['$trim' => ['input' => '$email']]]
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$normalized_email',
                        'count' => ['$sum' => 1]
                    ]
                ],
                [
                    '$match' => [
                        'count' => ['$gt' => 1]
                    ]
                ]
            ]);
        });

        $tenantDuplicatesCollection = collect($tenantDuplicates);

        if ($tenantDuplicatesCollection->isNotEmpty()) {
            $duplicatesFound = true;
            $this->error('Found duplicate emails in Tenants collection:');
            $this->table(
                ['Email', 'Count', 'Tenant IDs'],
                $tenantDuplicatesCollection->map(function ($duplicate) {
                    $email = $duplicate['_id'];
                    $tenants = Tenant::where('email', 'regex', "/^{$email}$/i")->get(['_id', 'email']);

                    return [
                        $email,
                        $duplicate['count'],
                        implode(', ', $tenants->pluck('_id')->toArray()),
                    ];
                })
            );
        } else {
            $this->info('✓ No duplicates found in Tenants collection.');
        }

        $this->newLine();

        // Check cross-collection duplicates (email in both users and tenants)
        $this->info('Checking for emails that exist in both Users and Tenants...');
        $allUserEmails = User::get(['email'])->map(function($user) {
            return strtolower(trim($user->email));
        })->unique()->toArray();

        // MongoDB chunk processing if list is huge, but for this utility, whereIn should suffice
        $crossDuplicates = Tenant::where(function($query) use ($allUserEmails) {
            foreach ($allUserEmails as $email) {
                $query->orWhere('email', 'regex', "/^{$email}$/i");
            }
        })->get(['_id', 'email', 'name']);

        if ($crossDuplicates->isNotEmpty()) {
            $duplicatesFound = true;
            $this->error('Found emails that exist in both Users and Tenants:');
            $this->table(
                ['Tenant ID', 'Email', 'Tenant Name'],
                $crossDuplicates->map(function ($tenant) {
                    return [
                        $tenant->id,
                        $tenant->email,
                        $tenant->name,
                    ];
                })
            );
        } else {
            $this->info('✓ No cross-table duplicates found.');
        }

        $this->newLine();

        if ($duplicatesFound) {
            $this->error('⚠️  Duplicate emails found! Please review and fix them.');
            $this->warn('Note: The validation has been updated to prevent new duplicates.');
            $this->warn('You may need to clean up existing duplicates before running migrations.');

            return Command::FAILURE;
        }

        $this->info('✓ No duplicate emails found. Database is clean!');

        return Command::SUCCESS;
    }
}
