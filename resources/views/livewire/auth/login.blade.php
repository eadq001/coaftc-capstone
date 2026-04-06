<body class="h-screen bg-gray-100 dark:bg-gray-900 p-6">
<div class="flex items-center justify-center h-full">

    <div class="login-image flex-1 w-full h-full ">
        <img src="{{ asset("images/coaftc.webp") }}" alt="" class="w-full h-full object-cover">
    </div>

    <div class="w-full max-w-full flex-1 h-full">
        <div class="bg-white dark:bg-gray-800  shadow-lg p-8 h-full flex flex-col justify-center">
            <div class="text-center mb-8">
                <flux:heading size="xl">Welcome Back</flux:heading>
                <flux:text class="mt-2 text-gray-500 dark:text-gray-400">Sign in to your account</flux:text>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6" wire:submit="submit">
                @csrf

                <flux:field>
                    <flux:label>Email</flux:label>
                    <flux:input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email"
                        required
                        autofocus
                        input:class="h-13"

                    />
                    <flux:error name="email"/>
                </flux:field>

                <flux:field>
                    <flux:label>Password</flux:label>
                    <flux:input
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                        input:class="h-13"
                    />
                    <flux:error name="password"/>
                </flux:field>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5">
                        <span class="ml-2 text-base text-gray-600 dark:text-gray-400">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-500">
                        Forgot password?
                    </a>
                </div>

                <flux:button type="submit" variant="primary" class="w-full cursor-pointer">
                    Login
                </flux:button>
            </form>

            {{--            <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">--}}
            {{--                Don't have an account?--}}
            {{--                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 dark:text-blue-400">--}}
            {{--                    Sign up--}}
            </a>
        </div>
    </div>
</div>

</body>
</html>
