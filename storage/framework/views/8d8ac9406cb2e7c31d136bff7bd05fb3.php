<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?php echo e($title ?? config('app.name')); ?></title>

        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

        <?php echo app('flux')->fluxAppearance(); ?>

        <script>
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body>

    <div class="min-h-screen bg-white dark:bg-zinc-900 antialiased">
        <style>
            [data-flux-sidebar] {
                transition: width 300ms cubic-bezier(0.4, 0, 0.2, 1),
                max-width 300ms cubic-bezier(0.4, 0, 0.2, 1),
                min-width 300ms cubic-bezier(0.4, 0, 0.2, 1);
            }

            [data-flux-sidebar] > * {
                transition: opacity 200ms ease-out, transform 200ms ease-out;
            }

            [data-flux-sidebar-collapsed-desktop] [data-flux-sidebar-nav-item-label],
            [data-flux-sidebar-collapsed-desktop] [data-flux-sidebar-heading],
            [data-flux-sidebar-collapsed-desktop] [data-flux-sidebar-group-expand-icon] {
                opacity: 0;
                transform: translateX(-8px);
                pointer-events: none;
            }

            [data-flux-sidebar-collapsed-desktop] [data-flux-sidebar-brand-name],
            [data-flux-sidebar-collapsed-desktop] [data-flux-sidebar-profile-name] {
                opacity: 0;
                pointer-events: none;
            }
        </style>

        <?php if (isset($component)) { $__componentOriginal17e56bc23bb0192e474b351c4358d446 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal17e56bc23bb0192e474b351c4358d446 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.index','data' => ['sticky' => true,'collapsible' => true,'class' => 'bg-white dark:bg-zinc-900 border-r border-zinc-300 dark:border-zinc-700 text-zinc-800 dark:text-zinc-200']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['sticky' => true,'collapsible' => true,'class' => 'bg-white dark:bg-zinc-900 border-r border-zinc-300 dark:border-zinc-700 text-zinc-800 dark:text-zinc-200']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


            <?php if (isset($component)) { $__componentOriginal837232b594bf97def5cd04bcaa1b6bb0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal837232b594bf97def5cd04bcaa1b6bb0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.header','data' => ['class' => 'border-b border-zinc-300 dark:border-zinc-700']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'border-b border-zinc-300 dark:border-zinc-700']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <?php if (isset($component)) { $__componentOriginalc383431c42a29d2b8e3c3717e2d2226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc383431c42a29d2b8e3c3717e2d2226b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.brand','data' => ['href' => '#','disabled' => true,'name' => 'Coaftc Dashboard','class' => 'h-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.brand'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '#','disabled' => true,'name' => 'Coaftc Dashboard','class' => 'h-full']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    <img src="<?php echo e(asset('images/coaftc.png')); ?>" class="w-full h-full">







                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc383431c42a29d2b8e3c3717e2d2226b)): ?>
