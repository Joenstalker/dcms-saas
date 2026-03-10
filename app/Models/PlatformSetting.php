<?php

declare(strict_types=1);

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlatformSetting extends Model
{
    protected $connection = 'mongodb_central';
    use HasFactory;
    protected $fillable = [
        'default_theme_primary',
        'default_theme_secondary',
        'default_sidebar_position',
        'default_font_family',
        'default_logo_path',
        'default_favicon_path',
        'available_theme_colors',
        'available_fonts',
        'default_dashboard_widgets',
        // Update system fields
        'current_version',
        'min_supported_version',
        'update_channel',
        'auto_update_enabled',
        'maintenance_mode',
    ];

    protected $casts = [
        'available_theme_colors' => 'array',
        'available_fonts' => 'array',
        'default_dashboard_widgets' => 'array',
        'auto_update_enabled' => 'boolean',
        'maintenance_mode' => 'boolean',
    ];

    /**
     * Default values for new instances
     */
    protected $attributes = [
        'current_version' => '1.0.0',
        'min_supported_version' => '1.0.0',
        'update_channel' => 'stable',
        'auto_update_enabled' => true,
        'maintenance_mode' => false,
    ];

    /**
     * Get current platform version
     */
    public function getCurrentVersion(): string
    {
        return $this->current_version ?? '1.0.0';
    }

    /**
     * Check if auto updates are enabled
     */
    public function isAutoUpdateEnabled(): bool
    {
        return $this->auto_update_enabled ?? true;
    }

    /**
     * Check if maintenance mode is active
     */
    public function isMaintenanceMode(): bool
    {
        return $this->maintenance_mode ?? false;
    }

    /**
     * Enable maintenance mode
     */
    public function enableMaintenanceMode(): void
    {
        $this->maintenance_mode = true;
        $this->save();
    }

    /**
     * Disable maintenance mode
     */
    public function disableMaintenanceMode(): void
    {
        $this->maintenance_mode = false;
        $this->save();
    }

    /**
     * Update the current version
     */
    public function updateVersion(string $version): void
    {
        $this->current_version = $version;
        $this->save();
    }
}
