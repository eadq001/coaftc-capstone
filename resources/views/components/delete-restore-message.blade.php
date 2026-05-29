@props(['message' => '', 'event' => 'add-edit-product-success'])

<div x-data="{show:false}" x-on:{{ $event }}.window="show=true;setTimeout(()=>show=false, 3000)" x-transition class="absolute top-2 right-2 " x-show="show" >
    <div class="p-4 rounded-lg border bg-white ">
        {{ $message }}
    </div>
</div>
