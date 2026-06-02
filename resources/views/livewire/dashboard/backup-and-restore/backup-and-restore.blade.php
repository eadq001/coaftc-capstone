<div class="space-y-6" x-on:restore-success.window="$flux.modal('restore-success').show()">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" level="1">Backup and Restore</flux:heading>
            <flux:text class="mt-1 text-zinc-600 dark:text-zinc-300">
                Manage database backup files and recovery activity.
            </flux:text>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:grid-cols-2">
        <flux:card
                class="rounded-lg border border-zinc-300 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-1">
                    <flux:heading size="lg" level="2">Create Backup</flux:heading>
                    <flux:text class="text-zinc-600 dark:text-zinc-300">
                        Save a copy of the current database state.
                    </flux:text>
                </div>

                <flux:button type="button" variant="primary" icon="arrow-down-tray"
                             class="sm:w-auto data-loading:pointer-events-none data-loading:opacity-70"
                             wire:click="backupDb">
                    Backup
                </flux:button>
            </div>
        </flux:card>

        <flux:card
                class="rounded-lg border border-zinc-300 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-1">
                    <flux:heading size="lg" level="2">Restore Backup</flux:heading>
                    <flux:text class="text-zinc-600 dark:text-zinc-300">
                        Recover the system from a saved backup file.
                    </flux:text>
                </div>

                <flux:modal.trigger name="confirm-restore">
                    <flux:button type="button" variant="filled" icon="arrow-path" class="sm:w-auto">
                        Restore
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </flux:card>
    </div>



        <flux:modal name="confirm-restore" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Restore latest backup?</flux:heading>
                    <flux:text class="mt-2 text-zinc-600 dark:text-zinc-300">
                        This will restore the database from the last backup file.
                    </flux:text>
                </div>

                <div class="flex gap-2">
                    <flux:spacer/>

                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>

                    <flux:modal.close>
                        <flux:button type="button" variant="primary" icon="arrow-path" wire:click="restoreDb"
                                     wire:loading.attr="disabled" wire:target="restoreDb">
                            Yes, restore
                        </flux:button>
                    </flux:modal.close>
                </div>
            </div>
        </flux:modal>



    <flux:modal name="restore-success" class="min-w-[22rem]">
        <div class="space-y-6">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                    <flux:icon.check class="h-5 w-5"/>
                </div>

                <div>
                    <flux:heading size="lg">Database restored</flux:heading>
                    <flux:text class="mt-2 text-zinc-600 dark:text-zinc-300">
                        The database was restored successfully from the last backup.
                    </flux:text>
                </div>
            </div>

            <div class="flex justify-end">
                <flux:modal.close>
                    <flux:button variant="primary">Done</flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>

    <flux:card
            class="overflow-hidden rounded-lg border border-zinc-300 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
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

        <div wire:loading.flex wire:target="backupDb"
             class="fixed inset-0 z-50 items-center justify-center bg-zinc-950/60 p-4 backdrop-blur-sm">
            <div class="w-full max-w-sm rounded-lg border border-zinc-200 bg-white p-6 text-center shadow-xl dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                    <flux:icon.arrow-path class="h-6 w-6 animate-spin"/>
                </div>

                <flux:heading size="lg" level="2">Backing up the database</flux:heading>
                <flux:text class="mt-2 text-zinc-600 dark:text-zinc-300">
                    Please wait while the backup file is being prepared.
                </flux:text>
            </div>
        </div>

        <div wire:loading.flex wire:target="restoreDb"
             class="fixed inset-0 z-50 items-center justify-center bg-zinc-950/60 p-4 backdrop-blur-sm">
            <div class="w-full max-w-sm rounded-lg border border-zinc-200 bg-white p-6 text-center shadow-xl dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                    <flux:icon.arrow-path class="h-6 w-6 animate-spin"/>
                </div>

                <flux:heading size="lg" level="2">Restoring the database</flux:heading>
                <flux:text class="mt-2 text-zinc-600 dark:text-zinc-300">
                    Please wait while Restoring the database.
                </flux:text>
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
                <flux:table.column>User</flux:table.column>
            </flux:table.columns>

            @forelse ($this->histories as $history)
                <flux:table.row wire:key="backup-restore-history-{{ $history->id }}">
                    <flux:table.cell>
                        <div class="flex items-center gap-2">
                            @if ($history->action === 'backup')
                                <flux:icon.arrow-down-tray class="h-4 w-4 text-green-600"/>
                            @else
                                <flux:icon.arrow-path class="h-4 w-4 text-blue-600"/>
                            @endif
                            <span class="font-small text-zinc-900 capitalize dark:text-zinc-100">{{ $history->action }}</span>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell>
                        <span class="font-small text-zinc-900 dark:text-zinc-100">{{ $history->file_name ?? '-' }}</span>
                    </flux:table.cell>

                    <flux:table.cell>
                        @php
                            $statusColor = match ($history->status) {
                                'completed' => 'green',
                                'failed' => 'red',
                                default => 'blue',
                            };
                        @endphp

                        <flux:badge :color="$statusColor"
                                    variant="subtle">{{ str($history->status)->headline() }}</flux:badge>
                    </flux:table.cell>

                    <flux:table.cell>
                        @if ($history->file_size)
                            <span class="text-zinc-700 dark:text-zinc-300">{{ Number::fileSize($history->file_size) }}</span>
                        @else
                            <span class="text-zinc-500 dark:text-zinc-400">-</span>
                        @endif
                    </flux:table.cell>

                    <flux:table.cell>
                        <span class="text-zinc-700 dark:text-zinc-300">{{ $history->started_at?->format('M d, Y h:i A') ?? '-' }}</span>
                    </flux:table.cell>

                    <flux:table.cell>
                        @if ($history->completed_at)
                            <span class="text-zinc-700 dark:text-zinc-300">{{ $history->completed_at->format('M d, Y h:i A') }}</span>
                        @else
                            <span class="text-zinc-500 dark:text-zinc-400">Pending</span>
                        @endif
                    </flux:table.cell>

                    <flux:table.cell>
                        <span class="text-zinc-700 dark:text-zinc-300">{{ $history->user?->name ?? '-' }}</span>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="7">
                        <div class="py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            No backup or restore history yet.
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table>
        <div class="mt-2">
            {{ $this->histories->links(data: ['scrollTo' => false]) }}
        </div>
    </flux:card>
</div>
