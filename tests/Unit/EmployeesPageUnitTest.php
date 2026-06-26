<?php

use App\Models\Employee;

it('has fillable first_name last_name middle_name and position', function () {
    $employee = new Employee;

    expect($employee->getFillable())->toContain('first_name')
        ->and($employee->getFillable())->toContain('last_name')
        ->and($employee->getFillable())->toContain('middle_name')
        ->and($employee->getFillable())->toContain('position');
});

it('does not have timestamps disabled', function () {
    $employee = new Employee;

    expect($employee->timestamps)->toBeTrue();
});

it('does not use soft deletes', function () {
    $reflection = new ReflectionClass(Employee::class);

    expect($reflection->getTraitNames())->not->toContain('Illuminate\Database\Eloquent\SoftDeletes');
});

it('has factory trait', function () {
    $reflection = new ReflectionClass(Employee::class);

    expect($reflection->getTraitNames())->toContain('Illuminate\Database\Eloquent\Factories\HasFactory');
});

it('has no relationships defined', function () {
    $reflection = new ReflectionClass(Employee::class);

    expect($reflection->hasMethod('category'))->toBeFalse()
        ->and($reflection->hasMethod('product'))->toBeFalse();
});

it('fillable array has exactly 5 fields', function () {
    $employee = new Employee;

    expect($employee->getFillable())->toHaveCount(5);
});
