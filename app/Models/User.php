<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\BelongsToTenant;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, BelongsToTenant, SoftDeletes;
 
    public $incrementing = false;
    protected $keyType = 'string';

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
        'role',
        'status',
        'must_reset_password',
        'profile_photo_path',
    ];

    public const ROLE_SYSTEM_ADMIN = 'system_admin';
    public const ROLE_TENANT = 'tenant';
    public const ROLE_DENTIST = 'dentist';
    public const ROLE_ASSISTANT = 'assistant';

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

        if ($this->isDentist() || $this->isAssistant()) {
            return $this->initialAvatarDataUrl();
        }
        
        return asset('images/dcms-logo.png');
    }

    protected function initialAvatarDataUrl(): string
    {
        $name = trim((string) $this->name);
        $initial = $name !== '' ? Str::upper(Str::substr($name, 0, 1)) : '?';
        $colors = ['#0ea5e9', '#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#14b8a6', '#a855f7', '#ec4899'];
        $index = abs(crc32($name)) % count($colors);
        $background = $colors[$index];
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128"><rect width="100%" height="100%" fill="' . $background . '"/><text x="50%" y="54%" text-anchor="middle" dominant-baseline="middle" font-family="Inter, Arial, sans-serif" font-size="64" fill="#ffffff">' . $initial . '</text></svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public function isSystemAdmin(): bool
    {
        return $this->role === self::ROLE_SYSTEM_ADMIN || ($this->is_system_admin ?? false);
    }

    public function isTenantAdmin(): bool
    {
        return $this->tenant_id !== null && !$this->isSystemAdmin();
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
        return $this->role === self::ROLE_TENANT || $this->hasTenantRole('owner');
    }

    /**
     * Check if user is dentist in their tenant
     */
    public function isDentist(): bool
    {
        return $this->role === self::ROLE_DENTIST || $this->hasTenantRole('dentist');
    }

    /**
     * Check if user is assistant in their tenant
     */
    public function isAssistant(): bool
    {
        return $this->role === self::ROLE_ASSISTANT || $this->hasTenantRole('assistant');
    }
}
