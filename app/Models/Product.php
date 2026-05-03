<?php

namespace App\Models;

use App\Enums\EggSizes;
use App\Enums\ProductClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = ['name', 'stock_level', 'price', 'unit_id', 'category_id', 'subcategory_id', 'size', 'class'];

    protected $casts = [
      'sizes' => EggSizes::class,
      'class' => ProductClass::class
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
}