<?php $attributes = $__attributesOriginalc383431c42a29d2b8e3c3717e2d2226b; ?>
<?php unset($__attributesOriginalc383431c42a29d2b8e3c3717e2d2226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc383431c42a29d2b8e3c3717e2d2226b)): ?>
<?php $component = $__componentOriginalc383431c42a29d2b8e3c3717e2d2226b; ?>
<?php unset($__componentOriginalc383431c42a29d2b8e3c3717e2d2226b); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal27b151307b59a43acdad47db3fb6fbd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal27b151307b59a43acdad47db3fb6fbd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.collapse','data' => ['class' => 'hidden lg:flex']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.collapse'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'hidden lg:flex']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal27b151307b59a43acdad47db3fb6fbd0)): ?>
<?php $attributes = $__attributesOriginal27b151307b59a43acdad47db3fb6fbd0; ?>
<?php unset($__attributesOriginal27b151307b59a43acdad47db3fb6fbd0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal27b151307b59a43acdad47db3fb6fbd0)): ?>
<?php $component = $__componentOriginal27b151307b59a43acdad47db3fb6fbd0; ?>
<?php unset($__componentOriginal27b151307b59a43acdad47db3fb6fbd0); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal837232b594bf97def5cd04bcaa1b6bb0)): ?>
<?php $attributes = $__attributesOriginal837232b594bf97def5cd04bcaa1b6bb0; ?>
<?php unset($__attributesOriginal837232b594bf97def5cd04bcaa1b6bb0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal837232b594bf97def5cd04bcaa1b6bb0)): ?>
<?php $component = $__componentOriginal837232b594bf97def5cd04bcaa1b6bb0; ?>
<?php unset($__componentOriginal837232b594bf97def5cd04bcaa1b6bb0); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal061367e9976089f15083f05bd78a70e4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal061367e9976089f15083f05bd78a70e4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.nav','data' => ['class' => 'text-zinc-800 dark:text-zinc-200']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.nav'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-zinc-800 dark:text-zinc-200']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <?php if (isset($component)) { $__componentOriginalfe86969babb72517ecf97426e7c9330d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfe86969babb72517ecf97426e7c9330d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.item','data' => ['icon' => 'home','wire:navigate' => true,'href' => ''.e(route('dashboard.home')).'','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'home','wire:navigate' => true,'href' => ''.e(route('dashboard.home')).'','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Home <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $attributes = $__attributesOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__attributesOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $component = $__componentOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__componentOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalfe86969babb72517ecf97426e7c9330d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfe86969babb72517ecf97426e7c9330d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.item','data' => ['icon' => 'chart-bar-square','href' => '#','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'chart-bar-square','href' => '#','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Analytics <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $attributes = $__attributesOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__attributesOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $component = $__componentOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__componentOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalfe86969babb72517ecf97426e7c9330d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfe86969babb72517ecf97426e7c9330d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.item','data' => ['icon' => 'cube','wire:navigate' => true,'href' => ''.e(route('dashboard.products')).'','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'cube','wire:navigate' => true,'href' => ''.e(route('dashboard.products')).'','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Products <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $attributes = $__attributesOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__attributesOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $component = $__componentOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__componentOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalfe86969babb72517ecf97426e7c9330d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfe86969babb72517ecf97426e7c9330d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.item','data' => ['icon' => 'currency-dollar','href' => '#','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'currency-dollar','href' => '#','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Sales <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $attributes = $__attributesOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__attributesOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $component = $__componentOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__componentOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalfe86969babb72517ecf97426e7c9330d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfe86969babb72517ecf97426e7c9330d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.item','data' => ['icon' => 'document-text','href' => '#','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'document-text','href' => '#','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Reports <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $attributes = $__attributesOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__attributesOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $component = $__componentOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__componentOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalfe86969babb72517ecf97426e7c9330d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfe86969babb72517ecf97426e7c9330d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.item','data' => ['icon' => 'user','href' => '#','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'user','href' => '#','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Users <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $attributes = $__attributesOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__attributesOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $component = $__componentOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__componentOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalfe86969babb72517ecf97426e7c9330d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfe86969babb72517ecf97426e7c9330d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.item','data' => ['icon' => 'user-group','href' => '#','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'user-group','href' => '#','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-green-300! dark:hover:bg-primary hover:text-white']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Employees <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $attributes = $__attributesOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__attributesOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $component = $__componentOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__componentOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal31257750338e37e989bcfa8eb3c88bb1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31257750338e37e989bcfa8eb3c88bb1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.group','data' => ['expandable' => true,'icon' => 'cog-6-tooth','heading' => 'Settings','class' => 'grid text-zinc-800 dark:text-zinc-200 [&_[data-flux-sidebar-heading]]:text-primary dark:[&_[data-flux-sidebar-heading]]:text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['expandable' => true,'icon' => 'cog-6-tooth','heading' => 'Settings','class' => 'grid text-zinc-800 dark:text-zinc-200 [&_[data-flux-sidebar-heading]]:text-primary dark:[&_[data-flux-sidebar-heading]]:text-primary']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    <?php if (isset($component)) { $__componentOriginalfe86969babb72517ecf97426e7c9330d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfe86969babb72517ecf97426e7c9330d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.item','data' => ['href' => '','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-primary dark:hover:bg-primary hover:text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-primary dark:hover:bg-primary hover:text-white']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Account <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $attributes = $__attributesOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__attributesOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $component = $__componentOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__componentOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalfe86969babb72517ecf97426e7c9330d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfe86969babb72517ecf97426e7c9330d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.item','data' => ['href' => '#','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-primary dark:hover:bg-primary hover:text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '#','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-primary dark:hover:bg-primary hover:text-white']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Notifications <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $attributes = $__attributesOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__attributesOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $component = $__componentOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__componentOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalfe86969babb72517ecf97426e7c9330d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfe86969babb72517ecf97426e7c9330d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.item','data' => ['href' => '#','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-primary dark:hover:bg-primary hover:text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '#','class' => 'text-zinc-800 dark:text-zinc-200 hover:bg-primary dark:hover:bg-primary hover:text-white']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Security <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $attributes = $__attributesOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__attributesOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfe86969babb72517ecf97426e7c9330d)): ?>
<?php $component = $__componentOriginalfe86969babb72517ecf97426e7c9330d; ?>
<?php unset($__componentOriginalfe86969babb72517ecf97426e7c9330d); ?>
<?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal31257750338e37e989bcfa8eb3c88bb1)): ?>
<?php $attributes = $__attributesOriginal31257750338e37e989bcfa8eb3c88bb1; ?>
<?php unset($__attributesOriginal31257750338e37e989bcfa8eb3c88bb1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal31257750338e37e989bcfa8eb3c88bb1)): ?>
<?php $component = $__componentOriginal31257750338e37e989bcfa8eb3c88bb1; ?>
<?php unset($__componentOriginal31257750338e37e989bcfa8eb3c88bb1); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal061367e9976089f15083f05bd78a70e4)): ?>
<?php $attributes = $__attributesOriginal061367e9976089f15083f05bd78a70e4; ?>
<?php unset($__attributesOriginal061367e9976089f15083f05bd78a70e4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal061367e9976089f15083f05bd78a70e4)): ?>
<?php $component = $__componentOriginal061367e9976089f15083f05bd78a70e4; ?>
<?php unset($__componentOriginal061367e9976089f15083f05bd78a70e4); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal2a3a5d5177f25cbe24fe83d2c80a8bc3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2a3a5d5177f25cbe24fe83d2c80a8bc3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.spacer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.spacer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2a3a5d5177f25cbe24fe83d2c80a8bc3)): ?>
<?php $attributes = $__attributesOriginal2a3a5d5177f25cbe24fe83d2c80a8bc3; ?>
<?php unset($__attributesOriginal2a3a5d5177f25cbe24fe83d2c80a8bc3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2a3a5d5177f25cbe24fe83d2c80a8bc3)): ?>
<?php $component = $__componentOriginal2a3a5d5177f25cbe24fe83d2c80a8bc3; ?>
<?php unset($__componentOriginal2a3a5d5177f25cbe24fe83d2c80a8bc3); ?>
<?php endif; ?>


            <?php if (isset($component)) { $__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::dropdown','data' => ['position' => 'top','align' => 'start','class' => 'max-lg:hidden']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['position' => 'top','align' => 'start','class' => 'max-lg:hidden']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <?php if (isset($component)) { $__componentOriginal78cc0fd2c5379f598cd86bbc76981fe3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal78cc0fd2c5379f598cd86bbc76981fe3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.profile','data' => ['name' => ''.e(auth()->user()->name).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.profile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => ''.e(auth()->user()->name).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal78cc0fd2c5379f598cd86bbc76981fe3)): ?>
