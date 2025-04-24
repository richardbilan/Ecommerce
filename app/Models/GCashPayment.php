<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GCashPayment extends Model
{
    protected $table = 'g_cash_payments';
    
    protected $fillable = [
        'order_id',
        'payment_reference',
        'amount',
        'status',
        'checkout_url',
        'payment_details',
        'paid_at'
    ];

    protected $casts = [
        'payment_details' => 'array',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(UserOrder::class);
    }
} 