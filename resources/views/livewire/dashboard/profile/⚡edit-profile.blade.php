<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts::dashboard', ['title' => 'Edit Profile'])]
class extends Component
{
    #[Validate]
    public string $username = '';

    public string $email = '';

    #[Validate('nullable|min:8')]
    public string $password = '';

    #[Validate('nullable|same:password')]
    public string $confirmPassword = '';

    public function mount(): void
    {
        $user = auth()->user();

        $this->username = $user->name;
        $this->email = $user->email;
    }

    protected function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'name')->ignore(auth()->id()),
            ],
            'password' => ['nullable', 'min:8'],
            'confirmPassword' => ['nullable', 'same:password'],
        ];
    }

    public function updatedPassword(): void
    {
        $this->validateOnly('confirmPassword');
    }

    public function save(): void
    {
        $validated = $this->validate();

        $attributes = [
            'name' => $validated['username'],
        ];

        if ($validated['password'] !== '') {
            $attributes['password'] = Hash::make($validated['password']);
        }

        auth()->user()->update($attributes);

        $this->password = '';
        $this->confirmPassword = '';

        $this->dispatch('profile-updated');
    }
};

?>

<div class="space-y-6">
    <div class="mb-6">
        <flux:heading size="xl" level="1">Edit Profile</flux:heading>
        <flux:text class="mt-1 text-zinc-600">Update your account details and password.</flux:text>
    </div>

    <flux:card class="mx-auto w-full max-w-2xl rounded-lg border border-zinc-300 bg-white p-6 shadow-sm">
        <form wire:submit="save" class="space-y-5">
            <flux:field>
                <flux:label class="mb-0.5!">Username</flux:label>
                <flux:input
                    type="text"
                    wire:model.live.debounce.500ms="username"
                    placeholder="Username"
                />
                <flux:error name="username" />
            </flux:field>

            <flux:field>
                <flux:label class="mb-0.5!">Email</flux:label>
                <flux:input
                    type="email"
                    wire:model="email"
                    readonly
                    variant="filled"
                />
            </flux:field>

            <flux:field>
                <flux:label class="mb-0.5!">Password</flux:label>
                <flux:input
                    type="password"
                    wire:model.live.debounce.500ms="password"
                    placeholder="Leave blank to keep your current password"
                />
                <flux:error name="password" />
            </flux:field>

            <flux:field>
                <flux:label class="mb-0.5!">Confirm Password</flux:label>
                <flux:input
                    type="password"
                    wire:model.live.debounce.500ms="confirmPassword"
                    placeholder="Confirm your new password"
                />
                <flux:error name="confirmPassword" />
            </flux:field>

            <div class="flex items-center justify-between gap-4">
                <x-success-message success-message="Profile updated" event="profile-updated" />

                <flux:button type="submit" variant="primary">
                    Save Changes
                </flux:button>
            </div>
        </form>
    </flux:card>
</div>
