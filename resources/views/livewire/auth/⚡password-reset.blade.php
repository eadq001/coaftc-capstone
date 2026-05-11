<?php

use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Layout('layouts.app')]
class extends Component {

    #[Url]
    public ?string $token = null;

    #[Url]
    public ?string $email = null;

    public string $resetEmail = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public bool $passwordResetSuccess = false;

    public function sendResetLink(): void
    {
        $this->validate(['resetEmail' => 'required|email|exists:users,email']);

        $token = Str::random(64);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $this->resetEmail],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        $url = route('password.reset', [
            'token' => $token,
            'email' => $this->resetEmail,
        ]);

        Mail::to($this->resetEmail)->send(new PasswordResetMail($url));

        session()->flash('status', 'Password reset link sent to your email.');
    }

    public function resetPassword(): void
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
            'passwordConfirmation' => 'required|min:8|same:password',
        ]);

        $record = DB::table('password_resets')->where('email', $this->email)->first();

        if (!$record || !Hash::check($this->token, $record->token)) {
            $this->addError('email', 'Invalid or expired reset link.');
            return;
        }

        User::where('email', $this->email)->update(['password' => Hash::make($this->password)]);

        DB::table('password_resets')->where('email', $this->email)->delete();

        session()->flash('status', 'Password reset successfully!');

//        $this->passwordResetSuccess = true;

//        $this->reset('password', 'passwordConfirmation');
//        sleep(2);
        $this->redirect('/login', true);
    }
};
?>

<div class="w-full max-w-lg mx-auto">
    <flux:card
            class="overflow-hidden rounded-[2rem] border border-white/12 bg-white/96 p-0 shadow-[0_24px_80px_rgba(15,23,42,0.26)] backdrop-blur-xl lg:border-zinc-200 lg:bg-white">
        <div class="border-b border-zinc-200/80 px-4 py-7 sm:px-10">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.32em] text-emerald-700">
                        {{ $token ? 'Reset Password' : 'Forgot Password' }}
                    </p>
                    <flux:heading size="xl" class="mt-3 text-zinc-950">
                        {{ $token ? 'Set a new password' : 'Reset your password' }}
                    </flux:heading>
                    <flux:text class="mt-2 max-w-md text-sm leading-6 text-zinc-600">
                        {{ $token ? 'Enter your new password below.' : 'Enter your email and we will send you a reset link.' }}
                    </flux:text>
                </div>
                <div class="hidden rounded-2xl bg-emerald-50 p-3 sm:block">
                    <flux:icon.shield-check class="h-7 w-7 text-emerald-700"/>
                </div>
            </div>
        </div>

        <div class="px-7 py-8 sm:px-10">
            @if (session('status'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

{{--            @if ($passwordResetSuccess)--}}
{{--                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">--}}
{{--                    {{ 'You successfully reset the password' }}--}}
{{--                </div>--}}
{{--            @endif--}}

            @if ($token)
                <form class="space-y-6" wire:submit="resetPassword">
                    <div class="grid gap-6">
                        <flux:field>
                            <flux:label class="mb-0.5!">Email</flux:label>
                            <flux:input type="email" wire:model="email" placeholder="name@company.com" readonly
                                        class="bg-zinc-50"/>
                            <flux:error name="email"/>
                        </flux:field>

                        <flux:field>
                            <flux:label class="mb-0.5!">New Password</flux:label>
                            <flux:input type="password" wire:model.live.debounce.400ms="password"
                                        placeholder="New password"/>
                            <flux:error name="password"/>
                        </flux:field>

                        <flux:field>
                            <flux:label class="mb-0.5!">Confirm Password</flux:label>
                            <flux:input type="password" wire:model.live.debounce.400ms="passwordConfirmation"
                                        placeholder="Confirm new password"/>
                            <flux:error name="passwordConfirmation"/>
                        </flux:field>
                    </div>

                    @error('email')
                    <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $message }}
                    </div>
                    @enderror

                    <div class="grid gap-4 pt-2">
                        <flux:button type="submit" variant="primary"
                                     class="h-13 w-full cursor-pointer rounded-xl !bg-emerald-700 text-base font-semibold hover:!bg-emerald-800">
                            Reset Password
                        </flux:button>
                    </div>
                </form>
            @else
                <form class="space-y-6" wire:submit="sendResetLink">
                    <div class="grid gap-6">
                        <flux:field>
                            <flux:label class="mb-0.5!">Email</flux:label>
                            <flux:input type="email" wire:model.live.debounce.400ms="resetEmail"
                                        placeholder="name@company.com"/>
                            <flux:error name="resetEmail"/>
                        </flux:field>
                    </div>

                    <div class="grid gap-4 pt-2">
                        <flux:button type="submit" variant="primary"
                                     class="h-13 w-full cursor-pointer rounded-xl !bg-emerald-700 text-base font-semibold hover:!bg-emerald-800">
                            Send Reset Link
                        </flux:button>
                    </div>

                    <div class="text-center">
                        <a wire:navigate href="{{ route('login') }}"
                           class="text-sm font-medium text-emerald-700 transition hover:text-emerald-800">
                            Back to sign in
                        </a>
                    </div>
                </form>
            @endif
        </div>
    </flux:card>
</div>
