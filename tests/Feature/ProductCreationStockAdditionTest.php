<?php

use App\Enums\UserRoles;
use App\Livewire\Components\ProductFormAdd;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockAddition;
use App\Models\Subcategory;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

function createProductDependenciesForStockAddition(): array
{
    return [
        'category' => Category::create(['category_name' => 'Chicken Eggs']),
        'subcategory' => Subcategory::create(['subcategory_name' => 'Brown Eggs']),
        'unit' => Unit::create(['unit_name' => 'Tray']),
    ];
}

it('records the initial stock level as a stock addition when a product is created', function () {
    $user = User::factory()->create(['user_role' => UserRoles::ADMIN]);
    actingAs($user);
    $dependencies = createProductDependencies();

    Livewire::test(ProductFormAdd::class)
        ->set('productForm.name', 'Layer Egg')
        ->set('stockLevel', 25)
        ->set('price', 180)
        ->set('productForm.unit_id', (string) $dependencies['unit']->id)
        ->set('productForm.category_id', (string) $dependencies['category']->id)
        ->set('productForm.subcategory_id', (string) $dependencies['subcategory']->id)
        ->call('save');

    $product = Product::firstOrFail();

    $addition = StockAddition::where('product_id', $product->id)->firstOrFail();

    expect((float) $addition->quantity_added)->toBe(25.0)
        ->and(StockAddition::count())->toBe(1);
});

it('stores the stock addition linked to the created product id', function () {
    $user = User::factory()->create(['user_role' => UserRoles::ADMIN]);
    actingAs($user);
    $dependencies = createProductDependenciesForStockAddition();

    Livewire::test(ProductFormAdd::class)
        ->set('productForm.name', 'Native Egg')
        ->set('stockLevel', 40)
        ->set('price', 200)
        ->set('productForm.unit_id', (string) $dependencies['unit']->id)
        ->set('productForm.category_id', (string) $dependencies['category']->id)
        ->set('productForm.subcategory_id', (string) $dependencies['subcategory']->id)
        ->call('save');

    $product = Product::where('name', 'Native Egg')->firstOrFail();
    $addition = StockAddition::where('product_id', $product->id)->firstOrFail();

    expect($addition->product_id)->toBe($product->id)
        ->and((float) $addition->quantity_added)->toBe(40.0);
});
