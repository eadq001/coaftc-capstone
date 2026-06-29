<?php

namespace App\Console\Commands;

use App\Enums\UserRoles;
use App\Models\BackupRestoreHistory;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class BackupToDrive extends Command
{
    protected $signature = 'backup:drive';

    protected $description = 'Create a database backup and upload it to Dropbox';

    public function handle(): int
    {
        $startedAt = now();
        $fileName = 'coaftc-backup-'.$startedAt->format('Y-m-d-His').'.zip';
        $backupDirectory = config('backup.backup.name', 'coaftc-backup');
        $filePath = "{$backupDirectory}/{$fileName}";
        $userId = $this->backupUserId();

        $this->info('Creating database backup...');

        $exitCode = Artisan::call('backup:run', [
            '--only-db' => true,
            '--filename' => $fileName,
            '--timeout' => 60,
        ]);

        if ($exitCode !== 0 || ! Storage::disk('local')->exists($filePath)) {
            $this->error('Backup failed.');

            BackupRestoreHistory::create([
                'user_id' => $userId,
                'action' => 'backup',
                'status' => 'failed',
                'file_name' => $fileName,
                'file_path' => $filePath,
                'started_at' => $startedAt,
                'completed_at' => now(),
            ]);

            return self::FAILURE;
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

        $this->info('Backup created: '.$fileName);

        if ($this->uploadToDropbox($filePath, $fileName)) {
            $this->info('Uploaded to Dropbox successfully.');
        } else {
            $this->warn('Dropbox upload skipped or failed (token may not be configured).');
        }

        return self::SUCCESS;
    }

    private function backupUserId(): ?int
    {
        return User::where('user_role', UserRoles::ADMIN)
            ->orderBy('id')
            ->value('id');
    }

    private function uploadToDropbox(string $localPath, string $fileName): bool
    {
        try {
            if (! config('filesystems.disks.dropbox.token')) {
                return false;
            }

            $localStream = Storage::disk('local')->readStream($localPath);

            Storage::disk('dropbox')->writeStream('backups/'.$fileName, $localStream);

            if (is_resource($localStream)) {
                fclose($localStream);
            }

            return true;
        } catch (Throwable $e) {
            Log::error('Dropbox upload failed: '.$e->getMessage());

            return false;
        }
    }
}
