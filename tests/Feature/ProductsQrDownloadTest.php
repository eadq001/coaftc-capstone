<?php

use App\Enums\ProductClass;
use App\Enums\UserRoles;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

function actingAsAdminForQr(): User
{
    $user = User::factory()->create(['user_role' => UserRoles::ADMIN]);

    actingAs($user);

    return $user;
}

it('shows a download button and downloads an HTML QR sheet for the current page', function () {
    actingAsAdminForQr();

    Product::factory()->create(['name' => 'Layer Egg', 'class' => ProductClass::A, 'size' => null]);
    Product::factory()->create(['name' => 'Brown Egg', 'class' => null, 'size' => 'Large']);

    Livewire::test('dashboard.products.products-qr')
        ->assertSee('Download Qr')
        ->call('downloadQrSheet')
        ->assertFileDownloaded('product-qr-codes-page-1.html');
});

it('renders the QR sheet view with product name, category and class or size', function () {
    actingAsAdminForQr();

    $product = Product::factory()->create([
        'name' => 'Layer Egg',
        'class' => ProductClass::A,
        'size' => null,
    ]);

    $html = view('livewire.dashboard.products.qr-print-sheet', [
        'products' => [$product],
        'page' => 1,
        'generatedAt' => now()->format('M d, Y H:i'),
    ])->render();

    expect($html)
        ->toContain('Layer Egg')
        ->toContain($product->category->category_name)
        ->toContain('Class: A')
        ->toContain('data:image/png;base64,');
});
