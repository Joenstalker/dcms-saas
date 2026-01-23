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
}
