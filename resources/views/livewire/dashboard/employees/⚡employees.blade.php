<?php

use App\Livewire\Dashboard;
use App\Models\Employee;

new class extends Dashboard {
    public string $first_name = '';

    public string $last_name = '';

    public string $middle_name = '';

    public string $address = '';

    public string $position = '';

    /**
     * @var array<int, array{
     *     first_name: string,
     *     last_name: string,
     *     middle_name: string,
     *     address: string,
     *     position: string,
     *     created_at: string
     * }>
     */
    public array $employees = [];

    public string $successMessage = '';

    public function mount()
    {
        $this->loadEmployees();
    }

    public function loadEmployees(): void
    {
        $this->employees = Employee::query()
            ->latest()
            ->get()
            ->map(fn (Employee $employee): array => [
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'middle_name' => $employee->middle_name ?? '',
                'address' => $employee->address,
                'position' => $employee->position,
                'created_at' => $employee->created_at?->format('Y-m-d H:i:s') ?? '',
            ])
            ->all();
    }

    public function addEmployee(): void
    {
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
        ]);

        Employee::create($validated);

        $this->loadEmployees();
        $this->successMessage = 'Employee added successfully.';

        $this->reset([
            'first_name',
            'last_name',
            'middle_name',
            'address',
            'position',
        ]);

        $this->dispatch('employee-added');
    }
};
?>

<div class="relative space-y-6" x-data="{ showEmployeeForm: false }">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" level="1">Employees</flux:heading>
            <flux:text class="mt-1 text-zinc-600">Add employee details from this dashboard page.</flux:text>
        </div>

        <flux:button icon="plus" variant="primary" @click="showEmployeeForm = true">
            Add Employee
        </flux:button>
    </div>

    <flux:card class="overflow-hidden border border-zinc-300 bg-white shadow-sm">
        <div class="border-b border-zinc-200 px-6 py-4">
            <flux:heading size="lg">Employee List</flux:heading>
            <flux:text class="mt-1 text-sm text-zinc-500">Employees saved here are loaded from the database.</flux:text>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200">
                <thead class="bg-zinc-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500">First Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500">Last Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500">Middle Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500">Address</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-500">Position</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 bg-white">
                @forelse ($employees as $index => $employee)
                    <tr wire:key="employee-{{ $employee['created_at'] }}-{{ $index }}" class="hover:bg-zinc-50">
                        <td class="px-6 py-4 text-sm text-zinc-900">{{ $employee['first_name'] }}</td>
                        <td class="px-6 py-4 text-sm text-zinc-900">{{ $employee['last_name'] }}</td>
                        <td class="px-6 py-4 text-sm text-zinc-600">{{ $employee['middle_name'] ?: 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-zinc-600">{{ $employee['address'] }}</td>
                        <td class="px-6 py-4 text-sm text-zinc-600">{{ $employee['position'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-zinc-500">
                            No employees added yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </flux:card>

    <div
        class="fixed inset-0 z-50 flex items-center justify-center bg-green-300/50 p-4 backdrop-blur-xs"
        x-cloak
        x-show="showEmployeeForm"
        x-transition
    >
        <div class="w-full max-w-3xl rounded-lg bg-white p-6 shadow-xl">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <flux:heading size="lg">Add Employee</flux:heading>
                    <flux:text class="mt-1 text-sm text-zinc-500">Fill out the employee information below.</flux:text>
                </div>

                <button
                    type="button"
                    class="rounded-full p-1 text-zinc-500 transition hover:bg-zinc-100 hover:text-zinc-900"
                    @click="showEmployeeForm = false"
                >
                    <flux:icon.x-mark class="h-5 w-5" />
                </button>
            </div>

            <form wire:submit="addEmployee" class="space-y-5">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <flux:field>
                        <flux:label>First Name</flux:label>
                        <flux:input type="text" wire:model.live="first_name" placeholder="First name" />
                        <flux:error name="first_name" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Last Name</flux:label>
                        <flux:input type="text" wire:model.live="last_name" placeholder="Last name" />
                        <flux:error name="last_name" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Middle Name</flux:label>
                        <flux:input type="text" wire:model.live="middle_name" placeholder="Middle name" />
                        <flux:error name="middle_name" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Position</flux:label>
                        <flux:input type="text" wire:model.live="position" placeholder="Position" />
                        <flux:error name="position" />
                    </flux:field>
                </div>

                <flux:field>
                    <flux:label>Address</flux:label>
                    <flux:textarea wire:model.live="address" rows="3" placeholder="Employee address" />
                    <flux:error name="address" />
                </flux:field>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between items-center">
                    <div>

                    <div class="flex items-center justify-end gap-3">
                        <flux:button type="button" variant="ghost" @click="showEmployeeForm = false">
                            Cancel
                        </flux:button>

                        <flux:button type="submit" variant="primary">
                            Add Employee
                        </flux:button>
                    </div>
                    </div>

                    <x-success-message success-message="Employee Added" event="employee-added"/>

                </div>
            </form>
        </div>
    </div>
</div>
