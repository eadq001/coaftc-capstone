@php use App\QrGenerator;use BaconQrCode\Encoder\QrCode; @endphp
<div class="fixed inset-0 z-50 bg-green-300/50 flex items-center justify-center backdrop-blur-xs ease-in-out"
     wire:transition wire:cloak x-data="{ active: false, showAddStockForm:false, showDeleteForm:false }">
    <div class=" bg-white p-4 w-2xl rounded-lg relative">
        <form wire:submit="update" class="space-y-3">
            <div class="absolute top-4 right-4" title="exit this form">
                <flux:icon.x-mark class="w-5 h-5 hover:rotate-180 transition-all cursor-pointer"
                                  wire:click="$parent.cancel()"/>
            </div>
            <p class="text-center" x-text="active ? 'Edit Product' : 'View Product'"></p>
            <flux:field>
                <flux:label class="mb-0.5!">Product Name</flux:label>

               <div class="text-xs">

{{--                   for troubleshooting in the live validation of edit form  --}}
{{--                @foreach($formValuesChangeState as $key => $liveValue)--}}
{{--                    {{ $key.':' . $liveValue }}--}}
{{--                @endforeach--}}
{{--                    <br/>--}}
{{--                @foreach($liveValues as $key => $liveValue)--}}
{{--                        {{ $key. ':' .$liveValue }}--}}
{{--                    @endforeach--}}
{{--                    <br/>--}}
{{--                    @foreach($oldValues as $key => $liveValue)--}}
{{--                        {{ $key. ':' .$liveValue }}--}}
{{--                    @endforeach--}}
               </div>

                <flux:input type="text" wire:model.live.debounce.1000ms="productForm.name" placeholder="Product Name"
                            x-bind:readonly="!active"/>
                <flux:error name="productForm.name"/>
            </flux:field>
            <flux:field>
                <flux:label class="mb-0.5!">Stock Level</flux:label>
                <flux:input type="number" wire:model.live.debounce.1000ms="stockLevel"
                            placeholder="Stock Level" x-bind:readonly="!active"/>
                <flux:error name="stockLevel"/>
            </flux:field>

            <flux:field>
                <flux:label class="mb-0.5!">Unit</flux:label>
                <flux:select wire:model.live.debounce.1000ms="productForm.unit_id" class="mb-0.5!"
                             placeholder="Choose a unit" x-bind:disabled="!active">
                    @forelse($this->units as $unit)
                        <flux:select.option value="{{ $unit->id }}">{{ $unit->unit_name }}</flux:select.option>
                    @empty
                        <flux:select.option>No unit added yet</flux:select.option>
                    @endforelse
                </flux:select>
                <flux:error name="productForm.unit_id"/>
            </flux:field>

            <flux:field>
                <flux:label class="mb-0.5!">Price</flux:label>
                <flux:input type="number" wire:model.live.debounce.1000ms="price" placeholder="Price"
                            x-bind:readonly="!active"/>
                <flux:error name="price"/>
            </flux:field>
            <div class="flex items-center gap-x-2">
                <div class="flex-7">

                    <flux:field>
                        <flux:label class="mb-0.5!">Category</flux:label>
                        <flux:select wire:model.live.debounce.1000ms="productForm.category_id" class="mb-0.5!"
                                     placeholder="Choose a category" x-bind:disabled="!active">
                            @forelse($this->categories as $category)
                                <flux:select.option
                                        value="{{ $category->id }}">{{ $category->category_name }}</flux:select.option>
                            @empty
                                <flux:select.option>No category added yet</flux:select.option>
                            @endforelse
                        </flux:select>
                        <flux:error name="productForm.category_id"/>
                    </flux:field>

                    <flux:field>
                        <flux:label class="mb-0.5!">Subcategory</flux:label>
                        <flux:select wire:model.live.debounce.1000ms="productForm.subcategory_id" class="mb-0.5!"
                                     placeholder="Choose a subcategory" x-bind:disabled="!active">
                            @forelse($this->subcategories as $subcategory)
                                <flux:select.option
                                        value="{{ $subcategory->id }}">{{ $subcategory->subcategory_name }}</flux:select.option>
                            @empty
                                <flux:select.option>No subcategory added yet</flux:select.option>
                            @endforelse
                        </flux:select>
                        <flux:error name="productForm.subcategory_id"/>
                    </flux:field>

                    <flux:field>
                        <flux:label class="mb-0.5!">Class</flux:label>
                        <flux:select wire:model.live.debounce.1000ms="productForm.class" class="mb-0.5!"
                                     placeholder="Optional"
                                     x-bind:disabled="!active">
                            @foreach(App\Enums\ProductClass::cases() as $class)
                                <flux:select.option>{{ $class->value }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="productForm.class" class="mt-1!"/>
                    </flux:field>

                    <flux:field>
                        <flux:label class="mb-0.5!">Size</flux:label>
                        <flux:select wire:model.live.debounce.1000ms="productForm.size" class="mb-0.5!"
                                     placeholder="Optional"
                                     x-bind:disabled="!active">
                            @foreach(App\Enums\EggSizes::cases() as $eggSize)
                                <flux:select.option>{{ $eggSize->label() }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="productForm.size"/>
                    </flux:field>

                </div>
                <div class="justify-self-stretch">
                    <img alt="qr-code" width="190" height="190"
                         src="data:image/png;base64,{{ QrGenerator::generate($productForm->id, 190) }}">
                </div>
            </div>

            <div class="flex justify-between mt-3 mr-3 items-center gap-x-7">
                <div class="flex gap-2">

                    <button class="bg-green-300 w-24 px-3 py-1 rounded-lg cursor-pointer hover:bg-green-400 transition-all"
                            type="button"
                            @click="active = !active"
                            :class="active ? 'hidden' : 'block'"
                    >
                        Edit
                    </button>

                    <button class="bg-green-300 w-24 px-3 py-1 rounded-lg cursor-pointer hover:bg-green-400 transition-all disabled:bg-gray-300"
                            type="submit"
                            x-show="active"
                            {{$isFormValuesChange ? '' : 'disabled'}}
                    >
                        Update
                    </button>

                    <div>
                        <button class="bg-green-300 px-2 py-1 rounded-lg cursor-pointer hover:bg-green-400 transition-all disabled:bg-gray-300"
                                type="button"
                                @click="showAddStockForm=true"
                        >
                            Add Stock Only
                        </button>

                    </div>

                    <div>
                        <flux:modal.trigger name="remove-item-{{ $productForm->id }}">
                            <flux:button variant="danger" size="sm" class="cursor-pointer text-[16px]!">Remove
                            </flux:button>
                        </flux:modal.trigger>
                    </div>

                    <x-success-message successMessage="Successfully edited the product"/>
                </div>
            </div>
        </form>


        <div x-show="showAddStockForm" wire:cloak
             x-on:add-product-stock-success.window="setTimeout(()=> {showAddStockForm=false}, 2000)">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-green-300/20 backdrop-blur-xs">
                <div class="relative bg-white p-4 w-2xl rounded-lg">
                    <form wire:submit="addStock" class="space-y-3 text-sm ">
                        <div class="absolute top-0 right-0 p-2" title="exit this form">
                            <flux:icon.x-mark class="w-5 h-5 hover:rotate-180 transition-all"
                                              @click="$wire.resetStockToAdd();showAddStockForm=false"/>
                        </div>
                        <p class="text-center">Add Stock Level</p>

                        <div class="space-y-4">
                            <flux:field>
                                <flux:label class="mb-0.5!">Quantity</flux:label>
                                <flux:input type="number" min="1" wire:model.live="stockToAdd"
                                            placeholder="Quantity to add"/>
                                <flux:error name="stockToAdd"/>
                            </flux:field>

                            <flux:field>
                                <flux:label class="mb-0.5! text-zinc-900!">Current Stock Level</flux:label>
                                <flux:input type="number" value="{{ $productForm->stock_level }}" disabled/>
                                <flux:error name="productForm.stock_level"/>
                            </flux:field>

                        </div>


                        <div class="flex justify-between mt-3 mr-3 items-center gap-x-7">

                            <button class="bg-green-300 w-24 px-3 py-1 rounded-lg cursor-pointer hover:bg-green-400 transition-all"
                                    type="submit"
                            >Add
                            </button>

                            <x-success-message event="add-product-stock-success"
                                               successMessage="Added product stock level"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div>

            <flux:modal name="remove-item-{{ $productForm->id }}" class="min-w-[22rem]">
                <div class="space-y-6 text-left">
                    <div>
                        <flux:heading size="lg">Remove product?</flux:heading>
                        <flux:text class="mt-2">
                            Do you want to remove "{{ $productForm->name }}" from the products inventory
                        </flux:text>
                    </div>

                    <div class="flex gap-2">
                        <flux:spacer/>

                        <flux:modal.close>
                            <flux:button variant="ghost">No</flux:button>
                        </flux:modal.close>

                        <flux:modal.close>
                            <flux:button variant="danger"
                                         wire:click.stop="softDeleteProduct({{ $productForm->id }})">
                                Yes
                            </flux:button>
                        </flux:modal.close>
                    </div>
                </div>
            </flux:modal>

            <div wire:transition x-data="{show:false}" x-on:product-delete-error.window="show=true"
                 @keydown.enter.window="show=false;" x-show="show"
                 class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm">
                <div class="bg-white rounded-[2rem] p-10 text-center shadow-[0_32px_80px_rgba(0,0,0,0.35)] max-w-sm w-full mx-4">
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
                        <flux:icon.x-mark class="h-8 w-8 text-red-600"/>
                    </div>
                    <flux:heading size="lg" class="text-zinc-950">Product Deletion Problem</flux:heading>
                    <p class="mt-3 text-sm text-zinc-500">cannot delete a product that has a sales record already.
                    </p>
                    <button type="button" wire:click="show=false"
                            class="mt-8 w-full rounded-xl bg-zinc-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2">
                        OK
                    </button>
                </div>
            </div>

        </div>
    </div>
    <x-delete-restore-message message="Successfully deleted the product" event="product-delete-success"/>
</div>
