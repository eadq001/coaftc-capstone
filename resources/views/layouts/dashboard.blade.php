<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        @fluxAppearance
        <script>
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body>

    <div class="min-h-screen bg-white dark:bg-zinc-900 antialiased">
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

        <flux:sidebar sticky collapsible class="bg-white dark:bg-zinc-900 border-r border-zinc-300 dark:border-zinc-700 text-zinc-800 dark:text-zinc-200">

            <flux:sidebar.header class="border-b border-zinc-300 dark:border-zinc-700">
                <flux:sidebar.brand
                    href="#"
                    disabled
                    name="Coaftc Dashboard"
                    class="h-full"
                >
                    <img src="{{ asset('images/coaftc.png') }}" class="w-full h-full">

{{--                    <x-slot:logo>--}}
{{--                        <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center">--}}
{{--                            <flux:icon.sparkles class="w-5 h-5 text-white" />--}}
{{--                        </div>--}}

{{--                    </x-slot:logo>--}}
                </flux:sidebar.brand>

                <flux:sidebar.collapse class="hidden lg:flex" />
            </flux:sidebar.header>

            <flux:sidebar.nav class="text-zinc-800 dark:text-zinc-200">
                <flux:sidebar.item icon="home" wire:navigate href="{{ route('dashboard.home') }}" class="text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white">Home</flux:sidebar.item>
                <flux:sidebar.item icon="chart-bar-square" href="#" class="text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white">Analytics</flux:sidebar.item>
                <flux:sidebar.item icon="cube" wire:navigate href="{{ route('dashboard.products') }}" class="text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white">Products</flux:sidebar.item>
                <flux:sidebar.item icon="currency-dollar" href="#" class="text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white">Sales</flux:sidebar.item>
                <flux:sidebar.item icon="document-text" href="#" class="text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white">Reports</flux:sidebar.item>
                <flux:sidebar.item icon="user" href="#" class="text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white">Users</flux:sidebar.item>
                <flux:sidebar.item icon="user-group" href="#" class="text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white">Employees</flux:sidebar.item>

                <flux:sidebar.group expandable icon="cog-6-tooth" heading="Settings" class="grid text-zinc-800 dark:text-zinc-200 [&_[data-flux-sidebar-heading]]:text-primary dark:[&_[data-flux-sidebar-heading]]:text-primary">
                    <flux:sidebar.item href="" class="text-zinc-800 dark:text-zinc-200 hover:bg-primary dark:hover:bg-primary hover:text-white">Account</flux:sidebar.item>
                    <flux:sidebar.item href="#" class="text-zinc-800 dark:text-zinc-200 hover:bg-primary dark:hover:bg-primary hover:text-white">Notifications</flux:sidebar.item>
                    <flux:sidebar.item href="#" class="text-zinc-800 dark:text-zinc-200 hover:bg-primary dark:hover:bg-primary hover:text-white">Security</flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:sidebar.spacer />


            <flux:dropdown position="top" align="start" class="max-lg:hidden">
                <flux:sidebar.profile name="{{ auth()->user()->name }}" />

                <flux:menu>
                    <flux:menu.radio.group>
                        <flux:menu.radio checked>{{ auth()->user()->name }}</flux:menu.radio>
                        {{--                    <flux:menu.radio>Truly Delta</flux:menu.radio>--}}
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form action="/logout" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <flux:button class="border-none! cursor-pointer hover:text-white-200 text-sm" icon="arrow-right-start-on-rectangle" type="submit">Logout</flux:button>
                    </form>
                    <flux:menu.separator />


                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <flux:main class="!bg-green-300">
            {{ $slot }}
        </flux:main>
    </div>
    @livewireScripts
    @fluxScripts

    </body>
</html>
