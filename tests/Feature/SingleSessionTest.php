<?php

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

function createSessionRecord(string $sessionId, int $userId): void
{
    DB::table('sessions')->insert([
        'id' => $sessionId,
        'user_id' => $userId,
        'ip_address' => '127.0.0.1',
        'payload' => 'test-payload',
        'last_activity' => time(),
    ]);
}

it('deletes other sessions for the same user when they log in again', function () {
    $user = createUserForSessionTest();

    createSessionRecord('old-session-aaa', $user->id);

    Livewire::test('login')
        ->set('email', 'admin@coaftc.test')
        ->set('password', 'password123')
        ->call('login');

    expect(DB::table('sessions')->where('id', 'old-session-aaa')->exists())->toBeFalse();
});

it('only deletes sessions belonging to the logging in user', function () {
    $user1 = createUserForSessionTest();

    $user2 = User::factory()->create([
        'email' => 'other@coaftc.test',
        'password' => 'password456',
        'user_role' => UserRoles::CASHIER,
    ]);

    createSessionRecord('user2-session-ccc', $user2->id);

    Livewire::test('login')
        ->set('email', 'admin@coaftc.test')
        ->set('password', 'password123')
        ->call('login');

    expect(DB::table('sessions')->where('id', 'user2-session-ccc')->exists())->toBeTrue();
});

it('logs out a user whose session was deleted by a newer login', function () {
    config()->set('session.driver', 'database');

    $user = createUserForSessionTest();

    $this->actingAs($user);

    $sessionId = session()->getId();

    // Simulate a newer login from another device that deletes this session
    DB::table('sessions')->where('id', $sessionId)->delete();

    $this->get('/dashboard')->assertRedirect('/login');
});

it('skips the session check when the session driver is not database', function () {
    // Default test env uses 'array' driver — middleware should be a no-op
    $user = createUserForSessionTest();

    $this->actingAs($user);

    // Should NOT redirect to /login (the middleware skips the check)
    $this->get('/dashboard/products/qr')->assertOk();
});
