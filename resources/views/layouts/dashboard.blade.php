@php use App\Enums\UserRoles; @endphp
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

        <flux:sidebar sticky collapsible wire:cloak class="bg-white dark:bg-zinc-900 border-r border-zinc-300 dark:border-zinc-700 text-zinc-800 dark:text-zinc-200">

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
                <flux:sidebar.item icon="chart-bar-square" wire:current.exact="bg-green-300!" wire:navigate href="{{ route('dashboard.home') }}" class="text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white">Analytics</flux:sidebar.item>

                @if(auth()->user()->user_role === App\Enums\UserRoles::ADMIN || auth()->user()->user_role->value === App\Enums\UserRoles::INVENTORY->value)
                <flux:sidebar.item icon="cube" wire:current.exact="bg-green-300!" wire:navigate href="{{ route('dashboard.products') }}" class="text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white">Products</flux:sidebar.item>
                <flux:sidebar.item icon="qr-code" wire:current.exact="bg-green-300!" wire:navigate href="{{ route('dashboard.products-qr') }}" class="text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white">Products QR Code</flux:sidebar.item>
                @endif

                @if(auth()->user()->user_role->value === App\Enums\UserRoles::ADMIN->value || auth()->user()->user_role->value === App\Enums\UserRoles::CASHIER->value)
                <flux:sidebar.item icon="currency-dollar" wire:navigate  href="{{ route('dashboard.sales') }}" class="text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white">Sales</flux:sidebar.item>
                @endif

                <flux:sidebar.item icon="document-text"  href="#" class="text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white">Reports</flux:sidebar.item>

                @if(auth()->user()->user_role->value === App\Enums\UserRoles::ADMIN->value)
                <flux:sidebar.item icon="user" wire:current.strict="bg-green-300!"  wire:navigate href="{{ route('dashboard.users') }}" class="text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white">Users</flux:sidebar.item>
                <flux:sidebar.item icon="user-group" wire:current.exact wire:navigate href="{{ route('dashboard.employees') }}" class="text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white">Employees</flux:sidebar.item>
                @endif

{{--                <flux:sidebar.group expandable icon="cog-6-tooth" heading="Settings" class="grid text-zinc-800 dark:text-zinc-200 [&_[data-flux-sidebar-heading]]:text-primary dark:[&_[data-flux-sidebar-heading]]:text-primary">--}}
{{--                    <flux:sidebar.item href="" class="text-zinc-800 dark:text-zinc-200 hover:bg-primary dark:hover:bg-primary hover:text-white">Account</flux:sidebar.item>--}}
{{--                    <flux:sidebar.item href="#" class="text-zinc-800 dark:text-zinc-200 hover:bg-primary dark:hover:bg-primary hover:text-white">Notifications</flux:sidebar.item>--}}
{{--                    <flux:sidebar.item href="#" class="text-zinc-800 dark:text-zinc-200 hover:bg-primary dark:hover:bg-primary hover:text-white">Security</flux:sidebar.item>--}}
{{--                </flux:sidebar.group>--}}
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
                    <flux:menu.item icon="cog-6-tooth" href="{{ route('profile.edit') }}" wire:navigate>Edit Profile</flux:menu.item>
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

        <flux:main class="bg-green-300">
{{--        <flux:main class="relative overflow-hidden !bg-transparent">--}}
{{--            <div class="absolute inset-0 bg-linear-to-br from-emerald-600 via-emerald-400 to-zinc-100"></div>--}}
{{--            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.78),transparent_28%),radial-gradient(circle_at_top_right,rgba(255,255,255,0.42),transparent_24%),radial-gradient(circle_at_bottom_left,rgba(6,78,59,0.12),transparent_30%)]"></div>--}}
{{--            <div class="absolute inset-x-0 top-0 h-40 bg-linear-to-b from-white/45 via-white/16 to-transparent"></div>--}}

{{--            <div class="relative min-h-screen p-4 sm:p-6 lg:p-8">--}}
{{--                <div class="min-h-[calc(100vh-4rem)] rounded-[2rem] border border-white/55 bg-white/28 shadow-[inset_0_1px_0_rgba(255,255,255,0.55),0_28px_70px_rgba(15,23,42,0.10)] backdrop-blur-md">--}}
{{--                    <div class="h-full rounded-[2rem] bg-linear-to-b from-white/18 to-transparent p-5 sm:p-6 lg:p-8">--}}

                        {{ $slot }}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </flux:main>
    </div>
    @livewireScripts
    @fluxScripts

    </body>
</html>
