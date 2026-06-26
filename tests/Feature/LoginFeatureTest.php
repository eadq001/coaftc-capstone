<?php

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

function createUserForLogin(string $email = 'admin@coaftc.test', string $password = 'password123'): User
{
    return User::factory()->create([
        'email' => $email,
        'password' => $password,
        'user_role' => UserRoles::ADMIN,
    ]);
}

it('logs in successfully with valid credentials', function () {
    createUserForLogin();

    Livewire::test('login')
        ->set('email', 'admin@coaftc.test')
        ->set('password', 'password123')
        ->call('login')
        ->assertHasNoErrors();

    expect(auth()->check())->toBeTrue();
});

it('rejects login with invalid email', function () {
    createUserForLogin();

    Livewire::test('login')
        ->set('email', 'wrong@coaftc.test')
        ->set('password', 'password123')
        ->call('login')
        ->assertHasErrors('loginFailed');

    expect(auth()->check())->toBeFalse();
});

it('rejects login with invalid password', function () {
    createUserForLogin();

    Livewire::test('login')
        ->set('email', 'admin@coaftc.test')
        ->set('password', 'wrong-password')
        ->call('login')
        ->assertHasErrors('loginFailed');

    expect(auth()->check())->toBeFalse();
});

it('validates email is required', function () {
    Livewire::test('login')
        ->set('email', '')
        ->set('password', 'password123')
        ->call('login')
        ->assertHasErrors(['email' => 'required']);
});

it('validates password is required', function () {
    Livewire::test('login')
        ->set('email', 'admin@coaftc.test')
        ->set('password', '')
        ->call('login')
        ->assertHasErrors(['password' => 'required']);
});

it('validates email format', function () {
    Livewire::test('login')
        ->set('email', 'not-an-email')
        ->set('password', 'password123')
        ->call('login')
        ->assertHasErrors(['email' => 'email']);
});

it('validates password minimum length', function () {
    Livewire::test('login')
        ->set('email', 'admin@coaftc.test')
        ->set('password', 'short')
        ->call('login')
        ->assertHasErrors(['password' => 'min']);
});

it('allows cashier role to log in', function () {
    User::factory()->create([
        'email' => 'cashier@coaftc.test',
        'password' => 'password123',
        'user_role' => UserRoles::CASHIER,
    ]);

    Livewire::test('login')
        ->set('email', 'cashier@coaftc.test')
        ->set('password', 'password123')
        ->call('login')
        ->assertHasNoErrors();

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user()->user_role)->toBe(UserRoles::CASHIER);
});

it('allows inventory clerk role to log in', function () {
    User::factory()->create([
        'email' => 'inventory@coaftc.test',
        'password' => 'password123',
        'user_role' => UserRoles::INVENTORY,
    ]);

    Livewire::test('login')
        ->set('email', 'inventory@coaftc.test')
        ->set('password', 'password123')
        ->call('login')
        ->assertHasNoErrors();

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user()->user_role)->toBe(UserRoles::INVENTORY);
});

it('redirects to dashboard after successful login', function () {
    createUserForLogin();

    Livewire::test('login')
        ->set('email', 'admin@coaftc.test')
        ->set('password', 'password123')
        ->call('login')
        ->assertRedirect(route('dashboard.home'));
});

it('prevents authenticated user from accessing login page', function () {
    $user = createUserForLogin();
    actingAs($user);

    $this->get(route('login'))->assertRedirect('/dashboard');
});
