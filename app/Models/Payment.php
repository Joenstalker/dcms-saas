<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $connection = 'mongodb';

    protected $fillable = [
        'tenant_id',
        'invoice_id',
        'patient_id',
        'amount_paid',
        'payment_method', // Cash, GCash, Card, Check, Bank Transfer
        'transaction_date',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    protected static function booted()
    {
        static::created(function ($payment) {
            if ($payment->invoice) {
                $payment->invoice->updateStatus();
            }
        });

        static::deleted(function ($payment) {
            if ($payment->invoice) {
                $payment->invoice->updateStatus();
            }
        });
    }
}
