<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncedTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'vendor',
        'external_order_id',
        'buyer_email',
        'event_title',
        'ticket_type',
        'quantity',
        'total_amount',
        'currency',
        'status',
        'purchased_at',
        'raw_payload',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'total_amount' => 'decimal:2',
        'purchased_at' => 'datetime',
        'raw_payload' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
