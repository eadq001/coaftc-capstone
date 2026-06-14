<?php

namespace App\Livewire\Dashboard\BackupAndRestore;

use App\Livewire\Dashboard;
use App\Models\BackupRestoreHistory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
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
                '--timeout' => 60,
            ]);

            if ($exitCode !== 0 || ! Storage::disk('local')->exists($filePath)) {
                $this->dispatch('backup-error');

                return null;
            }

            Artisan::call('backup:clean');

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

    public function restoreDb(): void
    {
        $startedAt = now();

        try {
            $backupDirectory = config('backup.backup.name', 'coaftc-backup');
            $latestBackup = collect(Storage::disk('local')->files($backupDirectory))
                ->filter(fn ($file) => str_ends_with($file, '.zip'))
                ->sortByDesc(fn ($file) => Storage::disk('local')->lastModified($file))
                ->map(fn ($file) => [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => Storage::disk('local')->size($file),
                    'last_modified' => Storage::disk('local')->lastModified($file),
                ])
                ->first();

            if (! $latestBackup) {
                $this->dispatch('restore-error');

                return;
            }

            $exitCode = Artisan::call('backup:restore', [
                '--backup' => 'latest',
                '--no-interaction' => true,
            ]);

            if ($exitCode === 0) {
                BackupRestoreHistory::create([
                    'user_id' => auth()->id(),
                    'action' => 'restore',
                    'file_name' => $latestBackup['name'],
                    'file_size' => $latestBackup['size'],
                    'file_path' => $latestBackup['path'],
                    'status' => 'completed',
                    'started_at' => $startedAt,
                    'completed_at' => now(),
                ]);

                $this->dispatch('restore-success');
            } else {
                $this->dispatch('restore-error');
            }
        } catch (Throwable $e) {
            Log::error('Restore failed: '.$e->getMessage());
            $this->dispatch('restore-error');
        }
    }

    #[Computed]
    public function histories(): LengthAwarePaginator
    {
        return BackupRestoreHistory::query()
            ->with('user')
            ->latest()
            ->paginate(15, pageName: 'histories');
    }

    public function render(): View
    {
        return view('livewire.dashboard.backup-and-restore.backup-and-restore');
    }
}
