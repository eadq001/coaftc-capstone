<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate('string|required|email')]
    public string $email = '';

    #[Validate('string|required|min:8')]
    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        $user = $this->validate();

        if (auth()->attempt($user, $this->remember)) {
            request()->session()->regenerate();

            return redirect()->intended('/dashboard/home');
        }

        $this->addError('loginFailed', 'The records you entered do not on our records.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
