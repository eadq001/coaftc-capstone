<?php

namespace App\Livewire;

use Livewire\Component;

class Login extends Component
{
    protected $layout = null;

    public function render()
    {
        return view('livewire.auth.login');
    }
}
