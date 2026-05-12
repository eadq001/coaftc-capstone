<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        parent::booted();

        static::creating(function (Sale $sale) {
            $year = now()->format('y');
            $prefix = "PRF{$year}-00000";

            $latestPrf = Sale::where('prf_number', 'like', $prefix.'%')->latest('id')->value('prf_number');

            $nextNumber = 1;

            if ($latestPrf) {
                $nextNumber = ((int) substr($latestPrf, strlen($prefix))) + 1;
            }

            $sale->prf_number = $prefix.$nextNumber;
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salesItem(): HasMany
    {
        return $this->hasMany(SalesItem::class);
    }
}
