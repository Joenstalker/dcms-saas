<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CertificateTemplate extends Model
{
    protected $fillable = [
        'tenant_id',
        'label',
        'template_html',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
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

    public static function defaultVariables()
    {
        return [
            '{patient_name}' => 'Patient Full Name',
            '{patient_address}' => 'Patient Address',
            '{date}' => 'Current Date',
            '{doctor_name}' => 'Doctor Name',
            '{clinic_name}' => 'Clinic Name',
            '{procedure}' => 'Procedure Performed',
            '{findings}' => 'Clinical Findings',
            '{remarks}' => 'Additional Remarks',
        ];
    }
}
