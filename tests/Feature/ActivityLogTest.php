<?php

use App\Enums\UserRoles;
use App\Livewire\Components\ProductFormAdd;
use App\Livewire\Components\ProductFormEdit;
use App\Livewire\Components\RemoveModal;
use App\Livewire\Components\RestoreModal;
use App\Livewire\Dashboard\Logs\Logs;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Unit;
use App\Models\UnverifiedUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

function actingAsAdmin(): User
{
    $user = User::factory()->create([
        'user_role' => UserRoles::ADMIN,
    ]);

    actingAs($user);

    return $user;
}

function createProductDependencies(): array
{
    return [
        'category' => Category::create(['category_name' => 'Chicken Eggs']),
        'subcategory' => Subcategory::create(['subcategory_name' => 'Brown Eggs']),
        'unit' => Unit::create(['unit_name' => 'Tray']),
    ];
}

it('logs product create update delete and restore actions', function () {
    actingAsAdmin();
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

    expect(ActivityLog::where('model', 'Product')->where('action', 'create')->exists())->toBeTrue();

    Livewire::test(ProductFormEdit::class, ['productToEdit' => $product->id])
        ->set('productForm.name', 'Updated Layer Egg')
        ->set('stockLevel', 30)
        ->set('price', 190)
        ->call('update');

    $updateLog = ActivityLog::where('model', 'Product')->where('action', 'update')->latest('id')->firstOrFail();

    expect($updateLog->old_values['name'])->toBe('Layer Egg')
        ->and($updateLog->new_values['name'])->toBe('Updated Layer Egg');

    Livewire::test(RemoveModal::class, [
        'id' => $product->id,
        'name' => $product->name,
        'modelName' => 'Product',
    ])->call('softDeleteItem');

    expect(Product::onlyTrashed()->whereKey($product->id)->exists())->toBeTrue()
        ->and(ActivityLog::where('model', 'Product')->where('action', 'delete')->exists())->toBeTrue();

    Livewire::test(RestoreModal::class, [
        'id' => $product->id,
        'modelName' => 'Product',
        'itemName' => $product->name,
    ])->call('restoreDeletedItem');

    expect(Product::whereKey($product->id)->exists())->toBeTrue()
        ->and(ActivityLog::where('model', 'Product')->where('action', 'restore')->exists())->toBeTrue();
});

it('logs category subcategory and unit create update delete and restore actions', function (string $model, string $addComponent, string $editComponent, string $field, string $initialValue, string $updatedValue) {
    actingAsAdmin();

    Livewire::test($addComponent)
        ->set($field, $initialValue)
        ->call('save');

    $modelClass = match ($model) {
        'Category' => Category::class,
        'Subcategory' => Subcategory::class,
        'Unit' => Unit::class,
    };

    $record = $modelClass::firstOrFail();

    expect(ActivityLog::where('model', $model)->where('action', 'create')->exists())->toBeTrue();

    Livewire::test($editComponent, [lcfirst($model).'ToEdit' => $record->id])
        ->set($field, $updatedValue)
        ->call('update');

    $updateLog = ActivityLog::where('model', $model)->where('action', 'update')->latest('id')->firstOrFail();

    expect($updateLog->old_values[$field])->toBe($initialValue)
        ->and($updateLog->new_values[$field])->toBe($updatedValue);

    Livewire::test(RemoveModal::class, [
        'id' => $record->id,
        'name' => $updatedValue,
        'modelName' => $model,
    ])->call('softDeleteItem');

    expect($modelClass::onlyTrashed()->whereKey($record->id)->exists())->toBeTrue()
        ->and(ActivityLog::where('model', $model)->where('action', 'delete')->exists())->toBeTrue();

    Livewire::test(RestoreModal::class, [
        'id' => $record->id,
        'modelName' => $model,
        'itemName' => $updatedValue,
    ])->call('restoreDeletedItem');

    expect($modelClass::whereKey($record->id)->exists())->toBeTrue()
        ->and(ActivityLog::where('model', $model)->where('action', 'restore')->exists())->toBeTrue();
})->with([
    ['Category', 'dashboard.forms.product-category-form-add', 'dashboard.forms.product-category-form-edit', 'category_name', 'Layer Eggs', 'Updated Layer Eggs'],
    ['Subcategory', 'dashboard.forms.product-subcategory-form-add', 'dashboard.forms.product-subcategory-form-edit', 'subcategory_name', 'Brown Eggs', 'Updated Brown Eggs'],
    ['Unit', 'dashboard.forms.product-unit-form-add', 'dashboard.forms.product-unit-form-edit', 'unit_name', 'Tray', 'Box'],
]);

it('logs admin user creation and email verification', function () {
    actingAsAdmin();

    Livewire::test('dashboard.users.create-users')
        ->set('username', 'Inventory Clerk')
        ->set('email', 'inventory@example.com')
        ->set('user_role', UserRoles::INVENTORY->value)
        ->set('password', 'password123')
        ->set('confirmPassword', 'password123')
        ->call('register');

    expect(UnverifiedUser::where('email', 'inventory@example.com')->exists())->toBeTrue()
        ->and(ActivityLog::where('model', 'User')->where('action', 'create')->exists())->toBeTrue();

    $unverifiedUser = UnverifiedUser::where('email', 'inventory@example.com')->firstOrFail();
    $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(10), ['token' => $unverifiedUser->verification_token]);

    auth()->logout();

    $this->get($url)->assertSuccessful()->assertSee('Email Verified');

    expect(User::where('email', 'inventory@example.com')->exists())->toBeTrue()
        ->and(ActivityLog::where('model', 'User')->where('action', 'verify_email')->exists())->toBeTrue();
});

it('opens a log details popup with old and new values', function () {
    $user = actingAsAdmin();

    $log = ActivityLog::record(
        action: 'update',
        model: 'Product',
        oldValues: ['name' => 'Old Egg'],
        newValues: ['name' => 'New Egg'],
        userId: $user->id,
    );

    Livewire::test(Logs::class)
        ->call('showDetails', $log->id)
        ->assertSet('showLogDetails', true)
        ->assertSee('Old Egg')
        ->assertSee('New Egg')
        ->assertSee('Product')
        ->assertSee($user->name);
});
