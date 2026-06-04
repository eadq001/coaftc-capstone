<div>
    @php
        $formatLogValue = function (mixed $value): string {
            if (is_array($value)) {
                return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }

            if (is_bool($value)) {
                return $value ? 'true' : 'false';
            }

            if ($value === null || $value === '') {
                return 'None';
            }

            return (string) $value;
        };
    @endphp

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
                    <flux:table.row wire:key="{{ $log->id }}"
                                    class="cursor-pointer text-zinc-700! hover:bg-green-50 dark:hover:bg-zinc-800"
                                    wire:click="showDetails({{ $log->id }})"
                                    title="Click to view complete log details">
                        <flux:table.cell>
                            {{ $log->action }}
                        </flux:table.cell>

                        <flux:table.cell>
                            @php
                                $oldValues = collect($log->old_values ?? [])
                                    ->map(fn($value, $key) => $key . ': ' . $formatLogValue($value))
                                    ->implode(', ');

                                $changeValues = Str::limit($oldValues, 30);
                            @endphp

                            {{ $changeValues }}
                        </flux:table.cell>

                        <flux:table.cell>
                            @php
                            $newValues = collect($log->new_values)
                                ->map(fn($value, $key) => $key . ': ' . $formatLogValue($value))
                                ->implode(', ');

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
                            {{ $log->user?->name ?? 'Unknown user' }}
                        </flux:table.cell>


                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="7">
                            <div class="py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                No activity logs yet.
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table>
            <div class="mt-4">
            {{ $this->logs->links(data:['scrollTo' => false]) }}
            </div>
        </div>
    </flux:card>

    <flux:modal wire:model="showLogDetails" class="w-full max-w-4xl">
        <div class="space-y-5">
            <div>
                <flux:heading size="lg">Activity Log Details</flux:heading>
                <flux:text class="mt-1 text-zinc-600 dark:text-zinc-300">
                    Complete record for the selected activity.
                </flux:text>
            </div>

            <div class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                <div class="rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
                    <div class="text-xs uppercase text-zinc-500">Action</div>
                    <div class="mt-1 font-medium text-zinc-900 dark:text-zinc-100">{{ $selectedLog['action'] ?? '' }}</div>
                </div>
                <div class="rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
                    <div class="text-xs uppercase text-zinc-500">Model</div>
                    <div class="mt-1 font-medium text-zinc-900 dark:text-zinc-100">{{ $selectedLog['model'] ?? '' }}</div>
                </div>
                <div class="rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
                    <div class="text-xs uppercase text-zinc-500">User</div>
                    <div class="mt-1 font-medium text-zinc-900 dark:text-zinc-100">{{ $selectedLog['user'] ?? '' }}</div>
                </div>
                <div class="rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
                    <div class="text-xs uppercase text-zinc-500">Date and Time</div>
                    <div class="mt-1 font-medium text-zinc-900 dark:text-zinc-100">{{ $selectedLog['date_time'] ?? '' }}</div>
                </div>
            </div>

            @php
                $oldDetails = $selectedLog['old_values'] ?? [];
                $newDetails = $selectedLog['new_values'] ?? [];
                $detailKeys = collect(array_keys($oldDetails))
                    ->merge(array_keys($newDetails))
                    ->unique()
                    ->values();
            @endphp

            <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
                <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-300">Field</th>
                        <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-300">Old Value</th>
                        <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-300">New Value</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($detailKeys as $field)
                        @php
                            $oldValue = $oldDetails[$field] ?? null;
                            $newValue = $newDetails[$field] ?? null;
                        @endphp
                        <tr @class(['bg-green-50 dark:bg-green-950/30' => $formatLogValue($oldValue) !== $formatLogValue($newValue)])>
                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-zinc-100">{{ $field }}</td>
                            <td class="px-4 py-3 text-zinc-600 dark:text-zinc-300">{{ $formatLogValue($oldValue) }}</td>
                            <td class="px-4 py-3 text-zinc-600 dark:text-zinc-300">{{ $formatLogValue($newValue) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-zinc-500">No value details were recorded.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </flux:modal>
</div>
