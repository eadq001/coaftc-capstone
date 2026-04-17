<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
class Dashboard extends Component
{
    public bool $sidebarCollapsed = false;

    public $username;

    public function mount()
    {
        $this->username = auth()->user()->name;
    }
}
