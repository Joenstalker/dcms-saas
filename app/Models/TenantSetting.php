<?php

declare(strict_types=1);

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'theme_mode',
        'theme_color_primary',
        'theme_color_secondary',
        'theme_color_accent',
        'theme_color_neutral',
        'theme_color_info',
        'theme_color_success',
        'theme_color_warning',
        'theme_color_error',
        'sidebar_position',
        'sidebar_collapsed',
        'font_family_heading',
        'font_family_body',
        'logo_path',
        'favicon_path',
        'dark_logo_path',
        'login_bg_path',
        'custom_brand_name',
        'dashboard_widgets',
    ];

    protected $casts = [
        'theme_mode' => 'string',
        'sidebar_collapsed' => 'boolean',
        'dashboard_widgets' => 'array',
        'theme_color_primary' => 'string',
        'theme_color_secondary' => 'string',
        'theme_color_accent' => 'string',
        'theme_color_neutral' => 'string',
        'theme_color_info' => 'string',
        'theme_color_success' => 'string',
        'theme_color_warning' => 'string',
        'theme_color_error' => 'string',
    ];

    public const THEME_MODE_LIGHT = 'light';
    public const THEME_MODE_DARK = 'dark';
    public const THEME_MODE_SYSTEM = 'system';

    public const SIDEBAR_LEFT = 'left';
    public const SIDEBAR_RIGHT = 'right';

    public const FONTS = [
        'Inter' => 'Inter',
        'Figtree' => 'Figtree',
        'Roboto' => 'Roboto',
        'Poppins' => 'Poppins',
        'Open Sans' => 'Open Sans',
        'Lato' => 'Lato',
        'Montserrat' => 'Montserrat',
        'Source Sans Pro' => 'Source Sans Pro',
        'Nunito' => 'Nunito',
        'Raleway' => 'Raleway',
    ];

    public const DEFAULT_PRESETS = [
        'blue' => [
            'name' => 'Classic Blue',
            'primary' => '#3b82f6',
            'secondary' => '#6366f1',
            'accent' => '#0ea5e9',
        ],
        'green' => [
            'name' => 'Fresh Green',
            'primary' => '#22c55e',
            'secondary' => '#10b981',
            'accent' => '#14b8a6',
        ],
        'purple' => [
            'name' => 'Royal Purple',
            'primary' => '#8b5cf6',
            'secondary' => '#a855f7',
            'accent' => '#c084fc',
        ],
        'coral' => [
            'name' => 'Warm Coral',
            'primary' => '#f97316',
            'secondary' => '#fb923c',
            'accent' => '#fdba74',
        ],
        'teal' => [
            'name' => 'Clean Teal',
            'primary' => '#14b8a6',
            'secondary' => '#2dd4bf',
            'accent' => '#5eead4',
        ],
        'rose' => [
            'name' => 'Soft Rose',
            'primary' => '#f43f5e',
            'secondary' => '#fb7185',
            'accent' => '#fda4af',
        ],
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getEffectiveThemeMode(): string
    {
        return $this->theme_mode ?? self::THEME_MODE_SYSTEM;
    }

    public function getEffectiveSidebarPosition(): string
    {
        return $this->sidebar_position ?? self::SIDEBAR_LEFT;
    }

    public function getEffectiveFontHeading(): string
    {
        return $this->font_family_heading ?? 'Figtree';
    }

    public function getEffectiveFontBody(): string
    {
        return $this->font_family_body ?? 'Inter';
    }

    public function getLogoUrl(): ?string
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }
        return null;
    }

    public function getFaviconUrl(): ?string
    {
        if ($this->favicon_path) {
            return asset('storage/' . $this->favicon_path);
        }
        return null;
    }

    public function getPrimaryColor(): string
    {
        return $this->theme_color_primary ?? '#3b82f6';
    }

    public function getSecondaryColor(): string
    {
        return $this->theme_color_secondary ?? '#6366f1';
    }

    public function applyPreset(string $presetKey): void
    {
        if (isset(self::DEFAULT_PRESETS[$presetKey])) {
            $preset = self::DEFAULT_PRESETS[$presetKey];
            $this->theme_color_primary = $preset['primary'];
            $this->theme_color_secondary = $preset['secondary'];
            $this->theme_color_accent = $preset['accent'];
        }
    }

    public function toBrandingArray(): array
    {
        return [
            'theme_mode' => $this->getEffectiveThemeMode(),
            'sidebar_position' => $this->getEffectiveSidebarPosition(),
            'sidebar_collapsed' => $this->sidebar_collapsed ?? false,
            'font_heading' => $this->getEffectiveFontHeading(),
            'font_body' => $this->getEffectiveFontBody(),
            'primary_color' => $this->getPrimaryColor(),
            'secondary_color' => $this->getSecondaryColor(),
            'accent_color' => $this->theme_color_accent ?? $this->getPrimaryColor(),
            'neutral_color' => $this->theme_color_neutral ?? '#6b7280',
            'info_color' => $this->theme_color_info ?? '#3b82f6',
            'success_color' => $this->theme_color_success ?? '#22c55e',
            'warning_color' => $this->theme_color_warning ?? '#f59e0b',
            'error_color' => $this->theme_color_error ?? '#ef4444',
            'logo_url' => $this->getLogoUrl(),
            'favicon_url' => $this->getFaviconUrl(),
            'custom_brand_name' => $this->custom_brand_name,
        ];
    }
}
