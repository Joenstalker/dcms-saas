<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class PrescriptionTemplate extends Model
{
    protected $fillable = [
        'tenant_id',
        'label',
        'medicines',
        'instructions',
        'is_active',
    ];

    protected $casts = [
        'medicines' => 'array',
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
