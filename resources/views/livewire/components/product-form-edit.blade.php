<div class="fixed inset-0 z-50 bg-green-300/50 flex items-center justify-center backdrop-blur-xs ease-in-out"  wire:transition wire:cloak>
    <div class=" bg-white p-4 w-2xl rounded-lg">
    <form wire:submit="save()" class="space-y-3">
        <div class="flex justify-end" title="exit this form">
            <flux:icon.x-mark class="w-5 h-5 hover:rotate-180 transition-all" wire:click="$parent.cancel()"/>
        </div>
        <p class="text-center">View Product</p>
        <flux:input type="text" wire:model="name" placeholder="Product Name" label="Product Name"/>
        <flux:input type="number" wire:model="stock_level" placeholder="Stock Level" label="Stock Level"/>
        <flux:input type="text" wire:model="unit" placeholder="Unit" label="Unit"/>
        <flux:input type="number" wire:model="price" placeholder="Price" label="Price"/>
        <flux:input type="text" wire:model="category" placeholder="Category" label="Category"/>
        <flux:input type="text" wire:model="subcategory" placeholder="Subcategory" label="Subcategory"/>

        <div class="flex justify-between mt-3 mr-3 items-center gap-x-7">

            <button class="bg-green-300 w-24 px-3 py-1 rounded-lg cursor-pointer hover:bg-green-400 transition-all" type="submit">Add</button>

            <x-success-message successMessage="Successfully edited the product"/>

        </div>
    </form>
    </div>
</div>