<?php $attributes = $__attributesOriginal78cc0fd2c5379f598cd86bbc76981fe3; ?>
<?php unset($__attributesOriginal78cc0fd2c5379f598cd86bbc76981fe3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal78cc0fd2c5379f598cd86bbc76981fe3)): ?>
<?php $component = $__componentOriginal78cc0fd2c5379f598cd86bbc76981fe3; ?>
<?php unset($__componentOriginal78cc0fd2c5379f598cd86bbc76981fe3); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    <?php if (isset($component)) { $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.radio.group','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.radio.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <?php if (isset($component)) { $__componentOriginal763facc893e4de8b6e8106f07c0fccb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal763facc893e4de8b6e8106f07c0fccb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.radio.index','data' => ['checked' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.radio'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['checked' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e(auth()->user()->name); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal763facc893e4de8b6e8106f07c0fccb4)): ?>
<?php $attributes = $__attributesOriginal763facc893e4de8b6e8106f07c0fccb4; ?>
<?php unset($__attributesOriginal763facc893e4de8b6e8106f07c0fccb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal763facc893e4de8b6e8106f07c0fccb4)): ?>
<?php $component = $__componentOriginal763facc893e4de8b6e8106f07c0fccb4; ?>
<?php unset($__componentOriginal763facc893e4de8b6e8106f07c0fccb4); ?>
<?php endif; ?>
                        
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $attributes = $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $component = $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginald5e1eb3ae521062f8474178ba08933ca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald5e1eb3ae521062f8474178ba08933ca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.separator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $attributes = $__attributesOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $component = $__componentOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__componentOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>

                    <form action="/logout" method="POST" class="w-full">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['class' => 'border-none! cursor-pointer hover:text-white-200 text-sm','icon' => 'arrow-right-start-on-rectangle','type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'border-none! cursor-pointer hover:text-white-200 text-sm','icon' => 'arrow-right-start-on-rectangle','type' => 'submit']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Logout <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
                    </form>
                    <?php if (isset($component)) { $__componentOriginald5e1eb3ae521062f8474178ba08933ca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald5e1eb3ae521062f8474178ba08933ca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.separator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $attributes = $__attributesOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $component = $__componentOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__componentOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>


                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a)): ?>
