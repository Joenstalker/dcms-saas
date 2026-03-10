<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PlatformUpdateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PlatformUpdateController extends Controller
{
    private PlatformUpdateService $updateService;

    public function __construct(PlatformUpdateService $updateService)
    {
        $this->updateService = $updateService;
        
        // Ensure user has platform update permission
        $this->middleware(function ($request, $next) {
            if (!$request->user()->hasPermissionTo('platform.update')) {
                abort(403, 'You do not have permission to manage platform updates.');
            }
            return $next($request);
        });
    }

    /**
     * Display the platform update dashboard
     */
    public function index(): View
    {
        $settings = $this->updateService->getSettings();
        $availableVersions = $this->updateService->getAvailableVersions();
        $deploymentHistory = $this->updateService->getDeploymentHistory();
        $updateAvailable = $this->updateService->checkForUpdates();

        return view('admin.platform-updates.index', compact(
            'settings',
            'availableVersions',
            'deploymentHistory',
            'updateAvailable'
        ));
    }

    /**
     * Get current platform version (API)
     */
    public function getVersion(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'version' => $this->updateService->getCurrentVersion(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get version: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get version information',
            ], 500);
        }
    }

    /**
     * Check for available updates (API)
     */
    public function checkForUpdates(Request $request): JsonResponse
    {
        try {
            $updates = $this->updateService->checkForUpdates();
            
            if ($updates) {
                return response()->json([
                    'success' => true,
                    'update_available' => true,
                    'update' => $updates,
                ]);
            }

            return response()->json([
                'success' => true,
                'update_available' => false,
                'message' => 'You are running the latest version.',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to check for updates: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to check for updates: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get available versions (API)
     */
    public function getAvailableVersions(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'versions' => $this->updateService->getAvailableVersions(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get versions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get available versions',
            ], 500);
        }
    }

    /**
     * Deploy a specific version
     */
    public function deploy(Request $request): JsonResponse
    {
        $request->validate([
            'version' => 'required|string',
        ]);

        $version = $request->input('version');

        // Check if maintenance mode is already active
        if ($this->updateService->isMaintenanceMode()) {
            return response()->json([
                'success' => false,
                'message' => 'Maintenance mode is already active. Please wait or check the system.',
            ], 400);
        }

        try {
            $result = $this->updateService->deployVersion($version);

            if ($result['success']) {
                // Log the successful deployment
                Log::info("Platform update deployed: {$version} by user " . $request->user()->id);
                
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'version' => $result['version'],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        } catch (\Exception $e) {
            Log::error("Failed to deploy version {$version}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Deployment failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Rollback to previous version
     */
    public function rollback(Request $request): JsonResponse
    {
        // Check if maintenance mode is active
        if (!$this->updateService->isMaintenanceMode()) {
            return response()->json([
                'success' => false,
                'message' => 'Maintenance mode must be active to perform rollback.',
            ], 400);
        }

        try {
            $result = $this->updateService->rollback();

            if ($result['success']) {
                Log::warning("Platform rolled back to {$result['version']} by user " . $request->user()->id);
                
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'version' => $result['version'],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        } catch (\Exception $e) {
            Log::error('Rollback failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Rollback failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Enable maintenance mode
     */
    public function enableMaintenanceMode(Request $request): JsonResponse
    {
        try {
            $this->updateService->enableMaintenanceMode();
            
            Log::info('Maintenance mode enabled by user ' . $request->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Maintenance mode enabled',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to enable maintenance mode: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to enable maintenance mode',
            ], 500);
        }
    }

    /**
     * Disable maintenance mode
     */
    public function disableMaintenanceMode(Request $request): JsonResponse
    {
        try {
            $this->updateService->disableMaintenanceMode();
            
            Log::info('Maintenance mode disabled by user ' . $request->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Maintenance mode disabled',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to disable maintenance mode: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to disable maintenance mode',
            ], 500);
        }
    }

    /**
     * Get update settings
     */
    public function getSettings(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'settings' => $this->updateService->getSettings(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get settings: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get settings',
            ], 500);
        }
    }

    /**
     * Update settings
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $request->validate([
            'update_channel' => 'nullable|in:stable,beta,alpha',
            'auto_update_enabled' => 'nullable|boolean',
            'min_supported_version' => 'nullable|string',
        ]);

        try {
            $settings = $request->only([
                'update_channel',
                'auto_update_enabled',
                'min_supported_version',
            ]);

            $this->updateService->updateSettings($settings);

            Log::info('Update settings modified by user ' . $request->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully',
                'settings' => $this->updateService->getSettings(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update settings: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings',
            ], 500);
        }
    }

    /**
     * Get deployment history
     */
    public function getHistory(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'history' => $this->updateService->getDeploymentHistory(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get history: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get deployment history',
            ], 500);
        }
    }

    /**
     * Create a new version (for local version management)
     */
    public function createVersion(Request $request): JsonResponse
    {
        $request->validate([
            'version' => 'required|string',
            'release_type' => 'required|in:major,minor,patch,hotfix',
            'status' => 'nullable|in:draft,testing,stable,deprecated',
            'release_notes' => 'nullable|string',
            'min_database_version' => 'nullable|string',
            'rollback_version' => 'nullable|string',
            'is_auto_deploy' => 'nullable|boolean',
            'update_channel' => 'nullable|in:stable,beta,alpha',
        ]);

        try {
            $version = $this->updateService->createVersion([
                ...$request->all(),
                'created_by' => $request->user()->id,
            ]);

            Log::info("Version {$version->version} created by user " . $request->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Version created successfully',
                'version' => $version,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create version: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create version: ' . $e->getMessage(),
            ], 500);
        }
    }
}
