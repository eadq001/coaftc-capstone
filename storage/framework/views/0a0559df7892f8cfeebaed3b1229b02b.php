<div class="min-h-screen bg-zinc-950">
    <div class="grid min-h-screen lg:grid-cols-[1.2fr_0.8fr]">
        <section class="relative hidden overflow-hidden lg:flex">
            <div
                class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('<?php echo e(asset('images/coaftc-bg-resize.webp')); ?>');"
            ></div>
            <div class="absolute inset-0 bg-linear-to-br from-emerald-950/90 via-emerald-900/72 to-zinc-950/84"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.16),transparent_32%),radial-gradient(circle_at_bottom_left,rgba(163,230,53,0.14),transparent_28%)]"></div>

            <div class="relative z-10 flex min-h-screen w-full flex-col justify-between p-10 xl:p-14">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/12 ring-1 ring-white/20 backdrop-blur-md">
                        <img src="<?php echo e(asset('images/coaftc.png')); ?>" alt="COAFTC" class="h-10 w-10 object-contain">
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.32em] text-emerald-100/80">Enterprise Access</p>
                        <h1 class="mt-1 text-2xl font-semibold text-white">COAFTC Operations Portal</h1>
                    </div>
                </div>

                <div class="max-w-2xl">
                    <div class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm text-emerald-50 backdrop-blur-md">
                        Organic agriculture and fisheries operations
                    </div>

                    <h2 class="mt-6 max-w-xl text-5xl font-semibold leading-tight text-white">
                        Secure access for inventory, workforce, and field operations.
                    </h2>

                    <p class="mt-5 max-w-xl text-base leading-7 text-emerald-50/78">
                        Centralize product monitoring, employee records, and day-to-day administrative workflows in a single controlled environment.
                    </p>

                    <div class="mt-10 grid max-w-2xl gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-white/12 bg-white/10 p-4 backdrop-blur-md">
                            <p class="text-xs uppercase tracking-[0.24em] text-emerald-100/70">Visibility</p>
                            <p class="mt-3 text-lg font-semibold text-white">Inventory oversight</p>
                        </div>

                        <div class="rounded-2xl border border-white/12 bg-white/10 p-4 backdrop-blur-md">
                            <p class="text-xs uppercase tracking-[0.24em] text-emerald-100/70">Security</p>
                            <p class="mt-3 text-lg font-semibold text-white">Role-based staff access</p>
                        </div>

                        <div class="rounded-2xl border border-white/12 bg-white/10 p-4 backdrop-blur-md">
                            <p class="text-xs uppercase tracking-[0.24em] text-emerald-100/70">Control</p>
                            <p class="mt-3 text-lg font-semibold text-white">Operational decision support</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-white/12 pt-6 text-sm text-emerald-50/70">
                    <span>City Organic Agriculture and Fisheries Training Center</span>
                    <span>Internal System</span>
                </div>
            </div>
        </section>

        <section class="relative flex min-h-screen items-center justify-center bg-zinc-950 px-2 py-2 sm:px-8 lg:bg-zinc-100">
            <div class="absolute inset-0 lg:hidden">
                <div
                    class="absolute inset-0 bg-cover bg-center"
                    style="background-image: url('<?php echo e(asset('images/coaftc-bg.jpeg')); ?>');"
                ></div>
                <div class="absolute inset-0 bg-zinc-950/82"></div>
            </div>

            <div class="relative z-10 w-full max-w-xl">
                <div class="mb-8 flex items-center gap-4 lg:hidden">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/15 backdrop-blur-md">
                        <img src="<?php echo e(asset('images/coaftc.png')); ?>" alt="COAFTC" class="h-8 w-8 object-contain">
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-200/80">Enterprise Access</p>
                        <p class="text-lg font-semibold text-white">COAFTC Operations Portal</p>
                    </div>
                </div>

                <?php if (isset($component)) { $__componentOriginalc4bce27d2c09d2f98a63d67977c1c3ec = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc4bce27d2c09d2f98a63d67977c1c3ec = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::card.index','data' => ['class' => 'overflow-hidden rounded-[2rem] border border-white/12 bg-white/96 p-0 shadow-[0_24px_80px_rgba(15,23,42,0.26)] backdrop-blur-xl lg:border-zinc-200 lg:bg-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'overflow-hidden rounded-[2rem] border border-white/12 bg-white/96 p-0 shadow-[0_24px_80px_rgba(15,23,42,0.26)] backdrop-blur-xl lg:border-zinc-200 lg:bg-white']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    <div class="border-b border-zinc-200/80 px-4 py-7 sm:px-10">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.32em] text-emerald-700">Sign In</p>
                                <?php if (isset($component)) { $__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::heading','data' => ['size' => 'xl','class' => 'mt-3 text-zinc-950']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'xl','class' => 'mt-3 text-zinc-950']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Access your workspace <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9)): ?>
<?php $attributes = $__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9; ?>
<?php unset($__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9)): ?>
<?php $component = $__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9; ?>
<?php unset($__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal0638ebfbd490c7a414275d493e14cb4e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0638ebfbd490c7a414275d493e14cb4e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::text','data' => ['class' => 'mt-2 max-w-md text-sm leading-6 text-zinc-600']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-2 max-w-md text-sm leading-6 text-zinc-600']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    Use your authorized account to continue to the operations dashboard.
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0638ebfbd490c7a414275d493e14cb4e)): ?>
<?php $attributes = $__attributesOriginal0638ebfbd490c7a414275d493e14cb4e; ?>
<?php unset($__attributesOriginal0638ebfbd490c7a414275d493e14cb4e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0638ebfbd490c7a414275d493e14cb4e)): ?>
<?php $component = $__componentOriginal0638ebfbd490c7a414275d493e14cb4e; ?>
<?php unset($__componentOriginal0638ebfbd490c7a414275d493e14cb4e); ?>
<?php endif; ?>
                            </div>

                            <div class="hidden rounded-2xl bg-emerald-50 p-3 sm:block">
                                <?php if (isset($component)) { $__componentOriginalf870514c33bb1b53395ba02235f60146 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf870514c33bb1b53395ba02235f60146 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::icon.shield-check','data' => ['class' => 'h-7 w-7 text-emerald-700']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::icon.shield-check'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-7 w-7 text-emerald-700']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf870514c33bb1b53395ba02235f60146)): ?>
<?php $attributes = $__attributesOriginalf870514c33bb1b53395ba02235f60146; ?>
<?php unset($__attributesOriginalf870514c33bb1b53395ba02235f60146); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf870514c33bb1b53395ba02235f60146)): ?>
<?php $component = $__componentOriginalf870514c33bb1b53395ba02235f60146; ?>
<?php unset($__componentOriginalf870514c33bb1b53395ba02235f60146); ?>
<?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="px-7 py-8 sm:px-10">
                        <form class="space-y-6" wire:submit="login">
                            <div class="grid gap-6">
                                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['wire:model.live.debounce.400ms' => 'email','type' => 'email','name' => 'email','placeholder' => 'name@company.com','inputClass' => 'h-13 rounded-xl border-zinc-300 bg-white','autocomplete' => 'email']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live.debounce.400ms' => 'email','type' => 'email','name' => 'email','placeholder' => 'name@company.com','inputClass' => 'h-13 rounded-xl border-zinc-300 bg-white','autocomplete' => 'email']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>

                                <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['wire:model.live.debounce.400ms' => 'password','type' => 'password','name' => 'password','placeholder' => 'Enter your password','inputClass' => 'h-13 rounded-xl border-zinc-300 bg-white','autocomplete' => 'current-password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live.debounce.400ms' => 'password','type' => 'password','name' => 'password','placeholder' => 'Enter your password','inputClass' => 'h-13 rounded-xl border-zinc-300 bg-white','autocomplete' => 'current-password']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['loginFailed'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <label class="inline-flex items-center gap-3 text-sm text-zinc-600">
                                    <input
                                        type="checkbox"
                                        name="remember"
                                        class="h-4 w-4 rounded border-zinc-300 text-emerald-700 focus:ring-emerald-600"
                                        wire:model.boolean="remember"
                                    >
                                    <span>Keep me signed in on this device</span>
                                </label>

                                <a
                                    href="<?php echo e(Route::has('password.request') ? route('password.request') : '#'); ?>"
                                    class="text-sm font-medium text-emerald-700 transition hover:text-emerald-800"
                                >
                                    Forgot password?
                                </a>
                            </div>

                            <div class="grid gap-4 pt-2">
                                <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['type' => 'submit','variant' => 'primary','class' => 'h-13 w-full cursor-pointer rounded-xl !bg-emerald-700 text-base font-semibold hover:!bg-emerald-800']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'primary','class' => 'h-13 w-full cursor-pointer rounded-xl !bg-emerald-700 text-base font-semibold hover:!bg-emerald-800']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    Log In
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>

                                <div class="flex items-center justify-between rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-500">
                                    <span>Authorized users only</span>
                                    <span class="font-medium text-zinc-700">Protected session</span>
                                </div>
                            </div>
                        </form>
                    </div>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc4bce27d2c09d2f98a63d67977c1c3ec)): ?>
<?php $attributes = $__attributesOriginalc4bce27d2c09d2f98a63d67977c1c3ec; ?>
<?php unset($__attributesOriginalc4bce27d2c09d2f98a63d67977c1c3ec); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc4bce27d2c09d2f98a63d67977c1c3ec)): ?>
<?php $component = $__componentOriginalc4bce27d2c09d2f98a63d67977c1c3ec; ?>
<?php unset($__componentOriginalc4bce27d2c09d2f98a63d67977c1c3ec); ?>
<?php endif; ?>
            </div>
        </section>
    </div>
</div>
<?php /**PATH C:\Herd\coaftcorig\resources\views/livewire/auth/login.blade.php ENDPATH**/ ?>