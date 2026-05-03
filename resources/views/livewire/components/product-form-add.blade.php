<div class="relative">
    <form wire:submit="save" class="space-y-3 text-sm">
        <div class="absolute top-0 right-0" title="exit this form">
            <flux:icon.x-mark class="w-5 h-5 hover:rotate-180 transition-all" wire:click="cancel" @click="show=false"/>
        </div>
        <p class="text-center">Add Product</p>
        <flux:field>
            <flux:label class="mb-0.5!">Product Name</flux:label>
            <flux:input type="text" wire:model.live.debounce.1000ms="productForm.name" placeholder="Product Name"/>
            <flux:error name="productForm.name" class="mt-1!"/>
        </flux:field>
        <flux:field>
            <flux:label class="mb-0.5!">Stock Level</flux:label>
            <flux:input type="number" wire:model.live.debounce.1000ms="productForm.stock_level" placeholder="Stock Level"/>
            <flux:error name="productForm.stock_level" class="mt-1!"/>
        </flux:field>

        <flux:field>
            <flux:label class="mb-0.5!">Unit</flux:label>
            <flux:select wire:model.live.debounce.1000ms="productForm.unit_id" class="mb-0.5!"  placeholder="Choose a unit">
                @forelse($this->units as $unit)
                    <flux:select.option value="{{ $unit->id }}">{{ $unit->unit_name }}</flux:select.option>
                @empty
                    <flux:select.option>No unit added yet</flux:select.option>
                @endforelse
            </flux:select>
            <flux:error name="productForm.unit_id" class="mt-1!"/>
        </flux:field>

        <flux:field>
            <flux:label class="mb-0.5!">Price</flux:label>
            <flux:input type="number" wire:model="productForm.price" placeholder="Price"/>
            <flux:error name="productForm.price" class="mt-1!"/>
        </flux:field>



        <flux:field>
            <flux:label class="mb-0.5!">Category</flux:label>
        <flux:select wire:model.live.debounce.1000ms="productForm.category_id" class="mb-0.5!"  placeholder="Choose a category">
        @forelse($this->categories as $category)
            <flux:select.option value="{{ $category->id }}">{{ $category->category_name }}</flux:select.option>
            @empty
                <flux:select.option>No category added yet</flux:select.option>
            @endforelse
        </flux:select>
            <flux:error name="productForm.category_id" class="mt-1!"/>
        </flux:field>


        <flux:field>
            <flux:label class="mb-0.5!">Subcategory</flux:label>
        <flux:select wire:model.live.debounce.1000ms="productForm.subcategory_id" class="mb-0.5!"  placeholder="Choose a subcategory">
            @forelse($this->subcategories as $subcategory)
            <flux:select.option value="{{ $subcategory->id }}">{{ $subcategory->subcategory_name }}</flux:select.option>
            @empty
                <flux:select.option>No subcategory added yet</flux:select.option>
            @endforelse
        </flux:select>
            <flux:error name="productForm.subcategory_id" class="mt-1!"/>
        </flux:field>

        <flux:field>
            <flux:label class="mb-0.5!">Class</flux:label>
        <flux:select wire:model.live.debounce.1000ms="productForm.class" class="mb-0.5!" placeholder="Optional"  >
            @foreach(App\Enums\ProductClass::cases() as $class)
            <flux:select.option>{{ $class->value }}</flux:select.option>
            @endforeach
        </flux:select>
            <flux:error name="productForm.class" class="mt-1!"/>
        </flux:field>

        <flux:field>
            <flux:label class="mb-0.5!">Size</flux:label>
        <flux:select wire:model.live.debounce.1000ms="productForm.size" class="mb-0.5!" placeholder="Optional"  >
            @foreach(App\Enums\EggSizes::cases() as $eggSize)
            <flux:select.option>{{ $eggSize->label() }}</flux:select.option>
            @endforeach
        </flux:select>
            <flux:error name="productForm.size" class="mt-1!"/>
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
