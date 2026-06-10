<?php

use App\Enums\UserRoles;
use App\Livewire\Dashboard\Sales\VoidSales;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesItem;
use App\Models\User;
use App\Models\VoidedSale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createAdmin(): User
{
    $user = User::factory()->create([
        'user_role' => UserRoles::ADMIN,
    ]);

    return $user;
}

function createCashier(): User
{
    $user = User::factory()->create([
        'user_role' => UserRoles::CASHIER,
    ]);

    return $user;
}

function createSaleWithItems(int $itemCount = 2): Sale
{
    $sale = Sale::factory()->create();

    for ($i = 0; $i < $itemCount; $i++) {
        $product = Product::factory()->create([
            'stock_level' => 50,
        ]);

        SalesItem::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'unit_price' => 100,
            'subtotal' => 500,
            'inventory_start' => 50,
            'inventory_end' => 45,
        ]);
    }

    $sale->update(['total_amount' => $sale->salesItem->sum('subtotal')]);

    return $sale->fresh();
}

it('allows cashier and admin to access the void sales page', function () {
    $cashier = createCashier();
    $admin = createAdmin();

    $this->actingAs($cashier)
        ->get(route('dashboard.void-sales'))
        ->assertOk();

    $this->actingAs($admin)
        ->get(route('dashboard.void-sales'))
        ->assertOk();
});

it('does not allow inventory clerk to access the void sales page', function () {
    $inventory = User::factory()->create([
        'user_role' => UserRoles::INVENTORY,
    ]);

    $this->actingAs($inventory)
        ->get(route('dashboard.void-sales'))
        ->assertNotFound();
});

it('searches a sale by prf number', function () {
    $cashier = createCashier();
    $sale = createSaleWithItems();

    $this->actingAs($cashier);

    Livewire::test(VoidSales::class)
        ->set('prfSearch', $sale->prf_number)
        ->assertSet('sale.id', $sale->id)
        ->assertSee($sale->prf_number)
        ->assertSee($sale->user->name);
});

it('shows not found when prf number does not exist', function () {
    $cashier = createCashier();

    $this->actingAs($cashier);

    Livewire::test(VoidSales::class)
        ->set('prfSearch', 'PRF99-999999')
        ->assertSet('showPrfNotFound', true);
});

it('requires admin authentication to edit a sale', function () {
    $cashier = createCashier();
    $sale = createSaleWithItems();

    $this->actingAs($cashier);

    Livewire::test(VoidSales::class)
        ->set('prfSearch', $sale->prf_number)
        ->call('startEditing')
        ->assertSet('showAuthModal', true);
});

it('allows editing a sale after admin authentication', function () {
    $cashier = createCashier();
    $admin = createAdmin();
    $sale = createSaleWithItems(2);

    $product = $sale->salesItem->first()->product;
    $originalStock = $product->stock_level;

    $this->actingAs($cashier);

    Livewire::test(VoidSales::class)
        ->set('prfSearch', $sale->prf_number)
        ->call('startEditing')
        ->set('authEmail', $admin->email)
        ->set('authPassword', 'password')
        ->call('authenticateAdmin')
        ->assertSet('authorizedAdminId', $admin->id)
        ->call('startEditing')
        ->assertSet('isEditing', true)
        ->call('updateEditQuantity', 0, 2)
        ->call('saveEdit')
        ->assertSet('isEditing', false);

    $sale->refresh();
    expect($sale->total_amount)->toBe(700)
        ->and($sale->salesItem->count())->toBe(2)
        ->and($product->fresh()->stock_level)->toBe($originalStock + 3);

    expect(VoidedSale::where('action', 'modified')->exists())->toBeTrue();
});

it('allows removing a product from a sale and restores inventory', function () {
    $cashier = createCashier();
    $admin = createAdmin();
    $sale = createSaleWithItems(2);

    $product = $sale->salesItem->first()->product;
    $originalStock = $product->stock_level;
    $originalQuantity = $sale->salesItem->first()->quantity;

    $this->actingAs($cashier);

    Livewire::test(VoidSales::class)
        ->set('prfSearch', $sale->prf_number)
        ->call('startEditing')
        ->set('authEmail', $admin->email)
        ->set('authPassword', 'password')
        ->call('authenticateAdmin')
        ->call('startEditing')
        ->call('removeEditItem', 0)
        ->call('saveEdit');

    $sale->refresh();
    expect($sale->salesItem->count())->toBe(1)
        ->and($product->fresh()->stock_level)->toBe($originalStock + $originalQuantity);
});

