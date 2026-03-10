<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PlatformSetting;
use App\Models\PlatformVersion;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Exception;

class PlatformUpdateService
{
    private const CACHE_KEY_CURRENT_VERSION = 'platform_current_version';
    private const CACHE_KEY_AVAILABLE_UPDATES = 'platform_available_updates';
    private const UPDATE_SERVER_URL = 'https://updates.dcms-saas.com/api'; // Placeholder URL

    /**
     * Get current platform version
     */
    public function getCurrentVersion(): string
    {
        return Cache::remember(self::CACHE_KEY_CURRENT_VERSION, now()->addHours(24), function () {
            $settings = PlatformSetting::first();
            return $settings?->getCurrentVersion() ?? '1.0.0';
        });
    }

    /**
     * Check for available updates from update server
     */
    public function checkForUpdates(): ?array
    {
        try {
            $currentVersion = $this->getCurrentVersion();
            $settings = PlatformSetting::first();
            $channel = $settings?->update_channel ?? 'stable';

            // In production, this would call the actual update server
            // For now, we'll check locally stored versions
            $availableVersions = PlatformVersion::forChannel($channel)
                ->stable()
                ->orderBy('version', 'desc')
                ->get();

            $latestVersion = $availableVersions->first();
            
            if ($latestVersion && $latestVersion->isNewerThan($currentVersion)) {
                return [
                    'current_version' => $currentVersion,
                    'latest_version' => $latestVersion->version,
                    'release_type' => $latestVersion->release_type,
                    'release_notes' => $latestVersion->release_notes,
                    'min_database_version' => $latestVersion->min_database_version,
                    'download_url' => $latestVersion->download_url,
                    'checksum' => $latestVersion->checksum,
                    'file_size' => $latestVersion->file_size,
                ];
            }

            return null;
        } catch (Exception $e) {
            Log::error('Failed to check for updates: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all available versions
     */
    public function getAvailableVersions(): array
    {
        return Cache::remember(self::CACHE_KEY_AVAILABLE_UPDATES, now()->addMinutes(30), function () {
            $settings = PlatformSetting::first();
            $channel = $settings?->update_channel ?? 'stable';

            return PlatformVersion::forChannel($channel)
                ->orderBy('version', 'desc')
                ->get()
                ->toArray();
        });
    }

    /**
     * Deploy a specific version
     */
    public function deployVersion(string $version): array
    {
        try {
            $platformVersion = PlatformVersion::where('version', $version)->first();
            
            if (!$platformVersion) {
                return [
                    'success' => false,
                    'message' => "Version {$version} not found",
                ];
            }

            if ($platformVersion->status !== PlatformVersion::STATUS_STABLE) {
                return [
                    'success' => false,
                    'message' => "Version {$version} is not stable and cannot be deployed",
                ];
            }

            // Create backup before deployment
            $this->createBackup();

            // Enable maintenance mode
            $this->enableMaintenanceMode();

            // Run pre-deployment tasks
            $this->runPreDeploymentTasks($platformVersion);

            // Update the platform version in settings
            $settings = PlatformSetting::first() ?? new PlatformSetting();
            $settings->current_version = $version;
            $settings->save();

            // Mark version as deployed
            $platformVersion->deployed_at = now();
            $platformVersion->save();

            // Run post-deployment tasks
            $this->runPostDeploymentTasks($platformVersion);

            // Disable maintenance mode
            $this->disableMaintenanceMode();

            // Clear caches
            $this->clearCaches();

            // Log the deployment
            Log::info("Platform updated to version {$version}");

            return [
                'success' => true,
                'message' => "Successfully deployed version {$version}",
                'version' => $version,
            ];
        } catch (Exception $e) {
            Log::error("Failed to deploy version {$version}: " . $e->getMessage());
            
            // Attempt rollback on failure
            $this->rollback();

            return [
                'success' => false,
                'message' => "Deployment failed: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Rollback to previous version
     */
    public function rollback(): array
    {
        try {
            $currentVersion = $this->getCurrentVersion();
            $platformVersion = PlatformVersion::where('version', $currentVersion)->first();

            if (!$platformVersion || !$platformVersion->canRollback()) {
                return [
                    'success' => false,
                    'message' => 'No rollback version available',
                ];
            }

            $rollbackVersion = $platformVersion->rollback_version;

            // Enable maintenance mode
            $this->enableMaintenanceMode();

            // Restore from backup
            $this->restoreFromBackup();

            // Update platform version
            $settings = PlatformSetting::first();
            if ($settings) {
                $settings->current_version = $rollbackVersion;
                $settings->save();
            }

            // Disable maintenance mode
            $this->disableMaintenanceMode();

            // Clear caches
            $this->clearCaches();

            Log::info("Platform rolled back to version {$rollbackVersion}");

            return [
                'success' => true,
                'message' => "Successfully rolled back to version {$rollbackVersion}",
                'version' => $rollbackVersion,
            ];
        } catch (Exception $e) {
            Log::error("Failed to rollback: " . $e->getMessage());

            return [
                'success' => false,
                'message' => "Rollback failed: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Create a backup of the current platform state
     */
    public function createBackup(): void
    {
        $backupPath = storage_path('app/backups');
        
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $timestamp = now()->format('Y-m-d_His');
        
        // Backup .env file
        if (File::exists(base_path('.env'))) {
            File::copy(
                base_path('.env'),
                "{$backupPath}/.env.backup.{$timestamp}"
            );
        }

        // Backup composer.json
        if (File::exists(base_path('composer.json'))) {
            File::copy(
                base_path('composer.json'),
                "{$backupPath}/composer.backup.{$timestamp}.json"
            );
        }

        // Store backup metadata
        Cache::put('platform_backup_timestamp', $timestamp, now()->addDays(30));

        Log::info("Platform backup created: {$timestamp}");
    }

    /**
     * Restore platform from backup
     */
    public function restoreFromBackup(): bool
    {
        try {
            $timestamp = Cache::get('platform_backup_timestamp');
            
            if (!$timestamp) {
                throw new Exception('No backup found');
            }

            $backupPath = storage_path('app/backups');

            // Restore .env file
            $envBackup = "{$backupPath}/.env.backup.{$timestamp}";
            if (File::exists($envBackup)) {
                File::copy($envBackup, base_path('.env'));
            }

            // Restore composer.json
            $composerBackup = "{$backupPath}/composer.backup.{$timestamp}.json";
            if (File::exists($composerBackup)) {
                File::copy($composerBackup, base_path('composer.json'));
            }

            Log::info("Platform restored from backup: {$timestamp}");

            return true;
        } catch (Exception $e) {
            Log::error("Failed to restore from backup: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Enable maintenance mode
     */
    public function enableMaintenanceMode(): void
    {
        $settings = PlatformSetting::first() ?? new PlatformSetting();
        $settings->maintenance_mode = true;
        $settings->save();

        // Create maintenance mode file
        File::put(
            storage_path('app/maintenance.mode'),
            json_encode([
                'enabled_at' => now()->toIso8601String(),
                'reason' => 'Platform update in progress',
            ])
        );
    }

    /**
     * Disable maintenance mode
     */
    public function disableMaintenanceMode(): void
    {
        $settings = PlatformSetting::first();
        if ($settings) {
            $settings->maintenance_mode = false;
            $settings->save();
        }

        // Remove maintenance mode file
        $maintenanceFile = storage_path('app/maintenance.mode');
        if (File::exists($maintenanceFile)) {
            File::delete($maintenanceFile);
        }
    }

    /**
     * Check if maintenance mode is active
     */
    public function isMaintenanceMode(): bool
    {
        $maintenanceFile = storage_path('app/maintenance.mode');
        
        if (File::exists($maintenanceFile)) {
            return true;
        }

        $settings = PlatformSetting::first();
        return $settings?->isMaintenanceMode() ?? false;
    }

    /**
     * Run pre-deployment tasks
     */
    private function runPreDeploymentTasks(PlatformVersion $version): void
    {
        Log::info("Running pre-deployment tasks for version {$version->version}");
        
        // Run composer install in dry-run to verify dependencies
        // This would be executed as a shell command in production
    }

    /**
     * Run post-deployment tasks
     */
    private function runPostDeploymentTasks(PlatformVersion $version): void
    {
        Log::info("Running post-deployment tasks for version {$version->version}");
        
        // Run database migrations
        try {
            Artisan::call('migrate', ['--force' => true]);
            Log::info('Database migrations completed');
        } catch (Exception $e) {
            Log::error('Database migration failed: ' . $e->getMessage());
            throw $e;
        }

        // Clear all caches
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Log::info('Caches cleared');
        } catch (Exception $e) {
            Log::warning('Cache clear failed: ' . $e->getMessage());
        }

        // Optimize the application
        try {
            Artisan::call('optimize');
        } catch (Exception $e) {
            Log::warning('Optimization failed: ' . $e->getMessage());
        }
    }

    /**
     * Clear all application caches
     */
    private function clearCaches(): void
    {
        Cache::flush();
        
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
        } catch (Exception $e) {
            Log::warning('Cache clear failed: ' . $e->getMessage());
        }
    }

    /**
     * Update platform settings
     */
    public function updateSettings(array $settings): bool
    {
        try {
            $platformSettings = PlatformSetting::first() ?? new PlatformSetting();
            
            if (isset($settings['update_channel'])) {
                $platformSettings->update_channel = $settings['update_channel'];
            }
            
            if (isset($settings['auto_update_enabled'])) {
                $platformSettings->auto_update_enabled = $settings['auto_update_enabled'];
            }
            
            if (isset($settings['min_supported_version'])) {
                $platformSettings->min_supported_version = $settings['min_supported_version'];
            }

            $platformSettings->save();

            // Clear cache to reflect changes
            Cache::forget(self::CACHE_KEY_CURRENT_VERSION);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to update settings: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get update settings
     */
    public function getSettings(): array
    {
        $settings = PlatformSetting::first();
        
        return [
            'current_version' => $this->getCurrentVersion(),
            'min_supported_version' => $settings?->min_supported_version ?? '1.0.0',
            'update_channel' => $settings?->update_channel ?? 'stable',
            'auto_update_enabled' => $settings?->isAutoUpdateEnabled() ?? true,
            'maintenance_mode' => $this->isMaintenanceMode(),
        ];
    }

    /**
     * Create a new version record (for local version management)
     */
    public function createVersion(array $data): PlatformVersion
    {
        $version = new PlatformVersion();
        $version->version = $data['version'];
        $version->release_type = $data['release_type'] ?? PlatformVersion::RELEASE_TYPE_PATCH;
        $version->status = $data['status'] ?? PlatformVersion::STATUS_DRAFT;
        $version->release_notes = $data['release_notes'] ?? '';
        $version->min_database_version = $data['min_database_version'] ?? '1.0.0';
        $version->rollback_version = $data['rollback_version'] ?? null;
        $version->is_auto_deploy = $data['is_auto_deploy'] ?? false;
        $version->update_channel = $data['update_channel'] ?? PlatformVersion::CHANNEL_STABLE;
        $version->download_url = $data['download_url'] ?? null;
        $version->checksum = $data['checksum'] ?? null;
        $version->file_size = $data['file_size'] ?? 0;
        $version->created_by = $data['created_by'] ?? 'system';
        
        $version->save();

        // Clear cache
        Cache::forget(self::CACHE_KEY_AVAILABLE_UPDATES);

        return $version;
    }

    /**
     * Get deployment history
     */
    public function getDeploymentHistory(int $limit = 10): array
    {
        return PlatformVersion::where('deployed_at', '!=', null)
            ->orderBy('deployed_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
