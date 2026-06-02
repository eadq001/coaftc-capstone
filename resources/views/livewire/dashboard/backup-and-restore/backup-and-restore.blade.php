<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" level="1">Backup and Restore</flux:heading>
            <flux:text class="mt-1 text-zinc-600 dark:text-zinc-300">
                Manage database backup files and recovery activity.
            </flux:text>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:grid-cols-2">
        <flux:card class="rounded-lg border border-zinc-300 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-1">
                    <flux:heading size="lg" level="2">Create Backup</flux:heading>
                    <flux:text class="text-zinc-600 dark:text-zinc-300">
                        Save a copy of the current database state.
                    </flux:text>
                </div>

                <flux:button type="button" variant="primary" icon="arrow-down-tray" class="sm:w-auto" wire:click="backupDb">
                    Backup
                </flux:button>
            </div>
        </flux:card>

        <flux:card class="rounded-lg border border-zinc-300 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-1">
                    <flux:heading size="lg" level="2">Restore Backup</flux:heading>
                    <flux:text class="text-zinc-600 dark:text-zinc-300">
                        Recover the system from a saved backup file.
                    </flux:text>
                </div>

                <flux:button type="button" variant="filled" icon="arrow-path" class="sm:w-auto">
                    Restore
                </flux:button>
            </div>
        </flux:card>
    </div>

    <flux:card class="overflow-hidden rounded-lg border border-zinc-300 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div class="border-b border-zinc-200 p-6 dark:border-zinc-700">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <flux:heading size="lg" level="2">Backup and Restore History</flux:heading>
                    <flux:text class="mt-1 text-zinc-600 dark:text-zinc-300">
                        Recent backup and recovery records.
                    </flux:text>
                </div>
            </div>
        </div>

        <flux:table class="border! border-gray-200! px-2 transition-opacity dark:border-zinc-700!">
            <flux:table.columns>
                <flux:table.column>Action</flux:table.column>
                <flux:table.column>File Name</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>File Size</flux:table.column>
                <flux:table.column>Started</flux:table.column>
                <flux:table.column>Completed</flux:table.column>
                <flux:table.column>Remarks</flux:table.column>
            </flux:table.columns>

            <flux:table.row>
                <flux:table.cell>
                    <div class="flex items-center gap-2">
                        <flux:icon.arrow-down-tray class="h-4 w-4 text-green-600" />
                        <span class="font-small text-zinc-900 dark:text-zinc-100">Backup</span>
                    </div>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="font-small text-zinc-900 dark:text-zinc-100">coaftc-backup-2026-06-02.sql</span>
                </flux:table.cell>

                <flux:table.cell>
                    <flux:badge color="green" variant="subtle">Completed</flux:badge>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-zinc-700 dark:text-zinc-300">18.4 MB</span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-zinc-700 dark:text-zinc-300">Jun 02, 2026 05:00 PM</span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-zinc-700 dark:text-zinc-300">Jun 02, 2026 05:02 PM</span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-zinc-700 dark:text-zinc-300">Manual backup created.</span>
                </flux:table.cell>
            </flux:table.row>

            <flux:table.row>
                <flux:table.cell>
                    <div class="flex items-center gap-2">
                        <flux:icon.arrow-path class="h-4 w-4 text-blue-600" />
                        <span class="font-small text-zinc-900 dark:text-zinc-100">Restore</span>
                    </div>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="font-small text-zinc-900 dark:text-zinc-100">coaftc-backup-2026-05-31.sql</span>
                </flux:table.cell>

                <flux:table.cell>
                    <flux:badge color="blue" variant="subtle">In Progress</flux:badge>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-zinc-700 dark:text-zinc-300">17.9 MB</span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-zinc-700 dark:text-zinc-300">Jun 02, 2026 04:42 PM</span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-zinc-500 dark:text-zinc-400">Pending</span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-zinc-700 dark:text-zinc-300">Restore verification running.</span>
                </flux:table.cell>
            </flux:table.row>

            <flux:table.row>
                <flux:table.cell>
                    <div class="flex items-center gap-2">
                        <flux:icon.arrow-down-tray class="h-4 w-4 text-green-600" />
                        <span class="font-small text-zinc-900 dark:text-zinc-100">Backup</span>
                    </div>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="font-small text-zinc-900 dark:text-zinc-100">coaftc-backup-2026-05-29.sql</span>
                </flux:table.cell>

                <flux:table.cell>
                    <flux:badge color="red" variant="subtle">Failed</flux:badge>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-zinc-500 dark:text-zinc-400">-</span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-zinc-700 dark:text-zinc-300">May 29, 2026 08:15 PM</span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-zinc-700 dark:text-zinc-300">May 29, 2026 08:16 PM</span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-zinc-700 dark:text-zinc-300">Storage location unavailable.</span>
                </flux:table.cell>
            </flux:table.row>
        </flux:table>
    </flux:card>
</div>
