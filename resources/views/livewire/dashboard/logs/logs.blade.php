<div>


    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" level="1">Activity Logs</flux:heading>
            <flux:text class="mt-1 text-zinc-600 dark:text-zinc-300">
                Monitor user activities in the system.
            </flux:text>
        </div>
    </div>

    <flux:card
            class="overflow-hidden rounded-lg border border-zinc-300 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 mt-6">
        <div class="border-zinc-200 p-2! dark:border-zinc-700">
            <flux:table
                    class="border! border-gray-200! px-2 transition-opacity dark:border-zinc-700! bg-white rounded-lg">
                <flux:table.columns>
                    <flux:table.column>Action</flux:table.column>
                    <flux:table.column>Old Values</flux:table.column>
                    <flux:table.column>New Values</flux:table.column>
                    <flux:table.column>Model</flux:table.column>
                    <flux:table.column>Completed</flux:table.column>
                    <flux:table.column>User</flux:table.column>
                </flux:table.columns>

                @forelse($this->logs as $log)
                    <flux:table.row wire:key="{{ $log->id }}" class="text-zinc-700!">
                        <flux:table.cell>
                            {{ $log->action }}
                        </flux:table.cell>

                        <flux:table.cell>
                            @php
                                $oldValues = collect($log->old_values ?? [])
                                ->map(fn($key, $value) => $key . ':' . $value)
                                ->implode(',');

                                $changeValues = Str::limit($oldValues, 30);
                            @endphp

                            {{ $changeValues }}
                            {{ $log->old_values ?? '' }}
                        </flux:table.cell>

                        <flux:table.cell>
                            @php
                            $newValues = collect($log->new_values)
                            ->map(fn($key, $value) => $key . ':' . $value)
                            ->implode(',');

                            $changeValues = Str::limit($newValues, 30);
                            @endphp

                            {{ $changeValues }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $log->model }}
                        </flux:table.cell>

                        <flux:table.cell>
                        {{ $log->date_time->format('F j, Y h:i:s A') }}
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $log->user->name }}
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
        </div>
    </flux:card>
</div>