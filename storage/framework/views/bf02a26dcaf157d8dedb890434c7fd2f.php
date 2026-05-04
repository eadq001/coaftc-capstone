<?php
use Livewire\Attributes\Layout;
use Livewire\Component;
?>

<?php
    $cartItems = [
        ['name' => 'Coca-Cola 1.5L', 'code' => 'QR-10021', 'qty' => 2, 'price' => 95.00],
        ['name' => 'Gardenia Classic', 'code' => 'QR-10042', 'qty' => 1, 'price' => 78.00],
        ['name' => 'Cup Noodles Beef', 'code' => 'QR-10056', 'qty' => 3, 'price' => 32.00],
    ];

    $subtotal = collect($cartItems)->sum(fn (array $item): float => $item['qty'] * $item['price']);
    $discount = 25.00;
    $tax = 18.50;
    $grandTotal = $subtotal - $discount + $tax;
?>

<div class="">




























    <div class="overflow-hidden rounded-[2rem] border border-emerald-200 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.10)]">
        <div class="grid gap-0 xl:grid-cols-[minmax(0,1.65fr)_minmax(320px,0.85fr)]">
            <section class="border-b border-emerald-100 xl:border-r xl:border-b-0">
                <div class="border-b border-emerald-100 bg-linear-to-r from-emerald-50 via-white to-emerald-100/70 px-6 py-5 sm:px-8">
                    <div class="grid gap-4 lg:grid-cols-[minmax(0,1.3fr)_repeat(4,minmax(0,0.7fr))]">
                        <?php if (isset($component)) { $__componentOriginaldbce252eb40169cc4a74f0123aabaf0b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldbce252eb40169cc4a74f0123aabaf0b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::field','data' => ['class' => 'lg:col-span-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'lg:col-span-2']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php if (isset($component)) { $__componentOriginal8a84eac5abb8af1e2274971f8640b38f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a84eac5abb8af1e2274971f8640b38f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
QR Code / Product Search <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a84eac5abb8af1e2274971f8640b38f)): ?>
<?php $attributes = $__attributesOriginal8a84eac5abb8af1e2274971f8640b38f; ?>
<?php unset($__attributesOriginal8a84eac5abb8af1e2274971f8640b38f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a84eac5abb8af1e2274971f8640b38f)): ?>
<?php $component = $__componentOriginal8a84eac5abb8af1e2274971f8640b38f; ?>
<?php unset($__componentOriginal8a84eac5abb8af1e2274971f8640b38f); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal26c546557cdc09040c8dd00b2090afd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26c546557cdc09040c8dd00b2090afd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::input.index','data' => ['icon' => 'qr-code','placeholder' => 'Scan QR or type product name...','id' => 'product-search']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'qr-code','placeholder' => 'Scan QR or type product name...','id' => 'product-search']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $attributes = $__attributesOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__attributesOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26c546557cdc09040c8dd00b2090afd0)): ?>
<?php $component = $__componentOriginal26c546557cdc09040c8dd00b2090afd0; ?>
<?php unset($__componentOriginal26c546557cdc09040c8dd00b2090afd0); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldbce252eb40169cc4a74f0123aabaf0b)): ?>
<?php $attributes = $__attributesOriginaldbce252eb40169cc4a74f0123aabaf0b; ?>
<?php unset($__attributesOriginaldbce252eb40169cc4a74f0123aabaf0b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldbce252eb40169cc4a74f0123aabaf0b)): ?>
<?php $component = $__componentOriginaldbce252eb40169cc4a74f0123aabaf0b; ?>
<?php unset($__componentOriginaldbce252eb40169cc4a74f0123aabaf0b); ?>
<?php endif; ?>
                    </div>
                </div>

                <div class="px-6 py-5 sm:px-8">

                    <div class="overflow-hidden rounded-2xl border border-zinc-200">
                        <div class="grid grid-cols-[minmax(0,1.6fr)_110px_110px_140px] border-b border-zinc-200 bg-zinc-50 text-xs font-semibold uppercase tracking-[0.22em] text-zinc-500">
                            <div class="px-4 py-3">Product</div>
                            <div class="px-4 py-3 text-right">Qty</div>
                            <div class="px-4 py-3 text-right">Price</div>
                            <div class="px-4 py-3 text-right">Amount</div>
                        </div>

                        <div class="divide-y divide-zinc-200">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div class="grid grid-cols-[minmax(0,1.6fr)_110px_110px_140px] items-center bg-white text-sm text-zinc-700 transition hover:bg-emerald-50/60">
                                    <div class="px-4 py-4">
                                        <p class="font-semibold text-zinc-900"><?php echo e($item['name']); ?></p>

                                    </div>
                                    <div class="px-4 py-4 text-right font-medium"><?php echo e($item['qty']); ?></div>
                                    <div class="px-4 py-4 text-right">₱<?php echo e(number_format($item['price'], 2)); ?></div>
                                    <div class="px-4 py-4 text-right font-semibold text-zinc-900">
                                        ₱<?php echo e(number_format($item['qty'] * $item['price'], 2)); ?>

                                    </div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="bg-zinc-950 text-white">
                <div class="border-b border-white/10 px-6 py-5 sm:px-8">
                    <?php if (isset($component)) { $__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::heading','data' => ['size' => 'lg','class' => '!text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'lg','class' => '!text-white']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Sales Summary <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::text','data' => ['class' => 'mt-1 !text-zinc-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-1 !text-zinc-300']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        Review totals, customer details, and payment readiness before checkout.
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

                <div class="grid gap-0">
                    <div class="grid grid-cols-2 border-b border-white/10">
                        <div class="border-r border-white/10 px-6 py-5">
                            <p class="text-xs uppercase tracking-[0.24em] text-zinc-400">Items</p>
                            <p class="mt-2 text-3xl font-semibold"><?php echo e(collect($cartItems)->sum('qty')); ?></p>
                        </div>
                        <div class="px-6 py-5">
                            <p class="text-xs uppercase tracking-[0.24em] text-zinc-400">Lines</p>
                            <p class="mt-2 text-3xl font-semibold"><?php echo e(count($cartItems)); ?></p>
                        </div>
                    </div>

                    <div class="border-b border-white/10 px-6 py-5 sm:px-8">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between text-sm text-zinc-300">
                                <span>Subtotal</span>
                                <span class="font-medium text-white">₱<?php echo e(number_format($subtotal, 2)); ?></span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-zinc-300">
                                <span>Discount</span>
                                <span class="font-medium text-emerald-300">- ₱<?php echo e(number_format($discount, 2)); ?></span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-zinc-300">
                                <span>Tax</span>
                                <span class="font-medium text-white">₱<?php echo e(number_format($tax, 2)); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="border-b border-white/10 bg-linear-to-br from-emerald-500 to-emerald-700 px-6 py-6 sm:px-8">
                        <p class="text-xs uppercase tracking-[0.3em] text-emerald-100">Grand Total</p>
                        <p class="mt-3 text-4xl font-semibold tracking-tight sm:text-5xl">
                            ₱<?php echo e(number_format($grandTotal, 2)); ?>

                        </p>
                        <p class="mt-2 text-sm text-emerald-100/90">Ready for cash, card, or mixed payment.</p>
                    </div>

                    <div class="border-b border-white/10 px-6 py-5 sm:px-8">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-4">
                                <p class="text-xs uppercase tracking-[0.22em] text-zinc-400">Customer</p>
                                <p class="mt-2 font-semibold">Walk-in</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-4">
                                <p class="text-xs uppercase tracking-[0.22em] text-zinc-400">Payment</p>
                                <p class="mt-2 font-semibold">Cash</p>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-5 sm:px-8">
                        <p class="text-xs uppercase tracking-[0.24em] text-zinc-400">Notes</p>
                        <div class="mt-3 rounded-2xl border border-dashed border-white/15 bg-white/5 p-4 text-sm text-zinc-300">
                            Verify item quantities before payment. Printing and hold actions are available below.
                        </div>
                    </div>
                </div>
            </aside>
        </div>

        <div class="grid gap-0 border-t border-emerald-200 bg-zinc-50 lg:grid-cols-[minmax(0,1fr)_auto]">
            <div class="grid gap-0 sm:grid-cols-2 xl:grid-cols-4">
                <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['variant' => 'primary','class' => 'h-18 rounded-none border-r border-zinc-200 text-base font-semibold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','class' => 'h-18 rounded-none border-r border-zinc-200 text-base font-semibold']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    Pay
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
                <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['variant' => 'ghost','class' => 'h-18 rounded-none border-t border-zinc-200 sm:border-t-0 sm:border-r text-base font-semibold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'ghost','class' => 'h-18 rounded-none border-t border-zinc-200 sm:border-t-0 sm:border-r text-base font-semibold']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    Print Receipt
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
                <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['variant' => 'ghost','class' => 'h-18 rounded-none border-t border-zinc-200 xl:border-t-0 xl:border-r text-base font-semibold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'ghost','class' => 'h-18 rounded-none border-t border-zinc-200 xl:border-t-0 xl:border-r text-base font-semibold']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    Hold Transaction
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
                <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['variant' => 'ghost','class' => 'h-18 rounded-none border-t border-zinc-200 xl:border-t-0 text-base font-semibold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'ghost','class' => 'h-18 rounded-none border-t border-zinc-200 xl:border-t-0 text-base font-semibold']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    New Sale
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
            </div>

            <div class="grid gap-0 sm:grid-cols-2 lg:min-w-[320px]">
                <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['variant' => 'subtle','class' => 'h-18 rounded-none border-t border-zinc-200 lg:border-t-0 lg:border-l text-base font-semibold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'subtle','class' => 'h-18 rounded-none border-t border-zinc-200 lg:border-t-0 lg:border-l text-base font-semibold']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    Discount
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
                <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['variant' => 'subtle','class' => 'h-18 rounded-none border-t border-zinc-200 sm:border-l lg:border-l text-base font-semibold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'subtle','class' => 'h-18 rounded-none border-t border-zinc-200 sm:border-l lg:border-l text-base font-semibold']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    More Actions
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
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Herd\coaftcorig\storage\framework/views/livewire/views/a861a413.blade.php ENDPATH**/ ?>