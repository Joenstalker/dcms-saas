<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CustomTheme extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'colors',
        'is_active',
    ];

    protected $casts = [
        'colors' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($theme) {
            if (!$theme->slug) {
                $theme->slug = Str::slug($theme->name);
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
