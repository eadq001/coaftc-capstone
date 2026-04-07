<div class="min-h-screen bg-white antialiased">
    <style>
        [data-flux-sidebar] {
            transition: width 300ms cubic-bezier(0.4, 0, 0.2, 1),
                        max-width 300ms cubic-bezier(0.4, 0, 0.2, 1),
                        min-width 300ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-flux-sidebar] > * {
            transition: opacity 200ms ease-out, transform 200ms ease-out;
        }

        [data-flux-sidebar-collapsed-desktop] [data-flux-sidebar-nav-item-label],
        [data-flux-sidebar-collapsed-desktop] [data-flux-sidebar-heading],
        [data-flux-sidebar-collapsed-desktop] [data-flux-sidebar-group-expand-icon] {
            opacity: 0;
            transform: translateX(-8px);
            pointer-events: none;
        }

        [data-flux-sidebar-collapsed-desktop] [data-flux-sidebar-brand-name],
        [data-flux-sidebar-collapsed-desktop] [data-flux-sidebar-profile-name] {
            opacity: 0;
            pointer-events: none;
        }
    </style>

    <flux:sidebar sticky collapsible class="bg-white border-r border-zinc-300 text-zinc-800">

        <flux:sidebar.header class="border-b border-zinc-300">
            <flux:sidebar.brand
                href="#" disabled
                name="Dashboard"
            >
                <x-slot:logo>
                    <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center">
                        <flux:icon.sparkles class="w-5 h-5 text-white" />
                    </div>
                </x-slot:logo>
            </flux:sidebar.brand>

            <flux:sidebar.collapse class="hidden lg:flex" />
        </flux:sidebar.header>

        <flux:sidebar.nav class="text-zinc-800">
            <flux:sidebar.item icon="home" href="#" current class="bg-primary text-white aria-selected:bg-primary">Home</flux:sidebar.item>
            <flux:sidebar.item icon="chart-bar-square" href="#" class="text-zinc-800 hover:bg-primary hover:text-white">Analytics</flux:sidebar.item>
            <flux:sidebar.item icon="cube" href="#" class="text-zinc-800 hover:bg-primary hover:text-white">Products</flux:sidebar.item>
            <flux:sidebar.item icon="currency-dollar" href="#" class="text-zinc-800 hover:bg-primary hover:text-white">Sales</flux:sidebar.item>
            <flux:sidebar.item icon="document-text" href="#" class="text-zinc-800 hover:bg-primary hover:text-white">Reports</flux:sidebar.item>
            <flux:sidebar.item icon="user" href="#" class="text-zinc-800 hover:bg-primary hover:text-white">Users</flux:sidebar.item>
            <flux:sidebar.item icon="user-group" href="#" class="text-zinc-800 hover:bg-primary hover:text-white">Employees</flux:sidebar.item>

            <flux:sidebar.group expandable icon="cog-6-tooth" heading="Settings" class="grid text-zinc-800 [&_[data-flux-sidebar-heading]]:text-primary">
                <flux:sidebar.item href="#" class="text-zinc-800 hover:bg-primary hover:text-white">Account</flux:sidebar.item>
                <flux:sidebar.item href="#" class="text-zinc-800 hover:bg-primary hover:text-white">Notifications</flux:sidebar.item>
                <flux:sidebar.item href="#" class="text-zinc-800 hover:bg-primary hover:text-white">Security</flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.spacer />

        <flux:sidebar.nav>
            <flux:sidebar.item icon="information-circle" href="#" class="text-zinc-800 hover:bg-primary hover:text-white">Help</flux:sidebar.item>
        </flux:sidebar.nav>

        <flux:dropdown position="top" align="start" class="max-lg:hidden">
            <flux:sidebar.profile avatar="https://fluxui.dev/img/demo/user.png" name="Olivia Martin" />

            <flux:menu>
                <flux:menu.radio.group>
                    <flux:menu.radio checked>Olivia Martin</flux:menu.radio>
                    <flux:menu.radio>Truly Delta</flux:menu.radio>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.item icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <flux:header class="lg:hidden bg-white border-b border-zinc-300">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="start">
            <flux:profile avatar="https://fluxui.dev/img/demo/user.png" />

            <flux:menu>
                <flux:menu.radio.group>
                    <flux:menu.radio checked>Olivia Martin</flux:menu.radio>
                    <flux:menu.radio>Truly Delta</flux:menu.radio>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.item icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    <flux:main>
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
    </flux:main>
</div>
