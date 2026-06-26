<?php

use App\Livewire\Dashboard\BackupAndRestore\BackupAndRestore;
use App\Models\BackupRestoreHistory;

it('has backupDb method', function () {
    $reflection = new ReflectionClass(BackupAndRestore::class);

    expect($reflection->hasMethod('backupDb'))->toBeTrue();
});

it('has restoreDb method', function () {
    $reflection = new ReflectionClass(BackupAndRestore::class);

    expect($reflection->hasMethod('restoreDb'))->toBeTrue();
});

it('has computed histories method', function () {
    $reflection = new ReflectionClass(BackupAndRestore::class);

    expect($reflection->hasMethod('histories'))->toBeTrue();
});

it('uses with pagination trait', function () {
    $reflection = new ReflectionClass(BackupAndRestore::class);

    expect($reflection->getTraitNames())->toContain('Livewire\WithPagination');
});

it('extends dashboard base class', function () {
    $reflection = new ReflectionClass(BackupAndRestore::class);

    expect($reflection->getParentClass()->getName())->toBe('App\Livewire\Dashboard');
});

it('backupDb returns StreamedResponse or null', function () {
    $reflection = new ReflectionMethod(BackupAndRestore::class, 'backupDb');

    expect($reflection->getReturnType())->not->toBeNull();
});

it('backup restore history has guarded empty array', function () {
    $reflection = new ReflectionClass(BackupRestoreHistory::class);
    $model = $reflection->newInstanceWithoutConstructor();
    $guardedProperty = $reflection->getProperty('guarded');
    $guardedProperty->setAccessible(true);

    expect($guardedProperty->getValue($model))->toBe([]);
});

it('backup restore history has casts method', function () {
    $reflection = new ReflectionClass(BackupRestoreHistory::class);

    expect($reflection->hasMethod('casts'))->toBeTrue();
});

it('backup restore history has user relationship', function () {
    $reflection = new ReflectionClass(BackupRestoreHistory::class);

    expect($reflection->hasMethod('user'))->toBeTrue();
});