<?php $attributes = $__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a; ?>
<?php unset($__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a)): ?>
<?php $component = $__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a; ?>
<?php unset($__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888)): ?>
<?php $attributes = $__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888; ?>
<?php unset($__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888)): ?>
<?php $component = $__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888; ?>
<?php unset($__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal17e56bc23bb0192e474b351c4358d446)): ?>
<?php $attributes = $__attributesOriginal17e56bc23bb0192e474b351c4358d446; ?>
<?php unset($__attributesOriginal17e56bc23bb0192e474b351c4358d446); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal17e56bc23bb0192e474b351c4358d446)): ?>
<?php $component = $__componentOriginal17e56bc23bb0192e474b351c4358d446; ?>
<?php unset($__componentOriginal17e56bc23bb0192e474b351c4358d446); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal95c5505ccad18880318521d2bba3eac7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal95c5505ccad18880318521d2bba3eac7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::main','data' => ['class' => '!bg-green-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::main'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!bg-green-300']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <?php echo e($slot); ?>

         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal95c5505ccad18880318521d2bba3eac7)): ?>
<?php $attributes = $__attributesOriginal95c5505ccad18880318521d2bba3eac7; ?>
<?php unset($__attributesOriginal95c5505ccad18880318521d2bba3eac7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal95c5505ccad18880318521d2bba3eac7)): ?>
<?php $component = $__componentOriginal95c5505ccad18880318521d2bba3eac7; ?>
<?php unset($__componentOriginal95c5505ccad18880318521d2bba3eac7); ?>
<?php endif; ?>
    </div>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php app('livewire')->forceAssetInjection(); ?>
<?php echo app('flux')->scripts(); ?>


    </body>
</html>
<?php /**PATH C:\Herd\coaftcorig\resources\views/layouts/dashboard.blade.php ENDPATH**/ ?>