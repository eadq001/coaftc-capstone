<div>
    <div class="mb-6">
        <flux:heading size="xl" level="1">Products</flux:heading>
        <flux:text class="mt-1 text-zinc-600">Manage your inventory and product catalog</flux:text>
    </div>

    <div class="grid gap-6 lg:grid-cols-3 mb-8">
        <flux:card class="border border-zinc-300 border-t-4 border-t-primary rounded-lg shadow-sm bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="sm" class="text-zinc-800">Total Products</flux:heading>
                    <flux:text class="text-2xl font-semibold mt-2 text-primary">{{ $totalProducts ?? '0' }}</flux:text>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <flux:icon.cube class="w-6 h-6 text-primary"/>
                </div>
            </div>
        </flux:card>

        <flux:card class="border border-zinc-300 border-t-4 border-t-red-500 rounded-lg shadow-sm bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="sm" class="text-zinc-800">Low Stock Items</flux:heading>
                    <flux:text class="text-2xl font-semibold mt-2 text-red-600">{{ $lowStockItems ?? '0' }}</flux:text>
                </div>
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <flux:icon.exclamation-triangle class="w-6 h-6 text-red-600"/>
                </div>
            </div>
        </flux:card>

        <flux:card class="border border-zinc-300 border-t-4 border-t-primary rounded-lg shadow-sm bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="sm" class="text-zinc-800">Total Inventory Value</flux:heading>
                    <flux:text class="text-2xl font-semibold mt-2 text-primary">
                        <span class="text-2xl text-primary">&#8369; {{ $totalInventoryValue }}</span>
                    </flux:text>
                </div>

                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <div class="text-2xl text-primary flex justify-center items-center">&#8369;</div>
                </div>
            </div>
        </flux:card>
    </div>

    <flux:card class="border border-zinc-300 rounded-lg shadow-sm bg-white overflow-hidden">
        <div class="p-6 border-b border-zinc-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex gap-x-2 flex-1 max-w-md relative">
                    <flux:input
                            icon="magnifying-glass"
                            placeholder="Search by name, QR code, or category..."
                            class="w-full"
                            wire:model.live.debounce.500ms="searchText"
                    />

                    @if($searchText)
                        <div class="absolute z-50 bg-gray-100 p-2 w-full rounded-lg mt-12">
                            @forelse($searchResults as $result)
                                <div class="flex justify-between text-sm text-zinc-600 text-left">
                                    <p>{{ $result->name }}</p>
                                    <p>stock: {{ $result->stock_level }}</p>
                                    <p>price: {{ $result->price }}</p>
                                </div>
                            @empty
                                <p class="text-sm">No results found</p>
                            @endforelse
                        </div>
                    @endif

                    <button type="button"
                            class="hover:bg-green-300  border border-gray-200 rounded-lg transition-all cursor-pointer text-zinc-600 px-5"
                            wire:click="clearSearchText">
                        Clear
                    </button>

                </div>

                <div class="flex flex-wrap items-center gap-2" x-data="{ show: false }">
                    <flux:button icon="plus" variant="primary" @click="show=true">
                        Add Product
                    </flux:button>

                    <div x-show="show" x-transition.duration.300ms
                         class="fixed inset-0 z-50 flex items-center justify-center bg-green-300/50 backdrop-blur-xs">
                        <div class="bg-white p-4 w-2xl rounded-lg">

                            <livewire:components.product-form-add/>

                        </div>
                    </div>
                </div>
            </div>

{{--                -----for categories----  --}}
{{--            <div class="flex flex-wrap items-center gap-3 mt-4">--}}
{{--                <flux:select placeholder="All Categories" class="min-w-[160px]">--}}
{{--                    <flux:select.option>Electronics</flux:select.option>--}}
{{--                    <flux:select.option>Clothing</flux:select.option>--}}
{{--                    <flux:select.option>Food & Beverages</flux:select.option>--}}
{{--                    <flux:select.option>Office Supplies</flux:select.option>--}}
{{--                </flux:select>--}}

{{--                <flux:select placeholder="All Classifications" class="min-w-[180px]">--}}
{{--                    <flux:select.option>Class A</flux:select.option>--}}
{{--                    <flux:select.option>Class B</flux:select.option>--}}
{{--                </flux:select>--}}

                <flux:button variant="subtle" icon="funnel" size="sm" wire:click="toggleLowStockOnly"
                        @class([
                        'mt-2',
                       '!bg-green-400 !text-white' => $lowStockOnly,
                    ])>
                    Low Stock Only
                </flux:button>

                {{--                <flux:badge color="zinc" variant="filled" class="px-3 py-1">--}}
                {{--                    {{ $filteredCount ?? '0' }} results--}}
                {{--                </flux:badge>--}}
{{--            </div>--}}
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column sortable>Product Name</flux:table.column>
                <flux:table.column sortable>Price</flux:table.column>
                <flux:table.column sortable>Stock Level</flux:table.column>
                <flux:table.column sortable>Unit</flux:table.column>
                <flux:table.column sortable>Category</flux:table.column>
                <flux:table.column>Subcategory</flux:table.column>
                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>

            @foreach($this->products as $product)
                <flux:table.row>
                    <flux:table.cell>
                        <div class="font-medium text-zinc-900">{{ $product->name }}</div>
                    </flux:table.cell>

                    <flux:table.cell>
                        <span class="font-semibold text-zinc-900">{{ $product->price }}</span>
                    </flux:table.cell>

                    <flux:table.cell>
                        <div class="flex items-center gap-2">
                            <flux:badge color="{{ $product->stock_level < 20 ? 'red' : 'green' }}" variant="subtle"
                                        class="font-semibold">
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
                        <span class="font-semibold text-zinc-900">{{ $product->unit}}</span>
                    </flux:table.cell>

                    <flux:table.cell>
                        <flux:badge color="primary" variant="subtle">{{ $product->category }}</flux:badge>
                    </flux:table.cell>

                    <flux:table.cell>
                        <flux:badge color="zinc" variant="subtle">{{ $product->subcategory }}</flux:badge>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach

            {{--                <flux:table.cell>--}}
            {{--                    <div class="flex items-center gap-1">--}}
            {{--                        <flux:button variant="ghost" size="sm" icon="eye" class="text-primary">--}}
            {{--                            View--}}
            {{--                        </flux:button>--}}
            {{--                        <flux:button variant="ghost" size="sm" icon="pencil" class="text-zinc-600">--}}
            {{--                            Edit--}}
            {{--                        </flux:button>--}}
            {{--                    </div>--}}
            {{--                </flux:table.cell>--}}

        </flux:table>
        <div class="p-6 border-t border-zinc-200">
            {{ $this->products->links(data: ['scrollTo' => false ]) }}
        </div>
    </flux:card>
</div>
