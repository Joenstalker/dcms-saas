<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class CheckExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and suspend tenants with expired subscriptions';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for expired subscriptions...');

        // Find active or trial tenants whose subscription has ended
        $expiredTenants = Tenant::whereIn('subscription_status', ['active', 'trial'])
            ->where(function ($query) {
                $query->where('subscription_ends_at', '<=', now())
                    ->orWhere(function ($q) {
                        $q->where('subscription_status', 'trial')
                            ->where('trial_ends_at', '<=', now());
                    });
            })
            ->get();

        if ($expiredTenants->isEmpty()) {
            $this->info('No expired subscriptions found.');

            return self::SUCCESS;
        }

        $count = 0;
        foreach ($expiredTenants as $tenant) {
            $tenant->update([
                'subscription_status' => 'suspended',
                'suspended_at' => now(),
            ]);

            $this->warn("Suspended: {$tenant->name} (ID: {$tenant->id})");
            $count++;

            // TODO: Send notification email to tenant owner about suspension
        }

        $this->info("✓ Suspended {$count} tenant(s) with expired subscriptions.");

        return self::SUCCESS;
    }
}
