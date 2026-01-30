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
        'trial_duration',
        'trial_unit',
        'auto_delete_after_trial',
        'features',
        'max_users',
        'max_patients',
        'is_active',
        'is_popular',
        'badge_text',
        'badge_color',
        'storage_limit_mb',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'trial_days' => 'integer',
        'trial_duration' => 'integer',
        'auto_delete_after_trial' => 'boolean',
        'max_users' => 'integer',
        'max_patients' => 'integer',
        'storage_limit_mb' => 'integer',
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
        return $this->trial_duration > 0 || $this->trial_days > 0;
    }

    public function calculateTrialEndDate(): \Carbon\Carbon
    {
        if ($this->trial_duration) {
            return match ($this->trial_unit) {
                'minutes' => now()->addMinutes($this->trial_duration),
                'hours' => now()->addHours($this->trial_duration),
                'days' => now()->addDays($this->trial_duration),
                'weeks' => now()->addWeeks($this->trial_duration),
                'months' => now()->addMonths($this->trial_duration),
                default => now()->addDays($this->trial_duration),
            };
        }
        
        // Fallback to old trial_days
        return now()->addDays($this->trial_days ?? 0);
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

    public function getFormattedStorage(): string
    {
        if (!$this->storage_limit_mb) {
            return 'Unlimited Storage';
        }

        if ($this->storage_limit_mb >= 1024) {
            $gb = $this->storage_limit_mb / 1024;
            // Round to 1 decimal place unless it's a whole number
            $formatted = $gb == (int)$gb ? (string)$gb : number_format($gb, 1);
            return $formatted . ' GB Storage';
        }

        return $this->storage_limit_mb . ' MB Storage';
    }
}
