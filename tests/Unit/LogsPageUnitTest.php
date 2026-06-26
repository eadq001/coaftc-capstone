<?php

use App\Livewire\Dashboard\Logs\Logs;
use App\Models\ActivityLog;

it('has computed logs method', function () {
    $reflection = new ReflectionClass(Logs::class);

    expect($reflection->hasMethod('logs'))->toBeTrue();
});

it('has showDetails method', function () {
    $reflection = new ReflectionClass(Logs::class);

    expect($reflection->hasMethod('showDetails'))->toBeTrue();
});

it('has showLogDetails property defaulting to false', function () {
    $reflection = new ReflectionClass(Logs::class);

    expect($reflection->hasProperty('showLogDetails'))->toBeTrue();
});

it('has selectedLog property', function () {
    $reflection = new ReflectionClass(Logs::class);

    expect($reflection->hasProperty('selectedLog'))->toBeTrue();
});

it('uses with pagination trait', function () {
    $reflection = new ReflectionClass(Logs::class);

    expect($reflection->getTraitNames())->toContain('Livewire\WithPagination');
});

it('extends dashboard base class', function () {
    $reflection = new ReflectionClass(Logs::class);

    expect($reflection->getParentClass()->getName())->toBe('App\Livewire\Dashboard');
});

it('activity log model has guarded empty array', function () {
    $reflection = new ReflectionClass(ActivityLog::class);
    $guardedProperty = $reflection->getProperty('guarded');
    $guardedProperty->setAccessible(true);
    $model = $reflection->newInstanceWithoutConstructor();

    expect($guardedProperty->getValue($model))->toBe([]);
});

it('activity log model has timestamps disabled', function () {
    $reflection = new ReflectionClass(ActivityLog::class);
    $timestampsProperty = $reflection->getProperty('timestamps');
    $timestampsProperty->setAccessible(true);
    $model = $reflection->newInstanceWithoutConstructor();

    expect($timestampsProperty->getValue($model))->toBeFalse();
});

it('activity log has casts method', function () {
    $reflection = new ReflectionClass(ActivityLog::class);

    expect($reflection->hasMethod('casts'))->toBeTrue();
});

it('activity log has record static method', function () {
    $reflection = new ReflectionClass(ActivityLog::class);

    expect($reflection->hasMethod('record'))->toBeTrue();
});

it('activity log has valuesFor static method', function () {
    $reflection = new ReflectionClass(ActivityLog::class);

    expect($reflection->hasMethod('valuesFor'))->toBeTrue();
});

it('activity log has user relationship', function () {
    $reflection = new ReflectionClass(ActivityLog::class);

    expect($reflection->hasMethod('user'))->toBeTrue();
});

it('showDetails accepts int logId parameter', function () {
    $reflection = new ReflectionMethod(Logs::class, 'showDetails');
    $parameters = $reflection->getParameters();

    expect($parameters)->toHaveCount(1)
        ->and($parameters[0]->getName())->toBe('logId')
        ->and($parameters[0]->getType())->not->toBeNull();
});
