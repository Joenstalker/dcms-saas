<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ConsentTemplate extends Model
{
    protected $fillable = [
        'tenant_id',
        'label',
        'content',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
