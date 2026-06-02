<?php

namespace App\Livewire\Dashboard\BackupAndRestore;

use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupAndRestore extends Dashboard
{
    public function backupDb()
    {
        try {
        Artisan::call('backup:run --only-db --filename=my-backup.zip');

        return Storage::disk('local')->download('coaftc-backup/my-backup.zip');
        }
        catch (ProcessFailedException $exception) {
            $this->dispatch('backup-error');
        }
    }
    public function render()
    {
        return view('livewire.dashboard.backup-and-restore.backup-and-restore');
    }
}
