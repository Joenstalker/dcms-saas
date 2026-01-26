<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    /**
     * The "booted" method of the model.
     */
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            // Only apply scope if we are explicitly in a tenant context via Middleware
            // We avoid auth()->check() to prevent infinite recursion loop with User model
            if (app()->bound('tenant')) {
                 $builder->where('tenant_id', app('tenant')->id);
            }
        });

        static::creating(function (Model $model) {
            if (! $model->getAttribute('tenant_id')) {
                if (app()->bound('tenant')) {
                    $model->setAttribute('tenant_id', app('tenant')->id);
                }
            }
        });
    }

    /**
     * Get the tenant that owns the model.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
