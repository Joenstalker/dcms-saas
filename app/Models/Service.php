<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'category',
        'amount',
        'duration_minutes',
        'color',
        'is_active',
        'auto_add',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'duration_minutes' => 'integer',
        'is_active' => 'boolean',
        'auto_add' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public static function categories()
    {
        return [
            'Preventive' => 'Preventive',
            'Restorative' => 'Restorative',
            'Endodontics' => 'Endodontics',
            'Oral Surgery' => 'Oral Surgery',
            'Prosthodontics' => 'Prosthodontics',
            'Orthodontics' => 'Orthodontics',
            'Cosmetic' => 'Cosmetic',
            'Diagnostic' => 'Diagnostic',
            'Emergency' => 'Emergency',
        ];
    }

    public static function colors()
    {
        return [
            '#3b82f6' => 'Blue',
            '#22c55e' => 'Green',
            '#f59e0b' => 'Amber',
            '#ef4444' => 'Red',
            '#8b5cf6' => 'Violet',
            '#ec4899' => 'Pink',
            '#06b6d4' => 'Cyan',
            '#84cc16' => 'Lime',
        ];
    }
}
