

    <div class="w-full max-w-full flex-1 h-full">
        <div class="bg-white dark:bg-gray-800  shadow-lg p-8 h-full flex flex-col justify-center">
            <div class="text-center mb-8">
                <flux:heading size="xl">Welcome Back</flux:heading>
                <flux:text class="mt-2 text-gray-500 dark:text-gray-400">Sign in to your account</flux:text>
            </div>

            <form class="space-y-6" wire:submit="login">

                <x-input wire:model.live.debounce.600ms="email" type="email" name="email" placeholder="Enter your email" inputClass="h-13" />

                <x-input wire:model.live.debounce.600ms="password" type="password" name="password" placeholder="Enter your password" inputClass="h-13" required="true"/>

                @error('loginFailed')
                <flux:error name="loginFailed"/>
                @enderror

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5"
                               wire:model.boolean="remember">
                        <span class="ml-2 text-base text-gray-600 dark:text-gray-400">Remember me</span>
                    </label>
                    <a href="{{ Route::has('password.request') ?? '#' }}" class="text-sm text-blue-600 hover:text-blue-500">
                        Forgot password?
                    </a>
                </div>

                <flux:button type="submit" variant="primary" class="w-full cursor-pointer">
                    Login
                </flux:button>
            </form>
        </div>
    </div>

