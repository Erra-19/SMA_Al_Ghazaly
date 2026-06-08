<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentHistory extends Model
{
    protected $primaryKey = 'payment_history_id';

    protected $fillable = [
        'payment_id',
        'order_id',
        'transaction_id',
        'old_status',
        'new_status',
        'event_type',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'payment_id');
    }
}
