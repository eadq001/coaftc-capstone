<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <flux:heading size="xl" level="1">Archives</flux:heading>
            <flux:text class="mt-1 text-zinc-600">You can restore the deleted products.</flux:text>
        </div>
    </div>

    <flux:card class="border border-zinc-300 rounded-lg shadow-sm bg-white overflow-hidden">
        <div class="p-6 border-zinc-200">

            <flux:table class="border! border-gray-200! px-2 transition-opacity" wire:poll.10s>
                <flux:table.columns>
                    <flux:table.column sortable>Product Name</flux:table.column>
                    <flux:table.column sortable>Price</flux:table.column>
                    <flux:table.column sortable>Stock Level</flux:table.column>
                    <flux:table.column sortable>Unit</flux:table.column>
                    <flux:table.column sortable>Category</flux:table.column>
                    <flux:table.column>Subcategory</flux:table.column>
                    <flux:table.column>Class</flux:table.column>
                    <flux:table.column>Size</flux:table.column>
                    <flux:table.column>Action</flux:table.column>
                </flux:table.columns>

                @forelse($this->products as $product)
                    <flux:table.row wire:key="{{ $product->id }}"
                    >
                        <flux:table.cell>
                            <div class="font-small text-zinc-900">{{ $product->name }}</div>
                        </flux:table.cell>

                        <flux:table.cell>
                            <span class="font-small text-zinc-900">{{ $product->price }}</span>
                        </flux:table.cell>

                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <flux:badge color="{{ $product->stock_level < 20 ? 'red' : 'green' }}" variant="subtle"
                                            class="font-small">
                                    {{ $product->stock_level }}
                                </flux:badge>

                                @if($product->stock_level < 20)
                                    <flux:icon.exclamation-circle class="w-4 h-4 text-red-600"/>
                                @else
                                    <flux:icon.check-circle class="w-4 h-4 text-green-600"/>
                                @endif
                            </div>
                        </flux:table.cell>

                        <flux:table.cell>
                            <span class="font-small! text-zinc-900">{{ $product->unit?->unit_name}}</span>
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:badge color="primary" class="font-small!"
                                        variant="subtle">{{ $product->category?->category_name }}</flux:badge>
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:badge color="zinc" class="font-small!"
                                        variant="subtle">{{ $product->subcategory?->subcategory_name }}</flux:badge>
                        </flux:table.cell>

                        <flux:table.cell>
                            @if($product->class)
                                <flux:badge color="zinc" class="font-small!"
                                            variant="subtle">{{ $product->class->value }}</flux:badge>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell>
                            @if($product->size)
                                <flux:badge color="zinc" class="font-small!"
                                            variant="subtle">{{ $product->size}}</flux:badge>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:modal.trigger name="product-{{ $product->id }}">
                                <flux:button size="sm"
                                             class="bg-green-200! hover:bg-green-400! cursor-pointer transition-all">
                                    Restore
                                </flux:button>
                            </flux:modal.trigger>

                            <livewire:components.restore-modal :id="$product->id" modelName="Product"
                                                               :itemName="$product->name"/>
                            <x-delete-restore-message message="Successfully restored the product"
                                                      event="product-restore-success"/>

                        </flux:table.cell>

                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell>

                            <div class="w-full">No results found</div>
                        </flux:table.cell>

                    </flux:table.row>
                @endforelse
            </flux:table>
            <div class="p-6 border-t border-zinc-200">
                {{ $this->products->links(data: ['scrollTo' => false ]) }}
            </div>
        </div>
    </flux:card>

    {{--    Product Categories and Subcategories grid display --}}
    <div class="mt-5 text-sm grid grid-cols-3 gap-3 rounded-lg text-zinc-900 max-md:flex-col max-md:gap-6">
        <div class="p-8 bg-white rounded-lg w-full" wire:poll.11s>
            <div class="mb-4 text-lg">Categories</div>
            <div class="grid grid-cols-1 gap-4">
                @forelse($this->categories as $category)
                    <div class="flex gap-4">
                        <p class="bg-gray-200  px-2 py-1.5 rounded-lg text-center  w-full flex-2/3"
                        >{{ $category->category_name }}</p>

                        <div>

                            <flux:modal.trigger name="category-{{ $category->id }}">
                                <flux:button size="sm"
                                             class="bg-green-200! hover:bg-green-400! cursor-pointer transition-all">
                                    Restore
                                </flux:button>
                            </flux:modal.trigger>

                            <livewire:components.restore-modal :id="$category->id" modelName="Category"
                                                               :itemName="$category->category_name"/>
                            <x-delete-restore-message message="Successfully restored the category"
                                                      event="product-category-restore-success"/>

                        </div>
                    </div>
                @empty
                    <p>No categories added yet</p>
                @endforelse
            </div>

            <div class="mt-8!">
                {{ $this->categories->links(data:['scrollTo' => false])}}
            </div>
        </div>


        <div class="p-8 bg-white rounded-lg w-full" wire:poll.12s>
            <div class="mb-4 text-lg">Subcategories</div>
            <div class="grid grid-cols-1 gap-4">
                @forelse($this->subcategories as $subcategory)
                    <div class="flex gap-4">
                        <p class="bg-gray-200 px-1 py-1.5 rounded-lg text-center cursor-pointer w-full flex-2/3"
                        >{{ $subcategory->subcategory_name }}</p>

                        <div>
                            <flux:modal.trigger name="subcategory-{{ $subcategory->id }}">
                                <flux:button size="sm"
                                             class="bg-green-200! hover:bg-green-400! cursor-pointer transition-all">
                                    Restore
                                </flux:button>
                            </flux:modal.trigger>

                            <livewire:components.restore-modal :id="$subcategory->id" modelName="Subcategory"
                                                               :itemName="$subcategory->subcategory_name"/>
                            <x-delete-restore-message message="Successfully restored the subcategory"
                                                      event="product-subcategory-restore-success"/>
                        </div>
                    </div>
                @empty
                    <p>No subcategory added yet</p>
                @endforelse
            </div>

            <div class="mt-8!">
                {{ $this->subcategories->links(data:['scrollTo' => false])}}
            </div>
        </div>

        <div class="p-8 bg-white rounded-lg w-full" wire:poll.13s>
            <div class="mb-4 text-lg">Units</div>
            <div class="grid grid-cols-1 gap-4">
                @forelse($this->units as $unit)
                    <div class="flex gap-4">
                        <p class="bg-gray-200 px-2 py-1.5 rounded-lg text-center cursor-pointer w-full flex-2/3"
                        >{{ $unit->unit_name }}</p>

                        <div>
                            <flux:modal.trigger name="unit-{{ $unit->id }}">
                                <flux:button size="sm"
                                             class="bg-green-200! hover:bg-green-400! cursor-pointer transition-all">
                                    Restore
                                </flux:button>
                            </flux:modal.trigger>

                            <livewire:components.restore-modal :id="$unit->id" modelName="Unit"
                                                               :itemName="$unit->unit_name"/>
                            <x-delete-restore-message message="Successfully restored the unit"
                                                      event="product-unit-restore-success"/>
                        </div>
                    </div>

                @empty
                    <p>No subcategory added yet</p>
                @endforelse
            </div>

            <div class="mt-8!">
                {{ $this->subcategories->links(data:['scrollTo' => false])}}
            </div>
        </div>
    </div>
</div>