<?php

use App\Enums\UserRoles;
use App\Livewire\Components\ProductFormAdd;
use App\Livewire\Components\ProductFormEdit;
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

function createProductDependenciesForStockUpdate(): array
{
    return [
        'category' => Category::create(['category_name' => 'Chicken Eggs']),
        'subcategory' => Subcategory::create(['subcategory_name' => 'Brown Eggs']),
        'unit' => Unit::create(['unit_name' => 'Tray']),
    ];
}

function actingAsAdminForStockUpdate(): User
{
    $user = User::factory()->create(['user_role' => UserRoles::ADMIN]);

    actingAs($user);

    return $user;
}

it('deletes the previous stock addition from today and replaces it when stock is updated on the same day', function () {
    actingAsAdminForStockUpdate();
    $dependencies = createProductDependenciesForStockUpdate();

    Livewire::test(ProductFormAdd::class)
        ->set('productForm.name', 'Layer Egg')
        ->set('stockLevel', 25)
        ->set('price', 180)
        ->set('productForm.unit_id', (string) $dependencies['unit']->id)
        ->set('productForm.category_id', (string) $dependencies['category']->id)
        ->set('productForm.subcategory_id', (string) $dependencies['subcategory']->id)
        ->call('save');

    $product = Product::firstOrFail();
    expect(StockAddition::where('product_id', $product->id)->count())->toBe(1);

    Livewire::test(ProductFormEdit::class, ['productToEdit' => $product->id])
        ->set('stockLevel', 35)
        ->set('price', 200)
        ->call('update');

    $additions = StockAddition::where('product_id', $product->id)->get();

    expect($additions)->toHaveCount(1)
        ->and((float) $additions->first()->quantity_added)->toBe(35.0);
});

it('does not delete stock additions from previous days when updating stock', function () {
    actingAsAdminForStockUpdate();
    $dependencies = createProductDependenciesForStockUpdate();

    Livewire::test(ProductFormAdd::class)
        ->set('productForm.name', 'Layer Egg')
        ->set('stockLevel', 25)
        ->set('price', 180)
        ->set('productForm.unit_id', (string) $dependencies['unit']->id)
        ->set('productForm.category_id', (string) $dependencies['category']->id)
        ->set('productForm.subcategory_id', (string) $dependencies['subcategory']->id)
        ->call('save');

    $product = Product::firstOrFail();

    $oldAddition = StockAddition::where('product_id', $product->id)->firstOrFail();
    $oldAddition->update(['created_at' => now()->subDay()]);

    Livewire::test(ProductFormEdit::class, ['productToEdit' => $product->id])
        ->set('stockLevel', 35)
        ->set('price', 200)
        ->call('update');

    expect(StockAddition::where('product_id', $product->id)->count())->toBe(2);
});
