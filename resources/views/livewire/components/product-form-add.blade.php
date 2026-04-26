<div class="relative">
    <form wire:submit="save" class="space-y-3 text-sm">
        <div class="absolute top-0 right-0" title="exit this form">
            <flux:icon.x-mark class="w-5 h-5 hover:rotate-180 transition-all" wire:click="cancel" @click="show=false"/>
        </div>
        <p class="text-center">Add Product</p>
        <flux:field>
            <flux:label class="mb-0.5!">Product Name</flux:label>
            <flux:input type="text" wire:model.live.debounce.1000ms="productForm.name" placeholder="Product Name"/>
            <flux:error name="productForm.name"/>
        </flux:field>
        <flux:field>
            <flux:label class="mb-0.5!">Stock Level</flux:label>
            <flux:input type="number" wire:model.live.debounce.1000ms="productForm.stock_level" placeholder="Stock Level"/>
            <flux:error name="productForm.stock_level"/>
        </flux:field>
        <flux:field>
            <flux:label class="mb-0.5!">Unit</flux:label>
            <flux:input type="text" wire:model.live.debounce.1000ms="productForm.unit" placeholder="Unit"/>
            <flux:error name="productForm.unit"/>
        </flux:field>
        <flux:field>
            <flux:label class="mb-0.5!">Price</flux:label>
            <flux:input type="number" wire:model.live.debounce.1000ms="productForm.price" placeholder="Price"/>
            <flux:error name="productForm.price"/>
        </flux:field>



        <flux:field>
            <flux:label class="mb-0.5!">Category</flux:label>
        <flux:select wire:model.live.debounce.1000ms="productForm.category" class="mb-0.5!"  placeholder="Choose a category">
            @forelse($this->categories as $category)
            <flux:select.option>{{ $category->category_name }}</flux:select.option>
            @empty
                <flux:select.option>No category added yet</flux:select.option>
            @endforelse
        </flux:select>
            <flux:error name="productForm.category"/>
        </flux:field>


        <flux:field>
            <flux:label class="mb-0.5!">Subcategory</flux:label>
        <flux:select wire:model.live.debounce.1000ms="productForm.subcategory" class="mb-0.5!"  placeholder="Choose a subcategory">
            @forelse($this->subcategories as $subcategory)
            <flux:select.option>{{ $subcategory->subcategory_name }}</flux:select.option>
            @empty
                <flux:select.option>No subcategory added yet</flux:select.option>
            @endforelse
        </flux:select>
            <flux:error name="productForm.subcategory"/>
        </flux:field>

{{--        <flux:field>--}}
{{--            <flux:label class="mb-0.5!">Category</flux:label>--}}
{{--            <flux:input type="text" wire:model.live.debounce.1000ms="productForm.category" placeholder="Category"/>--}}
{{--            <flux:error name="productForm.category"/>--}}
{{--        </flux:field>--}}

{{--        <flux:field>--}}
{{--            <flux:label class="mb-0.5!">Subcategory</flux:label>--}}
{{--            <flux:input type="text" wire:model.live.debounce.1000ms="productForm.subcategory" placeholder="Subcategory"/>--}}
{{--            <flux:error name="productForm.subcategory"/>--}}
{{--        </flux:field>--}}

        <div class="flex justify-between mt-3 mr-3 items-center gap-x-7">

            <button class="bg-green-300 w-24 px-3 py-1 rounded-lg cursor-pointer hover:bg-green-400 transition-all" type="submit">Add</button>

            <x-success-message successMessage="{{$productForm->successMessage ?? ''}}"/>

        </div>
    </form>
</div>
