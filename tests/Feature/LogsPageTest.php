<?php

use App\Enums\UserRoles;
use App\Livewire\Dashboard\Logs\Logs;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

function actingAsAdminForLogs(): User
{
    $user = User::factory()->create(['user_role' => UserRoles::ADMIN]);

    actingAs($user);

    return $user;
}

function createActivityLogForTest(string $action = 'create', string $model = 'Product'): ActivityLog
{
    return ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => $action,
        'model' => $model,
        'old_values' => ['name' => 'Old Name'],
        'new_values' => ['name' => 'New Name'],
        'date_time' => now(),
    ]);
}

it('allows admin to access logs page', function () {
    actingAsAdminForLogs();

    $this->get(route('dashboard.logs'))->assertOk();
});

it('prevents cashier from accessing logs page', function () {
    $cashier = User::factory()->create(['user_role' => UserRoles::CASHIER]);
    actingAs($cashier);

    $this->get(route('dashboard.logs'))->assertNotFound();
});

it('prevents inventory clerk from accessing logs page', function () {
    $inventory = User::factory()->create(['user_role' => UserRoles::INVENTORY]);
    actingAs($inventory);

    $this->get(route('dashboard.logs'))->assertNotFound();
});

it('paginates logs with 10 per page', function () {
    actingAsAdminForLogs();

    for ($i = 0; $i < 15; $i++) {
        createActivityLogForTest();
    }

    $component = Livewire::test(Logs::class);

    expect($component->get('logs')->count())->toBe(10)
        ->and($component->get('logs')->hasMorePages())->toBeTrue();
});

it('orders logs by latest date_time first', function () {
    actingAsAdminForLogs();

    $oldLog = ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => 'create',
        'model' => 'Product',
        'old_values' => [],
        'new_values' => ['name' => 'Old Log'],
        'date_time' => now()->subHour(),
    ]);

    $newLog = ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => 'create',
        'model' => 'Product',
        'old_values' => [],
        'new_values' => ['name' => 'New Log'],
        'date_time' => now(),
    ]);

    $component = Livewire::test(Logs::class);

    expect($component->get('logs')->first()->id)->toBe($newLog->id);
});

it('loads logs with user relationship', function () {
    $user = actingAsAdminForLogs();

    createActivityLogForTest();

    $component = Livewire::test(Logs::class);

    expect($component->get('logs')->first()->user->id)->toBe($user->id);
});

it('shows log details when showDetails is called', function () {
    actingAsAdminForLogs();

    $log = createActivityLogForTest('update', 'Product');

    $component = Livewire::test(Logs::class);
    $component->call('showDetails', $log->id);

    $selectedLog = $component->get('selectedLog');

    expect($selectedLog['action'])->toBe('update')
        ->and($selectedLog['model'])->toBe('Product')
        ->and($selectedLog['old_values'])->toBe(['name' => 'Old Name'])
        ->and($selectedLog['new_values'])->toBe(['name' => 'New Name']);
});

it('sets showLogDetails to true when showing details', function () {
    actingAsAdminForLogs();

    $log = createActivityLogForTest();

    Livewire::test(Logs::class)
        ->call('showDetails', $log->id)
        ->assertSet('showLogDetails', true);
});

it('initializes with showLogDetails as false', function () {
    actingAsAdminForLogs();

    Livewire::test(Logs::class)
        ->assertSet('showLogDetails', false);
});

it('initializes with empty selectedLog', function () {
    actingAsAdminForLogs();

    Livewire::test(Logs::class)
        ->assertSet('selectedLog', []);
});
