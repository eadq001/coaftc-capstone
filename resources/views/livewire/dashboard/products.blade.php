<div x-data="{ show: false, showCategoryForm:false, showSubcategoryForm:false, showUnitForm:false }">
    <div class="mb-6 flex items-center justify-between">
        <div>
        <flux:heading size="xl" level="1">Products</flux:heading>
        <flux:text class="mt-1 text-zinc-600">Manage your inventory and product catalog</flux:text>
        </div>

        <div class="flex flex-wrap items-center gap-2" >
            <flux:button icon="plus" variant="primary" @click="show=true">
                Add Product
            </flux:button>

            <flux:button icon="plus" variant="primary" @click="showCategoryForm=true">
                Add Product Category
            </flux:button>

            <flux:button icon="plus" variant="primary" @click="showSubcategoryForm=true">
                Add Product Subcategory
            </flux:button>

            <flux:button icon="plus" variant="primary" @click="showUnitForm=true">
                Add Product Unit
            </flux:button>

            <div x-show="show" x-transition
                 class="fixed inset-0 z-50 flex items-center justify-center bg-green-300/50 backdrop-blur-xs"
                 wire:cloak>
                <div class="bg-white p-4 w-2xl rounded-lg">
                    <livewire:components.product-form-add @add-edit-product-success="refreshData('add')"/>
                </div>
            </div>

            <div x-show="showCategoryForm" x-transition wire:cloak class="fixed inset-0 z-50 flex items-center justify-center bg-green-300/50 backdrop-blur-xs">
                <livewire:dashboard.forms.product-category-form-add/>
            </div>

            <div x-show="showSubcategoryForm" x-transition wire:cloak class="fixed inset-0 z-50 flex items-center justify-center bg-green-300/50 backdrop-blur-xs">
                <livewire:dashboard.forms.product-subcategory-form-add/>
            </div>

            <div x-show="showUnitForm" x-transition wire:cloak class="fixed inset-0 z-50 flex items-center justify-center bg-green-300/50 backdrop-blur-xs">
                <livewire:dashboard.forms.product-unit-form-add/>
            </div>

        </div>
    </div>

    <flux:card class="border border-zinc-300 rounded-lg shadow-sm bg-white overflow-hidden">
        <div class="p-6 border-b border-zinc-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex gap-x-2 flex-1 max-w-md relative">
                    <flux:input
                            icon="magnifying-glass"
                            placeholder="Search by product name..."
                            class="w-full"
                            autocomplete="off"
                            wire:model.live.debounce.500ms="searchText"
                    />

