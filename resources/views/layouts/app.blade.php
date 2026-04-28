<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        <script>
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="h-screen bg-gray-100 dark:bg-gray-900 p-6">
    <div class="flex items-center justify-center h-full">

{{--        <div class="login-image flex-1 w-full h-full ">--}}
{{--            <img src="{{ asset("images/coaftc.webp") }}" alt="" class="w-full h-full object-cover">--}}
{{--        </div>--}}

        {{ $slot }}

        @livewireScripts
    </body>
</html>
