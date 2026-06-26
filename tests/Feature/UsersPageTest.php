<?php

use App\Enums\UserRoles;
use App\Models\ActivityLog;
use App\Models\UnverifiedUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

function actingAsAdminForUsers(): User
{
    $user = User::factory()->create(['user_role' => UserRoles::ADMIN]);

    actingAs($user);

    return $user;
}

it('allows admin to access users page', function () {
    actingAsAdminForUsers();

    $this->get(route('dashboard.users'))->assertOk();
});

it('prevents cashier from accessing users page', function () {
    $cashier = User::factory()->create(['user_role' => UserRoles::CASHIER]);
    actingAs($cashier);

    $this->get(route('dashboard.users'))->assertNotFound();
});

it('prevents inventory clerk from accessing users page', function () {
    $inventory = User::factory()->create(['user_role' => UserRoles::INVENTORY]);
    actingAs($inventory);

    $this->get(route('dashboard.users'))->assertNotFound();
});

it('creates an unverified user with valid data', function () {
    actingAsAdminForUsers();

    Livewire::test('dashboard.users.create-users')
        ->set('username', 'New User')
        ->set('email', 'newuser@coaftc.test')
        ->set('user_role', UserRoles::CASHIER->value)
        ->set('password', 'password123')
        ->set('confirmPassword', 'password123')
        ->set('verification_token', 'test-token-123')
        ->call('register')
        ->assertHasNoErrors()
        ->assertSet('userCreated', true);

    expect(UnverifiedUser::where('email', 'newuser@coaftc.test')->exists())->toBeTrue();
});

it('validates required username', function () {
    actingAsAdminForUsers();

    Livewire::test('dashboard.users.create-users')
        ->set('username', '')
        ->set('email', 'test@coaftc.test')
        ->set('user_role', UserRoles::CASHIER->value)
        ->set('password', 'password123')
        ->set('confirmPassword', 'password123')
        ->set('verification_token', 'token')
        ->call('register')
        ->assertHasErrors(['username' => 'required']);
});

it('validates required email', function () {
    actingAsAdminForUsers();

    Livewire::test('dashboard.users.create-users')
        ->set('username', 'Test User')
        ->set('email', '')
        ->set('user_role', UserRoles::CASHIER->value)
        ->set('password', 'password123')
        ->set('confirmPassword', 'password123')
        ->set('verification_token', 'token')
        ->call('register')
        ->assertHasErrors(['email' => 'required']);
});

it('validates unique email against existing users', function () {
    actingAsAdminForUsers();
    User::factory()->create(['email' => 'existing@coaftc.test']);

    Livewire::test('dashboard.users.create-users')
        ->set('username', 'Test User')
        ->set('email', 'existing@coaftc.test')
        ->set('user_role', UserRoles::CASHIER->value)
        ->set('password', 'password123')
        ->set('confirmPassword', 'password123')
        ->set('verification_token', 'token')
        ->call('register')
        ->assertHasErrors(['email' => 'unique']);
});

it('validates unique email against unverified users', function () {
    actingAsAdminForUsers();
    UnverifiedUser::create([
        'username' => 'Pending',
        'email' => 'pending@coaftc.test',
        'password' => 'hashed',
        'user_role' => UserRoles::CASHIER,
        'verification_token' => 'token',
    ]);

    Livewire::test('dashboard.users.create-users')
        ->set('username', 'Test User')
        ->set('email', 'pending@coaftc.test')
        ->set('user_role', UserRoles::CASHIER->value)
        ->set('password', 'password123')
        ->set('confirmPassword', 'password123')
        ->set('verification_token', 'new-token')
        ->call('register')
        ->assertHasErrors(['email' => 'unique']);
});

it('validates password minimum length', function () {
    actingAsAdminForUsers();

    Livewire::test('dashboard.users.create-users')
        ->set('username', 'Test User')
        ->set('email', 'test@coaftc.test')
        ->set('user_role', UserRoles::CASHIER->value)
        ->set('password', 'short')
        ->set('confirmPassword', 'short')
        ->set('verification_token', 'token')
        ->call('register')
        ->assertHasErrors(['password' => 'min']);
});

it('validates password confirmation matches', function () {
    actingAsAdminForUsers();

    Livewire::test('dashboard.users.create-users')
        ->set('username', 'Test User')
        ->set('email', 'test@coaftc.test')
        ->set('user_role', UserRoles::CASHIER->value)
        ->set('password', 'password123')
        ->set('confirmPassword', 'different123')
        ->set('verification_token', 'token')
        ->call('register')
        ->assertHasErrors(['confirmPassword' => 'same']);
});

it('validates user role is required', function () {
    actingAsAdminForUsers();

    Livewire::test('dashboard.users.create-users')
        ->set('username', 'Test User')
        ->set('email', 'test@coaftc.test')
        ->set('user_role', '')
        ->set('password', 'password123')
        ->set('confirmPassword', 'password123')
        ->set('verification_token', 'token')
        ->call('register')
        ->assertHasErrors(['user_role' => 'required']);
});

it('logs user creation in activity log', function () {
    actingAsAdminForUsers();

    Livewire::test('dashboard.users.create-users')
        ->set('username', 'New User')
        ->set('email', 'newuser@coaftc.test')
        ->set('user_role', UserRoles::CASHIER->value)
        ->set('password', 'password123')
        ->set('confirmPassword', 'password123')
        ->set('verification_token', 'test-token-123')
        ->call('register');

    expect(ActivityLog::where('model', 'User')->where('action', 'create')->exists())->toBeTrue();
});

it('resets form on cancel', function () {
    actingAsAdminForUsers();

    Livewire::test('dashboard.users.create-users')
        ->set('username', 'Test User')
        ->set('email', 'test@coaftc.test')
        ->call('cancel')
        ->assertSet('username', '')
        ->assertSet('email', '');
});
