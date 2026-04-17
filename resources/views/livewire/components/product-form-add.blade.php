<div>
    <form wire:submit="save()" class="space-y-3">
        <div class="flex justify-end" title="exit this form">
            <flux:icon.x-mark class="w-5 h-5 hover:rotate-180 transition-all" wire:click="cancel" @click="show=false"/>
        </div>
        <p class="text-center">Add Product</p>
        <flux:input type="text" wire:model="name" placeholder="Product Name" label="Product Name"/>
        <flux:input type="number" wire:model="stock_level" placeholder="Stock Level" label="Stock Level"/>
        <flux:input type="text" wire:model="unit" placeholder="Unit" label="Unit"/>
        <flux:input type="number" wire:model="price" placeholder="Price" label="Price"/>
        <flux:input type="text" wire:model="category" placeholder="Category" label="Category"/>
        <flux:input type="text" wire:model="subcategory" placeholder="Subcategory" label="Subcategory"/>

        <div class="flex justify-between mt-3 mr-3 items-center gap-x-7">

            <button class="bg-green-300 w-24 px-3 py-1 rounded-lg cursor-pointer hover:bg-green-400 transition-all" type="submit">Add</button>

            <x-success-message :successMessage="$successMessage"/>


        </div>
    </form>
</div>
