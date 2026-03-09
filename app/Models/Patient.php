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

    protected $connection = 'mongodb';

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

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Re-calculate patient balance based on invoices and payments.
     */
    public function updateBalance(): void
    {
        $totalInvoiced = (float) $this->invoices()->where('status', '!=', 'Cancelled')->sum('grand_total');
        $totalPaid = (float) $this->payments()->sum('amount_paid');
        
        $this->balance = $totalInvoiced - $totalPaid;
        $this->save();
    }
}
