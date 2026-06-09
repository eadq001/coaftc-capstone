<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dispersal extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        parent::booted();

        static::creating(function (Dispersal $dispersal) {
            $year = now()->format('y');
            $prefix = "LGU{$year}-";

            $latestDispersal = Dispersal::where('dispersal_number', 'like', $prefix.'%')->latest('id')->value('dispersal_number');

            $nextNumber = 1;

            if ($latestDispersal) {
                $nextNumber = ((int) substr($latestDispersal, strlen($prefix))) + 1;
            }
            $dispersal->dispersal_number = $prefix.str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dispersalItems(): HasMany
    {
        return $this->hasMany(DispersalItem::class);
    }
}
