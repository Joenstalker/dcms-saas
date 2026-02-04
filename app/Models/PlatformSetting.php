<?php

declare(strict_types=1);

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlatformSetting extends Model
{
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
    ];

    protected $casts = [
        'available_theme_colors' => 'array',
        'available_fonts' => 'array',
        'default_dashboard_widgets' => 'array',
    ];
}
