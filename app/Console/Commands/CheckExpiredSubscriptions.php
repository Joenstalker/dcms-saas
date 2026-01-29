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

        $countSuspended = 0;
        $countDeleted = 0;

        foreach ($expiredTenants as $tenant) {
            $plan = $tenant->pricingPlan;

            // Check if plan is configured to auto-delete expired trials
            if ($tenant->subscription_status === 'trial' && 
                $plan && 
                $plan->auto_delete_after_trial) {
                
                $this->error("Deleting expired trial tenant: {$tenant->name} (ID: {$tenant->id})");
                
                // Permanent deletion
                $tenant->delete();
                $countDeleted++;
                continue;
            }

            $tenant->update([
                'subscription_status' => 'suspended',
                'suspended_at' => now(),
            ]);

            $this->warn("Suspended: {$tenant->name} (ID: {$tenant->id})");
            $countSuspended++;

            // TODO: Send notification email to tenant owner about suspension
        }

        if ($countSuspended > 0) {
            $this->info("✓ Suspended {$countSuspended} tenant(s).");
        }
        if ($countDeleted > 0) {
            $this->info("✓ Deleted {$countDeleted} expired trial tenant(s).");
        }

        return self::SUCCESS;
    }
}
