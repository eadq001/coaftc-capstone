<?php

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

function createUserForSessionTest(): User
{
    return User::factory()->create([
        'email' => 'admin@coaftc.test',
        'password' => 'password123',
        'user_role' => UserRoles::ADMIN,
    ]);
}

it('marks old sessions as kicked in cache when a user logs in again', function () {
    $user = createUserForSessionTest();

    DB::table('sessions')->insert([
        'id' => 'old-session-aaa',
        'user_id' => $user->id,
        'ip_address' => '127.0.0.1',
        'payload' => 'test',
        'last_activity' => time(),
    ]);

    Livewire::test('login')
        ->set('email', 'admin@coaftc.test')
        ->set('password', 'password123')
        ->call('login');

    expect(Cache::has('kicked:old-session-aaa'))->toBeTrue();
});

it('does not delete old sessions from database when logging in again', function () {
    $user = createUserForSessionTest();

    DB::table('sessions')->insert([
        'id' => 'old-session-bbb',
        'user_id' => $user->id,
        'ip_address' => '127.0.0.1',
        'payload' => 'test',
        'last_activity' => time(),
    ]);

    Livewire::test('login')
        ->set('email', 'admin@coaftc.test')
        ->set('password', 'password123')
        ->call('login');

    expect(DB::table('sessions')->where('id', 'old-session-bbb')->exists())->toBeTrue();
});

it('does not log out a user without a kicked cache key', function () {
    $user = createUserForSessionTest();

    $this->actingAs($user);

    $this->get('/dashboard/products/qr')->assertOk();
});

it('does not mark other users sessions as kicked', function () {
    $user1 = createUserForSessionTest();

    $user2 = User::factory()->create([
        'email' => 'other@coaftc.test',
        'password' => 'password456',
        'user_role' => UserRoles::CASHIER,
    ]);

    DB::table('sessions')->insert([
        'id' => 'user2-session-ccc',
        'user_id' => $user2->id,
        'ip_address' => '127.0.0.1',
        'payload' => 'test',
        'last_activity' => time(),
    ]);

    Livewire::test('login')
        ->set('email', 'admin@coaftc.test')
        ->set('password', 'password123')
        ->call('login');

    expect(Cache::has('kicked:user2-session-ccc'))->toBeFalse();
});
