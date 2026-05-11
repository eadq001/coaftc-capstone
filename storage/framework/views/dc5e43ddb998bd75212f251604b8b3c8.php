<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo e($title ?? config('app.name')); ?></title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="h-screen bg-gray-100 dark:bg-gray-900 p-6">
<div class="flex items-center justify-center h-full">

<?php echo e($slot); ?>

<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH C:\Herd\coaftcorig\resources\views/layouts/blank.blade.php ENDPATH**/ ?>