<?php

namespace App\Livewire;

use App\Enums\UserRoles;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts.dashboard')]
class Dashboard extends Component
{
    public bool $sidebarCollapsed = false;

    public $username;

    #[Locked]
    public string $currentUserRole = '';

    public array $userRoles = [];

    public bool $rolesAllowed = false;

    public function mount()
    {
        $this->username = auth()->user()->name;
    }


}
