@props(['successMessage' => '', 'event' => 'add-edit-product-success'])

    <div x-data="{show:false}" x-on:{{ $event }}.window="show=true; setTimeout(()=> show = false, 2500)" x-show="show"
         class="text-xs bg-green-400 px-3 py-1.5 rounded-lg flex gap-x-2 items-center"
         x-transition.enter.duration.200ms x-transition.leave.duration.400ms
         >
        <span><flux:icon.check class="size-4"/> </span>
        {{ $successMessage }}
    </div>
