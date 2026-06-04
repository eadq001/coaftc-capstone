<?php

namespace App\Livewire\Dashboard\Logs;

use App\Livewire\Dashboard;
use App\Models\ActivityLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class Logs extends Dashboard
{
    use WithPagination;

    public bool $showLogDetails = false;

    /**
     * @var array{
     *     action?: string,
     *     model?: string,
     *     user?: string,
     *     date_time?: string,
     *     old_values?: array<string, mixed>,
     *     new_values?: array<string, mixed>
     * }
     */
    public array $selectedLog = [];

    #[Computed]
    public function logs(): LengthAwarePaginator
    {
        return ActivityLog::with('user')
            ->latest('date_time')
            ->paginate(10, pageName: 'activity-logs');
    }

    public function showDetails(int $logId): void
    {
        $log = ActivityLog::with('user')->findOrFail($logId);

        $this->selectedLog = [
            'action' => $log->action,
            'model' => $log->model,
            'user' => $log->user?->name ?? 'Unknown user',
            'date_time' => $log->date_time->format('F j, Y h:i:s A'),
            'old_values' => $log->old_values ?? [],
            'new_values' => $log->new_values ?? [],
        ];

        $this->showLogDetails = true;
    }

    public function render()
    {
        return view('livewire.dashboard.logs.logs');
    }
}
