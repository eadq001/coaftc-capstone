@props(['successMessage' => ''])

@if($successMessage)
    <div x-data="{show:false}" x-on:add-product-success.window="show=true; setTimeout(()=> show = false, 2500)" x-show="show" class="text-sm bg-green-400 px-1 py-2 rounded-lg">
        {{ $successMessage }}
    </div>
@endif