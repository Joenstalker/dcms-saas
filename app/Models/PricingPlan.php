<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricingPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_cycle',
        'trial_days',
        'features',
        'max_users',
        'max_patients',
        'is_active',
        'is_popular',
        'badge_text',
        'badge_color',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'trial_days' => 'integer',
        'max_users' => 'integer',
        'max_patients' => 'integer',
        'sort_order' => 'integer',
    ];

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public function hasFeature(string $feature): bool
    {
        $features = $this->features ?? [];

        return in_array($feature, $features);
    }

    public function hasTrial(): bool
    {
        return $this->trial_days > 0;
    }

    public function getFormattedPrice(): string
    {
        $price = (float) $this->price;

        if ($price == 0) {
            return 'Free';
        }

        return '₱'.number_format($price, 2);
    }

    public function getFormattedBillingCycle(): string
    {
        return match ($this->billing_cycle) {
            'monthly' => 'month',
            'quarterly' => 'quarter',
            'yearly' => 'year',
            default => $this->billing_cycle,
        };
    }
}
