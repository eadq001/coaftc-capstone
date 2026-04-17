<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Register extends Component
{
    #[Validate('required')]
    public string $name ='';

    #[Validate('required|email|unique:users,email')]
    public string $email ='';

    #[Validate('required|min:8')]
    public string $password ='';

    public function save()
    {
        User::create($this->validate());

        $this->redirect('/login', true);
    }
    public function render()
    {
        return view('livewire.auth.register');
    }
}
