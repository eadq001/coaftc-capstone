<?php
use App\Models\UnverifiedUser;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
?>

<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($verified): ?>
        <div class="text-lg">
            Email Verified
        </div>
    <?php else: ?>
        <div class="text-lg">
            Link Expired
        </div>
</div>
        <script>
            setTimeout(() => window.location.href = "/login", 3000);
        </script>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php /**PATH C:\Herd\coaftcorig\storage\framework/views/livewire/views/63108672.blade.php ENDPATH**/ ?>