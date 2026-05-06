<?php

use App\Models\Product;
use App\QrGenerator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::dashboard', ['title' => 'Products QR'])]
class extends Component {
    use WithPagination;

    public string $searchText = '';

    public function updatedSearchText(): void
    {
        $this->resetPage('products-qr-page');
    }

    public function clearSearchText(): void
    {
        $this->reset('searchText');
        $this->resetPage('products-qr-page');
    }

    #[Computed]
    public function products()
    {
        return Product::query()
//            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
//            ->leftJoin('subcategories', 'subcategories.id', '=', 'products.subcategory_id')
//            ->select([
//                'products.id',
//                'products.name',
//                'categories.category_name as category_name',
//                'subcategories.subcategory_name as subcategory',
//            ])
            ->when($this->searchText !== '', function ($query) {
                $query->where('products.name', 'like', $this->searchText . '%');
            })
            ->orderByDesc('products.id')
            ->paginate(12, pageName: 'products-qr-page');
    }
};
?>

<div class="space-y-6">
    <div class="mb-6">
        <flux:heading size="xl" level="1">Product QR Codes</flux:heading>
        <flux:text class="mt-1 text-zinc-600">Print or scan product QR codes with their item details.</flux:text>
    </div>

    <flux:card class="overflow-hidden rounded-lg border border-zinc-300 bg-white shadow-sm">
        <div class="border-b border-zinc-200 p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1 max-w-md">
                    <flux:input
                            icon="magnifying-glass"
                            placeholder="Search by product name..."
                            wire:model.live.debounce.300ms="searchText"
                    />
                </div>

                <flux:button type="button"
                             class="hover:bg-green-300! disabled:hover:bg-0  border border-gray-200 rounded-lg transition-all cursor-pointer text-zinc-600 px-5 py-2"
                             wire:click="clearSearchText"
                             :disabled="$searchText === ''"
                >
                    Clear
                </flux:button>

            </div>
        </div>

        <div class="grid gap-6 p-6 sm:grid-cols-2 xl:grid-cols-4">
            @forelse($this->products as $product)
                <article
                        wire:key="product-qr-{{ $product->id }}"
                        class="flex min-h-[18rem] flex-col items-center rounded-xl border border-zinc-200 bg-zinc-50 p-6 text-center shadow-sm"
                >
                    <div class="rounded-lg bg-white p-4 shadow-sm">
                        <img
                                alt="QR code for {{ $product->name }}"

                                src="data:image/png;base64,{{ QrGenerator::generate((string) $product->id, 150) }}"
                        >
                    </div>

                    <div class="mt-1 flex w-full flex-1 flex-col justify-end gap-2 border-t border-dashed border-zinc-200 pt-4">
                        <p class="text-base font-semibold text-zinc-900">{{ $product->name }}</p>
                        <p class="text-sm text-zinc-600">{{ $product->category?->category_name }}</p>
                        <p class="text-sm text-zinc-500">{{ $product->subcategory?->subcategory_name }}</p>

                        @if($product->size)
                            <p class="text-sm font-medium text-zinc-700">{{ $product->size }}</p>
                        @endif
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-lg border border-dashed border-zinc-300 bg-zinc-50 p-10 text-center text-zinc-500">
                    No products found.
                </div>
            @endforelse
        </div>

        @if($this->products->hasPages())
            <div class="border-t border-zinc-200 p-6">
                {{ $this->products->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </flux:card>
</div>