{{--                    search results display --}}
{{--                    @if($searchText)--}}
{{--                        <div class="absolute z-50 bg-gray-50 p-2 w-full rounded-lg mt-12" wire:transition>--}}
{{--                            @forelse($searchResults as $result)--}}
{{--                                <div class="flex justify-between text-sm text-zinc-600 text-left hover:text-green-400 cursor-pointer"--}}
{{--                                     wire:key="{{ $result->id }}" wire:click="$set('productToEdit', {{ $result->id }})">--}}
{{--                                    <p>{{ $result->name }}</p>--}}
{{--                                    <p>stock: {{ $result->stock_level }}</p>--}}
{{--                                    <p>price: {{ $result->price }}</p>--}}
{{--                                </div>--}}
{{--                            @empty--}}
{{--                                <p class="text-sm">No results found</p>--}}
{{--                            @endforelse--}}
{{--                        </div>--}}
{{--                    @endif--}}

                    <button type="button"
                            class="hover:bg-green-300  border border-gray-200 rounded-lg transition-all cursor-pointer text-zinc-600 px-5"
                            wire:click="clearSearchText">
                        Clear
                    </button>

                </div>

            <flux:button variant="subtle" icon="funnel" size="sm" wire:click="toggleLowStockOnly"
                    @class([
                    'mt-2',
                   '!bg-green-400 !text-white' => $lowStockOnly,
                ])>
                View Low Stock Products
            </flux:button>

            </div>


            {{--                            <flux:badge color="zinc" variant="filled" class="px-3 py-1">--}}
            {{--                                {{ $filteredCount ?? '0' }} results--}}
            {{--                            </flux:badge>--}}
        </div>

        <flux:table x-data x-transition class="border! border-gray-200! px-2">
            <flux:table.columns>
                <flux:table.column sortable>Product Name</flux:table.column>
                <flux:table.column sortable>Price</flux:table.column>
                <flux:table.column sortable>Stock Level</flux:table.column>
                <flux:table.column sortable>Unit</flux:table.column>
                <flux:table.column sortable>Category</flux:table.column>
                <flux:table.column>Subcategory</flux:table.column>
                <flux:table.column>Class</flux:table.column>
                <flux:table.column>Size</flux:table.column>
            </flux:table.columns>

            @forelse($this->products as $product)
                <flux:table.row wire:key="{{ $product->id }}" class="cursor-pointer"
                                wire:click="$set('productToEdit', {{ $product->id }} )" title="click to edit"
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
    </flux:card>

    {{--    Product Categories and Subcategories grid display --}}
    <div class="mt-5 text-sm grid grid-cols-2 gap-3 rounded-lg text-zinc-900 max-md:flex-col max-md:gap-6">
        <div class="p-8 bg-white rounded-lg w-full">
            <div class="mb-4 text-lg">Categories</div>
            <div class="grid grid-cols-3 gap-4">
                @forelse($this->categories as $category)
                    <p class="bg-green-200  px-2 py-1.5 rounded-lg text-center cursor-pointer"
                       wire:click="$set('categoryToEdit', {{ $category->id }})"
                       title="click to edit">{{ $category->category_name }}</p>
                @empty
                    <p>No categories added yet</p>
                @endforelse
            </div>

            <div class="mt-8!">
                {{ $this->categories->links(data:['scrollTo' => false])}}
            </div>
        </div>


        <div class="p-8 bg-white rounded-lg w-full">
            <div class="mb-4 text-lg">Subcategories</div>
            <div class="grid grid-cols-3 gap-4">
                @forelse($this->subcategories as $subcategory)
                    <p class="bg-gray-200 px-1 py-1.5 rounded-lg text-center cursor-pointer"
                       wire:click="$set('subcategoryToEdit', {{ $subcategory->id }})"
                       title="click to edit">{{ $subcategory->subcategory_name }}</p>
                @empty
                    <p>No subcategory added yet</p>
                @endforelse
            </div>

            <div class="mt-8!">
                {{ $this->subcategories->links(data:['scrollTo' => false])}}
            </div>
        </div>

        <div class="p-8 bg-white rounded-lg w-full">
            <div class="mb-4 text-lg">Units</div>
            <div class="grid grid-cols-3 gap-4">
                @forelse($this->units as $unit)
                    <p class="bg-gray-200 px-2 py-1.5 rounded-lg text-center cursor-pointer"
                       wire:click="$set('unitToEdit', {{ $unit->id }})" title="click to edit">{{ $unit->unit_name }}</p>
                @empty
                    <p>No subcategory added yet</p>
                @endforelse
            </div>

            <div class="mt-8!">
                {{ $this->subcategories->links(data:['scrollTo' => false])}}
            </div>
        </div>
    </div>

    @if($subcategoryToEdit)
        <livewire:dashboard.forms.product-subcategory-form-edit :subcategoryToEdit="$subcategoryToEdit"
                                                                wire:key="edit-subcategory-{{ $subcategoryToEdit }}"/>
    @endif

    @if($categoryToEdit)
        <livewire:dashboard.forms.product-category-form-edit :categoryToEdit="$categoryToEdit"
                                                             wire:key="edit-category-{{ $categoryToEdit }}"/>
    @endif

    @if($unitToEdit)
        <livewire:dashboard.forms.product-unit-form-edit :unitToEdit="$unitToEdit"
                                                         wire:key="edit-unit-{{ $unitToEdit }}"/>
    @endif

    @if($productToEdit)
        <livewire:components.product-form-edit :productToEdit="$productToEdit"
                                               wire:key="edit-product-{{ $productToEdit }}"
                                               @add-edit-product-success="refreshData; $refresh"/>
    @endif

</div>
