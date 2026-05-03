@php use App\QrGenerator;use BaconQrCode\Encoder\QrCode; @endphp
<div class="fixed inset-0 z-50 bg-green-300/50 flex items-center justify-center backdrop-blur-xs ease-in-out"
     wire:transition wire:cloak x-data="{ active: false }">
    <div class=" bg-white p-4 w-2xl rounded-lg relative">
        <form wire:submit="update" class="space-y-3">
            <div class="absolute top-4 right-4" title="exit this form">
                <flux:icon.x-mark class="w-5 h-5 hover:rotate-180 transition-all cursor-pointer" wire:click="$parent.cancel()"/>
            </div>
            <p class="text-center" x-text="active ? 'Edit Product' : 'View Product'"></p>
            <flux:field>
                <flux:label class="mb-0.5!">Product Name</flux:label>
                <flux:input type="text" wire:model="productForm.name" placeholder="Product Name"
                            x-bind:readonly="!active"/>
                <flux:error name="productForm.name"/>
            </flux:field>
            <flux:field>
                <flux:label class="mb-0.5!">Stock Level</flux:label>
                <flux:input type="number" wire:model="productForm.stock_level"
                            placeholder="Stock Level" x-bind:readonly="!active"/>
                <flux:error name="productForm.stock_level"/>
            </flux:field>

            <flux:field>
                <flux:label class="mb-0.5!">Unit</flux:label>
                <flux:select wire:model.live.debounce.1000ms="productForm.unit_id" class="mb-0.5!"  placeholder="Choose a unit" x-bind:disabled="!active">
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
                <flux:input type="number" wire:model="productForm.price" placeholder="Price"
                            x-bind:readonly="!active"/>
                <flux:error name="productForm.price"/>
            </flux:field>
            <div class="flex items-center gap-x-2">
                <div class="flex-7">


                    {{--                    <flux:field>--}}
                    {{--                        <flux:label class="mb-0.5!">Category</flux:label>--}}
                    {{--                        <flux:input type="text" wire:model="productForm.category" placeholder="Category"--}}
                    {{--                                    x-bind:readonly="!active"/>--}}
                    {{--                        <flux:error name="productForm.category"/>--}}
                    {{--                    </flux:field>--}}
                    {{--                    --}}

                    <flux:field>
                        <flux:label class="mb-0.5!">Category</flux:label>
                        <flux:select wire:model.live.debounce.1000ms="productForm.category_id" class="mb-0.5!"
                                     placeholder="Choose a category" x-bind:disabled="!active">
                            @forelse($this->categories as $category)
                                <flux:select.option value="{{ $category->id }}">{{ $category->category_name }}</flux:select.option>
                            @empty
                                <flux:select.option>No category added yet</flux:select.option>
                            @endforelse
                        </flux:select>
                        <flux:error name="productForm.category_id"/>
                    </flux:field>

                    <flux:field>
                        <flux:label class="mb-0.5!">Subcategory</flux:label>
                        <flux:select wire:model="productForm.subcategory_id" class="mb-0.5!"
                                     placeholder="Choose a subcategory" x-bind:disabled="!active">
                            @forelse($this->subcategories as $subcategory)
                                <flux:select.option value="{{ $subcategory->id }}">{{ $subcategory->subcategory_name }}</flux:select.option>
                            @empty
                                <flux:select.option>No subcategory added yet</flux:select.option>
                            @endforelse
                        </flux:select>
                        <flux:error name="productForm.subcategory_id"/>
                    </flux:field>

                    <flux:field>
                        <flux:label class="mb-0.5!">Class</flux:label>
                        <flux:select wire:model="productForm.class" class="mb-0.5!" placeholder="Optional"  x-bind:disabled="!active">
                            @foreach(App\Enums\ProductClass::cases() as $class)
                                <flux:select.option>{{ $class->value }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="productForm.class" class="mt-1!"/>
                    </flux:field>

                    <flux:field>
                        <flux:label class="mb-0.5!">Size</flux:label>
                        <flux:select wire:model="productForm.size" class="mb-0.5!"
                                     placeholder="Optional" x-bind:disabled="!active">
                            @foreach(App\Enums\EggSizes::cases() as $eggSize)
                                <flux:select.option>{{ $eggSize->label() }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="productForm.size"/>
                    </flux:field>

                </div>
                <div class="justify-self-stretch">
                    <img alt="qr-code" width="190" height="190" src="data:image/png;base64,{{ QrGenerator::generate($productForm->id, 190) }}">
                </div>
            </div>

            <div class="flex justify-between mt-3 mr-3 items-center gap-x-7">
                <div>

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
                            disabled
                            wire:dirty.attr.remove="disabled"
                    >
                        Update
                    </button>
                </div>
                <x-success-message successMessage="Successfully edited the product"/>
            </div>
        </form>
    </div>
</div>
