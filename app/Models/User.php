<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id',
        'is_system_admin',
        'must_reset_password',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_system_admin' => 'boolean',
            'must_reset_password' => 'boolean',
        ];
    }

    protected $appends = [
        'profile_photo_url',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function isSystemAdmin(): bool
    {
        return $this->is_system_admin ?? false;
    }

    public function isTenantAdmin(): bool
    {
        return $this->tenant_id !== null && !$this->is_system_admin;
    }

    /**
     * Check if user has a role within their tenant
     */
    public function hasTenantRole(string $role): bool
    {
        if (!$this->tenant_id) {
            return false;
        }

        return $this->roles()
            ->where('tenant_id', $this->tenant_id)
            ->where('name', $role)
            ->exists();
    }

    /**
     * Get roles scoped to the user's tenant
     */
    public function getTenantRoles()
    {
        if (!$this->tenant_id) {
            return collect([]);
        }

        return $this->roles()
            ->where('tenant_id', $this->tenant_id)
            ->get();
    }

    /**
     * Check if user is owner of their tenant
     */
    public function isOwner(): bool
    {
        return $this->hasTenantRole('owner');
    }

    /**
     * Check if user is dentist in their tenant
     */
    public function isDentist(): bool
    {
        return $this->hasTenantRole('dentist');
    }

    /**
     * Check if user is assistant in their tenant
     */
    public function isAssistant(): bool
    {
        return $this->hasTenantRole('assistant');
    }
}
