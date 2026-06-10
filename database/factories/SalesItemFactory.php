<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalesItem>
 */
class SalesItemFactory extends Factory
{
    protected $model = SalesItem::class;

    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 10);
        $unitPrice = fake()->randomNumber(3, true);

        return [
            'sale_id' => Sale::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'subtotal' => $quantity * $unitPrice,
            'inventory_start' => fake()->numberBetween(20, 50),
            'inventory_end' => fake()->numberBetween(10, 19),
        ];
    }
}
