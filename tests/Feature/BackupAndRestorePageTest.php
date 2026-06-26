<?php

use App\Enums\UserRoles;
use App\Livewire\Dashboard\BackupAndRestore\BackupAndRestore;
use App\Models\BackupRestoreHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

function actingAsAdminForBackup(): User
{
    $user = User::factory()->create(['user_role' => UserRoles::ADMIN]);

    actingAs($user);

    return $user;
}

it('allows admin to access backup and restore page', function () {
    actingAsAdminForBackup();

    $this->get(route('dashboard.backup-and-restore'))->assertOk();
});

it('prevents cashier from accessing backup and restore page', function () {
    $cashier = User::factory()->create(['user_role' => UserRoles::CASHIER]);
    actingAs($cashier);

    $this->get(route('dashboard.backup-and-restore'))->assertNotFound();
});

it('prevents inventory clerk from accessing backup and restore page', function () {
    $inventory = User::factory()->create(['user_role' => UserRoles::INVENTORY]);
    actingAs($inventory);

    $this->get(route('dashboard.backup-and-restore'))->assertNotFound();
});

it('paginates backup restore histories with 15 per page', function () {
    actingAsAdminForBackup();

    for ($i = 0; $i < 20; $i++) {
        BackupRestoreHistory::create([
            'user_id' => auth()->id(),
            'action' => 'backup',
            'status' => 'completed',
            'file_name' => "backup-{$i}.zip",
            'file_path' => "coaftc-backup/backup-{$i}.zip",
            'file_size' => 1024,
            'started_at' => now(),
            'completed_at' => now(),
        ]);
    }

    $component = Livewire::test(BackupAndRestore::class);

    expect($component->get('histories')->count())->toBe(15)
        ->and($component->get('histories')->hasMorePages())->toBeTrue();
});

it('displays backup histories', function () {
    actingAsAdminForBackup();

    BackupRestoreHistory::create([
        'user_id' => auth()->id(),
        'action' => 'backup',
        'status' => 'completed',
        'file_name' => 'old-backup.zip',
        'file_path' => 'coaftc-backup/old-backup.zip',
        'file_size' => 1024,
        'started_at' => now()->subHour(),
        'completed_at' => now()->subHour(),
    ]);

    BackupRestoreHistory::create([
        'user_id' => auth()->id(),
        'action' => 'backup',
        'status' => 'completed',
        'file_name' => 'new-backup.zip',
        'file_path' => 'coaftc-backup/new-backup.zip',
        'file_size' => 2048,
        'started_at' => now(),
        'completed_at' => now(),
    ]);

    $component = Livewire::test(BackupAndRestore::class);

    expect($component->get('histories')->total())->toBe(2);
});

it('loads histories with user relationship', function () {
    $user = actingAsAdminForBackup();

    BackupRestoreHistory::create([
        'user_id' => $user->id,
        'action' => 'backup',
        'status' => 'completed',
        'file_name' => 'test-backup.zip',
        'file_path' => 'coaftc-backup/test-backup.zip',
        'file_size' => 1024,
        'started_at' => now(),
        'completed_at' => now(),
    ]);

    $component = Livewire::test(BackupAndRestore::class);

    expect($component->get('histories')->first()->user->id)->toBe($user->id);
});
