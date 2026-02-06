<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use App\Models\TenantSetting;
use App\Models\CustomTheme;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TenantBrandingService
{
    private const CACHE_PREFIX = 'dcms:tenant_branding:';
    private const SETTINGS_TTL = 86400;
    private const CSS_TTL = 3600;
    private const THEMES_TTL = 3600;

    public function getSettings(Tenant $tenant): ?TenantSetting
    {
        $cacheKey = self::CACHE_PREFIX . 'settings:' . $tenant->id;

        return Cache::remember($cacheKey, self::SETTINGS_TTL, function () use ($tenant) {
            Log::debug('Cache miss: loading tenant settings from DB', ['tenant_id' => $tenant->id]);
            return TenantSetting::where('tenant_id', $tenant->id)->first();
        });
    }

    public function getBrandingCss(Tenant $tenant): string
    {
        $cacheKey = self::CACHE_PREFIX . 'css:' . $tenant->id;

        return Cache::remember($cacheKey, self::CSS_TTL, function () use ($tenant) {
            Log::debug('Cache miss: compiling branding CSS', ['tenant_id' => $tenant->id]);
            return $this->compileBrandingCss($tenant);
        });
    }

    public function getActiveThemes(): \Illuminate\Support\Collection
    {
        $cacheKey = self::CACHE_PREFIX . 'themes:active';

        return Cache::remember($cacheKey, self::THEMES_TTL, function () {
            Log::debug('Cache miss: loading active custom themes from DB');
            return CustomTheme::where('is_active', true)->get();
        });
    }

    public function getBrandingArray(Tenant $tenant): array
    {
        $settings = $this->getSettings($tenant);

        if (!$settings) {
            return $this->getDefaultBranding();
        }

        return [
            'theme_mode' => $settings->theme_mode ?? 'system',
            'sidebar_position' => $settings->sidebar_position ?? 'left',
            'sidebar_collapsed' => $settings->sidebar_collapsed ?? false,
            'font_heading' => $settings->font_family_heading ?? 'Figtree',
            'font_body' => $settings->font_family_body ?? 'Inter',
            'primary_color' => $settings->theme_color_primary ?? '#3b82f6',
            'secondary_color' => $settings->theme_color_secondary ?? '#6366f1',
            'accent_color' => $settings->theme_color_accent ?? '#0ea5e9',
            'neutral_color' => $settings->theme_color_neutral ?? '#6b7280',
            'info_color' => $settings->theme_color_info ?? '#3b82f6',
            'success_color' => $settings->theme_color_success ?? '#22c55e',
            'warning_color' => $settings->theme_color_warning ?? '#f59e0b',
            'error_color' => $settings->theme_color_error ?? '#ef4444',
            'logo_url' => $settings->getLogoUrl(),
            'favicon_url' => $settings->getFaviconUrl(),
            'custom_brand_name' => $settings->custom_brand_name,
        ];
    }

    public function clearSettingsCache(Tenant $tenant): void
    {
        $settingsKey = self::CACHE_PREFIX . 'settings:' . $tenant->id;
        $cssKey = self::CACHE_PREFIX . 'css:' . $tenant->id;

        Cache::forget($settingsKey);
        Cache::forget($cssKey);

        Log::info('Cleared tenant branding cache', ['tenant_id' => $tenant->id]);
    }

    public function clearThemesCache(): void
    {
        $themesKey = self::CACHE_PREFIX . 'themes:active';
        Cache::forget($themesKey);

        Log::info('Cleared active themes cache');
    }

    private function getDefaultBranding(): array
    {
        return [
            'theme_mode' => 'system',
            'sidebar_position' => 'left',
            'sidebar_collapsed' => false,
            'font_heading' => 'Figtree',
            'font_body' => 'Inter',
            'primary_color' => '#3b82f6',
            'secondary_color' => '#6366f1',
            'accent_color' => '#0ea5e9',
            'neutral_color' => '#6b7280',
            'info_color' => '#3b82f6',
            'success_color' => '#22c55e',
            'warning_color' => '#f59e0b',
            'error_color' => '#ef4444',
            'logo_url' => null,
            'favicon_url' => null,
            'custom_brand_name' => null,
        ];
    }

    private function compileBrandingCss(Tenant $tenant): string
    {
        $settings = $this->getSettings($tenant);

        $headingFont = $settings?->font_family_heading ?? 'Figtree';
        $bodyFont = $settings?->font_family_body ?? 'Inter';
        $primary = $settings?->theme_color_primary ?? '#3b82f6';
        $secondary = $settings?->theme_color_secondary ?? '#6366f1';
        $accent = $settings?->theme_color_accent ?? '#0ea5e9';
        $neutral = $settings?->theme_color_neutral ?? '#6b7280';
        $info = $settings?->theme_color_info ?? '#3b82f6';
        $success = $settings?->theme_color_success ?? '#22c55e';
        $warning = $settings?->theme_color_warning ?? '#f59e0b';
        $error = $settings?->theme_color_error ?? '#ef4444';
        $sidebarPos = $settings?->sidebar_position ?? 'left';

        $primaryHsl = $this->hexToHsl($primary);
        $secondaryHsl = $this->hexToHsl($secondary);
        $accentHsl = $this->hexToHsl($accent);
        $neutralHsl = $this->hexToHsl($neutral);
        $infoHsl = $this->hexToHsl($info);
        $successHsl = $this->hexToHsl($success);
        $warningHsl = $this->hexToHsl($warning);
        $errorHsl = $this->hexToHsl($error);

        $primaryContent = $this->getLuminance($primary) > 0.5 ? '0 0% 0%' : '0 0% 100%';
        $secondaryContent = $this->getLuminance($secondary) > 0.5 ? '0 0% 0%' : '0 0% 100%';
        $accentContent = $this->getLuminance($accent) > 0.5 ? '0 0% 0%' : '0 0% 100%';
        $infoContent = $this->getLuminance($info) > 0.5 ? '0 0% 0%' : '0 0% 100%';
        $successContent = $this->getLuminance($success) > 0.5 ? '0 0% 0%' : '0 0% 100%';
        $warningContent = $this->getLuminance($warning) > 0.5 ? '0 0% 0%' : '0 0% 100%';
        $errorContent = $this->getLuminance($error) > 0.5 ? '0 0% 0%' : '0 0% 100%';
        $neutralContent = $this->getLuminance($neutral) > 0.5 ? '0 0% 0%' : '0 0% 100%';
        $neutralContentFg = $this->getLuminance($neutral) > 0.5 ? '0 0% 10%' : '0 0% 90%';

        $css = sprintf(
            '<style id="tenant-branding-styles" data-sidebar-position="%s">
            :root {
                --tenant-font-heading: %s, sans-serif;
                --tenant-font-body: %s, sans-serif;
                --tenant-primary: %s %s%% %s%%;
                --tenant-secondary: %s %s%% %s%%;
                --tenant-accent: %s %s%% %s%%;
                --tenant-neutral: %s %s%% %s%%;
                --tenant-info: %s %s%% %s%%;
                --tenant-success: %s %s%% %s%%;
                --tenant-warning: %s %s%% %s%%;
                --tenant-error: %s %s%% %s%%;
                --tenant-primary-content: %s;
                --tenant-secondary-content: %s;
                --tenant-accent-content: %s;
                --tenant-info-content: %s;
                --tenant-success-content: %s;
                --tenant-warning-content: %s;
                --tenant-error-content: %s;
            }
            html { --font-heading: var(--tenant-font-heading); --font-body: var(--tenant-font-body); }
            body { font-family: var(--font-body), sans-serif; }
            h1, h2, h3, h4, h5, h6, .font-heading { font-family: var(--font-heading), sans-serif; }
            [data-theme="tenant"] {
                --p: %s %s%% %s%%; --pc: %s;
                --s: %s %s%% %s%%; --sc: %s;
                --a: %s %s%% %s%%; --ac: %s;
                --n: %s %s%% %s%%; --nc: %s; --nf: %s;
                --b1: 0 0%% 100%%; --b2: 0 0%% 96%%; --b3: 0 0%% 92%%;
                --in: 212 86%% 54%%; --inc: %s;
                --su: %s %s%% %s%%; --suc: %s;
                --wa: %s %s%% %s%%; --wac: %s;
                --er: %s %s%% %s%%; --erc: %s;
            }
            .tenant-theme-primary { --tw-primary: %s; }
            .tenant-theme-secondary { --tw-secondary: %s; }
            .tenant-theme-accent { --tw-accent: %s; }
            </style>',
            $sidebarPos,
            $headingFont, $bodyFont,
            $primaryHsl['h'], $primaryHsl['s'], $primaryHsl['l'],
            $secondaryHsl['h'], $secondaryHsl['s'], $secondaryHsl['l'],
            $accentHsl['h'], $accentHsl['s'], $accentHsl['l'],
            $neutralHsl['h'], $neutralHsl['s'], $neutralHsl['l'],
            $infoHsl['h'], $infoHsl['s'], $infoHsl['l'],
            $successHsl['h'], $successHsl['s'], $successHsl['l'],
            $warningHsl['h'], $warningHsl['s'], $warningHsl['l'],
            $errorHsl['h'], $errorHsl['s'], $errorHsl['l'],
            $primaryContent, $secondaryContent, $accentContent, $infoContent, $successContent, $warningContent, $errorContent,
            $primaryHsl['h'], $primaryHsl['s'], $primaryHsl['l'], $primaryContent,
            $secondaryHsl['h'], $secondaryHsl['s'], $secondaryHsl['l'], $secondaryContent,
            $accentHsl['h'], $accentHsl['s'], $accentHsl['l'], $accentContent,
            $neutralHsl['h'], $neutralHsl['s'], $neutralHsl['l'], $neutralContent, $neutralContentFg,
            $infoContent,
            $successHsl['h'], $successHsl['s'], $successHsl['l'], $successContent,
            $warningHsl['h'], $warningHsl['s'], $warningHsl['l'], $warningContent,
            $errorHsl['h'], $errorHsl['s'], $errorHsl['l'], $errorContent,
            $primary, $secondary, $accent
        );

        return $css;
    }

    private function hexToHsl(string $hex): array
    {
        $hex = str_replace('#', '', $hex);
        $len = strlen($hex);
        $r = $g = $b = 0;
        
        if ($len === 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1)) / 255;
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1)) / 255;
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1)) / 255;
        } else {
            $r = hexdec(substr($hex, 0, 2)) / 255;
            $g = hexdec(substr($hex, 2, 2)) / 255;
            $b = hexdec(substr($hex, 4, 2)) / 255;
        }
        
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2;
        
        if ($max === $min) {
            return ['h' => 0, 's' => 0, 'l' => round($l * 100, 1)];
        }
        
        $d = $max - $min;
        $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
        
        switch ($max) {
            case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
            case $g: $h = ($b - $r) / $d + 2; break;
            default: $h = ($r - $g) / $d + 4; break;
        }
        $h /= 6;
        
        return [
            'h' => round($h * 360),
            's' => round($s * 100, 1),
            'l' => round($l * 100, 1)
        ];
    }

    private function getLuminance(string $hex): float
    {
        $hex = str_replace('#', '', $hex);
        $len = strlen($hex);
        
        if ($len === 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1)) / 255;
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1)) / 255;
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1)) / 255;
        } else {
            $r = hexdec(substr($hex, 0, 2)) / 255;
            $g = hexdec(substr($hex, 2, 2)) / 255;
            $b = hexdec(substr($hex, 4, 2)) / 255;
        }
        
        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }
}
