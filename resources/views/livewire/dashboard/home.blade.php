<div>
<div class="mb-6">
    <flux:heading size="xl" level="1">Welcome back</flux:heading>
    <flux:text class="mt-1 text-zinc-600">Here's an overview of your dashboard</flux:text>
</div>

<div class="grid gap-6 lg:grid-cols-3 mb-8">
    <flux:card class="border border-zinc-300 border-t-4 border-t-primary rounded-lg shadow-sm bg-white">
        <flux:heading size="sm" class="text-zinc-800">Total Revenue</flux:heading>
        <flux:text class="text-2xl font-semibold mt-2 text-primary">$12,450</flux:text>
        <flux:badge color="primary" class="mt-2">+12.5%</flux:badge>
    </flux:card>

    <flux:card class="border border-zinc-300 border-t-4 border-t-primary rounded-lg shadow-sm bg-white">
        <flux:heading size="sm" class="text-zinc-800">Active Users</flux:heading>
        <flux:text class="text-2xl font-semibold mt-2 text-primary">1,234</flux:text>
        <flux:badge color="primary" class="mt-2">+5.2%</flux:badge>
    </flux:card>

    <flux:card class="border border-zinc-300 border-t-4 border-t-primary rounded-lg shadow-sm bg-white">
        <flux:heading size="sm" class="text-zinc-800">Tasks Completed</flux:heading>
        <flux:text class="text-2xl font-semibold mt-2 text-primary">89</flux:text>
        <flux:badge color="primary" class="mt-2">+3.1%</flux:badge>
    </flux:card>
</div>
    <div class="grid gap-6 lg:grid-cols-3 mb-8">
        <flux:card class="border border-zinc-300 border-t-4 border-t-primary rounded-lg shadow-sm bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="sm" class="text-zinc-800">Total Products</flux:heading>
                    <flux:text class="text-2xl font-semibold mt-2 text-primary">{{ $totalProducts ?? '0' }}</flux:text>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <flux:icon.cube class="w-6 h-6 text-primary"/>
                </div>
            </div>
        </flux:card>

        <flux:card class="border border-zinc-300 border-t-4 border-t-red-500 rounded-lg shadow-sm bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="sm" class="text-zinc-800">Low Stock Items</flux:heading>
                    <flux:text class="text-2xl font-semibold mt-2 text-red-600">{{ $lowStockItems ?? '0' }}</flux:text>
                </div>
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <flux:icon.exclamation-triangle class="w-6 h-6 text-red-600"/>
                </div>
            </div>
        </flux:card>

        <flux:card class="border border-zinc-300 border-t-4 border-t-primary rounded-lg shadow-sm bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="sm" class="text-zinc-800">Total Inventory Value</flux:heading>
                    <flux:text class="text-2xl font-semibold mt-2 text-primary">
                        <span class="text-2xl text-primary">&#8369; {{ $totalInventoryValue }}</span>
                    </flux:text>
                </div>

                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <div class="text-2xl text-primary flex justify-center items-center">&#8369;</div>
                </div>
            </div>
        </flux:card>
    </div>

<flux:separator variant="subtle" />

<div class="mt-6 flex items-center gap-3">
    <flux:button variant="primary" icon="plus">New Project</flux:button>
    <flux:button variant="outline" icon="arrow-down-tray" class="border-primary text-primary hover:bg-primary hover:text-white">Export</flux:button>
</div>
</div>
