<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'generic_name',
        'description',
        'dosage',
        'unit',
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

    public static function units()
    {
        return [
            'mg' => 'mg',
            'g' => 'g',
            'ml' => 'ml',
            'mcg' => 'mcg',
            'IU' => 'IU',
            'tablet' => 'tablet(s)',
            'capsule' => 'capsule(s)',
            'ml' => 'ml',
            'drop' => 'drop(s)',
            'application' => 'application(s)',
        ];
    }
}
