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

        // Check Users table
        $this->info('Checking Users table...');
        $userDuplicates = DB::table('users')
            ->select(DB::raw('LOWER(TRIM(email)) as normalized_email'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('LOWER(TRIM(email))'))
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($userDuplicates->isNotEmpty()) {
            $duplicatesFound = true;
            $this->error('Found duplicate emails in Users table:');
            $this->table(
                ['Email', 'Count', 'User IDs'],
                $userDuplicates->map(function ($duplicate) {
                    $users = User::whereRaw('LOWER(TRIM(email)) = ?', [$duplicate->normalized_email])
                        ->get(['id', 'email']);

                    return [
                        $users->first()->email ?? $duplicate->normalized_email,
                        $duplicate->count,
                        implode(', ', $users->pluck('id')->toArray()),
                    ];
                })
            );
        } else {
            $this->info('✓ No duplicates found in Users table.');
        }

        $this->newLine();

        // Check Tenants table
        $this->info('Checking Tenants table...');
        $tenantDuplicates = DB::table('tenants')
            ->select(DB::raw('LOWER(TRIM(email)) as normalized_email'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('LOWER(TRIM(email))'))
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($tenantDuplicates->isNotEmpty()) {
            $duplicatesFound = true;
            $this->error('Found duplicate emails in Tenants table:');
            $this->table(
                ['Email', 'Count', 'Tenant IDs'],
                $tenantDuplicates->map(function ($duplicate) {
                    $tenants = Tenant::whereRaw('LOWER(TRIM(email)) = ?', [$duplicate->normalized_email])
                        ->get(['id', 'email']);

                    return [
                        $tenants->first()->email ?? $duplicate->normalized_email,
                        $duplicate->count,
                        implode(', ', $tenants->pluck('id')->toArray()),
                    ];
                })
            );
        } else {
            $this->info('✓ No duplicates found in Tenants table.');
        }

        $this->newLine();

        // Check cross-table duplicates (email in both users and tenants)
        $this->info('Checking for emails that exist in both Users and Tenants...');
        $allUserEmails = User::select(DB::raw('LOWER(TRIM(email)) as email'))
            ->distinct()
            ->pluck('email')
            ->toArray();

        $crossDuplicates = Tenant::whereIn(DB::raw('LOWER(TRIM(email))'), $allUserEmails)
            ->get(['id', 'email', 'name']);

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
