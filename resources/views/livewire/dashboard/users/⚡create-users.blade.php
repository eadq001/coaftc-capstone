<?php

use App\Enums\UserRoles;
use App\Livewire\Dashboard;
use App\Mail\UserEmailVerification;
use App\Models\UnverifiedUser;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Str;

new #[Layout('layouts::dashboard', ['title' => 'Users'])]
class extends Component {

    #[Validate('required')]
    public string $username = '';

    #[Validate('required|email|unique:users,email|unique:unverified_users,email')]
    public string $email = '';

    #[Validate('required')]
    public string $user_role = '';

    #[Validate('required|min:8')]
    public string $password = '';

    #[Validate('required|min:8|same:password')]
    public string $confirmPassword = '';

    #[Validate('required')]
    public string $verification_token = '';

    public function updatedPassword(): void
    {
        $this->validateOnly('confirmPassword');
    }

    public function cancel(): void
    {
        $this->reset();
    }

    public function register(): void
    {
        $this->verification_token = Str::random(64);
        $this->validate();
        $this->password = Hash::make($this->password);
        UnverifiedUser::create($this->only('username', 'password', 'user_role', 'email', 'verification_token'));
        $this->dispatch('add-user-successful');

        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(10), ['token' => $this->verification_token]);
        Mail::to($this->email)->send(New UserEmailVerification($url));
        $this->reset();
    }


};
?>

<div class="relative" x-data="{showUserForm:false}">
    <div class="mb-6 flex items-center justify-between">
        <div>
        <flux:heading size="xl" level="1">Users</flux:heading>
        <flux:text class="mt-1 text-zinc-600">Add new users to the system</flux:text>
        </div>
        <div class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                <flux:button icon="plus" variant="primary" @click="showUserForm=true">
                    Add User
                </flux:button>
            </div>
        </div>
    </div>

    <flux:card class="border border-zinc-300 rounded-lg shadow-sm bg-white overflow-hidden">
        <table class="min-w-full divide-y divide-zinc-200">
            <thead class="">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">
                    Username
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">
                    Email
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Role</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-zinc-200">
            @forelse(App\Models\User::all() as $user)
                <tr class="hover:bg-zinc-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-zinc-900">{{ $user->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-zinc-600">{{ $user->email }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-zinc-100 text-zinc-800">{{ $user->user_role->getUserRole() }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-zinc-500">No users found</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </flux:card>

    <div class="w-full fixed inset-0 z-50 flex items-center justify-center bg-green-300/50 backdrop-blur-xs"
         x-show="showUserForm" x-transition x-cloak>
        <form wire:submit="register">
            <div class="space-y-3 text-sm relative bg-white p-4 w-2xl rounded-lg">
                <div class="absolute top-2 right-2 " title="exit this form">
                    <flux:icon.x-mark class="w-5 h-5 hover:rotate-180 transition-all" wire:click="cancel"
                                      @click="showUserForm=false"/>
                </div>
                <p class="text-center">Add User</p>

                <flux:field>
                    <flux:label class="mb-0.5!">Username</flux:label>
                    <flux:input type="text" wire:model.live.debounce.1000ms="username" placeholder="Username"/>
                    <flux:error name="username"/>
                </flux:field>

                <flux:field>
                    <flux:label class="mb-0.5!">Email</flux:label>
                    <flux:input type="email" wire:model.live.debounce.1000ms="email"
                                placeholder="Email"/>
                    <flux:error name="email"/>
                </flux:field>

                <flux:field>
                    <flux:label class="mb-0.5!">User Role</flux:label>
                    <flux:select wire:model="user_role" class="mb-0.5!"
                                 placeholder="Choose a user role">
                        @foreach(UserRoles::cases() as $userRole)
                            <flux:select.option> {{ $userRole->value}}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:field>

                <flux:field>
                    <flux:label class="mb-0.5!">Password</flux:label>
                    <flux:input type="password" wire:model.live.debounce.1000ms="password" placeholder="Password"/>
                    <flux:error name="password"/>
                </flux:field>

                <flux:field>
                    <flux:label class="mb-0.5!">Confirm Password</flux:label>
                    <flux:input type="password" wire:model.live.debounce.1000ms="confirmPassword"
                                placeholder="Confirm Password"/>
                    <flux:error name="confirmPassword"/>
                </flux:field>

                <div class="flex justify-between">
                    <button class="bg-green-300 w-24 px-3 py-1 rounded-lg cursor-pointer hover:bg-green-400 transition-all"
                            type="submit">Register
                    </button>

                    <x-success-message success-message="User added" event="add-user-successful"/>
                </div>
            </div>
        </form>
    </div>
</div>