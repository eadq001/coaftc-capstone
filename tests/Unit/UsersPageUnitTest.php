<?php

use App\Enums\UserRoles;
use App\Models\User;

it('validates that admin role value is admin string', function () {
    expect(UserRoles::ADMIN->value)->toBe('admin');
});

it('validates that cashier role value is cashier string', function () {
    expect(UserRoles::CASHIER->value)->toBe('cashier');
});

it('validates that inventory role value is inventory_clerk string', function () {
    expect(UserRoles::INVENTORY->value)->toBe('inventory_clerk');
});

it('returns correct display name for each role', function () {
    expect(UserRoles::ADMIN->getUserRole())->toBe('administrator')
        ->and(UserRoles::CASHIER->getUserRole())->toBe('cashier')
        ->and(UserRoles::INVENTORY->getUserRole())->toBe('inventory clerk');
});

it('has all three roles defined', function () {
    $cases = UserRoles::cases();

    expect($cases)->toHaveCount(3)
        ->and($cases)->toContain(UserRoles::ADMIN)
        ->and($cases)->toContain(UserRoles::CASHIER)
        ->and($cases)->toContain(UserRoles::INVENTORY);
});

it('can create enum from string value', function () {
    expect(UserRoles::from('admin'))->toBe(UserRoles::ADMIN)
        ->and(UserRoles::from('cashier'))->toBe(UserRoles::CASHIER)
        ->and(UserRoles::from('inventory_clerk'))->toBe(UserRoles::INVENTORY);
});

it('user model uses has factory and notifiable traits', function () {
    $reflection = new ReflectionClass(User::class);

    expect($reflection->getTraitNames())->toContain('Illuminate\Database\Eloquent\Factories\HasFactory')
        ->and($reflection->getTraitNames())->toContain('Illuminate\Notifications\Notifiable');
});

it('user model extends authenticatable', function () {
    $reflection = new ReflectionClass(User::class);

    expect($reflection->getParentClass()->getName())->toBe('Illuminate\Foundation\Auth\User');
});