it('allows voiding a sale after admin authentication', function () {
    $cashier = createCashier();
    $admin = createAdmin();
    $sale = createSaleWithItems(2);

    $product = $sale->salesItem->first()->product;
    $originalStock = $product->stock_level;
    $originalQuantity = $sale->salesItem->first()->quantity;

    $this->actingAs($cashier);

    Livewire::test(VoidSales::class)
        ->set('prfSearch', $sale->prf_number)
        ->call('confirmVoid')
        ->set('authEmail', $admin->email)
        ->set('authPassword', 'password')
        ->call('authenticateAdmin')
        ->assertSet('authorizedAdminId', $admin->id)
        ->set('voidReason', 'Customer request')
        ->call('voidSale')
        ->assertSet('sale', null);

    expect(Sale::where('id', $sale->id)->exists())->toBeFalse()
        ->and(VoidedSale::where('action', 'voided')->exists())->toBeTrue()
        ->and($product->fresh()->stock_level)->toBe($originalStock + $originalQuantity);
});

it('requires re-authentication after an action', function () {
    $cashier = createCashier();
    $admin = createAdmin();
    $sale = createSaleWithItems(2);

    $this->actingAs($cashier);

    $component = Livewire::test(VoidSales::class)
        ->set('prfSearch', $sale->prf_number)
        ->call('startEditing')
        ->set('authEmail', $admin->email)
        ->set('authPassword', 'password')
        ->call('authenticateAdmin')
        ->call('startEditing')
        ->call('updateEditQuantity', 0, 2)
        ->call('saveEdit')
        ->assertSet('authorizedAdminId', null);

    // Try to edit again — should prompt auth
    $component->call('startEditing')
        ->assertSet('showAuthModal', true);
});

it('shows invalid credentials for non-admin user', function () {
    $cashier = createCashier();
    $sale = createSaleWithItems();

    $this->actingAs($cashier);

    Livewire::test(VoidSales::class)
        ->set('prfSearch', $sale->prf_number)
        ->call('startEditing')
        ->set('authEmail', $cashier->email)
        ->set('authPassword', 'password')
        ->call('authenticateAdmin')
        ->assertHasErrors('authPassword');
});

it('shows paginated voided sales at the bottom', function () {
    $cashier = createCashier();
    $admin = createAdmin();

    $this->actingAs($cashier);

    for ($i = 0; $i < 15; $i++) {
        $sale = createSaleWithItems(1);

        Livewire::test(VoidSales::class)
            ->set('prfSearch', $sale->prf_number)
            ->call('confirmVoid')
            ->set('authEmail', $admin->email)
            ->set('authPassword', 'password')
            ->call('authenticateAdmin')
            ->call('voidSale');
    }

    Livewire::test(VoidSales::class)
        ->assertViewHas('voidedSales', function ($paginator) {
            return $paginator->count() === 10;
        });
});

it('voids a sale when all items are removed during edit', function () {
    $cashier = createCashier();
    $admin = createAdmin();
    $sale = createSaleWithItems(1);

    $product = $sale->salesItem->first()->product;
    $originalStock = $product->stock_level;
    $originalQuantity = $sale->salesItem->first()->quantity;

    $this->actingAs($cashier);

    Livewire::test(VoidSales::class)
        ->set('prfSearch', $sale->prf_number)
        ->call('startEditing')
        ->set('authEmail', $admin->email)
        ->set('authPassword', 'password')
        ->call('authenticateAdmin')
        ->call('startEditing')
        ->call('removeEditItem', 0)
        ->call('saveEdit')
        ->assertSet('sale', null);

    expect(Sale::where('id', $sale->id)->exists())->toBeFalse()
        ->and(VoidedSale::where('action', 'voided')->exists())->toBeTrue()
        ->and($product->fresh()->stock_level)->toBe($originalStock + $originalQuantity);
});
