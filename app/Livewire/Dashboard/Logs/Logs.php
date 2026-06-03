<?php

namespace App\Livewire\Dashboard\Logs;

use App\Livewire\Dashboard;
use App\Models\ActivityLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;

class Logs extends Dashboard
{
    #[Computed]
    public function logs(): LengthAwarePaginator
    {
       return ActivityLog::with('user')
           ->paginate(15, pageName: 'activity-logs');
    }
    public function render()
    {
        return view('livewire.dashboard.logs.logs');
    }
}
