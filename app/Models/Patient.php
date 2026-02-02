<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HybridCompatibility;

class Patient extends Model
{
    use HasFactory, BelongsToTenant, HybridCompatibility;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'dob',
        'gender',
        'address',
        'medical_history',
    ];

    protected $casts = [
        'dob' => 'date',
    ];
}
