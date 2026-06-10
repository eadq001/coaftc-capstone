<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoidedSale extends Model
{
    protected $guarded = [];

    protected $casts = [
        'original_items' => 'array',
        'modified_items' => 'array',
        'voided_at' => 'datetime',
    ];

    public function originalSale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'original_sale_id');
    }

    public function authorizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'authorized_by');
    }

    public function originalCashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'original_cashier_id');
    }
}
