<div>
    <x-delete-restore-message message="Successfully restored the product" event="restore-success"/>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <flux:heading size="xl" level="1">Archive</flux:heading>
            <flux:text class="mt-1 text-zinc-600">You can restore the deleted products.</flux:text>
        </div>
    </div>

    <flux:card class="border border-zinc-300 rounded-lg shadow-sm bg-white overflow-hidden">
        <div class="p-6 border-b border-zinc-200">

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
                        <flux:modal.trigger name="{{ $product->id }}">
                            <flux:button size="sm" class="bg-green-200! hover:bg-green-400! cursor-pointer transition-all">
                                Restore
                            </flux:button>
                        </flux:modal.trigger>

                        <flux:modal name="{{ $product->id }}" class="min-w-[22rem]">
                            <div class="space-y-6 text-left">
                                <div>
                                    <flux:heading size="lg">Restore product?</flux:heading>
                                    <flux:text class="mt-2">
                                        Do you want to restore "{{ $product->id }}" to the products inventory
                                    </flux:text>
                                </div>

                                <div class="flex gap-2">
                                    <flux:spacer/>

                                    <flux:modal.close>
                                        <flux:button variant="ghost">No</flux:button>
                                    </flux:modal.close>

                                    <flux:modal.close>
                                        <flux:button variant="primary" color="emerald"
                                                     wire:click.stop="restoreProduct({{$product->id}})">
                                            Yes
                                        </flux:button>
                                    </flux:modal.close>
                                </div>
                            </div>
                        </flux:modal>
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
</div>