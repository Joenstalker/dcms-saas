<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $connection = 'mongodb';

    protected $fillable = [
        'tenant_id',
        'patient_id',
        'appointment_id',
        'invoice_number',
        'total_amount',
        'discount_amount',
        'tax_amount',
        'grand_total',
        'status', // Unpaid, Partial, Paid, Cancelled
        'notes',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getAmountDue()
    {
        $totalPaid = $this->payments()->sum('amount_paid');
        return $this->grand_total - $totalPaid;
    }

    /**
     * Update invoice status based on payments.
     */
    public function updateStatus()
    {
        $totalPaid = (float) $this->payments()->sum('amount_paid');
        $grandTotal = (float) $this->grand_total;

        if ($totalPaid <= 0) {
            $this->status = 'Unpaid';
        } elseif ($totalPaid < $grandTotal) {
            $this->status = 'Partial';
        } else {
            $this->status = 'Paid';
            $this->paid_at = now();
        }

        $this->save();
        
        // Update patient balance
        $this->patient->updateBalance();
    }
}
