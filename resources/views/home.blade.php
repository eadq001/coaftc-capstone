@vite(['resources/css/app.css'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? config('app.name', 'Home') }}</title>
    </head>
    <body class="min-h-screen bg-cover bg-center bg-no-repeat" style="background-image: url('{{ config('app.home_background', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920') }}')">
        <div class="min-h-screen bg-black/40 flex flex-col">
            <header class="w-full py-6 px-8">
                <div class="max-w-7xl mx-auto flex justify-end gap-4">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="px-6 py-2 text-white font-semibold border border-white/80 rounded-full hover:bg-white hover:text-gray-900 transition duration-300">
                            Login
                        </a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-6 py-2 bg-white text-gray-900 font-semibold rounded-full hover:bg-gray-100 transition duration-300">
                            Register
                        </a>
                    @endif
                </div>
            </header>

            <main class="flex-1 flex items-center justify-center">
                <div class="text-center text-white">
                    <h1 class="text-5xl md:text-7xl font-bold mb-4 drop-shadow-lg">
                        {{ config('app.name', 'Welcome') }}
                    </h1>
                    <p class="text-xl md:text-2xl text-white/90 drop-shadow">
                        {{ config('app.tagline', 'Your journey starts here') }}
                    </p>
                </div>
            </main>
        </div>
    </body>
</html>
