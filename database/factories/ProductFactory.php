<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'stock_level' => fake()->numberBetween(10, 50),
            'price' => fake()->randomNumber(3, true),
            'size' => fake()->optional()->word(),
            'class' => fake()->optional()->randomElement(['A', 'B']),
            'user_id' => User::factory(),
            'unit_id' => Unit::factory(),
            'category_id' => Category::factory(),
            'subcategory_id' => Subcategory::factory(),
        ];
    }
}
