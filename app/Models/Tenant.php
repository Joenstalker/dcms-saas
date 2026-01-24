<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'pricing_plan_id',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'logo',
        'settings',
        'is_active',
        'trial_ends_at',
        'subscription_ends_at',
        'subscription_status',
        'last_payment_date',
        'suspended_at',
        'email_verification_token',
        'email_verified_at',
        'setup_completed',
        'primary_color',
        'secondary_color',
        'invoice_header',
        'receipt_header',
        'business_hours',
        'consent_forms',
        'certificate_templates',
        'default_hmo_providers',
        'default_dental_services',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'setup_completed' => 'boolean',
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'last_payment_date' => 'datetime',
        'suspended_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'business_hours' => 'array',
        'consent_forms' => 'array',
        'certificate_templates' => 'array',
        'default_hmo_providers' => 'array',
        'default_dental_services' => 'array',
    ];

    public function pricingPlan(): BelongsTo
    {
        return $this->belongsTo(PricingPlan::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function isActive(): bool
    {
        // Tenant is active if email is verified and account is not soft deleted
        return $this->isEmailVerified() && $this->is_active;
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at !== null && $this->trial_ends_at->isFuture();
    }

    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function hasActiveSubscription(): bool
    {
        // Check if subscription is active or on trial
        if ($this->subscription_status === 'active') {
            return true;
        }

        if ($this->subscription_status === 'trial' && $this->isOnTrial()) {
            return true;
        }

        // Check if subscription end date is in the future
        if ($this->subscription_ends_at && $this->subscription_ends_at->isFuture()) {
            return true;
        }

        return false;
    }

    public function isSubscriptionExpired(): bool
    {
        return in_array($this->subscription_status, ['expired', 'suspended', 'cancelled']);
    }

    public function isSuspended(): bool
    {
        return $this->subscription_status === 'suspended' ||
               ($this->subscription_ends_at && $this->subscription_ends_at->isPast());
    }

    public function getSubscriptionStatusBadge(): string
    {
        return match ($this->subscription_status) {
            'active' => '<span class="badge badge-success">Active</span>',
            'trial' => '<span class="badge badge-info">Trial</span>',
            'expired' => '<span class="badge badge-error">Expired</span>',
            'suspended' => '<span class="badge badge-warning">Suspended</span>',
            'cancelled' => '<span class="badge badge-ghost">Cancelled</span>',
            default => '<span class="badge badge-ghost">Unknown</span>',
        };
    }

    public function getDaysUntilExpiration(): ?int
    {
        if ($this->isOnTrial() && $this->trial_ends_at) {
            return now()->diffInDays($this->trial_ends_at, false);
        }

        if ($this->subscription_ends_at) {
            return now()->diffInDays($this->subscription_ends_at, false);
        }

        return null;
    }
}
