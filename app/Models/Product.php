<?php

namespace App\Models;

use App\Enums\EggSizes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'stock_level', 'price', 'unit', 'category', 'subcategory', 'size'];

    protected $casts = [
      'sizes' => EggSizes::class
    ];
}
