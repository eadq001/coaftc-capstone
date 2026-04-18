<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;
use View;

class Register extends Component
{
    #[Validate('required')]
    public string $name ='';

    #[Validate('required|email|unique:users,email')]
    public string $email ='';

    #[Validate('required|min:8')]
    public string $password ='';

    public function save(): void
    {
        User::create($this->validate());

        $this->redirect('/login', true);
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.auth.register');
    }
}
