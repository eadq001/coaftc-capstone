<?php

use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use withPagination;

    #[Computed]
    public function products()
    {
        return Product::all();
    }
};
?>

<div>

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
</div>