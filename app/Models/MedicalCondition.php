<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class MedicalCondition extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'icd_code',
        'remarks',
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
