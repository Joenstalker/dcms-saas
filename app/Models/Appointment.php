<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $connection = 'mongodb';

    protected $fillable = [
        'patient_id',
        'dentist_id',
        'service_id',
        'scheduled_at',
        'status',
        'notes',
        'is_guest_booking',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_guest_booking' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist()
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
