<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\RefreshesPermissionCache;
use Spatie\Permission\Guard;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

class Role extends Model implements RoleContract
{
    use HasPermissions;
    use RefreshesPermissionCache;

    public $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

        parent::__construct($attributes);

        $this->guarded[] = $this->primaryKey;
    }

    public function getTable()
    {
        return config('permission.table_names.roles', parent::getTable());
    }

    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        // Check if role exists first (Mocking 'findOrCreate' behavior if needed, or rely on Spatie logic)
        // Spatie's logic usually handles this, but since we are overriding, we just ensure basics.
        
        return static::query()->create($attributes);
    }
    
    /**
     * Find a role by its name and guard name.
     *
     * @param string $name
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Role
     *
     * @throws \Spatie\Permission\Exceptions\RoleDoesNotExist
     */
    public static function findByName(string $name, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::where('name', $name)->where('guard_name', $guardName)->first();

        if (! $role) {
            throw RoleDoesNotExist::named($name, $guardName);
        }

        return $role;
    }

    public static function findById(int|string $id, $guardName = null): RoleContract
    {
         $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::where('id', $id)->where('guard_name', $guardName)->first();

        if (! $role) {
            throw RoleDoesNotExist::withId($id, $guardName);
        }

        return $role;
    }

    /**
     * Find or create role by its name (and optionally guardName).
     *
     * @param string $name
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Role
     */
    public static function findOrCreate(string $name, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::where('name', $name)->where('guard_name', $guardName)->first();

        if (! $role) {
            return static::create(['name' => $name, 'guard_name' => $guardName]);
        }

        return $role;
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param string|Permission $permission
     *
     * @return bool
     *
     * @throws \Spatie\Permission\Exceptions\PermissionDoesNotExist
     */
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        // Simple implementation for Mongo (Spatie logic is complex)
        // We rely on HasPermissions trait but need to ensure relations work with Mongo.
        // For now, let's trust traits if relations are compatible.
        // MongoDB relations in Laravel-MongoDB mimic Eloquent relations.
        
        return $this->permissions()->where('name', $permission->name ?? $permission)->exists();
    }
}
