<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="h-screen bg-zinc-950 font-sans antialiased">
    <div class="flex h-full items-center justify-center px-4">
        <div class="w-full max-w-md rounded-[2rem] border border-zinc-800 bg-zinc-900/60 p-8 text-center shadow-[0_32px_80px_rgba(0,0,0,0.45)] backdrop-blur-xl">
            <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-amber-900/40">
                <svg class="h-8 w-8 text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-semibold text-white">Session Expired</h1>
            <p class="mt-4 text-sm leading-6 text-zinc-400">
                Someone logged in with the same account credentials.
            </p>
            <p class="mt-4 text-xs text-zinc-600">
                Redirecting to login page in <span id="countdown" class="font-semibold text-zinc-300">3</span> seconds...
            </p>
        </div>
    </div>

    <script>
        (function () {
            var seconds = 3;
            var el = document.getElementById('countdown');
            var timer = setInterval(function () {
                seconds--;
                if (el) el.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(timer);
                    window.location.href = '/login';
                }
            }, 1000);
        })();
    </script>
</body>
</html>
