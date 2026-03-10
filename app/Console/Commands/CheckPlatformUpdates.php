<?php

namespace App\Console\Commands;

use App\Models\PlatformSetting;
use App\Services\PlatformUpdateService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckPlatformUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'platform:check-updates
                            {--force : Force the update check even if disabled}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for platform updates and auto-deploy if enabled';

    private PlatformUpdateService $updateService;

    public function __construct(PlatformUpdateService $updateService)
    {
        parent::__construct();
        $this->updateService = $updateService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for platform updates...');
        Log::info('Starting scheduled platform update check');

        // Check if auto-update is enabled
        $settings = PlatformSetting::first();
        
        if (!$settings?->isAutoUpdateEnabled() && !$this->option('force')) {
            $this->info('Auto-update is disabled. Skipping...');
            Log::info('Auto-update is disabled, skipping check');
            return Command::SUCCESS;
        }

        // Check for maintenance mode
        if ($this->updateService->isMaintenanceMode()) {
            $this->warn('Maintenance mode is active. Skipping...');
            Log::info('Maintenance mode is active, skipping update check');
            return Command::SUCCESS;
        }

        // Check for updates
        $updateInfo = $this->updateService->checkForUpdates();

        if (!$updateInfo) {
            $this->info('No updates available.');
            Log::info('No platform updates available');
            return Command::SUCCESS;
        }

        $this->info("Update available: {$updateInfo['latest_version']}");
        
        // Get the latest stable version for auto-deployment
        $versions = $this->updateService->getAvailableVersions();
        
        // Find the latest stable version
        $latestStable = null;
        foreach ($versions as $version) {
            if ($version['status'] === 'stable' && $version['is_auto_deploy']) {
                $latestStable = $version;
                break;
            }
        }

        if (!$latestStable) {
            $this->info('No auto-deployable versions available.');
            return Command::SUCCESS;
        }

        $currentVersion = $this->updateService->getCurrentVersion();

        // Check if we should auto-deploy
        if (version_compare($latestStable['version'], $currentVersion, '>')) {
            $this->info("Auto-deploying version {$latestStable['version']}...");
            Log::info("Auto-deploying platform to version {$latestStable['version']}");

            try {
                $result = $this->updateService->deployVersion($latestStable['version']);

                if ($result['success']) {
                    $this->info("Successfully deployed version {$latestStable['version']}");
                    $this->notifyAdmins($latestStable['version']);
                } else {
                    $this->error("Failed to deploy: {$result['message']}");
                    Log::error("Auto-deployment failed: {$result['message']}");
                }
            } catch (\Exception $e) {
                $this->error("Deployment error: {$e->getMessage()}");
                Log::error("Auto-deployment exception: {$e->getMessage()}");
            }
        } else {
            $this->info('Already running the latest version.');
        }

        return Command::SUCCESS;
    }

    /**
     * Notify admins about the update
     */
    private function notifyAdmins(string $version): void
    {
        // In a production system, this would send emails or notifications
        // to all platform administrators
        Log::info("Platform automatically updated to version {$version}");
        
        $this->info("Admins would be notified about the update to {$version}");
    }
}
