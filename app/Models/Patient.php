<?php

declare(strict_types=1);

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'dob',
        'gender',
        'address',
        'medical_history',
        'balance',
    ];

    protected $casts = [
        'dob' => 'date',
        'balance' => 'decimal:2',
    ];
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
