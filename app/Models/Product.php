<?php

namespace App\Models;

use App\Enums\EggSizes;
use App\Enums\ProductClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'sizes' => EggSizes::class,
        'class' => ProductClass::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function salesItem(): HasMany
    {
        return $this->hasMany(SalesItem::class);
    }

    public function dispersalItems(): HasMany
    {
        return $this->hasMany(DispersalItem::class);
    }
}
