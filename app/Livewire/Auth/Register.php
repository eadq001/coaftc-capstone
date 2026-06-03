<?php

namespace App\Livewire\Auth;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Register extends Component
{
    #[Validate('required')]
    public string $name = '';

    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    #[Validate('required|min:8')]
    public string $password = '';

    public function save(): void
    {
        $user = User::create($this->validate());

        ActivityLog::record(
            action: 'create',
            model: 'User',
            newValues: ActivityLog::valuesFor($user),
            userId: $user->id,
        );

        $this->redirect('/login', true);
    }

    public function render(): \Illuminate\Contracts\View\View|Factory|View
    {
        return view('livewire.auth.register');
    }
}
