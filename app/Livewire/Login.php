<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
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
            $userId = auth()->id();
            $currentSessionId = session()->getId();

            session()->regenerate();

            DB::table('sessions')
                ->where('user_id', $userId)
                ->where('id', '!=', $currentSessionId)
                ->delete();

            return redirect()->intended('/dashboard');
        }

        $this->addError('loginFailed', 'Invalid email and password.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
