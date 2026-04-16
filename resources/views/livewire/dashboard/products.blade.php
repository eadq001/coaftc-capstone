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
                    <flux:text class="text-2xl font-semibold mt-2 text-red-600">{{ $lowStockCount ?? '0' }}</flux:text>
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
                    <flux:text
                            class="text-2xl font-semibold mt-2 text-primary">{{ $inventoryValue ?? '$0.00' }}</flux:text>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <flux:icon.currency-dollar class="w-6 h-6 text-primary"/>
                </div>
            </div>
        </flux:card>
    </div>

    <flux:card class="border border-zinc-300 rounded-lg shadow-sm bg-white overflow-hidden">
        <div class="p-6 border-b border-zinc-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1 max-w-md">
                    <flux:input
                            icon="magnifying-glass"
                            placeholder="Search by name, QR code, or category..."
                            class="w-full"
                    />
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

            <div class="flex flex-wrap items-center gap-3 mt-4">
                <flux:select placeholder="All Categories" class="min-w-[160px]">
                    <flux:select.option>Electronics</flux:select.option>
                    <flux:select.option>Clothing</flux:select.option>
                    <flux:select.option>Food & Beverages</flux:select.option>
                    <flux:select.option>Office Supplies</flux:select.option>
                </flux:select>

                <flux:select placeholder="All Classifications" class="min-w-[180px]">
                    <flux:select.option>Class A</flux:select.option>
                    <flux:select.option>Class B</flux:select.option>
                </flux:select>

                <flux:button variant="subtle" icon="funnel" size="sm">
                    Low Stock Only
                </flux:button>

                <flux:badge color="zinc" variant="filled" class="px-3 py-1">
                    {{ $filteredCount ?? '0' }} results
                </flux:badge>
            </div>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column sortable>QR Code</flux:table.column>
                <flux:table.column sortable>Product Name</flux:table.column>
                <flux:table.column sortable>Category</flux:table.column>
                <flux:table.column sortable>Classification</flux:table.column>
                <flux:table.column sortable>Stock Level</flux:table.column>
                <flux:table.column sortable>Unit Price</flux:table.column>
                <flux:table.column>Action</flux:table.column>
                <flux:table.column>Subcategory</flux:table.column>
            </flux:table.columns>

            <flux:table.row>
                <flux:table.cell>
                    <div class="font-mono text-xs bg-zinc-100 px-2 py-1 rounded">
                        QR-001234
                    </div>
                </flux:table.cell>
                <flux:table.cell>
                    <div class="font-medium text-zinc-900">Wireless Mouse</div>
                    <div class="text-xs text-zinc-500">Compact wireless mouse with USB receiver</div>
                </flux:table.cell>
                <flux:table.cell>
                    <flux:badge color="primary" variant="subtle">Electronics</flux:badge>
                </flux:table.cell>
                <flux:table.cell>
                    <flux:badge color="amber" variant="subtle">Class A</flux:badge>
                </flux:table.cell>
                <flux:table.cell>
                    <div class="flex items-center gap-2">
                        <flux:badge color="green" variant="subtle" class="font-semibold">
                            150
                        </flux:badge>
                        <flux:icon.check-circle class="w-4 h-4 text-green-600"/>
                    </div>
                </flux:table.cell>
                <flux:table.cell>
                    <span class="font-semibold text-zinc-900">$29.99</span>
                </flux:table.cell>
                <flux:table.cell>
                    <div class="flex items-center gap-1">
                        <flux:button variant="ghost" size="sm" icon="eye" class="text-primary">
                            View
                        </flux:button>
                        <flux:button variant="ghost" size="sm" icon="pencil" class="text-zinc-600">
                            Edit
                        </flux:button>
                    </div>
                </flux:table.cell>
            </flux:table.row>

            <flux:table.row>
                <flux:table.cell>
                    <div class="font-mono text-xs bg-zinc-100 px-2 py-1 rounded">
                        QR-001235
                    </div>
                </flux:table.cell>
                <flux:table.cell>
                    <div class="font-medium text-zinc-900">USB-C Cable</div>
                    <div class="text-xs text-zinc-500">Fast charging USB-C cable, 2m length</div>
                </flux:table.cell>
                <flux:table.cell>
                    <flux:badge color="primary" variant="subtle">Electronics</flux:badge>
                </flux:table.cell>
                <flux:table.cell>
                    <flux:badge color="zinc" variant="subtle">Class B</flux:badge>
                </flux:table.cell>
                <flux:table.cell>
                    <div class="flex items-center gap-2">
                        <flux:badge color="red" variant="subtle" class="font-semibold">
                            5
                        </flux:badge>
                        <flux:icon.exclamation-circle class="w-4 h-4 text-red-600"/>
                    </div>
                </flux:table.cell>
                <flux:table.cell>
                    <span class="font-semibold text-zinc-900">$12.99</span>
                </flux:table.cell>
                <flux:table.cell>
                    <div class="flex items-center gap-1">
                        <flux:button variant="ghost" size="sm" icon="eye" class="text-primary">
                            View
                        </flux:button>
                        <flux:button variant="ghost" size="sm" icon="pencil" class="text-zinc-600">
                            Edit
                        </flux:button>
                    </div>
                </flux:table.cell>
            </flux:table.row>
        </flux:table>

        <div class="p-6 border-t border-zinc-200 bg-zinc-50">
            <div class="flex items-center justify-between">
                <flux:text variant="secondary" size="sm">
                    Showing 1-4 of {{ $totalProducts ?? '12' }} products
                </flux:text>
                <div class="flex items-center gap-1">
                    <flux:button variant="ghost" size="sm" icon="chevron-left" class="text-zinc-400" disabled/>
                    <flux:button variant="primary" size="sm" class="w-8 h-8">1</flux:button>
                    <flux:button variant="ghost" size="sm" class="w-8 h-8">2</flux:button>
                    <flux:button variant="ghost" size="sm" class="w-8 h-8">3</flux:button>
                    <flux:button variant="ghost" size="sm" icon="chevron-right"/>
                </div>
            </div>
        </div>
    </flux:card>


    <flux:modal name="delete-product" class="max-w-md">
        <div>
            <flux:heading size="lg">Delete Product</flux:heading>
            <flux:text class="mt-2">This action cannot be undone.</flux:text>
        </div>

        <div class="flex items-center gap-4 p-4 bg-red-50 rounded-lg border border-red-200 mt-6">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <flux:icon.exclamation-triangle class="w-6 h-6 text-red-600"/>
            </div>
            <div>
                <flux:text class="font-semibold text-red-800">Are you sure you want to delete this product?</flux:text>
                <flux:text size="sm" class="text-red-600">"Wireless Mouse" will be permanently removed.</flux:text>
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-3">
                <flux:button variant="subtle">Cancel</flux:button>
                <flux:button variant="danger">Delete Product</flux:button>
            </div>
        </x-slot>
    </flux:modal>
</div>
