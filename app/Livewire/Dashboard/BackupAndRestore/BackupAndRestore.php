<?php

namespace App\Livewire\Dashboard\BackupAndRestore;

use App\Livewire\Dashboard;
use App\Models\BackupRestoreHistory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class BackupAndRestore extends Dashboard
{
    use WithPagination;

    public function backupDb(): ?StreamedResponse
    {
        $startedAt = now();
        $fileName = 'coaftc-backup-'.$startedAt->format('Y-m-d-His').'.zip';
        $backupDirectory = config('backup.backup.name', 'coaftc-backup');
        $filePath = "{$backupDirectory}/{$fileName}";

        try {
            $exitCode = Artisan::call('backup:run', [
                '--only-db' => true,
                '--filename' => $fileName,
            ]);

            if ($exitCode !== 0 || ! Storage::disk('local')->exists($filePath)) {
                $this->dispatch('backup-error');

                return null;
            }

            BackupRestoreHistory::create([
                'user_id' => auth()->id(),
                'action' => 'backup',
                'status' => 'completed',
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => Storage::disk('local')->size($filePath),
                'started_at' => $startedAt,
                'completed_at' => now(),
            ]);

            return Storage::disk('local')->download($filePath, $fileName);

        } catch (Throwable) {
            $this->dispatch('backup-error');

            return null;
        }
    }

    #[Computed]
    public function histories()
    {
        return BackupRestoreHistory::query()->latest()->paginate(15, pageName: 'histories');
    }

    public function render(): View
    {
        return view('livewire.dashboard.backup-and-restore.backup-and-restore');
    }
}
