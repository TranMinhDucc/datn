<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRequestItemAction extends Model
{
    protected $fillable = [
        'return_request_item_id',
        'action',
        'quantity',
        'exchange_variant_id',
        'refund_amount',
        'note',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'quantity'      => 'integer',
        'refund_amount' => 'decimal:2',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(ReturnRequestItem::class, 'return_request_item_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'exchange_variant_id');
    }
}
