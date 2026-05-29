<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['message' => '', 'event' => 'add-edit-product-success']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['message' => '', 'event' => 'add-edit-product-success']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div x-data="{show:false}" x-on:<?php echo e($event); ?>.window="show=true;setTimeout(()=>show=false, 3000)" x-transition class="absolute top-2 right-2 " x-show="show" >
    <div class="p-4 rounded-lg border bg-white ">
        <?php echo e($message); ?>

    </div>
</div>
<?php /**PATH C:\Herd\coaftcorig\resources\views/components/delete-restore-message.blade.php ENDPATH**/ ?>