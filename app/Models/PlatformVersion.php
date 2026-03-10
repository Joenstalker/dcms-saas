<?php

declare(strict_types=1);

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlatformVersion extends Model
{
    protected $connection = 'mongodb_central';
    use HasFactory;

    const RELEASE_TYPE_MAJOR = 'major';
    const RELEASE_TYPE_MINOR = 'minor';
    const RELEASE_TYPE_PATCH = 'patch';
    const RELEASE_TYPE_HOTFIX = 'hotfix';

    const STATUS_DRAFT = 'draft';
    const STATUS_TESTING = 'testing';
    const STATUS_STABLE = 'stable';
    const STATUS_DEPRECATED = 'deprecated';

    const CHANNEL_STABLE = 'stable';
    const CHANNEL_BETA = 'beta';
    const CHANNEL_ALPHA = 'alpha';

    protected $fillable = [
        'version',
        'release_type',
        'status',
        'release_notes',
        'min_database_version',
        'rollback_version',
        'is_auto_deploy',
        'deployed_at',
        'created_by',
        'update_channel',
        'download_url',
        'checksum',
        'file_size',
    ];

    protected $casts = [
        'is_auto_deploy' => 'boolean',
        'deployed_at' => 'datetime',
        'file_size' => 'integer',
    ];

    /**
     * Get release type options
     */
    public static function releaseTypes(): array
    {
        return [
            self::RELEASE_TYPE_MAJOR => 'Major',
            self::RELEASE_TYPE_MINOR => 'Minor',
            self::RELEASE_TYPE_PATCH => 'Patch',
            self::RELEASE_TYPE_HOTFIX => 'Hotfix',
        ];
    }

    /**
     * Get status options
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_TESTING => 'Testing',
            self::STATUS_STABLE => 'Stable',
            self::STATUS_DEPRECATED => 'Deprecated',
        ];
    }

    /**
     * Get channel options
     */
    public static function channels(): array
    {
        return [
            self::CHANNEL_STABLE => 'Stable',
            self::CHANNEL_BETA => 'Beta',
            self::CHANNEL_ALPHA => 'Alpha',
        ];
    }

    /**
     * Check if this version is newer than the given version
     */
    public function isNewerThan(string $version): bool
    {
        return version_compare($this->version, $version, '>');
    }

    /**
     * Check if this version can be rolled back to
     */
    public function canRollback(): bool
    {
        return !empty($this->rollback_version) && $this->status === self::STATUS_STABLE;
    }

    /**
     * Scope for stable versions
     */
    public function scopeStable($query)
    {
        return $query->where('status', self::STATUS_STABLE);
    }

    /**
     * Scope for auto-deploy versions
     */
    public function scopeAutoDeploy($query)
    {
        return $query->where('is_auto_deploy', true);
    }

    /**
     * Scope for specific channel
     */
    public function scopeForChannel($query, string $channel)
    {
        return $query->where('update_channel', $channel);
    }

    /**
     * Get the latest stable version
     */
    public static function getLatestStable(): ?self
    {
        return static::stable()
            ->orderBy('version', 'desc')
            ->first();
    }

    /**
     * Get the current deployed version
     */
    public static function getCurrentDeployed(): ?self
    {
        return static::where('deployed_at', '!=', null)
            ->orderBy('deployed_at', 'desc')
            ->first();
    }
}
