<?php

declare(strict_types=1);

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CustomTheme extends Model
{
    use HasFactory, BelongsToTenant;
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
