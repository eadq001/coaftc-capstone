<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Dashboard extends Component
{
    public bool $sidebarCollapsed = false;

    #[On('sidebar-toggle')]
    public function toggleSidebar()
    {
        $this->sidebarCollapsed = ! $this->sidebarCollapsed;
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.appDashboard');
    }
}
