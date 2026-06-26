<?php

use App\Enums\UserRoles;
use App\Models\ActivityLog;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

function actingAsAdminForEmployees(): User
{
    $user = User::factory()->create(['user_role' => UserRoles::ADMIN]);

    actingAs($user);

    return $user;
}

it('allows admin to access employees page', function () {
    actingAsAdminForEmployees();

    $this->get(route('dashboard.employees'))->assertOk();
});

it('prevents cashier from accessing employees page', function () {
    $cashier = User::factory()->create(['user_role' => UserRoles::CASHIER]);
    actingAs($cashier);

    $this->get(route('dashboard.employees'))->assertNotFound();
});

it('prevents inventory clerk from accessing employees page', function () {
    $inventory = User::factory()->create(['user_role' => UserRoles::INVENTORY]);
    actingAs($inventory);

    $this->get(route('dashboard.employees'))->assertNotFound();
});

it('displays employees sorted by first name', function () {
    actingAsAdminForEmployees();

    Employee::create(['first_name' => 'Zara', 'last_name' => 'Smith', 'position' => 'Clerk']);
    Employee::create(['first_name' => 'Alice', 'last_name' => 'Jones', 'position' => 'Manager']);

    $component = Livewire::test('dashboard.employees');

    $employees = $component->get('employees');

    expect($employees[0]['first_name'])->toBe('Alice')
        ->and($employees[1]['first_name'])->toBe('Zara');
});

it('adds a new employee with valid data', function () {
    actingAsAdminForEmployees();

    Livewire::test('dashboard.employees')
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('middle_name', 'Michael')
        ->set('position', 'Field Worker')
        ->call('addEmployee')
        ->assertHasNoErrors()
        ->assertSet('successMessage', 'Employee added successfully.');

    expect(Employee::where('first_name', 'John')->where('last_name', 'Doe')->exists())->toBeTrue();
});

it('validates first name is required', function () {
    actingAsAdminForEmployees();

    Livewire::test('dashboard.employees')
        ->set('first_name', '')
        ->set('last_name', 'Doe')
        ->set('position', 'Worker')
        ->call('addEmployee')
        ->assertHasErrors(['first_name' => 'required']);
});

it('validates last name is required', function () {
    actingAsAdminForEmployees();

    Livewire::test('dashboard.employees')
        ->set('first_name', 'John')
        ->set('last_name', '')
        ->set('position', 'Worker')
        ->call('addEmployee')
        ->assertHasErrors(['last_name' => 'required']);
});

it('validates position is required', function () {
    actingAsAdminForEmployees();

    Livewire::test('dashboard.employees')
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('position', '')
        ->call('addEmployee')
        ->assertHasErrors(['position' => 'required']);
});

it('logs employee creation in activity log', function () {
    actingAsAdminForEmployees();

    Livewire::test('dashboard.employees')
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('position', 'Worker')
        ->call('addEmployee');

    expect(ActivityLog::where('model', 'Employee')->where('action', 'create')->exists())->toBeTrue();
});

it('selects an employee for editing', function () {
    actingAsAdminForEmployees();

    $employee = Employee::create(['first_name' => 'John', 'last_name' => 'Doe', 'position' => 'Worker']);

    Livewire::test('dashboard.employees')
        ->call('editEmployee', $employee->id)
        ->assertSet('first_name', 'John')
        ->assertSet('last_name', 'Doe')
        ->assertSet('position', 'Worker');
});

it('updates an employee', function () {
    actingAsAdminForEmployees();

    $employee = Employee::create(['first_name' => 'John', 'last_name' => 'Doe', 'position' => 'Worker']);

    Livewire::test('dashboard.employees')
        ->call('editEmployee', $employee->id)
        ->set('first_name', 'Jane')
        ->call('updateEmployee')
        ->assertHasNoErrors()
        ->assertSet('editSuccessMessage', 'Employee updated successfully.');

    $employee->refresh();

    expect($employee->first_name)->toBe('Jane');
});

it('resets the employee form', function () {
    actingAsAdminForEmployees();

    Livewire::test('dashboard.employees')
        ->set('first_name', 'John')
        ->set('last_name', 'Doe')
        ->set('position', 'Worker')
        ->call('resetForm')
        ->assertSet('first_name', '')
        ->assertSet('last_name', '')
        ->assertSet('position', '');
});
