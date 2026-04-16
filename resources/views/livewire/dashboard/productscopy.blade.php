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
                    <flux:icon.cube class="w-6 h-6 text-primary" />
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
                    <flux:icon.exclamation-triangle class="w-6 h-6 text-red-600" />
                </div>
            </div>
        </flux:card>

        <flux:card class="border border-zinc-300 border-t-4 border-t-primary rounded-lg shadow-sm bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="sm" class="text-zinc-800">Total Inventory Value</flux:heading>
                    <flux:text class="text-2xl font-semibold mt-2 text-primary">{{ $inventoryValue ?? '$0.00' }}</flux:text>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <flux:icon.currency-dollar class="w-6 h-6 text-primary" />
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
                <div class="flex flex-wrap items-center gap-2">
                    <flux:button icon="plus" variant="primary">
                        Add Product
                    </flux:button>
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
                <flux:table.column>Actions</flux:table.column>
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
                        <flux:icon.check-circle class="w-4 h-4 text-green-600" />
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
                        <flux:button variant="ghost" size="sm" icon="trash" class="text-red-600 hover:text-red-700">
                            Delete
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
                        <flux:icon.exclamation-circle class="w-4 h-4 text-red-600" />
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
                        <flux:button variant="ghost" size="sm" icon="trash" class="text-red-600 hover:text-red-700">
                            Delete
                        </flux:button>
                    </div>
                </flux:table.cell>
            </flux:table.row>

            <flux:table.row>
                <flux:table.cell>
                    <div class="font-mono text-xs bg-zinc-100 px-2 py-1 rounded">
                        QR-001236
                    </div>
                </flux:table.cell>
                <flux:table.cell>
                    <div class="font-medium text-zinc-900">A4 Bond Paper</div>
                    <div class="text-xs text-zinc-500">High quality white bond paper, 500 sheets</div>
                </flux:table.cell>
                <flux:table.cell>
                    <flux:badge color="sky" variant="subtle">Office Supplies</flux:badge>
                </flux:table.cell>
                <flux:table.cell>
                    <flux:badge color="amber" variant="subtle">Class A</flux:badge>
                </flux:table.cell>
                <flux:table.cell>
                    <div class="flex items-center gap-2">
                        <flux:badge color="green" variant="subtle" class="font-semibold">
                            89
                        </flux:badge>
                        <flux:icon.check-circle class="w-4 h-4 text-green-600" />
                    </div>
                </flux:table.cell>
                <flux:table.cell>
                    <span class="font-semibold text-zinc-900">$8.50</span>
                </flux:table.cell>
                <flux:table.cell>
                    <div class="flex items-center gap-1">
                        <flux:button variant="ghost" size="sm" icon="eye" class="text-primary">
                            View
                        </flux:button>
                        <flux:button variant="ghost" size="sm" icon="pencil" class="text-zinc-600">
                            Edit
                        </flux:button>
                        <flux:button variant="ghost" size="sm" icon="trash" class="text-red-600 hover:text-red-700">
                            Delete
                        </flux:button>
                    </div>
                </flux:table.cell>
            </flux:table.row>

            <flux:table.row>
                <flux:table.cell>
                    <div class="font-mono text-xs bg-zinc-100 px-2 py-1 rounded">
                        QR-001237
                    </div>
                </flux:table.cell>
                <flux:table.cell>
                    <div class="font-medium text-zinc-900">Ballpoint Pens</div>
                    <div class="text-xs text-zinc-500">Blue ink ballpoint pens, pack of 12</div>
                </flux:table.cell>
                <flux:table.cell>
                    <flux:badge color="sky" variant="subtle">Office Supplies</flux:badge>
                </flux:table.cell>
                <flux:table.cell>
                    <flux:badge color="zinc" variant="subtle">Class B</flux:badge>
                </flux:table.cell>
                <flux:table.cell>
                    <div class="flex items-center gap-2">
                        <flux:badge color="red" variant="subtle" class="font-semibold">
                            3
                        </flux:badge>
                        <flux:icon.exclamation-circle class="w-4 h-4 text-red-600" />
                    </div>
                </flux:table.cell>
                <flux:table.cell>
                    <span class="font-semibold text-zinc-900">$4.99</span>
                </flux:table.cell>
                <flux:table.cell>
                    <div class="flex items-center gap-1">
                        <flux:button variant="ghost" size="sm" icon="eye" class="text-primary">
                            View
                        </flux:button>
                        <flux:button variant="ghost" size="sm" icon="pencil" class="text-zinc-600">
                            Edit
                        </flux:button>
                        <flux:button variant="ghost" size="sm" icon="trash" class="text-red-600 hover:text-red-700">
                            Delete
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
                    <flux:button variant="ghost" size="sm" icon="chevron-left" class="text-zinc-400" disabled />
                    <flux:button variant="primary" size="sm" class="w-8 h-8">1</flux:button>
                    <flux:button variant="ghost" size="sm" class="w-8 h-8">2</flux:button>
                    <flux:button variant="ghost" size="sm" class="w-8 h-8">3</flux:button>
                    <flux:button variant="ghost" size="sm" icon="chevron-right" />
                </div>
            </div>
        </div>
    </flux:card>

    <flux:modal name="add-product" class="max-w-2xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Add New Product</flux:heading>
                <flux:text class="mt-2">Fill in the details below to add a new product to your inventory.</flux:text>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Product Name</flux:label>
                    <flux:input placeholder="Enter product name" />
                </flux:field>

                <flux:field>
                    <flux:label>QR Code</flux:label>
                    <flux:input value="QR-001238" readonly class="bg-zinc-50" />
                </flux:field>
            </div>

            <flux:field>
                <flux:label>Description</flux:label>
                <flux:textarea placeholder="Enter product description" rows="3" />
            </flux:field>

            <div class="grid gap-6 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Category</flux:label>
                    <flux:select placeholder="Select category">
                        <flux:select.option>Electronics</flux:select.option>
                        <flux:select.option>Clothing</flux:select.option>
                        <flux:select.option>Food & Beverages</flux:select.option>
                        <flux:select.option>Office Supplies</flux:select.option>
                        <flux:select.option>Home & Garden</flux:select.option>
                        <flux:select.option>Sports & Outdoors</flux:select.option>
                    </flux:select>
                </flux:field>

                <flux:field>
                    <flux:label>Classification</flux:label>
                    <flux:select placeholder="Select classification">
                        <flux:select.option>Class A</flux:select.option>
                        <flux:select.option>Class B</flux:select.option>
                    </flux:select>
                </flux:field>
            </div>

            <div class="grid gap-6 sm:grid-cols-3">
                <flux:field>
                    <flux:label>Unit Price</flux:label>
                    <flux:input type="number" step="0.01" placeholder="0.00" />
                </flux:field>

                <flux:field>
                    <flux:label>Initial Stock</flux:label>
                    <flux:input type="number" placeholder="0" />
                </flux:field>

                <flux:field>
                    <flux:label>Low Stock Threshold</flux:label>
                    <flux:input type="number" placeholder="10" />
                </flux:field>
            </div>

            <flux:field>
                <flux:label>Auto-generated QR Code Preview</flux:label>
                <div class="flex items-center gap-4 p-4 bg-zinc-50 rounded-lg border border-zinc-200">
                    <div class="w-24 h-24 bg-white rounded-lg border-2 border-dashed border-zinc-300 flex items-center justify-center">
                        <flux:icon.qr-code class="w-12 h-12 text-zinc-400" />
                    </div>
                    <div>
                        <flux:text class="font-mono text-sm text-zinc-600">QR-001238</flux:text>
                        <flux:text size="xs" class="text-zinc-400">Will be generated on save</flux:text>
                    </div>
                </div>
            </flux:field>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-3">
                <flux:button variant="subtle">Cancel</flux:button>
                <flux:button variant="primary">Save Product</flux:button>
            </div>
        </x-slot>
    </flux:modal>

    <flux:modal name="view-product" class="max-w-3xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Product Details</flux:heading>
                <flux:text class="mt-2">View and manage product information and stock history.</flux:text>
            </div>

            <div class="flex flex-col sm:flex-row gap-6">
                <div class="sm:w-1/3">
                    <flux:field>
                        <flux:label>QR Code</flux:label>
                        <div class="p-4 bg-zinc-50 rounded-lg border border-zinc-200 flex flex-col items-center">
                            <div class="w-32 h-32 bg-white rounded-lg border-2 border-dashed border-zinc-300 flex items-center justify-center mb-3">
                                <flux:icon.qr-code class="w-20 h-20 text-zinc-600" />
                            </div>
                            <flux:text class="font-mono text-sm font-semibold text-zinc-800">QR-001234</flux:text>
                            <flux:button variant="ghost" size="xs" icon="arrow-down-tray" class="mt-2">
                                Download
                            </flux:button>
                        </div>
                    </flux:field>
                </div>

                <div class="sm:w-2/3 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <flux:field>
                            <flux:label>Product Name</flux:label>
                            <flux:input value="Wireless Mouse" readonly class="bg-zinc-50" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Category</flux:label>
                            <flux:input value="Electronics" readonly class="bg-zinc-50" />
                        </flux:field>
                    </div>

                    <flux:field>
                        <flux:label>Description</flux:label>
                        <flux:textarea value="Compact wireless mouse with USB receiver. Features ergonomic design and 12-month battery life." readonly class="bg-zinc-50" rows="2" />
                    </flux:field>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <flux:field>
                            <flux:label>Classification</flux:label>
                            <flux:badge color="amber" variant="subtle" class="w-fit">Class A</flux:badge>
                        </flux:field>

                        <flux:field>
                            <flux:label>Unit Price</flux:label>
                            <flux:input value="$29.99" readonly class="bg-zinc-50" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Low Stock Threshold</flux:label>
                            <flux:input value="10" readonly class="bg-zinc-50" />
                        </flux:field>
                    </div>
                </div>
            </div>

            <flux:separator variant="subtle" />

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                    <flux:heading size="xs" class="text-green-800">Current Stock</flux:heading>
                    <flux:text size="lg" class="font-bold text-green-600 mt-1">150</flux:text>
                </div>

                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <flux:heading size="xs" class="text-blue-800">Stock Value</flux:heading>
                    <flux:text size="lg" class="font-bold text-blue-600 mt-1">$4,498.50</flux:text>
                </div>

                <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                    <flux:heading size="xs" class="text-green-800">Status</flux:heading>
                    <flux:badge color="green" variant="subtle" class="w-fit mt-1">In Stock</flux:badge>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-3">
                    <flux:heading size="sm" class="text-zinc-800">Stock History</flux:heading>
                    <div class="flex gap-2">
                        <flux:button variant="subtle" size="xs" icon="arrow-down-tray">
                            Export
                        </flux:button>
                    </div>
                </div>

                <div class="rounded-lg border border-zinc-200 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-zinc-50 border-b border-zinc-200">
                            <tr>
                                <th class="text-left px-4 py-3 font-medium text-zinc-600">Date</th>
                                <th class="text-left px-4 py-3 font-medium text-zinc-600">Type</th>
                                <th class="text-left px-4 py-3 font-medium text-zinc-600">Quantity</th>
                                <th class="text-left px-4 py-3 font-medium text-zinc-600">Reference</th>
                                <th class="text-left px-4 py-3 font-medium text-zinc-600">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            <tr class="hover:bg-zinc-50">
                                <td class="px-4 py-3 text-zinc-600">2026-04-08</td>
                                <td class="px-4 py-3">
                                    <flux:badge color="green" variant="subtle" size="sm">Stock In</flux:badge>
                                </td>
                                <td class="px-4 py-3 text-green-600 font-semibold">+50</td>
                                <td class="px-4 py-3 text-zinc-600 font-mono text-xs">PO-2026-0045</td>
                                <td class="px-4 py-3 text-zinc-500">Restock from supplier</td>
                            </tr>
                            <tr class="hover:bg-zinc-50">
                                <td class="px-4 py-3 text-zinc-600">2026-04-05</td>
                                <td class="px-4 py-3">
                                    <flux:badge color="red" variant="subtle" size="sm">Stock Out</flux:badge>
                                </td>
                                <td class="px-4 py-3 text-red-600 font-semibold">-15</td>
                                <td class="px-4 py-3 text-zinc-600 font-mono text-xs">TXN-2026-0892</td>
                                <td class="px-4 py-3 text-zinc-500">Sale transaction</td>
                            </tr>
                            <tr class="hover:bg-zinc-50">
                                <td class="px-4 py-3 text-zinc-600">2026-04-01</td>
                                <td class="px-4 py-3">
                                    <flux:badge color="red" variant="subtle" size="sm">Stock Out</flux:badge>
                                </td>
                                <td class="px-4 py-3 text-red-600 font-semibold">-10</td>
                                <td class="px-4 py-3 text-zinc-600 font-mono text-xs">TXN-2026-0783</td>
                                <td class="px-4 py-3 text-zinc-500">Sale transaction</td>
                            </tr>
                            <tr class="hover:bg-zinc-50">
                                <td class="px-4 py-3 text-zinc-600">2026-03-28</td>
                                <td class="px-4 py-3">
                                    <flux:badge color="blue" variant="subtle" size="sm">Adjustment</flux:badge>
                                </td>
                                <td class="px-4 py-3 text-blue-600 font-semibold">+5</td>
                                <td class="px-4 py-3 text-zinc-600 font-mono text-xs">ADJ-2026-0012</td>
                                <td class="px-4 py-3 text-zinc-500">Inventory correction</td>
                            </tr>
                            <tr class="hover:bg-zinc-50">
                                <td class="px-4 py-3 text-zinc-600">2026-03-25</td>
                                <td class="px-4 py-3">
                                    <flux:badge color="green" variant="subtle" size="sm">Stock In</flux:badge>
                                </td>
                                <td class="px-4 py-3 text-green-600 font-semibold">+100</td>
                                <td class="px-4 py-3 text-zinc-600 font-mono text-xs">PO-2026-0038</td>
                                <td class="px-4 py-3 text-zinc-500">Initial stock</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 flex justify-end">
                    <div class="flex items-center gap-1">
                        <flux:button variant="ghost" size="sm" icon="chevron-left" class="text-zinc-400" disabled />
                        <flux:button variant="primary" size="sm" class="w-8 h-8">1</flux:button>
                        <flux:button variant="ghost" size="sm" icon="chevron-right" />
                    </div>
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-between w-full">
                <div class="flex gap-2">
                    <flux:button variant="subtle" icon="arrow-down-tray">Print QR</flux:button>
                </div>
                <div class="flex gap-3">
                    <flux:button variant="subtle">Close</flux:button>
                    <flux:button variant="primary" icon="pencil">Edit Product</flux:button>
                </div>
            </div>
        </x-slot>
    </flux:modal>

    <flux:modal name="delete-product" class="max-w-md">
        <div>
            <flux:heading size="lg">Delete Product</flux:heading>
            <flux:text class="mt-2">This action cannot be undone.</flux:text>
        </div>

        <div class="flex items-center gap-4 p-4 bg-red-50 rounded-lg border border-red-200 mt-6">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <flux:icon.exclamation-triangle class="w-6 h-6 text-red-600" />
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
