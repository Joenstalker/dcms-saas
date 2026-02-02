<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HybridCompatibility;

class TenantSetting extends Model
{
    use HybridCompatibility;
    protected $fillable = [
        'tenant_id',
        'theme_color_primary',
        'theme_color_secondary',
        'sidebar_position',
        'font_family',
        'logo_path',
        'favicon_path',
        'dashboard_widgets',
    ];

    protected $casts = [
        'dashboard_widgets' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
