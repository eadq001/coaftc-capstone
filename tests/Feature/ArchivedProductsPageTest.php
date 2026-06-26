<?php

use App\Enums\UserRoles;
use App\Livewire\Components\RemoveModal;
use App\Livewire\Components\RestoreModal;
use App\Livewire\Dashboard\Archives\ArchiveProducts;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

function actingAsAdminForArchives(): User
{
    $user = User::factory()->create(['user_role' => UserRoles::ADMIN]);

    actingAs($user);

    return $user;
}

function createProductDependenciesForArchive(): array
{
    return [
        'category' => Category::create(['category_name' => 'Eggs']),
        'subcategory' => Subcategory::create(['subcategory_name' => 'Brown']),
        'unit' => Unit::create(['unit_name' => 'Tray']),
    ];
}

it('allows admin to access archived products page', function () {
    actingAsAdminForArchives();

    $this->get(route('dashboard.archived-products'))->assertOk();
});

it('prevents cashier from accessing archived products page', function () {
    $cashier = User::factory()->create(['user_role' => UserRoles::CASHIER]);
    actingAs($cashier);

    $this->get(route('dashboard.archived-products'))->assertNotFound();
});

it('prevents inventory clerk from accessing archived products page', function () {
    $inventory = User::factory()->create(['user_role' => UserRoles::INVENTORY]);
    actingAs($inventory);

    $this->get(route('dashboard.archived-products'))->assertNotFound();
});

it('lists only soft-deleted products', function () {
    actingAsAdminForArchives();
    $deps = createProductDependenciesForArchive();

    $activeProduct = Product::create([
        'name' => 'Active Egg',
        'stock_level' => 50,
        'price' => 100,
        'unit_id' => $deps['unit']->id,
        'category_id' => $deps['category']->id,
        'subcategory_id' => $deps['subcategory']->id,
        'user_id' => auth()->id(),
    ]);

    $deletedProduct = Product::create([
        'name' => 'Deleted Egg',
        'stock_level' => 10,
        'price' => 50,
        'unit_id' => $deps['unit']->id,
        'category_id' => $deps['category']->id,
        'subcategory_id' => $deps['subcategory']->id,
        'user_id' => auth()->id(),
    ]);
    $deletedProduct->delete();

    $component = Livewire::test(ArchiveProducts::class);

    expect($component->get('products')->pluck('name'))
        ->toContain('Deleted Egg')
        ->not->toContain('Active Egg');
});

it('lists only soft-deleted categories', function () {
    actingAsAdminForArchives();

    Category::create(['category_name' => 'Active Category']);
    $deletedCategory = Category::create(['category_name' => 'Deleted Category']);
    $deletedCategory->delete();

    $component = Livewire::test(ArchiveProducts::class);

    expect($component->get('categories')->pluck('category_name'))
        ->toContain('Deleted Category')
        ->not->toContain('Active Category');
});

it('lists only soft-deleted subcategories', function () {
    actingAsAdminForArchives();

    Subcategory::create(['subcategory_name' => 'Active Sub']);
    $deletedSub = Subcategory::create(['subcategory_name' => 'Deleted Sub']);
    $deletedSub->delete();

    $component = Livewire::test(ArchiveProducts::class);

    expect($component->get('subcategories')->pluck('subcategory_name'))
        ->toContain('Deleted Sub')
        ->not->toContain('Active Sub');
});

it('lists only soft-deleted units', function () {
    actingAsAdminForArchives();

    Unit::create(['unit_name' => 'Active Unit']);
    $deletedUnit = Unit::create(['unit_name' => 'Deleted Unit']);
    $deletedUnit->delete();

    $component = Livewire::test(ArchiveProducts::class);

    expect($component->get('units')->pluck('unit_name'))
        ->toContain('Deleted Unit')
        ->not->toContain('Active Unit');
});

it('paginates archived products with 10 per page', function () {
    actingAsAdminForArchives();
    $deps = createProductDependenciesForArchive();

    for ($i = 0; $i < 15; $i++) {
        $product = Product::create([
            'name' => "Deleted Product {$i}",
            'stock_level' => 10,
            'price' => 50,
            'unit_id' => $deps['unit']->id,
            'category_id' => $deps['category']->id,
            'subcategory_id' => $deps['subcategory']->id,
            'user_id' => auth()->id(),
        ]);
        $product->delete();
    }

    $component = Livewire::test(ArchiveProducts::class);

    expect($component->get('products')->count())->toBe(10)
        ->and($component->get('products')->hasMorePages())->toBeTrue();
});

it('restores a soft-deleted product via RestoreModal', function () {
    actingAsAdminForArchives();
    $deps = createProductDependenciesForArchive();

    $product = Product::create([
        'name' => 'Restored Egg',
        'stock_level' => 10,
        'price' => 50,
        'unit_id' => $deps['unit']->id,
        'category_id' => $deps['category']->id,
        'subcategory_id' => $deps['subcategory']->id,
        'user_id' => auth()->id(),
    ]);
    $product->delete();

    expect(Product::onlyTrashed()->whereKey($product->id)->exists())->toBeTrue();

    Livewire::test(RestoreModal::class, [
        'id' => $product->id,
        'modelName' => 'Product',
        'itemName' => $product->name,
    ])->call('restoreDeletedItem');

    expect(Product::whereKey($product->id)->exists())->toBeTrue()
        ->and(Product::onlyTrashed()->whereKey($product->id)->exists())->toBeFalse();
});

it('soft-deletes a product via RemoveModal', function () {
    actingAsAdminForArchives();
    $deps = createProductDependenciesForArchive();

    $product = Product::create([
        'name' => 'To Delete',
        'stock_level' => 10,
        'price' => 50,
        'unit_id' => $deps['unit']->id,
        'category_id' => $deps['category']->id,
        'subcategory_id' => $deps['subcategory']->id,
        'user_id' => auth()->id(),
    ]);

    Livewire::test(RemoveModal::class, [
        'id' => $product->id,
        'name' => $product->name,
        'modelName' => 'Product',
    ])->call('softDeleteItem');

    expect(Product::onlyTrashed()->whereKey($product->id)->exists())->toBeTrue();
});
