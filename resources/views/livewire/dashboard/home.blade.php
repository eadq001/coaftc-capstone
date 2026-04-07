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

<flux:separator variant="subtle" />

<div class="mt-6 flex items-center gap-3">
    <flux:button variant="primary" icon="plus">New Project</flux:button>
    <flux:button variant="outline" icon="arrow-down-tray" class="border-primary text-primary hover:bg-primary hover:text-white">Export</flux:button>
</div>
</div>
