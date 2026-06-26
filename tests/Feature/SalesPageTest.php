<?php

use App\Enums\UserRoles;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesItem;
use App\Models\Subcategory;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

function actingAsCashierForSales(): User
{
    $user = User::factory()->create([
        'user_role' => UserRoles::CASHIER,
        'name' => 'Test Cashier',
    ]);

    actingAs($user);

    return $user;
}

function createProductForSale(): Product
{
    $category = Category::create(['category_name' => 'Eggs']);
    $subcategory = Subcategory::create(['subcategory_name' => 'Brown']);
    $unit = Unit::create(['unit_name' => 'Tray']);

    return Product::create([
        'name' => 'Layer Egg',
        'stock_level' => 100,
        'price' => 180,
        'unit_id' => $unit->id,
        'category_id' => $category->id,
        'subcategory_id' => $subcategory->id,
        'user_id' => auth()->id(),
    ]);
}

it('allows cashier to access sales page', function () {
    actingAsCashierForSales();

    $this->get(route('dashboard.sales'))->assertOk();
});

it('allows admin to access sales page', function () {
    $admin = User::factory()->create(['user_role' => UserRoles::ADMIN]);
    actingAs($admin);

    $this->get(route('dashboard.sales'))->assertOk();
});

it('prevents inventory clerk from accessing sales page', function () {
    $inventory = User::factory()->create(['user_role' => UserRoles::INVENTORY]);
    actingAs($inventory);

    $this->get(route('dashboard.sales'))->assertNotFound();
});

it('finds a product by search id', function () {
    actingAsCashierForSales();
    $product = createProductForSale();

    Livewire::test('dashboard.sales.add-sales')
        ->set('searchId', (string) $product->id)
        ->assertSet('currentItem.id', $product->id)
        ->assertSet('currentItem.name', 'Layer Egg');
});

it('shows product not found for invalid search id', function () {
    actingAsCashierForSales();

    Livewire::test('dashboard.sales.add-sales')
        ->set('searchId', '99999')
        ->assertSet('showProductNotFound', true);
});

it('searches products by text', function () {
    actingAsCashierForSales();
    $product = createProductForSale();

    Livewire::test('dashboard.sales.add-sales')
        ->set('productSearchText', 'Layer')
        ->assertSet('productSearchResults', function ($results) use ($product) {
            return count($results) === 1 && $results[0]['id'] === $product->id;
        });
});

it('requires at least 2 characters for product text search', function () {
    actingAsCashierForSales();
    createProductForSale();

    Livewire::test('dashboard.sales.add-sales')
        ->set('productSearchText', 'L')
        ->assertSet('productSearchResults', []);
});

it('adds quantity to cart item', function () {
    actingAsCashierForSales();
    $product = createProductForSale();

    Livewire::test('dashboard.sales.add-sales')
        ->set('searchId', (string) $product->id)
        ->set('currentItemQuantity', 5)
        ->set('price', 180)
        ->call('addQuantity')
        ->assertSet('items', function ($items) {
            return count($items) === 1 && $items[0]['quantity'] === 5;
        });
});

it('prevents quantity exceeding available stock', function () {
    actingAsCashierForSales();
    $product = createProductForSale();

    Livewire::test('dashboard.sales.add-sales')
        ->set('searchId', (string) $product->id)
        ->set('currentItemQuantity', 200)
        ->set('price', 180)
        ->call('addQuantity')
        ->assertHasErrors('currentItemQuantity');
});

it('computes grand total after adding items', function () {
    actingAsCashierForSales();
    $product = createProductForSale();

    Livewire::test('dashboard.sales.add-sales')
        ->set('searchId', (string) $product->id)
        ->set('currentItemQuantity', 5)
        ->set('price', 180)
        ->call('addQuantity')
        ->call('grandTotal')
        ->assertSet('grandTotal', 900.0);
});

it('removes an item from the cart', function () {
    actingAsCashierForSales();
    $product = createProductForSale();

    Livewire::test('dashboard.sales.add-sales')
        ->set('searchId', (string) $product->id)
        ->set('currentItemQuantity', 5)
        ->set('price', 180)
        ->call('addQuantity')
        ->call('removeItem', 0)
        ->assertSet('items', []);
});

it('completes a sale with pay', function () {
    actingAsCashierForSales();
    $product = createProductForSale();

    Livewire::test('dashboard.sales.add-sales')
        ->set('searchId', (string) $product->id)
        ->set('currentItemQuantity', 5)
        ->set('price', 180)
        ->call('addQuantity')
        ->call('grandTotal')
        ->call('pay')
        ->assertSet('paid', true);

    $sale = Sale::firstOrFail();

    expect((float) $sale->total_amount)->toEqual(900.0)
        ->and($sale->salesItem)->toHaveCount(1)
        ->and((float) $sale->salesItem->first()->quantity)->toBe(5.0);

    $updatedProduct = Product::find($product->id);

    expect((float) $updatedProduct->stock_level)->toBe(95.0);
});

it('starts a new transaction', function () {
    actingAsCashierForSales();
    $product = createProductForSale();

    Livewire::test('dashboard.sales.add-sales')
        ->set('searchId', (string) $product->id)
        ->set('currentItemQuantity', 5)
        ->set('price', 180)
        ->call('addQuantity')
        ->call('newTransaction')
        ->assertSet('items', [])
        ->assertSet('paid', false);
});

it('searches a sale by PRF number', function () {
    actingAsCashierForSales();
    $product = createProductForSale();

    $sale = Sale::create(['user_id' => auth()->id(), 'total_amount' => 360]);
    SalesItem::create([
        'sale_id' => $sale->id,
        'product_id' => $product->id,
        'quantity' => 2,
        'unit_price' => 180,
        'subtotal' => 360,
        'inventory_start' => 100,
        'inventory_end' => 98,
    ]);

    Livewire::test('dashboard.sales.add-sales')
        ->set('prfSearch', $sale->prf_number)
        ->assertSet('prfReceipt.prfNumber', $sale->prf_number)
        ->assertSet('prfReceipt.grandTotal', 360);
});

it('shows not found for invalid PRF search', function () {
    actingAsCashierForSales();

    Livewire::test('dashboard.sales.add-sales')
        ->set('prfSearch', 'PRF99-999999')
        ->assertSet('showPrfNotFound', true);
});
