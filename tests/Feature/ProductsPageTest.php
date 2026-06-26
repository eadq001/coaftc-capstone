<?php

use App\Enums\UserRoles;
use App\Livewire\Dashboard\Products;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

function actingAsAdminForProducts(): User
{
    $user = User::factory()->create(['user_role' => UserRoles::ADMIN]);

    actingAs($user);

    return $user;
}

function actingAsInventoryForProducts(): User
{
    $user = User::factory()->create(['user_role' => UserRoles::INVENTORY]);

    actingAs($user);

    return $user;
}

it('allows admin to access products page', function () {
    actingAsAdminForProducts();

    $this->get(route('dashboard.products'))->assertOk();
});

it('allows inventory clerk to access products page', function () {
    actingAsInventoryForProducts();

    $this->get(route('dashboard.products'))->assertOk();
});

it('prevents cashier from accessing products page', function () {
    $cashier = User::factory()->create(['user_role' => UserRoles::CASHIER]);
    actingAs($cashier);

    $this->get(route('dashboard.products'))->assertNotFound();
});

it('displays products in the list', function () {
    actingAsAdminForProducts();

    Product::factory()->create(['name' => 'Layer Egg']);
    Product::factory()->create(['name' => 'Brown Egg']);

    $component = Livewire::test(Products::class);

    expect($component->get('products')->pluck('name'))
        ->toContain('Layer Egg')
        ->toContain('Brown Egg');
});

it('searches products by name prefix', function () {
    actingAsAdminForProducts();

    Product::factory()->create(['name' => 'Layer Egg']);
    Product::factory()->create(['name' => 'Brown Egg']);
    Product::factory()->create(['name' => 'Native Chicken']);

    $component = Livewire::test(Products::class)
        ->set('searchText', 'Layer');

    expect($component->get('products')->pluck('name'))
        ->toContain('Layer Egg')
        ->not->toContain('Brown Egg')
        ->not->toContain('Native Chicken');
});

it('clears search text', function () {
    actingAsAdminForProducts();

    Product::factory()->create(['name' => 'Layer Egg']);

    Livewire::test(Products::class)
        ->set('searchText', 'Egg')
        ->call('clearSearchText')
        ->assertSet('searchText', '');
});

it('filters products by low stock only', function () {
    actingAsAdminForProducts();

    Product::factory()->create(['name' => 'Low Stock Item', 'stock_level' => 5]);
    Product::factory()->create(['name' => 'High Stock Item', 'stock_level' => 100]);

    Livewire::test(Products::class)
        ->call('toggleLowStockOnly')
        ->assertSee('Low Stock Item')
        ->assertDontSee('High Stock Item');
});

it('clears filters', function () {
    actingAsAdminForProducts();

    Livewire::test(Products::class)
        ->set('filterField', 'price')
        ->set('filterValue', 'highest')
        ->call('clearFilters')
        ->assertSet('filterField', '')
        ->assertSet('filterValue', '');
});

it('computes total products count', function () {
    actingAsAdminForProducts();

    Product::factory()->count(3)->create();

    $component = Livewire::test(Products::class);
    $component->call('refreshData');

    expect($component->get('totalProducts'))->toBe(3);
});

it('computes low stock items count', function () {
    actingAsAdminForProducts();

    Product::factory()->create(['stock_level' => 5]);
    Product::factory()->create(['stock_level' => 10]);
    Product::factory()->create(['stock_level' => 50]);

    $component = Livewire::test(Products::class);
    $component->call('refreshData');

    expect($component->get('lowStockItems'))->toBe(2);
});

it('paginates products', function () {
    actingAsAdminForProducts();

    Product::factory()->count(15)->create();

    $component = Livewire::test(Products::class);

    expect($component->get('products')->count())->toBe(10)
        ->and($component->get('products')->hasMorePages())->toBeTrue();
});

it('loads categories subcategories and units', function () {
    actingAsAdminForProducts();

    Category::create(['category_name' => 'Vegetables']);
    Subcategory::create(['subcategory_name' => 'Leafy']);
    Unit::create(['unit_name' => 'kg']);

    $component = Livewire::test(Products::class);

    expect($component->get('categories')->pluck('category_name'))->toContain('Vegetables')
        ->and($component->get('subcategories')->pluck('subcategory_name'))->toContain('Leafy')
        ->and($component->get('units')->pluck('unit_name'))->toContain('kg');
});
