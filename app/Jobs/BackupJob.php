<?php

namespace App\Jobs;

use App\Enums\UserRoles;
use App\Models\BackupRestoreHistory;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class BackupJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function handle(): void
    {
        $startedAt = now();
        $fileName = 'coaftc-backup-'.$startedAt->format('Y-m-d-His').'.zip';
        $backupDirectory = config('backup.backup.name', 'coaftc-backup');
        $filePath = "{$backupDirectory}/{$fileName}";
        $userId = User::where('user_role', UserRoles::ADMIN)
            ->orderBy('id')
            ->value('id');

        $exitCode = Artisan::call('backup:run', [
            '--only-db' => true,
            '--filename' => $fileName,
            '--timeout' => 60,
        ]);

        if ($exitCode !== 0 || ! Storage::disk('local')->exists($filePath)) {
            BackupRestoreHistory::create([
                'user_id' => $userId,
                'action' => 'backup',
                'status' => 'failed',
                'file_name' => $fileName,
                'file_path' => $filePath,
                'started_at' => $startedAt,
                'completed_at' => now(),
            ]);

            return;
        }

        Artisan::call('backup:clean');

        $fileSize = Storage::disk('local')->size($filePath);

        BackupRestoreHistory::create([
            'user_id' => $userId,
            'action' => 'backup',
            'status' => 'completed',
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'started_at' => $startedAt,
            'completed_at' => now(),
        ]);

        try {
            if (config('filesystems.disks.dropbox.token')) {
                $localStream = Storage::disk('local')->readStream($filePath);
                Storage::disk('dropbox')->writeStream('backups/'.$fileName, $localStream);

                if (is_resource($localStream)) {
                    fclose($localStream);
                }
            }
        } catch (Throwable $e) {
            Log::error('Dropbox upload failed: '.$e->getMessage());
        }
    }
}
