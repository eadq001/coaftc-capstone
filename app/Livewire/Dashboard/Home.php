<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class Home extends Component
{
    public $username;

    public function mount()
    {
        $this->username = auth()->user()->name;
    }

    public function render()
    {
        return view('livewire.dashboard.home')->layout('layouts.dashboard');
    }
}
