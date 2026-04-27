<?php

use App\Models\UnverifiedUser;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.blank', ['title' => 'Email Verification'])]
class extends Component {

    public bool $verified = false;

    public function mount(): void
    {
        $user = UnverifiedUser::where('verification_token', '=', request()->token)->first();

        if ($user) {
            DB::transaction(function () use ($user) {
                User::create(['name' => $user->username,
                    'email' => $user->email,
                    'password' => $user->password,
                    'user_role' => $user->user_role,
                    'email_verified_at' => now()
                ]);
                $user->delete();
                $this->verified = true;
            });
        } else {
            $this->verified = false;
        }

    }
};
?>
<div>
    @if($verified)
        <div class="text-lg">
            Email Verified
        </div>
    @else
        <div class="text-lg">
            Link Expired
        </div>
</div>
<script>
    setTimeout(() => window.location.href = "/login", 3000);
</script>

@endif
