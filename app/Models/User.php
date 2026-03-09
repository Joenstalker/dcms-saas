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

    /**
     * Dynamically resolve which database connection to use.
     *
     * - System admins and tenant owners → mongodb_central (platform data)
     * - Dentists and assistants         → mongodb (already switched to db_{slug} by TenantMiddleware)
     */
    public function getConnectionName(): string
    {
        // 1. If an explicit connection is already set on the instance, use it.
        if (!empty($this->connection)) {
            return $this->connection;
        }

        $role = $this->attributes['role'] ?? null;
        $isSystemAdmin = (bool)($this->attributes['is_system_admin'] ?? false);

        // 2. Platform-level users stay in central
        if ($isSystemAdmin || in_array($role, [self::ROLE_SYSTEM_ADMIN, self::ROLE_TENANT])) {
            return 'mongodb_central';
        }

        // 3. Staff members (dentist, assistant) stay in tenant DB
        if (in_array($role, [self::ROLE_DENTIST, self::ROLE_ASSISTANT])) {
            return 'mongodb';
        }

        // 4. Default: If a tenant is bound to the app container, 
        // we assume we want the tenant-specific connection (mongodb)
        // for queries or new instances that don't have a role yet.
        if (app()->bound('tenant')) {
            return 'mongodb';
        }

        return 'mongodb_central';
    }


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
        'profile_photo_data',
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

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_data) {
            return $this->profile_photo_data;
        }

        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }

        return $this->initialAvatarDataUrl();
    }

    public function setProfilePhotoDataAttribute(?string $value): void
    {
        $this->attributes['profile_photo_data'] = $value;
        if ($value) {
            $this->attributes['profile_photo_path'] = null;
        }
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
     * Check if user is assistant in their tenant
     */
    public function isAssistant(): bool
    {
        return $this->role === self::ROLE_ASSISTANT || $this->hasTenantRole('assistant');
    }

    /**
     * Check if user is dentist in their tenant
     */
    public function isDentist(): bool
    {
        return $this->role === self::ROLE_DENTIST || $this->hasTenantRole('dentist');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();

        // 1. Try querying the tenant db (mongodb connection)
        // If a tenant subdomain is present, ensure the connection is pointing to the right DB
        $host = request()->getHost();
        $subdomain = explode('.', $host)[0];
        $baseDomain = env('LOCAL_BASE_DOMAIN', 'dcmsapp.local');
        
        // Only try tenant DB if we're on a subdomain and it's not a reserved one
        if ($subdomain !== 'admin' && $host !== $baseDomain && $host !== 'localhost' && $host !== '127.0.0.1') {
            $dbName = 'db_' . $subdomain;
            
            // Re-sync connection if needed (failsafe for early binding)
            if (config('database.connections.mongodb.database') !== $dbName) {
                config(['database.connections.mongodb.database' => $dbName]);
                \Illuminate\Support\Facades\DB::purge('mongodb');
            }

            $user = $this->setConnection('mongodb')
                ->newQuery()
                ->withoutGlobalScope('tenant') // Temporarily bypass to find by ID
                ->where(function($query) use ($field, $value) {
                    $query->where($field, $value);
                    // Also try as ObjectId if it looks like one and we're searching by _id
                    if ($field === '_id' || $field === 'id') {
                        try {
                            $query->orWhere('_id', new \MongoDB\BSON\ObjectId($value));
                        } catch (\Exception $e) {
                            // Not a valid ObjectId, ignore
                        }
                    }
                })
                ->first();
                
            if ($user) {
                return $user;
            }
        }

        // 2. Fall back to central database
        return $this->setConnection('mongodb_central')
            ->newQuery()
            ->where($field, $value)
            ->first();
    }
}
